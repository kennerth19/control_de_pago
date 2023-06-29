<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte_pagos extends Model
{
    protected $table = 'reporte_pagos';
    protected $fillable = ['cobrado_por','fecha_pago','codigo_pago','nombre','direccion','tlf','cedula','monto_d','monto_pm','monto_bs','monto_z_1','monto_z_2','pm_ref','tasa','id','total_d','active','reg_del'];
    protected $guarded = ['id'];
}