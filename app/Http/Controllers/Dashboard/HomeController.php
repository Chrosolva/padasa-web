<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function getOverview(Request $request)
    {
        /*
         * Default date:
         * Use yesterday because LHP data is normally complete for yesterday.
         *
         * Change Carbon::yesterday() to Carbon::today()
         * when you want the default filter to use today.
         */
        $tanggal = $request->get(
            'tanggal',
            Carbon::yesterday()->format('Y-m-d')
        );

        $siteId = $request->get('site_id', '9999');

        $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'site_id' => 'nullable|in:9999,2200,2300,2400,2500,3200,5200',
        ]);

        /*
         * For SEMUA:
         * Stored procedure receives NULL.
         *
         * For individual PMKS:
         * Stored procedure receives the selected SITE_ID.
         */
        $siteIdParameter = $siteId === '9999'
            ? null
            : (int) $siteId;

        $sqlDate = Carbon::parse($tanggal)->format('Ymd');

        try {
            $result = DB::select(
                "
                EXEC PUBDB.Produksi.Overview_PMKS_1_Harian_DashBoard_GPT
                    @startdate = ?,
                    @enddate   = ?,
                    @site_id   = ?
                ",
                [
                    $sqlDate,
                    $sqlDate,
                    $siteIdParameter,
                ]
            );

            /*
             * When site_id is NULL, the stored procedure returns:
             * - every PMKS
             * - one final SEMUA row with SITE_ID = 9999
             *
             * The overview only needs one selected row.
             */
            if ($siteId === '9999') {
                $overview = collect($result)->first(function ($row) {
                    return (string) $row->SITE_ID === '9999';
                });
            } else {
                $overview = collect($result)->first(function ($row) use ($siteId) {
                    return (string) $row->SITE_ID === (string) $siteId;
                });
            }

            /*
             * Fallback when no result is found.
             */
            if (!$overview) {
                $overview = $this->emptyOverview($tanggal, $siteId);
            }

            $overview = $this->normalizeOverview($overview);

            $sites = $this->getSiteOptions();

            return view('dashboard.overview', compact(
                'overview',
                'tanggal',
                'siteId',
                'sites'
            ));
        } catch (\Exception $exception) {
            report($exception);

            $overview = $this->normalizeOverview(
                $this->emptyOverview($tanggal, $siteId)
            );

            $sites = $this->getSiteOptions();

            return view('dashboard.overview', compact(
                'overview',
                'tanggal',
                'siteId',
                'sites'
            ))->with(
                'error',
                'Data overview gagal dimuat: ' . $exception->getMessage()
            );
        }
    }

    private function getSiteOptions()
    {
        return [
            '9999' => 'SEMUA PMKS',
            '2200' => 'TELDA',
            '2300' => 'KALSA',
            '2400' => 'KALDA',
            '2500' => 'KOKAR',
            '3200' => 'RICKO',
            '5200' => 'PASER',
        ];
    }

    private function normalizeOverview($row)
    {
        return (object) [
            'COMP_ID'          => $row->COMP_ID ?? null,
            'SITE_ID'          => $row->SITE_ID ?? null,
            'PMKS'             => $row->PMKS ?? '-',
            'TGLLHP'           => $row->TGLLHP ?? null,

            'TBSOLAH'          => $this->toNumber($row->TBSOLAH ?? 0),

            'PRODUKSICPO'      => $this->toNumber($row->PRODUKSICPO ?? 0),
            'TARGET_CPO_HARIAN'=> $this->toNumber($row->TARGET_CPO_HARIAN ?? 0),
            'PENCAPAIANCPO'    => $this->toNumber($row->PENCAPAIANCPO ?? 0),

            'PRODUKSIPK'       => $this->toNumber($row->PRODUKSIPK ?? 0),
            'TARGET_PK_HARIAN' => $this->toNumber($row->TARGET_PK_HARIAN ?? 0),
            'PENCAPAIANPK'     => $this->toNumber($row->PENCAPAIANPK ?? 0),

            'OER'              => $this->toNumber($row->OER ?? 0),
            'TARGET_OER'       => $this->toNumber($row->TARGET_OER ?? 0),
            'PENCAPAIAN_OER'   => $this->toNumber($row->PENCAPAIAN_OER ?? 0),

            'KER'              => $this->toNumber($row->KER ?? 0),
            'TARGET_KER'       => $this->toNumber($row->TARGET_KER ?? 0),
            'PENCAPAIAN_KER'   => $this->toNumber($row->PENCAPAIAN_KER ?? 0),

            'JAMOLAH'          => $this->toNumber($row->JAMOLAH ?? 0),
            'BREAKDOWN'        => $this->toNumber($row->BREAKDOWN ?? 0),
        ];
    }

    private function toNumber($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        /*
         * Handles both:
         * 20.57
         * 20,57
         */
        return (float) str_replace(',', '.', $value);
    }

    private function emptyOverview($tanggal, $siteId)
    {
        $sites = $this->getSiteOptions();

        return (object) [
            'COMP_ID'           => null,
            'SITE_ID'           => $siteId,
            'PMKS'              => $sites[$siteId] ?? '-',
            'TGLLHP'            => $tanggal,
            'TBSOLAH'           => 0,
            'PRODUKSICPO'       => 0,
            'TARGET_CPO_HARIAN' => 0,
            'PENCAPAIANCPO'     => 0,
            'PRODUKSIPK'        => 0,
            'TARGET_PK_HARIAN'  => 0,
            'PENCAPAIANPK'      => 0,
            'OER'               => 0,
            'TARGET_OER'        => 0,
            'PENCAPAIAN_OER'    => 0,
            'KER'               => 0,
            'TARGET_KER'        => 0,
            'PENCAPAIAN_KER'    => 0,
            'JAMOLAH'           => 0,
            'BREAKDOWN'         => 0,
        ];
    }
}