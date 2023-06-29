<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class backup extends Model
{
    protected $table = 'backup';
    protected $fillable = ['file_name'];
    protected $guarded = ['id'];
}
