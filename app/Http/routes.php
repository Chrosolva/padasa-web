<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\Route;

Route::group(['middlewareGroups' => ['web']], function() {

	// Company Profile
	Route::get('/', function() {
		return redirect('/home');
	});
	Route::get('/home', 'CompanyProfile\CompanyProfileController@getHome');
	Route::get('/logout', 'CompanyProfile\UserController@getLogout');
	Route::get('/ChangePassword/{username}/edit', 'CompanyProfile\UserController@getEdit');
	Route::put('/ChangePassword/{username}/change-password', 'CompanyProfile\UserController@putChangePassword');
	
	Route::get('/ajax/api/tooltipMSTD', 'Dashboard\FetchController@GetTooltip');
	

	Route::group(['middleware' => ['guest']], function() {
		Route::get('/login', 'CompanyProfile\UserController@getLogin');
		Route::post('/login', 'CompanyProfile\UserController@postLogin');
		// Route::post('/forget-password', 'CompanyProfile\UserController@postForgetPassword');
	});

	Route::group(['middleware' => ['auth']], function() {
		// Route::get('/profile', 'General\UserController@getProfile');
		// Route::put('/profile', 'General\UserController@putProfile');

		// Dashboard
		Route::group(['prefix' => 'dashboard'], function() {
			Route::get('/', function () {
				return redirect('/dashboard/home'); 
			});
			Route::get('/home', function () {
			    return view('dashboard.home');
			});

			// Personalia
			Route::group(['prefix' => 'personalia'], function() {
				Route::get('/', 'Dashboard\PersonaliaController@getIndex');
				Route::get('/dinas', 'Dashboard\PersonaliaController@getDinas');
				Route::get('/daftar_manager', 'Dashboard\PersonaliaController@getManagerKebun');
				Route::get('/daftarkaryawan', 'Dashboard\PersonaliaController@getDaftarKaryawan');
			});

			// Timbangan
			Route::group(['prefix' => 'timbangan'], function() {
				Route::get('/pengolahan-tbs', 'Dashboard\TimbanganController@getPengolahanTBS');
				Route::get('/produksi-gabungan', 'Dashboard\TimbanganController@getProduksiGabungan');
				Route::get('/rendemen-gabungan', 'Dashboard\TimbanganController@getRendemenGabungan');
				Route::get('/hasil-timbang', 'Dashboard\TimbanganController@getHasilTimbangan');
			});

			//LHP Executive Director
			Route::group(['prefix' => 'lhpexecutive'], function() {
				Route::get('/lhpEDMain', 'Dashboard\LHPEksekutiveController@getLhpEDMain');
				Route::get('/lhpEDMainInti', 'Dashboard\LHPEksekutiveController@getLhpEDMainInti');
				Route::get('/lhpEDDetail', 'Dashboard\LHPEksekutiveController@getLhpED');
				Route::get('/lhpEDDetailInti', 'Dashboard\LHPEksekutiveController@getLhpEDInti');
				Route::get('/ReportALB', 'Dashboard\LHPEksekutiveController@getLhpReportALB');
				Route::get('/ReportALBInti', 'Dashboard\LHPEksekutiveController@getLhpReportALBInti');
				Route::get('/ReportFFAallPMKS', 'Dashboard\LHPEksekutiveController@getLhpReportFFAallPMKS');
				Route::get('/ReportFFAallPMKSInti', 'Dashboard\LHPEksekutiveController@getLhpReportFFAallPMKSInti');
				Route::get('/ReportFFAallPMKSIntiExport', 'Dashboard\LHPEksekutiveController@getLhpReportFFAallPMKSIntiExport');
				Route::get('/lhpRealisasiVsTarget', 'Dashboard\LHPEksekutiveController@getLhpRealVsTarget');
				Route::get('/lhpRealisasiVsTargetExport', 'Dashboard\LHPEksekutiveController@getLhpRealVsTargetExport');
				Route::get('/lhpTBSOlah', 'Dashboard\LHPEksekutiveController@getLhpTBSOlah');
				Route::get('/lhpTBSOlahExport', 'Dashboard\LHPEksekutiveController@getLhpTBSOlahExport');
				Route::get('/lhpRestanPanen', 'Dashboard\LHPEksekutiveController@getLhpRestanPanen');
				Route::get('/lhpRestanPanenBulanan', 'Dashboard\LHPEksekutiveController@getLhpRestanPanenBulanan');
				Route::get('/lhpRestanPanenExport', 'Dashboard\LHPEksekutiveController@getLhpRestanPanenExport');
				Route::get('/lhpRestanPanenBlmAngkut', 'Dashboard\LHPEksekutiveController@getlhpRestanPanenBlmAngkut');
				Route::get('/lhpCurahHujan', 'Dashboard\LHPEksekutiveController@getlhpWeatherStation');
				Route::get('/lhpByProduct', 'Dashboard\LHPEksekutiveController@getProdukSampingan');
				Route::get('/lhpByProductBulanan', 'Dashboard\LHPEksekutiveController@getProdukSampinganBulanan');
				Route::get('/lhpProduksiTBS', 'Dashboard\LHPEksekutiveController@getProduksiTBS');
			});

			// Penjualan
			Route::group(['prefix' => 'penjualan'], function() {
				Route::get('/kontrak-penjualan', 'Dashboard\PenjualanController@getKontrakPenjualan');
				Route::get('/harga-kontrak-vs-harga-tender', 'Dashboard\PenjualanController@getHargaKontrakVSHargaTender');
			});

			// Pembelian
			Route::group(['prefix' => 'pembelian'], function() {
				Route::get('/harga-ideal', 'Dashboard\PembelianController@getHargaIdeal');
				Route::get('/hargaBeliTBS', 'Dashboard\PembelianController@getHargaBeliTBS');
			});

			// Bantuan
			Route::group(['prefix' => 'Bantuan'], function() {
				Route::get('/kamus-istilah', 'Dashboard\BantuanController@getKamusIstilah');
			});
		});

		// Admin Panel
		Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
			Route::get('/', function () {
				return redirect('/admin/home'); 
			});
			Route::get('/home', 'Admin\HomeController@getHome');

			// User
			Route::get('/user', 'Admin\UserController@getIndex');
			Route::get('/user/create', 'Admin\UserController@getCreate');
			Route::post('/user/create', 'Admin\UserController@postCreate');
			Route::delete('/user/{username}/non-aktif', 'Admin\UserController@setTidakAktif');
			Route::put('/user/{username}/aktif', 'Admin\UserController@setAktif');
			Route::get('/user/{username}/edit', 'Admin\UserController@getEdit');
			Route::put('/user/{username}/edit', 'Admin\UserController@putEdit');
			Route::put('/user/{username}/change-password', 'Admin\UserController@putChangePassword');

			// Hak Akses
			Route::get('/hak-akses', 'Admin\HakAksesController@getIndex');
			Route::get('/hak-akses/create', 'Admin\HakAksesController@getCreate');
			Route::post('/hak-akses/create', 'Admin\HakAksesController@postCreate');
			Route::get('/hak-akses/{username}/edit', 'Admin\HakAksesController@getEdit');
			Route::put('/hak-akses/{username}/edit', 'Admin\HakAksesController@putEdit');
			Route::delete('/hak-akses/{id}/delete', 'Admin\HakAksesController@delete');

			// Kebun
			Route::get('/kebun', 'Admin\KebunController@getIndex');
			Route::get('/kebun/create', 'Admin\KebunController@getCreate');
			Route::post('/kebun/create', 'Admin\KebunController@postCreate');
			Route::get('/kebun/{kode_kebun}/edit', 'Admin\KebunController@getEdit');
			Route::put('/kebun/{kode_kebun}/edit', 'Admin\KebunController@putEdit');
			Route::delete('/kebun/{kode_kebun}/delete', 'Admin\KebunController@delete');

			// Modul
			Route::get('/modul', 'Admin\ModulController@getIndex');
			Route::get('/modul/create', 'Admin\ModulController@getCreate');
			Route::post('/modul/create', 'Admin\ModulController@postCreate');
			Route::get('/modul/{nama_modul}/edit', 'Admin\ModulController@getEdit');
			Route::put('/modul/{nama_modul}/edit', 'Admin\ModulController@putEdit');
			Route::delete('/modul/{nama_modul}/delete', 'Admin\ModulController@delete');

			// Modul Per Kebun
			Route::get('/modul-per-kebun', 'Admin\ModulPerKebunController@getIndex');
			Route::get('/modul-per-kebun/edit', 'Admin\ModulPerKebunController@getEdit');
			Route::put('/modul-per-kebun/edit', 'Admin\ModulPerKebunController@putEdit');
		});
	});
});