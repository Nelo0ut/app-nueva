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
                <h1>Tarifa Bilaterales</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
{{-- <section class="content">
    <div class="row">
        <div class="col-3 col-md-3">

            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <button type="button" class="btn btn-block btn-morado" data-toggle="modal"
                        data-target="#modal-default-create">Crear Tarifa</button>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtrar tarifas bilaterales</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            @include('includes.form-error')
                            @include('includes.mensajes')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('filtar_tarifa_bilateral') }}" method="POST" role="form" id="quickForm" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="entidad1_id">Entidad 1 *</label>
                                        <select class="form-control select2 select2-purple" data-dropdown-css-class="select2-purple" style="width: 100%;"
                                            required id="entidad1_id" name="entidad1_id">
                                            <option value="">:: Seleccione ::</option>
                                            @foreach ($entidads as $entidad)
                                            <option value="{{ $entidad->id }}" {{ old('entidad1_id') == $entidad->id ? 'selected' : '' }}>
                                                {{ $entidad->alias }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="entidad2_id">Entidad 2 *</label>
                                        <select class="form-control select2 select2-purple" data-dropdown-css-class="select2-purple" style="width: 100%;"
                                            required id="entidad2_id" name="entidad2_id">
                                            <option value="">:: Seleccione ::</option>
                                            @foreach ($entidads as $entidad)
                                            <option value="{{ $entidad->id }}" {{ old('entidad2_id') == $entidad->id ? 'selected' : '' }}>
                                                {{ $entidad->alias }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tipotarifas">Diferida / Inmediata *</label>
                                        <select name="tipotarifas" id="tipotarifas" class="form-control" required>
                                            <option value="">:: Seleccione ::</option>
                                            <option value="1" {{ old('tipotarifas') == 1 ? 'selected' : '' }}>Tarifa Diferida</option>
                                            <option value="2" {{ old('tipotarifas') == 2 ? 'selected' : '' }}>Tarifa Inmediata</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tipotransferencia">Tipo de transferencia *</label>
                                        <select name="tipotransferencia" id="tipotransferencia" class="form-control">
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
                                    <div class="form-group col-md-3 pt-4">
                                        <button type="submit" class="btn btn-morado mt-2 mr-2">Filtrar</button>
                                        <a href="{{route('ver_crear_tarifa')}}" class="btn btn-morado mt-2">Nueva Tarifa</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if($tarifatotal)
                        <hr>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5>Datos de la entidad</h5>
                            </div>
                            <div class="col-md-3 mb-3">
                                <strong>Entidad 1:</strong> {{$tarifatotal[0]['info'][0]->alias}}
                            </div>
                            <div class="col-md-3 mb-3">
                                <strong>Entidad 2:</strong> {{$tarifatotal[0]['info'][0]->alias2}}
                            </div>
                            <div class="col-md-3 mb-3">
                                @if ($tarifatotal[0]['info'][0]->tipotarifa==1)
                                    <strong>Tarifa:</strong> Diferida
                                @else
                                    <strong>Tarifa:</strong> Inmediata
                                @endif
                            </div>
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
                        <hr>
                        @for ($i = 0; $i < count($tarifatotal); $i++)
                            <div class="col-md-3 mb-3">
                                    @if ($tarifatotal[$i]['info'][0]->tipotransferencia==1)
                                    <strong>Tipo de transferencia:</strong> Ordinaria
                                    @elseif($tarifatotal[$i]['info'][0]->tipotransferencia==2)
                                    <strong>Tipo de transferencia:</strong> Pago de tarjeta de crédito
                                    @elseif($tarifatotal[$i]['info'][0]->tipotransferencia==3)
                                    <strong>Tipo de transferencia:</strong> Pago de proveedores
                                    @elseif($tarifatotal[$i]['info'][0]->tipotransferencia==4)
                                    <strong>Tipo de transferencia:</strong> Pago de haberes
                                    @elseif($tarifatotal[$i]['info'][0]->tipotransferencia==5)
                                    <strong>Tipo de transferencia:</strong> Pago de CTS
                                    @else
                                    <strong>Tipo de transferencia:</strong> TIN Especial
                                    @endif
                            </div>
                        
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        @if ($oldre['moneda'] == null)
                                            <th rowspan="2">Plaza</th>
                                            <th colspan="3">Soles</th>
                                            <th colspan="3">Dolares</th>
                                        @else
                                            <th rowspan="2">Plaza</th>
                                            <th>Porcentaje</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($a = 0; $a < count($tarifatotal[$i]['info']);$a++)
                                        
                                        <tr>
                                            @if ($tarifatotal[$i]['info'][$a]->tipoplaza==1)
                                                <td>MP</td>
                                            @elseif($tarifatotal[$i]['info'][$a]->tipoplaza==2)
                                                <td>OP</td>
                                            @else  
                                                <td>PE</td>
                                            @endif

                                            @if ($oldre['moneda'] == null)
                                                @if ($tarifatotal[$i]['info'][$a]->tipoplaza==1)
                                                    <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                    <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                    <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                @else
                                                    <td>{{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_porcentaje : 0 }}%</td>
                                                    <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                    <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomax : 0 }}</td>
                                                @endif
                                                @if ($tarifatotal[$i]['info'][$a]->tipoplaza==1)
                                                    <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                    <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                    <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                @else
                                                    <td>{{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_porcentaje : 0 }}%</td>
                                                    <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                    <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomax : 0 }}</td>
                                                @endif
                                            @else
                                                @if ($oldre['moneda'] == 1)
                                                    @if ($tarifatotal[$i]['info'][$a]->tipoplaza==1)
                                                        <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                        <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                        <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                    @else
                                                        <td>{{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_porcentaje : 0 }}%</td>
                                                        <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomin : 0 }}</td>
                                                        <td>S/. {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->sol_montofijomax : 0 }}</td>
                                                    @endif
                                                @else
                                                    @if ($tarifatotal[$i]['info'][$a]->tipoplaza==1)
                                                        <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                        <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                        <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                    @else
                                                        <td>{{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_porcentaje : 0 }}%</td>
                                                        <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomin : 0 }}</td>
                                                        <td>$ {{ $tarifatotal[$i]['info'][0]->tipotransferencia > 0 ? $tarifatotal[$i]['info'][$a]->dol_montofijomax : 0 }}</td>
                                                    @endif
                                                @endif
                                            @endif

                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                            @for ($b = 0; $b < count($tarifatotal[$i]['info']);$b++)
                                @if ($tarifatotal[$i]['info'][$b]->tipoplaza == 1)
                                    @php $mp = $tarifatotal[$i]['info'][$b]->id; @endphp                           
                                @elseif ($tarifatotal[$i]['info'][$b]->tipoplaza == 2)
                                    @php $op = $tarifatotal[$i]['info'][$b]->id; @endphp
                                @elseif ($tarifatotal[$i]['info'][$b]->tipoplaza == 3)
                                    @php $pe = $tarifatotal[$i]['info'][$b]->id; @endphp
                                @endif
                            @endfor              
                            <div class="col-md-12 mt-3">
                                <a href="{{route('eliminar_tarifa', ['mp'=>$mp,'op'=>$op,'pe'=>$pe])}}" class="btn btn-morado mr-2">Eliminar Tarifa</a>
                                <a href="{{route('ver_tarifa', ['mp'=>$mp,'op'=>$op,'pe'=>$pe])}}" class="btn btn-morado">Modificar Tarifa</a>
                            </div>
                            <hr>
                        @endfor                        
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
<div class="modal fade" id="modal-administrar_TIN" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <label class="labelform">Plaza</label><br>
                        @php $i = 0; @endphp
                        @if (!empty($medios))
                        @foreach ($medios as $medio)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input checkmedios" id="medios_{{$medio->mediotarifa}}"
                                value="{{$medio->mediotarifa}}" name="medios[]" required @if($medio->tinespecial == 1) checked @endif>
                            <label class="custom-control-label" for="medios_{{$medio->mediotarifa}}">{{$medio->nomediotarifa}}</label>
                        </div>
                        @php $i++; @endphp
                        @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="tintope">Ingrese el tope (S/)</label>
                        <input type="text" name="tintope" class="form-control valdecimal" id="tintope"
                            placeholder="Ingresar el tope del TIN" required>
                    </div>
                    <div class="form-group">
                        <label for="tintopedol">Ingrese el tope ($)</label>
                        <input type="text" name="tintopedol" class="form-control valdecimal" id="tintopedol" placeholder="Ingresar el tope del TIN" required>
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
            

            $('#tinespecial').change(function () {
                if($(this).val() == 0){
                    $('.checkmedios:checkbox').prop('checked', false);
                    $('#tintope, #tintopedol').val(0);
                }
            });

            $('#btnUpdateTIN').click(function(){
                tin = $('#tinespecial').val();
                tope = $('#tintope').val();
                topedol = $('#tintopedol').val();
                id = $('#ad_entidad_id').val();
                tipo = $('#ad_tipotarifa').val();
                var medios = $('input[name^="medios[]"]').serializeArray();
                // console.log(medios);
                // return
                if(tin.length > 0 && tope.length > 0 && id.length > 0 && tipo.length > 0 && topedol.length > 0 ){
                    $.ajax({
                        type:'POST',
                        url:"{{ route('administrar_tin') }}",
                        data:{ ad_entidad_id:id, ad_tipotarifa: tipo, tinespecial: tin, tintope: tope, tintopedol: topedol, medios: medios },
                        beforeSend:function(){
                            $('.preload').show();
                        },
                        success:function(data){
                            if(data.status == 1){
                                swalWithBootstrapButtons.fire(
                                '¡Éxito!',
                                'Se actualizó el TIN Especial con éxito.',
                                'success'
                                )
                                setTimeout(function(){ location.reload(); }, 3000);
                                
                            }else{
                                swalWithBootstrapButtons.fire(
                                '¡Aviso!',
                                'No se pudo actualizar el TIN Especial.',
                                'warning'
                                )
                            }
                        },
                        complete:function(){
                            $('.preload').hide();
                        }
                    });
                }else{
                    swalWithBootstrapButtons.fire(
                        '¡Aviso!',
                        'Debe completar todos los campos.',
                        'error'
                    )
                }
                
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

            $('.abrirmodalTIN').click(function(){
                id = $(this).data("id");
                tipo = $(this).data("tipo");
                $('#ad_entidad_id').val(id);
                $('#ad_tipotarifa').val(tipo);
                tin = $('#tinespecial_save').val();
                tope = $('#tintope_save').val();
                topedol = $('#tintopedol_save').val();

                $('#tinespecial').val(tin);
                $('#tintope').val(tope);
                $('#tintopedol').val(topedol);

                $('#modal-administrar_TIN').modal('show');
            });

            $('#example2').DataTable({
                pageLength: 25,
                paging: true,
                lengthChange: true,
                searching: true,
                info: true,
                autoWidth: true,
                responsive: true,
                order: [[ 1, "asc" ]],
                dom: '<"html5buttons"B>lTfgitp',
                buttons: ['excel']
            });
            
        });
</script>
@endsection