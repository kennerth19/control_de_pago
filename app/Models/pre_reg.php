<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pre_reg extends Model
{
    protected $table = 'pre_reg';
    protected $fillable = ['full_name','dir','type','plan','plan_name','id_user','tlf','date_pay','observation','server_a','monto'];
    protected $guarded = ['id'];

    public $timestamps = false;
}

