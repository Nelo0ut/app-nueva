<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $fillable = [
        'entidad_id', 'tipotransferencia_id', 'porcentaje', 'name', 'flestado'
    ];

    public function entidad()
    {
        return $this->belongsTo(Entidad::class);
    }

    public function tipotransferencia()
    {
        return $this->belongsTo(Tipotransferencia::class);
    }

    public function medio()
    {
        return $this->belongsTo(Mediotarifa::class);
    }
}
