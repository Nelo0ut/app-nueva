@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tipo de documentos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Documentos</a></li>
              <li class="breadcrumb-item active">Tipo de Documentos</li>
            </ol>
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
              <button type="button" class="btn btn-block btn-morado" data-toggle="modal" data-target="#modal-default-create">Crear Tipo de documentos</button>
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
              <h3 class="card-title">Lista de tipos de documentos</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                 @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Estado</th>
                  <th width="30%"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($tipoDocumentos as $tipoDocumento)
                        <tr>
                            <td>{{ $tipoDocumento->tipodoc }}</td>
                            <td>
                                @if ($tipoDocumento->flestado == 1)
                                    Activo
                                @else
                                    Inactivo
                                @endif
                            </td>
                            <td><a href="{{ route('tipoDocumento.edit', $tipoDocumento) }}" class="btn btn-app btnEdit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button class="btn btn-app bg-gradient-danger " onclick="eliminarTipoDocumento({{ $tipoDocumento->id }});" ">
                                  <i class="fas fa-trash"></i> Eliminar
                                </button>
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
    <!-- /.content-wrapper -->
    <div class="modal fade" id="modal-default-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Tipo de documento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- form start -->
                    <form action="{{ route('tipoDocumento.store') }}" method="POST" role="form" id="quickForm" class="needs-validation" novalidate>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="tipodoc">Nombre</label>
                                <input type="text" name="tipodoc" class="form-control" id="tipodoc" placeholder="Ingresar el Nombre" required>
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

  function eliminarTipoDocumento(idTipoDocumento){
    swalWithBootstrapButtons.fire({
    title: '¿Estás seguro de eliminar el tipo de documento?',
    text: "Si decide eliminar, no podrá recuperar los datos del tipo de documento",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Si, estoy seguro',
    cancelButtonText: 'No, deseo cancelar',
    reverseButtons: true
  }).then((result) => {
    if (result.value) {
      $.ajax({
      type:'POST',
      url:"{{ route('eliminar_tipo_documento') }}",
      data:{ idTipoDocumento:idTipoDocumento },
      beforeSend:function(){
        $('.preload').show();
      },
      success:function(data){
    if(data.status == 1){
        swalWithBootstrapButtons.fire(
          '¡Éxito!',
          'El tipo de documento fue eliminado con exito.',
          'success'
        )
        setTimeout(function(){ location.reload(); }, 3000);
        
    }else{
        swalWithBootstrapButtons.fire(
          '¡Aviso!',
          'No se pudo eliminar el tipo de documento',
          'warning'
        )
        }
    },
    complete:function(){
    $('.preload').hide();
    }
  });

    swalWithBootstrapButtons.fire(
    '¡Eliminado!',
    'El tipo de documento fue eliminado con éxito',
    'success'
    )
    } else if (
    result.dismiss === Swal.DismissReason.cancel
    ){
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