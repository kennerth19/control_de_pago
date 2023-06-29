<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = ['full_name','tlf','dir','last_name','last_cut_act','dni','day','ip','servidor','send_confirm','server_active','bi'];
    protected $guarded = ['id'];
}