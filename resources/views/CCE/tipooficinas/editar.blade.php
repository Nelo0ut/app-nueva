@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tipo de Oficina</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Tipo de Oficina</a></li>
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
                <h3 class="card-title">Editar Tipo de Oficina</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <form action="{{ route('tipooficina.update', $tipooficina) }}" method="POST" role="form" id="quickForm" class="needs-validation" novalidate>
                    <div class="card-body">
                      @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Ingresar el nombre" required value="{{ old('name', $tipooficina->name) }}">
                        </div>
                        <div class="form-group">
                            <label for="flestado">Estado</label>
                            <select name="flestado" id="flestado" class="form-control" required>
                                <option value="">:: SELECCIONE ::</option>
                                <option value="1" @if ($tipooficina->flestado == 1) selected @endif>Activo</option>
                                <option value="0" @if ($tipooficina->flestado == 0) selected @endif>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @csrf
                        @method('PUT')
                        <a href="{{ route('tipooficina.index')}} " class="btn btn-default">Regresar</a>
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
    <script  type = "text/javascript">
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