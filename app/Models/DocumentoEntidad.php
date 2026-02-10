<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoEntidad extends Model
{
    protected $fillable = [
       'documento_id', 'entidad_id',
    ];
}
