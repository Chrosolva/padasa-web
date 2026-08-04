<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Modul;

class HomeController extends Controller
{
    public function getHome()
    {
        return view('admin.home')->with([
        	'user_count' => User::withTrashed()->count(),
        	'modul_count' => Modul::count(),
        	]);
    }

    public function redirectToDashboard(): RedirectResponse
    {
        return redirect('/dashboard/home');
    }
}