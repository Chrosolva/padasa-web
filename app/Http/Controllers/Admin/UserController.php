<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\User;
use App\Models\HakAkses;
use App\Models\HakAksesUser;
use Hash;

class UserController extends Controller
{
    public function create_rules()
    {
        return [
            'nama' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'username' => 'required|alpha|max:50|unique:users,username',
            'password' => 'required|min:6|max:20',
            'admin' => 'required'
        ];
    }

    public function edit_rules($username)
    {
        return [
            'nama' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $username . ',username',
            'admin' => 'required'
        ];
    }

        public function password_rules()
        {
            return [
                'password' => 'required|min:6|max:20',
            ];
        }

    public function getIndex()
    {
        return view('admin.user.user')->with([
            'user' => User::withTrashed()->get(),
            ]);
    }

    public function getCreate()
    {
        return view('admin.user.addUser')->with([
            'hak_akses' => HakAkses::orderBy('nama_modul', 'asc')
                ->orderBy('hak_akses', 'asc')
                ->get()
            ]);
    }

    public function postCreate(Request $request)
    {
        $user = array(
            'nama' => $request['nama'],
            'email' => $request['email'],
            'username' => strtolower($request['username']),
            'password' => $request['password'],
            'admin' => (Input::has('admin')) ? true : false
        );

        $hak_akses = $request['hak_akses'];
        $hak_akses_user = [];
        $total_hak_akses = count($hak_akses);
        for ($i = 0; $i < $total_hak_akses; $i++) {
            array_push($hak_akses_user, array(
                'username' => $user['username'],
                'id_hak_akses' => $hak_akses[$i]
                ));
        }

        $validator = Validator::make($user, $this->create_rules(), []);
        if ($validator->passes()) {
            $user['password'] = Hash::make($user['password']);
            $new_user = User::create($user);
            HakAksesUser::insert($hak_akses_user);
            return redirect('/admin/user')
                ->with('message', 'User "' . $new_user->username . '" has been successfully created.');
        }
        else {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(Input::except('password', 'konfirmasi'));
        }
    }

    public function getEdit($username)
    {
        $user = User::withTrashed()->find($username);
        if ($user != null) {
            return view('admin.user.editUser')->with([
                'user' => $user,
                'hak_akses' => HakAkses::orderBy('nama_modul', 'asc')
                    ->orderBy('hak_akses', 'asc')
                    ->get(),
                'hak_akses_user' => HakAksesUser::where('username', $username)
                    ->get()
                ]);
        }
        else {
            abort(404);
        }
    }

    public function putEdit(Request $request, $username)
    {
        $user = User::withTrashed()->find($username);
        if ($user != null) {
            $user_data = array(
                'nama' => $request['nama'],
                'email' => $request['email'],
                'admin' => (Input::has('admin')) ? true : false
            );

            $hak_akses = $request['hak_akses'];
            $hak_akses_user = [];
            $total_hak_akses = count($hak_akses);
            for ($i = 0; $i < $total_hak_akses; $i++) {
                array_push($hak_akses_user, array(
                    'username' => $user['username'],
                    'id_hak_akses' => $hak_akses[$i]
                    ));
            }

            $validator = Validator::make($user_data, $this->edit_rules($user->username), []);
            if ($validator->passes()) {
                $user->nama = $user_data['nama'];
                $user->email = $user_data['email'];
                $user->admin = $user_data['admin'];
                $user->save();
                HakAksesUser::where('username', $username)->delete();
                HakAksesUser::insert($hak_akses_user);
                return redirect('/admin/user')
                    ->with('message', 'User "' . $user->username . '" has been successfully updated.');
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

    public function putChangePassword(Request $request, $username)
    {
        $user = User::withTrashed()->find($username);
        if ($user != null) {
            $user_data = array(
                'password' => $request['password'],
            );
            $validator = Validator::make($user_data, $this->password_rules(), []);
            if ($validator->passes()) {
                $user['password'] = Hash::make($user_data['password']);
                $user->save();
                return redirect('/admin/user')
                    ->with('message', 'User "' . $user->username . '" has been successfully updated.');
            }
            else {
                return Redirect::back()
                    ->withErrors($validator);
            }
        }
        else {
            abort(404);
        }
    }

    public function setTidakAktif($username) {
        $user = User::find($username);
        if ($user != null) {
            $user->delete();
            return redirect('/admin/user')
                ->with('message', 'User "' . $user->username . '" has been successfully set as Tidak Aktif.');
        }
        else {
            return redirect('/admin/user')
                ->with('error', 'User not found.');
        }
    }

    public function setAktif($username) {
        $user = User::onlyTrashed()->find($username);
        if ($user != null) {
            $user->restore();
            return redirect('/admin/user')
                ->with('message', 'User "' . $user->username . '" has been successfully set as Aktif.');
        }
        else {
            return redirect('/admin/user')
                ->with('error', 'User not found.');
        }
    }
}
