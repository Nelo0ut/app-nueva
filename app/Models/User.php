<?php

namespace App\Models;

// correcion - use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'email_2', 'password', 'usuario', 'role_id', 'entidad_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setSession($role){
        if($role->flestado == 1){
            Session::put(
                [
                    'role_id' => $role->id,
                    'rol_nombre' => $role->tipo,
                    'nombre' => $this->name,
                    'idusuario' => $this->id,
                    'correo' => $this->email,
                    'identidad' => $this->entidad->id,
                    'entidad_nombre' => $this->entidad->name,
                    'entidad_logo' => $this->entidad->logo,
                ]
            );
        }
    }
    public function entidad(){
       return $this->belongsTo(Entidad::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function agendar()
    {
        return $this->belongsTo(Agendar::class);
    }

    public function documentos(){
        return $this->hasMany(Documento::class);
    }
    
    public function getAuthIdentifierName()
    {
        return 'usuario';
    }
}
