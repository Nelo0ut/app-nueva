<?php

namespace App\Http\Controllers\CCE;

use App\Http\Controllers\Controller;
use App\Mail\CorreoMail;
use Illuminate\Http\Request;
use App\Models\Oficina;
use App\Models\Ubigeo;
use App\Models\User;

use App\Mail\TxtDiario;
use Illuminate\Support\Facades\Mail;

class BashController extends Controller
{
    public function generartxtdiario()
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
            $ubigeodis = Ubigeo::where('id',$oficina->ubigeodistrital)->get()[0];
            $ubigeoche = Ubigeo::where('id', $oficina->ubigeocheques)->get()[0];
            $ubigeotra = Ubigeo::where('id', $oficina->ubigeotransferencia)->get()[0];
            #Separamos la linea con las variables segun el manual
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
            $NumeroTelefono = str_pad($oficina->telefono1, 8, ' ', STR_PAD_LEFT);
            $NumeroTelefono2 = str_pad($oficina->telefono2, 8, ' ', STR_PAD_LEFT);
            $NumeroTelefonoFax = str_pad($oficina->fax, 8, ' ', STR_PAD_LEFT);
            $NumeroCentralTelefonica = str_pad($oficina->centraltelefonica, 8, ' ', STR_PAD_LEFT);
            $NumeroExtension = str_pad($oficina->numeroextension, 5, ' ', STR_PAD_LEFT);
            $MotivoActualizacion = str_pad($motivo, 1);
            // $FechaActualizacion = str_pad(date('d-M-Y', strtotime($oficina->fecha)), 8);
            $FechaActualizacion = str_pad(date('Ymd', strtotime($fecha)), 8);
            $Relleno3 = str_pad('0',5, "0", STR_PAD_LEFT);
            $txt .= $CodigoRegistro. $NumeroEntidad. $NumeroOficina. $NumeroOficinaPlaza. $UbigeoCheques. $UbigeoTransferencia. $Relleno. $NombreOficina. $DomicilioOficina . $LocalidadOficina . $CodigoPostalDistrito . $NombrePlaza . $TipoOficina . $UbigeoDistrital . $PlazaExclusiva . $Relleno2. $PrefTelefonoLarga . $NumeroTelefono . $NumeroTelefono2 . $NumeroTelefonoFax . $NumeroCentralTelefonica . $NumeroExtension. $MotivoActualizacion . $FechaActualizacion . $Relleno3. "\r\n";

        }

        $nombrearchivo = "txt-diario-de-oficinas-".date('yy-m-d')."_".time().".txt";

        $handle = fopen("/home/ccepane1/extranet.transferenciasinterbancarias.pe/public/cron/". $nombrearchivo, "x+");
        fwrite($handle, $txt);
        fclose($handle);
        $mesesN = array(
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        );
        $textodia = date('d') . " de " . $mesesN[(int)date('m') - 1] . " del " . date('Y');
        $data = [
            'path' => "/home/ccepane1/extranet.transferenciasinterbancarias.pe/public/cron/" . $nombrearchivo,
            'url' => config('app.url'),
            'fecha' => $textodia
        ];

        Mail::to('ofi@cce.com.pe')->cc('eficiencia@cce.com.pe')->send(new TxtDiario($data));
        Mail::to('lenin@althus.pe')->send(new TxtDiario($data));

        exit();
    }

    public function generarcorreo()
    {
        $usuarios = User::where('id', '>', 17)->get();
        if(count($usuarios) > 0){
            foreach ($usuarios as $usuario) {
                $data = [
                    'contrasena' => $usuario->password,
                    'nombre' => $usuario->name,
                    'url' => config('app.url'),
                ];
                if(!empty($usuario->email) && !empty($usuario->email_2)){
                    Mail::to($usuario->email)->cc($usuario->email_2)->send(new CorreoMail($data));
                    //Mail::to('mrios@cce.com.pe')->send(new CorreoMail($data));
                }else{
                    Mail::to($usuario->email)->send(new CorreoMail($data));
                }

                $usuario->password = bcrypt($usuario->password);
                $usuario->save();
                //Mail::to('ofi@cce.com.pe')->cc('eficiencia@cce.com.pe')->send(new CorreoMail($data));
            }
        }

        exit();
    }
}
