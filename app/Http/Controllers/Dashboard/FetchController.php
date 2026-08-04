<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ModulPerKebun;
use DB;
use Auth;
use DateTime;

class FetchController extends Controller
{
    public function GetTooltip (Request $request) {

        if(isset($_GET["id"]))
        {
            $dari_tanggal = (isset($request['dari_tanggal']) 
                            ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                            : date("Y-m-d", strtotime("-7 days")));
            $sampai_tanggal = (isset($request['sampai_tanggal'])
                            ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']) 
                            : date("Y-m-d", strtotime("0 days")));
            $filter = $_GET["id"];
            $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35');

            $lhp_ED = [];

            $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_TELDA(?,?,?) where TGL_LHP = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi , $filter]);
            // if($select_kebun == 'DBTimbPMKSTD') {
            //     $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_TELDA(?,?,?) where TGL_LHP = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi, $filter]);
            // }
            // else if($select_kebun == 'DBTimbPMKSK1') {
            //     $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KALSA(?,?,?) where TGL_LHP = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi, $filter]);
            // }
            // else if($select_kebun == 'DBTimbPMKSK2') {
            //     $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KALDA(?,?,?) where TGL_LHP = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi, $filter]);
            // }
            // else if($select_kebun == 'DBTimbPMKSKK') {
            //     $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KOKAR(?,?,?) where TGL_LHP = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi, $filter]);
            // }
            // else if($select_kebun == 'DBTimbPMKSRK') {
            //     $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_RICKO(?,?,?) where TGL_LHP = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi, $filter]);
            // }
            // else if($select_kebun == 'DBTimbPMKSPS') {
            //     $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_PASER(?,?,?) where TGL_LHP = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi, $filter]);
            // }

            $output = '
                <p><label>Rend Telda : '. $lhp_ED[0]->REND_TELDA . ' </label></p>
                <p><label>Rend Target Telda : '. $lhp_ED[0]->REND_TARGET_TELDA . ' </label></p>
                <p><label>Rend P3 Telda : '. $lhp_ED[0]->REND_P3_TELDA . '</label></p>
                <p><label>Rend P3 Target Telda : '. $lhp_ED[0]->REND_TARGET_P3_TELDA . ' </label></p>
                ';
         return $output;
        }
    }
}
