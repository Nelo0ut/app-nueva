<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    protected $fillable =[
        'user_id','titulo', 'fecha', 'flayer'
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidads()
    {
        return $this->belongsToMany(Entidad::class, 'evento_entidad')->withTimesTamps();
    }
    public function getGetDocumentoAttribute()
    {
        if ($this->evento)
            return url("storage/$this->evento");
    }

    public function getYearIniAttribute()
    {
        $dateyhora = explode(" ", $this->fecha);
        $date = explode("-", $dateyhora[0]);
        //Year
        return $date[0];
    }

    public function getMonthIniAttribute()
    {
        $dateyhora = explode(" ", $this->fecha);
        $date = explode("-", $dateyhora[0]);
        //Month
        return (int) $date[1] - 1;
    }

    public function getDayIniAttribute()
    {
        $dateyhora = explode(" ", $this->fecha);
        $date = explode("-", $dateyhora[0]);
        //Day
        return (int) $date[2];
    }

    public function getHourIniAttribute()
    {
        $dateyhora = explode(" ", $this->fecha);
        $hora = explode(":", $dateyhora[1]);
        //Month
        return (int) $hora[0];
    }

    public function getMinIniAttribute()
    {
        $dateyhora = explode(" ", $this->fecha);
        $hora = explode(":", $dateyhora[1]);
        //Day
        return (int) $hora[1];
    }
    // <td><img src="{{ ('/storage/'.$evento->flayer)}}" width ="372"alt="imagen"></td>
    
    

}
