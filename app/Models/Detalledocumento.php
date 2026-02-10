<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalledocumento extends Model
{
    protected $fillable = [
        'documento_id', 'name', 'documento', 'flestado',
    ];

    public function documento(){
        return $this->belongsTo(Documento::class);
    }

    public function getGetDocumentoAttribute()
    {
        if ($this->documento)
            return asset($this->documento);
    }
}
