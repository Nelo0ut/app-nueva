@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Importar oficinas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Oficinas</a></li>
              <li class="breadcrumb-item active">Importar</li>
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
                <h3 class="card-title">Importar oficinas</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{ route('save_importar') }}" method="POST" enctype="multipart/form-data" role="form" id="quickForm" class="needs-validation" novalidate>
                @csrf
                <div class="card-body">
                  {{-- @if (session("status")) --}}
                    @if ( (int)session("status") == 1)
                      <div class="alert alert-success alert-dismissable mt-1 mb-1" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Mensaje del sistema</h5>
                          Se cargó las oficinas con éxito.
                      </div>
                    @endif
                    @if((int)session("status") == 2)
                      <div class="alert alert-warning alert-dismissable mb-3" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> El documento contiene errores</h5>
                        <ul>
                          @if (session("repetidos"))
                            @if (count(session("repetidos")) > 0)
                              @for ($i = 0; $i < count(session("repetidos")); $i++)
                                <li>Código de oficina repetida: {{session("repetidos")[$i]}}</li>
                              @endfor
                            @endif
                          @endif
                          @if (session("noexiste"))
                            @if (count(session("noexiste")) > 0)
                              @for ($a = 0; $a < count(session("noexiste")); $a++)
                                <li>El código {{session("noexiste")[$a]}} de la entidad ingresada no existe en el sistema.</li>
                              @endfor
                            @endif  
                          @endif
                          @if (session("noubigeo"))
                            @if (count(session("noubigeo")) > 0)
                              @for ($a = 0; $a < count(session("noubigeo")); $a++)
                                <li>El código de ubigeo {{session("noubigeo")[$a]}} de la oficina ingresada no existe en el sistema.</li>
                              @endfor
                            @endif  
                          @endif
                        </ul>
                      </div>
                    @endif
                  {{-- @endif --}}
                  

                  <div class="form-group">
                    <label for="file">Archivo con oficinas a importar</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" required>
                        <label class="custom-file-label" for="file" data-browse="Escoger archivo">Escoger archivo</label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-morado" id="submit-all">Importar</button>
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
    <script type = "text/javascript">
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
              }else{
                  event.preventDefault();
                  event.stopPropagation();
                  $('form').submit();
                  $('#submit-all').attr('disabled','disabled');
              }
              form.classList.add('was-validated');
              }, false);
          });
          }, false);
      })();

      $(function () {
        $('#file').change(function() {
            files = $(this)[0].files;
        
            $(this).next('.custom-file-label').html('');
            console.log(files);
        
            msgInput = '<div class="alert alert-warning" role="alert">';
            size = (files[0].size / 1024 / 1024).toFixed(2);
            if(size <= 8 && $.inArray(files[0].name.split(".")[1].toLowerCase(),['txt','TXT'])>= 0){
                $(this).next('.custom-file-label').append(files[0].name.split(".")[0].toLowerCase());
            }else{
                msgInput += 'El archivo: ' + files[0].name + ' pesa más de 8 MB o no es un tipo de archivo permitido.<br>';
                $(this).next('.custom-file-label').html('Escoger archivo');
                $(this).replaceWith($(this).val('').clone(true));
                alert(msgInput);
            }
        });
      });
    </script>
    
    @endsection