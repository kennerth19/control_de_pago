<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imprimir extends Model
{
    protected $table = 'imprimir';
    protected $fillable = ['nombre','direccion','plan','pendiente','fecha_de_corte','total'];
}
