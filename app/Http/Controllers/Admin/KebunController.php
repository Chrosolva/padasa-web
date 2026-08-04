<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\Kebun;
use DB;

class KebunController extends Controller
{
    public function create_rules()
    {
        return [
            'kode_kebun' => 'required|max:10|unique:TblKebun,kode_kebun',
            'nama_singkat' => 'required|max:50',
            'nama_lengkap' => 'required|max:50',
            'kode_PT' => 'required|max:5',
            'nama_PT' => 'required|max:50',
            'nama_DB' => 'required|max:20',
        ];
    }

    public function edit_rules($kode_kebun)
    {
        return [
            'kode_kebun' => 'required|max:5|unique:TblKebun,kode_kebun,' . $kode_kebun . ",kode_kebun",
            'nama_singkat' => 'required|max:20',
            'nama_lengkap' => 'required|max:50',
            'kode_PT' => 'required|max:5',
            'nama_PT' => 'required|max:50',
            'nama_DB' => 'required|max:20',
        ];
    }

    public function getIndex() 
    {
        return view('admin.kebun.kebun')->with([
            'kebun' => Kebun::all()
            ]);
    }

    public function getCreate() 
    {
        return view('admin.kebun.addKebun');
    }

    public function postCreate(Request $request)
    {
        $kebun = array(
            'kode_kebun' => strtoupper($request['kode_kebun']),
            'nama_singkat' => $request['nama_singkat'],
            'nama_lengkap' => $request['nama_lengkap'],
            'kode_PT' => strtoupper($request['kode_PT']),
            'nama_PT' => $request['nama_PT'],
            'nama_DB' => $request['nama_DB'],
        );

        $validator = Validator::make($kebun, $this->create_rules(), []);
        if ($validator->passes()) {
            $new_kebun = Kebun::create($kebun);
            return redirect('/admin/kebun')
                ->with('message', 'Kebun "' . $new_kebun->nama_lengkap . '" has been successfully created.');
        }
        else {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function getEdit($kode_kebun)
    {
        $kebun = Kebun::find($kode_kebun);
        if ($kebun != null) {
            return view('admin.kebun.editKebun')->with([
                'kebun' => $kebun
                ]);
        }
        else {
            abort(404);
        }
    }

    public function putEdit(Request $request, $kode_kebun)
    {
        $kebun = Kebun::find($kode_kebun);
        if ($kebun != null) {
            $kebun_data = array(
                'kode_kebun' => strtoupper($request['kode_kebun']),
                'nama_singkat' => $request['nama_singkat'],
                'nama_lengkap' => $request['nama_lengkap'],
                'kode_PT' => strtoupper($request['kode_PT']),
                'nama_PT' => $request['nama_PT'],
                'nama_DB' => $request['nama_DB'],
            );
            $validator = Validator::make($kebun_data, $this->edit_rules($kode_kebun), []);
            if ($validator->passes()) {
                $kode_kebun_lama = $kebun->kode_kebun;
                $kebun->kode_kebun = $kebun_data['kode_kebun'];
                $kebun->nama_singkat = $kebun_data['nama_singkat'];
                $kebun->nama_lengkap = $kebun_data['nama_lengkap'];
                $kebun->kode_PT = $kebun_data['kode_PT'];
                $kebun->nama_PT = $kebun_data['nama_PT'];
                $kebun->nama_DB = $kebun_data['nama_DB'];
                $kebun->save();
                DB::table('TblModulPerKebun')->where('kode_kebun', '=', $kode_kebun_lama)->update(array('kode_kebun' => $kebun->kode_kebun));
                return redirect('/admin/kebun')
                    ->with('message', 'Kebun "' . $kode_kebun . '" has been successfully updated.');
            }
            else {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        else {
            abort(404);
        }
    }

    public function delete($kode_kebun) {
        $kebun = Kebun::find($kode_kebun);
        if ($kebun != null) {
            $kebun->delete();
            DB::table('TblModulPerKebun')->where('kode_kebun', '=', $kebun->kode_kebun)->delete();
            return redirect('/admin/kebun')
                ->with('message', 'Kebun "' . $kebun->nama_lengkap . '" has been successfully deleted.');
        }
        else {
            return redirect('/admin/kebun')
                ->with('error', 'Kebun not found.');
        }
    }
}