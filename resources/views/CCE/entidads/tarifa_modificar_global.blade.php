@extends('layouts.app')
@section('title','Modificar')
@section('styles')
<link href="{{ asset('daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script src="{{ asset('daterangepicker/daterangepicker.js') }}"></script>
@endsection
@section('content')
    <!-- Content Header (Page header) -->


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modificar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Tarifas</li>
                        <li class="breadcrumb-item active">Modificar</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <form action="{{route('modificar_tarifa_global')}}" method="POST" enctype="multipart/form-data" role="form" id="quickForm" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="container-fluid">
            <div class="row">
                <div class="col-6 col-md-6" > 
                    <!-- Main content -->
                    <section class="content">
                            <!-- left column -->
                            <div >
                                <!-- general form elements -->
                                <div class="card card-morado">
                                    <div class="card-header">
                                        <h3 class="card-title">Escoger Bancos</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        @if ($errors->all())
                                            <div class="alert alert-warning alert-dismissable mb-3" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                                                <div class="alert-text">
                                                    @foreach ($errors->all() as $message)
                                                        {{ $message }}
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
            
                                        @if (session('status'))
                                            <div class="alert alert-success mb-3" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        <div class="msgEntidad" style="display:none;">
                                            <div class="alert alert-warning mb-3" role="alert">Debe escoger al menos un banco.</div>
                                        </div>
                                        <table id="example" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="checkboxPrimary0">
                                                        <label for="checkboxPrimary0">
                                                            Seleccionar todo
                                                        </label>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($entidads as $entidad)
                                                <tr>
                                                    <td>{{ $entidad->code }}</td>
                                                    <td>{{ $entidad->alias }}</td>
                                                    <td>
                                                        <!-- checkbox -->
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline check_todos">
                                                                <input type="checkbox" id="checkboxPrimary{{ $entidad->id }}"
                                                                    value="{{ $entidad->id }}">
                                                                <label for="checkboxPrimary{{ $entidad->id }}"></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
            
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        <!-- /.row -->
                    </section>
                    <div class="hiddenInput"></div>
                </div>
                <div class="col-6 col-md-6" style="max-width: 100%">
                    
                    <div class="card card-morado">
                        <div class="card-header">
                            <h3 class="card-title">Elegir tipo de tarifa</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            
                                <div class="form-group col-md-6">
                                    <label for="tipotarifas">Diferida / Inmediata *</label>
                                    <select name="tipotarifas" id="tipotarifas" class="form-control" required>
                                        <option value="">:: Seleccione ::</option>
                                        <option value="1" {{ old('tipotarifas') == 1 ? 'selected' : '' }}>Tarifa Diferida
                                        </option>
                                        <option value="2" {{ old('tipotarifas') == 2 ? 'selected' : '' }}>Tarifa Inmediata
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tipotransferencia">Tipo de transferencia *</label>
                                    <select name="tipotransferencia" id="tipotransferencia" class="form-control" required>
                                        <option value="">:: Seleccione ::</option>
                                    </select>
                                </div>
                                
                        </div>
                    </div>
                </div>
          
                <div class="col-12">
                    <div class="card card-body" style="overflow-x: scroll">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5>Datos de la entidad</h5>
                            </div>
                        </div>
                        <hr>
                        <table id="example3" class="table table-bordered table-striped" style="text-align: center">
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
                                <th colspan="3" style="padding-bottom:30px;">Tarifa interbancaria</th>
                                <th colspan="1">Tarifa de entidad financiera</th>
                                <th colspan="3" style="padding-bottom:30px;">Tarifa interbancaria</th>
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
                            <tr>
                                <td>
                                    MP
                                    <input type="hidden" min="0" name="tipoplaza[]" value="1">
                                </td>
                                <td>
                                    Página Web
                                    <input type="hidden" name="mediotarifa_id[]" value="1">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
                                <td colspan="3" align="center">
                                    <input type="hidden" min="0" name="sol_cci_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_cci_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_cci_montofijomin[]" value="0">
                                    <input type="number" min="0" class="form-control" name="sol_cci_montofijo[]" id=""
                                           value="0" required>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td colspan="3" align="center">
                                    <input type="number" min="0" class="form-control" name="dol_cci_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_cci_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_cci_montofijomin[]" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    OP
                                    <input type="hidden" min="0" name="tipoplaza[]" value="2">
                                </td>
                                <td>
                                    Página Web
                                    <input type="hidden" name="mediotarifa_id[]" value="1">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
    
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                            </tr>
                            <tr>
                                <td>
                                    PE
                                    <input type="hidden" min="0" name="tipoplaza[]" value="3">
                                </td>
                                <td>
                                    Página Web
                                    <input type="hidden" name="mediotarifa_id[]" value="1">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
    
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                            </tr>
    
    
                            <tr>
                                <td>
                                    MP
                                    <input type="hidden" min="0" name="tipoplaza[]" value="1">
                                </td>
                                <td>
                                    Aplicación Móvil
                                    <input type="hidden" name="mediotarifa_id[]" value="2">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
                                <td colspan="3" align="center">
                                    <input type="hidden" min="0" name="sol_cci_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_cci_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_cci_montofijomin[]" value="0">
                                    <input type="number" min="0" class="form-control" name="sol_cci_montofijo[]" id=""
                                           value="0" required>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td colspan="3" align="center">
                                    <input type="number" min="0" class="form-control" name="dol_cci_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_cci_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_cci_montofijomin[]" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    OP
                                    <input type="hidden" min="0" name="tipoplaza[]" value="2">
                                </td>
                                <td>
                                    Aplicación Móvil
                                    <input type="hidden" name="mediotarifa_id[]" value="2">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
    
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                            </tr>
                            <tr>
                                <td>
                                    PE
                                    <input type="hidden" min="0" name="tipoplaza[]" value="3">
                                </td>
                                <td>
                                    Aplicación Móvil
                                    <input type="hidden" name="mediotarifa_id[]" value="2">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
    
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                            </tr>
    
                            {{-- -------------------- --}}
    
                            <tr>
                                <td>
                                    MP
                                    <input type="hidden" min="0" name="tipoplaza[]" value="1">
                                </td>
                                <td>
                                    Ventanilla
                                    <input type="hidden" name="mediotarifa_id[]" value="3">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
                                <td colspan="3" align="center">
                                    <input type="hidden" min="0" name="sol_cci_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_cci_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_cci_montofijomin[]" value="0">
                                    <input type="number" min="0" class="form-control" name="sol_cci_montofijo[]" id=""
                                           value="0" required>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td colspan="3" align="center">
                                    <input type="number" min="0" class="form-control" name="dol_cci_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_cci_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_cci_montofijomin[]" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    OP
                                    <input type="hidden" min="0" name="tipoplaza[]" value="2">
                                </td>
                                <td>
                                    Ventanilla
                                    <input type="hidden" name="mediotarifa_id[]" value="3">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
    
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                            </tr>
                            <tr>
                                <td>
                                    PE
                                    <input type="hidden" min="0" name="tipoplaza[]" value="3">
                                </td>
                                <td>
                                    Ventanilla
                                    <input type="hidden" name="mediotarifa_id[]" value="3">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="sol_int_montofijomin[]" value="0">
                                </td>
    
                                <td>
                                    <input type="number" min="0" class="form-control" name="sol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="sol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="sol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_int_montofijo[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_int_porcentaje[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomax[]" value="0">
                                    <input type="hidden" min="0" name="dol_int_montofijomin[]" value="0">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="dol_cci_porcentaje[]" id=""
                                           value="0" required>
                                    <input type="hidden" min="0" name="dol_cci_montofijo[]" value="0">
                                </td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomin[]" id=""
                                           value="0" required></td>
                                <td><input type="number" min="0" class="form-control" name="dol_cci_montofijomax[]" id=""
                                           value="0" required></td>
                            </tr>
                            </tbody>
                            
                        </table>
                        <div class="row" style="margin-left: 15px !important;">
                            <div class="form-group">
                              <div class="col-md-12 mb-3 my-3">
                                 <button type="submit" class="btn btn-morado" id="submit-all">Actualizar</button>
                             </div>
                            </div>
                        </div>
                    </div>
                        <!--<div class="row">
                            <div class="col-md-12 mb-3 my-3">
                                <button type="submit" class="btn btn-morado" id="submit-all">Actualizar</button>
                            </div>
                        </div>-->
    
                    </div>
                    
                </div>
            </div>
           
    
  
    
</form>
    

    <!-- /.content-wrapper -->

    <!-- /.content -->
    <!-- page script -->
    <script type="text/javascript">
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            form.classList.add('was-validated');
                            event.preventDefault();
                            event.stopPropagation();
                            $('#submit-all').removeAttr('disabled');
                        } else {
                            event.preventDefault();
                            event.stopPropagation();
                            $('#submit-all').attr('disabled', 'disabled');
                            if ($('input[name="entidad_id[]"]').length > 0) {
                                $('form').submit();
                            } else {
                                $('.msgEntidad').show();
                                $('#submit-all').removeAttr('disabled');
                            }
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
            $("#checkboxPrimary0").change(function () {
                if ($(this).is(':checked')) {
                    $('.hiddenInput').html('');
                    $(".check_todos input").each(function () {
                        $('.hiddenInput').append('<input type="hidden" name="entidad_id[]" value="' + $(this).val() + '" id="entidad_id_' + $(this).val() + '">');
                    });
                    $('.msgEntidad').hide();
                } else {
                    $('.hiddenInput').html('');
                    $('.msgEntidad').show();
                }
                $(".check_todos input:checkbox").prop('checked', $(this).prop("checked"));
            });

            $(".check_todos input").change(function () {
                if ($(this).is(':checked')) {
                    $('.hiddenInput').append('<input type="hidden" name="entidad_id[]" value="' + $(this).val() + '" id="entidad_id_' + $(this).val() + '">');
                    $('.msgEntidad').hide();
                } else {
                    $('#entidad_id_' + $(this).val()).remove();
                }
            });

        })();
        // Dropzone.autoDiscover = false;
        // var myDropzone;
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#tipotarifas').change(function () {
                tarifa = $(this).val();
                cmb = '<option value="">:: Seleccione ::</option>';
                if (tarifa == 1) {
                    cmb += '<option value="1">Ordinarias</option><option value="2">Pagos de targetas de crédito</option><option value="3">Pago de proveedores</option><option value="4">Pago de haberes</option><option value="5">Pago de CTS</option>';
                } else {
                    cmb += '<option value="1">Ordinarias</option><option value="2">Pagos de targetas de crédito</option>';
                }
                $('#tipotransferencia').html(cmb);
            });

            $('#enviarfechahora').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePickerIncrement: true,
                locale: {
                    format: 'YYYY-MM-DD hh:mm A'
                }
            });

            $('#example').DataTable({
                "scrollY": "250px",
                "scrollCollapse": true,
                "paging": false
            });
           
            $('#example').each(function(){
                var datatable = $(this);
                // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.addClass('form-control form-control-sm filtrar-banco');

            });
            

        });

        function slugify(string) {
            return string
                .toString()
                .trim()
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "")
                .replace(/\-\-+/g, "-")
                .replace(/^-+/, "")
                .replace(/-+$/, "");
        }
    </script>
@endsection
<style>
    .filtrar-banco{
        max-width: 150px;
    }
</style>