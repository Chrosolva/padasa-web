<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'TblModul';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'nama_modul';
    protected $guarded = array();
}
