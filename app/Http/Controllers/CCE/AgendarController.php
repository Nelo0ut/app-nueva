<?php

namespace App\Http\Controllers\CCE;

use App\Models\Agendar;
use App\Models\Entidad;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgendarRequest;
use Carbonl\Carbon;
use App\helper\bash;
use App\Mail\AgendarEntidad;
use App\Models\User;
use DateTime;
use Spatie\CalendarLinks\Link;
use Illuminate\Support\Facades\Mail;

class AgendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   date_default_timezone_set("America/Lima");
        return view('CCE.agendar.index', [
            'agendas' => Agendar::where('flestado', '=', 1)->get(),
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
        return view('CCE.agendar.create', [
            'entidads' => Entidad::where('id', '<>', 1)->get()
        ]);
    }
    private function formatfechatime(string $fechainifin): array
{
    // Ejemplo típico: "2024-02-01 08:00 - 2024-02-01 10:00"
    $fechas = explode(' - ', $fechainifin);

    return [
        date('Y-m-d H:i:s', strtotime($fechas[0])),
        date('Y-m-d H:i:s', strtotime($fechas[1])),
    ];
}
    public function store(AgendarRequest $request)
    {
        $fechas = $this->formatfechatime($request->fechainifin);

        $agendar = new Agendar;
        $agendar->user_id = auth()->user()->id;
        $agendar->description = $request->description;
        $agendar->name = $request->name;
        $agendar->feinicio = $fechas[0];
        $agendar->fefin = $fechas[1];
        $agendar->flestado = 1;
        $agendar->save();

        if (!empty($request->entidad_id)) {
            foreach ($request->entidad_id as $entidadId) {

                $agendar->entidads()->attach($entidadId);

                $usuarios = User::select('name','email','email_2')
                    ->where('entidad_id', $entidadId)
                    ->get();

                if ($usuarios->isNotEmpty()) {

                    $from = DateTime::createFromFormat(
                        'Y-m-d H:i',
                        substr($fechas[0], 0, -3)
                );

                    $to = DateTime::createFromFormat(
                        'Y-m-d H:i',
                        substr($fechas[1], 0, -3)
                );

                    $link = Link::create($request->name, $from, $to)
                        ->description($request->description);

                $emails = [];

                foreach ($usuarios as $usuario) {
                    if ($usuario->email_2) {
                        $emails[] = $usuario->email_2;
                    }
                    $emails[] = $usuario->email;
                }

                Mail::to($emails)->send(new AgendarEntidad([
                    'nombre' => $usuarios[0]->name,
                    'url' => config('app.url'),
                    'google' => $link->google(),
                    'yahoo' => $link->yahoo(),
                    'webOutlook' => $link->webOutlook(),
                    'ics' => $link->ics(),
                    'feini' => substr($fechas[0], 0, -3),
                    'fefin' => substr($fechas[1], 0, -3),
                    'nombreage' => $request->name,
                    'descripcion' => $request->description,
                ]));
            }
        }
    }

        return back()->with('status', 'Creado con éxito'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agendar  $agendar
     * @return \Illuminate\Http\Response
     */
    public function show(Agendar $agendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agendar  $agendar
     * @return \Illuminate\Http\Response
     */
    public function edit(Agendar $agendar)
    {
        //dd($documento->detalledocumentos);
        $entidades = $agendar->entidads;
        $entxage = array();
        for ($i = 0; $i < count($entidades); $i++) {
            array_push($entxage, $entidades[$i]->id);
        }
        $fechaini = $agendar->feinicio;
        $fechafin = $agendar->fefin;

        $fechaeditar = setfechadouble($fechaini, $fechafin);
        //dd($entxage);
        return view('CCE.agendar.editar', [
            'agendar' => $agendar,
            'entxage' => $entxage,
            'fechaeditar' => $fechaeditar,
            'entidads' => Entidad::where('id', '!=', 1)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Agendar  $agendar
     * @return \Illuminate\Http\Response
     */
    public function update(AgendarRequest $request, Agendar $agendar)
    {
        
        $fechas = formatfechatime($request->fechainifin);
        $agendar->feinicio = $fechas[0];
        $agendar->fefin = $fechas[1];
        if (count($request->entidad_id) > 0) {
            $agendar->entidads()->detach();
            for ($i = 0; $i < count($request->entidad_id); $i++) {
                $agendar->entidads()->attach($request->entidad_id[$i]);

                $usuario = User::select('email')
                            ->where('entidad_id', $request->entidad_id[$i])
                            // ->where('role_id', 3)
                            ->get();
                if(count($usuario)>0){
                    $from = DateTime::createFromFormat('Y-m-d H:i', substr($fechas[0], 0, -3));
                    $to = DateTime::createFromFormat('Y-m-d H:i', substr($fechas[1], 0, -3));
    
                    $link = Link::create($request->name, $from, $to)
                        ->description($request->description);
    
                    $data = [
                            'nombre' => $usuario[0]->name,
                            'url' => config('app.url'),
                            'google' => $link->google(),
                            'yahoo' => $link->yahoo(),
                            'webOutlook' => $link->webOutlook(),
                            'ics' => $link->ics(),
                            'feini' => substr($fechas[0], 0, -3),
                            'fefin' => substr($fechas[1], 0, -3),
                            'nombreage' => $request->name,
                            'descripcion' => $request->description,
                        ];
                            $array = array();
                        for($i = 0; $i< sizeof($usuario) ; $i++ ){
                                if($usuario[$i]->email_2){
                                    array_push($array,  $usuario[$i]->email_2);
                                }
                                array_push($array,  $usuario[$i]->email);
                            }
                        Mail::to($array)->send(new AgendarEntidad($data));
                }
            }
        }
        $agendar->update($request->all());



        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agendar  $agendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agendar $agendar)
    {
       
    }

    
}
