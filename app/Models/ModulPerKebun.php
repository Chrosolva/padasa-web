<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// use App\Models\ModulPerKebun;
use App\Models\Kebun;

class ModulPerKebun extends Model
{
    protected $table = 'TblModulPerKebun';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['kode_kebun', 'nama_modul'];
    protected $guarded = array();

    public function getListKebunForModul($nama_modul) {
    	return ModulPerKebun::join('TblKebun', 'TblModulPerKebun.kode_kebun', '=', 'TblKebun.kode_kebun')
    		->select('TblKebun.*')
            ->where('nama_modul', $nama_modul)
            ->orderBy('Nourut')
            ->get();
    }
}
