<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ModulPerKebun;
use DB;
use Auth;
use DateTime;

class BioFertilizerController extends Controller
{
    public function getStatusbatchfromDB (Request $request) {
        
        $bulan = (isset($_GET['bulan']) ? $_GET['bulan'] : date('n'));
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : '2025');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        $select_jenis = (isset($_GET['selectjenis']) ? $_GET['selectjenis'] : 'SSB');
        $select_status = (isset($_GET['selectstatus']) ? $_GET['selectstatus'] : 'SEMUA');
        
        return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Compost.StatusBatchCompost_PerBulan_Dashboard  
                            @Tahun = ? , @Bulan = ?,  @site_id = ?, @status = ? , @Jenis = ? , @inactive = 0", [$tahun, $bulan, $select_kebun, $select_status, $select_jenis]);
    }

    public function getStatusbatch (Request $request) {
        if (Auth::user()->canAccessByHakAkses('BioFertilizer', 'Status Batch') == false) abort('403-dashboard'); 

        $Status_Batch = $this->getStatusbatchfromDB($request);
        return view('dashboard.biofertilizer.Statusbatch')->with([
            'Status_Batch' => $Status_Batch,
            ]);
    }

    public function getStatusbatchEPLANTfromDB (Request $request) {

        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        $select_jenis = (isset($_GET['selectjenis']) ? $_GET['selectjenis'] : 'SSB');
        $select_status = (isset($_GET['selectstatus']) ? $_GET['selectstatus'] : 'SEMUA');
        
        return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Compost.StatusBatchCompost_UsiaBatch 
                            @site_id = ? ,@Jenis = ?,  @status = ?,  @inactive = 0", [$select_kebun, $select_jenis, $select_status]);
    }

    public function getStatusbatchEPLANT (Request $request) {
        if (Auth::user()->canAccessByHakAkses('BioFertilizer', 'Status Batch EPLANT') == false) abort('403-dashboard'); 

        $Status_Batch = $this->getStatusbatchEPLANTfromDB($request);
        return view('dashboard.biofertilizer.StatusbatchEPLANT')->with([
            'Status_Batch' => $Status_Batch,
            ]);
    }

    public function getAnalisaMutasiPupukCompost_PerBulan(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses(
                'BioFertilizer',
                'Analisa Mutasi Pupuk Compost PerBulan'
            ) == false
        ) {
            abort('403-dashboard');
        }

        $tahun = (int) $request->get('tahun', date('Y'));
        // $bulan = (int) $request->get('bulan', date('n'));
        $bulan = 12;
        $siteId = (int) $request->get('site_id', 2200);

        $jenisProduk = strtoupper(
            trim((string) $request->get('jenis_produk', 'SEMUA'))
        );

        $jenisParameter = in_array(
            $jenisProduk,
            ['SSB', 'SSC', 'SSK'],
            true
        ) ? $jenisProduk : null;

        $status = null;
        $inactive = 0;

        // TAB 1 - ANALISA MUTASI
        $rows = DB::select(
            "SET NOCOUNT ON;

            EXEC PUBDB.Compost.AnalisaMutasiPupukCompost_PerTahun_Dashboard
                @Tahun = ?,
                @Bulan = ?,
                @site_id = ?,
                @status = ?,
                @Jenis = ?,
                @inactive = ?",
            [
                $tahun,
                null,
                $siteId,
                'SEMUA',
                $jenisParameter,
                $inactive
            ]
        );

        // TAB 2 - PRODUKSI PUPUK
        $produksiPupuk = DB::select(
            "SET NOCOUNT ON;
            EXEC PUBDB.Compost.AnalisaPersediaanCompost_YTD_Dashboard
                @Tahun = ?,
                @Bulan = ?,
                @site_id = ?,
                @status = ?,
                @Jenis = ?,
                @inactive = ?",
            [
                $tahun,
                $bulan,
                (string) $siteId,
                $status,
                $jenisParameter,
                $inactive
            ]
        );

        $siteOptions = [
            '2200' => 'TELDA',
            '2300' => 'KALSA',
            '2400' => 'KALDA',
            '2500' => 'KOKAR',
            '3200' => 'RICKO',
            '5200' => 'PASER',
        ];

        return view(
            'dashboard.biofertilizer.analisa-mutasi-per-bulan',
            [
                'rows' => $rows,
                'produksiPupuk' => $produksiPupuk,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'siteId' => $siteId,
                'jenisProduk' => $jenisProduk,
                'siteOptions' => $siteOptions,
            ]
        );
    }
}