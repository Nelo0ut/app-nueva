<?php
    function formatDatetime($datetime){
        $fechacompleta = explode(" ", $datetime);
        if ($fechacompleta[2] == "PM") {
            $hora = explode(":", $fechacompleta[1]);
            if ((int) $hora[0] < 12) {
                $suma = (int) $hora[0] + 12;
                $fechafinal = $fechacompleta[0] . " " . $suma . ":" . $hora[1] . ":00";
            } else {
                $fechafinal = $fechacompleta[0] . " " . $fechacompleta[1] . ":00";
            }
        } else {
            $hora = explode(":", $fechacompleta[1]);
            if ((int) $hora[0] == 12) {
                $fechafinal = $fechacompleta[0] . " 00:" . $hora[1] . ":00";
            } else {
                $fechafinal = $fechacompleta[0] . " " . $fechacompleta[1] . ":00";
            }
        }
        return $fechafinal;
    }

    function setformatDatetime($datetimeini)
    {
        $fechacompleta = explode(" ", $datetimeini);
        if ($fechacompleta[2] == "PM") {
            $hora = explode(":", $fechacompleta[1]);
            if ((int) $hora[0] < 12) {
                $suma = (int) $hora[0] + 12;
                $fechafinal = $fechacompleta[0] . " " . $suma . ":" . $hora[1] . ":00";
            } else {
                $fechafinal = $fechacompleta[0] . " " . $fechacompleta[1] . ":00";
            }
        } else {
            $hora = explode(":", $fechacompleta[1]);
            if ((int) $hora[0] == 12) {
                $fechafinal = $fechacompleta[0] . " 00:" . $hora[1] . ":00";
            } else {
                $fechafinal = $fechacompleta[0] . " " . $fechacompleta[1] . ":00";
            }
        }

        return $fechafinal;
    }

    function formatfechatime($fechatime){
        $fechaindi = explode(" - ", $fechatime);
        //Fecha inicial
        $fechaini = explode(" ", $fechaindi[0]);
        $dateini = formatfecha($fechaini[0]);
        $horaini = formathora($fechaini[1], $fechaini[2]);
        $feinifinal = $dateini." ".$horaini;

        //Fecha final
        $fechafin = explode(" ", $fechaindi[1]);
        $datefin = formatfecha($fechafin[0]);
        $horafin = formathora($fechafin[1], $fechafin[2]);
        $fefinfinal = $datefin . " " . $horafin;
        return array($feinifinal, $fefinfinal);
    }

    function formatfecha($fecha){
        $date = explode("/", $fecha);
        return $date[2]."-".$date[1]."-".$date[0];
    }

    function formathora($hora, $tipo)
    {
        if ($tipo == "AM") {
            $h = explode(":", $hora);
            if((int)($h[0]) ==12){
            $hora = "00" . ":$h[1]";
            }
            $hora = $hora . ":00";
        } else {
            $h = explode(":", $hora);
            if((int)($h[0])==12){
                $h[0] = 0;
            }
            $hora = (int) ($h[0] + 12) . ":" . $h[1] . ":00";
        }
        return $hora;
    }

    function setfechadouble($feini, $fefin){
    $fechacompletaini = explode(" ", $feini);
        $fechaini = setformatfecha($fechacompletaini[0]);
        $horaini = setformathora($fechacompletaini[1]);
        $feinifinal = $fechaini." ".$horaini;

        $fechacompletafin = explode(" ", $fefin);
        $fechafin = setformatfecha($fechacompletafin[0]);
        $horafin = setformathora($fechacompletafin[1]);
        $fefinfinal = $fechafin." ".$horafin;

        return $feinifinal. " - ". $fefinfinal;
    }

    function setformatfecha($fecha){
        $date = explode("-", $fecha);
        return $date[0]."/".$date[1]."/".$date[2];
    }

    function setformathora($hora)
    {
        $h = explode(":", $hora);
        if((int)($h[0]) > 11){
            $hora =  ((int)$h[0] - 12) . ":" . $h[1]. " PM";
        }else{
            $hora = $h[0] . ":" . $h[1] . " AM";
        }
        return $hora;
    }

    use Illuminate\Support\Str;

    if (! function_exists('create_slug')) {
    function create_slug(string $string): string
        {
            return Str::slug($string);
        }
    }