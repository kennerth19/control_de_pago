<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sort extends Model
{
    protected $table = 'sort';
    protected $fillable = ['sort','status','reg','api','type','last_event'];
    protected $guarded = ['id'];

}
