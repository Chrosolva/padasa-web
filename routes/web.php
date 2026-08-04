<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers;
use App\Http\Controllers\Auth;
use App\Http\Controllers\CompanyProfile;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::group(['middlewareGroups' => ['web']], function() {

	
// });

// Company Profile
	Route::get('/', function() {
		return redirect('/home');
	});

	Route::get(
		'/home',
		'CompanyProfile\CompanyProfileController@getHome'
	);
	Route::get('/logout', 'CompanyProfile\UserController@getLogout');
	Route::get('/ChangePassword/{username}/edit', 'CompanyProfile\UserController@getEdit');
	Route::put('/ChangePassword/{username}/change-password', 'CompanyProfile\UserController@putChangePassword');

	Route::get('/verify/pb/{id}', 'Verify\VerifyController@checkPb');	
	Route::get('/verify/pk/{id}', 'Verify\VerifyController@checkPk');
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
			Route::get(
				'/home',
				'Dashboard\HomeController@getOverview'
			)->name('dashboard.home');

			// Areal Statement
			Route::group(['prefix' => 'arealstatement'], function() {
				Route::get('/breakdown-luasan-wilayah-pt', 'Dashboard\ArealStatementController@getBreakdownLuasanWilayahPT');
				Route::get('/luasan-wilayah-per-kebun', 'Dashboard\ArealStatementController@getLuasanWilayahPerKebun');
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
				Route::get('/lhpHitunganP3', 'Dashboard\LHPEksekutiveController@getLHPHitunganP3');
				Route::get('/proporsiProduksiP3Harian', 'Dashboard\LHPEksekutiveController@getProporsiProduksiP3Harian');
				Route::get('/lhpRealisasiVsTargetInti', 'Dashboard\LHPEksekutiveController@getLhpRealVsTargetInti');
				Route::get('/lhpRealisasiVsTargetExport', 'Dashboard\LHPEksekutiveController@getLhpRealVsTargetExport');
				Route::get('/lhpTBSOlah', 'Dashboard\LHPEksekutiveController@getLhpTBSOlah');
				Route::get('/lhpTBSOlahExport', 'Dashboard\LHPEksekutiveController@getLhpTBSOlahExport');
				Route::get('/lhpRestanPanen', 'Dashboard\LHPEksekutiveController@getLhpRestanPanen');
				Route::get('/lhpRestanPanenBulanan', 'Dashboard\LHPEksekutiveController@getLhpRestanPanenBulanan');
				Route::get('/lhpRestanPanenExport', 'Dashboard\LHPEksekutiveController@getLhpRestanPanenExport');
				Route::get('/lhpRestanPanenBlmAngkut', 'Dashboard\LHPEksekutiveController@getlhpRestanPanenBlmAngkut');
				Route::get('/lhpRestanPanenBJR', 'Dashboard\LHPEksekutiveController@getLHPRestanPanenBJR');
				Route::get('/lhpCurahHujan', 'Dashboard\LHPEksekutiveController@getlhpWeatherStation');
				Route::get('/lhpCurahHujanV2', 'Dashboard\LHPEksekutiveController@getlhpWeatherStationV2');
				Route::get('/lhpByProduct', 'Dashboard\LHPEksekutiveController@getProdukSampingan');
				Route::get('/lhpByProductBulanan', 'Dashboard\LHPEksekutiveController@getProdukSampinganBulanan');
				Route::get('/lhpProduksiAngkutTBS', 'Dashboard\LHPEksekutiveController@getProduksiAngkutTBS');
				Route::get('/lhpProduksiTBS', 'Dashboard\LHPEksekutiveController@getProduksiTBS');
				Route::get('/lhpWaktuProsesLHP', 'Dashboard\LHPEksekutiveController@getWaktuProsesLHP');
				Route::get('/MutasiCI', 'Dashboard\LHPEksekutiveController@getMutasiCI');
				Route::get('/MSISPerGrup', 'Dashboard\LHPEksekutiveController@getMSISPerGrup');
				Route::get('/PengeluaranMSISPerGrup', 'Dashboard\LHPEksekutiveController@getPengeluaranMSISPerGrup');
				Route::get('/KadarAirMSIS', 'Dashboard\LHPEksekutiveController@getLhpKadarAirMSIS');
				Route::get('/KadarKotoranMSIS', 'Dashboard\LHPEksekutiveController@getLhpKadarKotoranMSIS');
			});

            // Produksi TBS
            Route::group(['prefix' => 'produksi'], function() {
				Route::get('/lhpTBSTersedia', 'Dashboard\LHPEksekutiveController@getTBSTersedia');
				Route::get('/inventoryTBS', 'Dashboard\LHPEksekutiveController@getInventoryTBS');
				Route::get('/lhpBudgetProduksi', 'Dashboard\LHPEksekutiveController@getBudgetProduksi');
				Route::get('/lhpProduksiTBS2Jam', 'Dashboard\LHPEksekutiveController@getProduksiTBSPer2Jam');
				Route::get('/lhpPencapaianProduksiTBS', 'Dashboard\LHPEksekutiveController@getPencapaianProduksi');
				Route::get('/lhpPencapaianProduksiMain', 'Dashboard\LHPEksekutiveController@getPencapaianProduksiMain');
				Route::get('/MutasiTBS', 'Dashboard\LHPEksekutiveController@getMutasiTBS');
				Route::get('/AnalisaPupukPerPokok', 'Dashboard\LHPEksekutiveController@getAnalisaPupukPerPokok');
				Route::get('/lhpProduksiTBS', 'Dashboard\LHPEksekutiveController@getLHPProduksiTBS');
				Route::get('/PenerimaanTBSPerJam', 'Dashboard\LHPEksekutiveController@getPenerimaanTBSPerJam');
				Route::get('/lhpBrondolan', 'Dashboard\LHPEksekutiveController@getLHPBrondolan');
				Route::get('/lhpBrondolanBulanan', 'Dashboard\LHPEksekutiveController@getLHPBrondolanBulanan');
				Route::get(
					'/rkap-vs-real-tbs-harian',
					'Dashboard\LHPEksekutiveController@getRkapVsRealTbsHarian'
				);
			});

			// Penjualan
			Route::group(['prefix' => 'penjualan'], function() {
				Route::get('/kontrak-penjualan', 'Dashboard\PenjualanController@getKontrakPenjualan');
				Route::get('/harga-kontrak-vs-harga-tender', 'Dashboard\PenjualanController@getHargaKontrakVSHargaTender');
				Route::get('/harga-tender', 'Dashboard\PenjualanController@getHargaTender');
				Route::get('/pengiriman-DO', 'Dashboard\PenjualanController@getPengirimanDO');
				Route::get('/outstanding', 'Dashboard\PenjualanController@getOutstanding');
				Route::get('/outstandingpercust', 'Dashboard\PenjualanController@getOutstandingPerCustomer');
				Route::get('/pengiriman', 'Dashboard\PenjualanController@getPengiriman');
				Route::get('/analisisstok', 'Dashboard\PenjualanController@getAnalisisStok');
				Route::get('/kontrak_jualV2', 'Dashboard\PenjualanController@getKontrakPenjualanV2');
				Route::get('/RekapShipment', 'Dashboard\PenjualanController@getRekapShipment');
				Route::get('/RekapShipmentTahunan', 'Dashboard\PenjualanController@getRekapShipmentTahunan');
				Route::get('/harga-tender-final-ltc', 'Dashboard\PenjualanController@getHargaTenderFinalLTC');
				Route::get('/rekomendasi-harga-tender-harian-pdp', 'Dashboard\PenjualanController@getRekomendasiHargaTenderHarianPDP');
				Route::get('/harga-rata-rata-tender', 'Dashboard\PenjualanController@getHargaRataRataTender');
			});

			// Pembelian
			Route::group(['prefix' => 'pembelian'], function() {
				Route::get('/harga-ideal', 'Dashboard\PembelianController@getHargaIdeal');
				Route::get('/harga-idealNew', 'Dashboard\PembelianController@getHargaIdealNew');
				Route::get('/hargaBeliTBS', 'Dashboard\PembelianController@getHargaBeliTBS');
				Route::get('/hargaIdealTBS', 'Dashboard\PembelianController@getHargaIdealTBS');
				Route::get('/rata2HargaBeliTBS', 'Dashboard\PembelianController@getHargaRata2BeliTBS');
				Route::get('/AnalisaPupuk', 'Dashboard\PembelianController@getAnalisaPupuk');
				Route::get('/PembelianTBSP3', 'Dashboard\PembelianController@getPembelianTBSP3');
				Route::get('/RekapitulasiPembelianSolar', 'Dashboard\PembelianController@getRekapitulasiPembelianSolar');
				Route::get('/RekapitulasiPembelianBeras', 'Dashboard\PembelianController@getRekapitulasiPembelianBeras');
				Route::get('/RekapitulasiPembelianSolarPO', 'Dashboard\PembelianController@getRekapitulasiPembelianSolarPO');
				Route::get('/RekapitulasiPembelianBerasPO', 'Dashboard\PembelianController@getRekapitulasiPembelianBerasPO');
				Route::get('/RekapPembelianTBSP3', 'Dashboard\PembelianController@getRekapPembelianTBSP3');
				Route::get('/AnalisaMutasiPupuk', 'Dashboard\PembelianController@getAnalisaMutasiPupuk');
			});

			// Inventory
			Route::group(['prefix' => 'inventory'], function() {
				Route::get('/Dead-Stock', 'Dashboard\PembelianController@getDeadStock');
			});

			// BIOFertilizer
			Route::group(['prefix' => 'biofertilizer'], function() {
				Route::get('/Statusbatch', 'Dashboard\BioFertilizerController@getStatusbatch');
				Route::get('/StatusbatchEplant', 'Dashboard\BioFertilizerController@getStatusbatchEPLANT');
				// NEW: Analisa Mutasi Pupuk Compost Per Bulan
        		Route::get(
            		'/AnalisaMutasiPupukPerBulan',
            		'Dashboard\BioFertilizerController@getAnalisaMutasiPupukCompost_PerBulan'
        		);
			});

			// Bantuan
			Route::group(['prefix' => 'bantuan'], function() {
				Route::get('/kamus', 'Dashboard\BantuanController@getKamusIstilah');
			});

			// HPT
			Route::group(['prefix' => 'hpt'], function () {
				Route::get(
					'/Rekap-HPT',
					'Dashboard\HPTController@getRekapHPT'
				)->name('hpt.rekap');

				Route::get(
					'/Detail-HPT',
					'Dashboard\HPTController@getDetailHPT'
				)->name('hpt.detail');
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
