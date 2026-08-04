<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ModulPerKebun;
use DB;
use Auth;
use DateTime;

class BantuanController extends Controller
{
    public function getKamusIstilah() {
        // if (Auth::user()->canAccessByHakAkses('Bantuan', 'Kamus Istilah') == false) abort ('403-dashboard');

        $kamus_istilah = [];
        $kamus_istilah = DB::select("select * FROM PUBDB.Kamus.TblIstilah");

        return view('dashboard.bantuan.kamus-istilah')->with([
            'kamus_istilah' => $kamus_istilah
        ]);

    }
}
