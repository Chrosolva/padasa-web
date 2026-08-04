<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ModulPerKebun;
use DB;
use Auth;
use DateTime;

class PenjualanController extends Controller
{
    public function getKontrakPenjualan(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Kontrak Penjualan') == false) abort('403-dashboard');

    	$dari_tanggal = (isset($request['dari_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal']) : date("Y-m-d", strtotime("-7 days")));
    	$sampai_tanggal = (isset($request['sampai_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']): date("Y-m-d", strtotime("0 days")));

        $kontrak_penjualan = [];
        $list_produk = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Penjualan');
        for ($i = 0; $i < count($kebun); $i ++) {
            $kontrak_penjualan[$i] = DB::select(
                "Select TblX.TglKontrak, TblX.NamaProduk AS Produk, IIF(TblY.TotalKontrak is null,0,TblY.TotalKontrak) as TotalKontrak from " .
                "(select Distinct(A.TglKontrak),B.NamaProduk from " . $kebun[$i]->nama_DB . ".dbo.TblKontrakJual A " .
                "CROSS JOIN " . $kebun[$i]->nama_DB . ".dbo.TblProduk B " .
                "where TglKontrak >= ? and TglKontrak <= ?) TblX " .
                "LEFT JOIN " .
                "(select TglKontrak, Produk, sum(Quantity) as TotalKontrak from " . $kebun[$i]->nama_DB . ".dbo.TblKontrakJual " .
                "where TglKontrak >= ? and TglKontrak <= ? " .
                "group by TglKontrak, Produk) TblY " .
                "on TblX.TglKontrak = TblY.TglKontrak and TblX.NamaProduk = TblY.produk"
                , [$dari_tanggal, $sampai_tanggal, $dari_tanggal, $sampai_tanggal]);
            $list_produk[$i] = DB::select("select NamaProduk AS Produk from " . $kebun[$i]->nama_DB . ".dbo.TblProduk");
        }

        return view('dashboard.penjualan.kontrakPenjualan')->with([
            'kebun' => $kebun,
            'kontrak_penjualan' => $kontrak_penjualan,
            'list_produk' => $list_produk
            ]);
    }

    public function getHargaKontrakVSHargaTender(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Kontrak vs Harga Tender') == false) abort('403-dashboard');

        $per_tanggal = (isset($request['per_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['per_tanggal'])->format('Y-m-d') : date("Y-m-d", strtotime("0 days")));

        $harga_kontrak_vs_harga_tender = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Penjualan');
        for ($i = 0; $i < count($kebun); $i ++) {
            $harga_kontrak_vs_harga_tender[$i] = DB::select(
                "select A.NoKontrak, D.NamaKebun, A.TglKontrak, B.NamaCustFP , A.Produk, A.Quantity, A.Harga AS HargaKontrak , C.Harga AS HargaTender, C.HargaRata as HargaTenderRataRata " .
                "from  " . $kebun[$i]->nama_DB . ".dbo.TblKontrakJual A " .
                "left join " . $kebun[$i]->nama_DB . ".dbo.TblCust B on A.KodeCust = B.KodeCust " .
                "left join " . $kebun[$i]->nama_DB . ".dbo.TblHargaTender C on A.Produk = C.NamaProduk and A.TglKontrak = C.TglTender " .
                "left join " . $kebun[$i]->nama_DB . ".dbo.TblKebun D on A.Kebun = D.KodeKebun " .
                "where A.TglKontrak = ?"
                , [$per_tanggal]);
        }


        return view('dashboard.penjualan.hargaKontrakVSHargaTender')->with([
            'kebun' => $kebun,
            'harga_kontrak_vs_harga_tender' => $harga_kontrak_vs_harga_tender
            ]);
    }

    public function getHargaTender(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Tender') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));

        $harga_tender = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Penjualan');
        $harga_tender = DB::select("SELECT * FROM PUBDB.Sales.TblHargaTenderCPO_PK(?,?)", [$dari_tanggal, $sampai_tanggal]);


        return view('dashboard.penjualan.HargaTender')->with([
            'kebun' => $kebun,
            'harga_tender' => $harga_tender
            ]);
    }

    public function getPengirimanDO(Request $request) {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Pengiriman DO') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');
        $select_product = (isset($_GET['selectproduct']) ? $_GET['selectproduct'] : '%%');

        $pengiriman_DO = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Penjualan');
        if($select_kebun == 'SEMUA') {
            if($select_product == 'SEMUA') {
                $pengiriman_DO = DB::select("Select * from PUBDB.Sales.TblPengirimanDO_TglTerpilih(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else {
                $pengiriman_DO = DB::select("Select * from PUBDB.Sales.TblPengirimanDO_TglTerpilih(?,?) where PRODUK like ? ", [$dari_tanggal, $sampai_tanggal, $select_product]);
            }
        }
        else {
            $pengiriman_DO = DB::select("Select * from PUBDB.Sales.TblPengirimanDO_TglTerpilih(?,?) Where PABRIK = ? and PRODUK like ?", [$dari_tanggal, $sampai_tanggal, $select_kebun, $select_product]);
        }

        return view('dashboard.penjualan.PengirimanDO')->with([
            'kebun' => $kebun,
            'pengiriman_DO' => $pengiriman_DO
            ]);
    }

    public function getKontrakPenjualanV2 (Request $request ) {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Kontrak Penjualan V2') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');
        $select_product = (isset($_GET['selectproduct']) ? $_GET['selectproduct'] : '%%');

        $kontrak_jualV2 = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Penjualan');
        if($select_kebun == 'SEMUA') {
            if($select_product == 'SEMUA') {
                $kontrak_jualV2 = DB::select(" select COMP_ID, SITE_ID, MILL_KONTRAK, NO_KONTRAK, PRODUK, KONTRAK_QTY, NAMA_CUST, UNITPRICE, TOTAL_PENJUALAN, SISA " . 
                                             " FROM PUBDB.Sales.TblKontrakJualDenganSisaDO(?, ?) " . 
                                             " ORDER BY COMP_ID,SITE_ID, MILL_KONTRAK", [$dari_tanggal, $sampai_tanggal]);
            }
            else {
                $kontrak_jualV2 = DB::select(" select COMP_ID, SITE_ID, MILL_KONTRAK, NO_KONTRAK, PRODUK, KONTRAK_QTY, NAMA_CUST, UNITPRICE, TOTAL_PENJUALAN, SISA " . 
                                             " FROM PUBDB.Sales.TblKontrakJualDenganSisaDO(?, ?) Where PRODUK like ? " . 
                                             " ORDER BY COMP_ID,SITE_ID, MILL_KONTRAK", [$dari_tanggal, $sampai_tanggal, $select_product]);
            }
        }
        else {
            $kontrak_jualV2 = DB::select(" select COMP_ID, SITE_ID, MILL_KONTRAK, NO_KONTRAK, PRODUK, KONTRAK_QTY, NAMA_CUST, UNITPRICE, TOTAL_PENJUALAN, SISA " . 
                                             " FROM PUBDB.Sales.TblKontrakJualDenganSisaDO(?, ?) Where MILL_KONTRAK = ? and  PRODUK like ? " . 
                                             " ORDER BY COMP_ID,SITE_ID, MILL_KONTRAK", [$dari_tanggal, $sampai_tanggal, $select_kebun ,  $select_product]);
        }

        return view('dashboard.penjualan.kontrak_jualV2')->with([
            'kebun' => $kebun,
            'kontrak_jualV2' => $kontrak_jualV2
            ]);
    }

    public function getOutstanding(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Outstanding') == false) abort('403-dashboard');

        $per_tanggal = (isset($request['per_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['per_tanggal'])->format('Y-m-d') : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'TELDA');

        $outstanding = [];
        
        $outstanding = DB::select("select * FROM PUBDB.Sales.TblOutstanding(?) " .
                                " WHERE SISA > 0  AND YEAR(TGL_KONTRAK) >= 2021 AND MILL = ? " . 
                                " ORDER BY TGL_KONTRAK  ", [$per_tanggal,$select_kebun]);

        return view('dashboard.penjualan.OutStanding')->with([
            'outstanding' => $outstanding
            ]);
    }

    public function getPengiriman(Request $request)
    {       
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Pengiriman') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                        : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                        : date("Y-m-d", strtotime("0 days")));
        $select_product = (isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'MINYAK SAWIT');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        $pengiriman = [];
        
        $pengiriman = DB::select("SET NOCOUNT ON; exec PUBDB.Sales.ShipmentPerBulan_Dashboard @startdate = ?, @enddate = ? " .
                                " , @site = ?, @produk = ? ", [$dari_tanggal, $sampai_tanggal, $select_kebun,$select_product]);

        return view('dashboard.penjualan.pengiriman')->with([
            'pengiriman' => $pengiriman
            ]);
    }

    public function getOutstandingPerCustomer(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Outstanding Per Customer') == false) abort('403-dashboard');

        $per_tanggal = (isset($request['per_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['per_tanggal'])->format('Y-m-d') : date("Y-m-d", strtotime("0 days")));
        $select_produk = (isset($_GET['selectproduk']) ? $_GET['selectproduk'] : 'CPO');

        $outstandingpc = [];
        
        if($select_produk != 'SEMUA') {
            $outstandingpc = DB::select("SET NOCOUNT ON ; exec PUBDB.Sales.AnalisaOutStanding @SelectedDate = ? , @Product = ?  ", [$per_tanggal, $select_produk]);
        }
        else {
            $outstandingpc = DB::select("SET NOCOUNT ON ; exec PUBDB.Sales.AnalisaOutStanding @SelectedDate = ?,@Product = ?", [$per_tanggal,$select_produk]);
        }

        return view('dashboard.penjualan.OutStandingPerCustomer')->with([
            'outstandingpc' => $outstandingpc
            ]);
    }

    public function getAnalisisStok(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Analisis Stok') == false) abort('403-dashboard');
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date("Y"));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        $analisa_produksi = [];
        $analisa_stok = [];
        $analisa_stokIS = [];
        
        $analisa_produksi = DB::select("SET NOCOUNT ON ; exec PUBDB.Produksi.AnalisaProduksi_Pengiriman_Stock @Tahun = ? , @Site = ? ", [$tahun , $select_kebun]);
        $analisa_stok = DB::select("SET NOCOUNT ON ; exec PUBDB.Produksi.Analisa_Stock_AkhirBulan @Tahun = ? , @Site = ? ", [$tahun , $select_kebun]);
        $analisa_stokIS = DB::select("SET NOCOUNT ON; EXEC PUBDB.Produksi.Analisa_Stock_AkhirBulan_PK @Tahun = ? , @site = ? ", [$tahun,$select_kebun]);

        return view('dashboard.penjualan.AnalisisStok')->with([
            'analisa_produksi' => $analisa_produksi,
            'analisa_stok' => $analisa_stok,
            'analisa_stokIS' => $analisa_stokIS
            ]);
    }

    public function getRekapShipmentfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                        : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                        : date("Y-m-d", strtotime("0 days")));
        
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        
        return DB::select( "SET NOCOUNT ON ; EXEC pubdb.sales.RekapPengirimanShipmentPerBulan_Dashboard @startdate = ?, @enddate = ?, @site = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
    }

    public function getRekapShipment (Request $request) {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Rekap Shipment') == false) abort('403-dashboard'); 

        $kebun = (new ModulPerKebun())->getListKebunForModul('Penjualan');
        $Rekap_Shipment = $this->getRekapShipmentfromDB($request);
        return view('dashboard.penjualan.RekapShipment')->with([
            'kebun' => $kebun,
            'Rekap_Shipment' => $Rekap_Shipment,
            ]);
    }

    public function getRekapShipmentTahunanfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                        : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                        : date("Y-m-d", strtotime("0 days")));
        
        $select_product = (isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'MINYAK SAWIT');
        
        return DB::select( "SET NOCOUNT ON ; exec PUBDB.Sales.ShipmentPerBulan2 @startdate = ?, @enddate = ?, @produk = ?", [$dari_tanggal, $sampai_tanggal, $select_product]);
    }

    public function getRekapShipmentTahunan (Request $request) {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Rekap Shipment') == false) abort('403-dashboard'); 

        $kebun = (new ModulPerKebun())->getListKebunForModul('Penjualan');
        $Rekap_ShipmentTahunan = $this->getRekapShipmentTahunanfromDB($request);
        return view('dashboard.penjualan.RekapShipmentTahunan')->with([
            'kebun' => $kebun,
            'Rekap_ShipmentTahunan' => $Rekap_ShipmentTahunan,
            ]);
    }

    public function getHargaTenderFinalLTC(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Tender Final LTC') == false) abort('403-dashboard');

        $startdate   = isset($_GET['startdate']) ? $_GET['startdate'] : date('Y-m-d', strtotime('-7 days'));
        $enddate     = isset($_GET['enddate']) ? $_GET['enddate'] : date('Y-m-d');
        $site_id     = isset($_GET['site_id']) ? $_GET['site_id'] : '2100';
        $pmks        = isset($_GET['pmks']) ? $_GET['pmks'] : 'TELDA';
        $produk      = isset($_GET['produk']) ? $_GET['produk'] : 'CPO';
        $jenistender = isset($_GET['jenistender']) ? $_GET['jenistender'] : 'AVG';

        $data = DB::select(
            "SET NOCOUNT ON;
            EXEC PUBDB.Sales.sp_Dashboard_KontrakJual_HargaTenderFinal_LTC
                @startdate = ?,
                @endDate = ?,
                @site_id = ?,
                @pmks = ?,
                @produk = ?,
                @jenistender = ?",
            [$startdate, $enddate, $site_id, $pmks, $produk, $jenistender]
        );

        return view('dashboard.penjualan.HargaTenderFinalLTC')->with([
            'data' => $data
        ]);
    }

    public function getRekomendasiHargaTenderHarianPDP(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Rekomendasi Harga Tender Harian PDP') == false) abort('403-dashboard');

        $tanggal_awal  = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-d', strtotime('-7 days'));
        $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');
        $produk        = isset($_GET['produk']) ? $_GET['produk'] : 'CPO';

        $data = DB::select(
            "SET NOCOUNT ON;
            EXEC PUBDB.Sales.sp_Dashboard_Recommended_Tender_Price_Daily_PDP_GPT
                @TanggalAwal = ?,
                @TanggalAkhir = ?,
                @Produk = ?",
            [$tanggal_awal, $tanggal_akhir, $produk]
        );

        return view('dashboard.penjualan.RekomendasiHargaTenderHarianPDP')->with([
            'data' => $data
        ]);
    }

    public function getHargaRataRataTender(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Rata Rata Tender') == false) abort('403-dashboard');

        $tanggal_awal  = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-d', strtotime('-7 days'));
        $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');
        $jenis_tender  = isset($_GET['jenis_tender']) ? $_GET['jenis_tender'] : 'AVG';
        $site_id       = isset($_GET['site_id']) ? $_GET['site_id'] : '2100';
        $produk        = isset($_GET['produk']) ? $_GET['produk'] : 'CPO';

        $data = DB::select(
            "SET NOCOUNT ON;
            EXEC PUBDB.Sales.sp_Dashboard_Harga_AVG_Tender
                @TanggalAwal = ?,
                @TanggalAkhir = ?,
                @JenisTender = ?,
                @Siteid = ?,
                @Produk = ?",
            [$tanggal_awal, $tanggal_akhir, $jenis_tender, $site_id, $produk]
        );

        return view('dashboard.penjualan.HargaRataRataTender')->with([
            'data' => $data
        ]);
    }

}

