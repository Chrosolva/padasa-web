<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\ModulPerKebun;
use App\Models\Kebun;
use App\Models\Modul;

class ModulPerKebunController extends Controller
{
    public function getIndex() 
    {
        return view('admin.modulPerKebun.modulPerKebun')->with([
            'kebun' => Kebun::all(),
            'modul' => Modul::all(),
            'modul_per_kebun' => ModulPerKebun::all(),
            ]);
    }

    public function getEdit()
    {
        return view('admin.modulPerKebun.editModulPerKebun')->with([
            'kebun' => Kebun::all(),
            'modul' => Modul::all(),
            'modul_per_kebun' => ModulPerKebun::all(),
            ]);
    }

    public function putEdit(Request $request)
    {
        $data = [];
        $kebun = Kebun::all();
        for ($i = 0; $i < count($kebun); $i++) {
            if (isset($request['data'][$kebun[$i]->kode_kebun]) == true) {
                $modul = $request['data'][$kebun[$i]->kode_kebun];
                for ($j = 0; $j < count($modul); $j++) {
                    array_push($data, array(
                        'kode_kebun' => $kebun[$i]->kode_kebun,
                        'nama_modul' => $modul[$j]
                        ));
                }
            }
        }


        ModulPerKebun::truncate();
        ModulPerKebun::insert($data);
        return redirect('/admin/modul-per-kebun')
            ->with('message', 'Modul Per Kebun has been successfully updated.');
    }
}