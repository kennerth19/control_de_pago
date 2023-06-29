<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reporte';
    protected $fillable = ['descripcion'];
    protected $guarded = ['id'];
}