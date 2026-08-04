<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\HakAkses;
use App\Models\HakAksesUser;

class HakAksesController extends Controller
{

    public function edit_rules()
    {
        return [
            'nama_modul' => 'required|max:50',
            'hak_akses' => 'required|max:50',
        ];
    }

    public function getIndex() 
    {
        return view('admin.hakAkses.hakAkses')->with([
            'hak_akses' => HakAkses::all(),
            ]);
    }

    public function getCreate() 
    {
        // $hak_akses = HakAkses::fresh();
        // $this->debug_to_console($hak_akses);
        return view('admin.hakAkses.addHakAkses');
    }

    public function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    public function postCreate(Request $request)
    {
        $nama_modul = $request['nama_modul'];
        $hak_akses = $request['hak_akses'];
        $total_hak_akses = min(count($nama_modul), count($hak_akses));

        $new_hak_akses = [];
        for ($i = 0; $i < $total_hak_akses; $i++) {
            array_push($new_hak_akses, array(
                'nama_modul' => $nama_modul[$i],
                'hak_akses' => $hak_akses[$i]
                ));
        }
        HakAkses::insert($new_hak_akses);

        return redirect('/admin/hak-akses')
                ->with('message', count($new_hak_akses) . ' new Hak Akses has been successfully created.');
    }

    public function getEdit($id)
    {
        $hak_akses = HakAkses::find($id);
        if ($hak_akses != null) {
            return view('admin.hakAkses.editHakAkses')->with([
                'hak_akses' => $hak_akses
                ]);
        }
        else {
            abort(404);
        }
    }

    public function putEdit(Request $request, $id)
    {
        $hak_akses = HakAkses::find($id);
        if ($hak_akses != null) {
            $hak_akses_data = array(
                'nama_modul' => $request['nama_modul'],
                'hak_akses' => $request['hak_akses']
            );
            $validator = Validator::make($hak_akses_data, $this->edit_rules(), []);
            if ($validator->passes()) {
                $hak_akses->nama_modul = $hak_akses_data['nama_modul'];
                $hak_akses->hak_akses = $hak_akses_data['hak_akses'];
                $hak_akses->save();
                return redirect('/admin/hak-akses')
                    ->with('message', 'Hak Akses "' . $hak_akses->id . '" has been successfully updated.');
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

    public function delete($id) {
        $hak_akses = HakAkses::find($id);
        if ($hak_akses != null) {
            $hak_akses->delete();
            HakAksesUser::where('id_hak_akses', $id)->delete();
            return redirect('/admin/hak-akses')
                ->with('message', 'Hak Akses "' . $hak_akses->id . '" has been successfully deleted.');
        }
        else {
            return redirect('/admin/hak-akses')
                ->with('error', 'Hak Akses not found.');
        }
    }
}