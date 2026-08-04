<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\Modul;
use DB;

class ModulController extends Controller
{
    public function create_rules()
    {
        return [
            'nama_modul' => 'required|max:50|unique:TblModul,nama_modul',
        ];
    }

    public function edit_rules($nama_modul)
    {
        return [
            'nama_modul' => 'required|max:50|unique:TblModul,nama_modul,' . $nama_modul . ',nama_modul',
        ];
    }

    public function getIndex() 
    {
        return view('admin.modul.modul')->with([
            'modul' => Modul::all(),
            ]);
    }

    public function getCreate() 
    {
        return view('admin.modul.addModul');
    }

    public function postCreate(Request $request)
    {
        $modul = array(
            'nama_modul' => $request['nama_modul']
            );

        $validator = Validator::make($modul, $this->create_rules(), []);
        if ($validator->passes()) {
            $modul = Modul::create($modul);
            return redirect('/admin/modul')
                ->with('message', 'Modul "' . $modul->nama_modul . '" has been successfully created.');
        }
        else {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function getEdit($nama_modul)
    {
        $modul = Modul::find($nama_modul);
        if ($modul != null) {
            return view('admin.modul.editModul')->with([
                'modul' => $modul
                ]);
        }
        else {
            abort(404);
        }
    }

    public function putEdit(Request $request, $nama_modul)
    {
        $modul = Modul::find($nama_modul);
        if ($modul != null) {
            $modul_data = array(
                'nama_modul' => $request['nama_modul']
            );
            $validator = Validator::make($modul_data, $this->edit_rules($modul->nama_modul), []);
            if ($validator->passes()) {
                $nama_modul_lama = $modul->nama_modul;
                $modul->nama_modul = $modul_data['nama_modul'];
                $modul->save();
                DB::table('TblModulPerKebun')->where('nama_modul', '=', $nama_modul_lama)->update(array('nama_modul' => $modul->nama_modul));
                return redirect('/admin/modul')
                    ->with('message', 'Modul "' . $modul->nama_modul . '" has been successfully updated.');
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

    public function delete($nama_modul) {
        $modul = Modul::find($nama_modul);
        if ($modul != null) {
            $modul->delete();
            DB::table('TblModulPerKebun')->where('nama_modul', '=', $modul->nama_modul)->delete();
            return redirect('/admin/modul')
                ->with('message', 'Modul "' . $modul->nama_modul . '" has been successfully deleted.');
        }
        else {
            return redirect('/admin/modul')
                ->with('error', 'Modul not found.');
        }
    }
}