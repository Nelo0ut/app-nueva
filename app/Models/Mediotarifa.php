<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mediotarifa extends Model
{
    //
    protected $fillable = [
        'medio'
    ];


    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }
}
