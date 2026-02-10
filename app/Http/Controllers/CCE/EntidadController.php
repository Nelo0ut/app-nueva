<?php

namespace App\Http\Controllers\CCE;

use App\Models\Entidad;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntidadRequest;
use Iluminate\Support\Facades\Storage;

class EntidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $entidads = Entidad::latest()->get();
        // dd($entidads);

        //return view('CCE.entidads.index', compact($entidads));
        return view('CCE.entidads.index', [
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado','=',1)->latest()->get()
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
    public function store(EntidadRequest $request)
    {
        // Guardamos
        $entidad = Entidad::create($request->all());

        //image
        if ($request->file('logo')) {
            $entidad->logo = $request->file('logo')->store('logos', 'public');
            $request->logo->move(public_path('logos'), $entidad->logo);
            $entidad->save();
        }

        //Retornamos
        return back()->with('status', 'Creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function show(Entidad $entidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Entidad $entidad)
    {
        return view('CCE.entidads.editar',['entidad' => $entidad]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function update(EntidadRequest $request, Entidad $entidad)
    {
        //Actualizamos
        $entidad->update($request->all());

        //image
        if ($request->file('logo')) {
            //Eliminar imagen
            //Storage::disk('public')->delete($entidad->logo);
            $entidad->logo = $request->file('logo')->store('logos', 'public');
            $request->logo->move(public_path('logos'), $entidad->logo);
            $entidad->save();
        }

        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entidad $entidad)
    {
        //
    }

    public function mostrar()
    {
        $entidad = Entidad::where('id', session()->get('identidad'))->get();
        // dd($entidad);
        return view('Entidad.entidads.editar', ['entidad' => $entidad[0]]);
    }

    public function actualizar(EntidadRequest $request)
    {
        Entidad::findOrFail(session()->get('identidad'))->update($request->all());

        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }
}
