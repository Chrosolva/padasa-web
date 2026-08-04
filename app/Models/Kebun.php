<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kebun extends Model
{
    protected $table = 'TblKebun';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'kode_kebun';
    protected $guarded = array();
}
