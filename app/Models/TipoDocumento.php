<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    //es: Desde aquí 
    protected $fillable = [
        'tipodoc', 'flestado',
    ];

    public function documentos(){
        return $this->hasMany(Documento::class);
    }
}
