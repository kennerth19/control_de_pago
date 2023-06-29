<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'pagos';
    protected $fillable = ['monto_bs','monto_pm','monto_dollar','monto_zelle','usuario','reg'];
    protected $guarded = ['codigo_de_pago'];
}
