@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Documentos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Documentos</a></li>
              <li class="breadcrumb-item active">Consultar</li>
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
              <a href="{{ route('documento.create') }}" class="btn btn-block btn-morado">Crear Documento</a>
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
              <h3 class="card-title">Lista de documentos</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Código</th>
                  <th>Documento</th>
                  <th>Tipo</th>
                  <th>Registro</th>
                  <th width="300"></th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($documentos as $documento)
                    <tr>
                      <td>{{ $documento->id }}</td>
                      <td>{{ $documento->name }}</td>
                      <td>{{ $documento->tipoDocumento->tipodoc }}</td>
                      <td>{{ $documento->created_at }}</td>
                      <td>
                        <button class="btn btn-app bg-gradient-success" onclick="verBancos({{ $documento->id }});" >
                          <i class="fas fa-eye"></i> Ver Bancos
                        </button>
                        <a class="btn btn-app" href="{{ route('documento.edit', $documento) }}">
                          <i class="fas fa-edit"></i> Editar
                        </a>
                        <button class="btn btn-app bg-gradient-danger " onclick="eliminarDocumentos({{ $documento->id }});">
                          <i class="fas fa-trash"></i> Eliminar
                        </button>
                        {{-- <button class="btn btn-app bg-gradient-info descargarDocumnentos" data-id="{{ $documento->id }}">
                          <i class="fas fa-download"></i> Descargar --}}
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
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<!-- page script -->
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

    function verBancos(id){

      listaBancos = "<ul>";
      $.ajax({
          type:'POST',
          url:"{{ route('getbancos') }}",
          data:{ id: id },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            console.log(data);
            if(data.length > 0){
                for (let i = 0; i < data.length; i++) {
                    listaBancos += "<li>" + data[i].name + " / " + data[i].alias + "</li>";
                }
                listaBancos += "</ul>";
                Swal.fire({
                    title: '<strong>¡Lista de Bancos!</strong>',
                    icon: 'info',
                    html: listaBancos,
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    cancelButtonText:
                        'Cerrar',
                    cancelButtonAriaLabel: 'Thumbs down'
                })
            }else{
                swalWithBootstrapButtons.fire(
                    '¡Aviso!',
                    'El documento no tienen bancos designados.',
                    'error'
                )
            }
          },
          complete:function(){
            $('.preload').hide();
          }
      });
    }

    function eliminarDocumentos(idDocumento){
      swalWithBootstrapButtons.fire({
        title: '¿Estás seguro de eliminar el documento?',
        text: "Si decide eliminar, no podrá recuperar los documentos eliminados",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, estoy seguro',
        cancelButtonText: 'No, deseo cancelar',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {//Aquí va el ajax
          $.ajax({
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type:'POST',
            url:"{{ route('eliminar_documento') }}",
            data:{ idDocumento:idDocumento },
            beforeSend:function(){
              $('.preload').show();
            },
            success:function(data){
              if(data.status == 1){
                  swalWithBootstrapButtons.fire(
                  '¡Éxito!',
                  'El documento fue eliminado con éxito',
                  'success'
                  );
                  setTimeout(function(){ location.reload(); }, 3000);
                  
              }else{
                  swalWithBootstrapButtons.fire(
                  '¡Aviso!',
                  'No se pudo eliminar el documento',
                  'warning'
                  );
              }
            },
            complete:function(){
              $('.preload').hide();
            }
          });
        }else if (result.dismiss === Swal.DismissReason.cancel) {
          swalWithBootstrapButtons.fire(
            '¡Cancelado!',
            'La operación fue cancelada',
            'error'
          );
        }
      });
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