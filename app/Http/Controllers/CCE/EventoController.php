<?php

namespace App\Http\Controllers\CCE;

use App\Models\Entidad;
use App\Models\Eventos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EventoRequest;


class EventoController extends Controller
{
    //

    public function index()
    {
        return view('CCE.eventos.index',[
            'eventos'  => Eventos::where('flestado','=','1')->get(),
            'entidads' => Entidad::where('id', '!=', 1)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('CCE.eventos.create', [
            'entidads' => Entidad::where('id', '<>', 1)->get()
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Eventos  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit(Eventos $evento)
    {
        //dd($documento->detalledocumentos);
        $eventos = $evento->entidads;
        $entxage = array();
        for ($i = 0; $i < count($eventos); $i++) {
            array_push($entxage, $eventos[$i]->id);
        }
        //dd($entxage);
        return view('CCE.eventos.editar', [
            'evento' => $evento,
            'entxage' => $entxage,
            'entidads' => Entidad::where('id', '!=', 1)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $fechas = setformatDatetime($request->fechainifin);
        //Registramos ID
        $evento = new Eventos;
        $evento->user_id = auth()->user()->id;
        $evento->titulo = $request->titulo;
        if ($request->file('documentos')) {
            //Eliminar imagen
            $evento->flayer = $request->file('documentos')->store('eventos', 'public');
        }
        $evento->fecha = $fechas;
        
        
        $evento->save();
        $idneo = $evento->id;

        //Registramos entidad por agendar
        if (count($request->entidad_id) > 0) {
            for ($i = 0; $i < count($request->entidad_id); $i++) {
                $evento->entidads()->attach($request->entidad_id[$i]);
            }
        }
        
        return back()->with('status', 'Creado con éxito'); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Eventos  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(EventoRequest $request, Eventos $evento)
    {
        //Actualizamos
        $evento->update($request->all());

        //image
        if ($request->file('documentos')) {
            //Eliminar imagen
            //Storage::disk('public')->delete($entidad->logo);
            $evento->flayer = $request->file('documentos')->store('eventos', 'public');
            $evento->save();
        }
        
        if (count($request->entidad_id) > 0) {
            $evento->entidads()->detach();
            for ($i = 0; $i < count($request->entidad_id); $i++) {
                $evento->entidads()->attach($request->entidad_id[$i]);
            }
        }

        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }



    // private function formatfechatime($fechatime){
    //     //Fecha inicial
    //     $fechaini = explode(" ", $fechatime);
    //     $dateini = $this->formatfecha($fechaini[0]);
    //     $horaini = $this->formathora($fechaini[1], $fechaini[2]);
    //     $feinifinal = $dateini." ".$horaini;

    //     // //Fecha final
    //     // $fechafin = explode(" ", $fechaindi[1]);
    //     // $datefin = $this->formatfecha($fechafin[0]);
    //     // $horafin = $this->formathora($fechafin[1], $fechafin[2]);
    //     // $fefinfinal = $datefin . " " . $horafin;
    //     return array($feinifinal);
    // }

    // private function formatfecha($fecha){
    //     $date = explode("/", $fecha);
    //     return $date[2]."-".$date[1]."-".$date[0];
    // }

    // private function formathora($hora, $tipo)
    // {
    //     if ($tipo == "AM") {
    //         $hora = $hora . ":00";
    //     } else {
    //         $h = explode(":", $hora);
    //         $hora = (int) ($h[0] + 12) . ":" . $h[1] . ":00";
    //     }
    //     return $hora;
    // }
}
