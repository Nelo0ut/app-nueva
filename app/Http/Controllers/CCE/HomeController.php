<?php

namespace App\Http\Controllers\CCE;

use App\Models\Agendar;
use App\Models\Bitlogin;
use App\Models\Eventos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //dd(auth()->user());
        //dd(session()->all());
        $agendados = Agendar::where('fefin', '>=', date('Y-m-d H:i:s'))->get();
        $a = 0;
        $jsonAgenda = "[";
        if (count($agendados) > 0) {
            foreach ($agendados as $agendar) {
                $jsonAgenda .= "{title: 'Agenda: " . $agendar->name . "',start: new Date(" . $agendar->year_ini . ", " . $agendar->month_ini . "," . $agendar->day_ini . "," . $agendar->hour_ini . "," . $agendar->min_ini . "), end: new Date(" . $agendar->year_fin . ", " . $agendar->month_fin . "," . $agendar->day_fin . "," . $agendar->hour_fin . "," . $agendar->min_fin . "), backgroundColor: '#2b0c81',borderColor: '#2b0c81'},";
            }
            //$jsonAgenda = substr($jsonAgenda, 0, -1);
            $a++;
        }

        $eventos = Eventos::get();
        if (count($eventos) > 0) {
            foreach ($eventos as $evento) {
                $jsonAgenda .= "{title: 'Evento: " . $evento->titulo . "',start: new Date(" . $evento->year_ini . ", " . $evento->month_ini . "," . $evento->day_ini . "," . $evento->hour_ini . "," . $evento->min_ini . "), end: new Date(" . $evento->year_ini . ", " . $evento->month_ini . "," . $evento->day_ini . ",23,59), backgroundColor: '#f2a413',borderColor: '#f2a413'},";
            }
            //$jsonAgenda = substr($jsonAgenda, 0, -1);
            $a++;
        }
        if ($a > 0) {
            $jsonAgenda = substr($jsonAgenda, 0, -1);
        }

        $jsonAgenda .= "]"; 
        //dd($jsonAgenda);
        return view('CCE.home', [
                    'agendados' => $jsonAgenda
                ]);
    }

    public function bitacora(){
        //$bitacoras = Bitlogin::whereNotIn('usuario_id',[1,2]);
        $bitacoras = DB::select('select entidads.alias, roles.tipo, users.name, users.email, users.usuario, bitlogin.created_at from users inner join bitlogin on bitlogin.usuario_id = users.id inner join roles on roles.id = users.role_id inner join entidads on entidads.id = users.entidad_id where usuario_id not in (?,?)', [1,2]);
        //dd($bitacoras);
        return view('CCE.bitlogin', [
            'bitacoras' => $bitacoras
        ]);
    }

    public function dashboard()
    {
        $agendados = Agendar::where('fefin', '>=', date('Y-m-d H:i:s'))->get();
        $a = 0;
        $jsonAgenda = "[";
        if (count($agendados) > 0) {
            foreach ($agendados as $agendar) {
                $jsonAgenda .= "{title: 'Agenda: " . $agendar->name . "',start: new Date(" . $agendar->year_ini . ", " . $agendar->month_ini . "," . $agendar->day_ini . "," . $agendar->hour_ini . "," . $agendar->min_ini . "), end: new Date(" . $agendar->year_fin . ", " . $agendar->month_fin . "," . $agendar->day_fin . "," . $agendar->hour_fin . "," . $agendar->min_fin . "), backgroundColor: '#2b0c81',borderColor: '#2b0c81'},";
            }
            //$jsonAgenda = substr($jsonAgenda, 0, -1);
            $a ++;
        }

        $eventos = Eventos::get();
        if (count($eventos) > 0) {
            foreach ($eventos as $evento) {
                $jsonAgenda .= "{title: 'Evento: " . $evento->titulo . "',start: new Date(" . $evento->year_ini . ", " . $evento->month_ini . "," . $evento->day_ini . "," . $evento->hour_ini . "," . $evento->min_ini . "), end: new Date(" . $evento->year_ini . ", " . $evento->month_ini . "," . $evento->day_ini . ",23,59), backgroundColor: '#f2a413',borderColor: '#f2a413'},";
            }
            //$jsonAgenda = substr($jsonAgenda, 0, -1);
            $a++;
        }
        if($a > 0){
            $jsonAgenda = substr($jsonAgenda, 0, -1);
        }
        
        $jsonAgenda .= "]"; 


        $documentos = DB::table('documentos')
            ->join('documento_entidad', 'documentos.id', '=', 'documento_entidad.documento_id')
            ->join('detalledocumentos', 'documentos.id', '=', 'detalledocumentos.documento_id')
            ->join('tipo_documentos', 'documentos.tipodocumento_id', '=', 'tipo_documentos.id')
            ->select('documentos.id', 'documentos.name', 'documentos.enviar_notification', 'documentos.feinsert', 'documentos.created_at', 'tipo_documentos.tipodoc', 'detalledocumentos.name AS nombre_doc', 'detalledocumentos.documento', 'detalledocumentos.id')
            ->where('documento_entidad.entidad_id', session()->get('identidad'))
            ->where('documentos.flestado', 1)
            ->where('detalledocumentos.flestado', 1)
            ->where('detalledocumentos.isread', 0)
            ->get();

        $eventos = DB::table('eventos')
            ->join('evento_entidad', 'eventos.id', '=', 'evento_entidad.eventos_id')
            ->select('entidad_id', 'titulo', 'fecha', 'flayer', 'eventos.id')
            ->where('evento_entidad.entidad_id', session()->get('identidad'))
            ->where('eventos.flestado', 1)
            ->where('eventos.isread', 0)
            ->get();
        $agendar = DB::table('agendar')
            ->join('agendar_entidad', 'agendar.id', '=', 'agendar_entidad.agendar_id')
            ->select('entidad_id', 'name', 'description', 'feinicio', 'fefin', 'agendar.id')
            ->where('agendar_entidad.entidad_id', session()->get('identidad'))
            ->where('agendar.flestado', 1)
            ->where('agendar.isread', 0)
            ->get();

        $banner = DB::table('banners')->where('flestado', 1)->orderBy('posicion', 'ASC')->get();
        //dd(session()->get('role_id'));
        return view('Entidad.home', [
            'documentos' => $documentos,
            'eventos' => $eventos,
            'agendas' => $agendar,
            'agendados' => $jsonAgenda,
            'banners' => $banner
        ]);
    }
}
