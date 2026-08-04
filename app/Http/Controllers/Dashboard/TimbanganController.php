<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\ModulPerKebun;
use DB;
use DateTime;
use Auth;

class TimbanganController extends Controller
{
    public function getPengolahanTBS(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Timbangan', 'Pengolahan TBS') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal']) : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']): date("Y-m-d", strtotime("0 days")));

        $pengolahan_tbs = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Timbangan');
        for ($i = 0; $i < count($kebun); $i ++) {
            $pengolahan_tbs[$i] = DB::select("select TglLHP as TglProduksi, Sum(ProduksiHariIni) as ProduksiHariIni, Sum(Diolah) as JumlahDiolah from " . $kebun[$i]->nama_DB . ".produksi.tbllhpbag3TBS where Cast(TglLHP As Date) >= ? And Cast(TglLHP As Date) <= ? group by Kebun, tgllhp", [$dari_tanggal, $sampai_tanggal]);
        }

        return view('dashboard.timbangan.pengolahanTBS')->with([
            'kebun' => $kebun,
            'pengolahan_tbs' => $pengolahan_tbs
            ]);
    }

    public function getProduksiGabungan(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Timbangan', 'Produksi Gabungan') == false) abort('403-dashboard');
        
        $dari_tanggal = (isset($request['dari_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal']) : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']): date("Y-m-d", strtotime("0 days")));

        $produksi_gabungan = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Timbangan');
        for ($i = 0; $i < count($kebun); $i ++) {
            $produksi_gabungan[$i] = DB::select(
                "select TblTBS.TglProduksi, TblTBS.JumlahTBSOlah, TblCPO.ProduksiCPO, TblInti.ProduksiInti from " .
                "(select Kebun, TglLHP as TglProduksi, Sum(SisaSblm) as RestanTBS, Sum(ProduksiHariIni) as ProduksiTBSHariIni, Sum(Diolah) as JumlahTBSOlah " .
                "from " . $kebun[$i]->nama_DB . ".produksi.tbllhpbag3TBS where Cast(TglLHP As Date) >= ? And Cast(TglLHP As Date) <= ? " .
                "group by Kebun, tgllhp) TblTBS " .
                "JOIN " .
                "(select Kebun, TglLHP as TglProduksi, sum(ProduksiHariIni) as ProduksiCPO from " . $kebun[$i]->nama_DB . ".produksi.tbllhpbag3CPO " .
                "where Cast(TglLHP As Date) >= ? And Cast(TglLHP As Date) <= ? " .
                "group by Kebun, tgllhp) TblCPO on TblTBS.Kebun = TblCPO.Kebun and TblTBS.TglProduksi = TblCPO.TglProduksi " .
                "JOIN " .
                "(select Kebun, TglLHP as TglProduksi, sum(ProduksiHariIni) as ProduksiInti from " . $kebun[$i]->nama_DB . ".produksi.tbllhpbag3Inti " .
                "where Cast(TglLHP As Date) >= ? And Cast(TglLHP As Date) <= ? " .
                "group by Kebun, tgllhp) TblInti on TblTBS.Kebun = TblInti.Kebun and TblTBS.TglProduksi = TblInti.TglProduksi "
                , [$dari_tanggal, $sampai_tanggal, $dari_tanggal, $sampai_tanggal, $dari_tanggal, $sampai_tanggal]);
        }


        return view('dashboard.timbangan.produksiGabungan')->with([
            'kebun' => $kebun,
            'produksi_gabungan' => $produksi_gabungan
            ]);
    }

    public function getRendemenGabungan(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Timbangan', 'Rendemen Gabungan') == false) abort('403-dashboard');
        
        $dari_tanggal = (isset($request['dari_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal']) : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']): date("Y-m-d", strtotime("0 days")));

        $rendemen_gabungan = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Timbangan');
        for ($i = 0; $i < count($kebun); $i ++) {
            $rendemen_gabungan[$i] = DB::select(
                "select TblTBS.TglProduksi, IIF(TblTBS.JumlahTBSOlah = 0,0,(CAST((TblCPO.ProduksiCPOHariIni * 100.00/TblTBS.JumlahTBSOlah) as DECIMAL(16,2)))) As RENDEMEN_TOTAL_CPO, " .
                "IIF(TblTBS.JumlahTBSOlah = 0,0,(CAST((TblInti.ProduksiIntiHariIni * 100.00/TblTBS.JumlahTBSOlah) as DECIMAL(16,2)))) As RENDEMEN_TOTAL_INTI from " .
                "(select Kebun, TglLHP as TglProduksi, Sum(SisaSblm) as RestanTBS, Sum(ProduksiHariIni) as ProduksiTBSHariIni, Sum(Diolah) as JumlahTBSOlah " .
                "from " . $kebun[$i]->nama_DB . ".produksi.tbllhpbag3TBS where Cast(TglLHP As Date) >= ? And Cast(TglLHP As Date) <= ? group by Kebun, tgllhp) TblTBS " .
                "JOIN " .
                "(select Kebun, TglLHP as TglProduksi, sum(ProduksiHariIni) as ProduksiCPOHariIni from " . $kebun[$i]->nama_DB . ".produksi.tbllhpbag3CPO " .
                "where Cast(TglLHP As Date) >= ? And Cast(TglLHP As Date) <= ? " .
                "group by Kebun, tgllhp) TblCPO on TblTBS.Kebun = TblCPO.Kebun and TblTBS.TglProduksi = TblCPO.TglProduksi " .
                "JOIN " .
                "(select Kebun, TglLHP as TglProduksi, sum(ProduksiHariIni) as ProduksiIntiHariIni from " . $kebun[$i]->nama_DB . ".produksi.tbllhpbag3Inti " .
                "where Cast(TglLHP As Date) >= ? And Cast(TglLHP As Date) <= ? " .
                "group by Kebun, tgllhp) TblInti on TblTBS.Kebun = TblInti.Kebun and TblTBS.TglProduksi = TblInti.TglProduksi"
                , [$dari_tanggal, $sampai_tanggal, $dari_tanggal, $sampai_tanggal, $dari_tanggal, $sampai_tanggal]);
        }

        return view('dashboard.timbangan.rendemenGabungan')->with([
            'kebun' => $kebun,
            'rendemen_gabungan' => $rendemen_gabungan
            ]);
    }

    public function getHasilTimbangan (Request $request) 
    {
        if (Auth::user()->canAccessByHakAkses('Timbangan', 'Hasil Timbang') == false) abort('403-dashboard');
        
        $dari_tanggal = (isset($request['dari_tanggal']) 
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']) 
                         : date("Y-m-d", strtotime("0 days")));
        $status = (isset($_GET['status']) ? $_GET['status'] : '2');

        $hasil_timbang = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Timbangan');
        for ($i = 0; $i < count($kebun); $i ++) {
            $hasil_timbang[$i] = DB::select(
                "select A.KodeSP, cast(A.TGLJAMBRUTTO as DATE) as TANGGAL_TBS_MASUK, 
                ISNULL(SUM(A.BeratBrutto),0) as BRUTTO, ISNULL(SUM(A.BeratTarra),0) as TARRA,  ISNULL(SUM(A.BeratNetto),0) as NETTO1,
                (ISNULL(SUM(A.NilaiPotongan),0) + ISNULL(SUM(A.NilaiPotonganSampah),0)) as TOTAL_POTONGAN,
                cast(IIF( ISNULL(SUM(A.BeratNetto),0) > 0, ( ((ISNULL(SUM(A.NilaiPotongan),0) + ISNULL(SUM(A.NilaiPotonganSampah),0)) * 100.00) /ISNULL(SUM(A.BeratNetto),0) ) ,0) AS decimal(18,2))  AS PERSEN_POTONGAN,
                ISNULL(SUM(A.BeratNettoStlhSortasi),0) AS NETTO2  
                FROM " . $kebun[$i]->nama_DB . ".Timbangan.TblPMTimbangTBS A
                LEFT JOIN " . $kebun[$i]->nama_DB . ".Accounting.TblSP B ON A.KodeSP = B.KodeSP 
                LEFT JOIN " . $kebun[$i]->nama_DB . ".Timbangan.TblPengangkutan C ON A.KodePengangkutan = C.KodePengangkutan
                WHERE CAST(A.TglJamBrutto AS DATE) >= ?  AND  CAST(A.TglJamBrutto AS DATE) <= ? AND B.Status = ?
                GROUP BY A.KodeSP, CAST(A.TGLJAMBRUTTO AS DATE)
                ORDER BY CAST(A.TGLJAMBRUTTO AS DATE)", [$dari_tanggal, $sampai_tanggal, $status]);
        }

        return view('dashboard.timbangan.hasiltimbang')->with([
            'kebun' => $kebun,
            'hasil_timbang' => $hasil_timbang
            ]);
    }
}