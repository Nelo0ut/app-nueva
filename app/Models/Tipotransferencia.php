<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipotransferencia extends Model
{
    protected $fillable = [
        'name',
    ];

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }
}
