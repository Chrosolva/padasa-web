<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DevController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Biaya DEV
    |--------------------------------------------------------------------------
    |
    | Untuk sementara merupakan copy dari Produksi CPO.
    |
    */

    public function getBiaya(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses(
                'Dev',
                'Main'
            ) == false
        ) {
            abort('403-dashboard');
        }

        $tahun = (int) $request->get(
            'tahun',
            date('Y')
        );

        $bulan = (int) $request->get(
            'bulan',
            date('n')
        );

        $selectedSites =
            $this->normalizeSelectedSites(
                $request->get(
                    'site_id',
                    []
                )
            );

        $siteParameter =
            count($selectedSites) > 0
                ? implode(
                    ',',
                    $selectedSites
                )
                : null;


        /*
        |--------------------------------------------------------------------------
        | Data Biaya Mill
        |--------------------------------------------------------------------------
        */

        $dataRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.[web].[biaya_kebun]
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                $tahun,
                $bulan,
                $siteParameter
            ]
        );


        $dataRaw2 = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiTBS_Bulanan_Budget_YTD_DASHBOARD_OPTZ3
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                $tahun,
                $bulan,
                $siteParameter
            ]
        );
        

        $totalRaw = count($dataRaw);
        $dataRaw2Array = json_decode(json_encode($dataRaw2),true);

        for ($i = 0; $i < $totalRaw; $i++) {
            $indexRaw2 = floor($i / 5);

            if (isset($dataRaw2[$indexRaw2])) {
                $rowRaw2 = $dataRaw2Array[$indexRaw2];
                 $nilaiKolomRaw2 = array_values($rowRaw2)[2];

                $dataRaw[$i]->ytd_tahun_lalu = $dataRaw[$i]->ytd_tahun_lalu / (int)array_values($rowRaw2)[2];
                $dataRaw[$i]->aktual_bulan_ini = $dataRaw[$i]->aktual_bulan_ini / (int)array_values($rowRaw2)[3];
                $dataRaw[$i]->aktual_ytd = $dataRaw[$i]->aktual_ytd / (int)array_values($rowRaw2)[4];
                $dataRaw[$i]->budget_bulan_ini = $dataRaw[$i]->budget_bulan_ini / (int)array_values($rowRaw2)[5];  
                $dataRaw[$i]->budget_ytd = $dataRaw[$i]->budget_ytd / (int)array_values($rowRaw2)[6];  
                $dataRaw[$i]->budget_all = $dataRaw[$i]->budget_all / (int)array_values($rowRaw2)[7];  

                $dataRaw[$i]->var_ytd_tahun_lalu = 0; 
                $dataRaw[$i]->var_bulan_ini = 0 ;
                $dataRaw[$i]->var_ytd = 0;
                $dataRaw[$i]->ach = 0; 

                if($dataRaw[$i]->ytd_tahun_lalu>0)
                    $dataRaw[$i]->var_ytd_tahun_lalu = $dataRaw[$i]->aktual_ytd / $dataRaw[$i]->ytd_tahun_lalu *100;  
                if($dataRaw[$i]->budget_bulan_ini>0)
                    $dataRaw[$i]->var_bulan_ini = $dataRaw[$i]->aktual_bulan_ini  / $dataRaw[$i]->budget_bulan_ini *100;  
                if($dataRaw[$i]->budget_ytd>0)
                    $dataRaw[$i]->var_ytd = $dataRaw[$i]->aktual_ytd / $dataRaw[$i]->budget_ytd *100;  
                if($dataRaw[$i]->budget_all>0)
                    $dataRaw[$i]->ach = $dataRaw[$i]->aktual_ytd / $dataRaw[$i]->budget_all *100;  

            }
        }

        $dataRaw4 = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_3_PT_KEBUN_AFDELING_AR
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                $tahun,
                $bulan,
                $siteParameter
            ]
        );
        $dataRaw4Array = json_decode(json_encode($dataRaw4),true);


        $dataRaw5 = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_3_PT_KEBUN_AFDELING_AR
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                (int)$tahun-1,
                $bulan,
                $siteParameter
            ]
        );
        $dataRaw5Array = json_decode(json_encode($dataRaw5),true);

        $dataRaw3 = array_map(fn($item) => clone $item, $dataRaw);

        for ($i = 0; $i < $totalRaw; $i++) {
            $indexRaw2 = floor($i / 5);

            if (isset($dataRaw4[$indexRaw2])) {
                $rowRaw2 = $dataRaw4Array[$indexRaw2];       
                $rowRaw1 = $dataRaw5Array[$indexRaw2];               
                $dataRaw3[$i]->ytd_tahun_lalu = $dataRaw3[$i]->ytd_tahun_lalu / (float)array_values($rowRaw1)[2] ;
                $dataRaw3[$i]->aktual_bulan_ini = $dataRaw3[$i]->aktual_bulan_ini / (int)array_values($rowRaw2)[2] ; 
                $dataRaw3[$i]->aktual_ytd = $dataRaw3[$i]->aktual_ytd / (int)array_values($rowRaw2)[2] ; 
                $dataRaw3[$i]->budget_bulan_ini = $dataRaw3[$i]->budget_bulan_ini / (float)array_values($rowRaw2)[2]; 
                $dataRaw3[$i]->budget_ytd = $dataRaw3[$i]->budget_ytd / (int)array_values($rowRaw2)[2];
                $dataRaw3[$i]->budget_all = $dataRaw3[$i]->budget_all / (int)array_values($rowRaw2)[2]; 


            }
        }


        /*
        |--------------------------------------------------------------------------
        | Mapping Data
        |--------------------------------------------------------------------------
        */

        $dataBiaya =
            collect($dataRaw)
                ->map(function ($row) {

                    return [
                        'NO' =>
                            (int) (
                                $row->no
                                ?? 0
                            ),

                        'COMP_ID' =>
                            $row->comp_id
                            ?? null,

                        'SITE_ID' =>
                            $row->site_id
                            ?? null,

                        'BIAYA' =>
                            strtoupper(
                                trim(
                                    $row->biaya
                                    ?? ''
                                )
                            ),

                        'TAHUN' =>
                            (int) (
                                $row->tahun
                                ?? 0
                            ),

                        'BULAN' =>
                            (int) (
                                $row->bulan
                                ?? 0
                            ),

                        'YTD_TAHUN_LALU' =>
                            (float) (
                                $row->ytd_tahun_lalu
                                ?? 0
                            ),

                        'VAR_YTD_TAHUN_LALU' =>
                            (float) (
                                $row->var_ytd_tahun_lalu
                                ?? 0
                            ),

                        'AKTUAL_BULAN_INI' =>
                            (float) (
                                $row->aktual_bulan_ini
                                ?? 0
                            ),

                        'BUDGET_BULAN_INI' =>
                            (float) (
                                $row->budget_bulan_ini
                                ?? 0
                            ),

                        'VAR_BULAN_INI' =>
                            (float) (
                                $row->var_bulan_ini
                                ?? 0
                            ),

                        'AKTUAL_YTD' =>
                            (float) (
                                $row->aktual_ytd
                                ?? 0
                            ),

                        'BUDGET_YTD' =>
                            (float) (
                                $row->budget_ytd
                                ?? 0
                            ),

                        'VAR_YTD' =>
                            (float) (
                                $row->var_ytd
                                ?? 0
                            ),

                        'BUDGET_ALL' =>
                            (float) (
                                $row->budget_all
                                ?? 0
                            ),

                        'ACH' =>
                            (float) (
                                $row->ach
                                ?? 0
                            ),
                    ];
                })
                ->values();

        $dataRaw2 = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiTBS_Bulanan_Budget_YTD_DASHBOARD_OPTZ3
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                $tahun,
                $bulan,
                $siteParameter
            ]
        );
        

        /*
        |--------------------------------------------------------------------------
        | Site Options
        |--------------------------------------------------------------------------
        */

        $siteOptions = [
            '2200' => 'TELDA',
            '2300' => 'KALSA',
            '2400' => 'KALDA',
            '2500' => 'KOKAR',
            '3200' => 'RICKO',
            '4200' => 'MUARA',
            '5200' => 'PASER',
            '6200' => 'LANGGAI',
        ];


        $selectedSiteNames =
            collect($selectedSites)
                ->map(
                    function ($siteId) use ($siteOptions) {
                        return
                            $siteOptions[$siteId]
                            ?? $siteId;
                    }
                )
                ->values()
                ->all();

        /*
        |--------------------------------------------------------------------------
        | Data Produksi TBS - Table Kanan
        |--------------------------------------------------------------------------
        */

        $dataProduksiTBS = collect($dataRaw2)
            ->filter(function ($row) use ($siteOptions) {
                // Hanya kebun yang memang ingin ditampilkan.
                // SITE_ID 2600 (MITRA KOKAR) tidak ikut.
                return isset(
                    $siteOptions[
                        (string) ($row->SITE_ID ?? '')
                    ]
                );
            })
            ->map(function ($row) use ($siteOptions) {

                $siteId =
                    (string) (
                        $row->SITE_ID
                        ?? ''
                    );

                return [
                    'COMP_ID' =>
                        $row->COMP_ID
                        ?? null,

                    'SITE_ID' =>
                        $row->SITE_ID
                        ?? null,

                    'KEBUN' =>
                        $siteOptions[$siteId]
                        ?? $siteId,

                    'YTD_TAHUN_LALU' =>
                        (float) (
                            $row->PRODUKSI_TBS_YTD_TAHUN_LALU
                            ?? 0
                        ),

                    'AKT_BULAN_INI' =>
                        (float) (
                            $row->PRODUKSI_TBS_AKTUAL_BULAN_INI
                            ?? 0
                        ),

                    'YTD_TAHUN_INI' =>
                        (float) (
                            $row->PRODUKSI_TBS_YTD_TAHUN_INI
                            ?? 0
                        ),

                    'BUD_BULAN_INI' =>
                        (float) (
                            $row->BUDGET_BULAN_INI
                            ?? 0
                        ),

                    'BUD_YTD_TAHUN_INI' =>
                        (float) (
                            $row->BUDGET_YTD_TAHUN_INI
                            ?? 0
                        ),

                    'BUD_TAHUNAN' =>
                        (float) (
                            $row->ANNUALBUDGET
                            ?? 0
                        ),
                ];
            })
            ->values();


        $dataBiaya2 =
            collect($dataRaw3)
                ->map(function ($row) {

                    return [
                        'NO' =>
                            (int) (
                                $row->no
                                ?? 0
                            ),

                        'COMP_ID' =>
                            $row->comp_id
                            ?? null,

                        'SITE_ID' =>
                            $row->site_id
                            ?? null,

                        'BIAYA' =>
                            strtoupper(
                                trim(
                                    $row->biaya
                                    ?? ''
                                )
                            ),

                        'TAHUN' =>
                            (int) (
                                $row->tahun
                                ?? 0
                            ),

                        'BULAN' =>
                            (int) (
                                $row->bulan
                                ?? 0
                            ),

                        'YTD_TAHUN_LALU' =>
                            (float) (
                                $row->ytd_tahun_lalu
                                ?? 0
                            ),

                        'VAR_YTD_TAHUN_LALU' =>
                            (float) (
                                $row->var_ytd_tahun_lalu
                                ?? 0
                            ),

                        'AKTUAL_BULAN_INI' =>
                            (float) (
                                $row->aktual_bulan_ini
                                ?? 0
                            ),

                        'BUDGET_BULAN_INI' =>
                            (float) (
                                $row->budget_bulan_ini
                                ?? 0
                            ),

                        'VAR_BULAN_INI' =>
                            (float) (
                                $row->var_bulan_ini
                                ?? 0
                            ),

                        'AKTUAL_YTD' =>
                            (float) (
                                $row->aktual_ytd
                                ?? 0
                            ),

                        'BUDGET_YTD' =>
                            (float) (
                                $row->budget_ytd
                                ?? 0
                            ),

                        'VAR_YTD' =>
                            (float) (
                                $row->var_ytd
                                ?? 0
                            ),

                        'BUDGET_ALL' =>
                            (float) (
                                $row->budget_all
                                ?? 0
                            ),

                        'ACH' =>
                            (float) (
                                $row->ach
                                ?? 0
                            ),
                    ];
                })
                ->values();


        // $dataBiaya3 = DB::select(
        //     'SET NOCOUNT ON;

        //     EXEC PUBDB.Produksi.LaporanProduksiTBS_Bulanan_Budget_YTD_DASHBOARD_OPTZ3
        //         @tahun = ?,
        //         @bulan = ?,
        //         @site_id = ?',
        //     [
        //         $tahun,
        //         $bulan,
        //         $siteParameter
        //     ]
        // );

        /*
        |--------------------------------------------------------------------------
        | Data HA TM Tahun Ini & Tahun Lalu
        |--------------------------------------------------------------------------
        */

        $dataHaYtd = [];

        $totalHa = max(count($dataRaw4Array), count($dataRaw5Array));

        for ($i = 0; $i < $totalHa; $i++) {

            $rowTahunIni = isset($dataRaw4Array[$i])
                ? array_values($dataRaw4Array[$i])
                : [];

            $rowTahunLalu = isset($dataRaw5Array[$i])
                ? array_values($dataRaw5Array[$i])
                : [];

            /*
            * Berdasarkan penggunaan existing:
            * index 0 = COMP_ID
            * index 1 = SITE_ID
            * index 2 = HA_TM
            */

            $siteId = isset($rowTahunIni[1])
                ? (string)$rowTahunIni[1]
                : (
                    isset($rowTahunLalu[1])
                        ? (string)$rowTahunLalu[1]
                        : ''
                );

            if ($siteId == '') {
                continue;
            }

            $dataHaYtd[] = [
                'SITE_ID' => $siteId,

                'HA_TAHUN_INI' => isset($rowTahunIni[2])
                    ? (float)$rowTahunIni[2]
                    : 0,

                'HA_TAHUN_LALU' => isset($rowTahunLalu[2])
                    ? (float)$rowTahunLalu[2]
                    : 0,
            ];
        }

        $dataHaYtd = collect($dataHaYtd)
            ->filter(function ($row) use ($siteOptions) {
                return isset($siteOptions[(string)$row['SITE_ID']]);
            })
            ->map(function ($row) use ($siteOptions) {

                $siteId = (string)$row['SITE_ID'];

                return [
                    'SITE_ID' => $siteId,
                    'KEBUN' => $siteOptions[$siteId] ?? $siteId,
                    'HA_TAHUN_INI' => (float)$row['HA_TAHUN_INI'],
                    'HA_TAHUN_LALU' => (float)$row['HA_TAHUN_LALU'],
                ];
            })
            ->values();

        return view(
            'dashboard.dev.BiayaDev',
            [
                'dataBiaya' =>
                    $dataBiaya,
                'dataBiaya2' =>
                    $dataBiaya2,
                'dataProduksiTBS' =>
                    $dataProduksiTBS,
                'dataHaYtd' => $dataHaYtd,
                'tahun' =>
                    $tahun,

                'bulan' =>
                    $bulan,

                'namaBulan' =>
                    $this->getNamaBulan(),

                'siteOptions' =>
                    $siteOptions,

                'selectedSites' =>
                    $selectedSites,

                'selectedSiteNames' =>
                    $selectedSiteNames,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Site Selection
    |--------------------------------------------------------------------------
    */

    private function normalizeSelectedSites(
        $selectedSites
    ) {
        if (
            !is_array(
                $selectedSites
            )
        ) {
            $selectedSites =
                explode(
                    ',',
                    $selectedSites
                );
        }

        return array_values(
            array_filter(
                array_map(
                    'trim',
                    $selectedSites
                ),
                function ($siteId) {
                    return
                        $siteId !== ''
                        && strtoupper(
                            $siteId
                        ) !== 'NULL'
                        && is_numeric(
                            $siteId
                        );
                }
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Nama Bulan
    |--------------------------------------------------------------------------
    */

    private function getNamaBulan()
    {
        return [
            1 => 'JANUARI',
            2 => 'FEBRUARI',
            3 => 'MARET',
            4 => 'APRIL',
            5 => 'MEI',
            6 => 'JUNI',
            7 => 'JULI',
            8 => 'AGUSTUS',
            9 => 'SEPTEMBER',
            10 => 'OKTOBER',
            11 => 'NOVEMBER',
            12 => 'DESEMBER',
        ];
    }

    private function formatKeteranganKebun($divisionName, $divisionCode = '')
    {
        $name = strtoupper(trim($divisionName));

        /*
         * Hilangkan nama company di depan tanda "-".
         *
         * PEU - TELDA AFDELING 01
         * menjadi:
         * TELDA AFDELING 01
         */
        if (strpos($name, '-') !== false) {
            $parts = explode('-', $name, 2);
            $name = trim($parts[1]);
        }

        $kodeKebun = '';

        $mappingKebun = [
            'TELDA' => 'TD',
            'KALSA' => 'K1',
            'KALDA' => 'K2',
            'KALIANTA' => 'K2',
            'KOKAR' => 'KK',
            'RICKO' => 'RK',
            'MUARA' => 'MR',
            'PASER' => 'PS',
            'LANGGAI' => 'LG',
        ];

        foreach ($mappingKebun as $namaPanjang => $namaSingkat) {
            if (preg_match('/\b' . preg_quote($namaPanjang, '/') . '\b/i', $name)) {
                $kodeKebun = $namaSingkat;
                break;
            }
        }

        /*
         * Format Afdeling:
         * TELDA AFDELING 01 => TD AFD 01
         */
        if (preg_match('/\bAFDELING\s*([0-9A-Z]+)/i', $name, $matches)) {
            $nomorAfdeling = strtoupper($matches[1]);

            return trim($kodeKebun . ' AFD ' . $nomorAfdeling);
        }

        /*
         * Format Mitra Rayon:
         * MITRA KALIANTA RAYON A => MTR K2 RYN A
         * RICKO MITRA RAYON A    => MTR RK RYN A
         */
        if (
            preg_match('/\bMITRA\b/i', $name) ||
            preg_match('/\bMTR\b/i', $name)
        ) {
            $rayon = '';

            if (preg_match('/\bRAYON\s*([0-9A-Z]+)/i', $name, $matches)) {
                $rayon = strtoupper($matches[1]);
            }

            $result = 'MTR';

            if ($kodeKebun !== '') {
                $result .= ' ' . $kodeKebun;
            }

            if ($rayon !== '') {
                $result .= ' RYN ' . $rayon;
            }

            return trim($result);
        }
         /*
         * Fallback apabila pola DIVISIONNAME berbeda dari pola normal.
         */
        $name = preg_replace('/\bAFDELING\b/i', 'AFD', $name);
        $name = preg_replace('/\bMITRA\b/i', 'MTR', $name);
        $name = preg_replace('/\bRAYON\b/i', 'RYN', $name);

        foreach ($mappingKebun as $namaPanjang => $namaSingkat) {
            $name = preg_replace(
                '/\b' . preg_quote($namaPanjang, '/') . '\b/i',
                $namaSingkat,
                $name
            );
        }

        $name = preg_replace('/\s+/', ' ', $name);

        if ($name !== '') {
            return trim($name);
        }

        return strtoupper(trim($divisionCode));
    }
}