<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planes extends Model
{
    protected $table = 'planes';
    protected $fillable = ['nombre_de_plan','plan','dedicado','valor','descripcion','type'];
    protected $guarded = ['id'];
}
