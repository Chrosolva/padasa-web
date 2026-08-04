<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class HPTController extends Controller
{
    public function getRekapHPT(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses('HPT', 'Rekap HPT') == false
        ) {
            abort('403-dashboard');
        }

        $endDate = $request->get(
            'endDate',
            Carbon::yesterday()->format('Y-m-d')
        );

        try {
            $request->validate([
                'endDate' => 'nullable|date_format:Y-m-d',
            ]);

            $result = DB::select(
                "
                SET NOCOUNT ON;

                EXEC [PUBDB].[rnd].[rptdetailsensus3_rekap]
                    @endDate = ?
                ",
                [$endDate]
            );

            $rekapHPT = collect($result)
                ->filter(function ($row) {
                    return strtoupper(
                        trim($row->KEBUN ?? '')
                    ) !== 'TOTAL';
                })
                ->map(function ($row) {
                    $jumlahPkk = $this->toNumeric(
                        $row->{'JUMLAH PKK'} ?? 0
                    );

                    $pkkTerserang = $this->toNumeric(
                        $row->{'PKK TERSERANG'} ?? 0
                    );

                    $persenTerserang = $jumlahPkk > 0
                        ? ($pkkTerserang / $jumlahPkk) * 100
                        : 0;

                    return [
                        'kebun' => trim($row->KEBUN ?? ''),
                        'jumlah_pkk' => $jumlahPkk,
                        'pkk_terserang' => $pkkTerserang,
                        'persen_terserang' => round(
                            $persenTerserang,
                            2
                        ),
                    ];
                })
                ->values();

            return view('dashboard.hpt.RekapHPT', [
                'rekapHPT' => $rekapHPT,
                'endDate' => $endDate,
            ]);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal mengambil data Rekap HPT: ' .
                    $e->getMessage()
                );
        }
    }

    public function getDetailHPT(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses('HPT', 'Detail HPT') == false
        ) {
            abort('403-dashboard');
        }

        /*
         * Sama seperti Rekap HPT:
         * jika parameter belum ada, pakai nilai default.
         */
        $endDate = $request->get(
            'endDate',
            Carbon::yesterday()->format('Y-m-d')
        );

        $siteId = $request->get(
            'site_id',
            '2200'
        );

        $daftarKebun = [
            '2200' => 'TELDA',
            '2300' => 'KALSA',
            '2400' => 'KALDA',
            '2500' => 'KOKAR',
            '2600' => 'RICKO',
            '2700' => 'PASER',
        ];

        try {
            /*
             * Jangan gunakan required karena ketika halaman pertama
             * dibuka parameter memang belum terdapat di URL.
             */
            $request->validate([
                'endDate' => 'nullable|date_format:Y-m-d',
                'site_id' => 'nullable|integer',
            ]);

            $result = DB::select(
                "
                SET NOCOUNT ON;

                EXEC [PUBDB].[rnd].[rptdetailsensus3]
                    @endDate = ?,
                    @site_id = ?;
                ",
                [
                    $endDate,
                    (int) $siteId,
                ]
            );

            $detailHPT = collect($result)
                ->map(function ($row) {
                    $jumlahPokokSakit = $this->toNumeric(
                        $row->jumlah_pokok_sakit ?? 0
                    );

                    $totalPokok = $this->toNumeric(
                        $row->total_pokok ?? 0
                    );

                    $persenSakit = $totalPokok > 0
                        ? ($jumlahPokokSakit / $totalPokok) * 100
                        : 0;

                    return [
                        'kodesite' => trim(
                            $row->kodesite ?? ''
                        ),

                        'jumlah_pokok_sakit' =>
                            $jumlahPokokSakit,

                        'total_pokok' =>
                            $totalPokok,

                        'persen_sakit' => round(
                            $persenSakit,
                            2
                        ),

                        'tglakhir' => !empty($row->tglakhir)
                            ? Carbon::parse(
                                $row->tglakhir
                            )->format('Y-m-d')
                            : null,
                    ];
                })
                ->values();

            return view('dashboard.hpt.DetailHPT', [
                'detailHPT' => $detailHPT,
                'endDate' => $endDate,
                'siteId' => (string) $siteId,
                'daftarKebun' => $daftarKebun,
            ]);
        } catch (\Exception $e) {
            /*
             * Tetap tampilkan halaman Detail HPT.
             * Jangan return back(), karena dapat kembali ke Rekap HPT.
             */
            return view('dashboard.hpt.DetailHPT', [
                'detailHPT' => collect([]),
                'endDate' => $endDate,
                'siteId' => (string) $siteId,
                'daftarKebun' => $daftarKebun,
                'queryError' =>
                    'Gagal mengambil data Detail HPT: ' .
                    $e->getMessage(),
            ]);
        }
    }

    private function toNumeric($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = trim((string) $value);

        /*
         * Format Indonesia:
         * 3,37 menjadi 3.37
         * 1.234,56 menjadi 1234.56
         */
        if (strpos($value, ',') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value)
            ? (float) $value
            : 0;
    }
}