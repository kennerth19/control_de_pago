<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evento_diario extends Model
{
    protected $table = 'evento_diario';
    protected $fillable = ['evento','fecha','date_print','total_d','total_bs'];
    protected $guarded = ['id'];
}