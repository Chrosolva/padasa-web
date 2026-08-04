<?php

namespace App\Http\Controllers\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\User;
use Hash;
use Auth;
use Mail;
use DB;

class UserController extends Controller
{
    public function profile_rules()
    {
        return [
            'nama' => 'required|max:100',
        ];
    }

	public function generateRandomString($length = 50) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

	public function getLogin()
	{
		if (User::find('admin') == null) {
			$admin = array(
	            'nama' => 'Admin',
	            'username' => 'admin',
	            'email' => 'admin@padasa.co.id',
	            'password' => Hash::make('superadmin'),
	            'admin' => true
	        );
	        User::create($admin);
		}
		return view('companyProfile.login');
	}

	public function postLogin(Request $request)
	{
		$user = array(
    		'username' => $request['username'],
    		'password' => $request['password']
    	);
        $remember_me = (Input::has('remember_me')) ? true : false;
		if(Auth::attempt($user, $remember_me))
		{
			// return redirect()->intended('/');
			$request->session()->regenerate();

            return redirect('/dashboard/home');
		}
		else {
			return Redirect::back()
				->with('error', 'Invalid username or password.')
				->withInput(Input::except('password'));
		}
	}

	public function getLogout() {
		Auth::logout();
		return redirect('/login');
	}

	public function getProfile(Request $request)
	{
		return view('general.profile')->with([
			'user' => Auth::user()
		]);
	}

    public function putProfile(Request $request)
    {
    	$user_data = array(
            'nama' => $request['nama'],
        );
        $validator = Validator::make($user_data, $this->profile_rules(), []);

        if ($validator->passes()) {
        	$user = Auth::user();
            $user->nama = $user_data['nama'];
            $user->save();
            return redirect('/profile')
                ->with('message', 'Your profile has been successfully updated.');
        }
        else {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function password_rules()
    {
        return [
            'password' => 'required|min:6|max:20',
        ];
    }

    public function getEdit($username)
    {
        $user = User::withTrashed()->find($username);
        if ($user != null) {
            return view('companyProfile.changePassword')->with([
                'user' => $user]);
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
                return redirect('/dashboard/home')
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









	

    public function postForgetPassword(Request $request) {
        $user = User::where('email', $request['email_reset'])->first();

        if ($user != null) {
            $token = $this->generateRandomString();
            while (count(DB::select('SELECT * FROM password_resets WHERE token = ?', [$token])) > 0) {
                $token = $this->generateRandomString();
            }

            DB::delete("DELETE FROM password_resets WHERE email = ?", [$request['email_reset']]);
            DB::insert("INSERT INTO password_resets values (?, ?, CURRENT_TIMESTAMP)", [$request['email_reset'], $token]);

            Mail::send('mail.resetPassword', ['user' => $user, 'token' => $token] , function($message) use ($user) {
                $message->from('no-reply@padasa.co.id');
                $message->to($user['email']);
                $message->subject('Reset Password');
            });
        }
    }
}