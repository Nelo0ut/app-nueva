<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //es: Desde aquí 
    protected $fillable = [
        'tipo', 'flestado',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
