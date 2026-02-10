<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    //es: Desde aquí 
    protected $fillable = [
        'code','name', 'alias', 'logo', 'flestado',
    ];
    
    public function users(){
        return $this->hasMany(User::class);
    }

    public function documentos()
    {
        return $this->belongsToMany(Documento::class, 'documento_entidad')->withTimesTamps();
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_entidad')->withTimesTamps();
    }

    public function oficinas(){
        return $this->hasMany(Oficina::class);
    }

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }
}
