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
        // --- Filters (default like your examples) ---
        $tahun  = (int) ($request->get('tahun')  ?? date('Y'));
        $bulan  = (int) ($request->get('bulan')  ?? date('n')); // 1..12
        // $siteId = $request->get('site_id');
        // $siteId = ($siteId === 'ALL' || $siteId === null || $siteId === '') ? null : (int)$siteId;
        $siteId = (int) ($request->get('site_id') ?? 2200);



        // status & jenis must be NULL; inactive = 0
        $status   = null;
        $jenis    = null;
        $inactive = 0;

        // Run the stored procedure (SQL Server)
        $rows = DB::select(
            "SET NOCOUNT ON;
             EXEC PUBDB.Compost.AnalisaMutasiPupukCompost_PerBulan_Dashboard
                @Tahun = ?, @Bulan = ?, @site_id = ?, @status = ?, @Jenis = ?, @inactive = ?",
            [$tahun, $bulan, $siteId, $status, $jenis, $inactive]
        );

        // Pass data & current filters to the view
        return view('dashboard.biofertilizer.analisa-mutasi-per-bulan', [
            'rows'   => $rows,
            'tahun'  => $tahun,
            'bulan'  => $bulan,
            'siteId' => $siteId,
        ]);
    }
}