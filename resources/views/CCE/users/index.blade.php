@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Usuarios</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="row">
        <div class="col-12 col-md-3">

          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <button type="button" class="btn btn-block btn-morado" data-toggle="modal" data-target="#modal-default">Crear Usuario</button>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de usuarios</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                @include('includes.form-error')
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Tipo</th>
                        <th>Banco</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->tipo }}</td>
                            <td>{{ $user->entidad->alias }}</td>
                            <td>
                                @if ($user->flestado == 1)
                                    Activo
                                @else
                                    Inactivo
                                @endif
                            </td>
                            <td>
                              <a href="{{ route('user.edit', $user->id) }}" class="btn btn-app btnEdit">
                                <i class="fas fa-edit"></i> Editar
                              </a>
                              @if($user->role->id == 4)
                              <button class="btn btn-app bg-gradient-danger" onclick="eliminarUsuario({{ $user->id }});">
                                <i class="fas fa-trash"></i> Eliminar 
                              </button>
                              @endif
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
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
          <form action="{{ route('user.store') }}" method="POST" role="form" id="quickForm" autocomplete="off" class="needs-validation" novalidate >
            <div class="card-body">
              <div class="form-group">
                <label for="entidad_id">Banco</label>
                <select name="entidad_id" id="entidad_id" class="form-control" required>
                  <option value="">:: SELECCIONE ::</option>
                  @foreach ($entidads as $entidad)
                    <option value="{{ $entidad->id }}">{{ $entidad->alias }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="name">Nombres</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Ingresar el nombre completo" required>
              </div>
              <div class="form-group">
                <label for="email">Correo</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Ingresar el correo" required >
              </div>
              <div class="form-group">
                <label for="email_2">Correo N°2 <small>(Opcional)</small></label>
                <input type="email" name="email_2" class="form-control" id="email_2" placeholder="Ingresar el correo" >
              </div>
              <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" class="form-control" id="usuario" placeholder="Ingresar el usuario" maxlength="15" minlength="5" required>
              </div>
              <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Ingresar la contraseña" required>
              </div>
              <div class="form-group">
                <label for="role_id">Tipo</label>
                <select name="role_id" id="role_id" class="form-control" required>
                  <option value="">:: SELECCIONE ::</option>
                  {{-- @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->tipo }}</option>
                  @endforeach --}}
                </select>
              </div>
              <div class="form-group">
                <label for="flestado">Estado</label>
                <select name="flestado" id="flestado" class="form-control" required>
                  <option value="">:: SELECCIONE ::</option>
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                @csrf
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
<script>
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: 'btn btn-morado',
    cancelButton: 'btn btn-naranja'
    },
    buttonsStyling: false
  });
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

  function eliminarUsuario(idUsuario){
    swalWithBootstrapButtons.fire({
    title: '¿Estás seguro de eliminar el usuario?',
    text: "Si decide eliminar, no podrá recuperar el usuario eliminado",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Si, estoy seguro',
    cancelButtonText: 'No, deseo cancelar',
    reverseButtons: true
  }).then((result) => {
  if (result.value) {

  $.ajax({
    type:'POST',
    url:"{{ route('eliminar_usuario') }}",
    data:{ idUsuario:idUsuario },
    beforeSend:function(){
      $('.preload').show();
    },
    success:function(data){
    if(data.status == 1){
      swalWithBootstrapButtons.fire(
        '¡Éxito!',
        'Se elimino el usuario.',
        'success'
      )
      setTimeout(function(){ location.reload(); }, 3000);
  }else{
  swalWithBootstrapButtons.fire(
    '¡Aviso!',
    'No se pudo eliminar el evento',
    'warning'
  )}
  },
  complete:function(){
    $('.preload').hide();
  }
  });

  swalWithBootstrapButtons.fire(
    '¡Eliminado!',
    'El usuario fue eliminado con éxito',
    'success'
  )
  } else if (
  /* Read more about handling dismissals below */
  result.dismiss === Swal.DismissReason.cancel
  ) {
  swalWithBootstrapButtons.fire(
    '¡Cancelado!',
    'La operación fue cancelada',
    'error'
  )}
  })
  }


$(function () {
  $('#example2').DataTable({
    pageLength: 10,
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    dom: '<"html5buttons"B>lTfgitp',
    buttons: ['excel']
  });

  $('#email').blur(function() {
      email = $(this).val();
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
          url:"{{ route('getcorreouser') }}",
          data:{ email:email },
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
                else{
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
        $.ajax({
            type:'POST',
            url:"{{ route('getcorreoalternativouser') }}",
            data:{ email_2:email_2 },
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

  $('#usuario').blur(function() {
      usuario = $(this).val();
      //Aquí va el ajax
      $.ajax({
          type:'POST',
          url:"{{ route('getusuariouserent') }}",
          data:{ usuario:usuario },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
              console.log(data);
              if(data.existe == 0){
                  swalWithBootstrapButtons.fire(
                  '¡Éxito!',
                  'El usuario está disponible.',
                  'success'
                  )
                  
              }else{
                  $('#usuario').val('');
                  swalWithBootstrapButtons.fire(
                  '¡Aviso!',
                  'El usuario no está disponible.',
                  'warning'
                  )
              }
          },
          complete:function(){
            $('.preload').hide();
          }
      });
  });

  $('#entidad_id').change(function(){
    let entidadValor = $('#entidad_id').val();
    if(entidadValor != '' ){

      $.ajax({
        type: 'POST',
        url: "{{ route('cargar_rol_tipo')  }}",
        data: { entidadValor: entidadValor },

        beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            if(data.existe == 0){
              $('#role_id').html(
                `
                  <option value="">:: SELECCIONE ::</option>
                  <option value="3">Administrador</option>
                  <option value="4">Funcionario de enlace</option>  
                `
                );
            }else {
              $('#role_id').html(
                `
                  <option value="">:: SELECCIONE ::</option>
                  <option value="4">Funcionario de enlace</option>  
                `
                );
            }

          },
          complete:function(){
          $('.preload').hide();
          }
      })
    }else {
      $('#entidad_id').val('');
      $('#role_id').html(
                `<option value="">:: SELECCIONE ::</option>`
                );
    }
  });

  $('#role_id').click(function(){
    if($('#entidad_id').val()== ''){
      swalWithBootstrapButtons.fire(
        '¡Aviso!',
        'Debe seleccionar un banco primero',
        'warning'
        )
    }
  })
});
</script>
@endsection