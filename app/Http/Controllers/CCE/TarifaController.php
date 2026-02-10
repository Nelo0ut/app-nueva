<?php

namespace App\Http\Controllers\CCE;

use App\Http\Controllers\Controller;
use App\Http\Requests\TarifaRequest;
use App\Http\Requests\FiltrartarifaRequest;
use App\Http\Requests\FiltratbilateralRequest;
use App\Models\Entidad;
use App\Http\Requests\AgregarTarifaBilateralRequest;
use App\Http\Requests\ModificarTarifaRequest;
use App\Http\Requests\ModificarTarifaEntidadRequest;
use App\Http\Requests\ModificarTarifaNormalEntidadRequest;
use App\Http\Requests\ModificaTarifaRequest;
use App\Models\Mediotarifa;
use Illuminate\Http\Request;
use App\Models\Tarifa;
use App\Models\Tipotransferencia;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\DB;

class TarifaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //$tarifas = Tarifa::get()->toArray();
        $tarifas = array();
        //dd($tarifas);
        return view('CCE.entidads.tarifa', [
            'tarifas' => $tarifas,
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado',1)->get(),
            'tipotransferencias' => Tipotransferencia::get(),
        ]);
    }

    public function filtrar(FiltrartarifaRequest $request)
    {
        if($request->tipotransferencia == -1){
            $tipo = 1;
        }else{
            $tipo = $request->tipotransferencia;}
        $tarifas = DB::select('SELECT tarifas.id, tarifas.entidad_id, entidads.alias, tipotransferencias.id AS tipotransferencia, tipotransferencias.name AS notipotransferencia, tarifas.tipotarifa, tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, mediotarifas.id AS mediotarifa, mediotarifas.medio AS nomediotarifa,tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM entidads  INNER JOIN tarifas ON tarifas.entidad_id = entidads.id  INNER JOIN tipotransferencias ON tipotransferencias.id = tarifas.tipotransferencia_id INNER JOIN mediotarifas ON mediotarifas.id = tarifas.mediotarifa_id WHERE tipotarifa = ? AND tipotransferencia_id = ? AND entidad_id = ?', [$request->tipotarifas, $tipo, $request->entidad_id]);
        
        $medios = DB::select('SELECT DISTINCT mediotarifas.id AS mediotarifa, mediotarifas.medio AS nomediotarifa, tinespecial, tintope, tintopedol FROM tarifas INNER JOIN mediotarifas ON mediotarifas.id = tarifas.mediotarifa_id WHERE tipotarifa = ? AND tipotransferencia_id = ? AND entidad_id = ?', [$request->tipotarifas, $tipo, $request->entidad_id]);
        
        //dd($request->all());
        //dd($tarifas);
        return view('CCE.entidads.tarifa', [
            'tarifas' => $tarifas,
            'entidads' => Entidad::where('id', '!=', '1')->get(),
            'tipotransferencias' => Tipotransferencia::get(),
            'medios' => $medios,
            'oldre' => $request->all()
        ]);
    }

    public function editar_tarifa($tarifa, $tipotransferencia, $tipo)
    {
        $tarifas = DB::select('SELECT tarifas.id, tarifas.entidad_id, entidads.alias, tipotransferencias.id AS tipotransferencia, tipotransferencias.name AS notipotransferencia, tarifas.tipotarifa, tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, mediotarifas.id AS mediotarifa, mediotarifas.medio AS nomediotarifa,tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM entidads  INNER JOIN tarifas ON tarifas.entidad_id = entidads.id  INNER JOIN tipotransferencias ON tipotransferencias.id = tarifas.tipotransferencia_id INNER JOIN mediotarifas ON mediotarifas.id = tarifas.mediotarifa_id WHERE tipotarifa = ? AND tipotransferencia_id = ? AND entidad_id = ?', [(int)$tipo, (int)$tipotransferencia, (int)$tarifa]);
        // echo count($tarifas);
        // dd($tarifas);
        if(count($tarifas) == 0){
            return redirect('admin/tarifa')->with('status', "No se encontró la tarifa buscada, volver a intentar.");
        }
        
        return view('CCE.entidads.tarifa_ent_modificar', [
            'tarifas' => $tarifas,
        ]);
    }

    public function modificar_tarifa_entidad(ModificaTarifaRequest $request, $id)
    {
        if ($request) {
                $update_query = "UPDATE tarifas SET sol_int_porcentaje =" . $request->sol_int_porcentaje . ", sol_int_montofijo =" . $request->sol_int_montofijo . " , sol_int_montofijomin =" . $request->sol_int_montofijomin . " , sol_int_montofijomax =" . $request->sol_int_montofijomax . " , sol_cci_porcentaje =" . $request->sol_cci_porcentaje . " , sol_cci_montofijo =" . $request->sol_cci_montofijo . " , sol_cci_montofijomin =" .$request->sol_cci_montofijomin . " , 
                sol_cci_montofijomax =" . $request->sol_cci_montofijomax . " , dol_int_porcentaje =" . $request->dol_int_porcentaje . " , dol_int_montofijo =" . $request->dol_int_montofijo . " , dol_int_montofijomin =" . $request->dol_int_montofijomin . " , dol_int_montofijomax =" . $request->dol_int_montofijomax . " , dol_cci_porcentaje =" . $request->dol_cci_porcentaje . " , dol_cci_montofijo =" . $request->dol_cci_montofijo . " , dol_cci_montofijomin =" . $request->dol_cci_montofijomin . " , dol_cci_montofijomax =" . $request->dol_cci_montofijomax . " WHERE id =" . $id;
            DB::statement($update_query);
            $msg = 'Actualizado con éxito';
        } else {
            $msg = 'Registro no actualizado, ha ocurrido un problema';
        }

        return back()->with('status', $msg);
    }
    public function modificar_tarifa_global(Request $request)
    {   

        if(count($request->entidad_id) > 0){
            for ($i=0; $i < count($request->entidad_id); $i++) { 

                #Recorrer los inputs dentro del TR - 14 input
                for ($a=0; $a < count($request->sol_int_porcentaje); $a++) {
                    #Usamos las 14 variables del TR
                    $update_query = "UPDATE tarifas SET sol_int_porcentaje =" . $request->sol_int_porcentaje[$a] . ", sol_int_montofijo =" . $request->sol_int_montofijo[$a] . " , sol_int_montofijomin =" . $request->sol_int_montofijomin[$a] . " , sol_int_montofijomax =" . $request->sol_int_montofijomax[$a] . " , sol_cci_porcentaje =" . $request->sol_cci_porcentaje[$a] . " , sol_cci_montofijo =" . $request->sol_cci_montofijo[$a] . " , sol_cci_montofijomin =" . $request->sol_cci_montofijomin[$a] . " , sol_cci_montofijomax =" . $request->sol_cci_montofijomax[$a] . " , dol_int_porcentaje =" . $request->dol_int_porcentaje[$a] . " , dol_int_montofijo =" . $request->dol_int_montofijo[$a] . " , dol_int_montofijomin =" . $request->dol_int_montofijomin[$a] . " , dol_int_montofijomax =" . $request->dol_int_montofijomax[$a] . " , dol_cci_porcentaje =" . $request->dol_cci_porcentaje[$a] . " , dol_cci_montofijo =" . $request->dol_cci_montofijo[$a] . " , dol_cci_montofijomin =" . $request->dol_cci_montofijomin[$a] . " , dol_cci_montofijomax =" . $request->dol_cci_montofijomax[$a] . " WHERE entidad_id =" . $request->entidad_id[$i]. " AND tipoplaza = ". $request->tipoplaza[$a] ." AND mediotarifa_id = ". $request->mediotarifa_id[$a] ." AND tipotarifa = ". $request->tipotarifas . " AND tipotransferencia_id = ". $request->tipotransferencia;
                    DB::statement($update_query);
                    $msg = 'Actualizado con éxito';
                }
            }
            return back()->with('status', $msg);
        }
       
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TarifaRequest $request)
    {
        // $tarifaant = Tarifa::where('entidad_id', '=', $request->entidad_id)->where('flestado', 1)->exists();
        // dd($tarifaant);
        if(Tarifa::where('entidad_id', '=', $request->entidad_id)->where('flestado', 1)->exists()){
            Tarifa::where('entidad_id', '=', $request->entidad_id)->where('flestado', 1)->update(['flestado' => 0]);
        }
        // Guardamos
        $tarifa = Tarifa::create($request->all());

        //Retornamos
        return back()->with('status', 'Creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarifa $tarifa)
    {
        return view('CCE.entidads.editartarifa', [
                    'tarifa' => $tarifa,
                    'entidads' => Entidad::where('id', '!=', '1')->get(),
                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TarifaRequest $request, Tarifa $tarifa)
    {
        // if (Tarifa::where('entidad_id', '=', $request->entidad_id)->where('flestado', 1)->exists()) {
        //     Tarifa::where('entidad_id', '=', $request->entidad_id)->where('flestado', 1)->update(['flestado' => 0]);
        //     // Guardamos
        //     $tarifa = Tarifa::create($request->all());
        // }else{
        //     //Actualizamos
        //     $tarifa->update($request->all());
        // }
        //Actualizamos
        $tarifa->update($request->all());
        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function mostrarbilateral()
    {

        $tarifas = array();
        $detalles = array();

        //dd($request->all());
        //dd($detalles);
        return view('CCE.entidads.tarifa_bilateral', [
            'tarifatotal' => $tarifas,
            'detalles' => $detalles,
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado',1)->get(),
            'tipotransferencias' => Tipotransferencia::get(),
            'oldre' => array()
        ]);
    }

    public function mostrarvistamodificartarifas(Tarifa $tarifa)
    {

        // $tarifas = array();
        $detalles = array();

        //dd($request->all());
        //dd($detalles);
        return view('CCE.entidads.tarifa_modificar_global', [
            'entidads' => Entidad::where('id','<>',1)->get(),
            'tarifas' => $tarifa
        ]);
    }

    public function filtrarbilateral(FiltratbilateralRequest $request)
    {   

        $tarifatotal = [];
        $filtro = "";
        if (empty($request->tipotransferencia)) {
            // if (!empty($request->moneda)) {
            //     $filtro .= " AND moneda_id =" . $request->moneda;
            // }
            $a = 0;
            for ($i=1; $i < 7; $i++) {
                $tarifas = DB::select(
                    'SELECT bilateral.id, bilateral.entidad1_id, bilateral.entidad2_id, ent1.alias, ent2.alias AS alias2,
                                tipotransferencias.id AS tipotransferencia, tipotransferencias.NAME AS notipotransferencia,
                                bilateral.tipotarifa, bilateral.tipoplaza, bilateral.sol_porcentaje, bilateral.sol_montofijomin,
                                bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax
                                FROM bilateral
                                INNER JOIN entidads ent1 ON bilateral.entidad1_id = ent1.id
                                INNER JOIN entidads ent2 ON bilateral.entidad2_id = ent2.id
                                INNER JOIN tipotransferencias ON tipotransferencias.id = bilateral.tipotransferencia_id
                                WHERE tipotarifa = ? ' . $filtro . '  AND entidad1_id = ? AND entidad2_id = ? AND tipotransferencia_id = ?',
                    [$request->tipotarifas, $request->entidad1_id, $request->entidad2_id, $i]
                );
                // echo "<pre>";
                // print_r($tarifas);
                // echo "</pre>";

                if(count($tarifas) > 0){
                    $tarifatotal[$a]['info'] = $tarifas;
                    $a++;
                }
            }
            // dd($tarifatotal);
        }else{
            $tarifatotal = [];
            if ($request->tipotransferencia == -1) {
                $tipo = 1;
            } else {
                $tipo = $request->tipotransferencia;
            }
            $filtro .= " AND tipotransferencia_id =" . $tipo;

            // if (!empty($request->moneda)) {
            //     $filtro .= " AND moneda_id =" . $request->moneda;
            // }

            $tarifas = DB::select('SELECT bilateral.id, bilateral.entidad1_id, bilateral.entidad2_id, ent1.alias, ent2.alias AS alias2,
                                tipotransferencias.id AS tipotransferencia, tipotransferencias.NAME AS notipotransferencia,
                                bilateral.tipotarifa, bilateral.tipoplaza, bilateral.sol_porcentaje, bilateral.sol_montofijomin,
                                bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax
                                FROM bilateral
                                INNER JOIN entidads ent1 ON bilateral.entidad1_id = ent1.id
                                INNER JOIN entidads ent2 ON bilateral.entidad2_id = ent2.id
                                INNER JOIN tipotransferencias ON tipotransferencias.id = bilateral.tipotransferencia_id
                                WHERE tipotarifa = ? ' . $filtro . '  AND entidad1_id = ? AND entidad2_id = ?',
                [$request->tipotarifas, $request->entidad1_id, $request->entidad2_id]
            );

            if($tarifas){ 
                $tarifatotal[0]['info'] = $tarifas;
            }
            

        }   

        // dd($tarifatotal);
        // dd(session()->get('identidad'));
        


        return view('CCE.entidads.tarifa_bilateral', [
            'tarifatotal' => $tarifatotal,
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado',1)->get(),
            'tipotransferencias' => Tipotransferencia::get(),
            'oldre' => $request->all()
        ]);
    }

    public function eliminar_tarifa($mp, $op, $pe)
    {

        if ($mp && $op && $pe) {
            $delete_query = "DELETE FROM bilateral WHERE id in (".$mp.",".$op.",".$pe.")";
            DB::statement($delete_query);
            $msg = 'Tarifas eliminadas';
        } else {
            $msg = 'No has enviado alguna de las plazas';
        }

        return back()->with('status', $msg);
    }

    public function ver_tarifa($mp, $op, $pe)
    {
        $tarifas = DB::select('SELECT bilateral.id, bilateral.entidad1_id, bilateral.entidad2_id, ent1.alias, ent2.alias AS alias2,
        tipotransferencias.id AS tipotransferencia, tipotransferencias.NAME AS notipotransferencia,
        bilateral.tipotarifa, bilateral.tipoplaza, bilateral.sol_porcentaje, bilateral.sol_montofijomin,
        bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax
        FROM bilateral
        INNER JOIN entidads ent1 ON bilateral.entidad1_id = ent1.id
        INNER JOIN entidads ent2 ON bilateral.entidad2_id = ent2.id
        INNER JOIN tipotransferencias ON tipotransferencias.id = bilateral.tipotransferencia_id
        WHERE bilateral.id in (?,?,?)', array($mp,$op,$pe));

        return view('CCE.entidads.tarifa_modificar', [
            'tarifas' => $tarifas,
            'tipotransferencias' => Tipotransferencia::get(),
        ]);
    }
    
    public function modificar_tarifa(ModificarTarifaEntidadRequest $request, $id)
    {
        if ($request) {
            $update_query = "UPDATE bilateral SET sol_porcentaje =".$request->sol_porcentaje.", sol_montofijomin =".$request->sol_montofijomin." , sol_montofijomax =".$request->sol_montofijomax." , dol_porcentaje =".$request->dol_porcentaje." , dol_montofijomin =".$request->dol_montofijomin." , dol_montofijomax =".$request->dol_montofijomax." WHERE id =".$id;
            DB::statement($update_query);
            $msg = 'Actualizado con éxito';
        } else {
            $msg = 'Registro no actualizado, ha ocurrido un problema';
        }

        return back()->with('status', $msg);

    }

    public function ver_crear_tarifa()
    {
        return view('CCE.entidads.tarifa_crear', [
            'entidads' => Entidad::where('id', '!=', '1')->get(),
            'tipotransferencias' => Tipotransferencia::get(),
        ]);
    }

    public function post_crear_tarifa(AgregarTarifaBilateralRequest $request)
    {
        //dd($request);
        $tarfias_bilaterales = DB::select('SELECT bilateral.id FROM bilateral WHERE bilateral.entidad1_id = ? AND bilateral.entidad2_id = ? AND bilateral.tipotarifa = ? AND bilateral.tipotransferencia_id = ?', array($request->entidad1_id,$request->entidad2_id,$request->tipotarifas, $request->tipotransferencia));
        //dd($tarfias_bilaterales);
        if (count($tarfias_bilaterales) > 0) {
            return back()->with('status', 'Ya hay tarifas bilaterales registrados para las entidades y los demás datos');
        }else{
            $insertInto1 = "INSERT INTO bilateral (entidad1_id, entidad2_id, tipotarifa, tipotransferencia_id, tipoplaza, 
                            sol_porcentaje, sol_montofijomin, sol_montofijomax, dol_porcentaje, dol_montofijomin, dol_montofijomax, flestado) VALUES 
                            (".$request->entidad1_id.",".$request->entidad2_id.",".$request->tipotarifas.",".$request->tipotransferencia.",1,0,".$request->mp_sol_montofijomin.",0,0,".$request->mp_dol_montofijomin.",0,1);";
            DB::statement($insertInto1);

            $insertInto2 = "INSERT INTO bilateral (entidad1_id, entidad2_id, tipotarifa, tipotransferencia_id, tipoplaza, 
                            sol_porcentaje, sol_montofijomin, sol_montofijomax, dol_porcentaje, dol_montofijomin, dol_montofijomax, flestado) VALUES 
                            (".$request->entidad1_id.",".$request->entidad2_id.",".$request->tipotarifas.",".$request->tipotransferencia.",2,".$request->op_sol_porcentaje.",".$request->op_sol_montofijomin.",".$request->op_sol_montofijomax.",".$request->op_dol_porcentaje.",".$request->op_dol_montofijomin.",".$request->op_dol_montofijomax.",1);";
            DB::statement($insertInto2);

            $insertInto3 = "INSERT INTO bilateral (entidad1_id, entidad2_id, tipotarifa, tipotransferencia_id, tipoplaza, 
                            sol_porcentaje, sol_montofijomin, sol_montofijomax, dol_porcentaje, dol_montofijomin, dol_montofijomax, flestado) VALUES 
                            (".$request->entidad1_id.",".$request->entidad2_id.",".$request->tipotarifas.",".$request->tipotransferencia.",3,".$request->pe_sol_porcentaje.",".$request->pe_sol_montofijomin.",".$request->pe_sol_montofijomax.",".$request->pe_dol_porcentaje.",".$request->pe_dol_montofijomin.",".$request->pe_dol_montofijomax.",1);";
            DB::statement($insertInto3);

            return redirect('admin/bilateral')->with('status', 'Se creo las tarifas bilaterales con éxito');
        }
    }


    /* Aqui es para tarifas de Entidad */

    public function listar()
    {

        $tarifas = array();
        $detalles = array();

        //dd($request->all());
        //dd($detalles);
        return view('Entidad.entidads.tarifa', [
            'tarifas' => $tarifas,
            'detalles' => $detalles,
            'entidads' => Entidad::where('id', '!=', '1')->get(),
            'tipotransferencias' => Tipotransferencia::get(),
            'oldre' => array()
        ]);
    }

    public function buscar(FiltrartarifaRequest $request)
    {
        if ($request->tipotransferencia == -1) {
            $tipo = 1;
        } else {
            $tipo = $request->tipotransferencia;
        }
        //dd(session()->get('identidad'));
        $tarifas = DB::select('SELECT tarifas.id, tarifas.entidad_id, entidads.alias, tipotransferencias.id AS tipotransferencia, tipotransferencias.name AS notipotransferencia, tarifas.tipotarifa, tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, mediotarifas.id AS mediotarifa, mediotarifas.medio AS nomediotarifa,tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM entidads  INNER JOIN tarifas ON tarifas.entidad_id = entidads.id  INNER JOIN tipotransferencias ON tipotransferencias.id = tarifas.tipotransferencia_id INNER JOIN mediotarifas ON mediotarifas.id = tarifas.mediotarifa_id WHERE tipotarifa = ? AND tipotransferencia_id = ? AND entidad_id = ?', [$request->tipotarifas, $tipo, $request->entidad_id]);

        $medios = DB::select('SELECT DISTINCT mediotarifas.id AS mediotarifa, mediotarifas.medio AS nomediotarifa, tinespecial, tintope, tintopedol FROM tarifas INNER JOIN mediotarifas ON mediotarifas.id = tarifas.mediotarifa_id WHERE tipotarifa = ? AND tipotransferencia_id = ? AND entidad_id = ?', [$request->tipotarifas, $tipo, $request->entidad_id]);

        //dd($request->all());
        //dd($tarifas);
        return view('Entidad.entidads.tarifa', [
            'tarifas' => $tarifas,
            'entidads' => Entidad::where('id', '!=', '1')->get(),
            'tipotransferencias' => Tipotransferencia::get(),
            'medios' => $medios,
            'oldre' => $request->all()
        ]);
    }

    public function updateentidad(TarifaRequest $request, Tarifa $tarifa)
    {
        // if (Tarifa::where('entidad_id', '=', $request->entidad_id)->where('flestado', 1)->exists()) {
        //     Tarifa::where('entidad_id', '=', $request->entidad_id)->where('flestado', 1)->update(['flestado' => 0]);
        //     // Guardamos
        //     $tarifa = Tarifa::create($request->all());
        // }else{
        //     //Actualizamos
        //     $tarifa->update($request->all());
        // }
        //Actualizamos
        $tarifa->update($request->all());
        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }

    public function mostrarbilateralentidad()
    {

        $tarifas = array();
        $detalles = array();

        //dd($request->all());
        //dd($detalles);
        return view('Entidad.entidads.tarifa_bilateral', [
            'tarifatotal' => $tarifas,
            'detalles' => $detalles,
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado',1)->get(),
            'tipotransferencias' => Tipotransferencia::get(),
            'oldre' => array()
        ]);
    }

    public function filtrarbilateralentidad(FiltratbilateralRequest $request)
    {
        $tarifatotal = [];
        $filtro = "";
        if (empty($request->tipotransferencia)) {
            // if (!empty($request->moneda)) {
            //     $filtro .= " AND moneda_id =" . $request->moneda;
            // }
            $a = 0;
            for ($i = 1; $i < 7; $i++) {
                $tarifas = DB::select(
                    'SELECT bilateral.id, bilateral.entidad1_id, bilateral.entidad2_id, ent1.alias, ent2.alias AS alias2,
                                tipotransferencias.id AS tipotransferencia, tipotransferencias.NAME AS notipotransferencia,
                                bilateral.tipotarifa, bilateral.tipoplaza, bilateral.sol_porcentaje, bilateral.sol_montofijomin,
                                bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax
                                FROM bilateral
                                INNER JOIN entidads ent1 ON bilateral.entidad1_id = ent1.id
                                INNER JOIN entidads ent2 ON bilateral.entidad2_id = ent2.id
                                INNER JOIN tipotransferencias ON tipotransferencias.id = bilateral.tipotransferencia_id
                                WHERE tipotarifa = ? ' . $filtro . '  AND entidad1_id = ? AND entidad2_id = ? AND tipotransferencia_id = ?',
                    [$request->tipotarifas, session()->get('identidad'), $request->entidad2_id, $i]
                );
                // echo "<pre>";
                // print_r($tarifas);
                // echo "</pre>";

                if (count($tarifas) > 0) {
                    $tarifatotal[$a]['info'] = $tarifas;
                    $a++;
                }
            }
            // dd($tarifatotal);
        } else {
            $tarifatotal = [];
            if ($request->tipotransferencia == -1) {
                $tipo = 1;
            } else {
                $tipo = $request->tipotransferencia;
            }
            $filtro .= " AND tipotransferencia_id =" . $tipo;

            // if (!empty($request->moneda)) {
            //     $filtro .= " AND moneda_id =" . $request->moneda;
            // }

            $tarifas = DB::select(
                'SELECT bilateral.id, bilateral.entidad1_id, bilateral.entidad2_id, ent1.alias, ent2.alias AS alias2,
                                tipotransferencias.id AS tipotransferencia, tipotransferencias.NAME AS notipotransferencia,
                                bilateral.tipotarifa, bilateral.tipoplaza, bilateral.sol_porcentaje, bilateral.sol_montofijomin,
                                bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax
                                FROM bilateral
                                INNER JOIN entidads ent1 ON bilateral.entidad1_id = ent1.id
                                INNER JOIN entidads ent2 ON bilateral.entidad2_id = ent2.id
                                INNER JOIN tipotransferencias ON tipotransferencias.id = bilateral.tipotransferencia_id
                                WHERE tipotarifa = ? ' . $filtro . '  AND entidad1_id = ? AND entidad2_id = ?',
                [$request->tipotarifas, session()->get('identidad'), $request->entidad2_id]
            );

            $tarifatotal[0]['info'] = $tarifas;

            if( sizeof($tarifatotal[0]['info']) == 0){ $tarifatotal = false; }
        }

        // dd($tarifatotal);
        // dd(session()->get('identidad'));



        return view('Entidad.entidads.tarifa_bilateral', [
            'tarifatotal' => $tarifatotal,
            'entidads' => Entidad::where('id', '!=', '1')->get(),
            'tipotransferencias' => Tipotransferencia::get(),
            'oldre' => $request->all()
        ]);
    }

    public function eliminar_tarifaentidad($mp, $op, $pe)
    {

        if ($mp && $op && $pe) {
            $delete_query = "DELETE FROM bilateral WHERE id in (" . $mp . "," . $op . "," . $pe . ")";
            DB::statement($delete_query);
            $msg = 'Tarifas eliminadas';
        } else {
            $msg = 'No has enviado alguna de las plazas';
        }

        return back()->with('status', $msg);
    }

    public function ver_tarifaentidad($mp, $op, $pe)
    {
        $tarifas = DB::select('SELECT bilateral.id, bilateral.entidad1_id, bilateral.entidad2_id, ent1.alias, ent2.alias AS alias2,
        tipotransferencias.id AS tipotransferencia, tipotransferencias.NAME AS notipotransferencia,
        bilateral.tipotarifa, bilateral.tipoplaza, bilateral.sol_porcentaje, bilateral.sol_montofijomin,
        bilateral.sol_montofijomax, bilateral.dol_porcentaje, bilateral.dol_montofijomin, bilateral.dol_montofijomax
        FROM bilateral
        INNER JOIN entidads ent1 ON bilateral.entidad1_id = ent1.id
        INNER JOIN entidads ent2 ON bilateral.entidad2_id = ent2.id
        INNER JOIN tipotransferencias ON tipotransferencias.id = bilateral.tipotransferencia_id
        WHERE bilateral.id in (?,?,?)', array($mp, $op, $pe));

        return view('Entidad.entidads.tarifa_modificar', [
            'tarifas' => $tarifas,
            'tipotransferencias' => Tipotransferencia::get(),
        ]);
    }

    public function editar_entidad_tarifa($tarifa, $tipotransferencia, $tipo)
    {
        $tarifas = DB::select('SELECT tarifas.id, tarifas.entidad_id, entidads.alias, tipotransferencias.id AS tipotransferencia, tipotransferencias.name AS notipotransferencia, tarifas.tipotarifa, tarifas.tinespecial, tarifas.tintope, tarifas.tintopedol, mediotarifas.id AS mediotarifa, mediotarifas.medio AS nomediotarifa,tarifas.tipoplaza,tarifas.sol_int_porcentaje, tarifas.sol_int_montofijo, tarifas.sol_int_montofijomin, tarifas.sol_int_montofijomax,tarifas.sol_cci_porcentaje, tarifas.sol_cci_montofijo, tarifas.sol_cci_montofijomin, tarifas.sol_cci_montofijomax, tarifas.dol_int_porcentaje, tarifas.dol_int_montofijo, tarifas.dol_int_montofijomin, tarifas.dol_int_montofijomax,tarifas.dol_cci_porcentaje, tarifas.dol_cci_montofijo, tarifas.dol_cci_montofijomin, tarifas.dol_cci_montofijomax FROM entidads  INNER JOIN tarifas ON tarifas.entidad_id = entidads.id  INNER JOIN tipotransferencias ON tipotransferencias.id = tarifas.tipotransferencia_id INNER JOIN mediotarifas ON mediotarifas.id = tarifas.mediotarifa_id WHERE tipotarifa = ? AND tipotransferencia_id = ? AND entidad_id = ?', [(int)$tipo, (int)$tipotransferencia, (int)session()->get('identidad')]);
        // echo count($tarifas);
        // dd($tarifas);
        if (count($tarifas) == 0) {
            return redirect('entidad/tarifa')->with('status', "No se encontró la tarifa buscada, volver a intentar.");
        }

        return view('Entidad.entidads.tarifa_ent_modificar', [
            'tarifas' => $tarifas,
        ]);
    }

    public function modificar_entidad_tarifa_entidad(ModificarTarifaNormalEntidadRequest $request, $id)
    {
        if ($request) {
            // $update_query = "UPDATE tarifas SET sol_int_porcentaje =" . $request->sol_int_porcentaje . ", sol_int_montofijo =" . $request->sol_int_montofijo . " , sol_int_montofijomin =" . $request->sol_int_montofijomin . " , sol_int_montofijomax =" . $request->sol_int_montofijomax . " , sol_cci_porcentaje =" . $request->sol_cci_porcentaje . " , sol_cci_montofijo =" . $request->sol_cci_montofijo . " , sol_cci_montofijomin =" . $request->sol_cci_montofijomin . " , 
            //                     sol_cci_montofijomax =" . $request->sol_cci_montofijomax . " , dol_int_porcentaje =" . $request->dol_int_porcentaje . " , dol_int_montofijo =" . $request->dol_int_montofijo . " , dol_int_montofijomin =" . $request->dol_int_montofijomin . " , dol_int_montofijomax =" . $request->dol_int_montofijomax . " , dol_cci_porcentaje =" . $request->dol_cci_porcentaje . " , dol_cci_montofijo =" . $request->dol_cci_montofijo . " , dol_cci_montofijomin =" . $request->dol_cci_montofijomin . " , dol_cci_montofijomax =" . $request->dol_cci_montofijomax . " WHERE id =" . $id;
            $update_query = "UPDATE tarifas SET sol_int_montofijo =" . $request->sol_int_montofijo  . " , dol_int_montofijo =" . $request->dol_int_montofijo . " WHERE id =" . $id;

            
           DB::statement($update_query);
            $msg = 'Actualizado con éxito';
        } else {
            $msg = 'Registro no actualizado, ha ocurrido un problema';
        }

        return back()->with('status', $msg);
    }

    public function modificar_tarifaentidad(ModificarTarifaEntidadRequest $request, $id)
    {

        if ($request) {
            $update_query = "UPDATE bilateral SET sol_porcentaje =" . $request->sol_porcentaje . ", sol_montofijomin =" . $request->sol_montofijomin . " , sol_montofijomax =" . $request->sol_montofijomax . " , dol_porcentaje =" . $request->dol_porcentaje . " , dol_montofijomin =" . $request->dol_montofijomin . " , dol_montofijomax =" . $request->dol_montofijomax . " WHERE id =" . $id;
            DB::statement($update_query);
            $msg = 'Actualizado con éxito';
        } else {
            $msg = 'Registro no actualizado, ha ocurrido un problema';
        }

        return back()->with('status', $msg);
    }

    public function ver_crear_tarifaentidad()
    {
        return view('Entidad.entidads.tarifa_crear', [
            'entidads' => Entidad::where('id', '!=', '1')->get(),
            'tipotransferencias' => Tipotransferencia::get(),
        ]);
    }

    public function post_crear_tarifaentidad(AgregarTarifaBilateralRequest $request)
    {
        $tarfias_bilaterales = DB::select('SELECT bilateral.id FROM bilateral WHERE bilateral.entidad1_id = ? AND bilateral.entidad2_id = ? AND bilateral.tipotarifa = ? AND bilateral.tipotransferencia_id = ?', array(session()->get('identidad'), $request->entidad2_id, $request->tipotarifas, $request->tipotransferencia));
        //dd($tarfias_bilaterales);
        if (count($tarfias_bilaterales) > 0) {
            return back()->with('status', 'Ya hay tarifas bilaterales registrados para las entidades y los demás datos');
        } else {
            $insertInto1 = "INSERT INTO bilateral (entidad1_id, entidad2_id, tipotarifa, tipotransferencia_id, tipoplaza, 
                            sol_porcentaje, sol_montofijomin, sol_montofijomax, dol_porcentaje, dol_montofijomin, dol_montofijomax, flestado) VALUES 
                            (" . session()->get('identidad') . "," . $request->entidad2_id . "," . $request->tipotarifas . "," . $request->tipotransferencia . ",1,0," . $request->mp_sol_montofijomin . ",0,0," . $request->mp_dol_montofijomin . ",0,1);";
            DB::statement($insertInto1);

            $insertInto2 = "INSERT INTO bilateral (entidad1_id, entidad2_id, tipotarifa, tipotransferencia_id, tipoplaza, 
                            sol_porcentaje, sol_montofijomin, sol_montofijomax, dol_porcentaje, dol_montofijomin, dol_montofijomax, flestado) VALUES 
                            (" . session()->get('identidad') . "," . $request->entidad2_id . "," . $request->tipotarifas . "," . $request->tipotransferencia . ",2," . $request->op_sol_porcentaje . "," . $request->op_sol_montofijomin . "," . $request->op_sol_montofijomax . "," . $request->op_dol_porcentaje . "," . $request->op_dol_montofijomin . "," . $request->op_dol_montofijomax . ",1);";
            DB::statement($insertInto2);

            $insertInto3 = "INSERT INTO bilateral (entidad1_id, entidad2_id, tipotarifa, tipotransferencia_id, tipoplaza, 
                            sol_porcentaje, sol_montofijomin, sol_montofijomax, dol_porcentaje, dol_montofijomin, dol_montofijomax, flestado) VALUES 
                            (" . session()->get('identidad') . "," . $request->entidad2_id . "," . $request->tipotarifas . "," . $request->tipotransferencia . ",3," . $request->pe_sol_porcentaje . "," . $request->pe_sol_montofijomin . "," . $request->pe_sol_montofijomax . "," . $request->pe_dol_porcentaje . "," . $request->pe_dol_montofijomin . "," . $request->pe_dol_montofijomax . ",1);";
            DB::statement($insertInto3);

            return redirect('entidad/bilateral')->with('status', 'Se creo las tarifas bilaterales con éxito');
        }
    }

    
}
