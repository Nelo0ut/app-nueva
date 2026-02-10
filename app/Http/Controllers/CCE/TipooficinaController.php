<?php

namespace App\Http\Controllers\CCE;

use App\Http\Controllers\Controller;
use App\Models\Tipooficina;
use App\Http\Requests\TipooficinaRequest;

class TipooficinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('CCE.tipooficinas.index', [
            'tipooficinas' => Tipooficina::where('flestado','=',1)->latest()->get()
        ]);
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
    public function store(TipooficinaRequest $request)
    {
        // Guardamos
        $tipooficina = Tipooficina::create($request->all());

        //Retornamos
        return back()->with('status', 'Creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tipooficina  $tipooficina
     * @return \Illuminate\Http\Response
     */
    public function show(Tipooficina $tipooficina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tipooficina  $tipooficina
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipooficina $tipooficina)
    {
        return view('CCE.tipooficinas.editar',['tipooficina' => $tipooficina]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tipooficina  $tipooficina
     * @return \Illuminate\Http\Response
     */
    public function update(TipooficinaRequest $request, Tipooficina $tipooficina)
    {
        //Actualizamos
        $tipooficina->update($request->all());

        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipooficina  $tipooficina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipooficina $tipooficina)
    {
        //
    }
}
