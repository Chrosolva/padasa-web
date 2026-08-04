<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\HakAkses;
use App\Models\HakAksesUser;

class User extends Authenticatable
{
    use SoftDeletes;
    public $incrementing = false;
    protected $primaryKey = 'username';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'email', 'nama', 'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];



    public function canAccessById($id_hak_akses) {
        if ($this->admin) {
            return true;
        }
        else {
            $hak_akses = HakAkses::where('username', $this->username)
                ->where('id_hak_akses', $id_hak_akses)
                ->get();
            if(count($hak_akses) > 0) {
                return count($hak_akses);
            }
            else {
                return false;
            }
        }
    }

    public function canAccessByHakAkses($nama_modul, $hak_akses) {
        if ($this->admin) {
            return true;
        }
        else {
            $hak_akses = HakAkses::where('nama_modul', $nama_modul)
                ->where('hak_akses', $hak_akses)->first();
            if ($hak_akses) {
                $new_hakakses = (count(HakAksesUser::where('username', $this->username)
                ->where('id_hak_akses', $hak_akses->id)
                ->get()) > 0);
                return $new_hakakses;
            }
            else {
                return false;
            }
            // if (count($hak_akses) > 0) {
            //     $new_hakakses = (count(HakAksesUser::where('username', $this->username)
            //     ->where('id_hak_akses', $hak_akses[0]->id)
            //     ->get()) > 0);
            //     return $new_hakakses;
            // }
            // else {
            //     return false;
            // }
        }
    }
}
