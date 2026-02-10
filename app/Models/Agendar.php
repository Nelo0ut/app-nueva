<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendar extends Model
{
    protected $table = "agendar";

    protected $fillable = [
        'name', 'description', 'feinicio', 'fefin', 'user_id', 'flestado',
    ];

    public function getYearIniAttribute(){
        $dateyhora = explode(" ", $this->feinicio);
        $date = explode("-", $dateyhora[0]);
        //Year
        return $date[0];

    }

    public function getMonthIniAttribute(){
        $dateyhora = explode(" ", $this->feinicio);
        $date = explode("-", $dateyhora[0]);
        //Month
        return (int)$date[1] - 1;
    }

    public function getDayIniAttribute(){
        $dateyhora = explode(" ", $this->feinicio);
        $date = explode("-", $dateyhora[0]);
        //Day
        return (int)$date[2];
    }

    public function getHourIniAttribute()
    {
        $dateyhora = explode(" ", $this->feinicio);
        $hora = explode(":", $dateyhora[1]);
        //Month
        return (int) $hora[0];
    }

    public function getMinIniAttribute()
    {
        $dateyhora = explode(" ", $this->feinicio);
        $hora = explode(":", $dateyhora[1]);
        //Day
        return (int) $hora[1];
    }

    public function getYearFinAttribute()
    {
        $dateyhora = explode(" ", $this->fefin);
        $date = explode("-", $dateyhora[0]);
        //Year
        return $date[0];
    }

    public function getMonthFinAttribute()
    {
        $dateyhora = explode(" ", $this->fefin);
        $date = explode("-", $dateyhora[0]);
        //Month
        return (int) $date[1] - 1;
    }

    public function getDayFinAttribute()
    {
        $dateyhora = explode(" ", $this->fefin);
        $date = explode("-", $dateyhora[0]);
        //day
        return (int) $date[2];
    }

    public function getHourFinAttribute()
    {
        $dateyhora = explode(" ", $this->fefin);
        $hora = explode(":", $dateyhora[1]);
        //hours
        return (int) $hora[0];
    }

    public function getMinFinAttribute()
    {
        $dateyhora = explode(" ", $this->fefin);
        $hora = explode(":", $dateyhora[1]);
        //hours
        return (int) $hora[1];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidads()
    {
        return $this->belongsToMany(Entidad::class, 'agendar_entidad')->withTimesTamps();
    }
    public  function  xd(){
        return $this->belongsToMany();
    }
}
