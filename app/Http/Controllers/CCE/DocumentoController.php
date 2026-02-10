<?php

namespace App\Http\Controllers\CCE;

use App\Models\Documento;
use App\Models\Entidad;
use App\Models\TipoDocumento;
use App\Models\Detalledocumento;
use App\Models\User;
use App\Models\DocumentoEntidad;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Mail\NuevoDocumento;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('CCE.documentos.index', [
            'documentos' => Documento::where('flestado','=',1)->get(),
            'entidads' => Entidad::where('id','!=',1)->get(),
            'tipoDocumentos' => TipoDocumento::where('flestado','=',1)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('CCE.documentos.create', [
            'entidads' => Entidad::where('id','<>',1)->get(),
            'tipoDocumentos' => TipoDocumento::where('flestado','=',1)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentoRequest $request)
    {

        //dd($request->all());
        //Registramos ID
        $documento = new Documento;
        $documento->user_id = auth()->user()->id;
        $documento->tipodocumento_id = $request->tipodocumento_id;
        $documento->name = $request->name;
        if ($request->enviar_notification) {
            $documento->enviar_notification = $request->enviar_notification;
        }
        // if($request->enviar_notification){
            
        //     $fechacompleta = explode(" ", $request->feinsert);
        //     if($fechacompleta[2] == "PM"){
        //         $hora = explode(":", $fechacompleta[1]);
        //         if((int)$hora[0] < 12){
        //             $suma = (int) $hora[0] + 12;
        //             $fechafinal = $fechacompleta[0] . " " .$suma.":".$hora[1].":00";
        //         }else{
        //             $fechafinal = $fechacompleta[0] . " " . $fechacompleta[1] . ":00";
        //         }
        //     }else{
        //         $hora = explode(":", $fechacompleta[1]);
        //         if ((int) $hora[0] == 12) {
        //             $fechafinal = $fechacompleta[0] . " 00:".$hora[1].":00";
        //         }else{
        //             $fechafinal = $fechacompleta[0] . " " . $fechacompleta[1] . ":00";
        //         }  
        //     }
        //     $documento->feinsert = $fechafinal;
        // }else{
        //     $fechafinal = now(); 
        // }
        $documento->flestado = 1;
        $documento->save();

        $idneo = $documento->id;
        //Registramos entidad por documento
        if(count($request->entidad_id) > 0){
            for ($i=0; $i < count($request->entidad_id); $i++) { 
                $documento->entidads()->attach($request->entidad_id[$i]);
            }
        }

        //Registramos los documentos
        if($request->hasfile('documentos'))
        {
            for ($i=0; $i < count($request->name_doc); $i++) {
                $file = $request->file('documentos')[$i];
                $original_name = time() . "-" . Str::slug(preg_replace('/\..+$/', '', $file->getClientOriginalName())) . '.' . $file->getClientOriginalExtension();
 
                $detalledocumento = new Detalledocumento;
                $detalledocumento->documento_id = $idneo;
                $detalledocumento->name = $request->name_doc[$i];
                // $detalledocumento->documento = $request->file('documentos')[$i]->store('documentos','public');
                // $request->documentos[$i]->move(public_path('documentos'), $detalledocumento->documento);
                
                $request->documentos[$i]->move(public_path('documentos'), $original_name);
                $detalledocumento->documento = "documentos/" . $original_name;

                $detalledocumento->flestado = 1;
                $detalledocumento->save();
            }
        }

        if ($request->enviar_notification == 1) {
            //Enviamos el mail
            if (count($request->entidad_id) > 0) {
                for ($a = 0; $a < count($request->entidad_id); $a++) { 
                    $usuario = DB::table('users')
                                    ->join('entidads', 'users.entidad_id', '=', 'entidads.id')
                                    ->select('users.name', 'users.email', 'users.email_2', 'entidads.name AS namenet')
                                    ->where('users.entidad_id', $request->entidad_id[$a])
                                    // ->orderBy('role_id', 'ASC')
                                    // ->limit(2)
                                    ->get()
                                    ->toArray();
                    if (count($usuario) > 0) {
                        $data = [
                            'title' => $request->name,
                            'nombre' => $usuario[0]->namenet,
                            'url' => config('app.url'),
                            'documentos' => $request->name_doc
                        ];
                        
                        $array = array();
                        for($i = 0; $i< sizeof($usuario) ; $i++ ){
                            if($usuario[$i]->email_2){
                                array_push($array,  $usuario[$i]->email_2);
                            }
                            array_push($array,  $usuario[$i]->email);
                        }

                        Mail::to($array)->send(new NuevoDocumento($data));
                        // if( count($usuario) == 1 ){
                        //     Mail::to($usuario[0]->email)->send(new NuevoDocumento($data));
                        // }else{
                        //     Mail::to($usuario[0]->email)->cc($usuario[1]->email)->send(new NuevoDocumento($data));
                        // }
                    }
                }
            }
        }
        return redirect('admin/documento')->with('status', 'Creado con éxito'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function edit(Documento $documento)
    {
        //dd($documento->detalledocumentos);
        $entidades = $documento->entidads;
        $entxdoc = array();
        for ($i=0; $i < count($entidades); $i++) { 
            array_push($entxdoc, $entidades[$i]->id);
        }
        //dd($entxdoc);
        return view('CCE.documentos.editar',[
                            'documento' => $documento,
                            'entxdoc' => $entxdoc,
                            'entidads' => Entidad::where('id','!=',1)->get(),
                            'tipoDocumentos' => TipoDocumento::get()
                        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentoRequest $request, Documento $documento)
    {
        //
        $documento->update($request->all());
        $idneo = $documento->id;
        if($request->hasfile('documentos'))
        {
            for ($i=0; $i < count($request->name_doc); $i++) {
                $file = $request->file('documentos')[$i];
                $original_name = time() . "-" . Str::slug(preg_replace('/\..+$/', '', $file->getClientOriginalName())) . '.' . $file->getClientOriginalExtension();

                $detalledocumento = new Detalledocumento;
                $detalledocumento->documento_id = $idneo;
                $detalledocumento->name = $request->name_doc[$i];

                $request->documentos[$i]->move(public_path('documentos'), $original_name);
                $detalledocumento->documento = "documentos/" . $original_name;


                // $detalledocumento->documento = $request->file('documentos')[$i]->store('documentos','public');
                // $request->documentos[$i]->move(public_path('documentos'), $detalledocumento->documento);
                $detalledocumento->flestado = 1;
                $detalledocumento->save();
            }
        }
        if (count($request->entidad_id) > 0) {
            $documento->entidads()->detach();
            for ($i = 0; $i < count($request->entidad_id); $i++) {
                $documento->entidads()->attach($request->entidad_id[$i]);
            }
        }

        return back()->with('status', 'Actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {
        //
        
    }

    // Entidad - Documento
    public function listar()
    {
        $documentos = DB::table('documentos')
            ->join('documento_entidad', 'documentos.id', '=', 'documento_entidad.documento_id')
            ->join('detalledocumentos', 'documentos.id', '=', 'detalledocumentos.documento_id')
            ->join('tipo_documentos', 'documentos.tipodocumento_id', '=', 'tipo_documentos.id')
            ->select('documentos.id', 'documentos.name', 'documentos.enviar_notification', 'documentos.feinsert', 'documentos.created_at', 'tipo_documentos.tipodoc', 'detalledocumentos.name AS nombre_doc', 'detalledocumentos.documento')
            ->where('documento_entidad.entidad_id', session()->get('identidad'))
            ->where('documentos.flestado', 1)
            ->where('detalledocumentos.flestado', 1)
            ->orderBy('documentos.created_at', 'DESC')
            ->get();
        //dd($documentos);
        return view('Entidad.documentos.index', [
            'documentos' => $documentos
        ]);
    }

    public function documentostotales(){
        $entidades = Entidad::where('id', '!=', 1)->get();
        $documentos = Documento::all();
        
        
        foreach ($entidades as $entidad) {
            foreach ($documentos as $documento) {
                $documento->entidads()->attach($entidad->id);
            }
        }
        //dd($entidades);
    }
}
