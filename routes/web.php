<?php

use App\Http\Controllers\CCE\HomeController;
use App\Http\Controllers\CCE\AjaxController;
use App\Http\Controllers\CCE\UserController;
use App\Http\Controllers\CCE\EntidadController;
use App\Http\Controllers\CCE\DocumentoController;
use App\Http\Controllers\CCE\OficinaController;
use App\Http\Controllers\CCE\TarifaController;
use App\Http\Controllers\CCE\BashController;
use App\Http\Controllers\CCE\TipoDocumentoController;
use App\Http\Controllers\CCE\TipooficinaController;
use App\Http\Controllers\CCE\AgendarController;
use App\Http\Controllers\CCE\EventoController;
use App\Http\Controllers\CCE\BannerController;


use App\Mail\AgendarEntidad;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Role;
use App\Mail\ActualizarUsuario;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Login\LoginController;

    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/validar', [LoginController::class, 'login'])->name('login_post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'superadmin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('entidad', [EntidadController::class, 'index'])->name('admin.entidad.index');
        
        Route::post('ajaxubigeoadmin', [AjaxController::class, 'getubigeo'])->name('getubigeoadmin');
        Route::post('ajaxbancos', [AjaxController::class, 'getbancos'])->name('getbancos');
        Route::post('ajaxbancosxagenda', [AjaxController::class, 'getbancosxagenda'])->name('getbancosxagenda');
        Route::post('ajaxbancosxevento', [AjaxController::class, 'getbancosxevento'])->name('getbancosxevento');

        // Validar correo
        Route::post('ajaxcorreouser', [AjaxController::class, 'getcorreouser'])->name('getcorreouser');
        Route::post('ajaxcorreoalternativouser', [AjaxController::class, 'getcorreoalternativouser'])->name('getcorreoalternativouser');
        Route::post('ajaxcorreouserent', [AjaxController::class, 'getcorreouserent'])->name('getcorreouserent');
        Route::post('ajaxcorreoalternativouserent', [AjaxController::class, 'getcorreoalternativouserent'])->name('getcorreoalternativouserent');

        // Validar usuario
        Route::post('ajaxusuariouser', [AjaxController::class, 'getusuariouser'])->name('getusuariouser');
        Route::post('ajaxusuariouserent', [AjaxController::class, 'getusuariouserent'])->name('getusuariouserent');

        // Comentarios
        Route::post('eliminar_agenda', [AjaxController::class, 'eliminaragenda'])->name('eliminar_agenda');
        Route::post('eliminar_evento', [AjaxController::class, 'eliminarEvento'])->name('eliminar_evento');

        // BASH
        Route::post('generar_txt', [BashController::class, 'generartxtdiario'])->name('generar_txt');

        Route::post('eliminar_tipoOficina', [AjaxController::class, 'eliminarTipoOficina'])->name('eliminar_tipoOficina');
        Route::post('eliminar_usuario', [AjaxController::class, 'eliminarUsuario'])->name('eliminar_usuario');
        Route::post('eliminar_oficina', [AjaxController::class, 'eliminarOficina'])->name('eliminar_oficina');
        Route::post('eliminar_tipo_documento', [AjaxController::class, 'eliminarTipoDocumento'])->name('eliminar_tipo_documento');
        Route::post('eliminar_entidad', [AjaxController::class, 'eliminarEntidad'])->name('eliminar_entidad');
        Route::post('eliminarFlayerEvento', [AjaxController::class, 'eliminarFlayerEvento'])->name('eliminarFlayerEvento');
        Route::post('eliminar_documento', [AjaxController::class, 'eliminarDocumento'])->name('eliminar_documento');
        Route::post('eliminar_detalle_documento', [AjaxController::class, 'eliminarDetalleDocumento'])->name('eliminar_detalle_documento');

        Route::post('eliminar_banner', [AjaxController::class, 'eliminarbanner'])->name('eliminar_banner');
        Route::post('actualizar_posicion_banner', [AjaxController::class, 'actualizar_posicion_banner'])->name('actualizar_posicion_banner');
        Route::post('cargar_rol_tipo', [AjaxController::class, 'cargar_rol_tipo'])->name('cargar_rol_tipo');

        Route::get('importar-oficinas', function () {
            return view('CCE/oficinas/importar');
        });
    });

Route::post('/importar/save', [OficinaController::class, 'saveimportar'])->name('save_importar');

Route::get('/importar', [OficinaController::class, 'importar']);

Route::get('/exportar', [OficinaController::class, 'generartxtdiario'])->name('descargar_txt_oficinas');
Route::get('/exportarexcel', [OficinaController::class, 'generarexceldiario'])->name('descargar_excel_oficinas');

Route::resource('entidad', EntidadController::class)->except('destroy');

Route::resource('user', UserController::class)->except('destroy');

Route::resource('tipoDocumento', TipoDocumentoController::class)->except('destroy');

Route::resource('documento', DocumentoController::class)->except('destroy');

Route::get('documentosave', [DocumentoController::class, 'documentostotales'])->name('cambiosdocumentos');

Route::resource('tipooficina', TipooficinaController::class)->except('destroy');

Route::resource('oficina', OficinaController::class)->except('destroy');

Route::post('oficina/filtrar', [OficinaController::class, 'filtrar'])->name('filtrar_oficina');

Route::post('tarifa-entidad', [TarifaController::class, 'filtrar'])->name('filtar-tarifa');

Route::post('tarifa-tin-especial', [AjaxController::class, 'actualizartin'])->name('administrar_tin');

Route::resource('tarifa', TarifaController::class)->except('destroy');

// ================= TARIFAS =================

// Ver vista de modificación de tarifa (entidad)
Route::get(
    'tarifa/editar/{tarifa}/{tipotransferencia}/{tipo}',
    [TarifaController::class, 'editar_tarifa']
)->name('editar_tarifa_entidad');

// Modificar tarifa (entidad)
Route::put(
    'tarifa/editar/{id}',
    [TarifaController::class, 'modificar_tarifa_entidad']
)->name('modificar_tarifa_entidad');

// Modificar tarifa global
Route::put(
    'modificare',
    [TarifaController::class, 'modificar_tarifa_global']
)->name('modificar_tarifa_global');


// ================= BILATERAL =================

// Vista bilateral
Route::get(
    'bilateral',
    [TarifaController::class, 'mostrarbilateral']
)->name('mostrar_vista_bilateral');

// Filtrar bilateral
Route::post(
    'bilateral',
    [TarifaController::class, 'filtrarbilateral']
)->name('filtar_tarifa_bilateral');

// Vista modificar tarifas
Route::get(
    'modificar',
    [TarifaController::class, 'mostrarvistamodificartarifas']
)->name('mostrar_vista_modificar_tarifas');

// Eliminar tarifa bilateral
Route::get(
    'bilateral/eliminar/{mp}/{op}/{pe}',
    [TarifaController::class, 'eliminar_tarifa']
)->name('eliminar_tarifa');

// Ver tarifa bilateral
Route::get(
    'bilateral/modificar/{mp}/{op}/{pe}',
    [TarifaController::class, 'ver_tarifa']
)->name('ver_tarifa');

// Modificar tarifa bilateral
Route::put(
    'bilateral/modificar/{id}',
    [TarifaController::class, 'modificar_tarifa']
)->name('modificar_tarifa');

// Vista crear tarifa bilateral
Route::get(
    'bilateral/crear',
    [TarifaController::class, 'ver_crear_tarifa']
)->name('ver_crear_tarifa');

// Insertar tarifa bilateral
Route::post(
    'bilateral/crear',
    [TarifaController::class, 'post_crear_tarifa']
)->name('post_crear_tarifa');


// ================= AGENDAR =================

Route::resource(
    'agendar',
    AgendarController::class
)->except('destroy');


// ================= EVENTOS =================

Route::resource(
    'evento',
    EventoController::class
)->except('destroy');


// ================= BITÁCORA =================

Route::get(
    'bitacora',
    [HomeController::class, 'bitacora']
)->name('bitacora');


// ================= BANNERS =================

Route::resource(
    'banner',
    BannerController::class
)->except('destroy');

Route::middleware(['auth', 'admin'])
    ->prefix('entidad')
    ->group(function () {

        Route::get('/', [HomeController::class, 'dashboard'])->name('home_ent');

        Route::post('ajaxubigeo', [AjaxController::class, 'getubigeo'])->name('getubigeo');

        Route::get('importar-oficinas', function () {
            return view('CCE/oficinas/importar');
        });

        // Usuarios globales
        Route::get(
            'global/usuarios',
            [UserController::class, 'listar_global_usuarios']
        )->name('usuario-listar-global');

        Route::post(
            'entidad_eliminar_oficinas',
            [AjaxController::class, 'entidadEliminarOficinas']
        )->name('entidad_eliminar_oficinas');

        // Entidad
        Route::get(
            'entidad_edit',
            [EntidadController::class, 'mostrar']
        )->name('entidad-ver');

        Route::put(
            'entidad_update',
            [EntidadController::class, 'actualizar']
        )->name('entidad-actualizar');

        // Documentos
        Route::get(
            'ver-documentos',
            [DocumentoController::class, 'listar']
        )->name('documento-ver');

        // Usuarios entidad
        Route::get(
            'usuarios',
            [UserController::class, 'lista']
        )->name('usuario-listar');

        Route::post(
            'usuarios',
            [UserController::class, 'store']
        )->name('usuario-guardar');

        Route::get(
            'usuario_edit/{id}/edit',
            [UserController::class, 'editar']
        )->name('usuario-ver');

        Route::put(
            'usuario_actualizar/{usuario}',
            [UserController::class, 'actualizar']
        )->name('usuario-actualiza');

        // Oficinas
        Route::get(
            'ver-oficina',
            [OficinaController::class, 'listar']
        )->name('ver-oficina');

        Route::get(
            'crear-oficina',
            [OficinaController::class, 'create']
        )->name('crear-oficina');

        Route::post(
            'guardar-oficina',
            [OficinaController::class, 'store']
        )->name('guardar-oficina');

        Route::get(
            'oficina-ent/{oficina}/edit',
            [OficinaController::class, 'edit']
        )->name('editar-oficina');

        Route::put(
            'oficina-ent/{oficina}',
            [OficinaController::class, 'update']
        )->name('actualizar-oficina');

        Route::get(
            'oficinas/consultar',
            [OficinaController::class, 'listar_oficinas']
        )->name('listar-oficinas');

        Route::post(
            'oficinas/filtrar',
            [OficinaController::class, 'filtrar_oficinas_entidad']
        )->name('filtrar_oficinas');

        Route::get(
            'exportar',
            [OficinaController::class, 'generartxtdiario']
        )->name('descargar_txt_oficinas_ent');

        Route::get(
            'exportarexcel',
            [OficinaController::class, 'generarexceldiario']
        )->name('descargar_excel_oficinas_ent');

        // Tarifas / Ajax
        Route::post(
            'tarifa-tin-especial-entidad',
            [AjaxController::class, 'actualizartin']
        )->name('administrar_tin_ent');

        Route::post(
            'read-documento',
            [AjaxController::class, 'readdocumento']
        )->name('read_documento');

        Route::post(
            'read-agenda',
            [AjaxController::class, 'readagenda']
        )->name('read_agenda');

        Route::post(
            'read-evento',
            [AjaxController::class, 'readevento']
        )->name('read_evento');

        // Tarifas entidad
        Route::get(
            'listar-tarifas',
            [TarifaController::class, 'listar']
        )->name('listar-tarifa');

        Route::post(
            'tarifas',
            [TarifaController::class, 'buscar']
        )->name('buscar-tarifa');

        Route::get(
            'tarifa/editar/{tarifa}/{tipotransferencia}/{tipo}',
            [TarifaController::class, 'editar_entidad_tarifa']
        )->name('entidad_editar_tarifa_entidad');

        Route::put(
            'tarifa/editar/{id}',
            [TarifaController::class, 'modificar_entidad_tarifa_entidad']
        )->name('entidad_modificar_tarifa_entidad');

        // Bilateral entidad
        Route::get(
            'bilateral',
            [TarifaController::class, 'mostrarbilateralentidad']
        )->name('entidad_mostrar_vista_bilateral');

        Route::post(
            'bilateral',
            [TarifaController::class, 'filtrarbilateralentidad']
        )->name('entidad_filtar_tarifa_bilateral');

        Route::get(
            'bilateral/eliminar/{mp}/{op}/{pe}',
            [TarifaController::class, 'eliminar_tarifaentidad']
        )->name('entidad_eliminar_tarifa');

        Route::get(
            'bilateral/modificar/{mp}/{op}/{pe}',
            [TarifaController::class, 'ver_tarifaentidad']
        )->name('entidad_ver_tarifa');

        Route::put(
            'bilateral/modificar/{id}',
            [TarifaController::class, 'modificar_tarifaentidad']
        )->name('entidad_modificar_tarifa');

        Route::get(
            'bilateral/crear',
            [TarifaController::class, 'ver_crear_tarifaentidad']
        )->name('entidad_ver_crear_tarifa');

        Route::post(
            'bilateral/crear',
            [TarifaController::class, 'post_crear_tarifaentidad']
        )->name('entidad_post_crear_tarifa');

        // Validaciones
        Route::post(
            'ajaxcorreouserentidad',
            [AjaxController::class, 'getcorreouser']
        )->name('getcorreouserentidad');

        Route::post(
            'ajaxcorreouserentidadent',
            [AjaxController::class, 'getcorreouserent']
        )->name('getcorreouserentidadent');

        Route::post(
            'ajaxusuariouserentidad',
            [AjaxController::class, 'getusuariouser']
        )->name('getusuariouserentidad');
    });


// Route::group(['prefix' => 'entidad', 'namespace' => 'Entidad', 'middleware' => ['auth', 'admin']], function () {

   

// });

Route::get('homeprueba', function () {
    $txt = "Logs \n";

    return response($txt)->withHeaders([
        'Content-Type' => 'text/plain',
        'Cache-Control' => 'no-store, no-cache',
        'Content-Disposition' => 'attachment; filename="logs.txt"',
    ]);
});


Route::get('pruebamail', function () {

    $from = DateTime::createFromFormat('Y-m-d H:i', '2018-02-01 09:00');
    $to   = DateTime::createFromFormat('Y-m-d H:i', '2018-02-01 18:00');

    $link = Link::create('Sebastian\'s birthday', $from, $to)
        ->description('Cookies & cocktails!')
        ->address('Kruikstraat 22, 2018 Antwerpen');

    $data = [
        'title'       => 'Hola',
        'nombre'      => 'Lenin',
        'url'         => config('app.url'),
        'documentos'  => 'Agendas',
        'google'      => $link->google(),
        'yahoo'       => $link->yahoo(),
        'webOutlook'  => $link->webOutlook(),
        'ics'         => $link->ics(),
    ];

    Mail::to('lenin_92_mij@hotmail.es')->send(new AgendarEntidad($data));
});


Route::get('slug', function () {
    return \Illuminate\Support\Str::slug("Hola cómo estás Á - ( )");
});


Route::get('testcorreo', function () {
    $data = [
        'usuario' => 'Prueba',
        'nombre'  => 'Nombre',
        'url'     => config('app.url'),
        'mensaje' => 'Se cambió',
    ];

    Mail::to('lenin_92_mij@hotmail.es')->send(new ActualizarUsuario($data));
});


// ================= CRON =================

Route::get(
    'cron/diario',
    [BashController::class, 'generartxtdiario']
)->name('generartxtdiario');

Route::get(
    'cron/correo',
    [BashController::class, 'generarcorreo']
)->name('generarcorreo');
