<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class soporte extends Model
{
    protected $table = 'soporte';
    protected $fillable = ['nota','valor','fecha','type'];
    protected $guarded = ['id'];

    public $timestamps = false;
}