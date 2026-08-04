<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ModulPerKebun;
use DB;
use Auth;
use DateTime;

class PembelianController extends Controller
{
    public function getHargaIdeal(Request $request) {

        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Ideal') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal']) : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']): date("Y-m-d", strtotime("0 days")));

        $harga_ideal = [];
        $kode_kebun = DB::select("Select RIGHT(kode_kebun,2) as kode_kebun, nama_lengkap from TblKebun where LEN(kode_kebun)>5 order by Nourut");
        $kebun = (new ModulPerKebun())->getListKebunForModul('Pembelian');

        for ($i = 0; $i < count($kode_kebun); $i ++) {
            for ($j = 0; $j < count($kebun); $j ++) {
                if ($kode_kebun[$i]->kode_kebun == 'RK') {
                    $harga_ideal[$i] = DB::select(
                        "Select * from APMRKD.dbo.hargaideal(?,?,?) ".
                         " Order By Kebun, TglPembelian asc" , [$dari_tanggal, $sampai_tanggal, $kode_kebun[$i]->kode_kebun]);
                } else if ($kode_kebun[$i]->kode_kebun == 'PS') {
                    $harga_ideal[$i] = DB::select(
                        "Select * from MMMAKD.dbo.hargaideal(?,?,?) ".
                         " Order By Kebun, TglPembelian asc" , [$dari_tanggal, $sampai_tanggal, $kode_kebun[$i]->kode_kebun]);
                } else {
                    $harga_ideal[$i] = DB::select(
                        "Select * from " . $kebun[$j]->nama_DB . ".dbo.hargaideal(?,?,?) ".
                         " Order By Kebun, TglPembelian asc" , [$dari_tanggal, $sampai_tanggal, $kode_kebun[$i]->kode_kebun]);
                }
            }
        }

        return view('dashboard.pembelian.hargaideal')->with([
            'kode_kebun' => $kode_kebun,
            'harga_ideal' => $harga_ideal
        ]);

    }

    public function getHargaBeliTBS(Request $request) {

        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Beli TBS') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal']) : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']): date("Y-m-d", strtotime("0 days")));

        $harga_beliTBS = [];
        $kode_kebun = DB::select("Select RIGHT(kode_kebun,2) as kode_kebun, nama_lengkap from TblKebun where LEN(kode_kebun)>5 order by Nourut");
        $kebun = (new ModulPerKebun())->getListKebunForModul('Pembelian');

        for ($i = 0; $i < count($kode_kebun); $i ++) {
            for ($j = 0; $j < count($kebun); $j ++) {
                if ($kode_kebun[$i]->kode_kebun == 'RK') {
                    $harga_beliTBS[$i] = DB::select(
                        "Select * from APMRKD.dbo.hargabelitbs(?,?,?) ".
                         " Order By NoPembelian asc" , [$dari_tanggal, $sampai_tanggal, $kode_kebun[$i]->kode_kebun]);
                } else if ($kode_kebun[$i]->kode_kebun == 'PS') {
                    $harga_beliTBS[$i] = DB::select(
                        "Select * from MMMAKD.dbo.hargabelitbs(?,?,?) ".
                         " Order By NoPembelian asc" , [$dari_tanggal, $sampai_tanggal, $kode_kebun[$i]->kode_kebun]);
                } else {
                    $harga_beliTBS[$i] = DB::select(
                        "Select * from " . $kebun[$j]->nama_DB . ".dbo.hargabelitbs(?,?,?) ".
                         " Order By NoPembelian asc" , [$dari_tanggal, $sampai_tanggal, $kode_kebun[$i]->kode_kebun]);
                }
            }
        }

        return view('dashboard.pembelian.hargabeliTBS')->with([
            'kode_kebun' => $kode_kebun,
            'harga_beliTBS' => $harga_beliTBS
        ]);

    }

    public function getHargaIdealNew(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Ideal') == false) {
            abort('403-dashboard');
        }

        // Default: 7 hari terakhir sampai kemarin
        $dariInput = $request->get('dari_tanggal');
        $sampaiInput = $request->get('sampai_tanggal');

        if ($dariInput) {
            $dariDate = DateTime::createFromFormat('d/m/Y', $dariInput);
        } else {
            $dariDate = new DateTime(date('Y-m-d', strtotime('-7 days')));
        }

        if ($sampaiInput) {
            $sampaiDate = DateTime::createFromFormat('d/m/Y', $sampaiInput);
        } else {
            $sampaiDate = new DateTime(date('Y-m-d', strtotime('-1 days')));
        }

        // Safety fallback kalau format tanggal salah
        if (!$dariDate) {
            $dariDate = new DateTime(date('Y-m-d', strtotime('-7 days')));
        }

        if (!$sampaiDate) {
            $sampaiDate = new DateTime(date('Y-m-d', strtotime('-1 days')));
        }

        $startDate = $dariDate->format('Ymd');
        $endDate   = $sampaiDate->format('Ymd');

        // 1 = EGP
        $harga_egp = DB::select("
            EXEC PUBDB.MM_TBS.sp_SimulasiHargaTBSP3_Dashboard
                @startDate = ?,
                @endDate = ?,
                @parameterrendemen = ?
        ", [$startDate, $endDate, '1']);

        // 2 = NPS
        $harga_nps = DB::select("
            EXEC PUBDB.MM_TBS.sp_SimulasiHargaTBSP3_Dashboard
                @startDate = ?,
                @endDate = ?,
                @parameterrendemen = ?
        ", [$startDate, $endDate, '2']);

        return view('dashboard.pembelian.hargaIdeal2')->with([
            'harga_egp'      => $harga_egp,
            'harga_nps'      => $harga_nps,
            'dari_tanggal'   => $dariDate->format('d/m/Y'),
            'sampai_tanggal' => $sampaiDate->format('d/m/Y'),
        ]);
    }

    public function getHargaIdealOLD(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Ideal') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal']) : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']): date("Y-m-d", strtotime("0 days")));

        $harga_idealTD = [];
        $harga_idealK1 = [];
        $harga_idealK2 = [];
        $harga_idealKK = [];
        $harga_idealRK = [];
        $harga_idealPS = [];

        $harga_idealTD = DB::select("select * FROM PUBDB.MM_TBS.TblHargaIdealPEU(?,?,'TELDA')", [$dari_tanggal,$sampai_tanggal]);
        $harga_idealK1 = DB::select("select * FROM PUBDB.MM_TBS.TblHargaIdealPEU(?,?,'KALSA')", [$dari_tanggal,$sampai_tanggal]);
        $harga_idealK2 = DB::select("select * FROM PUBDB.MM_TBS.TblHargaIdealPEU(?,?,'KALDA')", [$dari_tanggal,$sampai_tanggal]);
        $harga_idealKK = DB::select("select * FROM PUBDB.MM_TBS.TblHargaIdealPEU(?,?,'KOKAR')", [$dari_tanggal,$sampai_tanggal]);
        $harga_idealRK = DB::select("select * FROM PUBDB.MM_TBS.TblHargaIdealAPMR(?,?,'RICKO')", [$dari_tanggal,$sampai_tanggal]);
        $harga_idealPS = DB::select("select * FROM PUBDB.MM_TBS.TblHargaIdealMMMA(?,?,'PASER')", [$dari_tanggal,$sampai_tanggal]);

        return view('dashboard.pembelian.hargaIdeal2')->with([
            'harga_idealTD' => $harga_idealTD,
            'harga_idealK1'=> $harga_idealK1,
            'harga_idealK2'=> $harga_idealK2,
            'harga_idealKK'=> $harga_idealKK,
            'harga_idealRK'=> $harga_idealRK,
            'harga_idealPS'=> $harga_idealPS
        ]);
    }

    public function getHargaIdealTBSfromDB(Request $request, $supplierCode)
    {
        $dariInput = $request->get('dari_tanggal');
        $sampaiInput = $request->get('sampai_tanggal');
        $selectKebun = $request->get('selectkebun', 'SEMUA');

        if ($dariInput) {
            $dariDate = DateTime::createFromFormat('d/m/Y', $dariInput);
        } else {
            $dariDate = new DateTime(date('Y-m-d', strtotime('-7 days')));
        }

        if ($sampaiInput) {
            $sampaiDate = DateTime::createFromFormat('d/m/Y', $sampaiInput);
        } else {
            $sampaiDate = new DateTime(date('Y-m-d', strtotime('-1 days')));
        }

        if (!$dariDate) {
            $dariDate = new DateTime(date('Y-m-d', strtotime('-7 days')));
        }

        if (!$sampaiDate) {
            $sampaiDate = new DateTime(date('Y-m-d', strtotime('-1 days')));
        }

        $startDate = $dariDate->format('Ymd');
        $endDate   = $sampaiDate->format('Ymd');

        /*
            millcode:
            - Untuk SEMUA, pakai NULL
            - Kalau SP menerima millcode nama kebun seperti TELDA/KALSA/dst, bisa pakai $selectKebun.
            - Kalau ternyata SP hanya valid dengan NULL, biarkan selalu NULL.
        */
        $millCode = ($selectKebun == 'SEMUA') ? null : $selectKebun;

        return DB::select("
            EXEC PUBDB.MM_TBS.sp_RealisasiHargaBeliTBSP3_Dashboard
                @startDate = ?,
                @endDate = ?,
                @suppliercode = ?,
                @millcode = ?
        ", [$startDate, $endDate, $supplierCode, $millCode]);
    }

    public function getHargaIdealTBS(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Ideal TBS') == false) {
            abort('403-dashboard');
        }

        $dariInput = $request->get('dari_tanggal');
        $sampaiInput = $request->get('sampai_tanggal');

        $dariDate = $dariInput
            ? DateTime::createFromFormat('d/m/Y', $dariInput)
            : new DateTime(date('Y-m-d', strtotime('-7 days')));

        $sampaiDate = $sampaiInput
            ? DateTime::createFromFormat('d/m/Y', $sampaiInput)
            : new DateTime(date('Y-m-d', strtotime('-1 days')));

        if (!$dariDate) {
            $dariDate = new DateTime(date('Y-m-d', strtotime('-7 days')));
        }

        if (!$sampaiDate) {
            $sampaiDate = new DateTime(date('Y-m-d', strtotime('-1 days')));
        }

        $harga_beli_egp = $this->getHargaIdealTBSfromDB($request, 'FEGP001');
        $harga_beli_nps = $this->getHargaIdealTBSfromDB($request, 'FNPS001');

        return view('dashboard.pembelian.hargaidealTBS')->with([
            'harga_beli_egp' => $harga_beli_egp,
            'harga_beli_nps' => $harga_beli_nps,
            'dari_tanggal' => $dariDate->format('d/m/Y'),
            'sampai_tanggal' => $sampaiDate->format('d/m/Y'),
            'selectkebun' => $request->get('selectkebun', 'SEMUA'),
        ]);
    }

    public function getHargaRata2TBSFromDB(Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');
        
        if($select_kebun == 'SEMUA') {
            return DB::select("select ROW_NUMBER() OVER (ORDER BY ( " .
                                " (IIF(KEBUN = 'TELDA', '1', IIF(KEBUN = 'KALSA', '2', IIF(KEBUN = 'KALDA', '3',IIF(KEBUN = 'KOKAR', '4', IIF(KEBUN = 'RICKO', '5', '6')))) ))) Asc, TAHUN , BULAN) AS BARIS" . 
                                " ,* FROM PUBDB.MM_TBS.TblHargaIdealTBSUnionAll_Average(?,?) where KEBUN like '%%' ", [$dari_tanggal, $sampai_tanggal]);
        }
        else {
            return DB::select("select ROW_NUMBER() OVER (ORDER BY ( " .
                                " (IIF(KEBUN = 'TELDA', '1', IIF(KEBUN = 'KALSA', '2', IIF(KEBUN = 'KALDA', '3',IIF(KEBUN = 'KOKAR', '4', IIF(KEBUN = 'RICKO', '5', '6')))) ))) Asc, TAHUN , BULAN) AS BARIS" . 
                                " ,* FROM PUBDB.MM_TBS.TblHargaIdealTBSUnionAll_Average(?,?) where KEBUN like ? ", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }

    }

    public function getHargaRata2BeliTBS(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Rata2 Beli TBS') == false) abort('403-dashboard');

        $harga_rata2beliTBS = $this->getHargaRata2TBSFromDB($request);

        return view('dashboard.pembelian.HargaBeliRata2TBS')->with([
            'harga_rata2beliTBS' => $harga_rata2beliTBS
        ]);
    } 

    public function getAnalisaPupukFromDB(Request $request) {
        $bulan = (isset($_GET['bulan']) ? $_GET['bulan'] : '1');
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : '2024');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        return DB::select("SET NOCOUNT ON ; EXEC [PUBDB].[web].[mutasi_pupuk] @bulan = ? , @tahun = ? , @site_id = ? ", [$bulan, $tahun, $select_kebun]);

    }

    public function getAnalisaPupuk(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Analisa Pupuk') == false) abort('403-dashboard');

        $Analisa_Pupuk = $this->getAnalisaPupukFromDB($request);

        $bulan = (isset($_GET['bulan']) ? $_GET['bulan'] : '1');
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : '2024');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        $data=array();
        for ($i=1; $i <= $bulan; $i++) { 
            $data[] = DB::select("SET NOCOUNT ON ; EXEC [PUBDB].[web].[mutasi_pupuk] @bulan = ? , @tahun = ? , @site_id = ? ", [$i, $tahun, $select_kebun]);        
        }

        $grn = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $budget = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $siv = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        
        $grn2 = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $budget2 = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $siv2 = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        

        for ($i=0; $i < intval($bulan); $i++) { 
            for ($j=0; $j < count($data[0]); $j++) { 
                $grn[$j] += floatval($data[$i][$j]->Nilai_GRN);
                $budget[$j] += floatval($data[$i][$j]->Nilai_Budget);
                $siv[$j] += floatval($data[$i][$j]->Nilai_SIV);

                $grn2[$j] += floatval($data[$i][$j]->Qty_GRN);
                $budget2[$j] += floatval($data[$i][$j]->Qty_Budget);
                $siv2[$j] += floatval($data[$i][$j]->Qty_SIV);
            }
        }

        return view('dashboard.pembelian.AnalisaPupuk')->with([
            'Analisa_Pupuk' => $Analisa_Pupuk , 'grn'=>$grn, 'budget'=>$budget, 'siv'=>$siv, 'grn2'=>$grn, 'budget2'=>$budget, 'siv2'=>$siv
        ]);
    }

    public function getPembelianTBSP3FromDB(Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');
        
        if($select_type == '0') {
            return DB::select("SET NOCOUNT ON ; EXEC PUBDB.TBS.PenerimaanTBSP3_Harian_Dashboard @startDate = ? , @endDate = ? , @site = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if ($select_type == '1') {
            return DB::select("SET NOCOUNT ON ; EXEC PUBDB.TBS.PenerimaanTBSP3_Bulanan_Dashboard @startDate = ? , @endDate = ? , @site = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }

    }

    public function getPembelianTBSP3(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian TBS P3') == false) abort('403-dashboard');

        $Pembelian_TBSP3 = $this->getPembelianTBSP3FromDB($request);

        return view('dashboard.pembelian.PembelianTBSP3')->with([
            'Pembelian_TBSP3' => $Pembelian_TBSP3    
        ]);
    }

    public function getRekapitulasiPembelianSolarFromDB(Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('0 days')));
        $jenis = (isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '00.000.0001');
        
        return DB::select("SET NOCOUNT ON ; exec [PUBDB].[web].[analisa_grn] @nkb = ? , @tahun= ? ", [$jenis, $tahun]);

    }

    public function getRekapitulasiPembelianSolar(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') == false) abort('403-dashboard');

        $Rekapitulasi_PSolar = $this->getRekapitulasiPembelianSolarFromDB($request);

        return view('dashboard.pembelian.RekapitulasiPembelianSolar')->with([
            'Rekapitulasi_PSolar' => $Rekapitulasi_PSolar    
        ]);
    }

    public function getRekapitulasiPembelianSolarPOFromDB(Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('0 days')));
        $jenis = (isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '00.000.0001');
        
        return DB::select("SET NOCOUNT ON ; exec [PUBDB].[web].[analisa_po] @nkb = ? , @tahun= ? ", [$jenis, $tahun]);

    }

    public function getRekapitulasiPembelianSolarPO(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') == false) abort('403-dashboard');

        $Rekapitulasi_PSolar = $this->getRekapitulasiPembelianSolarPOFromDB($request);

        return view('dashboard.pembelian.RekapitulasiPembelianSolarPO')->with([
            'Rekapitulasi_PSolar' => $Rekapitulasi_PSolar    
        ]);
    }

    public function getRekapitulasiPembelianBerasFromDB(Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('0 days')));
        $jenis = (isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '13.000.0001');
        
        return DB::select("SET NOCOUNT ON ; exec [PUBDB].[web].[analisa_grn] @nkb = ? , @tahun= ? ", [$jenis, $tahun]);

    }

    public function getRekapitulasiPembelianBeras(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') == false) abort('403-dashboard');

        $Rekapitulasi_PBeras = $this->getRekapitulasiPembelianBerasFromDB($request);
        $Jlh_PBeras = [];

        foreach ($Rekapitulasi_PBeras as $row) {
            $bulan = (int)$row->BULAN;
            $tahunRow = (int)$row->TAHUN;

            $result = DB::select("SET NOCOUNT ON; EXEC [PUBDB].[web].[penerima_beras] @bulan = ?, @tahun = ?", [$bulan, $tahunRow]);

            // Group by month number (e.g., 1 for January)
            if (count($result) === 8) {
                if (!isset($Jlh_PBeras[$bulan])) {
                    $Jlh_PBeras[$bulan] = [];
                }

                foreach ($result as $r) {
                    $Jlh_PBeras[$bulan][] = (array) $r;
                }
            }
        }

        return view('dashboard.pembelian.RekapitulasiPembelianBeras')->with([
            'Rekapitulasi_PBeras' => $Rekapitulasi_PBeras,
            'Jlh_PBeras' => $Jlh_PBeras    
        ]);
    }

    public function getRekapitulasiPembelianBerasPOFromDB(Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('0 days')));
        $jenis = (isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '13.000.0001');
        
        return DB::select("SET NOCOUNT ON ; exec [PUBDB].[web].[analisa_po] @nkb = ? , @tahun= ? ", [$jenis, $tahun]);

    }

    public function getRekapitulasiPembelianBerasPO(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') == false) abort('403-dashboard');

        $Rekapitulasi_PBeras = $this->getRekapitulasiPembelianBerasPOFromDB($request);
        $Jlh_PBeras = [];

        foreach ($Rekapitulasi_PBeras as $row) {
            $bulan = (int)$row->BULAN;
            $tahunRow = (int)$row->TAHUN;

            $result = DB::select("SET NOCOUNT ON; EXEC [PUBDB].[web].[penerima_beras] @bulan = ?, @tahun = ?", [$bulan, $tahunRow]);

            // Group by month number (e.g., 1 for January)
            if (count($result) === 8) {
                if (!isset($Jlh_PBeras[$bulan])) {
                    $Jlh_PBeras[$bulan] = [];
                }

                foreach ($result as $r) {
                    $Jlh_PBeras[$bulan][] = (array) $r;
                }
            }
        }

        return view('dashboard.pembelian.RekapitulasiPembelianBerasPO')->with([
            'Rekapitulasi_PBeras' => $Rekapitulasi_PBeras,
            'Jlh_PBeras' => $Jlh_PBeras     
        ]);
    }

    public function getRekapPembelianTBSP3FromDB(Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        
        return DB::select("SET NOCOUNT ON ; EXEC PUBDB.TBS.RekapPembelianTBSPihak3_AVRG_PRICE_SITE_Dashboard @startDate = ? , @endDate = ?, @site = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);

    }

    public function getRekapPembelianTBSP3(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Rekap Pembelian TBS P3') == false) abort('403-dashboard');

        $RekapPembelian_TBSP3 = $this->getRekapPembelianTBSP3FromDB($request);

        return view('dashboard.pembelian.RekapPembelianTBSP3')->with([
            'RekapPembelian_TBSP3' => $RekapPembelian_TBSP3    
        ]);
    }

    public function getDeadStockFromDB(Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('0 days')));
        $bulan = (isset($_GET['bulan']) ? $_GET['bulan'] : date('m', strtotime('-1 months')));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        
        return DB::select("SET NOCOUNT ON ; EXEC [PUBDB].[MM].[d_adeadstock_group] @site_id =?, @tahun = ?, @bulan = ?", [$select_kebun, $tahun, $bulan]);

    }

    public function getDeadStock(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Inventory', 'Dead Stock') == false) abort('403-dashboard');

        $Dead_Stock = $this->getDeadStockFromDB($request);

        return view('dashboard.inventory.DeadStock')->with([
            'Dead_Stock' => $Dead_Stock    
        ]);
    }

    public function getAnalisaMutasiPupukFromDB(Request $request) {
        $bulan = (isset($_GET['bulan']) ? $_GET['bulan'] : '1');
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('0 days')));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        
        return DB::select("SET NOCOUNT ON ; EXEC [PUBDB].[web].[analisa_mutasi_pupuk] @site = ? , @year = ? , @month = ? ", [$select_kebun, $tahun, $bulan]);

    }

    public function getAnalisaMutasiPupuk(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Pembelian', 'Analisa Pupuk') == false) abort('403-dashboard');

        $Analisa_Pupuk = $this->getAnalisaMutasiPupukFromDB($request);

        return view('dashboard.pembelian.AnalisaMutasiPupuk')->with([
            'Analisa_Pupuk' => $Analisa_Pupuk
        ]);
    }

}
