@extends('layouts.app')
@section('title')
Tarifas
@endsection
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Crear Tarifas Bilaterales</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="row mb-2">
        <div class="col-12">
            <div class="card card-body" style="background-color: #f4eebd">
                <p>Aquí puedes crear las tarifas tanto en dólares como en soles entre las dos entidades indicadas, modifica los campos que desees y ejecuta tu modificación haciendo clic en "Modificar"</p>
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
                <form action="{{route('post_crear_tarifa')}}" method="POST" role="form" id="modificar" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-12">
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
                                    <select name="tipotransferencia" id="tipotransferencia" class="form-control" required>
                                        <option value="">:: Seleccione ::</option>
                                    </select>
                                </div>
                            </div>
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
                        </tr>
                        </thead>
                        <tbody>
                            
                                <tr>
                                    <th scope="row">
                                        MP                                    
                                    </th>
                                    <td colspan="3">
                                        <input type="number" min="0.00" class="form-control" name="mp_sol_montofijomin"  id="mp_sol_montofijomin" value="{{old('mp_sol_montofijomin')}}" required>
                                    </td>
                                    <td colspan="3">
                                        <input type="number" min="0.00" class="form-control" name="mp_dol_montofijomin"  id="mp_dol_montofijomin" value="{{old('mp_dol_montofijomin')}}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        OP
                                    </th>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="op_sol_porcentaje" id="op_sol_porcentaje"
                                            value="{{old('op_sol_porcentaje')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="op_sol_montofijomin" id="op_sol_montofijomin"
                                            value="{{old('op_sol_montofijomin')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="op_sol_montofijomax" id="op_sol_montofijomax"
                                            value="{{old('op_sol_montofijomax')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="op_dol_porcentaje" id="op_dol_porcentaje"
                                            value="{{old('op_dol_porcentaje')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="op_dol_montofijomin" id="op_dol_montofijomin"
                                            value="{{old('op_dol_montofijomin')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="op_dol_montofijomax" id="op_dol_montofijomax"
                                            value="{{old('op_dol_montofijomax')}}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        PE
                                    </th>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="pe_sol_porcentaje" id="pe_sol_porcentaje"
                                            value="{{old('pe_sol_porcentaje')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="pe_sol_montofijomin" id="pe_sol_montofijomin"
                                            value="{{old('pe_sol_montofijomin')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="pe_sol_montofijomax" id="pe_sol_montofijomax"
                                            value="{{old('pe_sol_montofijomax')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="pe_dol_porcentaje" id="pe_dol_porcentaje"
                                            value="{{old('pe_dol_porcentaje')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="pe_dol_montofijomin" id="pe_dol_montofijomin"
                                            value="{{old('pe_dol_montofijomin')}}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0.00" class="form-control valdecimal"  name="pe_dol_montofijomax" id="pe_dol_montofijomax"
                                            value="{{old('pe_dol_montofijomax')}}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <button type="submit" class="btn btn-primary btn-lg">Crear</button>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>            
    </div>
</section>    

<script type="text/javascript">
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

        $('#tipotarifas').change(function() {
            tarifa = $(this).val();
            cmb = '<option value="">:: Seleccione ::</option>';
            if(tarifa == 1){
                cmb += '<option value="1">Ordinarias</option><option value="2">Pagos de targetas de crédito</option><option value="3">Pago de proveedores</option><option value="4">Pago de haberes</option><option value="5">Pago de CTS</option><option value="6">TIN Especial</option>';
            }else{
                cmb += '<option value="1">Ordinarias</option><option value="2">Pagos de targetas de crédito</option><option value="6">TIN Especial</option>';
            }
            $('#tipotransferencia').html(cmb);
        });

    });        

</script>
@endsection