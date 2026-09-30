<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MillController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Produksi CPO
    |--------------------------------------------------------------------------
    */

    public function getProduksiCPO(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses(
                'Mill',
                'Produksi CPO'
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
        | Per Kebun
        |--------------------------------------------------------------------------
        */

        $dataRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiCPO_Bulanan_Budget_YTD_DASHBOARD
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
        | Per Region
        |--------------------------------------------------------------------------
        */

        $regionRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiCPO_Bulanan_Budget_REGION_YTD_DASHBOARD
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                $tahun,
                $bulan,
                $siteParameter
            ]
        );


        $dataProduksi =
            collect($dataRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiMillRow(
                            $row
                        );
                })
                ->sortBy('INDEX')
                ->values();


        $dataRegion =
            collect($regionRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiRegionRow(
                            $row
                        );
                })
                ->sortBy('INDEX')
                ->values();


        $siteOptions =
            $this->getMillSiteOptions();


        $selectedSiteNames =
            collect($selectedSites)
                ->map(
                    function (
                        $siteId
                    ) use (
                        $siteOptions
                    ) {
                        return
                            $siteOptions[$siteId]
                            ?? $siteId;
                    }
                )
                ->values()
                ->all();


        return view(
            'dashboard.mill.ProduksiCPO',
            [
                'dataProduksi' =>
                    $dataProduksi,

                'dataRegion' =>
                    $dataRegion,

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
    | Produksi Inti / PK
    |--------------------------------------------------------------------------
    */

    public function getProduksiInti(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses(
                'Mill',
                'Produksi Inti'
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
        | Per Kebun
        |--------------------------------------------------------------------------
        */

        $dataRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiPK_Bulanan_Budget_YTD_DASHBOARD
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
        | Per Region
        |--------------------------------------------------------------------------
        */

        $regionRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiPK_Bulanan_Budget_REGION_YTD_DASHBOARD
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                $tahun,
                $bulan,
                $siteParameter
            ]
        );


        $dataProduksi =
            collect($dataRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiMillRow(
                            $row
                        );
                })
                ->sortBy('INDEX')
                ->values();


        $dataRegion =
            collect($regionRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiRegionRow(
                            $row
                        );
                })
                ->sortBy('INDEX')
                ->values();


        $siteOptions =
            $this->getMillSiteOptions();


        $selectedSiteNames =
            collect($selectedSites)
                ->map(
                    function (
                        $siteId
                    ) use (
                        $siteOptions
                    ) {
                        return
                            $siteOptions[$siteId]
                            ?? $siteId;
                    }
                )
                ->values()
                ->all();


        return view(
            'dashboard.mill.ProduksiInti',
            [
                'dataProduksi' =>
                    $dataProduksi,

                'dataRegion' =>
                    $dataRegion,

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
    | Mapping Per Kebun
    |--------------------------------------------------------------------------
    */

    private function mapProduksiMillRow($row, $defaultProduk = '')
    {
        return [
            'PRODUK' =>
                strtoupper(
                    trim(
                        $row->PRODUK
                        ?? $defaultProduk
                    )
                ),
            'INDEX' =>
                (int) (
                    $row->INDEX
                    ?? 0
                ),

            'COMP_ID' =>
                $row->COMP_ID
                ?? null,

            'SITE_ID_MILL' =>
                $row->SITE_ID_MILL
                ?? null,

            'MILLCODE' =>
                strtoupper(
                    trim(
                        $row->MILLCODE
                        ?? ''
                    )
                ),

            'TAHUN' =>
                (int) (
                    $row->TAHUN
                    ?? 0
                ),

            'BULAN' =>
                (int) (
                    $row->BULAN
                    ?? 0
                ),

            'PRODUKSI_LASTYEAR_YTD' =>
                (float) (
                    $row->PRODUKSI_LASTYEAR_YTD
                    ?? 0
                ),

            'PRODUKSI_THISYEAR_YTD' =>
                (float) (
                    $row->PRODUKSI_THISYEAR_YTD
                    ?? 0
                ),

            'VARIAN_YTD_TAHUN_LALU' =>
                (float) (
                    $row->VARIAN_YTD_TAHUN_LALU
                    ?? 0
                ),

            'PRODUKSI_THISYEAR_SELECTED_MONTH' =>
                (float) (
                    $row->PRODUKSI_THISYEAR_SELECTED_MONTH
                    ?? 0
                ),

            'BUDGET_THISYEAR_SELECTED_MONTH' =>
                (float) (
                    $row->BUDGET_THISYEAR_SELECTED_MONTH
                    ?? 0
                ),

            'VARIAN_BULAN_INI' =>
                (float) (
                    $row->VARIAN_BULAN_INI
                    ?? 0
                ),

            'BUDGET_THISYEAR_YTD' =>
                (float) (
                    $row->BUDGET_THISYEAR_YTD
                    ?? 0
                ),

            'VARIAN_YTD' =>
                (float) (
                    $row->VARIAN_YTD
                    ?? 0
                ),

            'BUDGET_THISYEAR_ALL' =>
                (float) (
                    $row->BUDGET_THISYEAR_ALL
                    ?? 0
                ),

            'VARIAN_ALL' =>
                (float) (
                    $row->VARIAN_ALL
                    ?? 0
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Mapping Per Region
    |--------------------------------------------------------------------------
    */

    private function mapProduksiRegionRow($row, $defaultProduk = '')
    {
        return [
            'PRODUK' =>
                strtoupper(
                    trim(
                        $row->PRODUK
                        ?? $defaultProduk
                    )
                ),
            'INDEX' =>
                (int) (
                    $row->INDEX
                    ?? 0
                ),

            'REGION' =>
                strtoupper(
                    trim(
                        $row->REGION
                        ?? ''
                    )
                ),

            'TAHUN' =>
                (int) (
                    $row->TAHUN
                    ?? 0
                ),

            'BULAN' =>
                (int) (
                    $row->BULAN
                    ?? 0
                ),

            'PRODUKSI_LASTYEAR_YTD' =>
                (float) (
                    $row->PRODUKSI_LASTYEAR_YTD
                    ?? 0
                ),

            'PRODUKSI_THISYEAR_YTD' =>
                (float) (
                    $row->PRODUKSI_THISYEAR_YTD
                    ?? 0
                ),

            'VARIAN_YTD_TAHUN_LALU' =>
                (float) (
                    $row->VARIAN_YTD_TAHUN_LALU
                    ?? 0
                ),

            'PRODUKSI_THISYEAR_SELECTED_MONTH' =>
                (float) (
                    $row->PRODUKSI_THISYEAR_SELECTED_MONTH
                    ?? 0
                ),

            'BUDGET_THISYEAR_SELECTED_MONTH' =>
                (float) (
                    $row->BUDGET_THISYEAR_SELECTED_MONTH
                    ?? 0
                ),

            'VARIAN_BULAN_INI' =>
                (float) (
                    $row->VARIAN_BULAN_INI
                    ?? 0
                ),

            'BUDGET_THISYEAR_YTD' =>
                (float) (
                    $row->BUDGET_THISYEAR_YTD
                    ?? 0
                ),

            'VARIAN_YTD' =>
                (float) (
                    $row->VARIAN_YTD
                    ?? 0
                ),

            'BUDGET_THISYEAR_ALL' =>
                (float) (
                    $row->BUDGET_THISYEAR_ALL
                    ?? 0
                ),

            'VARIAN_ALL' =>
                (float) (
                    $row->VARIAN_ALL
                    ?? 0
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Mill Site Options
    |--------------------------------------------------------------------------
    */

    private function getMillSiteOptions()
    {
        return [
            '2200' => 'TELDA',
            '2300' => 'KALSA',
            '2400' => 'KALDA',
            '2500' => 'KOKAR',
            '3200' => 'RICKO',
            '5200' => 'PASER',
        ];
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

    public function getProduksiCPOInti(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses(
                'Mill',
                'Produksi CPO Inti'
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
        | CPO - PER PABRIK
        |--------------------------------------------------------------------------
        */

        $cpoRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiCPO_Bulanan_Budget_YTD_DASHBOARD
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
        | CPO - PER REGION
        |--------------------------------------------------------------------------
        */

        $cpoRegionRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiCPO_Bulanan_Budget_REGION_YTD_DASHBOARD
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
        | INTI - PER PABRIK
        |--------------------------------------------------------------------------
        */

        $intiRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiPK_Bulanan_Budget_YTD_DASHBOARD
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
        | INTI - PER REGION
        |--------------------------------------------------------------------------
        */

        $intiRegionRaw = DB::select(
            'SET NOCOUNT ON;

            EXEC PUBDB.Produksi.LaporanProduksiPK_Bulanan_Budget_REGION_YTD_DASHBOARD
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
        | Mapping CPO
        |--------------------------------------------------------------------------
        */

        $dataCPO =
            collect($cpoRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiMillRow(
                            $row,
                            'CPO'
                        );
                });


        $regionCPO =
            collect($cpoRegionRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiRegionRow(
                            $row,
                            'CPO'
                        );
                });


        /*
        |--------------------------------------------------------------------------
        | Mapping INTI
        |--------------------------------------------------------------------------
        */

        $dataInti =
            collect($intiRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiMillRow(
                            $row,
                            'PK'
                        );
                });


        $regionInti =
            collect($intiRegionRaw)
                ->map(function ($row) {
                    return $this
                        ->mapProduksiRegionRow(
                            $row,
                            'PK'
                        );
                });

        /*
        |--------------------------------------------------------------------------
        | Filter Pabrik
        |--------------------------------------------------------------------------
        |
        | Stored procedure ternyata masih bisa mengembalikan semua PMKS ketika
        | @site_id berisi beberapa ID seperti "2200,2300".
        |
        | Karena itu hasil akhirnya difilter kembali berdasarkan SITE_ID_MILL.
        |
        */

        if (
            count($selectedSites) > 0
        ) {
            $selectedSiteStrings =
                array_map(
                    'strval',
                    $selectedSites
                );


            $dataCPO =
                $dataCPO
                    ->filter(
                        function ($row) use (
                            $selectedSiteStrings
                        ) {
                            return in_array(
                                (string) (
                                    $row[
                                        'SITE_ID_MILL'
                                    ]
                                    ?? ''
                                ),
                                $selectedSiteStrings,
                                true
                            );
                        }
                    )
                    ->values();


            $dataInti =
                $dataInti
                    ->filter(
                        function ($row) use (
                            $selectedSiteStrings
                        ) {
                            return in_array(
                                (string) (
                                    $row[
                                        'SITE_ID_MILL'
                                    ]
                                    ?? ''
                                ),
                                $selectedSiteStrings,
                                true
                            );
                        }
                    )
                    ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Gabungkan CPO + INTI
        |--------------------------------------------------------------------------
        */

        $dataProduksi =
            $dataCPO
                ->concat($dataInti)
                ->values();


        $dataRegion =
            $regionCPO
                ->concat($regionInti)
                ->values();


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $siteOptions =
            $this->getMillSiteOptions();


        $selectedSiteNames =
            collect($selectedSites)
                ->map(
                    function (
                        $siteId
                    ) use (
                        $siteOptions
                    ) {
                        return
                            $siteOptions[$siteId]
                            ?? $siteId;
                    }
                )
                ->values()
                ->all();


        return view(
            'dashboard.mill.ProduksiCPOInti',
            [
                'dataProduksi' =>
                    $dataProduksi,

                'dataRegion' =>
                    $dataRegion,

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
    | Penerimaan TBS
    |--------------------------------------------------------------------------
    |
    | Tambahkan method ini ke:
    | app/Http/Controllers/Dashboard/MillController.php
    |
    */

    public function getTBSTerima(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses(
                'Mill',
                'TBS Terima'
            ) == false
        ) {
            abort('403-dashboard');
        }

        $tahun =
            (int) $request->get(
                'tahun',
                date('Y')
            );

        $bulan =
            (int) $request->get(
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
        | QUERY 1 - PER PABRIK
        |--------------------------------------------------------------------------
        */

        $dataRaw =
            DB::select(
                'SET NOCOUNT ON;

                EXEC PUBDB.Produksi.LaporanPenerimaanTBS_Bulanan_Budget_YTD_DASHBOARD
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
        | QUERY 2 - PER WILAYAH
        |--------------------------------------------------------------------------
        */

        $regionRaw =
            DB::select(
                'SET NOCOUNT ON;

                EXEC PUBDB.Produksi.LaporanPenerimaanTBS_Bulanan_Budget_REGION_YTD_DASHBOARD
                    @tahun = ?,
                    @bulan = ?,
                    @site_id = ?',
                [
                    $tahun,
                    $bulan,
                    $siteParameter
                ]
            );


        $siteOptions = [
            '2200' => 'TELDA',
            '2300' => 'KALSA',
            '2400' => 'KALDA',
            '2500' => 'KOKAR',
            '2600' => 'MITRA KOKAR',
            '3200' => 'RICKO',
            '4200' => 'MUARA',
            '5200' => 'PASER',
            '6200' => 'LANGGAI',
        ];


        $dataProduksi =
            collect($dataRaw)
                ->map(
                    function ($row) use ($siteOptions) {
                        $siteId =
                            (string) (
                                $row->SITE_ID
                                ?? ''
                            );

                        return [
                            'INDEX' =>
                                (int) (
                                    $row->INDEX
                                    ?? 0
                                ),

                            'COMP_ID' =>
                                $row->COMP_ID
                                ?? null,

                            'SITE_ID' =>
                                $row->SITE_ID
                                ?? null,

                            'NAMA_PABRIK' =>
                                $siteOptions[$siteId]
                                ?? $siteId,

                            'GRUP' =>
                                strtoupper(
                                    trim(
                                        $row->GRUP
                                        ?? ''
                                    )
                                ),

                            'PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU' =>
                                (float) (
                                    $row->PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU
                                    ?? 0
                                ),

                            'VARIAN_TAHUNLALU' =>
                                (float) (
                                    $row->VARIAN_TAHUNLALU
                                    ?? 0
                                ),

                            'PRODUKSI_TBS_AKTUAL_BULAN_INI' =>
                                (float) (
                                    $row->PRODUKSI_TBS_AKTUAL_BULAN_INI
                                    ?? 0
                                ),

                            'MONTHLYBUDGET' =>
                                (float) (
                                    $row->MONTHLYBUDGET
                                    ?? 0
                                ),

                            'VARIAN_TAHUN_INI' =>
                                (float) (
                                    $row->VARIAN_TAHUN_INI
                                    ?? 0
                                ),

                            'PRODUKSI_TBS_AKTUAL_YTD' =>
                                (float) (
                                    $row->PRODUKSI_TBS_AKTUAL_YTD
                                    ?? 0
                                ),

                            'BUDGETYTD' =>
                                (float) (
                                    $row->BUDGETYTD
                                    ?? 0
                                ),

                            'VARIAN_YTD' =>
                                (float) (
                                    $row->VARIAN_YTD
                                    ?? 0
                                ),

                            'ANUALBUDGET' =>
                                (float) (
                                    $row->ANUALBUDGET
                                    ?? 0
                                ),

                            'VARIAN_TOTAL' =>
                                (float) (
                                    $row->VARIAN_TOTAL
                                    ?? 0
                                ),
                        ];
                    }
                )
                ->values();


        $dataRegion =
            collect($regionRaw)
                ->map(
                    function ($row) {
                        return [
                            'INDEX' =>
                                (int) (
                                    $row->INDEX
                                    ?? 0
                                ),

                            'WILAYAH' =>
                                strtoupper(
                                    trim(
                                        $row->WILAYAH
                                        ?? ''
                                    )
                                ),

                            'GRUP' =>
                                strtoupper(
                                    trim(
                                        $row->GRUP
                                        ?? ''
                                    )
                                ),

                            'PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU' =>
                                (float) (
                                    $row->PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU
                                    ?? 0
                                ),

                            'VARIAN_TAHUNLALU' =>
                                (float) (
                                    $row->VARIAN_TAHUNLALU
                                    ?? 0
                                ),

                            'PRODUKSI_TBS_AKTUAL_BULAN_INI' =>
                                (float) (
                                    $row->PRODUKSI_TBS_AKTUAL_BULAN_INI
                                    ?? 0
                                ),

                            'MONTHLYBUDGET' =>
                                (float) (
                                    $row->MONTHLYBUDGET
                                    ?? 0
                                ),

                            'VARIAN_TAHUN_INI' =>
                                (float) (
                                    $row->VARIAN_TAHUN_INI
                                    ?? 0
                                ),

                            'PRODUKSI_TBS_AKTUAL_YTD' =>
                                (float) (
                                    $row->PRODUKSI_TBS_AKTUAL_YTD
                                    ?? 0
                                ),

                            'BUDGETYTD' =>
                                (float) (
                                    $row->BUDGETYTD
                                    ?? 0
                                ),

                            'VARIAN_YTD' =>
                                (float) (
                                    $row->VARIAN_YTD
                                    ?? 0
                                ),

                            'ANUALBUDGET' =>
                                (float) (
                                    $row->ANUALBUDGET
                                    ?? 0
                                ),

                            'VARIAN_TOTAL' =>
                                (float) (
                                    $row->VARIAN_TOTAL
                                    ?? 0
                                ),
                        ];
                    }
                )
                ->values();


        $selectedSiteNames =
            collect($selectedSites)
                ->map(
                    function ($siteId) use ($siteOptions) {
                        return (
                            $siteOptions[$siteId]
                            ?? $siteId
                        );
                    }
                )
                ->values()
                ->all();


        return view(
            'dashboard.mill.TBSTerima',
            [
                'dataProduksi' =>
                    $dataProduksi,

                'dataRegion' =>
                    $dataRegion,

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

}