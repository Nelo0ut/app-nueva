@extends('layouts.app')
@section('title')
Tarifas
@endsection
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Modificar Tarifas Bilaterales</h1>
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
            <div class="row mb-3">
                <div class="col-12">
                    @include('includes.form-error')
                    @include('includes.mensajes')
                </div>
            </div>           
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
                        <strong>Entidad 2:</strong> {{$tarifas[0]->alias2}}
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
                <table id="example3" class="table nowrap" style="text-align: center">
                    <thead class="rounded-top" style="background-color: #3c8dbc; color:white;">
                    <tr>
                        <th style="background-color: #3c8dbc;">Plaza</th>
                        <th style="background-color: #3c8dbc;">PEN %</th>
                        <th style="background-color: #3c8dbc;">PEN min</th>
                        <th style="background-color: #3c8dbc;">PEN max</th>
                        <th style="background-color: #3c8dbc;">USD %</th>
                        <th style="background-color: #3c8dbc;">USD min</th>
                        <th style="background-color: #3c8dbc;">USD max</th>
                        <th style="background-color: #3c8dbc;">Acción</th>        
                    </tr>
                    </thead>
                    <tbody>
                        @if ($tarifas)
                            @php $i = 1; @endphp
                            @foreach ($tarifas as $tarifa)
                                <tr>
                                    <form action="{{route('entidad_modificar_tarifa', ['id' => $tarifa->id])}}" method="POST" role="form" id="modificar{{$i}}" class="needs-validation" novalidate>
                                        @csrf
                                        @method('PUT')
                                        <th scope="row">
                                            @if ($i == 1) MP @elseif($i == 2) OP @elseif($i == 3) PE @endif                                        
                                        </th>
                                        @if ($i == 1)
                                            <td colspan="3">
                                                <input type="hidden" min="0" name="sol_porcentaje" value="0">
                                                <input type="hidden" min="0" name="sol_montofijomax" value="0">
                                                <input type="number" min="0" class="form-control" name="sol_montofijomin" id="sol_montofijomin_{{$i}}"
                                                    value="{{$tarifa->sol_montofijomin}}">
                                            </td>
                                            <td colspan="3">
                                                <input type="hidden" min="0" name="dol_porcentaje" value="0">
                                                <input type="hidden" min="0" name="dol_montofijomax" value="0">
                                                <input type="number" min="0" class="form-control" name="dol_montofijomin" id="dol_montofijomin_{{$i}}"
                                                    value="{{$tarifa->dol_montofijomin}}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-morado" onclick="ejecutar({{$i}})">Modificar</button>
                                            </td>
                                        @else
                                            <td>
                                                <input type="number" min="0" class="form-control" name="sol_porcentaje" id="sol_porcentaje_{{$i}}"
                                                    value="{{$tarifa->sol_porcentaje}}">
                                            </td>
                                            <td>
                                                <input type="number" min="0" class="form-control" name="sol_montofijomin" id="sol_montofijomin_{{$i}}"
                                                    value="{{$tarifa->sol_montofijomin}}">
                                            </td>
                                            <td>
                                                <input type="number" min="0" class="form-control" name="sol_montofijomax" id="sol_montofijomax_{{$i}}"
                                                    value="{{$tarifa->sol_montofijomax}}">
                                            </td>
                                            <td>
                                                <input type="number" min="0" class="form-control" name="dol_porcentaje" id="dol_porcentaje_{{$i}}"
                                                    value="{{$tarifa->dol_porcentaje}}">
                                            </td>
                                            <td>
                                                <input type="number" min="0" class="form-control" name="dol_montofijomin" id="dol_montofijomin_{{$i}}"
                                                    value="{{$tarifa->dol_montofijomin}}">
                                            </td>
                                            <td>
                                                <input type="number" min="0" class="form-control" name="dol_montofijomax" id="dol_montofijomax_{{$i}}"
                                                    value="{{$tarifa->dol_montofijomax}}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-morado" onclick="ejecutar({{$i}})">Modificar</button>
                                            </td>
                                        @endif
                                        
                                    </form>
                                </tr>
                                @php $i++; @endphp      
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>            
    </div>
</section>    

<script type="text/javascript">

    $(function () {

        $('#example3').DataTable({
            lengthChange: true,
            searching: true,
            info: true,
            autoWidth: true,
            responsive: true,
            "scrollX": true,
            order: [[ 1, "asc" ]],
            dom: '<"html5buttons"B>lTfgitp',
        });
  
        $(window).on('resize', function() {
            $('#example3').css('width', '100%');
            table.draw(true);
        });

    });    

    function ejecutar(id) {
        let formulario = `#modificar${id}`;
        $(formulario).submit();

    }        

</script>
@endsection