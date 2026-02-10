@extends('layouts.app')
@section('title')
Editar Usuario
@endsection
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Usuario</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Usuario</a></li>
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
                <h3 class="card-title">Editar Usuario</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <form action="{{ route('usuario-actualiza', $user) }}" method="POST" role="form" id="quickForm" class="needs-validation" novalidate>
                    <div class="card-body">
                      <input type="hidden" name="iduser" id="iduser" value="{{$user->id}}">
                        @include('includes.form-error')
                        @include('includes.mensajes')
                        <input type="hidden" name="entidad_id" value="{{ session()->get('identidad') }}">
                        <div class="form-group">
                            <label for="name">Nombres</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Ingresar el nombre completo" required value="{{ old('name', $user->name) }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Ingresar el correo" required value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="form-group">
                          <label for="email_2">Correo N°2 <span>(Opcional)</span></label>
                          <input type="email" name="email_2" class="form-control" id="email_2" placeholder="Ingresar el correo"  value="{{ old('email_2', $user->email_2) }}">
                      </div>
                        <div class="form-group">
                          <label for="usuario">Usuario</label>
                          <input type="text" name="usuario" class="form-control alphaonly" id="usuario" placeholder="Ingresar el usuario" maxlength="15" value="{{ old('usuario', $user->usuario) }}"
                            minlength="5" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Ingresar la contraseña">
                        </div>

                        {{-- <div class="form-group">
                            <label for="role_id">Tipo</label>
                            <select name="role_id" id="role_id" class="form-control" required>
                            <option value="">:: SELECCIONE ::</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"  @if ($user->role_id == $role->id) selected @endif>{{ $role->tipo }}</option>
                            @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="flestado">Estado</label>
                            <select name="flestado" id="flestado" class="form-control" required>
                            <option value="">:: SELECCIONE ::</option>
                            <option value="1"  @if ($user->flestado == 1) selected @endif>Activo</option>
                            <option value="0"  @if ($user->flestado == 0) selected @endif>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @csrf
                        @method('PUT')
                        <a href="{{ route('usuario-listar') }}" class="btn btn-default" style="color: #444;">Regresar</a>
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
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });

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

          const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-morado',
              cancelButton: 'btn btn-naranja'
            },
            buttonsStyling: false
          });

          $('#email').blur(function() {
              email = $(this).val();
              id = $('#iduser').val();
              if($('#email').val().length <5){
                $('#email').val('');
                return ;
              }
              else if(!validateEmail("email"))
              {
                swalWithBootstrapButtons.fire(
                '¡Aviso!',
                'Debe ingresar un formato de correo correcto.',
                'warning'
                )
                return false;
              }
              //Aquí va el ajax
              $.ajax({
                  type:'POST',
                  url:"{{ route('getcorreouserent') }}",
                  data:{ email:email, id:id },
                  beforeSend:function(){
                    $('.preload').show();
                  },
                  success:function(data){
                      if(data.existe == 0){
                        if($('#email_2').val() == email){
                          $('#email').val('');
                            swalWithBootstrapButtons.fire(
                            '¡Aviso!',
                            'Los correos no pueden ser iguales',
                            'warning'
                            )
                          }
                        else {
                          swalWithBootstrapButtons.fire(
                          '¡Éxito!',
                          'El correo está disponible.',
                          'success'
                          )
                        }
                          
                      }else{
                          $('#email').val('');
                          swalWithBootstrapButtons.fire(
                          '¡Aviso!',
                          'El correo no está disponible.',
                          'warning'
                          )
                      }
                  },
                  complete:function(){
                    $('.preload').hide();
                  }
              });
          });


          $('#email_2').blur(function() {
              email_2 = $(this).val();
              id = $('#iduser').val();
              if($('#email_2').val().length <5){
                $('#email_2').val('');
                return ;
              }
              else if(!validateEmail("email_2"))
              {
                swalWithBootstrapButtons.fire(
                '¡Aviso!',
                'Debe ingresar un formato de correo correcto.',
                'warning'
                )
                return false;
              }
              //Aquí va el ajax
              $.ajax({
                  type:'POST',
                  url:"{{ route('getcorreoalternativouserent') }}",
                  data:{ email_2:email_2, id:id },
                  beforeSend:function(){
                    $('.preload').show();
                  },
                  success:function(data){
                      if(data.existe == 0){
                        if($('#email').val() == email_2){
                          $('#email_2').val('');
                            swalWithBootstrapButtons.fire(
                            '¡Aviso!',
                            'Los correos no pueden ser iguales',
                            'warning'
                            )
                          }
                        else {
                          swalWithBootstrapButtons.fire(
                          '¡Éxito!',
                          'El correo está disponible.',
                          'success'
                          )
                        }
                          
                      }else{
                          $('#email_2').val('');
                          swalWithBootstrapButtons.fire(
                          '¡Aviso!',
                          'El correo no está disponible.',
                          'warning'
                          )
                      }
                  },
                  complete:function(){
                    $('.preload').hide();
                  }
              });
          });


        });
    </script>
@endsection

{{-- <div class="modal fade" id="updateUser{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Nuevo Usuario</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- form start -->
          <form action="{{ route('usuario.update', $user->id) }}" method="POST" role="form" class="needs-validation" novalidate>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nombres</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Ingresar el nombre completo" required value="{{ old('code', $user->code) }}">
                </div>
                <div class="form-group">
                    <label for="email">Correo</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Ingresar el correo" required value="{{ old('code', $user->code) }}">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Ingresar la contraseña" required>
                </div>
                <div class="form-group">
                    <label for="role_id">Tipo</label>
                    <select name="role_id" id="role_id" class="form-control" required>
                    <option value="">:: SELECCIONE ::</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"  @if ($user->role_id == $role->id) selected @endif>{{ $role->tipo }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="entidad_id">Banco</label>
                    <select name="entidad_id" id="entidad_id" class="form-control" required>
                    <option value="">:: SELECCIONE ::</option>
                    @foreach ($entidads as $entidad)
                        <option value="{{ $entidad->id }}"  @if ($user->entidad_id == $entidad->id) selected @endif>{{ $entidad->alias }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="flestado">Estado</label>
                    <select name="flestado" id="flestado" class="form-control" required>
                    <option value="">:: SELECCIONE ::</option>
                    <option value="1"  @if ($user->flestado == 1) selected @endif>Activo</option>
                    <option value="0"  @if ($user->flestado == 1) selected @endif>Inactivo</option>
                    </select>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                @csrf
                @method('PUT')
                <a href="{{ route('usuario.index') }}" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-primary" id="btnSave">Actualizar</button>
            </div>
        </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal --> --}}