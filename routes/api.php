<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TarifaController;

Route::get('getdata', [TarifaController::class, 'getdata']);

Route::post('gettarifa', [TarifaController::class, 'gettarifa'])
    ->middleware('auth.basic.once');

Route::post('gettarifacredito', [TarifaController::class, 'gettarifacredito'])
    ->middleware('auth.basic.once');

Route::post('gettarifapagoproveedor', [TarifaController::class, 'gettarifapagoproveedor'])
    ->middleware('auth.basic.once');

Route::post('gettarifapagohaberes', [TarifaController::class, 'gettarifapagohaberes'])
    ->middleware('auth.basic.once');

Route::post('gettarifapagocts', [TarifaController::class, 'gettarifapagocts'])
    ->middleware('auth.basic.once');
