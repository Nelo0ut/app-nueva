<?php

namespace App\Http\Controllers\CCE;

use App\Models\Agendar;
use App\Models\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ubigeo;
use App\Models\Entidad;
use App\Models\Documento;
use App\Models\Eventos;
use App\Models\Detalledocumento;
use App\Http\Requests\TinRequest;
use App\Models\Tarifa;
use App\Models\Tipooficina;
use App\Models\Oficina;
use App\Models\TipoDocumento;
use App\Models\User;

class AjaxController extends Controller
{
    //Get Ubigeo
    public function getubigeo(Request $request){
        if($request->ajax()){
            $depa = empty($request->depa) ? null : $request->depa;
            $prov = empty($request->prov) ? 0 : $request->prov;
            $dist = empty($request->dist) ? 0 : $request->dist;

            if (!is_null($depa) && empty($prov)) {
                $ubigeo = Ubigeo::where('coddepa', $depa)
                    ->where('codprov', '<>', $prov)
                    ->where('codist', $dist)
                    ->get();
            } elseif (!is_null($depa) && !empty($prov)) {
                $ubigeo = Ubigeo::where('coddepa', $depa)
                    ->where('codprov', $prov)
                    ->where('codist', '<>', $dist)
                    ->get();
            } else {
                $ubigeo = Ubigeo::where('codprov', $prov)
                    ->where('codist', $dist)
                    ->get();
            }
            return response()->json($ubigeo, 200);
        }else {
            abort(404);
        }
        
    }

    //Get Bancos por documentos
    public function getbancos(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $bancos = Documento::find($id);
            return response()->json($bancos->entidads, 200);
        } else {
            abort(404);
        }
    }

    //Get Bancos por documentos
    public function getbancosxagenda(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $bancos = Agendar::find($id);
            return response()->json($bancos->entidads, 200);
        } else {
            abort(404);
        }
    }

    //Get Bancos por eventos
    public function getbancosxevento(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $bancos = Eventos::find($id);
            return response()->json($bancos->entidads, 200);
        } else {
            abort(404);
        }
    }

    //Descargar archivos
    public function getdownload($id){
        $detalledocumento = Detalledocumento::where('id', $id)->get();
        $ext = explode(".", $detalledocumento[0]->documento);
        // if(strtolower($ext[1]) == "pdf"){
        //     $headers = array(
        //         'Content-Type: application/pdf',
        //     );
        // }else
        if (strtolower($ext[1]) == "doc" || strtolower($ext[1]) == "docx") {
            $headers = array(
                'Content-Type: application/msword',
            );
        }elseif (strtolower($ext[1]) == "xls" || strtolower($ext[1]) == "xlsx") {
            $headers = array(
                'Content-Type: application/vnd.ms-excel',
            );
        }else{
            $headers = array(
                'Content-Type: image/png',
            );
        }
        //PDF file is stored under project/public/download/info.pdf
        $file = public_path() ."\\". $detalledocumento[0]->documento;

        return response()->download($file, $detalledocumento[0]->name.".".$ext[1], $headers);
    }

    public function actualizartin(TinRequest $request)
    {
        if ($request->ajax()) {
            if($request->tinespecial == 1 || $request->tinespecial == 3){
                Tarifa::where('entidad_id', '=', $request->ad_entidad_id)->where('tipotarifa', '=', $request->ad_tipotarifa)->update([
                    'tinespecial' => 0,
                    'tintope' => 0,
                    'tintopedol' => 0
                ]);
                if(!empty($request->medios)){
                    $medios = array();
                    for($i=0; $i < count($request->medios); $i++){
                        $medios[$i] = $request->medios[$i]['value'];
                    }
                    Tarifa::where('entidad_id', '=', $request->ad_entidad_id)->where('tipotarifa', '=', $request->ad_tipotarifa)->whereIn('mediotarifa_id', $medios)->update([
                        'tinespecial' => $request->tinespecial,
                        'tintope' => $request->tintope,
                        'tintopedol' => $request->tintopedol
                    ]);
                }
                
            }else{
                Tarifa::where('entidad_id', '=', $request->ad_entidad_id)->where('tipotarifa', '=', $request->ad_tipotarifa)->update([
                    'tinespecial' => 0,
                    'tintope' => 0,
                    'tintopedol' => 0
                ]);
            }
            
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function eliminaragenda(Request $request)
    {
        if ($request->ajax()) {
            if(empty($request->idagenda)){
                return response()->json(['status' => 2], 200);
            }
            Agendar::where('id', '=', $request->idagenda)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function eliminarbanner(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->idbanner)) {
                return response()->json(['status' => 2], 200);
            }
            Banner::where('id', '=', $request->idbanner)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function actualizar_posicion_banner(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->idbanner)) {
                return response()->json(['status' => 2], 200);
            }
            Banner::where('id', '=', $request->idbanner)->update([
                'posicion' => $request->posicion,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function eliminarEvento(Request $request)
    {
        if($request->ajax()){
            if(empty($request->idevento)){
                return response()->json(['status' =>2], 200);
            }
            Eventos::where('id','=',$request->idevento)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else{
            abort(404);
        }
    }

    public function eliminarFlayerEvento(Request $request)
    {
        if($request->ajax()){
            if(empty($request->idevento)){
                return response()->json(['status' =>2], 200);
            }
            Eventos::where('id','=',$request->idevento)->update([
                'flayer' => null,
            ]);
            return response()->json(['status' => 1], 200);
        } else{
            abort(404);
        }
    }

    public function eliminarDetalleDocumento(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idDetalle)){
                return response()->json(['status' => 2], 200);
            }
            Detalledocumento::where('id','=',$request->idDetalle)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function eliminarDocumento(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idDocumento)){
                return response()->json(['status' => 2], 200);
            }
            Documento::where('id','=',$request->idDocumento)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }
    
    public function eliminarTipoOficina(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idTipoOficina)){
                return response()->json(['status' => 2], 200);
            }
            Tipooficina::where('id','=',$request->idTipoOficina)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function eliminarOficina(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idOficina)){
                return response()->json(['status' => 2], 200);
            }
            $oficina = Oficina::find($request->idOficina);
            if($oficina){
                $oficina->delete();
            }
            /*Oficina::where('id','=',$request->idOficina)->update([
                'flestado' => 0,
                'motivo' => 2,
                'febaja' => date('Y-m-d'),
            ]);*/
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function getcorreouser(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->email)) {
                return response()->json(['status' => 2], 200);
            }
            $user = User::where('flestado', '=', 1)
                        ->where('id', '<>', $request->id)
                        ->where('email', '=', $request->email)
                        ->orWhere('email_2', '=', $request->email)
                        ->get();
            return response()->json(['status' => 1, 'existe' => count($user)], 200);
        } else {
            abort(404);
        }
    }

    public function getcorreoalternativouser(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->email_2)) {
                return response()->json(['status' => 2], 200);
            }
            $user = User::where('email', '=', $request->email_2)
                        ->orWhere('email_2','=', $request->email_2)
                        ->where('flestado', '=', 1)
                        ->where('id', '<>', $request->id)->get();
                        
            return response()->json(['status' => 1, 'existe' => count($user)], 200);
        } else {
            abort(404);
        }
    }

    public function getusuariouser(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->usuario)) {
                return response()->json(['status' => 2], 200);
            }
            $user = User::where('usuario', '=', $request->usuario)->where('id', '<>', $request->id)->where('flestado', '=', 1)->get();
            return response()->json(['status' => 1, 'existe' => count($user)], 200);
        } else {
            abort(404);
        }
    }

    public function getcorreouserent(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->email)) {
                return response()->json(['status' => 2], 200);
            }
            $user = User::where('flestado', '=', 1)
                        ->where('id', '<>', $request->id)
                        ->where('email', '=', $request->email)
                        ->orWhere('email_2','=',$request->email)
                        ->get();
            return response()->json(['status' => 1, 'existe' => count($user)], 200);
        } else {
            abort(404);
        }
    }

    public function getcorreoalternativouserent(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->email_2)) {
                return response()->json(['status' => 2], 200);
            }
            $user = User::where('flestado', '=', 1)
                         ->where('id', '<>', $request->id)
                         ->where('email', '=', $request->email_2)
                         ->orWhere('email_2', '=', $request->email_2)
                         ->get();
            return response()->json(['status' => 1, 'existe' => count($user)], 200);
        } else {
            abort(404);
        }
    }

    public function getusuariouserent(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->usuario)) {
                return response()->json(['status' => 2], 200);
            }
            $user = User::where('usuario', '=', $request->usuario)->where('flestado', '=', 1)->get();
            return response()->json(['status' => 1, 'existe' => count($user)], 200);
        } else {
            abort(404);
        }
    }
    
    public function entidadEliminarOficinas(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idOficina)){
                return response()->json(['status' => 2], 200);
            }
            Oficina::where('id','=',$request->idOficina)->update([
                'flestado' => 0,
                'motivo' => 2,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function eliminarTipoDocumento(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idTipoDocumento)){
                return response()->json(['status' => 2], 200);
            }
            TipoDocumento::where('id','=',$request->idTipoDocumento)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function eliminarEntidad(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idEntidad)){
                return response()->json(['status' => 2], 200);
            }
            Entidad::where('id','=',$request->idEntidad)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }
    
    public function eliminarUsuario(Request $request)
    {   
        if($request->ajax()){
            if(empty($request->idUsuario)){
                return response()->json(['status' => 2], 200);
            }
            User::where('id','=',$request->idUsuario)->update([
                'flestado' => 0,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function readdocumento(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->id)) {
                return response()->json(['status' => 2], 200);
            }
            Detalledocumento::where('id', '=', $request->id)->update([
                'isread' => 1,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function readagenda(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->id)) {
                return response()->json(['status' => 2], 200);
            }
            Agendar::where('id', '=', $request->id)->update([
                'isread' => 1,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }

    public function readevento(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->id)) {
                return response()->json(['status' => 2], 200);
            }
            Eventos::where('id', '=', $request->id)->update([
                'isread' => 1,
            ]);
            return response()->json(['status' => 1], 200);
        } else {
            abort(404);
        }
    }


    public function cargar_rol_tipo(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->entidadValor)) {
                return response()->json(['status' => 2], 200);
            }
            $roles = User::where('flestado', '=', 1)
                        ->where('entidad_id', '=', $request->entidadValor)
                        ->where('role_id', 3)
                        ->get();
            return response()->json(['status' => 1, 'existe' => count($roles)], 200);
        } else {
            abort(404);
        }
    }
}
