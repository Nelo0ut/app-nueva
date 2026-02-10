<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'name', 'tipodocumento_id', 'enviar_notification', 'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tipodocumento(){
        return $this->belongsTo(TipoDocumento::class);
    }

    public function entidads()
    {
        return $this->belongsToMany(Entidad::class, 'documento_entidad')->withTimesTamps();
    }

    public function detalledocumentos(){
        return $this->hasMany(Detalledocumento::class);
    }
}
