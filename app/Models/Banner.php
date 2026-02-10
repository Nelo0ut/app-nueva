<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    //
    protected $fillable = [
        'url_banner', 'titulo', 'subtitulo', 'posicion', 'user_id', 'flestado',
    ];
}
