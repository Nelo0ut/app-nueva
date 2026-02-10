<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipooficina extends Model
{
    protected $fillable = [
       'name', 'flestado',
    ];

    public function oficinas(){
        return $this->hasMany(Oficina::class);
    }
}
