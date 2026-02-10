@extends('layouts.app')
@section('title')
Editar Agenda
@endsection
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
                <h1>Agendar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="#">Agendar</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-12 col-md-6">
            <!-- general form elements -->
            <div class="card card-morado">
                <div class="card-header">
                    <h3 class="card-title">Escoger Bancos</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @include('includes.form-error')
                    @include('includes.mensajes')
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
                                        <input type="checkbox" id="checkboxPrimary0" @if (count($entidads)==count($entxage)) checked @endif>
                                        <label for="checkboxPrimary0">
                                            Seleccionar todo
                                        </label>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($entidads)
                            @foreach ($entidads as $entidad)
                            <tr>
                                <td>{{ $entidad->code }}</td>
                                <td>{{ $entidad->alias }}</td>
                                <td>
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline check_todos">
                                            <input type="checkbox" id="checkboxPrimary{{ $entidad->id }}" value="{{ $entidad->id }}" @if(in_array($entidad->id, $entxage)) checked @endif>
                                            <label for="checkboxPrimary{{ $entidad->id }}"></label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-12 col-md-6">
            <!-- general form elements -->
            <div class="card card-morado">
                <div class="card-header">
                    <h3 class="card-title">Datos</h3>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('agendar.update', $agendar) }}" method="POST" enctype="multipart/form-data" role="form" id="quickForm"
                        class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="hiddenInput">
                            @if ($agendar->entidads)
                            @foreach ($agendar->entidads as $entidad)
                            <input type="hidden" value="{{ $entidad->id }}" name="entidad_id[]" id="entidad_id_{{ $entidad->id }}">
                            @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Título *</label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Ingrese el Nombre" required value="{{ old('name', $agendar->name) }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción *</label>
                            <textarea name="description" class="form-control" id="" rows="6" required>{{ old('description', $agendar->description) }}</textarea>
                        </div>
                        <!-- Date dd/mm/yyyy -->
                        <div class="form-group">
                            <label>Horario de agenda:</label>
                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right" id="reservationtime" name="fechainifin" value="{{ old('fechainifin', $fechaeditar) }}">
                            </div>
                            <!-- /.input group -->
                            
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-morado" id="submit-all">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    {{-- {{ var_dump(old('feinicio',$agendar->feinicio) ,old('fefin',$agendar->fefin)) }} --}}
    <!-- /.row -->
</section>
<!-- /.content-wrapper -->

<!-- /.content -->
<!-- page script -->
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
                    form.classList.add('was-validated');
                    event.preventDefault();
                    event.stopPropagation();
                    $('#submit-all').removeAttr('disabled');
                }
                else{
                    event.preventDefault();
                    event.stopPropagation();
                    $('#submit-all').attr('disabled','disabled');
                    if($('input[name="entidad_id[]"]').length > 0){
                        $('form').submit();
                    }else{
                        $('.msgEntidad').show();
                        $('#submit-all').removeAttr('disabled');
                    }
                }
                form.classList.add('was-validated');
                }, false);
            });
            }, false);
        })();
        // Dropzone.autoDiscover = false;
        // var myDropzone;
        $(function () {

            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'DD/MM/YYYY hh:mm A'
                }
                //$("#reservationtime").data().daterangepicker.startDate = moment( 'fechainifin', $agendar-> );
                // $("#reservationtime").data().daterangepicker.endDate = moment( 'fechainifin', datepicker.data().daterangepicker.format );
            })
            $('#example').DataTable( {
                "scrollY":        "310px",
                "scrollCollapse": true,
                "paging":         false
            } );
            $("#checkboxPrimary0").change(function () {
                if($(this).is(':checked')){
                    $('.hiddenInput').html('');
                    $(".check_todos input").each(function(){
                        $('.hiddenInput').append('<input type="hidden" name="entidad_id[]" value="'+$(this).val()+'" id="entidad_id_'+$(this).val()+'">');
                    });
                    $('.msgEntidad').hide();
                }else{
                    $('.hiddenInput').html('');
                    $('.msgEntidad').show();
                }
                $(".check_todos input:checkbox").prop('checked', $(this).prop("checked"));
            });

            $(".check_todos input").change(function () {
                if($(this).is(':checked')){
                    $('.hiddenInput').append('<input type="hidden" name="entidad_id[]" value="'+$(this).val()+'" id="entidad_id_'+$(this).val()+'">');
                    $('.msgEntidad').hide();
                }else{
                    $('#entidad_id_'+$(this).val()).remove();
                }
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