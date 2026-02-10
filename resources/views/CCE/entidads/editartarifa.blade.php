@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tarifa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Tarifa</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
                    <h3 class="card-title">Editar Tarifa</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('tarifa.update', $tarifa) }}" method="POST" role="form" id="quickForm"
                    class="needs-validation" novalidate>
                    <input type="hidden" value="{{ $tarifa->entidad_id }}" name="entidad_id">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="porcentaje">Tarifa</label>
                            <input type="text" name="porcentaje" class="form-control" id="porcentaje" placeholder="Ingresar una tarifa"
                                required value="{{ old('porcentaje', $tarifa->porcentaje) }}">
                        </div>
                        <div class="form-group">
                            <label for="name">Descripción</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Ingresar la Descripción" value="{{ old('name', $tarifa->name) }}">
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @csrf
                        @method('PUT')
                        <a href="{{ route('tarifa.index')}} " class="btn btn-default">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="btnSave">Actualizar</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
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
</script>
@endsection