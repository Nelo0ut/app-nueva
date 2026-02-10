<?php

namespace App\Http\Controllers\CCE;

use App\Http\Controllers\Controller;
use App\Models\Oficina;
use App\Models\Tipooficina;
use App\Models\Entidad;
use App\Models\Ubigeo;
use Illuminate\Http\Request;
use App\Http\Requests\OficinaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OficinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('CCE.oficinas.index', [
            'oficinas' => Oficina::where('flestado','=',1)->get(),
            'tipooficinas' => Tipooficina::get(),
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado', '=', 1)->get(),
            'activar_filtro'=>false

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session()->get('role_id') == 1 || session()->get('role_id') == 2) {
            $vista = "CCE.oficinas.create";
        } else {
            $vista = "Entidad.oficinas.create";
        }
        // $ubigeos = Ubigeo::where('codprov', 0)->where('codprov', 0)->get();
        // dd($ubigeos);
        return view($vista, [
            'tipooficinas' => Tipooficina::where('flestado',1)->get(),
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado',1)->get(),
            'ubigeos' => Ubigeo::where('codprov',0)->where('codist',0)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OficinaRequest $request)
    {
        // Guardamos
        //$oficina = Oficina::create($request->all());

        $idubigeoDis = Ubigeo::where('coddepa',$request->inputDistriDep)->where('codprov',$request->inputDistriPro)->where('codist',$request->inputDistriDis)->get();
        $idubigeoChe = Ubigeo::where('coddepa',$request->inputChequeDep)->where('codprov',$request->inputChequePro)->where('codist',$request->inputChequeDis)->get();
        $idubigeoTra = Ubigeo::where('coddepa',$request->inputTransfeDep)->where('codprov',$request->inputTransfePro)->where('codist',$request->inputTransfeDis)->get();

        $cantidadOficinas = DB::table('oficinas')
        ->select('entidad_id')
        ->where('ubigeodistrital', $idubigeoDis[0]->id)
        ->get()->toArray();

        $plazaexclusivaSoN ='N';


        // dd(in_array(6, $cantidadOficinas[0]->entidad_id));

        $idEntidadOficinas = array();
        if(count($cantidadOficinas) > 0){
            for($i = 0; $i< count($cantidadOficinas) ; $i++){
                array_push($idEntidadOficinas, $cantidadOficinas[$i]->entidad_id);
            }
        }
        // dd($idEntidadOficinas);

        if(in_array(6, $idEntidadOficinas)){
            $total = count($idEntidadOficinas) - 1;
        }else{
            $total = count($idEntidadOficinas);
        }

        if ($total < 1) {
            $plazaexclusivaSoN = 'S';

        } else  {
            for ($i = 0; $i < sizeof($idEntidadOficinas); $i++) {
                $update_query = "UPDATE oficinas SET plazaexclusiva ='N' WHERE ubigeodistrital =" . $idubigeoDis[0]->id . " and entidad_id =" . $idEntidadOficinas[$i].";";
                DB::statement($update_query);
            }
            $plazaexclusivaSoN = 'N';
        }

        // if(sizeof($idEntidadOficinas)==0){
        //     $plazaexclusivaSoN ='S';
        // }
        // if(sizeof($cantidadOficinas) == 1){
        //     if($cantidadOficinas[0]->entidad_id == 6){
        //         $plazaexclusivaSoN ='S';
        //         if($request->entidad_id == 6){
        //             $update_query = "UPDATE oficinas SET plazaexclusiva ='N' WHERE ubigeodistrital =".$idubigeoDis[0]->id." and entidad_id =".$cantidadOficinas[0]->entidad_id;
        //             DB::statement($update_query);
        //             $plazaexclusivaSoN ='N';
        //         }
        //     }
        //     else{
        //         if($request->entidad_id == 6){
        //             $plazaexclusivaSoN ='S';
        //         }
        //         else{
        //             $update_query = "UPDATE oficinas SET plazaexclusiva ='N' WHERE ubigeodistrital =".$idubigeoDis[0]->id." and entidad_id =".$cantidadOficinas[0]->entidad_id;
        //             DB::statement($update_query);
        //             $plazaexclusivaSoN ='N';
        //         }
        //     }
        //     $plazaexclusivaSoN;
        // }
        // if(sizeof($cantidadOficinas) == 2){
        //     for($i=0;$i<sizeof($cantidadOficinas);$i++){
        //         $update_query = "UPDATE oficinas SET plazaexclusiva ='N' WHERE ubigeodistrital =".$idubigeoDis[0]->id." and entidad_id =".$cantidadOficinas[$i]->entidad_id;
        //         DB::statement($update_query);
        //     }
        //     $plazaexclusivaSoN ='N';
        // }

        #Cambiamos los ingresos de Motivos
        $oficina = new Oficina;
        $oficina->entidad_id = $request->entidad_id;
        $oficina->tipooficina_id = $request->tipooficina_id;
        $oficina->ubigeodistrital = $idubigeoDis[0]->id;
        $oficina->ubigeocheques = $idubigeoChe[0]->id;
        $oficina->ubigeotransferencia = $idubigeoTra[0]->id;
        $oficina->name = $request->name;
        $oficina->numero = $request->numero;
        $oficina->domicilio = $request->domicilio;
        $oficina->localidad = $request->localidad;
        $oficina->nameplaza = $request->nameplaza;
        $oficina->numeroplaza = $request->numeroplaza;
        $oficina->plazaexclusiva= $plazaexclusivaSoN;
        $oficina->preftelefono = $request->preftelefono;
        $oficina->telefono1 = $request->telefono1;
        $oficina->telefono2 = $request->telefono2;
        $oficina->fax = $request->fax;
        $oficina->centraltelefonica = $request->centraltelefonica;
        $oficina->motivo = $request->motivo;
        $oficina->fealta = date('Y-m-d');
        $oficina->flestado = 1;
        $oficina->save();

        //Retornamos
        return back()->with('status', 'Creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Oficina  $oficina
     * @return \Illuminate\Http\Response
     */
    public function show(Oficina $oficina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Oficina  $oficina
     * @return \Illuminate\Http\Response
     */
    public function edit(Oficina $oficina)
    {
        // $ubigeodis = Ubigeo::where('id',$oficina->ubigeodistrital)->get();
        // dd($ubigeodis);
        if (session()->get('role_id') == 1 || session()->get('role_id') == 2){
            $vista = "CCE.oficinas.editar";
        }else{
            $vista = "Entidad.oficinas.editar";
        }
        return view($vista,[
                        'oficina' => $oficina,
                        'ubigeodis' => Ubigeo::where('id',$oficina->ubigeodistrital)->get(),
                        'ubigeoche' => Ubigeo::where('id',$oficina->ubigeocheques)->get(),
                        'ubigeotra' => Ubigeo::where('id',$oficina->ubigeotransferencia)->get(),
                        'tipooficinas' => Tipooficina::get(),
                        'entidads' => Entidad::where('id', '!=', '1')->get(),
                        'ubigeos' => Ubigeo::where('codprov',0)->where('codprov',0)->get(),
                    ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Oficina  $oficina
     * @return \Illuminate\Http\Response
     */
    public function update(OficinaRequest $request, Oficina $oficina)
    {

        //dd($request);
        $idubigeoDis = Ubigeo::where('coddepa',$request->inputDistriDep)->where('codprov',$request->inputDistriPro)->where('codist',$request->inputDistriDis)->get();
        $idubigeoChe = Ubigeo::where('coddepa',$request->inputChequeDep)->where('codprov',$request->inputChequePro)->where('codist',$request->inputChequeDis)->get();
        $idubigeoTra = Ubigeo::where('coddepa',$request->inputTransfeDep)->where('codprov',$request->inputTransfePro)->where('codist',$request->inputTransfeDis)->get();

        $cantidadOficinas = DB::table('oficinas')
            ->select('entidad_id')
            ->where('ubigeodistrital', $idubigeoDis[0]->id)
            ->get()->toArray();

        $plazaexclusivaSoN = 'N';

        // dd(in_array(6, $cantidadOficinas[0]->entidad_id));

        $idEntidadOficinas = array();
        if (count($cantidadOficinas) > 0
        ) {
            for ($i = 0; $i < count($cantidadOficinas); $i++) {
                array_push($idEntidadOficinas, $cantidadOficinas[$i]->entidad_id);
            }
        }
        // dd($idEntidadOficinas);

        if (in_array(6, $idEntidadOficinas)) {
            $total = count($idEntidadOficinas) - 1;
        } else {
            $total = count($idEntidadOficinas);
        }

        if ($total < 1) {
            $plazaexclusivaSoN = 'S';
        } else {
            for ($i = 0; $i < sizeof($idEntidadOficinas); $i++) {
                $update_query = "UPDATE oficinas SET plazaexclusiva ='N' WHERE ubigeodistrital =" . $idubigeoDis[0]->id . " and entidad_id =" . $idEntidadOficinas[$i] . ";";
                DB::statement($update_query);
            }
            $plazaexclusivaSoN = 'N';
        }

        //dd($idubigeoDis[0]->id);
        $oficina->entidad_id = $request->entidad_id;
        $oficina->tipooficina_id = $request->tipooficina_id;
        $oficina->ubigeodistrital = $idubigeoDis[0]->id;
        $oficina->ubigeocheques = $idubigeoChe[0]->id;
        $oficina->ubigeotransferencia = $idubigeoTra[0]->id;
        $oficina->name = $request->name;
        $oficina->numero = $request->numero;
        $oficina->plazaexclusiva = $plazaexclusivaSoN;
        $oficina->domicilio = $request->domicilio;
        $oficina->localidad = $request->localidad;
        $oficina->nameplaza = $request->nameplaza;
        $oficina->numeroplaza = $request->numeroplaza;
        $oficina->preftelefono = $request->preftelefono;
        $oficina->telefono1 = $request->telefono1;
        $oficina->telefono2 = $request->telefono2;
        $oficina->fax = $request->fax;
        $oficina->centraltelefonica = $request->centraltelefonica;
        $oficina->motivo = $request->motivo;
        #actualizamos el campo fecha según el motivo
        if ($request->motivo == 1) {
            $oficina->fealta = date('Y-m-d');
        } elseif ($request->motivo == 2) {
            $oficina->febaja = date('Y-m-d');
        } elseif ($request->motivo == 3) {
            $oficina->femodifica = date('Y-m-d');
        } else {
            $oficina->febajadef = date('Y-m-d');
            $oficina->flestado = 0;
        }
        $oficina->update();

        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Oficina  $oficina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Oficina $oficina)
    {
        //
    }

    public function listar()
    {
        $oficina = Oficina::select('oficinas.id', 'oficinas.name', 'entidads.code','entidads.alias', 'oficinas.numero', 'oficinas.domicilio','oficinas.entidad_id','oficinas.tipooficina_id','oficinas.numeroplaza', 'oficinas.nameplaza', 'oficinas.plazaexclusiva', 'oficinas.codigopostal', 'oficinas.numeroextension', 'oficinas.localidad', 'oficinas.preftelefono', 'oficinas.telefono1','oficinas.telefono2','oficinas.fax', 'oficinas.centraltelefonica')
                        ->join('entidads', 'oficinas.entidad_id','=','entidads.id')
                        ->join('tipooficinas','oficinas.tipooficina_id','=','tipooficinas.id')
                        ->addSelect(['ubigeodis' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeodistrital', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeoche' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeocheques', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeotra' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeotransferencia', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->where('entidad_id', session()->get('identidad'))
                        ->where('oficinas.flestado', '=', 1)
                        ->get();
        //$oficinas = Oficina::get('entidad_id', session()->get('identidad'));
        return view('Entidad.oficinas.index', [
            'oficinas' => $oficina,
            'tipooficinas' => Tipooficina::get(),
        ]);
    }

    public function listar_oficinas()
    {
        $oficina = Oficina::select('oficinas.id', 'oficinas.name', 'entidads.code','entidads.alias', 'oficinas.numero', 'oficinas.domicilio','oficinas.entidad_id','oficinas.tipooficina_id','oficinas.numeroplaza', 'oficinas.nameplaza', 'oficinas.plazaexclusiva', 'oficinas.codigopostal', 'oficinas.numeroextension', 'oficinas.localidad', 'oficinas.preftelefono', 'oficinas.telefono1','oficinas.telefono2','oficinas.fax', 'oficinas.centraltelefonica')
                        ->join('entidads', 'oficinas.entidad_id','=','entidads.id')
                        ->join('tipooficinas','oficinas.tipooficina_id','=','tipooficinas.id')
                        ->addSelect(['ubigeodis' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeodistrital', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeoche' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeocheques', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeotra' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeotransferencia', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->where('oficinas.flestado', '=', 1)
                        ->get();
        return view('Entidad.oficinas.consultar_total', [
            'oficinas' => $oficina,
            'tipooficinas' => Tipooficina::get(),
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado',1)->get(),
            'activar_filtro'=>false

        ]);
    }

    public function saveimportar(Request $request){
        $request->validate([
            'file' => 'required',
        ]);
        $fileName = time() . '.' . $request->file->extension();
        $request['file'] = $fileName;
        $request->file->move(public_path('oficinas/cargar'), $fileName);

        $import = $this->importar($fileName);
        //dd($import);
        if(count($import[0]) > 0 || count($import[1]) > 0 || count($import[2]) > 0){
            return redirect('admin/importar-oficinas')->with(array('status' => 2, "repetidos" => $import[0], 'noexiste' => $import[1], 'noubigeo' => $import[2]));
        }else{
            return redirect('admin/importar-oficinas')->with(array('status' => 1));
        }

    }

    public function decodificarespacios($linea){
        // $linea = str_replace( '+','é', $linea);
        // $linea = str_replace( '[','á', $linea);
        // $linea = str_replace( '|','í', $linea);
        // $linea = str_replace( '{','ó', $linea);
        // $linea = str_replace( '%','ú', $linea);

        // $linea = str_replace( 'ô','Á', $linea);
        // $linea = str_replace( '@','É', $linea);
        // $linea = str_replace( ']','Í', $linea);
        // $linea = str_replace( '=','Ó', $linea);
        // $linea = str_replace( '¿','Ú', $linea);
        // // $linea = str_replace( '♣','Ñ', $linea);

        // $linea = str_replace( '$','°', $linea);
        // $linea = str_replace( '*',' ', $linea);
        $linea = str_replace('*', 'ñ', $linea);

        $linea = str_replace('%', 'Ñ', $linea);
        return $linea;
    }

    public function codificarespacios($linea)
    {
        // $linea = str_replace('é', '+', $linea);
        // $linea = str_replace('á', '[', $linea);
        // $linea = str_replace('í', '|', $linea);
        // $linea = str_replace('ó', '{', $linea);
        // $linea = str_replace('ú', '%', $linea);

        // $linea = str_replace('Á', 'ô', $linea);
        // $linea = str_replace('É', '@', $linea);
        // $linea = str_replace('Í', ']', $linea);
        // $linea = str_replace('Ó', '=', $linea);
        // $linea = str_replace('Ú', '¿', $linea);
        // //ALT+6540
        // // $linea = str_replace("Ñ", '♣', $linea);

        // $linea = str_replace('°', '$', $linea);
        // $linea = str_replace('º', '$', $linea);
        // $linea = str_replace('“', '', $linea);
        // $linea = str_replace('”', '', $linea);

        // $linea = str_replace(' ', '*', $linea);

        $linea = str_replace('ñ', '*', $linea);

        $linea = str_replace('Ñ', '%', $linea);

        return $linea;
    }

    #Importar oficinas
    public function importar($file){
        //$contents = File::get(public_path('oficinas/cargar/Ofic0820.txt'));
        $contents = fopen(public_path('oficinas/cargar/'.$file), "r");
        $i = 0;
        $Repetidos = array();
        $Noexiste = array();
        $NoUbigeo = array();
        while (!feof($contents)) {
            $linea = fgets($contents);
            $linea = utf8_encode($linea);
            // $linea = $this->codificarespacios($linea);

            // echo "<br>La Linea: ".$linea;

            #Separamos la linea con las variables segun el manual
            $CodigoRegistro = mb_substr($linea,0,2,'UTF-8');
            //  echo "<br>Codigo: " . $CodigoRegistro;

            $NumeroEntidad = mb_substr($linea, 2, 3,'UTF-8');
            //  echo "<br>NumeroEntidad: " . $NumeroEntidad;
            $NumeroOficina = mb_substr($linea, 5, 3,'UTF-8');
            //  echo "<br>NumeroOficina: " . $NumeroOficina;
            $NumeroOficinaPlaza = mb_substr($linea, 8, 4,'UTF-8');
            //  echo "<br>NumeroOficinaPlaza: " . $NumeroOficinaPlaza;
            $UbigeoCheques = mb_substr($linea, 12, 6,'UTF-8');
            //  echo "<br>UbigeoCheques: " . $UbigeoCheques;
            $UbigeoTransferencia = mb_substr($linea, 18, 6,'UTF-8');
            //  echo "<br>UbigeoTransferencia: " . $UbigeoTransferencia;
            $Relleno = mb_substr($linea, 24, 23,'UTF-8');
            //  echo "<br>Relleno: " . $Relleno;
            $NombreOficina = mb_substr($linea, 47, 35,'UTF-8');
            //  echo "<br>NombreOficina: " . $NombreOficina;
            $DomicilioOficina = mb_substr($linea, 82, 80,'UTF-8');
            //  echo "<br>DomicilioOficina: " . $DomicilioOficina;
            $LocalidadOficina = mb_substr($linea, 162, 35,'UTF-8');
            //  echo "<br>LocalidadOficina: " . $LocalidadOficina;
            $CodigoPostalDistrito = mb_substr($linea, 197, 4,'UTF-8');
            //  echo "<br>CodigoPostalDistrito: " . $CodigoPostalDistrito;
            $NombrePlaza = mb_substr($linea, 201, 35,'UTF-8');
            //  echo "<br>NombrePlaza: " . $NombrePlaza;
            $TipoOficina = mb_substr($linea, 236, 1,'UTF-8');
            //  echo "<br>TipoOficina: " . $TipoOficina;
            $UbigeoDistrital = mb_substr($linea, 237, 6,'UTF-8');
            //  echo "<br>UbigeoDistrital: " . $UbigeoDistrital;
            $PlazaExclusiva = mb_substr($linea, 243, 1,'UTF-8');
            //  echo "<br>PlazaExclusiva: " . $PlazaExclusiva;
            $Relleno2 = mb_substr($linea, 244, 2,'UTF-8');
            //  echo "<br>Relleno2: " . $Relleno2;
            $PrefTelefonoLarga = mb_substr($linea, 246, 3,'UTF-8');
            //  echo "<br>PrefTelefonoLarga: " . $PrefTelefonoLarga;
            $NumeroTelefono = mb_substr($linea, 249, 8,'UTF-8');
            //  echo "<br>NumeroTelefono: " . $NumeroTelefono;
            $NumeroTelefono2 = mb_substr($linea, 257, 8,'UTF-8');
            //  echo "<br>NumeroTelefono2: " . $NumeroTelefono2;
            $NumeroTelefonoFax = mb_substr($linea, 265, 8,'UTF-8');
            //  echo "<br>NumeroTelefonoFax: " . $NumeroTelefonoFax;
            $NumeroCentralTelefonica = mb_substr($linea, 273, 8,'UTF-8');
            //  echo "<br>NumeroCentralTelefonica: " . $NumeroCentralTelefonica;
            $NumeroExtension = mb_substr($linea, 281, 5,'UTF-8');
            //  echo "<br>NumeroExtension: " . $NumeroExtension;
            $MotivoActualizacion = mb_substr($linea, 286, 1,'UTF-8');
            //  echo "<br>MotivoActualizacion: " . $MotivoActualizacion;
            $FechaActualizacion = mb_substr($linea, 287, 8,'UTF-8');
            //  echo "<br>FechaActualizacion: " . $FechaActualizacion;
            $Relleno3 = mb_substr($linea, 295, 5,'UTF-8');
            //  echo "<br>Relleno3: " . $Relleno3;
            //dd($CodigoRegistro);

            // echo "<br><br>";

            #Validamos si existe el codigo de la entidad
            $entidad = Entidad::where('code', $NumeroEntidad)->get();
            // var_dump(count($entidad) > 0);
            // dd($entidad);
            if (count($entidad) > 0) {
                #Validamos si existe la oficina en la BD por el codigo
                $existeoficina = Oficina::select('oficinas.id', 'motivo', 'entidads.code', 'oficinas.numero', 'oficinas.numeroplaza')
                    ->join('entidads', 'oficinas.entidad_id', '=', 'entidads.id')
                    ->where('entidads.code', $NumeroEntidad)
                    ->where('oficinas.numero', $NumeroOficina)
                    ->where('oficinas.numeroplaza', $NumeroOficinaPlaza)
                    ->first();

                #Obtenemos los id de los ubigeos
                $codDepaDis = substr($UbigeoDistrital,0,2);
                $codProvDis = substr($UbigeoDistrital, 2, 2);
                $codDisDis = substr($UbigeoDistrital, 4, 2);
                $idubigeoDis = Ubigeo::where('coddepa', (int)$codDepaDis)->where('codprov', (int)$codProvDis)->where('codist', (int)$codDisDis)->get();

                $codDepaChe = substr($UbigeoCheques, 0, 2);
                $codProvChe = substr($UbigeoCheques, 2, 2);
                $codDisChe = substr($UbigeoCheques, 4, 2);
                $idubigeoChe = Ubigeo::where('coddepa', (int)$codDepaChe)->where('codprov', (int)$codProvChe)->where('codist', (int)$codDisChe)->get();

                $codDepaTrans = substr($UbigeoTransferencia, 0, 2);
                $codProvTrans = substr($UbigeoTransferencia, 2, 2);
                $codDisTrans = substr($UbigeoTransferencia, 4, 2);
                $idubigeoTra = Ubigeo::where('coddepa', (int)$codDepaTrans)->where('codprov', (int)$codProvTrans)->where('codist', (int)$codDisTrans)->get();



                #Obtenemos la fecha de actualizacion
                $anofecha = substr($FechaActualizacion, 0, 4);
                $mesfecha = substr($FechaActualizacion, 4, 2);
                $diafecha = substr($FechaActualizacion, 6, 2);

                $fechafinal = $anofecha."-". $mesfecha."-". $diafecha;

                if(count($idubigeoDis) > 0 && count($idubigeoChe) > 0 && count($idubigeoTra) > 0){
                    if (!empty($existeoficina)) {
                        #Cambiamos los ingresos de Motivos
                        if ($MotivoActualizacion == "A") {
                            $motivooficina = 1;
                            if($existeoficina->motivo != 1){
                                $existeoficina->fealta = $fechafinal;
                            }
                        } elseif ($MotivoActualizacion == "B") {
                            $motivooficina = 2;
                            if ($existeoficina->motivo != 2) {
                                $existeoficina->febaja = $fechafinal;
                            }
                        } elseif ($MotivoActualizacion == "C") {
                            $motivooficina = 3;
                            if ($existeoficina->motivo != 3) {
                                $existeoficina->femodifica = $fechafinal;
                            }
                        } else {
                            $motivooficina = 4;
                            if ($existeoficina->motivo != 4) {
                                $existeoficina->febajadef = $fechafinal;
                            }
                        }
                        $existeoficina->tipooficina_id = strip_tags($TipoOficina);
                        $existeoficina->ubigeodistrital = strip_tags($idubigeoDis[0]->id);
                        $existeoficina->ubigeocheques = strip_tags($idubigeoChe[0]->id);
                        $existeoficina->ubigeotransferencia = strip_tags($idubigeoTra[0]->id);
                        $existeoficina->name = strip_tags(Str::of($NombreOficina)->trim());
                        $existeoficina->numero = strip_tags(Str::of($NumeroOficina)->trim());
                        $existeoficina->domicilio = strip_tags(Str::of($DomicilioOficina)->trim());
                        $existeoficina->localidad = strip_tags(Str::of($LocalidadOficina)->trim());
                        $existeoficina->nameplaza = strip_tags(Str::of($NombrePlaza)->trim());
                        $existeoficina->numeroplaza = strip_tags(Str::of($NumeroOficinaPlaza)->trim());
                        $existeoficina->preftelefono = strip_tags(Str::of($PrefTelefonoLarga)->trim());
                        $existeoficina->telefono1 = strip_tags(Str::of($NumeroTelefono)->trim());
                        $existeoficina->telefono2 = strip_tags(Str::of($NumeroTelefono2)->trim());
                        $existeoficina->fax = strip_tags(Str::of($NumeroTelefonoFax)->trim());
                        $existeoficina->centraltelefonica = strip_tags(Str::of($NumeroCentralTelefonica)->trim());
                        $existeoficina->motivo = strip_tags($motivooficina);

                        $existeoficina->plazaexclusiva = strip_tags($PlazaExclusiva);
                        $existeoficina->codigopostal = strip_tags(Str::of($CodigoPostalDistrito)->trim());
                        $existeoficina->numeroextension = strip_tags(Str::of($NumeroExtension)->trim());
                        $existeoficina->save();
                    } else {

                        $oficina = new Oficina;

                        #Cambiamos los ingresos de Motivos
                        if ($MotivoActualizacion == "A") {
                            $oficina->motivo = 1;
                            $oficina->fealta = $fechafinal;
                        } elseif ($MotivoActualizacion == "B") {
                            $oficina->motivo = 2;
                            $oficina->febaja = $fechafinal;
                        } elseif ($MotivoActualizacion == "C") {
                            $oficina->motivo = 3;
                            $oficina->femodifica = $fechafinal;
                        } else {
                            $oficina->motivo = 4;
                            $oficina->febajadef = $fechafinal;
                        }

                        $oficina->entidad_id = strip_tags($entidad[0]->id);
                        $oficina->tipooficina_id = strip_tags($TipoOficina);
                        $oficina->ubigeodistrital = strip_tags($idubigeoDis[0]->id);
                        $oficina->ubigeocheques = strip_tags($idubigeoChe[0]->id);
                        $oficina->ubigeotransferencia = strip_tags($idubigeoTra[0]->id);
                        $oficina->name = strip_tags(Str::of($NombreOficina)->trim());
                        $oficina->numero = strip_tags(Str::of($NumeroOficina)->trim());
                        $oficina->domicilio = strip_tags(Str::of($DomicilioOficina)->trim());
                        $oficina->localidad = strip_tags(Str::of($LocalidadOficina)->trim());
                        $oficina->nameplaza = strip_tags(Str::of($NombrePlaza)->trim());
                        $oficina->numeroplaza = strip_tags(Str::of($NumeroOficinaPlaza)->trim());
                        $oficina->preftelefono = strip_tags(Str::of($PrefTelefonoLarga)->trim());
                        $oficina->telefono1 = strip_tags(Str::of($NumeroTelefono)->trim());
                        $oficina->telefono2 = strip_tags(Str::of($NumeroTelefono2)->trim());
                        $oficina->fax = strip_tags(Str::of($NumeroTelefonoFax)->trim());
                        $oficina->centraltelefonica = strip_tags(Str::of($NumeroCentralTelefonica)->trim());
                        $oficina->plazaexclusiva = strip_tags($PlazaExclusiva);
                        $oficina->codigopostal = strip_tags(Str::of($CodigoPostalDistrito)->trim());
                        $oficina->numeroextension = strip_tags(Str::of($NumeroExtension)->trim());
                        $oficina->flestado = 1;
                        $oficina->save();
                    }
                } else {
                    array_push($NoUbigeo, $UbigeoDistrital);
                }
            } else {
                array_push($Noexiste, $NumeroEntidad);
            }
        }

        fclose($contents);
        // echo "<pre>Repetidos: <br>";
        // print_r($Repetidos);
        // echo "</pre>";
        // echo "<pre>Noexiste: <br>";
        // print_r($Noexiste);
        // echo "</pre>";
        // echo "<pre>NoUbigeo: <br>";
        // print_r($NoUbigeo);
        // echo "</pre>";
        // exit();

        return [$Repetidos, $Noexiste, $NoUbigeo];
    }

    public function filtrar(Request $request){
        $codigo =$request->codigo_entidad;
        $numero =$request->numero;
        $entidad = $request->entidad_select;
        if($codigo == '' && $numero == '' && $entidad == '' ){
            return back()->with('status','Debe ingresar almenos un campo');
        }

        $existe = Oficina::select('oficinas.id', 'oficinas.name', 'entidads.code','entidads.alias', 'oficinas.numero', 'oficinas.domicilio','oficinas.entidad_id','oficinas.tipooficina_id','oficinas.numeroplaza', 'oficinas.nameplaza', 'oficinas.plazaexclusiva', 'oficinas.codigopostal', 'oficinas.numeroextension', 'oficinas.localidad', 'oficinas.preftelefono', 'oficinas.telefono1','oficinas.telefono2','oficinas.fax', 'oficinas.centraltelefonica')
                        ->join('entidads', 'oficinas.entidad_id','=','entidads.id')
                        ->join('tipooficinas','oficinas.tipooficina_id','=','tipooficinas.id')
                        ->addSelect(['ubigeodis' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeodistrital', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeoche' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeocheques', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeotra' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeotransferencia', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->entidad($entidad)
                        ->oficinanumeroplaza($codigo)
                        ->oficinanumero($numero)
                        ->get();

        // dd($existe[0]);

        return view('CCE.oficinas.index', [
            // 'oficinas' => Oficina::where('flestado','=',1)->where('numero',$numero)->get(),
            'oficinas' => $existe,
            'tipooficinas' => Tipooficina::get(),
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado', '=', 1)->get(),
            'activar_filtro'=>true
        ]);

    }

    public function filtrar_oficinas_entidad(Request $request){
        $codigo =$request->codigo_entidad;
        $numero =$request->numero;
        $entidad = $request->entidad_select;


        if($codigo == '' && $numero == '' && $entidad == '' ){
            return back()->with('status','Debe ingresar almenos un campo');
        }


        $existe = Oficina::select('oficinas.id', 'oficinas.name', 'entidads.code','entidads.alias', 'oficinas.numero', 'oficinas.domicilio','oficinas.entidad_id','oficinas.tipooficina_id','oficinas.numeroplaza', 'oficinas.nameplaza', 'oficinas.plazaexclusiva', 'oficinas.codigopostal', 'oficinas.numeroextension', 'oficinas.localidad', 'oficinas.preftelefono', 'oficinas.telefono1','oficinas.telefono2','oficinas.fax', 'oficinas.centraltelefonica')
                        ->join('entidads', 'oficinas.entidad_id','=','entidads.id')
                        ->join('tipooficinas','oficinas.tipooficina_id','=','tipooficinas.id')
                        ->addSelect(['ubigeodis' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeodistrital', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeoche' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeocheques', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->addSelect(['ubigeotra' => Ubigeo::select(DB::raw("CONCAT(LPAD(coddepa,2,'0'),LPAD(codprov,2,'0'),LPAD(codist,2,'0'))"))
                            ->whereColumn('ubigeotransferencia', 'ubigeos.id')
                            ->limit(1)
                        ])
                        ->entidad($entidad)
                        ->oficinanumeroplaza($codigo)
                        ->oficinanumero($numero)
                        ->get();
        // dd($existe);

        return view('Entidad.oficinas.consultar_total', [
            // 'oficinas' => Oficina::where('flestado','=',1)->where('numero',$numero)->get(),
            'oficinas' => $existe,
            'tipooficinas' => Tipooficina::get(),
            'entidads' => Entidad::where('id', '!=', '1')->where('flestado', '=', 1)->get(),
            'activar_filtro'=>true
        ]);

    }
    #Importar oficinas
    // public function importar($file)
    // {
    //     //$contents = File::get(public_path('oficinas/cargar/Ofic0820.txt'));
    //     $contents = fopen(public_path('oficinas/cargar/' . $file), "r");
    //     $i = 0;
    //     $Repetidos = array();
    //     $Noexiste = array();
    //     while (!feof($contents)) {
    //         $linea = fgets($contents);
    //         #Separamos la linea con las variables segun el manual
    //         $CodigoRegistro = substr($linea, 0, 2);
    //         $NumeroEntidad = substr($linea, 2, 3);
    //         $NumeroOficina = substr($linea, 5, 3);
    //         $NumeroOficinaPlaza = substr($linea, 8, 4);
    //         $UbigeoCheques = substr($linea, 12, 6);
    //         $UbigeoTransferencia = substr($linea, 18, 6);
    //         $Relleno = substr($linea, 24, 23);
    //         $NombreOficina = substr($linea, 47, 35);
    //         $DomicilioOficina = substr($linea, 82, 80);
    //         $LocalidadOficina = substr($linea, 162, 35);
    //         $CodigoPostalDistrito = substr($linea, 197, 4);
    //         $NombrePlaza = substr($linea, 201, 35);
    //         $TipoOficina = substr($linea, 236, 1);
    //         $UbigeoDistrital = substr($linea, 237, 6);
    //         $PlazaExclusiva = substr($linea, 243, 1);
    //         $Relleno2 = substr($linea, 244, 2);
    //         $PrefTelefonoLarga = substr($linea, 246, 3);
    //         $NumeroTelefono = substr($linea, 249, 8);
    //         $NumeroTelefono2 = substr($linea, 257, 8);
    //         $NumeroTelefonoFax = substr($linea, 265, 8);
    //         $NumeroCentralTelefonica = substr($linea, 273, 8);
    //         $NumeroExtension = substr($linea, 281, 5);
    //         $MotivoActualizacion = substr($linea, 286, 1);
    //         $FechaActualizacion = substr($linea, 287, 8);
    //         $Relleno3 = substr($linea, 295, 5);

    //         #Validamos si existe la oficina en la BD por el codigo
    //         $existe = Oficina::select('oficinas.id', 'motivo')
    //         ->join('entidads', 'oficinas.entidad_id', '=', 'entidads.id')
    //         ->where('entidads.code', $NumeroEntidad)
    //             ->where('oficinas.numero', $NumeroOficina)
    //             ->where('oficinas.numeroplaza', $NumeroOficinaPlaza)
    //             ->get()->toArray();

    //         if (count($existe) > 0) {
    //             // echo "<pre>";
    //             // print_r($existe);
    //             // echo "</pre>";
    //             // echo "Numero de entidad: " . $NumeroEntidad . " / Numero de Oficina: ". $NumeroOficina ;
    //             array_push($Repetidos, $existe);
    //         } else {
    //             #Validamos si existe el codigo de la entidad
    //             $entidad = Entidad::where('code', $NumeroEntidad)->get();
    //             // dd($entidad);
    //             if (count($entidad) > 0) {
    //                 #Obtenemos los id de los ubigeos
    //                 $codDepaDis = substr($UbigeoDistrital, 0, 2);
    //                 $codProvDis = substr($UbigeoDistrital, 2, 2);
    //                 $codDisDis = substr($UbigeoDistrital, 4, 2);
    //                 $idubigeoDis = Ubigeo::where('coddepa', (int)$codDepaDis)->where('codprov', (int)$codProvDis)->where('codist', (int)$codDisDis)->get();
    //                 // echo "<pre>";
    //                 // print_r($idubigeoDis);
    //                 // echo "</pre>";
    //                 $codDepaChe = substr($UbigeoCheques, 0, 2);
    //                 $codProvChe = substr($UbigeoCheques, 2, 2);
    //                 $codDisChe = substr($UbigeoCheques, 4, 2);
    //                 $idubigeoChe = Ubigeo::where('coddepa', (int)$codDepaChe)->where('codprov', (int)$codProvChe)->where('codist', (int)$codDisChe)->get();
    //                 // echo "<pre>";
    //                 // print_r($idubigeoChe);
    //                 // echo "</pre>";
    //                 $codDepaTrans = substr($UbigeoTransferencia, 0, 2);
    //                 $codProvTrans = substr($UbigeoTransferencia, 2, 2);
    //                 $codDisTrans = substr($UbigeoTransferencia, 4, 2);
    //                 $idubigeoTra = Ubigeo::where('coddepa', (int)$codDepaTrans)->where('codprov', (int)$codProvTrans)->where('codist', (int)$codDisTrans)->get();
    //                 // echo "<pre>";
    //                 // print_r($idubigeoTra);
    //                 // echo "</pre>";
    //                 // echo $linea . "<br>";
    //                 // echo $CodigoRegistro. "<br>";
    //                 // echo $NumeroEntidad . "<br>";
    //                 // echo $NumeroOficina . "<br>";
    //                 // echo $NumeroOficinaPlaza."<br>";
    //                 // echo $UbigeoCheques . "<br>";
    //                 // echo $UbigeoTransferencia . "<br>";
    //                 // echo $Relleno . "<br>";
    //                 // echo $NombreOficina . "<br>";
    //                 // echo $DomicilioOficina . "<br>";
    //                 // echo $LocalidadOficina . "<br>";
    //                 // echo $CodigoPostalDistrito . "<br>";
    //                 // echo $NombrePlaza . "<br>";
    //                 // echo $UbigeoDistrital . "<br>";
    //                 // echo $PlazaExclusiva . "<br>";
    //                 // echo $Relleno2 . "<br>";
    //                 // echo $PrefTelefonoLarga . "<br>";
    //                 // echo $NumeroTelefono . "<br>";
    //                 // echo $NumeroTelefono2 . "<br>";
    //                 // echo $NumeroTelefonoFax . "<br>";
    //                 // echo $NumeroCentralTelefonica . "<br>";
    //                 // echo $NumeroExtension . "<br>";
    //                 // echo $MotivoActualizacion . "<br>";
    //                 // echo $FechaActualizacion . "<br>";
    //                 // echo $Relleno3 . "<br>";
    //                 // echo $codDepaDis . " / " . $codProvDis . " / " . $codDisDis."<br>";

    //                 // if(count($idubigeoDis) == 0){
    //                 //     echo "<pre>";
    //                 //     print_r($idubigeoDis);
    //                 //     echo "</pre>";
    //                 // }

    //                 // $i++;
    //                 // if($i == 1000){
    //                 //     exit();
    //                 // }

    //                 #Cambiamos los id del tipo de oficina
    //                 if ($TipoOficina == "0") {
    //                     $tipooficinacce = 1;
    //                 } elseif ($TipoOficina == "1") {
    //                     $tipooficinacce = 2;
    //                 } else {
    //                     $tipooficinacce = 3;
    //                 }
    //                 #Cambiamos los ingresos de Motivos
    //                 if ($MotivoActualizacion == "A") {
    //                     $motivooficina = 1;
    //                 } elseif ($MotivoActualizacion == "B") {
    //                     $motivooficina = 2;
    //                 } elseif ($MotivoActualizacion == "C") {
    //                     $motivooficina = 3;
    //                 } else {
    //                     $motivooficina = 4;
    //                 }

    //                 $oficina = new Oficina;
    //                 $oficina->entidad_id = strip_tags(htmlentities($entidad[0]->id, ENT_QUOTES, "UTF-8"));
    //                 $oficina->tipooficina_id = strip_tags(htmlentities($tipooficinacce, ENT_QUOTES, "UTF-8"));
    //                 $oficina->ubigeodistrital = strip_tags(htmlentities($idubigeoDis[0]->id, ENT_QUOTES, "UTF-8"));
    //                 $oficina->ubigeocheques = strip_tags(htmlentities($idubigeoChe[0]->id, ENT_QUOTES, "UTF-8"));
    //                 $oficina->ubigeotransferencia = strip_tags(htmlentities($idubigeoTra[0]->id, ENT_QUOTES, "UTF-8"));
    //                 $oficina->name = strip_tags(utf8_encode(Str::of($NombreOficina)->trim()), ENT_QUOTES, "UTF-8");
    //                 $oficina->numero = strip_tags(htmlentities(Str::of($NumeroOficina)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->domicilio = strip_tags(utf8_encode(Str::of($DomicilioOficina)->trim()));
    //                 $oficina->localidad = strip_tags(utf8_encode(Str::of($LocalidadOficina)->trim()));
    //                 $oficina->nameplaza = strip_tags(utf8_encode(Str::of($NombrePlaza)->trim()));
    //                 $oficina->numeroplaza = strip_tags(htmlentities(Str::of($NumeroOficinaPlaza)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->preftelefono = strip_tags(htmlentities(Str::of($PrefTelefonoLarga)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->telefono1 = strip_tags(htmlentities(Str::of($NumeroTelefono)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->telefono2 = strip_tags(htmlentities(Str::of($NumeroTelefono2)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->fax = strip_tags(htmlentities(Str::of($NumeroTelefonoFax)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->centraltelefonica = strip_tags(htmlentities(Str::of($NumeroCentralTelefonica)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->motivo = strip_tags(htmlentities($motivooficina, ENT_QUOTES, "UTF-8"));

    //                 $oficina->plazaexclusiva = strip_tags(htmlentities($PlazaExclusiva, ENT_QUOTES, "UTF-8"));
    //                 $oficina->codigopostal = strip_tags(htmlentities(Str::of($CodigoPostalDistrito)->trim(), ENT_QUOTES, "UTF-8"));
    //                 $oficina->numeroextension = strip_tags(htmlentities(Str::of($NumeroExtension)->trim(), ENT_QUOTES, "UTF-8"));

    //                 $oficina->flestado = 1;
    //                 // echo "<pre>";
    //                 // print_r($oficina);
    //                 // echo "</pre>";
    //                 $oficina->save();
    //                 // DB::enableQueryLog();
    //                 // echo "<pre>";
    //                 // print_r(DB::getQueryLog());
    //                 // echo "</pre>";
    //             } else {
    //                 // echo "<pre>";
    //                 // print_r($entidad);
    //                 // echo "</pre>";
    //                 // echo "Numero entidad: ". $NumeroEntidad."<br>";
    //                 array_push($Noexiste, $NumeroEntidad);
    //             }
    //         }
    //         // echo utf8_encode(fgets($contents)) . "<br>";
    //         // $i++;
    //         // if($i == 500){
    //         //     exit();
    //         // }
    //     }

    //     fclose($contents);

    //     exit();
    //     // dd($contents);
    // }

    public function generartxtdiario()
    {

        #Obtenemos todas las listas
        $oficinas = Oficina::select('oficinas.motivo', 'oficinas.fealta', 'oficinas.febaja','oficinas.femodifica', 'oficinas.febajadef',
            'oficinas.ubigeodistrital','oficinas.ubigeocheques', 'oficinas.ubigeotransferencia','entidads.code', 'oficinas.numero','oficinas.numeroplaza','oficinas.name',
            'oficinas.domicilio','oficinas.localidad','oficinas.codigopostal','oficinas.nameplaza','oficinas.tipooficina_id',
            'oficinas.plazaexclusiva', 'oficinas.preftelefono', 'oficinas.telefono1', 'oficinas.telefono2', 'oficinas.fax', 'oficinas.centraltelefonica', 'oficinas.numeroextension')
            ->join('entidads', 'oficinas.entidad_id','=','entidads.id')
            ->orderBy('code','ASC')
            ->orderBy('numero', 'ASC')
            ->orderBy('numeroplaza', 'ASC')
            ->get();
        //dd($oficinas[0]);
        $txt = "";
        foreach ($oficinas as $oficina) {
            if($oficina->motivo == 1){$motivo = "A";$fecha= $oficina->fealta. " 00:00:00";}
            elseif($oficina->motivo == 2){$motivo = "B";$fecha = $oficina->febaja. " 00:00:00";}
            elseif($oficina->motivo == 3){$motivo = "C";$fecha= $oficina->femodifica. " 00:00:00";}
            else{$motivo = "D";$fecha= $oficina->febajadef. " 00:00:00";}
            #Buscamos el ubigeo
            $ubigeodis = Ubigeo::where('id',$oficina->ubigeodistrital)->get()[0];
            $ubigeoche = Ubigeo::where('id', $oficina->ubigeocheques)->get()[0];
            $ubigeotra = Ubigeo::where('id', $oficina->ubigeotransferencia)->get()[0];


            $CodigoRegistro = "01";
            $NumeroEntidad = str_pad($oficina->code, 3);
            $NumeroOficina = str_pad($oficina->numero, 3);
            $NumeroOficinaPlaza = str_pad($oficina->numeroplaza, 4);
            $UbigeoCheques = str_pad(str_pad($ubigeoche->coddepa,2, "0", STR_PAD_LEFT ). str_pad($ubigeoche->codprov, 2, "0", STR_PAD_LEFT). str_pad($ubigeoche->codist, 2, "0", STR_PAD_LEFT), 6);
            $UbigeoTransferencia = str_pad(str_pad($ubigeotra->coddepa,2, "0", STR_PAD_LEFT ). str_pad($ubigeotra->codprov, 2, "0", STR_PAD_LEFT). str_pad($ubigeotra->codist, 2, "0", STR_PAD_LEFT), 6);
            $Relleno = str_pad('0',23, "0", STR_PAD_LEFT);
            $NombreOficina = str_pad(utf8_decode($oficina->name), 35);
            $DomicilioOficina = str_pad(utf8_decode($oficina->domicilio),80);
            $LocalidadOficina = str_pad(utf8_decode($oficina->localidad), 35);
            $CodigoPostalDistrito = str_pad($oficina->codigopostal,  4);
            $NombrePlaza = str_pad(utf8_decode($oficina->nameplaza), 35);
            $TipoOficina = str_pad($oficina->tipooficina_id, 1);
            $UbigeoDistrital = str_pad(str_pad($ubigeodis->coddepa,2, "0", STR_PAD_LEFT ). str_pad($ubigeodis->codprov, 2, "0", STR_PAD_LEFT). str_pad($ubigeodis->codist, 2, "0", STR_PAD_LEFT), 6);
            $PlazaExclusiva = str_pad($oficina->plazaexclusiva, 1);
            $Relleno2 = str_pad('0',2, "0", STR_PAD_LEFT);
            $PrefTelefonoLarga = str_pad($oficina->preftelefono, 3, ' ', STR_PAD_LEFT);
            $NumeroTelefono = str_pad($oficina->telefono1,8, ' ', STR_PAD_LEFT);
            $NumeroTelefono2 = str_pad($oficina->telefono2,8, ' ', STR_PAD_LEFT);
            $NumeroTelefonoFax = str_pad($oficina->fax,8, ' ', STR_PAD_LEFT);
            $NumeroCentralTelefonica = str_pad($oficina->centraltelefonica,8, ' ', STR_PAD_LEFT);
            $NumeroExtension = str_pad($oficina->numeroextension,5, ' ', STR_PAD_LEFT);
            $MotivoActualizacion = str_pad($motivo, 1);
            // $FechaActualizacion = str_pad(date('d-M-Y', strtotime($oficina->fecha)), 8);
            $FechaActualizacion = str_pad(date('Ymd', strtotime($fecha)), 8);
            $Relleno3 = str_pad('0',5, "0", STR_PAD_LEFT);
            $txt .= $CodigoRegistro. $NumeroEntidad. $NumeroOficina. $NumeroOficinaPlaza. $UbigeoCheques. $UbigeoTransferencia. $Relleno. $NombreOficina. $DomicilioOficina . $LocalidadOficina . $CodigoPostalDistrito . $NombrePlaza . $TipoOficina . $UbigeoDistrital . $PlazaExclusiva . $Relleno2. $PrefTelefonoLarga . $NumeroTelefono . $NumeroTelefono2 . $NumeroTelefonoFax . $NumeroCentralTelefonica . $NumeroExtension. $MotivoActualizacion . $FechaActualizacion . $Relleno3. "\r\n";

        }


        //offer the content of txt as a download (logs.txt)
        return response($txt)
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename="oficinas-'.date("yy-m-d").'.txt"',
            ]);
    }

    public function generarexceldiario()
    {

        #Obtenemos todas las listas
        $oficinas = Oficina::select(
            'oficinas.motivo',
            'oficinas.fealta',
            'oficinas.febaja',
            'oficinas.femodifica',
            'oficinas.febajadef',
            'oficinas.ubigeodistrital',
            'oficinas.ubigeocheques',
            'oficinas.ubigeotransferencia',
            'entidads.code',
            'oficinas.numero',
            'oficinas.numeroplaza',
            'oficinas.name',
            'entidads.name as nombreentidad',
            'oficinas.domicilio',
            'oficinas.localidad',
            'oficinas.codigopostal',
            'oficinas.nameplaza',
            'oficinas.tipooficina_id',
            'oficinas.plazaexclusiva',
            'oficinas.preftelefono',
            'oficinas.telefono1',
            'oficinas.telefono2',
            'oficinas.fax',
            'oficinas.centraltelefonica',
            'oficinas.numeroextension'
        )
        ->join('entidads', 'oficinas.entidad_id', '=', 'entidads.id')
        ->orderBy('code', 'ASC')
        ->orderBy('numero', 'ASC')
        ->orderBy('numeroplaza', 'ASC')
        ->get();

        $txt = "";
        $excel = '<table cellspacing="0" cellpadding="0">';
        $excel .= '<tr>
                    <th>Código de reg.</th>
                    <th>Nombre de entidad</th>
                    <th>Número Entidad</th>
                    <th>Número de oficina</th>
                    <th>Número de plaza</th>
                    <th>Ubigeo cheques</th>
                    <th>Ubigeo Transferencia</th>
                    <th>Nombre oficina</th>
                    <th>Domicilio oficina</th>
                    <th>Localidad</th>
                    <th>Código postal</th>
                    <th>Nombre plaza</th>
                    <th>Tipo de oficina</th>
                    <th>Ubigeo distrital</th>
                    <th>Plaza exclusiva</th>
                    <th>Prefijo teléfono</th>
                    <th>Número de teléfono</th>
                    <th>Número de teléfono 2</th>
                    <th>FAX</th>
                    <th>Número central telefónica</th>
                    <th>Número de extensión</th>
                    <th>Motivo</th>
                    <th>Fecha de actua.</th>
                </tr>';
        if(count($oficinas) > 0){
            foreach ($oficinas as $oficina) {
                if ($oficina->motivo == 1) {
                    $motivo = "A";
                    $fecha = $oficina->fealta . " 00:00:00";
                } elseif ($oficina->motivo == 2) {
                    $motivo = "B";
                    $fecha = $oficina->febaja . " 00:00:00";
                } elseif ($oficina->motivo == 3) {
                    $motivo = "C";
                    $fecha = $oficina->femodifica . " 00:00:00";
                } else {
                    $motivo = "D";
                    $fecha = $oficina->febajadef . " 00:00:00";
                }
                #Buscamos el ubigeo
                $ubigeodis = Ubigeo::where('id', $oficina->ubigeodistrital)->get()[0];
                $ubigeoche = Ubigeo::where('id', $oficina->ubigeocheques)->get()[0];
                $ubigeotra = Ubigeo::where('id', $oficina->ubigeotransferencia)->get()[0];
                #Separamos la linea con las variables segun el manual
                $CodigoRegistro = "01";
                $NombreEntidad = str_pad($oficina->nombreentidad, 35);
                $NumeroEntidad = str_pad($oficina->code, 3);
                $NumeroOficina = str_pad($oficina->numero, 3);
                $NumeroOficinaPlaza = str_pad($oficina->numeroplaza, 4);
                $UbigeoCheques = str_pad(str_pad($ubigeoche->coddepa, 2, "0", STR_PAD_LEFT) . str_pad($ubigeoche->codprov, 2, "0", STR_PAD_LEFT) . str_pad($ubigeoche->codist, 2, "0", STR_PAD_LEFT), 6);
                $UbigeoTransferencia = str_pad(str_pad($ubigeotra->coddepa, 2, "0", STR_PAD_LEFT) . str_pad($ubigeotra->codprov, 2, "0", STR_PAD_LEFT) . str_pad($ubigeotra->codist, 2, "0", STR_PAD_LEFT), 6);
                $NombreOficina = str_pad(utf8_decode($oficina->name), 35);
                $DomicilioOficina = str_pad(utf8_decode($oficina->domicilio), 80);
                $LocalidadOficina = str_pad(utf8_decode($oficina->localidad), 35);
                $CodigoPostalDistrito = str_pad($oficina->codigopostal,  4);
                $NombrePlaza = str_pad(utf8_decode($oficina->nameplaza), 35);
                $TipoOficina = str_pad($oficina->tipooficina_id, 1);
                $UbigeoDistrital = str_pad(str_pad($ubigeodis->coddepa, 2, "0", STR_PAD_LEFT) . str_pad($ubigeodis->codprov, 2, "0", STR_PAD_LEFT) . str_pad($ubigeodis->codist, 2, "0", STR_PAD_LEFT), 6);
                $PlazaExclusiva = str_pad($oficina->plazaexclusiva, 1);
                $PrefTelefonoLarga = str_pad($oficina->preftelefono, 3);
                $NumeroTelefono = str_pad($oficina->telefono1, 8);
                $NumeroTelefono2 = str_pad($oficina->telefono2, 8);
                $NumeroTelefonoFax = str_pad($oficina->fax, 8);
                $NumeroCentralTelefonica = str_pad($oficina->centraltelefonica, 8);
                $NumeroExtension = str_pad($oficina->numeroextension, 5);
                $MotivoActualizacion = str_pad($oficina->motivo, 1);
                // $FechaActualizacion = str_pad(date('d-M-Y', strtotime($oficina->fecha)), 8);
                $FechaActualizacion = str_pad(date('Ymd', strtotime($fecha)), 8);
                $excel .= '<tr><td>'.$CodigoRegistro .'</td><td>'.$NombreEntidad.'</td><td>'. $NumeroEntidad . '</td><td>'. $NumeroOficina . '</td><td>'. $NumeroOficinaPlaza . '</td><td>'. $UbigeoCheques . '</td><td>'. $UbigeoTransferencia . '</td><td>' . $NombreOficina . '</td><td>'. $DomicilioOficina . '</td><td>'. $LocalidadOficina . '</td><td>'. $CodigoPostalDistrito . '</td><td>'. $NombrePlaza . '</td><td>'. $TipoOficina . '</td><td>'. $UbigeoDistrital . '</td><td>'. $PlazaExclusiva . '</td><td>' . $PrefTelefonoLarga . '</td><td>'. $NumeroTelefono . '</td><td>'. $NumeroTelefono2 . '</td><td>'. $NumeroTelefonoFax . '</td><td>'. $NumeroCentralTelefonica . '</td><td>'. $NumeroExtension . '</td><td>'. $MotivoActualizacion . '</td><td>'. $FechaActualizacion . '</td></tr>';
            }
        }else{
            $excel .= '<tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
        }
        $excel .= '</table>';

        $excel = utf8_decode($excel);

       //offer the content of txt as a download (logs.txt)
        return response($excel)
            ->withHeaders([
                'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
                'Content-Type' => 'text/xls; charset=UTF-8',
                'Content-Type' => 'application/x-msexcel; charset=utf-8',
                'Expires' => '0',
                'Cache-Control' => 'private',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename="oficinas-' . date("yy-m-d") . '.xls"',
            ]);
    }
}
