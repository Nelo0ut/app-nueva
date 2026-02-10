<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitlogin extends Model
{
    protected $table = "bitlogin";
    //es: Desde aquí 
    protected $fillable = [
        'usuario_id', 'usuario', 'istipo'
    ];
}
