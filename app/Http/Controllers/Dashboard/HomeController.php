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
        $request->validate([
            'tanggal' => 'nullable|date_format:Y-m-d',
            'site_id' => 'nullable|in:9999,2200,2300,2400,2500,3200,5200',
        ]);

        $tanggal = $request->filled('tanggal')
            ? $request->input('tanggal')
            : Carbon::yesterday()->format('Y-m-d');

        $siteId = $request->filled('site_id')
            ? (string) $request->input('site_id')
            : '9999';

        $sqlDate = Carbon::parse($tanggal)->format('Ymd');

        /*
        * Harus berada sebelum try supaya tetap tersedia
        * saat terjadi exception.
        */
        $sites = $this->getSiteOptions();

        try {
            $result = DB::select(
                "
                SET NOCOUNT ON;

                EXEC [PUBDB].[Produksi].[Overview_PMKS_1_Harian_DashBoard_GPT]
                    @startdate = ?,
                    @enddate   = ?,
                    @site_id   = ?;
                ",
                [
                    $sqlDate,
                    $sqlDate,
                    null,
                ]
            );

            $overviewBySite = collect($result)
                ->map(function ($row) {
                    return $this->normalizeOverview($row);
                })
                ->keyBy(function ($row) {
                    return (string) $row->SITE_ID;
                });

            $overview = $overviewBySite->get($siteId);

            if (!$overview) {
                $overview = $overviewBySite->get('9999');
            }

            if (!$overview) {
                $overview = $this->normalizeOverview(
                    $this->emptyOverview($tanggal, $siteId)
                );
            }

            return view('dashboard.overview', [
                'overview' => $overview,
                'overviewBySite' => $overviewBySite,
                'tanggal' => $tanggal,
                'siteId' => $siteId,
                'sites' => $sites,
                'queryError' => null,
            ]);
        } catch (\Throwable $exception) {
            report($exception);

            $emptyOverview = $this->normalizeOverview(
                $this->emptyOverview($tanggal, $siteId)
            );

            return view('dashboard.overview', [
                'overview' => $emptyOverview,
                'overviewBySite' => collect([
                    $siteId => $emptyOverview,
                ]),
                'tanggal' => $tanggal,
                'siteId' => $siteId,
                'sites' => $sites,
                'queryError' =>
                    'Gagal mengambil data overview: ' .
                    $exception->getMessage(),
            ]);
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