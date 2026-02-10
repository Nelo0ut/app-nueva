@extends('layouts.app')
@section('title')
Tarifas
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tarifa</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtrar tarifas</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @include('includes.form-error')
                    @include('includes.mensajes')
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('buscar-tarifa') }}" method="POST" role="form" id="quickForm" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <input type="hidden" value="{{session()->get('identidad')}}" name="entidad_id">
                                        <label for="tipotarifas">Diferida / Inmediata *</label>
                                        <select name="tipotarifas" id="tipotarifas" class="form-control" required>
                                            <option value="">:: Seleccione ::</option>
                                            <option value="1" {{ old('tipotarifas') == 1 ? 'selected' : '' }}>Tarifa Diferida</option>
                                            <option value="2" {{ old('tipotarifas') == 2 ? 'selected' : '' }}>Tarifa Inmediata</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tipotransferencia">Tipo de transferencia *</label>
                                        <select name="tipotransferencia" id="tipotransferencia" class="form-control" required>
                                            <option value="">:: Seleccione ::</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="moneda">Moneda</label>
                                        <select name="moneda" id="moneda" class="form-control">
                                            <option value="">:: Seleccione ::</option>
                                            <option value="1">Soles</option>
                                            <option value="2">Dolares</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tipo">Tipo</label>
                                        <select name="tipo" id="tipo" class="form-control">
                                            <option value="">:: Seleccione ::</option>
                                            <option value="1">Tarifa de entidad financiera</option>
                                            <option value="2">Tarifa interbancaria</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for=""></label>
                                        <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if($tarifas)
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5>Datos de la entidad</h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Entidad:</strong> {{$tarifas[0]->alias}}
                        </div>
                        <div class="col-md-3 mb-3">
                            @if ($tarifas[0]->tipotarifa==1)
                                <strong>Tarifa:</strong> Diferida
                            @else
                                <strong>Tarifa:</strong> Inmediata
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            @if($oldre['tipotransferencia'] > 0)
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
                        @if ($oldre['tipo'] == 1)
                        <div class="col-md-3 mb-3">
                            <strong>Tipo:</strong> Tarifa de entidad financiera
                        </div>
                        @elseif($oldre['tipo'] == 2)
                        <div class="col-md-3 mb-3">
                            <strong>Tipo:</strong> Tarifa interbancaria
                        </div>
                        @endif
                        @if ($oldre['moneda'] == 1)
                        <div class="col-md-3 mb-3">
                            <strong>Moneda:</strong> Soles
                        </div>
                        @elseif($oldre['moneda'] == 2)
                        <div class="col-md-3 mb-3">
                            <strong>Moneda:</strong> Dolares
                        </div>
                        @endif
                    </div>
                        
                    <div class="row mt-3 mb-3">
                        <div class="col-md-12 mb-3">
                            <h5>TIN Especial por medios</h5>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <th>Medio</th>
                                    <th>TIN Especial</th>
                                    <th>Soles</th>
                                    <th>Dólares</th>
                                </thead>
                                <tbody>
                                    @if (!empty($medios))
                                    @foreach ($medios as $medio)
                                    <tr>
                                        <td>{{$medio->nomediotarifa}}</td>
                                        @if ($medio->tinespecial == 1)
                                        <td>Si</td>
                                        @elseif($medio->tinespecial == 3)
                                        <td>Receptor</td>
                                        @else
                                        <td>No</td>
                                        @endif
                                        <td>{{$medio->tintope}}</td>
                                        <td>{{$medio->tintopedol}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                    
                        </div>
                    </div>
                    <hr>
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                
                                @if ($oldre['moneda'] == null)
                                    @if ($oldre['tipo'] == null)
                                        <th rowspan="3">Plaza</th>
                                        <th rowspan="3">Medio</th>
                                        <th colspan="4">Soles</th>
                                        <th colspan="4">Dolares</th>
                                    @elseif ($oldre['tipo'] == 1)
                                        <th>Plaza</th>
                                        <th>Medio</th>
                                        <th>Soles (Fijo)</th>
                                        <th>Dolares (Fijo)</th>
                                    @else
                                        <th rowspan="2">Plaza</th>
                                        <th rowspan="2">Medio</th>
                                        <th colspan="3">Soles</th>
                                        <th colspan="3">Dolares</th>
                                    @endif
                                @else
                                
                                    @if ($oldre['tipo'] == null)
                                        <th rowspan="2">Plaza</th>
                                        <th rowspan="2">Medio</th>
                                        <th>Tarifa de entidad financiera</th>
                                        <th colspan="3">Tarifa interbancaria</th>
                                    @elseif($oldre['tipo'] == 1)
                                        <th>Plaza</th>
                                        <th>Medio</th>
                                        <th>Fijo</th>
                                    @else
                                        <th>Plaza</th>
                                        <th>Medio</th>
                                        <th>Porcentaje</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                    @endif
                                @endif
                            </tr>
                            
                            @if ($oldre['moneda'] == null)
                                @if ($oldre['tipo'] == null)
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
                                @elseif ($oldre['tipo'] == 2)
                                    <tr>
                                        <th>Porcentaje</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                        <th>Porcentaje</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                    </tr>
                                @endif
                            @else
                                @if ($oldre['tipo'] == null)
                                    <tr>
                                        <th>Monto fijo</th>
                                        <th>Porcentaje</th>
                                        <th>Min</th>
                                        <th>Max</th>  
                                    </tr>
                                @else
                                    
                                @endif
                            @endif
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < count($tarifas); $i++)
                                
                                <tr>

                                    @if ($tarifas[$i]->tipoplaza==1)
                                        <td>MP</td>
                                    @elseif($tarifas[$i]->tipoplaza==2)
                                        <td>OP</td>
                                    @else  
                                        <td>PE</td>
                                    @endif
                                    <td>{{ $tarifas[$i]->nomediotarifa}}</td>

                                    @if ($oldre['moneda'] == null)
                                        @if ($oldre['tipo'] == null)
                                            <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_int_montofijo : 0 }}</td>
                                            @if ($tarifas[$i]->tipoplaza==1)
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                            @else
                                                <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_porcentaje : 0 }}%</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomin : 0 }}</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomax : 0 }}</td>
                                            @endif
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_int_montofijo : 0 }}</td>
                                            @if ($tarifas[$i]->tipoplaza==1)
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                            @else
                                                <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_porcentaje : 0 }}%</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomin : 0 }}</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomax : 0 }}</td>
                                            @endif
                                        @elseif ($oldre['tipo'] == 1)
                                            <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_int_montofijo : 0 }}</td>
                                            <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_int_montofijo : 0 }}</td>
                                        @else
                                            @if ($tarifas[$i]->tipoplaza==1)
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                            @else
                                                <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_porcentaje : 0 }}%</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomin : 0 }}</td>
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomax : 0 }}</td>
                                            @endif
                                            @if ($tarifas[$i]->tipoplaza==1)
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                            @else
                                                <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_porcentaje : 0 }}%</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomin : 0 }}</td>
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomax : 0 }}</td>
                                            @endif
                                        @endif
                                    @else
                                        @if ($oldre['moneda'] == 1)
                                            @if ($oldre['tipo'] == null)
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_int_montofijo : 0 }}</td>
                                                @if ($tarifas[$i]->tipoplaza==1)
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                @else
                                                    <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_porcentaje : 0 }}%</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomin : 0 }}</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomax : 0 }}</td>
                                                @endif
                                            @elseif ($oldre['tipo'] == 1)
                                                <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_int_montofijo : 0 }}</td>
                                            @else
                                                @if ($tarifas[$i]->tipoplaza==1)
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijo : 0 }}</td>
                                                @else
                                                    <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_porcentaje : 0 }}%</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomin : 0 }}</td>
                                                    <td>S/. {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->sol_cci_montofijomax : 0 }}</td>
                                                @endif
                                            @endif
                                        @else
                                            @if ($oldre['tipo'] == null)
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_int_montofijo : 0 }}</td>
                                                @if ($tarifas[$i]->tipoplaza==1)
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                @else
                                                    <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_porcentaje : 0 }}%</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomin : 0 }}</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomax : 0 }}</td>
                                                @endif
                                            @elseif ($oldre['tipo'] == 1)
                                                <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_int_montofijo : 0 }}</td>
                                            @else
                                                @if ($tarifas[$i]->tipoplaza==1)
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijo : 0 }}</td>
                                                @else
                                                    <td>{{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_porcentaje : 0 }}%</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomin : 0 }}</td>
                                                    <td>$ {{ $oldre['tipotransferencia'] > 0 ? $tarifas[$i]->dol_cci_montofijomax : 0 }}</td>
                                                @endif
                                            @endif
                                        @endif
                                    @endif

                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    <br>
                    @if ($oldre['tipotransferencia'] > 0)
                        @if ($tarifas[0]->tipotarifa > 0)
                            @php
                                $tarifa_get = $tarifas[0]->entidad_id;
                                $tipotrans_get = $oldre['tipotransferencia'];
                                $tipo_get = $tarifas[0]->tipotarifa;
                            @endphp
                            <a href="{{route('entidad_editar_tarifa_entidad', ['tarifa'=> $tarifa_get, 'tipotransferencia'=> $tipotrans_get, 'tipo' => $tipo_get ])}}" class="btn btn-morado">Modificar Tarifa</a>
                        @endif
                    @endif
                    @else
                        <hr>
                        <div class="alert alert-warning" role="alert">
                            No hay tarifas con los filtros agregados
                        </div>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

</section>
<div class="modal fade" id="modal-administrar_TIN" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Administrar TIN Especial</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- form start -->
                <form role="form" id="updateTipoCambio">
                    <input type="hidden" name="ad_entidad_id" id="ad_entidad_id">
                    <input type="hidden" name="ad_tipotarifa" id="ad_tipotarifa">
                    <div class="form-group">
                        <label for="tipo_cambio">Cuenta con TIN Especial</label>
                        <select name="tinespecial" id="tinespecial" class="form-control" required>
                            <option value="">:: Seleccione ::</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                            <option value="3">Solo receptor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tintope">Ingrese el tope (S/)</label>
                        <input type="text" name="tintope" class="form-control valdecimal" id="tintope"
                            placeholder="Ingresar el tope del TIN" required>
                    </div>
                    <div class="form-group">
                        <label for="tintopedol">Ingrese el tope ($)</label>
                        <input type="text" name="tintopedol" class="form-control valdecimal" id="tintopedol"
                            placeholder="Ingresar el tope del TIN" required>
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnUpdateTIN">Actualizar</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- /.content -->
<!-- page script -->
<script type="text/javascript">
    // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
                }, false);
            });
            }, false);
        })();
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            $('.select2').select2();
            
            $('#tipotarifas').change(function() {
                tarifa = $(this).val();
                cmb = '<option value="">:: Seleccione ::</option>';
                if(tarifa == 1){
                    cmb += '<option value="1">Ordinarias</option><option value="2">Pagos de targetas de crédito</option><option value="3">Pago de proveedores</option><option value="4">Pago de haberes</option><option value="5">Pago de CTS</option><option value="-1">TIN Especial</option>';
                }else{
                    cmb += '<option value="1">Ordinarias</option><option value="2">Pagos de targetas de crédito</option><option value="-1">TIN Especial</option>';
                }
                $('#tipotransferencia').html(cmb);
            });

            // $('#example2').DataTable({
            //     pageLength: 25,
            //     paging: true,
            //     lengthChange: true,
            //     searching: true,
            //     info: true,
            //     autoWidth: true,
            //     responsive: true,
            //     dom: '<"html5buttons"B>lTfgitp',
            //     buttons: ['excel']
            // });
            
        });
</script>
@endsection