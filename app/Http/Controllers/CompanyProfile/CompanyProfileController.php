<?php

namespace App\Http\Controllers\companyProfile;

use App\Http\Controllers\Controller;

class CompanyProfileController extends Controller
{
	public function getHome()
	{
        return view('companyProfile.home');
	}
}