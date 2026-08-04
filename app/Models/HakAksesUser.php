<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HakAksesUser extends Model
{
    protected $table = 'TblHakAksesUser';
    public $timestamps = false;
    public $incrementing = false;
    protected $guarded = array();
}
