<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use App\Http\Requests\ApiTarifaOrdinariaRequest;
use App\Models\Tipotransferencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TarifaController extends Controller
{
    public function gettarifa(Request $request){

        $v = Validator::make($request->all(), [
            'tipotransford' => 'required|integer|min:1|max:2',
            'entidadorigenord' => 'required',
            'entidadfinord' => 'required|different:entidadorigenord',
            'plazaord' => 'required|integer|min:1|max:3',
            'monedaord' => 'required|integer|min:1|max:2',
            'montoord' => 'required|numeric',
            'medioord' => 'required|numeric',
        ],[
            'tipotransford.required' => 'El tipo de tarifa es obligatorio',
            'tipotransford.integer' => 'El tipo de tarifa debe ser un número',
            'tipotransford.min' => 'El tipo de tarifa debe ser Inmedia o por horarios',
            'tipotransford.max' => 'El tipo de tarifa debe ser Inmedia o por horarios',
            'entidadorigenord.required' => 'La entidad financiera es obligatorio',
            'entidadfinord.required' => 'La entidad financiera es obligatorio',
            'entidadfinord.different' => 'La entidad financiera debe ser distinta',
            'plazaord.required' => 'La plaza es obligatorio',
            'plazaord.integer' => 'La plaza debe ser un número',
            'plazaord.min' => 'La plaza debe ser MP, OP o PE',
            'plazaord.max' => 'La plaza debe ser MP, OP o PE',
            'monedaord.required' => 'La moneda es obligatorio',
            'monedaord.integer' => 'La moneda debe ser un número',
            'monedaord.min' => 'La moneda debe ser Soles o Dolares',
            'monedaord.max' => 'La moneda debe ser Soles o Dolares',
            'montoord.required' => 'El monto es obligatorio',
            'montoord.numeric' => 'El monto debe ser un número',
            'medioord.required' => 'El medio origen es obligatorio',
            'medioord.numeric' => 'El medio origen debe ser un número',
        ]);

        if ($v->fails()) {
            $msg = "";
            foreach ($v->errors()->all() as $mensaje) {
                $msg .= $mensaje."<br>";
            }
            return response()->json(['status' => 4, 'msg' => $msg], 200);
        }

        $tipotarifa = $request->tipotransford;
        $entidad_des = $request->entidadorigenord;
        $entidad_par = $request->entidadfinord;
        $plaza = $request->plazaord;
        $moneda = $request->monedaord;
        $monto = $request->montoord;
        $medio = $request->medioord;
        $comisionfinalint = 0;
        $comisionfinal = 0;
        $montofinal = 0;

        //Agregamos lógica de Bilateral
        $bilateral_exist = DB::select('SELECT bilateral.sol_porcentaje, bilateral.sol_montofijomin, bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax FROM bilateral WHERE tipotarifa = ? AND tipotransferencia_id = 1 AND entidad1_id = ? AND entidad2_id = ?  AND tipoplaza = ? LIMIT 1', [$tipotarifa, $entidad_des, $entidad_par, $plaza]);

        $tarifas_des = DB::select('SELECT tarifas.id, tarifas.entidad_id, entidads.alias, tipotransferencias.id AS tipotransferencia, tipotransferencias.name AS notipotransferencia, tarifas.tipotarifa, tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, mediotarifas.id AS mediotarifa, mediotarifas.medio AS nomediotarifa,tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM entidads  INNER JOIN tarifas ON tarifas.entidad_id = entidads.id  INNER JOIN tipotransferencias ON tipotransferencias.id = tarifas.tipotransferencia_id INNER JOIN mediotarifas ON mediotarifas.id = tarifas.mediotarifa_id WHERE tipotarifa = ? AND tipotransferencia_id = 1 AND entidad_id = ? AND mediotarifa_id = ? AND tipoplaza = ? LIMIT 1', [$tipotarifa, $entidad_des, $medio, $plaza]);

        $tarifas_para = DB::select('SELECT tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, CASE WHEN (SELECT tinespecial FROM tarifas WHERE (tinespecial = 1 OR tinespecial = 3) AND tipotarifa = ? AND tipotransferencia_id = 1 AND entidad_id = ? LIMIT 1) IS NULL THEN 0 ELSE 1 END AS hasTin FROM tarifas WHERE tipotarifa = ? AND tipotransferencia_id = 1 AND entidad_id = ? LIMIT 1', [$tipotarifa, $entidad_par, $tipotarifa, $entidad_par]);
        //return response()->json(['status' => $tarifas_para],200);
        if(empty($tarifas_des)){ return response()->json(['status' => 2,'msg' =>'No se encontraron tarifas para la entidad de origen'], 200);}
        if (empty($tarifas_para)) {
            return response()->json(['status' => 3, 'msg' => 'No se encontraron tarifas para la entidad de destino'], 200);
        }
        if($tarifas_des[0]->tinespecial == 1 AND ($tarifas_para[0]->hasTin == 1 OR $tarifas_para[0]->tinespecial == 3)){
            if ($moneda == 1) {
                //Soles
                $montoiniint = $tarifas_des[0]->sol_int_montofijomin;
                $montofinint = $tarifas_des[0]->sol_int_montofijomax;
                $porcentajeint = $tarifas_des[0]->sol_int_porcentaje;
                $montofijoint = $tarifas_des[0]->sol_int_montofijo;

                $montoini = $tarifas_des[0]->sol_cci_montofijomin;
                $montofin = $tarifas_des[0]->sol_cci_montofijomax;
                $porcentaje = $tarifas_des[0]->sol_cci_porcentaje;
                $montofijo = $tarifas_des[0]->sol_cci_montofijo;
                $tintope = $tarifas_des[0]->tintope;
                $simbolo = "S/ ";
            }else{
                //dolares
                $montoiniint = $tarifas_des[0]->dol_int_montofijomin;
                $montofinint = $tarifas_des[0]->dol_int_montofijomax;
                $porcentajeint = $tarifas_des[0]->dol_int_porcentaje;
                $montofijoint = $tarifas_des[0]->dol_int_montofijo;

                $montoini = $tarifas_des[0]->dol_cci_montofijomin;
                $montofin = $tarifas_des[0]->dol_cci_montofijomax;
                $porcentaje = $tarifas_des[0]->dol_cci_porcentaje;
                $montofijo = $tarifas_des[0]->dol_cci_montofijo;
                $tintope = $tarifas_des[0]->tintopedol;
                $simbolo = "$ ";
            }


            if($tintope >= $monto){
                $comisionfinalint = 0;
                $comisionfinal = 0;
            }else{
                if ($plaza == 1) {
                    $comisionfinalint = $montofijoint;
                    $comisionfinal = $montofijo;
                } else {
                    if($porcentaje == 0){
                        $comisionfinalint = $montofijoint;
                        $comisionfinal = $montofijo;
                    }else{
                        $montmp = ( $monto * $porcentaje ) / 100;
                        $montmp = number_format($montmp,2);
                        $comisionfinal = $montmp;
                        //Validamos si pasamos el rango
                        if($montoini > $montmp){
                            $comisionfinal = $montoini;
                        }

                        if ($montofin < $montmp) {
                            $comisionfinal = $montofin;
                        }

                        $montmpint = ($monto * $porcentajeint) / 100;
                        $montmpint = number_format($montmpint, 2);
                        $comisionfinalint = $montmpint;
                        //Validamos si pasamos el rango
                        if ($montoiniint > $montmpint) {
                            $comisionfinalint = $montoiniint;
                        }

                        if ($montofinint < $montmpint) {
                            $comisionfinalint = $montofinint;
                        }
                    }

                }
                if (!empty($bilateral_exist)) {
                    $result = $this->bilateral($plaza, $moneda, $bilateral_exist[0]->sol_porcentaje, $bilateral_exist[0]->sol_montofijomin, $bilateral_exist[0]->sol_montofijomax, $bilateral_exist[0]->dol_porcentaje, $bilateral_exist[0]->dol_montofijomin, $bilateral_exist[0]->dol_montofijomax, $monto);
                    $comisionfinal = $result['comision'];
                }
            }
        }else{
            if ($moneda == 1) {
                //Soles
                $montoiniint = $tarifas_des[0]->sol_int_montofijomin;
                $montofinint = $tarifas_des[0]->sol_int_montofijomax;
                $porcentajeint = $tarifas_des[0]->sol_int_porcentaje;
                $montofijoint = $tarifas_des[0]->sol_int_montofijo;

                $montoini = $tarifas_des[0]->sol_cci_montofijomin;
                $montofin = $tarifas_des[0]->sol_cci_montofijomax;
                $porcentaje = $tarifas_des[0]->sol_cci_porcentaje;
                $montofijo = $tarifas_des[0]->sol_cci_montofijo;
                $tintope = $tarifas_des[0]->tintope;
                $simbolo = "S/ ";
            } else {
                //dolares
                $montoiniint = $tarifas_des[0]->dol_int_montofijomin;
                $montofinint = $tarifas_des[0]->dol_int_montofijomax;
                $porcentajeint = $tarifas_des[0]->dol_int_porcentaje;
                $montofijoint = $tarifas_des[0]->dol_int_montofijo;

                $montoini = $tarifas_des[0]->dol_cci_montofijomin;
                $montofin = $tarifas_des[0]->dol_cci_montofijomax;
                $porcentaje = $tarifas_des[0]->dol_cci_porcentaje;
                $montofijo = $tarifas_des[0]->dol_cci_montofijo;
                $tintope = $tarifas_des[0]->tintopedol;
                $simbolo = "$ ";
            }

            if ($plaza == 1) {
                $comisionfinalint = $montofijoint;
                $comisionfinal = $montofijo;
            } else {
                if ($porcentaje == 0) {
                    $comisionfinalint = $montofijoint;
                    $comisionfinal = $montofijo;
                } else {
                    $montmp = ($monto * $porcentaje) / 100;
                    $montmp = number_format($montmp, 2);
                    $comisionfinal = $montmp;
                    //Validamos si pasamos el rango
                    if ($montoini > $montmp) {
                        $comisionfinal = $montoini;
                    }

                    if ($montofin < $montmp) {
                        $comisionfinal = $montofin;
                    }

                    $montmpint = ($monto * $porcentajeint) / 100;
                    $montmpint = number_format($montmpint, 2);
                    $comisionfinalint = $montmpint;
                    //Validamos si pasamos el rango
                    if ($montoiniint > $montmpint) {
                        $comisionfinalint = $montoiniint;
                    }

                    if ($montofinint < $montmpint) {
                        $comisionfinalint = $montofinint;
                    }
                }
            }

            if (!empty($bilateral_exist)) {
                $result = $this->bilateral($plaza, $moneda, $bilateral_exist[0]->sol_porcentaje, $bilateral_exist[0]->sol_montofijomin, $bilateral_exist[0]->sol_montofijomax, $bilateral_exist[0]->dol_porcentaje, $bilateral_exist[0]->dol_montofijomin, $bilateral_exist[0]->dol_montofijomax, $monto);
                $comisionfinal = $result['comision'];
            }
        }

        $montofinal = $monto + $comisionfinal + $comisionfinalint;

        return response()->json([
            'status' => 1,
            'monto' => $simbolo. number_format($monto, 2),
            'comisionint' => $simbolo . number_format($comisionfinalint, 2),
            'comision' => $simbolo . number_format($comisionfinal, 2),
            'montofinal' => $simbolo . number_format( $montofinal, 2)
        ],200);

    }


    public function gettarifacredito(Request $request)
    {

        $v = Validator::make($request->all(), [
            'tipotransftar' => 'required|integer|min:1|max:2',
            'entidadorigentar' => 'required',
            'entidadfintar' => 'required|different:entidadorigentar',
            'monedatar' => 'required|integer|min:1|max:2',
            'montotar' => 'required|numeric',
        ], [
            'tipotransftar.required' => 'El tipo de tarifa es obligatorio',
            'tipotransftar.integer' => 'El tipo de tarifa debe ser un número',
            'tipotransftar.min' => 'El tipo de tarifa debe ser Inmedia o por horarios',
            'tipotransftar.max' => 'El tipo de tarifa debe ser Inmedia o por horarios',
            'entidadorigentar.required' => 'La entidad financiera es obligatorio',
            'entidadfintar.required' => 'La entidad financiera es obligatorio',
            'entidadfintar.different' => 'La entidad financiera debe ser distinta',
            'monedatar.required' => 'La moneda es obligatorio',
            'monedatar.integer' => 'La moneda debe ser un número',
            'monedatar.min' => 'La moneda debe ser Soles o Dolares',
            'monedatar.max' => 'La moneda debe ser Soles o Dolares',
            'montotar.required' => 'El monto es obligatorio',
            'montotar.numeric' => 'El monto debe ser un número',
        ]);

        if ($v->fails()) {
            $msg = "";
            foreach ($v->errors()->all() as $mensaje) {
                $msg .= $mensaje . "<br>";
            }
            return response()->json(['status' => 4, 'msg' => $msg], 200);
        }

        $tipotarifa = $request->tipotransftar;
        $entidad_des = $request->entidadorigentar;
        $entidad_par = $request->entidadfintar;
        $moneda = $request->monedatar;
        $medio = 1;
        $monto = $request->montotar;
        $mediofinal = 1;
        if (!empty($request->mediotar)) {
            $medio = $request->mediotar;
            $mediofinal = $request->mediotar;
        }
        $montofinal = 0;

        //Agregamos lógica de Bilateral
        $bilateral_exist = DB::select('SELECT bilateral.sol_porcentaje, bilateral.sol_montofijomin, bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax FROM bilateral WHERE tipotarifa = ? AND tipotransferencia_id = 1 AND entidad1_id = ? AND entidad2_id = ?  AND tipoplaza = ? LIMIT 1', [$tipotarifa, $entidad_des, $entidad_par, 1]);

        $tarifas_des = DB::select('SELECT tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM entidads  INNER JOIN tarifas ON tarifas.entidad_id = entidads.id WHERE tipotarifa = ? AND tipotransferencia_id = 2 AND entidad_id = ? AND mediotarifa_id = ? AND tipoplaza = 1 LIMIT 1', [$tipotarifa, $entidad_des, $medio]);

        if($medio == 4){$mediofinal = 1;}
        $tarifas_para = DB::select('SELECT tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM entidads  INNER JOIN tarifas ON tarifas.entidad_id = entidads.id WHERE tipotarifa = ? AND tipotransferencia_id = 2 AND entidad_id = ? AND mediotarifa_id = ? AND tipoplaza = 1 LIMIT 1', [$tipotarifa,$entidad_par, $mediofinal]);
        //return response()->json(['status' => $tarifas_para],200);
        if (empty($tarifas_des)) {
            return response()->json(['status' => 2, 'msg' => 'No se encontraron tarifas para la entidad de origen'], 200);
        }
        if (empty($tarifas_para)) {
            return response()->json(['status' => 3, 'msg' => 'No se encontraron tarifas para la entidad de destino'], 200);
        }

        if ($moneda == 1) {
            //Soles
            $montofijoori = $tarifas_des[0]->sol_int_montofijo;
            $montofijodes = $tarifas_des[0]->sol_cci_montofijo;
            $simbolo = "S/ ";
        } else {
            //dolares
            $montofijoori = $tarifas_des[0]->dol_int_montofijo;
            $montofijodes = $tarifas_des[0]->dol_cci_montofijo;
            $simbolo = "$ ";
        }

        if (!empty($bilateral_exist)) {
            $result = $this->bilateral(1, $moneda, $bilateral_exist[0]->sol_porcentaje, $bilateral_exist[0]->sol_montofijomin, $bilateral_exist[0]->sol_montofijomax, $bilateral_exist[0]->dol_porcentaje, $bilateral_exist[0]->dol_montofijomin, $bilateral_exist[0]->dol_montofijomax, $monto);
            $montofijodes = $result['comision'];
        }

        $montofinal = $monto + $montofijoori + $montofijodes;


        return response()->json([
            'status' => 1,
            'monto' => $simbolo . number_format($monto, 2),
            'comisionori' => $simbolo . number_format($montofijoori, 2),
            'comisiondes' => $simbolo . number_format($montofijodes, 2),
            'montofinal' => $simbolo . number_format($montofinal, 2)
        ], 200);
    }

    public function gettarifapagoproveedor(Request $request)
    {

        $v = Validator::make($request->all(), [
            'entidadorigenpp' => 'required',
            'entidadfinpp' => 'required|different:entidadorigenpp',
            'monedapp' => 'required|integer|min:1|max:2',
            'plazapp' => 'required|integer|min:1|max:3',
            'montopp' => 'required|numeric',
        ], [
            'entidadorigenpp.required' => 'La entidad financiera es obligatorio',
            'entidadfinpp.required' => 'La entidad financiera es obligatorio',
            'entidadfinpp.different' => 'La entidad financiera debe ser distinta',
            'plazapp.required' => 'La plaza es obligatorio',
            'plazapp.integer' => 'La plaza debe ser un número',
            'plazapp.min' => 'La plaza debe ser MP, OP o PE',
            'plazapp.max' => 'La plaza debe ser MP, OP o PE',
            'monedapp.required' => 'La moneda es obligatorio',
            'monedapp.integer' => 'La moneda debe ser un número',
            'monedapp.min' => 'La moneda debe ser Soles o Dolares',
            'monedapp.max' => 'La moneda debe ser Soles o Dolares',
            'montopp.required' => 'El monto es obligatorio',
            'montopp.numeric' => 'El monto debe ser un número',
        ]);

        if ($v->fails()) {
            $msg = "";
            foreach ($v->errors()->all() as $mensaje) {
                $msg .= $mensaje . "<br>";
            }
            return response()->json(['status' => 4, 'msg' => $msg], 200);
        }

        $entidad_des = $request->entidadorigenpp;
        $entidad_par = $request->entidadfinpp;
        $moneda = $request->monedapp;
        $plazapp = $request->plazapp;
        $monto = $request->montopp;
        $comisionfinalint = 0;
        $comisionfinal = 0;
        $montofinal = 0;

        //Agregamos lógica de Bilateral
        $bilateral_exist = DB::select('SELECT bilateral.sol_porcentaje, bilateral.sol_montofijomin, bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax FROM bilateral WHERE tipotarifa = ? AND tipotransferencia_id = 1 AND entidad1_id = ? AND entidad2_id = ?  AND tipoplaza = ? LIMIT 1', [1, $entidad_des, $entidad_par, $plazapp]);

        $tarifas_des = DB::select('SELECT tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM tarifas WHERE tipotarifa = 1 AND tipotransferencia_id = 3 AND entidad_id = ? AND mediotarifa_id = 1 AND tipoplaza = ? LIMIT 1', [ $entidad_des, $plazapp]);

        $tarifas_para = DB::select('SELECT tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM tarifas WHERE tipotarifa = 1 AND tipotransferencia_id = 3 AND entidad_id = ? AND mediotarifa_id = 1 AND tipoplaza = ? LIMIT 1', [ $entidad_par, $plazapp]);
        //return response()->json(['status' => $tarifas_para],200);
        if (empty($tarifas_des)) {
            return response()->json(['status' => 2, 'msg' => 'No se encontraron tarifas para la entidad de origen'], 200);
        }
        if (empty($tarifas_para)) {
            return response()->json(['status' => 3, 'msg' => 'No se encontraron tarifas para la entidad de destino'], 200);
        }

        if ($moneda == 1) {
            //Soles
            //$montofijoori = $tarifas_des[0]->sol_int_montofijo;
            $montoiniint = $tarifas_des[0]->sol_int_montofijomin;
            $montofinint = $tarifas_des[0]->sol_int_montofijomax;
            $porcentajeint = $tarifas_des[0]->sol_int_porcentaje;
            $montofijoint = $tarifas_des[0]->sol_int_montofijo;

            $montoini = $tarifas_des[0]->sol_cci_montofijomin;
            $montofin = $tarifas_des[0]->sol_cci_montofijomax;
            $porcentaje = $tarifas_des[0]->sol_cci_porcentaje;
            $montofijo = $tarifas_des[0]->sol_cci_montofijo;
            $simbolo = "S/ ";
        } else {
            //dolares
            //$montofijoori = $tarifas_des[0]->dol_int_montofijo;
            $montoiniint = $tarifas_des[0]->dol_int_montofijomin;
            $montofinint = $tarifas_des[0]->dol_int_montofijomax;
            $porcentajeint = $tarifas_des[0]->dol_int_porcentaje;
            $montofijoint = $tarifas_des[0]->dol_int_montofijo;

            $montoini = $tarifas_des[0]->dol_cci_montofijomin;
            $montofin = $tarifas_des[0]->dol_cci_montofijomax;
            $porcentaje = $tarifas_des[0]->dol_cci_porcentaje;
            $montofijo = $tarifas_des[0]->dol_cci_montofijo;
            $simbolo = "$ ";
        }

        if ($plazapp == 1) {
            $comisionfinal = $montofijo;
            $comisionfinalint = $montofijoint;
        } else {
            if ($porcentaje == 0) {
                $comisionfinal = $montofijo;
                $comisionfinalint = $montofijoint;
            } else {
                $montmp = ($monto * $porcentaje) / 100;
                $montmp = number_format($montmp, 2);
                $comisionfinal = $montmp;
                //Validamos si pasamos el rango
                if ($montoini > $montmp) {
                    $comisionfinal = $montoini;
                }

                if ($montofin < $montmp) {
                    $comisionfinal = $montofin;
                }

                $montmpint = ($monto * $porcentajeint) / 100;
                $montmpint = number_format($montmpint, 2);
                $comisionfinalint = $montmpint;
                //Validamos si pasamos el rango
                if ($montoiniint > $montmpint) {
                    $comisionfinalint = $montoiniint;
                }

                if ($montofinint < $montmpint) {
                    $comisionfinalint = $montofinint;
                }
            }
        }

        if (!empty($bilateral_exist)) {
            $result = $this->bilateral($plazapp, $moneda, $bilateral_exist[0]->sol_porcentaje, $bilateral_exist[0]->sol_montofijomin, $bilateral_exist[0]->sol_montofijomax, $bilateral_exist[0]->dol_porcentaje, $bilateral_exist[0]->dol_montofijomin, $bilateral_exist[0]->dol_montofijomax, $monto);
            $comisionfinal = $result['comision'];
        }

        $montofinal = $monto + $comisionfinal + $comisionfinalint;

        return response()->json([
            'status' => 1,
            'monto' => $simbolo . number_format($monto, 2),
            'comisionint' => $simbolo . number_format($comisionfinalint, 2),
            'comision' => $simbolo . number_format($comisionfinal, 2),
            'montofinal' => $simbolo . number_format($montofinal, 2)
        ], 200);
    }

    public function gettarifapagohaberes(Request $request)
    {

        $v = Validator::make($request->all(), [
            'entidadorigenph' => 'required',
            'entidadfinph' => 'required|different:entidadorigenph',
            'monedaph' => 'required|integer|min:1|max:2',
            'plazaph' => 'required|integer|min:1|max:3',
            'montoph' => 'required|numeric',
        ], [
            'entidadorigenph.required' => 'La entidad financiera es obligatorio',
            'entidadfinph.required' => 'La entidad financiera es obligatorio',
            'entidadfinph.different' => 'La entidad financiera debe ser distinta',
            'plazaph.required' => 'La plaza es obligatorio',
            'plazaph.integer' => 'La plaza debe ser un número',
            'plazaph.min' => 'La plaza debe ser MP, OP o PE',
            'plazaph.max' => 'La plaza debe ser MP, OP o PE',
            'monedaph.required' => 'La moneda es obligatorio',
            'monedaph.integer' => 'La moneda debe ser un número',
            'monedaph.min' => 'La moneda debe ser Soles o Dolares',
            'monedaph.max' => 'La moneda debe ser Soles o Dolares',
            'montoph.required' => 'El monto es obligatorio',
            'montoph.numeric' => 'El monto debe ser un número',
        ]);

        if ($v->fails()) {
            $msg = "";
            foreach ($v->errors()->all() as $mensaje) {
                $msg .= $mensaje . "<br>";
            }
            return response()->json(['status' => 4, 'msg' => $msg], 200);
        }

        $entidad_des = $request->entidadorigenph;
        $entidad_par = $request->entidadfinph;
        $moneda = $request->monedaph;
        $plazaph = $request->plazaph;
        $monto = $request->montoph;
        $comisionfinalint = 0;
        $comisionfinal = 0;
        $montofinal = 0;

        //Agregamos lógica de Bilateral
        $bilateral_exist = DB::select('SELECT bilateral.sol_porcentaje, bilateral.sol_montofijomin, bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax FROM bilateral WHERE tipotarifa = ? AND tipotransferencia_id = 1 AND entidad1_id = ? AND entidad2_id = ?  AND tipoplaza = ? LIMIT 1', [1, $entidad_des, $entidad_par, $plazaph]);

        $tarifas_des = DB::select('SELECT tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM tarifas WHERE tipotarifa = 1 AND tipotransferencia_id = 3 AND entidad_id = ? AND mediotarifa_id = 1 AND tipoplaza = ? LIMIT 1', [$entidad_des, $plazaph]);

        $tarifas_para = DB::select('SELECT tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM tarifas WHERE tipotarifa = 1 AND tipotransferencia_id = 3 AND entidad_id = ? AND mediotarifa_id = 1 AND tipoplaza = ? LIMIT 1', [$entidad_par, $plazaph]);
        //return response()->json(['status' => $tarifas_para],200);
        if (empty($tarifas_des)) {
            return response()->json(['status' => 2, 'msg' => 'No se encontraron tarifas para la entidad de origen'], 200);
        }
        if (empty($tarifas_para)) {
            return response()->json(['status' => 3, 'msg' => 'No se encontraron tarifas para la entidad de destino'], 200);
        }

        if ($moneda == 1) {
            //Soles
            //$montofijoori = $tarifas_des[0]->sol_int_montofijo;
            $montoiniint = $tarifas_des[0]->sol_int_montofijomin;
            $montofinint = $tarifas_des[0]->sol_int_montofijomax;
            $porcentajeint = $tarifas_des[0]->sol_int_porcentaje;
            $montofijoint = $tarifas_des[0]->sol_int_montofijo;

            $montoini = $tarifas_des[0]->sol_cci_montofijomin;
            $montofin = $tarifas_des[0]->sol_cci_montofijomax;
            $porcentaje = $tarifas_des[0]->sol_cci_porcentaje;
            $montofijo = $tarifas_des[0]->sol_cci_montofijo;
            $simbolo = "S/ ";
        } else {
            //dolares
            //$montofijoori = $tarifas_des[0]->dol_int_montofijo;
            $montoiniint = $tarifas_des[0]->dol_int_montofijomin;
            $montofinint = $tarifas_des[0]->dol_int_montofijomax;
            $porcentajeint = $tarifas_des[0]->dol_int_porcentaje;
            $montofijoint = $tarifas_des[0]->dol_int_montofijo;

            $montoini = $tarifas_des[0]->dol_cci_montofijomin;
            $montofin = $tarifas_des[0]->dol_cci_montofijomax;
            $porcentaje = $tarifas_des[0]->dol_cci_porcentaje;
            $montofijo = $tarifas_des[0]->dol_cci_montofijo;
            $simbolo = "$ ";
        }

        if ($plazaph == 1) {
            $comisionfinalint = $montofijoint;
            $comisionfinal = $montofijo;
        } else {
            if ($porcentaje == 0) {
                $comisionfinalint = $montofijoint;
                $comisionfinal = $montofijo;
            } else {
                $montmp = ($monto * $porcentaje) / 100;
                $montmp = number_format($montmp, 2);
                $comisionfinal = $montmp;
                //Validamos si pasamos el rango
                if ($montoini > $montmp) {
                    $comisionfinal = $montoini;
                }

                if ($montofin < $montmp) {
                    $comisionfinal = $montofin;
                }

                $montmpint = ($monto * $porcentajeint) / 100;
                $montmpint = number_format($montmpint, 2);
                $comisionfinalint = $montmpint;
                //Validamos si pasamos el rango
                if ($montoiniint > $montmpint) {
                    $comisionfinalint = $montoiniint;
                }

                if ($montofinint < $montmpint) {
                    $comisionfinalint = $montofinint;
                }
            }
        }

        if (!empty($bilateral_exist)) {
            $result = $this->bilateral($plazaph, $moneda, $bilateral_exist[0]->sol_porcentaje, $bilateral_exist[0]->sol_montofijomin, $bilateral_exist[0]->sol_montofijomax, $bilateral_exist[0]->dol_porcentaje, $bilateral_exist[0]->dol_montofijomin, $bilateral_exist[0]->dol_montofijomax, $monto);
            $comisionfinal = $result['comision'];
        }

        $montofinal = $monto + $comisionfinal + $comisionfinalint;

        return response()->json([
            'status' => 1,
            'monto' => $simbolo . number_format($monto, 2),
            'comisionint' => $simbolo . number_format($comisionfinalint, 2),
            'comision' => $simbolo . number_format($comisionfinal, 2),
            'montofinal' => $simbolo . number_format($montofinal, 2)
        ], 200);
    }

    public function gettarifapagocts(Request $request)
    {

        $v = Validator::make($request->all(), [
            'entidadorigenpcts' => 'required',
            'entidadfinpcts' => 'required|different:entidadorigenpcts',
            'monedapcts' => 'required|integer|min:1|max:2',
            'plazapcts' => 'required|integer|min:1|max:3',
            'montopcts' => 'required|numeric',
        ], [
            'entidadorigenpcts.required' => 'La entidad financiera es obligatorio',
            'entidadfinpcts.required' => 'La entidad financiera es obligatorio',
            'entidadfinpcts.different' => 'La entidad financiera debe ser distinta',
            'plazapcts.required' => 'La plaza es obligatorio',
            'plazapcts.integer' => 'La plaza debe ser un número',
            'plazapcts.min' => 'La plaza debe ser MP, OP o PE',
            'plazapcts.max' => 'La plaza debe ser MP, OP o PE',
            'monedapcts.required' => 'La moneda es obligatorio',
            'monedapcts.integer' => 'La moneda debe ser un número',
            'monedapcts.min' => 'La moneda debe ser Soles o Dolares',
            'monedapcts.max' => 'La moneda debe ser Soles o Dolares',
            'montopcts.required' => 'El monto es obligatorio',
            'montopcts.numeric' => 'El monto debe ser un número',
        ]);

        if ($v->fails()) {
            $msg = "";
            foreach ($v->errors()->all() as $mensaje) {
                $msg .= $mensaje . "<br>";
            }
            return response()->json(['status' => 4, 'msg' => $msg], 200);
        }

        $entidad_des = $request->entidadorigenpcts;
        $entidad_par = $request->entidadfinpcts;
        $moneda = $request->monedapcts;
        $plazapcts = $request->plazapcts;
        $monto = $request->montopcts;
        $comisionfinal = 0;
        $comisionfinalint = 0;
        $montofinal = 0;

        //Agregamos lógica de Bilateral
        $bilateral_exist = DB::select('SELECT bilateral.sol_porcentaje, bilateral.sol_montofijomin, bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax FROM bilateral WHERE tipotarifa = ? AND tipotransferencia_id = 1 AND entidad1_id = ? AND entidad2_id = ?  AND tipoplaza = ? LIMIT 1', [1, $entidad_des, $entidad_par, $plazapcts]);

        $tarifas_des = DB::select('SELECT tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM tarifas WHERE tipotarifa = 1 AND tipotransferencia_id = 3 AND entidad_id = ? AND mediotarifa_id = 1 AND tipoplaza = ? LIMIT 1', [$entidad_des, $plazapcts]);

        $tarifas_para = DB::select('SELECT tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM tarifas WHERE tipotarifa = 1 AND tipotransferencia_id = 3 AND entidad_id = ? AND mediotarifa_id = 1 AND tipoplaza = ? LIMIT 1', [$entidad_par, $plazapcts]);
        //return response()->json(['status' => $tarifas_para],200);
        if (empty($tarifas_des)) {
            return response()->json(['status' => 2, 'msg' => 'No se encontraron tarifas para la entidad de origen'], 200);
        }
        if (empty($tarifas_para)) {
            return response()->json(['status' => 3, 'msg' => 'No se encontraron tarifas para la entidad de destino'], 200);
        }

        if ($moneda == 1) {
            //Soles
            //$montofijoori = $tarifas_des[0]->sol_int_montofijo;
            $montoini = $tarifas_des[0]->sol_cci_montofijomin;
            $montofin = $tarifas_des[0]->sol_cci_montofijomax;
            $porcentaje = $tarifas_des[0]->sol_cci_porcentaje;
            $montofijo = $tarifas_des[0]->sol_cci_montofijo;

            $montoiniint = $tarifas_des[0]->sol_int_montofijomin;
            $montofinint = $tarifas_des[0]->sol_int_montofijomax;
            $porcentajeint = $tarifas_des[0]->sol_int_porcentaje;
            $montofijoint = $tarifas_des[0]->sol_int_montofijo;
            $simbolo = "S/ ";
        } else {
            //dolares
            //$montofijoori = $tarifas_des[0]->dol_int_montofijo;
            $montoiniint = $tarifas_des[0]->dol_int_montofijomin;
            $montofinint = $tarifas_des[0]->dol_int_montofijomax;
            $porcentajeint = $tarifas_des[0]->dol_int_porcentaje;
            $montofijoint = $tarifas_des[0]->dol_int_montofijo;

            $montoini = $tarifas_des[0]->dol_cci_montofijomin;
            $montofin = $tarifas_des[0]->dol_cci_montofijomax;
            $porcentaje = $tarifas_des[0]->dol_cci_porcentaje;
            $montofijo = $tarifas_des[0]->dol_cci_montofijo;
            $simbolo = "$ ";
        }

        if ($plazapcts == 1) {
            $comisionfinal = $montofijo;
            $comisionfinalint = $montofijoint;
        } else {
            if ($porcentaje == 0) {
                $comisionfinal = $montofijo;
                $comisionfinalint = $montofijoint;
            } else {
                $montmp = ($monto * $porcentaje) / 100;
                $montmp = number_format($montmp, 2);
                $comisionfinal = $montmp;
                //Validamos si pasamos el rango
                if ($montoini > $montmp) {
                    $comisionfinal = $montoini;
                }

                if ($montofin < $montmp) {
                    $comisionfinal = $montofin;
                }

                $montmpint = ($monto * $porcentajeint) / 100;
                $montmpint = number_format($montmpint, 2);
                $comisionfinalint = $montmpint;
                //Validamos si pasamos el rango
                if ($montoiniint > $montmpint) {
                    $comisionfinalint = $montoiniint;
                }

                if ($montofinint < $montmpint) {
                    $comisionfinalint = $montofinint;
                }
            }
        }

        if (!empty($bilateral_exist)) {
            $result = $this->bilateral($plazapcts, $moneda, $bilateral_exist[0]->sol_porcentaje, $bilateral_exist[0]->sol_montofijomin, $bilateral_exist[0]->sol_montofijomax, $bilateral_exist[0]->dol_porcentaje, $bilateral_exist[0]->dol_montofijomin, $bilateral_exist[0]->dol_montofijomax, $monto);
            $comisionfinal = $result['comision'];
        }

        $montofinal = $monto + $comisionfinal + $comisionfinalint;

        return response()->json([
            'status' => 1,
            'monto' => $simbolo . number_format($monto, 2),
            'comisionint' => $simbolo . number_format($comisionfinalint, 2),
            'comision' => $simbolo . number_format($comisionfinal, 2),
            'montofinal' => $simbolo . number_format($montofinal, 2)
        ], 200);
    }

    private function validarcampo($input){
        if(empty($input)){
            return false;
        }
        return true;
    }

    public function getdata(){
        $tarifa = Tarifa::get();
        return response()->json($tarifa, 200);
    }

    public function bilateral($plaza,$moneda, $sol_porcentaje, $sol_minimo, $sol_maximo, $dol_porcentaje, $dol_minimo, $dol_maximo, $monto){
        if ($moneda == 1) {
            //Soles
            //$montofijoori = $tarifas_des[0]->sol_int_montofijo;
            $montoini = $sol_minimo;
            $montofin = $sol_maximo;
            $porcentaje = $sol_porcentaje;
            $simbolo = "S/ ";
        } else {
            //dolares
            //$montofijoori = $dol_int_montofijo;
            $montoini = $dol_minimo;
            $montofin = $dol_maximo;
            $porcentaje = $dol_porcentaje;
            $simbolo = "$ ";
        }

        if ($plaza == 1) {
            $comisionfinal = $montoini;
            $comisionfinalint = 0;
        } else {
            if ($porcentaje == 0) {
                $comisionfinal = $montoini;
                $comisionfinalint = 0;
            } else {
                $montmp = ($monto * $porcentaje) / 100;
                $montmp = number_format($montmp, 2);
                $comisionfinal = $montmp;
                //Validamos si pasamos el rango
                if ($montoini > $montmp) {
                    $comisionfinal = $montoini;
                }

                if ($montofin < $montmp) {
                    $comisionfinal = $montofin;
                }

                $comisionfinalint = 0;
            }
        }

        $montofinal = $monto + $comisionfinal + $comisionfinalint;
        return [
            'comision' => $comisionfinal
        ];
    }
}
