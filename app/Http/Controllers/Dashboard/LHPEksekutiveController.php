<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\ModulPerKebun;
use DB;
use DateTime;
use Auth;
use Carbon\Carbon;

class LHPEksekutiveController extends Controller
{
    public function getLhpEDMain (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP main') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35');

        $lhp_EDMain = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_EDMain = DB::select( "SET NOCOUNT ON ;  exec PUBDB.Produksi.RendemenMSSemuaPMKS @startDate = ?, @endDate = ? ", [$dari_tanggal, $sampai_tanggal] );

        return view('dashboard.lhpED.lhpEDMain')->with([
            'kebun' => $kebun,
            'lhp_EDMain' => $lhp_EDMain
            ]);
    }

    public function getLhpEDMainInti (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP main Inti') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35');

        $lhp_EDMain = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_EDMain = DB::select( " SET NOCOUNT ON ;  exec PUBDB.Produksi.RendemenISSemuaPMKS @startDate = ?, @endDate = ? ", [$dari_tanggal, $sampai_tanggal] );

        return view('dashboard.lhpED.lhpEDIntiMain')->with([
            'kebun' => $kebun,
            'lhp_EDMain' => $lhp_EDMain
            ]);
    }

    public function getLhpED (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP detail') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35');

        $lhp_ED = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        if($select_type == '0') {
            if($select_kebun == 'DBTimbPMKSTD') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_TELDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK1') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KALSA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK2') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KALDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSKK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KOKAR(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSRK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_RICKO(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSPS') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_PASER(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
        }
        else if ($select_type == '1'){
            if($select_kebun == 'DBTimbPMKSTD') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_TELDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK1') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_KALSA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK2') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_KALDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSKK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_KOKAR(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSRK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_RICKO(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSPS') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_PASER(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
        }

        return view('dashboard.lhpED.lhpED')->with([
            'kebun' => $kebun,
            'lhp_ED' => $lhp_ED,
            'select_type' => $select_type,
            'select_kebun' => $select_kebun
            ]);
    }

    public function getLhpEDInti (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP detail Inti') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35');

        $lhp_ED = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        if($select_type == '0') {
            if($select_kebun == 'DBTimbPMKSTD') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_TELDA_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK1') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KALSA_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK2') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KALDA_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSKK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_KOKAR_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSRK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_RICKO_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSPS') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_HARIAN_PASER_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
        }
        else if ($select_type == '1'){
            if($select_kebun == 'DBTimbPMKSTD') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_TELDA_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK1') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_KALSA_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSK2') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_KALDA_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSKK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_KOKAR_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSRK') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_RICKO_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
            else if($select_kebun == 'DBTimbPMKSPS') {
                $lhp_ED = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BULANAN_PASER_INTI(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
            }
        }

        return view('dashboard.lhpED.lhpEDInti')->with([
            'kebun' => $kebun,
            'lhp_ED' => $lhp_ED,
            'select_type' => $select_type,
            'select_kebun' => $select_kebun
            ]);
    }

    public function getLhpReportALB (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP report alb') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '4.8');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');

        $lhp_ralb = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        // $lhp_ralb = DB::select( "Select * from " . $select_kebun . ".Produksi.LHP_EXECUTIVE_TT(?,?, ?)", [$dari_tanggal, $sampai_tanggal, $toleransi] );

        if($select_kebun == 'DBTimbPMKSTD') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_TT_TELDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSK1') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_TT_KALSA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSK2') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_TT_KALDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSKK') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_TT_KOKAR(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSRK') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_TT_RICKO(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSPS') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_TT_PASER(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        return view('dashboard.lhpED.lhpReportALB')->with([
            'kebun' => $kebun,
            'lhp_ralb' => $lhp_ralb
            ]);
    }

    public function getLhpReportALBInti (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP report alb Inti') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '4.8');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');

        $lhp_ralb = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        // $lhp_ralb = DB::select( "Select * from " . $select_kebun . ".Produksi.LHP_EXECUTIVE_TT(?,?, ?)", [$dari_tanggal, $sampai_tanggal, $toleransi] );

        if($select_kebun == 'DBTimbPMKSTD') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BIN_TELDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSK1') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BIN_KALSA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSK2') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BIN_KALDA(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSKK') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BIN_KOKAR(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSRK') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BIN_RICKO(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        else if($select_kebun == 'DBTimbPMKSPS') {
            $lhp_ralb = DB::select( "Select * from PUBDB.Produksi.LHP_EXECUTIVE_BIN_PASER(?,?,?) ", [$dari_tanggal, $sampai_tanggal, $toleransi]);
        }
        return view('dashboard.lhpED.lhpReportALBInti')->with([
            'kebun' => $kebun,
            'lhp_ralb' => $lhp_ralb
            ]);
    }

    public function getLhpReportFFAallPMKS (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP ffa PMKS') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '4.8');
        $toleransiproduksi = (isset($_GET['toleransiproduksi']) ? $_GET['toleransiproduksi'] : '4.5');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');



        $lhp_rffaPMKS = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        if($select_kebun == 'SEMUA') {
            $lhp_rffaPMKS = DB::select("SET NOCOUNT ON ; EXEC PUBDB.PRODUKSI.MutuCPO_ALLPMKS_DASHBOARD @startDate = ? ,@endDate = ? ,@site = NULL ", [$dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == 'TELDA') {
            $lhp_rffaPMKS = DB::select("SET NOCOUNT ON ; EXEC PUBDB.PRODUKSI.MutuCPO_TELDA_DASHBOARD @startDate = ? ,@endDate = ? ,@site = 2200 ", [$dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == 'KALSA') {
            $lhp_rffaPMKS = DB::select("SET NOCOUNT ON ; EXEC PUBDB.PRODUKSI.MutuCPO_KALSA_DASHBOARD @startDate = ? ,@endDate = ? ,@site = 2300 ", [$dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == 'KALDA') {
            $lhp_rffaPMKS = DB::select("SET NOCOUNT ON ; EXEC PUBDB.PRODUKSI.MutuCPO_KALDA_DASHBOARD @startDate = ? ,@endDate = ? ,@site = 2400 ", [$dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == 'KOKAR') {
            $lhp_rffaPMKS = DB::select("SET NOCOUNT ON ; EXEC PUBDB.PRODUKSI.MutuCPO_KOKAR_DASHBOARD @startDate = ? ,@endDate = ? ,@site = 2500 ", [$dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == 'RICKO') {
            $lhp_rffaPMKS = DB::select("SET NOCOUNT ON ; EXEC PUBDB.PRODUKSI.MutuCPO_RICKO_DASHBOARD @startDate = ? ,@endDate = ? ,@site = 3200 ", [$dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == 'PASER') {
            $lhp_rffaPMKS = DB::select("SET NOCOUNT ON ; EXEC PUBDB.PRODUKSI.MutuCPO_PASER_DASHBOARD @startDate = ? ,@endDate = ? ,@site = 5200 ", [$dari_tanggal, $sampai_tanggal]);
        }

        return view('dashboard.lhpED.lhpReportFFAallPMKS')->with([
            'kebun' => $kebun,
            'lhp_rffaPMKS' => $lhp_rffaPMKS
            ]);
    }

    public function getLhpReportFFAallPMKSIntifromDB (Request $request)
    {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $toleransi = (isset($_GET['toleransi']) ? $_GET['toleransi'] : '4.8');
        $toleransiproduksi = (isset($_GET['toleransiproduksi']) ? $_GET['toleransiproduksi'] : '4.5');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');

        $lhp_rffaPMKS = [];
        if ($select_kebun == 'SEMUA') {
            $lhp_rffaPMKS = DB::select( " select TGL, IIF(Kebun = 'TD', 'TELDA', IIF(Kebun = 'K1', 'KALSA', IIF(Kebun = 'K2', 'KALDA', IIF(Kebun = 'KK', 'KOKAR', IIF(Kebun = 'PS', 'PASER', 'RICKO'))))) AS KEBUN, " .
                                    " IIF(Kebun = 'TD', 1, IIF(Kebun = 'K1', 2, IIF(Kebun = 'K2', 3, IIF(Kebun = 'KK', 4, IIF(Kebun = 'PS', 5, 6))))) AS NOURUT," .
                                    " FFA_BIN1, VOLUME_BIN1, " .
                                    " FFA_BIN2, VOLUME_BIN2, " .
                                    " FFA_BIN3, VOLUME_BIN3, " .
                                    " FFA_BIN4, VOLUME_BIN4, " .
                                    " FFA_PRODUKSI, VOLUME_PRODUKSI " .
                                    " FROM " .
                                    " PUBDB.dbo.LHP_FFA_INTI_HARIAN_SEMUA_KEBUN(?, ?, ?, ?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $toleransiproduksi] );
        }
        else {
            $lhp_rffaPMKS = DB::select( " select TGL, IIF(Kebun = 'TD', 'TELDA', IIF(Kebun = 'K1', 'KALSA', IIF(Kebun = 'K2', 'KALDA', IIF(Kebun = 'KK', 'KOKAR', IIF(Kebun = 'PS', 'PASER', 'RICKO'))))) AS KEBUN, " .
                                    " IIF(Kebun = 'TD', 1, IIF(Kebun = 'K1', 2, IIF(Kebun = 'K2', 3, IIF(Kebun = 'KK', 4, IIF(Kebun = 'PS', 5, 6))))) AS NOURUT," .
                                    " FFA_BIN1, VOLUME_BIN1, " .
                                    " FFA_BIN2, VOLUME_BIN2, " .
                                    " FFA_BIN3, VOLUME_BIN3, " .
                                    " FFA_BIN4, VOLUME_BIN4, " .
                                    " FFA_PRODUKSI, VOLUME_PRODUKSI " .
                                    " FROM " .
                                    " PUBDB.dbo.LHP_FFA_INTI_HARIAN_SEMUA_KEBUN(?, ?, ?, ?) " .
                                    "where IIF(Kebun = 'TD', 'TELDA', IIF(Kebun = 'K1', 'KALSA', IIF(Kebun = 'K2', 'KALDA', IIF(Kebun = 'KK', 'KOKAR', IIF(Kebun = 'PS', 'PASER', 'RICKO'))))) = ? ", [$dari_tanggal, $sampai_tanggal, $toleransi, $toleransiproduksi, $select_kebun]);
        }

        return $lhp_rffaPMKS;
    }

    public function getLhpReportFFAallPMKSInti (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP ffa PMKS Inti') == false) abort('403-dashboard');

        $lhp_rffaPMKS = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_rffaPMKS = $this->getLhpReportFFAallPMKSIntifromDB($request);

        return view('dashboard.lhpED.lhpReportFFAallPMKSInti')->with([
            'kebun' => $kebun,
            'lhp_rffaPMKS' => $lhp_rffaPMKS
            ]);
    }

    public function getLhpReportFFAallPMKSIntiExport (Request $request)
    {
        $lhp_rffaPMKS = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_rffaPMKS = $this->getLhpReportFFAallPMKSIntifromDB($request);

        $data= json_decode(json_encode($lhp_rffaPMKS), true);

        Excel::create('FFA IS all PMKS Export', function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use ($data){
                $sheet->fromArray($data);
            });
        })->export('xlsx')->download('xlsx');

        redirect('dashboard.lhpED.lhpReportFFAallPMKSInti')->with([
            'kebun' => $kebun,
            'lhp_rffaPMKS' => $lhp_rffaPMKS
            ]);
    }

    public function getLhpRealvsTargetfromDB(Request $request)
    {
        $dari_tanggal = isset($request['dari_tanggal'])
            ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
            : date("Y-m-d", strtotime("-7 days"));

        $sampai_tanggal = isset($request['sampai_tanggal'])
            ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
            : date("Y-m-d", strtotime("0 days"));

        $site_id = isset($_GET['selectkebun']) ? (int)$_GET['selectkebun'] : 2200;
        $select_type = isset($_GET['type']) ? $_GET['type'] : '0';
        $toleransi = isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35';
        $harga = isset($_GET['harga']) ? $_GET['harga'] : '5800';

        $lhp_RvsT = [];

        if ($select_type == '0') {
            if ($site_id == 2200) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_TELDA(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 2300) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_KALSA(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 2400) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_KALDA(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 2500) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_KOKAR(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 3200) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_RICKO(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 5200) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_PASER(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            }
        } else if ($select_type == '1') {
            if ($site_id == 2200) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_TELDA(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 2300) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_KALSA(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 2400) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_KALDA(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 2500) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_KOKAR(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 3200) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_RICKO(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            } else if ($site_id == 5200) {
                $lhp_RvsT = DB::select("Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_PASER(?,?,?,?)", [$dari_tanggal, $sampai_tanggal, $toleransi, $harga]);
            }
        }

        return $lhp_RvsT;
    }

    public function getLhpProporsiPerPemasokDashboard(Request $request, $site_id)
    {
        $dari_tanggal = isset($request['dari_tanggal'])
            ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])->format('Ymd')
            : date("Ymd", strtotime("-7 days"));

        $sampai_tanggal = isset($request['sampai_tanggal'])
            ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])->format('Ymd')
            : date("Ymd", strtotime("0 days"));

        $select_type = isset($_GET['type']) ? $_GET['type'] : '0';
        $harga = isset($_GET['harga']) ? $_GET['harga'] : '5800';

        if ($select_type == '0') {

            return DB::select("SET NOCOUNT ON ; 
                EXEC PUBDB.Produksi.HitungProduksiProporsiPerPemasokTBS_Harian_Dashboard_GPT
                    @startDate = ?,
                    @endDate = ?,
                    @site_id = ?,
                    @harga = ?
            ", [
                $dari_tanggal,
                $sampai_tanggal,
                $site_id,
                $harga
            ]);
        }

        return DB::select("SET NOCOUNT ON ; 
            EXEC PUBDB.Produksi.HitungProduksiProporsiPerPemasokTBS_Bulanan_Dashboard_GPT
                @startDate = ?,
                @endDate = ?,
                @site_id = ?,
                @harga = ?
        ", [
            $dari_tanggal,
            $sampai_tanggal,
            $site_id,
            $harga
        ]);
    }

    private function getFormulaBonusDendaProduksi(Request $request, $site_id)
    {
        /*
        * Formula menggunakan bulan dan tahun dari Sampai Tanggal.
        * Contoh:
        * sampai_tanggal = 20/07/2026
        * maka mencari TAHUN = 2026 dan BULAN = 7.
        */
        $sampaiTanggal = null;

        if (!empty($request->sampai_tanggal)) {
            $sampaiTanggal = DateTime::createFromFormat(
                'd/m/Y',
                $request->sampai_tanggal
            );
        }

        if (!$sampaiTanggal) {
            $sampaiTanggal = new DateTime();
        }

        $tahun = (int)$sampaiTanggal->format('Y');
        $bulan = (int)$sampaiTanggal->format('n');

        $result = DB::select("
            SELECT TOP 1
                TAHUN,
                BULAN,
                SITE_ID,
                KEBUN,
                FORMULA
            FROM PUBDB.Produksi.TblParamFormulaBonusDendaProduksi
            WHERE TAHUN = ?
            AND BULAN = ?
            AND SITE_ID = ?
        ", [
            $tahun,
            $bulan,
            (int)$site_id
        ]);

        if (count($result) > 0) {
            $result[0]->FORMULA = strtoupper(
                trim($result[0]->FORMULA)
            );

            return $result[0];
        }

        /*
        * Data kosong tetap dikembalikan sebagai object supaya Blade
        * tidak perlu terlalu banyak pemeriksaan.
        */
        return (object)[
            'TAHUN'   => $tahun,
            'BULAN'   => $bulan,
            'SITE_ID' => (int)$site_id,
            'KEBUN'   => '',
            'FORMULA' => ''
        ];
    }

    public function getLhpRealvsTarget(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Real Vs Target') == false) abort('403-dashboard');

        $select_kebun = isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200';
        $select_type = isset($_GET['type']) ? $_GET['type'] : '0';

        $lhp_RvsT = [];

        $kebun = [
            (object)['site_id' => 2200, 'kode' => 'TD', 'nama' => 'TELDA'],
            (object)['site_id' => 2300, 'kode' => 'K1', 'nama' => 'KALSA'],
            (object)['site_id' => 2400, 'kode' => 'K2', 'nama' => 'KALDA'],
            (object)['site_id' => 2500, 'kode' => 'KK', 'nama' => 'KOKAR'],
            (object)['site_id' => 3200, 'kode' => 'RK', 'nama' => 'RICKO'],
            (object)['site_id' => 5200, 'kode' => 'PS', 'nama' => 'PASER'],
        ];

        $site_id = (int)$select_kebun;

        $siteToPmks = [
            2200 => 'TD',
            2300 => 'K1',
            2400 => 'K2',
            2500 => 'KK',
            3200 => 'RK',
            5200 => 'PS',
        ];

        $pmks = isset($siteToPmks[$site_id]) ? $siteToPmks[$site_id] : 'TD';

        $lhp_RvsT = $this->getLhpRealvsTargetfromDB($request);
        $lhp_P3Detail = $this->getLhpP3DetailMonthly($request, $pmks);
        $lhp_ProporsiPerPemasok = $this->getLhpProporsiPerPemasokDashboard(
            $request,
            $site_id
        );

        /*
        * Ambil formula bonus/denda berdasarkan:
        * - tahun dari Sampai Tanggal
        * - bulan dari Sampai Tanggal
        * - site_id yang dipilih
        */
        $formulaBonusDenda = $this->getFormulaBonusDendaProduksi(
            $request,
            $site_id
        );

        return view('dashboard.lhpED.lhpRealisasiVsTarget')->with([
            'kebun' => $kebun,
            'lhp_RvsT' => $lhp_RvsT,
            'lhp_P3Detail' => $lhp_P3Detail,
            'lhp_ProporsiPerPemasok' => $lhp_ProporsiPerPemasok,
            'formulaBonusDenda' => $formulaBonusDenda,
            'select_type' => $select_type,
            'select_kebun' => $select_kebun
        ]);
    }

    public function getLHPHitunganP3 (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Real Vs Target') == false) abort('403-dashboard');

        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '1');
        $lhp_RvsT = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $pmks = substr($select_kebun, -2); 
        $lhp_RvsT = $this->getLhpRealvsTargetfromDB($request);
        $lhp_P3Detail = $this->getLhpP3DetailMonthly($request, $pmks);

        return view('dashboard.lhpED.HitunganPihak3')->with([
                'kebun' => $kebun,
                'lhp_RvsT' => $lhp_RvsT,
                'lhp_P3Detail' => $lhp_P3Detail,
                'select_type' => $select_type,
                'select_kebun' => $select_kebun
        ]);
    }

    private function parseDate($value, $default)
    {
        if(empty($value))
            return new \DateTime($default);

        // format d/m/Y
        $d = \DateTime::createFromFormat('d/m/Y', $value);
        if($d !== false)
            return $d;

        // format Y-m-d
        $d = \DateTime::createFromFormat('Y-m-d', $value);
        if($d !== false)
            return $d;

        // fallback (very important)
        return new \DateTime($default);
    }

    private function getMonthYearPairs($start, $end)
    {
        $pairs = [];

        // first day of start month
        $startMonth = new \DateTime($start->format('Y-m-01'));

        // first day of end month
        $endMonth = new \DateTime($end->format('Y-m-01'));
        $endMonth->modify('+1 month'); // DatePeriod end is exclusive

        $interval = new \DateInterval('P1M');

        $period = new \DatePeriod($startMonth, $interval, $endMonth);

        foreach ($period as $dt) {
            $pairs[] = [
                'bulan' => (int)$dt->format('n'),
                'tahun' => (int)$dt->format('Y'),
            ];
        }

        return $pairs;
    }

    private function getLhpP3DetailMonthly(Request $request, $pmks)
    {
        $dari = $this->parseDate($request->get('dari_tanggal'), '-7 days');
        $sampai = $this->parseDate($request->get('sampai_tanggal'), 'today');

        $rows = DB::select("
            SELECT 
                BLN AS BULAN,
                TAHUN,
                PMKS,
                SUPPLIERCODE AS NAMAGRUP,
                TBS_OLAH_PROPORSI AS TBSOLAHPROPORSI,
                CPO_TARGET AS CPOTARGET,
                REND_TARGET AS RENDTARGET,
                CPO_REALISASI AS TBSOLAHREALISASI,
                REND_REAL AS RENDREALISASI,
                SELISIH AS SELISIHKG,
                REND_REAL - REND_TARGET AS SELISIHRENDEMEN,
                HARGA AS TOTAL
            FROM PUBDB.Produksi.TblHitungProduksiP3Detail
            WHERE PMKS = ?
            AND DATEFROMPARTS(TAHUN, BLN, 1)
                BETWEEN DATEFROMPARTS(YEAR(?), MONTH(?), 1)
                AND DATEFROMPARTS(YEAR(?), MONTH(?), 1)

            UNION ALL

            SELECT
                BLN,
                TAHUN,
                PMKS,
                'TOTAL',
                SUM(TBS_OLAH_PROPORSI),
                SUM(CPO_TARGET),
                CAST((SUM(CPO_TARGET)*100.00)/SUM(TBS_OLAH_PROPORSI) AS decimal(18,2)),
                SUM(CPO_REALISASI),
                CAST((SUM(CPO_REALISASI)*100.00)/SUM(TBS_OLAH_PROPORSI) AS decimal(18,2)),
                SUM(SELISIH),
                CAST(
                    (SUM(CPO_REALISASI)*100.00)/SUM(TBS_OLAH_PROPORSI)
                    - (SUM(CPO_TARGET)*100.00)/SUM(TBS_OLAH_PROPORSI)
                AS decimal(18,2)),
                SUM(HARGA)
            FROM PUBDB.Produksi.TblHitungProduksiP3Detail
            WHERE PMKS = ?
            AND DATEFROMPARTS(TAHUN, BLN, 1)
                BETWEEN DATEFROMPARTS(YEAR(?), MONTH(?), 1)
                AND DATEFROMPARTS(YEAR(?), MONTH(?), 1)
            GROUP BY BLN, TAHUN, PMKS

            ORDER BY TAHUN, BULAN, NAMAGRUP
        ", [
            $pmks, $dari, $dari, $sampai, $sampai,
            $pmks, $dari, $dari, $sampai, $sampai
        ]);

        return $rows;
    }

    public function getLhpRealvsTargetExport (Request $request)
    {
        $lhp_RvsT = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_RvsT = $this->getLhpRealvsTargetfromDB($request);
        $data= json_decode( json_encode($lhp_RvsT), true);
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');
        $tambahannama = '';

        if($select_type == '0') {
            $tambahannama = '_Harian';
        }
        else {
            $tambahannama = '_Bulanan';
        }

        if($select_kebun == 'DBTimbPMKSTD') {
            $tambahannama .= 'TELDA';
        }
        else if($select_kebun == 'DBTimbPMKSK1') {
            $tambahannama .= 'KALSA';
        }
        else if($select_kebun == 'DBTimbPMKSK2') {
            $tambahannama .= 'KALDA';
        }
        else if($select_kebun == 'DBTimbPMKSKK') {
            $tambahannama .= 'KOKAR';
        }
        else if($select_kebun == 'DBTimbPMKSRK') {
            $tambahannama .= 'RICKO';
        }
        else if($select_kebun == 'DBTimbPMKSPS') {
            $tambahannama .= 'PASER';
        }

        Excel::create('RealVsTarget_Export' . $tambahannama, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use ($data){
                $sheet->fromArray($data);
            });
        })->download('csv');

        redirect('dashboard.lhpED.lhpRealisasiVsTarget')->with([
            'kebun' => $kebun,
            'lhp_RvsT' => $lhp_RvsT
            ]);
    }



    public function getLhpRestanPanenfromDB (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Restan Panen') == false) abort('403-dashboard');
        $dari_tanggal = (isset($request['dari_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                          : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                          ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                          : date("Y-m-d", strtotime("0 days")));

        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        // return DB::select( "select SITE_ID, TGLANGKUT, " .
        //                                 " RESTAN_HARI_INI, " .
        //                                 " RESTAN_LEWAT_1_HARI_TERANGKUT, " .
        //                                 " RESTAN_LEWAT_2_HARI_TERANGKUT, " .
        //                                 " RESTAN_LEWAT_3_HARI_TERANGKUT, " .
        //                                 " RESTAN_LEWAT_4_HARI_TERANGKUT " .
        //                                 " FROM PUBDB.Tanaman.Tbl_Restan_Dari_Pengangkutan_V2(?,?,?)", [$select_kebun, $dari_tanggal, $sampai_tanggal]);
        return DB::select( "select SITE_ID, TGLANGKUT, " .
                                        " RESTAN_HARI_INI, " .
                                        " RESTAN_LEWAT_1_HARI_TERANGKUT, " .
                                        " RESTAN_LEWAT_2_HARI_TERANGKUT, " .
                                        " RESTAN_LEWAT_3_HARI_TERANGKUT, " .
                                        " RESTAN_LEWAT_4_HARI_TERANGKUT " .
                                        " FROM PUBDB.Tanaman.Tbl_Restan_Dari_Pengangkutan_V2(?,?,?)", [$select_kebun, $dari_tanggal, $sampai_tanggal]);
    }

    public function getLhpRestanPanen (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Restan Panen') == false) abort('403-dashboard');
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_RestanPanen = $this->getLhpRestanPanenfromDB($request);

        return view('dashboard.lhpED.lhpRestanPanen')->with([
            'kebun' => $kebun,
            'lhp_RestanPanen' => $lhp_RestanPanen,
            ]);
    }

    public function CsiteIDtoNamaKebun (Request $request) {
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        if($select_kebun == '2200') {
            $namakebun = 'TELDA';
        }
        else if ($select_kebun == '2300') {
            $namakebun = 'KALSA';
        }
        else if ($select_kebun == '2400') {
            $namakebun = 'KALDA';
        }
        else if ($select_kebun == '2500') {
            $namakebun = 'KOKAR';
        }
        else if ($select_kebun == '2600') {
            $namakebun = 'MITRA KOKAR';
        }
        else if ($select_kebun == '3200') {
            $namakebun = 'RICKO';
        }
        else if ($select_kebun == '4200') {
            $namakebun = 'MUARA';
        }
        else if ($select_kebun == '5200') {
            $namakebun = 'PASER';
        }
        else if ($select_kebun == '6200') {
            $namakebun = 'LANGGAI';
        }
        else if ($select_kebun ==  'SEMUA') {
            $namakebun = 'SEMUA';
        }
        return $namakebun;
    }

    public function getLhpRestanPanenExport (Request $request) {

        $lhp_RestanPanen = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_RestanPanen = $this->getLhpRestanPanenfromDB($request);
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        $namakebun = '';

        if($select_kebun == '2200') {
            $namakebun = 'TELDA';
        }
        else if ($select_kebun == '2300') {
            $namakebun = 'KALSA';
        }
        else if ($select_kebun == '2400') {
            $namakebun = 'KALDA';
        }
        else if ($select_kebun == '2500') {
            $namakebun = 'KOKAR';
        }
        else if ($select_kebun == '2600') {
            $namakebun = 'MITRA KOKAR';
        }
        else if ($select_kebun == '3200') {
            $namakebun = 'RICKO';
        }
        else if ($select_kebun == '4200') {
            $namakebun = 'MUARA';
        }
        else if ($select_kebun == '5200') {
            $namakebun = 'PASER';
        }
        else if ($select_kebun == '6200') {
            $namakebun = 'LANGGAI';
        }
        $data= json_decode( json_encode($lhp_RestanPanen), true);

        Excel::create('RestanPanenExport' . $namakebun, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use ($data){
                $sheet->fromArray($data);
            });
        })->export('xlsx')->download('xlsx');

        redirect('dashboard.lhpED.lhpRestanPanen')->with([
            'kebun' => $kebun,
            'lhp_RestanPanen' => $lhp_RestanPanen,
        ]);
    }



    // get TBS Olah from DB
    public function getLHPTBSOlahfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));

        return DB::select( "Select * FROM PUBDB.dbo.LHP_EXECUTIVE_TBS_OLAH(?,?)", [$dari_tanggal, $sampai_tanggal]);
    }

    // Return Data to View
    public function getLhpTBSOlah (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP TBS Olah') == false) abort('403-dashboard');

        $lhp_TBSOlah = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_TBSOlah = $this->getLHPTBSOlahfromDB($request);

        return view('dashboard.lhpED.lhpTBSOlah')->with([
            'kebun' => $kebun,
            'lhp_TBSOlah' => $lhp_TBSOlah,
            ]);
    }

    // Get Data and return as Exported Excel
    public function getLHPTBSOlahExport (Request $request) {

        $lhp_TBSOlah = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_TBSOlah = $this->getLHPTBSOlahfromDB($request);
        $data= json_decode( json_encode($lhp_TBSOlah), true);

        Excel::create('TBSOlahExport', function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use ($data){
                $sheet->fromArray($data);
            });
        })->export('xlsx')->download('xlsx');

        redirect('dashboard.lhpED.lhpTBSOlah')->with([
            'kebun' => $kebun,
            'lhp_TBSOlah' => $lhp_TBSOlah,
        ]);
    }

    // get Restan Panen blm angkut from DB
    public function getlhpRestanPanenBlmAngkutfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])->format('Y-m-d')
                         : date("Y-m-d", strtotime("-7 days")));
        
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])->format('Y-m-d')
                         : date("Y-m-d", strtotime("0 days")));
        // $sampai_tanggal = date("Y-m-d", strtotime("0 days"));
        
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        // if($select_kebun == 'SEMUA') {
        //     return DB::select( "Select * FROM PUBDB.Tanaman.Tbl_TBSBKM_R0_R1_R2_R3_R4_GABUNGAN(?,?)", [ $dari_tanggal, $sampai_tanggal]);
        // }
        // else {
        //     return DB::select( "Select * FROM PUBDB.Tanaman.Tbl_TBSBKM_R0_R1_R2_R3_R4(?,?,?)", [ $select_kebun ,$dari_tanggal, $sampai_tanggal]);
        // }

        // if($select_kebun == 'SEMUA') {
        //     return DB::select( "Select * FROM PUBDB.Tanaman.Tbl_TBSBKM_R0_R1_R2_R3_V2_GABUNGAN(?,?)", [ $dari_tanggal, $sampai_tanggal]);
        // }
        // else {
        //     return DB::select( "Select * FROM PUBDB.Tanaman.Tbl_TBSBKM_R0_R1_R2_R3_V2_GABUNGAN(?,?) WHERE SITE_ID = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        // }

        // if($select_kebun == 'SEMUA') {
        //     return DB::select( "Select * FROM PUBDB.Tanaman.TBSRestanR0R5_ALL(?,?)", [ $dari_tanggal, $sampai_tanggal]);
        // }
        // else {
        //     return DB::select( "Select * FROM PUBDB.Tanaman.TBSRestanR0R5_ALL(?,?) WHERE SITE_ID = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        // }
        // return DB::select( "Select * FROM PUBDB.Tanaman.TBSRestanR0R5_ALL(?,?)", [ $dari_tanggal, $sampai_tanggal]);
        return DB::select( "SET NOCOUNT ON ; EXEC [PUBDB].[Tanaman].[TblRestan] " . 
                            " @startDate = '" . $dari_tanggal . "', " . 
                            " @endDate = '" . $sampai_tanggal . "', " . 
                            " @site = ?", [$select_kebun]);
    }

    // get Restan Panen blm angkut return to view
    public function getlhpRestanPanenBlmAngkut (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Restan Panen Blm Angkut') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_RestanPanenBlmAngkut = $this->getlhpRestanPanenBlmAngkutfromDB($request);
        


        return view('dashboard.ProduksiTBS.lhpRestanPanenBlmAngkut')->with([
            'kebun' => $kebun,
            'lhp_RestanPanenBlmAngkut' => $lhp_RestanPanenBlmAngkut,
            ]);
    }

    // Export Restan Panen Blm Angkut

    // get Weather Station from DB
    public function getlhpWeatherStationfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        return DB::select( "Select * FROM PUBDB.WeatherStation.FuncRainfall(?,?,?)", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
    }

    // get Weather Station return to view
    public function getlhpWeatherStation (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Curah Hujan') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_WeatherStation = $this->getlhpWeatherStationfromDB($request);


        return view('dashboard.ProduksiTBS.lhpCurahHujan')->with([
            'kebun' => $kebun,
            'lhp_WeatherStation' => $lhp_WeatherStation,
            ]);
    }

    // get Weather Station V2 from DB
    public function getlhpWeatherStationV2fromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        if($select_kebun == '2200') {
            return DB::select( "select 'TELDA' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSTelda(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if($select_kebun == '2300') {
            return DB::select( "select 'KALSA' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSKalsa(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if($select_kebun == '2400') {
            return DB::select( "select 'KALDA' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSKalda(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if($select_kebun == '2500') {
            return DB::select( "select 'KOKAR' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSKokar(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if($select_kebun == '3200') {
            return DB::select( "select 'RICKO' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSRicko(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if($select_kebun == '4200') {
            return DB::select( "select 'MUARA' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSMuara(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if($select_kebun == '5200') {
            return DB::select( "select 'PASER' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSPaser(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
        else if ($select_kebun == '6200') {
            return DB::select( "select 'LANGGAI' AS KEBUN, * FROM PUBDB.WeatherStation.TblDashboardWSLanggai(?,?,?) order by tanggal", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
    }

    // get Weather Station V2 return to view
    public function getlhpWeatherStationV2 (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Curah Hujan V2') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_WeatherStation = $this->getlhpWeatherStationV2fromDB($request);


        return view('dashboard.ProduksiTBS.lhpCurahHujanV2')->with([
            'kebun' => $kebun,
            'lhp_WeatherStation' => $lhp_WeatherStation,
            ]);
    }

    // get Persediaan Produk Sampingan from DB
    public function getProdukSampinganfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        $select_product = (isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'PALM ACID OIL');

        return DB::select( "SET NOCOUNT ON; EXEC PUBDB.Production.BYPRODUCTFFB @startDate= ?, @endDate = ?, @site = ?, @produk = ?;" , [$dari_tanggal, $sampai_tanggal, $select_kebun, $select_product]);
    }

    // get Persediaan Produk Sampingan , return to view
    public function getProdukSampingan (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Persediaan Produk Sampingan') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_ProdukSampingan = $this->getProdukSampinganfromDB($request);


        return view('dashboard.lhpED.lhpByProduct')->with([
            'kebun' => $kebun,
            'lhp_ProdukSampingan' => $lhp_ProdukSampingan,
            ]);
    }

    // get Restan Panen bulanan from DB
    public function getlhpRestanPanenBulananfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));

        return DB::select( "Select * FROM PUBDB.Tanaman.Tbl_Restan_Bulanan_Dari_BKM(?,?)", [ $dari_tanggal, $sampai_tanggal]);
    }

    // get Restan Panen bulanan return to view
    public function getlhpRestanPanenBulanan (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'Restan Panen Bulanan') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_RestanPanenBulanan = $this->getlhpRestanPanenBulananfromDB($request);


        return view('dashboard.lhpED.lhpRestanBulanan')->with([
            'kebun' => $kebun,
            'lhp_RestanPanenBulanan' => $lhp_RestanPanenBulanan,
            ]);
    }

    // get Persediaan Produk Sampingan from DB
    public function getProdukSampinganBulananfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_product = (isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'PALM ACID OIL');

        return DB::select( "select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + CAST(SITE_ID as Varchar)) ASC) as BARIS, * FROM PUBDB.Produksi.Tbl_BYPRODUCT_ALL_PMKS(?,?) WHERE " .
                            " PRODUK = ? " .
                            " ORDER BY ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + CAST(SITE_ID as Varchar)) ASC)" , [$dari_tanggal, $sampai_tanggal, $select_product]);

    }

    // get Persediaan Produk Sampingan , return to view
    public function getProdukSampinganBulanan (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Persediaan Produk Sampingan Bulanan') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_ProdukSampinganBulanan = $this->getProdukSampinganBulananfromDB($request);


        return view('dashboard.lhpED.lhpByProductBulanan')->with([
            'kebun' => $kebun,
            'lhp_ProdukSampinganBulanan' => $lhp_ProdukSampinganBulanan,
            ]);
    }

    // get Produksi TBS from DB
    public function getProduksiAngkutTBSfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        return DB::select( "select Tanggal as TGL, SITE_ID, SUM(BERAT_BERSIH_TBS) as BERAT_BERSIH_TBS, SUM(BERAT_BERSIH_BRONDOLAN) as BERAT_BERSIH_BRONDOLAN , SUM(BERATBERSIH) as BERATBERSIH , SUM(JLH_TRIP) as JLH_TRIP" .
                            " FROM PUBDB.Produksi.TblProduksiDanPengangkutanTBS(?,?,?) GROUP BY Tanggal, SITE_ID Order BY TGL" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);

    }

    public function getProduksiTBSDetailfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        return DB::select( "select Tanggal as TGL, SITE_ID, ORI_NAME, BERATBERSIH, JLH_TRIP" .
                            " FROM PUBDB.Produksi.TblProduksiDanPengangkutanTBS(?,?,?) ORDER BY Tanggal,ORI_NAME" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);
    }

    // get Persediaan Produk Sampingan , return to view
    public function getProduksiAngkutTBS (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Produksi TBS') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_ProduksiTBS = $this->getProduksiAngkutTBSfromDB($request);
        $lhp_ProduksiTBSDetail = $this->getProduksiTBSDetailfromDB($request);
        $namakebun = $this->CsiteIDtoNamaKebun($request);

        return view('dashboard.ProduksiTBS.lhpProduksi&AngkutTBS')->with([
            'kebun' => $kebun,
            'namakebun' => $namakebun,
            'lhp_ProduksiTBS' => $lhp_ProduksiTBS,
            'lhp_ProduksiTBSDetail' => $lhp_ProduksiTBSDetail,
            ]);
    }

    public function getProduksiTBSfromDB(Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        if($select_kebun == '2200') {
            return DB::select("select * FROM PUBDB.Produksi.TblWB_Eplant_TELDA(?,?,?)" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == '2300') {
            return DB::select("select * FROM PUBDB.Produksi.TblWB_Eplant_KALSA(?,?,?)" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == '2400') {
            return DB::select("select * FROM PUBDB.Produksi.TblWB_Eplant_KALDA(?,?,?)" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == '2500') {
            return DB::select("select * FROM PUBDB.Produksi.TblWB_Eplant_KOKAR(?,?,?)" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == '3200') {
            return DB::select("select * FROM PUBDB.Produksi.TblWB_Eplant_RICKO(?,?,?)" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun == '5200') {
            return DB::select("select * FROM PUBDB.Produksi.TblWB_Eplant_PASER(?,?,?)" , [$select_kebun, $dari_tanggal, $sampai_tanggal]);
        }
    }

    public function getProduksiTBS(Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Produksi TBS') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_ProduksiTBS = $this->getProduksiTBSfromDB($request);
        $namakebun = $this->CsiteIDtoNamaKebun($request);

        return view('dashboard.ProduksiTBS.lhpProduksiTBS')->with([
            'kebun' => $kebun,
            'namakebun' => $namakebun,
            'lhp_ProduksiTBS' => $lhp_ProduksiTBS,
            ]);
    }

    //get TBS Tersedia from DB
    public function getTBSTersediafromDB(Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');
        $namakebun = $this->CsiteIDtoNamaKebun($request);

        // if($select_kebun == '2200') {
        //     return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaTELDA(?,?)" , [$dari_tanggal, $sampai_tanggal]);
        // }
        // else if($select_kebun == '2300') {
        //     return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaKALSA(?,?)" , [$dari_tanggal, $sampai_tanggal]);
        // }
        // else if($select_kebun == '2400') {
        //     return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaKALDA(?,?)" , [$dari_tanggal, $sampai_tanggal]);

        // }
        // else if($select_kebun == '2500') {
        //     return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaKOKAR(?,?)" , [$dari_tanggal, $sampai_tanggal]);
        // }
        // else if($select_kebun == '3200') {
        //     return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaRICKO(?,?)" , [$dari_tanggal, $sampai_tanggal]);
        // }
        // else if($select_kebun == '5200') {
        //     return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaPASER(?,?)" , [$dari_tanggal, $sampai_tanggal]);
        // }

        if($select_kebun == 'SEMUA') {
            return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaALL(?,?)" , [$dari_tanggal, $sampai_tanggal]);
        }
        else if($select_kebun != 'SEMUA') {
            return DB::select("select * FROM PUBDB. Produksi.TblTBSTersediaALL(?,?) where KEBUN = ? " , [$dari_tanggal, $sampai_tanggal, $namakebun]);
        }
    }

    // get TBS Tersedia
    public function getTBSTersedia(Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP TBS Tersedia') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_TBSTersedia = $this->getTBSTersediafromDB($request);
        $namakebun = $this->CsiteIDtoNamaKebun($request);

        return view('dashboard.ProduksiTBS.lhpTBSTersedia')->with([
            'kebun' => $kebun,
            'namakebun' => $namakebun,
            'lhp_TBSTersedia' => $lhp_TBSTersedia,
            ]);
    }

    // get Waktu Porses LHP from DB
    public function getWaktuProsesLHPfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');

        if($select_kebun == 'SEMUA') {
            return DB::select("Select * from PUBDB.Produksi.TblWktPenyelesaianLHPWeb(?,?)" , [$dari_tanggal, $sampai_tanggal]);
        }
        else {
            return DB::select("Select * from PUBDB.Produksi.TblWktPenyelesaianLHPWeb(?,?) Where KEBUN = ? " , [$dari_tanggal, $sampai_tanggal , $select_kebun]);
        }
    }

    // get Waktu Proses LHP
    public function getWaktuProsesLHP(Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Waktu Proses') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_WaktuProsesLHP = $this->getWaktuProsesLHPfromDB($request);


        return view('dashboard.lhpED.lhpWaktuProsesLHP')->with([
            'kebun' => $kebun,
            'lhp_WaktuProsesLHP' => $lhp_WaktuProsesLHP,
            ]);
    }

    // get Budget Produksi from DB
    public function getBudgetProduksifromDB (Request $request) {

        $select_tahun = (isset($_GET['select_tahun']) ? $_GET['select_tahun'] : '2022');
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');

        return DB::select("select  COMP_ID,SITE_ID, YEAR, MONTH, ESTATENAME, DIVISIONNAME, MONTHLYBUDGET FROM PUBDB.Budget.TblBudgetProduction(2000) " .
                          " WHERE  SITE_ID = ? " .
                          " and YEAR = ? " .
                          " ORDER BY DIVISIONCODE, MONTH", [$select_tahun, $select_kebun]);
    }

    // get Budget Produksi
    public function getBudgetProduksi(Request $request) {
        if( Auth::user()->canAccessByHakAkses('LHP', 'LHP Budget Produksi') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_BudgetProduksi = $this->getBudgetProduksifromDB($request);

        return view('dashboard.ProduksiTBS.lhpBudgetProduksi')->with([
            'kebun' => $kebun,
            'lhp_BudgetProduksi' => $lhp_BudgetProduksi
        ]);
    }

    // get Produksi TBS Per 2 Jam from DB
    public function getProduksiTBSPer2JamfromDB (Request $request) {
        $per_tanggal = (isset($request['per_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['per_tanggal'])->format('Y-m-d') : date("Y-m-d", strtotime("0 days")));

        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        return DB::select("select ROW_NUMBER() OVER (ORDER BY ( CAST(AFDELING as Varchar)) ASC) as BARIS, SITE_ID, AFDELING, TANGGAL, J05_07, J07_09, J09_11,J11_13,J13_15, J15_17, J17_19,J19_21,J21_23,J23_01,TOTAL, DAILYBUDGET FROM PUBDB.Produksi.TblProduksiTBSPer2Jam(2000) " .
                            " WHERE SITE_ID = ? " .
                            " and TANGGAL = ? " .
                            " ORDER BY AFDELING", [$select_kebun, $per_tanggal]);

    }

    public function getProduksiTBSPer2Jam (Request $request) {
        if( Auth::user()->canAccessByHakAkses('LHP', 'LHP Produksi per 2 Jam') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_Produksi2Jam = $this->getProduksiTBSPer2JamfromDB($request);

        return view('dashboard.ProduksiTBS.ProduksiTBSper2jam')->with([
            'kebun' => $kebun,
            'lhp_Produksi2Jam' => $lhp_Produksi2Jam
        ]);
    }

    // get Pencapaian Produksi from DB
    public function getPencapaianProduksifromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        if($select_kebun == 'SEMUA') {
            return DB::select("select * FROM PUBDB.Tanaman.TblBudget_Realisasi_Pencapaian_All(?,?) ", [$dari_tanggal, $sampai_tanggal]);
        }
        else {
            return DB::select("select * FROM PUBDB.Tanaman.TblBudget_Realisasi_Pencapaian_All(?,?) where SITE_ID = ? ", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
    }

    public function getPencapaianProduksi (Request $request) {
        if( Auth::user()->canAccessByHakAkses('LHP', 'LHP Pencapaian Produksi') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_PencapaianProduksi = $this->getPencapaianProduksifromDB($request);

        return view('dashboard.ProduksiTBS.lhpPencapaianProduksiTBS')->with([
            'kebun' => $kebun,
            'lhp_PencapaianProduksi' => $lhp_PencapaianProduksi
        ]);
    }

    public function getInventoryTBSfromDB(Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');

        if($select_type == '0') {
            if($select_kebun == 'SEMUA') {
                return DB::select("select TANGGAL, SITE_ID, STATUS, SALDOAWALTBS, TBSMASUK, TBSOLAH, PENYESUAIAN, SALDOAKHIRTBS  " . 
                                    " FROM PUBDB.Produksi.TblInventoryTBS_1_Harian(?,?) where STATUS not like '%TOTAL%'", [$dari_tanggal, $sampai_tanggal]);
            }
            else { 
                return DB::select("select TANGGAL, SITE_ID, STATUS, SALDOAWALTBS, TBSMASUK, TBSOLAH, PENYESUAIAN, SALDOAKHIRTBS  " . 
                                    " FROM PUBDB.Produksi.TblInventoryTBS_1_Harian(?,?)  WHERE SITE_ID = ? ", [$dari_tanggal,$sampai_tanggal, $select_kebun]);
            }
        }
        else if($select_type == '1') { 
            return DB::select("select *  FROM PUBDB.Produksi.TblInventoryTBS_1_Bulanan(?,?)  WHERE SITE_ID = ?", [$dari_tanggal,$sampai_tanggal,$select_kebun]);    
        }
    }

    public function getInventoryTBS (Request $request) {
        if( Auth::user()->canAccessByHakAkses('LHP', 'LHP Inventory TBS') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_InventoryTBS = $this->getInventoryTBSfromDB($request);

        return view('dashboard.ProduksiTBS.InventoryTBS')->with([
            'kebun' => $kebun,
            'lhp_InventoryTBS' => $lhp_InventoryTBS
        ]);
    }

    public function getLhpRealvsTargetIntifromDB (Request $request)
    {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');

        $lhp_RvsT = [];
        if($select_type == '0') {
            if($select_kebun == 'DBTimbPMKSTD') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, " .
                                    " * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_KALDA_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSK1') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, " .
                                    " * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_KALSA_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSK2') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, " .
                                    " * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_KALDA_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSKK') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, " .
                                    " * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_KOKAR_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSRK') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, " .
                                    " * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_RICKO_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSPS') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(TGL AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS, " .
                                    " * from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_HARIAN_DENGAN_RESTAN_PASER_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }

        }
        else if ($select_type == '1'){
            if($select_kebun == 'DBTimbPMKSTD') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS ,* from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_TELDA_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSK1') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS ,* from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_KALSA_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSK2') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS ,* from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_KALDA_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSKK') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS ,* from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_KOKAR_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSRK') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS ,* from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_RICKO_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
            else if($select_kebun == 'DBTimbPMKSPS') {
                $lhp_RvsT = DB::select( "Select ROW_NUMBER() OVER (ORDER BY (CAST(BULAN AS varchar(15)) + '-' + NAMA_GRUP) Asc) AS BARIS ,* from PUBDB.Produksi.LHP_EXECUTIVE_REALISASI_VS_TARGET_BULANAN_DENGAN_RESTAN_PASER_INTI(?,?)", [$dari_tanggal, $sampai_tanggal]);
            }
        }

        return $lhp_RvsT;
    }

    public function getLhpRealvsTargetInti (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Real Vs Target Inti') == false) abort('403-dashboard');

        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');
        $lhp_RvsT = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_RvsT = $this->getLhpRealvsTargetIntifromDB($request);
        // echo $lhp_RvsT;

        return view('dashboard.lhpED.lhpRealisasiVsTargetInti')->with([
            'kebun' => $kebun,
            'lhp_RvsT' => $lhp_RvsT,
            'select_type' => $select_type,
            'select_kebun' => $select_kebun
            ]);
    } 

    public function getMutasiTBSFromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');

        if($select_type == '0') {
            if($select_kebun == 'SEMUA') {
                return DB::select("SET NOCOUNT ON ; exec PUBDB.Produksi.InventoryTBSHarian_Dashboard_Detail @startDate = ?, @endDate = ?, @filter = 'ALL',  @site = null", [$dari_tanggal, $sampai_tanggal]);
            }
            else { 
                return DB::select("SET NOCOUNT ON ; exec PUBDB.Produksi.InventoryTBSHarian_Dashboard_Detail @startDate = ?, @endDate = ?, @filter = 'SITE',  @site = ?", [$dari_tanggal,$sampai_tanggal, $select_kebun]);
            }
        }
        else if($select_type == '1') { 
            if($select_kebun == 'SEMUA') {
                return DB::select("SET NOCOUNT ON ; exec PUBDB.Produksi.InventoryTBSBulanan_Dashboard @startDate = ? , @endDate = ?, @filter = 'ALL', @site = null" , [$dari_tanggal , $sampai_tanggal]);
            } 
            else {
                return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryTBSBulanan_Dashboard @startDate = ?, @endDate = ?, @filter = 'SITE', @site = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
            }
        }
    }

    public function getMutasiTBS (Request $request) {
        if( Auth::user()->canAccessByHakAkses('LHP', 'LHP Mutasi TBS') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_mutasiTBS = $this->getMutasiTBSFromDB($request);

        return view('dashboard.ProduksiTBS.MutasiPersediaanTBS')->with([
            'kebun' => $kebun,
            'lhp_mutasiTBS' => $lhp_mutasiTBS
        ]);
    }

    public function getMutasiCIFromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');
        $select_type = (isset($_GET['type']) ? $_GET['type'] : '0');
        $select_product = (isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'SEMUA');

        if($select_type == '0') {
            if($select_kebun == 'SEMUA') {
                if($select_product == 'SEMUA') {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIHarian_Dashboard_Detail @startDate = ?, @endDate = ?, @filter = 'ALL', @Produk = 'ALL',  @site = null" , [$dari_tanggal, $sampai_tanggal]);
                }
                else {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIHarian_Dashboard_Detail @startDate = ?, @endDate = ?, @filter = 'ALL', @Produk = ?,  @site = null" , [$dari_tanggal, $sampai_tanggal, $select_product]);
                }
            }
            else { 
                if($select_product == 'SEMUA') {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIHarian_Dashboard_Detail @startDate = ?, @endDate = ?, @filter = 'SITE', @Produk = 'ALL',  @site = ?" , [$dari_tanggal, $sampai_tanggal, $select_kebun]);
                }
                else {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIHarian_Dashboard_Detail @startDate = ?, @endDate = ?, @filter = 'SITE', @Produk = ?,  @site = ?" , [$dari_tanggal, $sampai_tanggal, $select_product, $select_kebun]);
                }
            }
        }
        else if($select_type == '1') { 
            if($select_kebun == 'SEMUA') {
                if($select_product == 'SEMUA') {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIBulanan_Dashboard @startDate = ?, @endDate = ?, @filter = 'ALL', @produk = 'ALL', @site = null" , [$dari_tanggal, $sampai_tanggal]);
                }
                else {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIBulanan_Dashboard @startDate = ?, @endDate = ?, @filter = 'ALL', @Produk = ?,  @site = null" , [$dari_tanggal, $sampai_tanggal, $select_product]);
                }
            }
            else { 
                if($select_product == 'SEMUA') {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIBulanan_Dashboard @startDate = ?, @endDate = ?, @filter = 'SITE', @Produk = 'ALL',  @site = ?" , [$dari_tanggal, $sampai_tanggal, $select_kebun]);
                }
                else {
                    return DB::select("SET NOCOUNT ON; exec PUBDB.Produksi.InventoryCPO_INTIBulanan_Dashboard @startDate = ?, @endDate = ?, @filter = 'SITE', @Produk = ?,  @site = ?" , [$dari_tanggal, $sampai_tanggal, $select_product, $select_kebun]);
                }
            }
        }
    }

    public function getMutasiCI (Request $request) {
        if( Auth::user()->canAccessByHakAkses('LHP', 'LHP Mutasi CI') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_mutasiCI = $this->getMutasiCIFromDB($request);

        return view('dashboard.lhpED.MutasiPersediaanCPOInti')->with([
            'kebun' => $kebun,
            'lhp_mutasiCI' => $lhp_mutasiCI
        ]);
    }

    // get Restan Panen BJR from DB
    public function getLHPRestanPanenBJRFromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])->format('Y-m-d')
                         : date("Y-m-d", strtotime("-7 days")));
        
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])->format('Y-m-d')
                         : date("Y-m-d", strtotime("0 days")));
        // $sampai_tanggal = date("Y-m-d", strtotime("0 days"));
        
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        if($select_kebun == 'SEMUA') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Tanaman.TblRestanBJRFinal_temp_dashboard @startDate = ?, @endDate = ?, @site = null", [$dari_tanggal, $sampai_tanggal]);
        }
        else {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Tanaman.TblRestanBJRFinal_temp_dashboard @startDate = ?, @endDate = ?, @site = ?", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }
    }

    // get Restan Panen BJR return to view
    public function getLHPRestanPanenBJR (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Restan Panen BJR') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_RestanPanenBJR = $this->getLHPRestanPanenBJRFromDB($request);
        return view('dashboard.ProduksiTBS.lhpRestanPanenBJR')->with([
            'kebun' => $kebun,
            'lhp_RestanPanenBJR' => $lhp_RestanPanenBJR,
            ]);
    }

    public function getMSISPerGrupFromDB (Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date("Y"));
        
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        if ($select_kebun == '2200') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Produksi_CPO_PK_PerGrup_TELDA_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if($select_kebun == '2300') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Produksi_CPO_PK_PerGrup_KALSA_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if ($select_kebun == '2400') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Produksi_CPO_PK_PerGrup_KALDA_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if ($select_kebun == '2500') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Produksi_CPO_PK_PerGrup_KOKAR_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if ($select_kebun == '3200') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Produksi_CPO_PK_PerGrup_RICKO_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        } 
        else if ($select_kebun == '5200') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Produksi_CPO_PK_PerGrup_PASER_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
    }

    // get MS IS PerGrup return to view
    public function getMSISPerGrup (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Get MS IS') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_MSISPerGrup = $this->getMSISPerGrupFromDB($request);
        return view('dashboard.lhpED.ProduksiMSISPerGrup')->with([
            'kebun' => $kebun,
            'lhp_MSISPerGrup' => $lhp_MSISPerGrup,
            ]);
    }

    public function getPengeluaranMSISPerGrupFromDB (Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date("Y"));
        
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        if ($select_kebun == '2200') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Pengiriman_CPO_PK_PerGrup_LHP_TELDA_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if($select_kebun == '2300') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Pengiriman_CPO_PK_PerGrup_LHP_KALSA_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if ($select_kebun == '2400') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Pengiriman_CPO_PK_PerGrup_LHP_KALDA_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if ($select_kebun == '2500') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Pengiriman_CPO_PK_PerGrup_LHP_KOKAR_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
        else if ($select_kebun == '3200') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Pengiriman_CPO_PK_PerGrup_LHP_RICKO_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        } 
        else if ($select_kebun == '5200') {
            return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.Pengiriman_CPO_PK_PerGrup_LHP_PASER_Eplant_DASHBOARD @Tahun = ? , @Site = ?", [$tahun, $select_kebun]);
        }
    }

    // get MS IS PerGrup return to view
    public function getPengeluaranMSISPerGrup (Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Get Pengeluaran MS IS') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_KeluarMSISPerGrup = $this->getPengeluaranMSISPerGrupFromDB($request);
        return view('dashboard.lhpED.PengeluaranMSISPerGrup')->with([
            'kebun' => $kebun,
            'lhp_KeluarMSISPerGrup' => $lhp_KeluarMSISPerGrup,
            ]);
    }

    public function getPencapaianProduksiMainfromDB (Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'A1');
        $select_TBS = (isset($_GET['selectTBS']) ? $_GET['selectTBS'] : 'A');
        $select_jenis = (isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '1');

        if($select_jenis == '1') {
            if($select_TBS == 'A') {
                if($select_kebun == 'A1') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_TELDA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A2') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_KALSA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2300', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A3') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_KALDA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2400', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A4') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_KOKAR_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2500', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A5') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_RICKO_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '3200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A6') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MUARA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '4200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A7') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_PASER_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A8') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_LANGGAI_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
            }
            else if ($select_TBS == 'B') {
                if($select_kebun == 'B1') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3TELDA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B2') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3KALSA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2300', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B3') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3KALDA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2400', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B4') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3KOKAR_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2500', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B5') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3RICKO_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '3200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B6') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3PASER_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
            }
            else if ($select_TBS == 'C') {
                if($select_kebun == 'C1') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAKOKAR_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '2500', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C2') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRARICKO_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '3200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C3') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAMUARA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '4200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C4') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAPASER_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C5') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRALANGGAI_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '6200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C6') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAKALDA_PerBulan_Dashboard @startdate = ?, @enddate = ?, @siteid = '6200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
            }
        }
        else if ($select_jenis == '2') {
            if($select_TBS == 'A') {
                if($select_kebun == 'A1') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_TELDA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A2') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_KALSA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2300', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A3') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_KALDA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2400', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A4') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_KOKAR_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2500', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A5') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_RICKO_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '3200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A6') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MUARA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '4200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A7') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_PASER_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'A8') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_LANGGAI_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
            }
            else if ($select_TBS == 'B') {
                if($select_kebun == 'B1') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3TELDA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B2') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3KALSA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2300', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B3') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3KALDA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2400', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B4') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3KOKAR_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2500', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B5') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3RICKO_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '3200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'B6') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_P3PASER_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
            }
            else if ($select_TBS == 'C') {
                if($select_kebun == 'C1') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAKOKAR_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '2500', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C2') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRARICKO_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '3200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C3') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAMUARA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '4200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C4') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAPASER_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '5200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C5') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRALANGGAI_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '6200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
                else if($select_kebun == 'C6') {
                    return DB::select( "SET NOCOUNT ON ; EXEC PUBDB.Produksi.ProduksiTBSKebunInti_RKAP_REAL_MITRAKALDA_PerPeriode_Dashboard @startdate = ?, @enddate = ?, @siteid = '6200', @thnrkap = '' ", [$dari_tanggal, $sampai_tanggal]);
                }
            }
        }
       
    }

    public function getPencapaianProduksiMain (Request $request) {
        if( Auth::user()->canAccessByHakAkses('LHP', 'LHP Pencapaian Produksi Main') == false) abort('403-dashboard');

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        $lhp_PencapaianProduksi = $this->getPencapaianProduksiMainfromDB($request);

        return view('dashboard.ProduksiTBS.lhpPencapaianProduksiMain')->with([
            'kebun' => $kebun,
            'lhp_PencapaianProduksi' => $lhp_PencapaianProduksi
        ]);
    }

    public function getAnalisaPupukPerPokokFromDB(Request $request) {
        $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : date("Y"));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');
        
        return DB::select("SET NOCOUNT ON ; EXEC [PUBDB].[web].[analisa_pupuk_per_pokok] @tahun = ? , @site_id = ? ", [$tahun, $select_kebun]);

    }

    public function getAnalisaPupukPerPokok(Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'Analisa Pupuk Per Pokok') == false) abort('403-dashboard');

        $Analisa_PupukPerPokok = $this->getAnalisaPupukPerPokokFromDB($request);

        return view('dashboard.ProduksiTBS.AnalisaPupukPerPokok')->with([
            'Analisa_PupukPerPokok' => $Analisa_PupukPerPokok
        ]);
    }

    public function getLHPProduksiTBSfromDB(Request $request) {
        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        
        return DB::select("SET NOCOUNT ON ; EXEC [PUBDB].[Produksi].[ProduksiTBSKebunINTI_PDP_WB_PerBulan_Dashboard] @Startdate = ? , @enddate = ? ", [$dari_tanggal, $sampai_tanggal]);

    }

    public function getLHPProduksiTBS(Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'Produksi TBS') == false) abort('403-dashboard');

        $lhp_ProduksiTBS = $this->getLHPProduksiTBSfromDB($request);

        return view('dashboard.ProduksiTBS.lhpProduksiTBS')->with([
            'lhp_ProduksiTBS' => $lhp_ProduksiTBS
        ]);
    }

    public function getPenerimaanTBSPerJam(Request $request) {
        if (Auth::user()->canAccessByHakAkses('LHP', 'Penerimaan TBS Per Jam') == false) abort('403-dashboard');

        $per_tanggal = (isset($request['per_tanggal']) ? DateTime::createFromFormat('d/m/Y', $request['per_tanggal'])->format('Y-m-d') : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200');

        $Penerimaan_TBSPerJam = [];

        $Penerimaan_TBSPerJam = DB::select("SET NOCOUNT ON ; EXEC PUBDB.WB.TblPenerimaanPerJamTimbanganV2 @startdate =?, @enddate = ?,  @site_id = ? ", [$per_tanggal, $per_tanggal, $select_kebun]);

        return view('dashboard.ProduksiTBS.PenerimaanTBSPerJam')->with([
            'Penerimaan_TBSPerJam' => $Penerimaan_TBSPerJam
        ]);
    }

    public function getLHPBrondolan (Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'LHP Brondolan') == false) abort('403-dashboard');

        $dari_tanggal = (isset($request['dari_tanggal'])
                        ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                         : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                         ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
                         : date("Y-m-d", strtotime("0 days")));
        $select_kebun = (isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA');



        $lhp_brondolan = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');
        if($select_kebun == 'SEMUA') {
            $lhp_brondolan = DB::select("SET NOCOUNT ON ; EXEC PUBDB.Produksi.BrondolanDashboard @startDate = ? , @endDate = ? , @site_id = NULL ", [$dari_tanggal, $sampai_tanggal]);
        }
        else {
            $lhp_brondolan = DB::select("SET NOCOUNT ON ; EXEC PUBDB.Produksi.BrondolanDashboard @startDate = ? ,@endDate = ? ,@site_id = ? ", [$dari_tanggal, $sampai_tanggal, $select_kebun]);
        }

        return view('dashboard.ProduksiTBS.lhpBrondolan')->with([
            'kebun' => $kebun,
            'lhp_brondolan' => $lhp_brondolan
            ]);
    }

    public function getLHPBrondolanBulanan(Request $request)
    {
        if (!Auth::user()->canAccessByHakAkses('LHP', 'LHP Brondolan')) {
            abort('403-dashboard');
        }

        $inFmt  = 'd/m/Y';
        $outFmt = 'Y-m-d';

        // Parse query inputs if present; otherwise default to month bounds
        $from = $request->filled('dari_tanggal')
            ? Carbon::createFromFormat($inFmt, $request->input('dari_tanggal'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $to = $request->filled('sampai_tanggal')
            ? Carbon::createFromFormat($inFmt, $request->input('sampai_tanggal'))->endOfDay()
            : Carbon::now()->endOfMonth();

        // Ensure we pass strings in the format your proc expects
        $params = [$from->format($outFmt), $to->format($outFmt)];

        $select_kebun = $request->query('selectkebun', 'SEMUA');

        if ($select_kebun === 'SEMUA') {
            $sql = "SET NOCOUNT ON; EXEC PUBDB.Produksi.BrondolanBulananDashboard @startDate = ?, @endDate = ?, @site_id = NULL";
            $lhp_brondolan = DB::select($sql, $params);
        } else {
            $sql = "SET NOCOUNT ON; EXEC PUBDB.Produksi.BrondolanBulananDashboard @startDate = ?, @endDate = ?, @site_id = ?";
            $lhp_brondolan = DB::select($sql, array_merge($params, [$select_kebun]));
        }

        $kebun = (new ModulPerKebun())->getListKebunForModul('LHP');

        return view('dashboard.ProduksiTBS.lhpBrondolanBulanan')->with([
            'kebun'          => $kebun,
            'lhp_brondolan'  => $lhp_brondolan,
            // Optional: pass back the effective dates for the form
            'dari_tanggal'   => $from->format($inFmt),
            'sampai_tanggal' => $to->format($inFmt),
            'select_kebun'   => $select_kebun,
        ]);
    }

    public function getLhpKadarAirMSIS(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'Kadar Air MS/IS') == false) abort('403-dashboard');

        // Inputs & defaults
        $dari_tanggal = isset($request['dari_tanggal'])
            ? \DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
            : new \DateTime(date('Y-m-d', strtotime('-7 days')));
        $sampai_tanggal = isset($request['sampai_tanggal'])
            ? \DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
            : new \DateTime(date('Y-m-d')); // today

        $select_kebun = isset($request['selectkebun']) ? $request['selectkebun'] : 'SEMUA';
        $jenis = strtoupper($request->get('jenis', 'MS')); // MS (default) or IS

        // Procs expect 'yyyyMMdd'
        $startYmd = $dari_tanggal instanceof \DateTime ? $dari_tanggal->format('Ymd') : date('Ymd', strtotime('-7 days'));
        $endYmd   = $sampai_tanggal instanceof \DateTime ? $sampai_tanggal->format('Ymd') : date('Ymd');

        // Map UI kebun to proc suffix
        $kebunList = [
            'SEMUA' => 'SEMUA',
            'TELDA' => 'TD',
            'KALSA' => 'K1',
            'KALDA' => 'K2',
            'KOKAR' => 'KK',
            'RICKO' => 'RK',
            'PASER' => 'PS',
        ];

        $rows = [];

        if ($jenis === 'MS') {
            // MINYAK SAWIT
            if ($select_kebun === 'SEMUA') {
                // ✅ Use full range for ALL
                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC PUBDB.Produksi.KADAR_AIR_MS_PMKS_ALL_Dashboard @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            } else {
                $code = $kebunList[$select_kebun] ?? 'TD';
                $proc = "PUBDB.Produksi.KADAR_AIR_MS_PMKS_{$code}_Dashboard";
                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC {$proc} @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            }
        } else {
            // INTI SAWIT
            if ($select_kebun === 'SEMUA') {
                // ✅ Use full range for ALL
                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC PUBDB.Produksi.KADAR_AIR_IS_PMKS_ALL_Dashboard @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            } else {
                $code = $kebunList[$select_kebun] ?? 'TD';
                $proc = "PUBDB.Produksi.KADAR_AIR_IS_PMKS_{$code}_Dashboard";
                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC {$proc} @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            }
        }

        $kebunOptions = array_keys($kebunList);

        return view('dashboard.lhpED.lhpKadarAirMSIS')->with([
            'jenis'          => $jenis,
            'kebunOptions'   => $kebunOptions,
            'select_kebun'   => $select_kebun,
            'dari_tanggal'   => $dari_tanggal->format('d/m/Y'),
            'sampai_tanggal' => $sampai_tanggal->format('d/m/Y'),
            'rows'           => $rows,
        ]);
    }

    public function getLhpKadarKotoranMSIS(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'Kadar Kotoran MS/IS') == false) abort('403-dashboard');

        // Inputs & defaults
        $dari_tanggal = isset($request['dari_tanggal'])
            ? \DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
            : new \DateTime(date('Y-m-d', strtotime('-7 days')));
        $sampai_tanggal = isset($request['sampai_tanggal'])
            ? \DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal'])
            : new \DateTime(date('Y-m-d')); // today

        $select_kebun = $request->get('selectkebun', 'SEMUA');
        $jenis = strtoupper($request->get('jenis', 'MS')); // MS (default) or IS

        // Proc date format 'yyyyMMdd'
        $startYmd = $dari_tanggal instanceof \DateTime ? $dari_tanggal->format('Ymd') : date('Ymd', strtotime('-7 days'));
        $endYmd   = $sampai_tanggal instanceof \DateTime ? $sampai_tanggal->format('Ymd') : date('Ymd');

        // UI -> Proc suffix map
        $kebunMap = [
            'SEMUA' => 'SEMUA',
            'TELDA' => 'TD',
            'KALSA' => 'K1',
            'KALDA' => 'K2',
            'KOKAR' => 'KK',
            'RICKO' => 'RK',
            'PASER' => 'PS',
        ];
        $kebunOptions = array_keys($kebunMap);

        $rows = [];

        if ($jenis === 'MS') {
            // MINYAK SAWIT
            if ($select_kebun === 'SEMUA') {
                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC PUBDB.Produksi.KADAR_KOTORAN_MS_PMKS_ALL_Dashboard @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            } else {
                $code = $kebunMap[$select_kebun] ?? 'TD';
                $proc = "PUBDB.Produksi.KADAR_KOTORAN_MS_PMKS_{$code}_Dashboard";
                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC {$proc} @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            }
        } else {
            // INTI SAWIT
            if ($select_kebun === 'SEMUA') {
                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC PUBDB.Produksi.KADAR_KOTORAN_IS_PMKS_ALL_Dashboard @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            } else {
                $code = $kebunMap[$select_kebun] ?? 'TD';

                // If your DB actually uses KADAR_AIR_* for these IS per-kebun dashboards, swap the next line:
                $proc = "PUBDB.Produksi.KADAR_KOTORAN_IS_PMKS_{$code}_Dashboard";
                // $proc = "PUBDB.Produksi.KADAR_AIR_IS_PMKS_{$code}_Dashboard"; // <— use this if that's really the name

                $rows = DB::select(
                    "SET NOCOUNT ON; EXEC {$proc} @startDate = ?, @endDate = ?",
                    [$startYmd, $endYmd]
                );
            }
        }

        return view('dashboard.lhpED.lhpKadarKotoranMSIS')->with([
            'jenis'          => $jenis,
            'kebunOptions'   => $kebunOptions,
            'select_kebun'   => $select_kebun,
            'dari_tanggal'   => $dari_tanggal->format('d/m/Y'),
            'sampai_tanggal' => $sampai_tanggal->format('d/m/Y'),
            'rows'           => $rows,
        ]);
    }

    public function getRkapVsRealTbsHarian(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'RKAP VS REAL TBS HARIAN') == false) abort('403-dashboard');

        $tahun  = (int) ($request->get('tahun') ?? date('Y'));
        $bulan  = (int) ($request->get('bulan') ?? date('n'));
        $siteId = $request->get('site_id', '2200');
        $jenis  = strtoupper($request->get('jenis', 'P3')); // P3 | INTI | MITRA

        // Map JENIS + SITE_ID to stored procedure
        $procedureMap = [
            // PIHAK 3 - all PMKS use the same SP with different @site_id
            'P3' => [
                '2200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_P3_HARIAN_DASHBOARD_GPT',
                '2300' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_P3_HARIAN_DASHBOARD_GPT',
                '2400' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_P3_HARIAN_DASHBOARD_GPT',
                '2500' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_P3_HARIAN_DASHBOARD_GPT',
                '3200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_P3_HARIAN_DASHBOARD_GPT',
                '5200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_P3_HARIAN_DASHBOARD_GPT',
            ],

            // KEBUN INTI
            'INTI' => [
                '2200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_TELDA_HARIAN_DASHBOARD_GPT',
                '2300' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_KALSA_HARIAN_DASHBOARD_GPT',
                '2400' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_KALDA_HARIAN_DASHBOARD_GPT',
                '2500' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_KOKAR_HARIAN_DASHBOARD_GPT',
                '3200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_RICKO_HARIAN_DASHBOARD_GPT',
                '5200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_PASER_HARIAN_DASHBOARD_GPT',
                // Note: MUARA/LANGGAI INTI can be added here if you later separate by unit.
            ],

            // MITRA
            'MITRA' => [
                '2400' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_KALDA_MITRA_HARIAN_DASHBOARD_GPT',
                '3200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_RICKO_MITRA_HARIAN_DASHBOARD_GPT',
                '5200' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_PASER_MITRA_HARIAN_DASHBOARD_GPT',
                // You can extend:
                // '5200-MUARA'   => 'PUBDB.TBS.RKAP_VS_REAL_TBS_MUARA_MITRA_HARIAN_DASHBOARD_GPT',
                // '5200-LANGGAI' => 'PUBDB.TBS.RKAP_VS_REAL_TBS_LANGGAI_MITRA_HARIAN_DASHBOARD_GPT',
            ],
        ];

        $proc = $procedureMap[$jenis][$siteId] ?? null;

        // Fallback: default to PIHAK 3 dashboard if mapping missing
        if (!$proc) {
            $proc = 'PUBDB.TBS.RKAP_VS_REAL_TBS_P3_HARIAN_DASHBOARD_GPT';
        }

        $rows = DB::select(
            "SET NOCOUNT ON;
             EXEC {$proc} @tahun = ?, @bulan = ?, @site_id = ?",
            [$tahun, $bulan, $siteId]
        );

        return view('dashboard.ProduksiTBS.rkap-vs-real-tbs-harian', [
            'rows'   => $rows,
            'tahun'  => $tahun,
            'bulan'  => $bulan,
            'siteId' => $siteId,
            'jenis'  => $jenis,
        ]);
    }

    public function getProporsiProduksiP3Harian(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('LHP', 'Proporsi Produksi P3 Harian') == false) abort('403-dashboard');

        $kebun = [
            (object)[
                'site_id' => 2200,
                'nama'    => 'TELDA'
            ],
            (object)[
                'site_id' => 2300,
                'nama'    => 'KALSA'
            ],
            (object)[
                'site_id' => 2400,
                'nama'    => 'KALDA'
            ],
            (object)[
                'site_id' => 2500,
                'nama'    => 'KOKAR'
            ],
            (object)[
                'site_id' => 3200,
                'nama'    => 'RICKO'
            ],
            (object)[
                'site_id' => 5200,
                'nama'    => 'PASER'
            ]
        ];

        $dari_tanggal = $request->get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days'));
        $sampai_tanggal = $request->get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days'));
        $site_id = $request->get('selectkebun') ?: 2200;
        $harga = $request->get('harga') ?: 5800;

        $startDate = date('Ymd', strtotime(str_replace('/', '-', $dari_tanggal)));
        $endDate = date('Ymd', strtotime(str_replace('/', '-', $sampai_tanggal)));

        $data = DB::select("
            SET NOCOUNT ON; EXEC PUBDB.Produksi.HitungProduksiProporsiPerPemasokTBS_Harian_P3_Dashboard_GPT
                @startDate = ?,
                @endDate = ?,
                @site_id = ?,
                @harga = ?
        ", [
            $startDate,
            $endDate,
            $site_id,
            $harga
        ]);

        return view('dashboard.lhpED.ProporsiProduksiP3Harian', compact(
            'kebun',
            'data',
            'dari_tanggal',
            'sampai_tanggal',
            'site_id',
            'harga'
        ));
    }

}
