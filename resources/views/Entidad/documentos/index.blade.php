@extends('layouts.app')
@section('title')
Documentos
@endsection
@section('content')
<style>
  .accent-purple .btn-link, .accent-purple
  a:not(.dropdown-item):not(.btn-app):not(.nav-link):not(.brand-link):not(.page-link) {
  color: #3c8dbc;
  }
</style>
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
                  <th width="30%"></th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($documentos as $documento)
                    <tr>
                      <td>{{ $documento->id }}</td>
                      <td>{{ $documento->name }} - {{ $documento->nombre_doc }}</td>
                      <td>{{ $documento->tipodoc }}</td>
                      <td>{{ $documento->created_at }}</td>
                      <td>
                        @if (explode(".",$documento->documento)[1] == "doc" ||
                        explode(".",$documento->documento)[1] == "docx" ||
                        explode(".",$documento->documento)[1] == "xls" ||
                        explode(".",$documento->documento)[1] == "xlsx" ||
                        explode(".",$documento->documento)[1] == "pdf")
                          <a href="{{ asset($documento->documento) }}" target="_blank">Descargar</a>
                        @elseif(explode(".",$documento->documento)[1] == "pdf")
                          
                          <a href="{{ asset($documento->documento) }}" target="_blank">Abrir</a>
                        @else
                          <a href="javascript:void(0);" data-img="{{ asset($documento->documento) }}" onclick="verImg();">Ver imagen</a>
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
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Ver imagen</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card">
            <img src="" id="imgModalDoc" alt="" class="img-fluid">
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
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

  function verImg(){
    img = $(this).data('img');
    $('#imgModalDoc').removeAttr('src').attr('src', img);
    $('#modal-default').modal('show');
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