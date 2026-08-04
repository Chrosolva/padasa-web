<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ModulPerKebun;
use DB;
use Auth;
use DateTime;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class PersonaliaController extends Controller
{
    public function getDinas(Request $request) {

        if (Auth::user()->canAccessByHakAkses('Personalia', 'Dinas') == false) abort('403-dashboard');

        
        $dari_tanggal = (isset($request['dari_tanggal']) 
                         ? DateTime::createFromFormat('d/m/Y', $request['dari_tanggal'])
                          : date("Y-m-d", strtotime("-7 days")));
        $sampai_tanggal = (isset($request['sampai_tanggal'])
                          ? DateTime::createFromFormat('d/m/Y', $request['sampai_tanggal']) 
                          : date("Y-m-d", strtotime("0 days")));
        $dinas = [];
        $temp = [];
        $kebun = (new ModulPerKebun())->getListKebunForModul('Personalia');
        for ($i = 0; $i < count($kebun); $i++) {
            $temp[$i] = DB::select("select No, KodePT, Lokasi, KodePekerja, NamaPegawai, Jabatan, TglBerangkat, TglSelesaiDinas, Tujuan " .
                                " FROM [PUBDB].Personalia.FuncSPD(?,?) as SPD", [$dari_tanggal, $sampai_tanggal]);
            $dinas = array_merge($dinas, $temp[$i]);
        }

        $dinas = array_unique($dinas, SORT_REGULAR);
        return view('dashboard.personalia.dinas')->with([
            'kebun' => $kebun,
            'dinas' => $dinas
            ]);
        
    }

    public function getManagerKebun(Request $request) {

        if (Auth::user()->canAccessByHakAkses('Personalia', 'Manager Kebun') == false) abort('403-dashboard');

        $daftar_manager = [];
        
        $kebun = (new ModulPerKebun())->getListKebunForModul('Personalia');
        $daftar_manager = DB::select("Select * from WebPadasa.dbo.Info Order By Urutan desc ");
        return view('dashboard.personalia.managerkebun')->with([
            'kebun' => $kebun,
            'daftar_manager' => $daftar_manager
            ]);
        
    } 

    public function getDaftarKaryawan(Request $request) {
        if(Auth::user()->canAccessByHakAkses('Personalia', 'Daftar Karyawan') == false) abort('403-dashboard');

        $daftar_karyawan = [];
        $select_lokasi = (isset($_GET['selectlokasi']) ? $_GET['selectlokasi'] : 'KANDIR JAKARTA PEU');
        if ($select_lokasi == 'ALL') {
            $daftar_karyawan = DB::select('Select * from WebPadasa.dbo.Kontak');
        }
        else {
            $daftar_karyawan = DB::select('Select * from WebPadasa.dbo.Kontak where LOKASI = ?', [$select_lokasi]);
        }

        return view('dashboard.personalia.daftarkaryawan')->with([
            'select_lokasi' => $select_lokasi,
            'daftar_karyawan' => $daftar_karyawan
        ]);
    }
}
