<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gastos extends Model
{
    protected $table = 'gastos';
    protected $fillable = ['concepto','type','dollar','bs','total','date'];
    protected $guarded = ['id'];
}