<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    protected $fillable = [
        'entidad_id', 'tipooficina_id', 'name', 'numero', 'domicilio', 'localidad', 'nameplaza', 'numeroplaza', 'ubigeodistrital', 'ubigeocheques', 'ubigeotransferencia', 'preftelefono', 'telefono1', 'telefono2', 'fax', 'centraltelefonica', 'motivo', 'flestado'
    ];

    public function ubigeodis(){
        return $this->belongsTo(Ubigeo::class, 'ubigeodistrital');
    }

    public function ubigeoche()
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeocheques');
    }

    public function ubigeotran()
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeotransferencia');
    }

    public function tipooficina(){
        return $this->belongsTo(Tipooficina::class);
    }

    public function entidad(){
        return $this->belongsTo(Entidad::class);
    }
    public function scopeEntidad($query, $entidad_id) {
        if ($entidad_id) {
            return $query->where('oficinas.entidad_id',$entidad_id);
        }
    }
    public function scopeOficinanumeroplaza($query, $oficinanumeroplaza) {
        if ($oficinanumeroplaza) {
            return $query->where('oficinas.numeroplaza',$oficinanumeroplaza);
        }
    }
    public function scopeOficinanumero($query, $oficinanumero) {
        if ($oficinanumero) {
            return $query->where('oficinas.numero',$oficinanumero);
        }
    }
}
