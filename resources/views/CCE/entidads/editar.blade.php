@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Entidad</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Entidad</a></li>
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
                <h3 class="card-title">Editar Entidad</h3>
              </div>
              <!-- /.card-header -->
              
              <!-- form start -->
                <form action="{{ route('entidad.update', $entidad) }}" method="POST" enctype="multipart/form-data" role="form" id="quickForm" class="needs-validation" novalidate>
                    <div class="card-body">
                      @include('includes.form-error')
                      @include('includes.mensajes')
                        <div class="form-group">
                            <label for="code">Código de entidad</label>
                            <input type="text" name="code" class="form-control valnumero" id="code" placeholder="Ingresar el número" required value="{{ old('code', $entidad->code) }}">
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Ingresar el nombre" required value="{{ old('name', $entidad->name) }}">
                        </div>
                        <div class="form-group">
                            <label for="alias">Alias</label>
                            <input type="text" name="alias" class="form-control" id="alias" placeholder="Ingresar el Alias" required value="{{ old('alias', $entidad->alias) }}">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputFile">Logo de la entidad</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="logo" id="logo" accept=".jpeg,.jpg,.png,.gif">
                              <label class="custom-file-label" for="logo" data-browse="Escoger"></label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                            <label for="flestado">Estado</label>
                            <select name="flestado" id="flestado" class="form-control" required>
                                <option value="">:: SELECCIONE ::</option>
                                <option value="1" @if ($entidad->flestado == 1) selected @endif>Activo</option>
                                <option value="0" @if ($entidad->flestado == 0) selected @endif>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @csrf
                        @method('PUT')
                        <a href="{{ route('home')}} " class="btn btn-default">Regresar</a>
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

        $(function () {
          $('#logo').change(function() {
            files = $(this)[0].files;
            $(this).next('.custom-file-label').html('');
            size = (files[0].size / 1024 / 1024).toFixed(2);
            if(size <= 4 && $.inArray(files[0].name.split(".")[1].toLowerCase(),['jpg','png','jpeg','gif'])>= 0){
              $(this).next('.custom-file-label').append(files[0].name.toLowerCase());
            }else{
              msgInput += 'El archivo: ' + files[0].name + ' pesa más de 10 MB o no es un tipo de archivo permitido.<br>';
              $(this).next('.custom-file-label').html('');
              $(this).replaceWith($(this).val('').clone(true));
            }
          });
        });
    </script>
@endsection