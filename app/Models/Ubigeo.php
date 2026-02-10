<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $fillable = [
       'coddepa', 'codprov', 'codist', 'name'
    ];

    public function oficinas(){
        return $this->hasMany(Oficina::class);
    }
}
