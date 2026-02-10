@extends('layouts.app')
@section('title')
Tarifas
@endsection
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Modificar Tarifas</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="row mb-2">
        <div class="col-12">
            <div class="card card-body" style="background-color: #f4eebd">
                <p>Aquí puedes modificar las tarifas tanto en dólares como en soles entre las dos entidades indicadas, modifica los campos que desees y ejecuta tu modificación haciendo clic en "Modificar"</p>
            </div>
            @include('includes.form-error')
            @include('includes.mensajes')            
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card card-body" style="overflow-x: scroll">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h5>Datos de la entidad</h5>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Entidad 1:</strong> {{$tarifas[0]->alias}}
                    </div>
                    <div class="col-md-3 mb-3">
                        @if ($tarifas[0]->tipotarifa==1)
                            <strong>Tarifa:</strong> Diferida
                        @else
                            <strong>Tarifa:</strong> Inmediata
                        @endif
                    </div>
                    <div class="col-md-3 mb-3">
                        @if($tarifas[0]->tipotarifa > 0)
                            @if ($tarifas[0]->tipotransferencia==1)
                            <strong>Tipo de transferencia:</strong> Ordinaria
                            @elseif($tarifas[0]->tipotransferencia==2)
                            <strong>Tipo de transferencia:</strong> Pago de tarjeta de crédito
                            @elseif($tarifas[0]->tipotransferencia==3)
                            <strong>Tipo de transferencia:</strong> Pago de proveedores
                            @elseif($tarifas[0]->tipotransferencia==4)
                            <strong>Tipo de transferencia:</strong> Pago de haberes
                            @else
                            <strong>Tipo de transferencia:</strong> Pago de CTS
                            @endif
                        @else
                            <strong>Tipo de transferencia:</strong> TIN Especial
                        @endif
                    </div>
                </div>
                <hr>
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="3">Plaza</th>
                            <th rowspan="3">Medio</th>
                            <th colspan="4">Soles</th>
                            <th colspan="4">Dolares</th>
                            <th rowspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="1">Tarifa de entidad financiera</th>
                            <th colspan="3">Tarifa interbancaria</th>
                            <th colspan="1">Tarifa de entidad financiera</th>
                            <th colspan="3">Tarifa interbancaria</th>
                        </tr>
                        <tr>
                            <th>Monto fijo</th>
                            <th>Porcentaje</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Monto fijo</th>
                            <th>Porcentaje</th>
                            <th>Min</th>
                            <th>Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($tarifas); $i++)
                            <tr>
                                <form action="{{route('entidad_modificar_tarifa_entidad', ['id' => $tarifas[$i]->id])}}" method="POST" role="form" id="modificar{{$i}}"
                                    class="needs-validation" novalidate>
                                    @csrf
                                    @method('PUT')
                                    @if ($tarifas[$i]->tipoplaza==1)
                                        <td>MP</td>
                                    @elseif($tarifas[$i]->tipoplaza==2)
                                        <td>OP</td>
                                    @else  
                                        <td>PE</td>
                                    @endif
                                    <td>{{ $tarifas[$i]->nomediotarifa}}</td>
                                    <td><input type="number" min="0" class="form-control" name="sol_int_montofijo" id="sol_int_montofijo_{{$i}}"
                                        value="{{ $tarifas[$i]->sol_int_montofijo}}">
                                        <input type="hidden" min="0" name="sol_int_porcentaje" value="0">
                                        <input type="hidden" min="0" name="sol_int_montofijomax" value="0">
                                        <input type="hidden" min="0" name="sol_int_montofijomin" value="0">
                                    </td>
                                    @if ($tarifas[$i]->tipoplaza==1)
                                        <input type="hidden" min="0" name="sol_cci_porcentaje" value="0">
                                        <input type="hidden" min="0" name="sol_cci_montofijomax" value="0">
                                        <input type="hidden" min="0" name="sol_cci_montofijomin" value="0">
                                        <td colspan="3" align="center" class="estilos_modificar_label_porcentaje">
                                            {{-- <input type="number" min="0" class="form-control" name="sol_cci_montofijo" id="sol_cci_montofijo_{{$i}}"
                                            value="{{ $tarifas[$i]->sol_cci_montofijo}}"> --}}
                                            <label for="" name="sol_cci_montofijo" id="sol_cci_montofijo_{{$i}}" > {{ $tarifas[$i]->sol_cci_montofijo}} </label>
                                        </td>
                                    @else
                                        
                                        <td class="estilos_modificar_label">
                                            {{-- <input type="number" min="0" class="form-control" name="sol_cci_porcentaje" id="sol_cci_porcentaje_{{$i}}" value="{{ $tarifas[$i]->sol_cci_porcentaje}}"> --}}
                                            <label for="" name="sol_cci_porcentaje"  id="sol_cci_porcentaje_{{$i}}" value="{{ $tarifas[$i]->sol_cci_porcentaje}}"> {{ $tarifas[$i]->sol_cci_porcentaje}}</label>
                                            <input type="hidden" min="0" name="sol_cci_montofijo" value="0">
                                        </td>
                                        <td class="estilos_modificar_label">
                                            {{-- <input type="number" min="0" class="form-control" name="sol_cci_montofijomin" id="sol_cci_montofijomin_{{$i}}" value="{{ $tarifas[$i]->sol_cci_montofijomin}}"> --}}
                                            <label for="" name="sol_cci_montofijomin" id="sol_cci_montofijomin_{{$i}}" value="{{ $tarifas[$i]->sol_cci_montofijomin}}" >{{ $tarifas[$i]->sol_cci_montofijomin}}</label></td>
                                        <td class="estilos_modificar_label">
                                            {{-- <input type="number" min="0" class="form-control" name="sol_cci_montofijomax" id="sol_cci_montofijomax_{{$i}}" value="{{ $tarifas[$i]->sol_cci_montofijomax}}"> --}}
                                            <label for="" name="sol_cci_montofijomax" id="sol_cci_montofijomax_{{$i}}" value="{{ $tarifas[$i]->sol_cci_montofijomax}}" > {{ $tarifas[$i]->sol_cci_montofijomax}}</label>
                                        </td>
                                    @endif
                                        <td>
                                            <input type="number" min="0" class="form-control" name="dol_int_montofijo" id="dol_int_montofijo_{{$i}}" value="{{ $tarifas[$i]->dol_int_montofijo}}">
                                            {{-- <label for="" name="dol_int_montofijo" id="dol_int_montofijo_{{$i}}" > {{ $tarifas[$i]->dol_int_montofijo}} </label>                                             --}}
                                            <input type="hidden" min="0" name="dol_int_porcentaje" value="0">
                                            <input type="hidden" min="0" name="dol_int_montofijomax" value="0">
                                            <input type="hidden" min="0" name="dol_int_montofijomin" value="0">
                                        </td>
                                        
                                    @if ($tarifas[$i]->tipoplaza==1)
                                        <td colspan="3" align="center" class="estilos_modificar_label_porcentaje">
                                            {{-- <input type="number" min="0" class="form-control" name="dol_cci_montofijo" id="dol_cci_montofijo_{{$i}}" value="{{ $tarifas[$i]->dol_cci_montofijo}}"> --}}
                                            <label for="" name="dol_cci_montofijo" id="dol_cci_montofijo_{{$i}}" value="{{ $tarifas[$i]->dol_cci_montofijo}}" > {{ $tarifas[$i]->dol_cci_montofijo}} </label>
                                            <input type="hidden" min="0" name="dol_cci_porcentaje" value="0">
                                            <input type="hidden" min="0" name="dol_cci_montofijomax" value="0">
                                            <input type="hidden" min="0" name="dol_cci_montofijomin" value="0">
                                        </td>
                                    @else
                                        <td class="estilos_modificar_label">
                                            {{-- <input type="number" min="0" class="form-control" name="dol_cci_porcentaje" id="dol_cci_porcentaje_{{$i}}" value="{{ $tarifas[$i]->dol_cci_porcentaje}}"> --}}
                                            <label for=""  name="dol_cci_porcentaje" id="dol_cci_porcentaje_{{$i}}" value="{{ $tarifas[$i]->dol_cci_porcentaje}}"  >{{ $tarifas[$i]->dol_cci_porcentaje}}</label>
                                            <input type="hidden" min="0" name="dol_cci_montofijo" value="0">
                                        </td>
                                        <td class="estilos_modificar_label">
                                            {{-- <input type="number" min="0" class="form-control" name="dol_cci_montofijomin" id="dol_cci_montofijomin_{{$i}}" value="{{ $tarifas[$i]->dol_cci_montofijomin}}"> --}}
                                            <label for="" name="dol_cci_montofijomin" id="dol_cci_montofijomin_{{$i}}" value="{{ $tarifas[$i]->dol_cci_montofijomin}}" > {{ $tarifas[$i]->dol_cci_montofijomin}} </label>
                                        </td>
                                        <td class="estilos_modificar_label">
                                            {{-- <input type="number" min="0" class="form-control" name="dol_cci_montofijomax" id="dol_cci_montofijomax_{{$i}}" value="{{ $tarifas[$i]->dol_cci_montofijomax}}"> --}}
                                            <label for="" name="dol_cci_montofijomax" id="dol_cci_montofijomax_{{$i}}" value="{{ $tarifas[$i]->dol_cci_montofijomax}}" >{{ $tarifas[$i]->dol_cci_montofijomax}}</label>
                                        </td>
                                    @endif
                                    <td>
                                        <button type="button" class="btn btn-morado" onclick="ejecutar({{$i}})">Modificar</button>
                                    </td>
                                </form>
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12 mb-3 my-3">
                        <a href="{{route('listar-tarifa')}}" class="btn btn-morado">Regresar</a>
                    </div>
                </div>
            </div>
        </div>            
    </div>
</section>    

<script type="text/javascript">

    $(function () {

    });    

    function ejecutar(id) {
        let formulario = `#modificar${id}`;
        $(formulario).submit();

    }        

</script>
@endsection