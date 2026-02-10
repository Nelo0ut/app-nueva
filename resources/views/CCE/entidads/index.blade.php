@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Entidad</h1>
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
              <button type="button" class="btn btn-block btn-morado" data-toggle="modal" data-target="#modal-default-create">Crear Entidad</button>
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
              <h3 class="card-title">Lista de entidades</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-12">
                  @include('includes.form-error')
                  @include('includes.mensajes')
                </div>
              </div>
                 @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Código de entidad</th>
                  <th>Nombre</th>
                  <th>Estado</th>
                  <th width=30%></th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($entidads as $entidad)
                        <tr>
                            <td>{{ $entidad->code }}</td>
                            <td>{{ $entidad->alias }}</td>
                            <td>
                                @if ($entidad->flestado == 1)
                                    Activo
                                @else
                                    Inactivo
                                @endif
                            </td>
                            <td><a href="{{ route('entidad.edit', $entidad) }}" class="btn btn-app btnEdit" data-id="{{ $entidad->id }}">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button class="btn btn-app bg-gradient-danger " onclick="eliminarEntidades({{ $entidad->id }});">
                                  <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
              <div class="mt-3 d-flex justify-content-center">
                {{ $entidads->links() }}
          </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content-wrapper -->
    <div class="modal fade" id="modal-default-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nueva Entidad</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- form start -->
                    <form action="{{ route('entidad.store') }}" method="POST" role="form" id="quickForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="code">Código de entidad</label>
                                <input type="text" name="code" class="form-control valnumero" id="code" placeholder="Ingresar el número" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Ingresar el nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="alias">Alias</label>
                                <input type="text" name="alias" class="form-control" id="alias" placeholder="Ingresar el Alias" required>
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
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @csrf
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="btnSave">Guardar</button>
                        </div>
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

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-morado',
        cancelButton: 'btn btn-naranja'
      },
      buttonsStyling: false
    });

    function eliminarEntidades(idEntidad){
      swalWithBootstrapButtons.fire({
      title: '¿Estás seguro de eliminar la entidad?',
      text: "Si decide eliminar, no podrá recuperar los datos de la entidad",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Si, estoy seguro',
      cancelButtonText: 'No, deseo cancelar',
      reverseButtons: true
    }).then((result) => {
    if (result.value) {

      //Aquí va el ajax
        $.ajax({
          type:'POST',
          url:"{{ route('eliminar_entidad') }}",
          data:{ idEntidad:idEntidad },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            if(data.status == 1){
                swalWithBootstrapButtons.fire(
                '¡Éxito!',
                'La entidad fue eliminada con exito.',
                'success'
                )
                setTimeout(function(){ location.reload(); }, 3000);
            }else{
                swalWithBootstrapButtons.fire(
                '¡Aviso!',
                'No se pudo eliminar la entidad',
                'warning'
                )
            }
          },
          complete:function(){
            $('.preload').hide();
          }
        });
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
});
</script>
@endsection