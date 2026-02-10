@extends('layouts.app')
@section('title')
Oficinas
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Oficinas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Oficinas</a></li>
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
              <a href="{{ route('crear-oficina') }}" class="btn btn-block btn-morado">Crear Oficina</a>
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
              <h3 class="card-title">Total de oficinas</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Código Entidad</th>
                  <th>Entidad</th>
                  <th>Código de oficina</th>
                  <th>Tipo de oficina</th>
                  <th>Nombre</th>
                  <th>Número de plaza</th>
                  <th>Nombre plaza</th>
                  <th>Plaza exclusiva</th>
                  <th>Código postal</th>
                  <th>Número de extensión</th>
                  <th>Localidad</th>
                  <th>Pref Tel</th>
                  <th>Teléfono 1</th>
                  <th>Teléfono 2</th>
                  <th>Fax</th>
                  <th>Central telefónica</th>
                  <th>Domicilio</th>
                  <th>Ubigeo Distrital</th>
                  <th>Ubigeo Cheque</th>
                  <th>Ubigeo Trans.</th>
                  <th width="20%"></th>
                </tr>
                </thead>
                <tbody>
                  @if ($oficinas)
                      @foreach ($oficinas as $oficina)
                      <tr>
                        <td>{{ $oficina->entidad->code }}</td>
                        <td>{{ $oficina->entidad->alias }}</td>
                        <td>{{ $oficina->numero }}</td>
                        <td>{{ $oficina->tipooficina->name }}</td>
                        <td>{{ $oficina->name }}</td>
                        <td>{{ $oficina->numeroplaza }}</td>
                        <td>{{ $oficina->nameplaza }}</td>
                        <td>{{ ($oficina->plazaexclusiva == 'S')?"SI":"NO" }}</td>
                        <td>{{ $oficina->codigopostal }}</td>
                        <td>{{ $oficina->numeroextension }}</td>
                        <td>{{ $oficina->localidad }}</td>
                        <td>{{ $oficina->preftelefono }}</td>
                        <td>{{ $oficina->telefono1 }}</td>
                        <td>{{ $oficina->telefono2 }}</td>
                        <td>{{ $oficina->fax }}</td>
                        <td>{{ $oficina->centraltelefonica }}</td>
                        <td>{{ $oficina->domicilio }}</td>
                        <td>{{ $oficina->ubigeodis }}</td>
                        <td>{{ $oficina->ubigeoche }}</td>
                        <td>{{ $oficina->ubigeotra }}</td>
                        <td>
                          <a class="btn bg-gradient-success" href="{{ route('editar-oficina', $oficina) }}">
                            <i class="fas bg-gradient-success fa-edit"></i> Editar
                          </a>
                          @if (session('role_id') == 3)
                              <button class="btn bg-gradient-danger" onclick="eliminarOficinas({{ $oficina->id }});">
                                <i class="fas fa-trash"></i> Eliminar
                              </button>
                          @endif
                          
                        </td>
                      </tr>
                      @endforeach
                  @endif
                  
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

  function eliminarOficinas(idOficina){
      swalWithBootstrapButtons.fire({
        title: '¿Estás seguro de eliminar la oficina?',
        text: "Si decide eliminar, no podrá recuperar los datos de la oficina eliminada",
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
            url:"{{ route('entidad_eliminar_oficinas') }}",
            data:{ idOficina:idOficina },
            success:function(data){
                console.log(data);
                if(data.status == 1){
                    swalWithBootstrapButtons.fire(
                    '¡Éxito!',
                    'La oficina fue eliminada con exito.',
                    'success'
                    )
                    setTimeout(function(){ location.reload(); }, 3000);
                    
                }else{
                    swalWithBootstrapButtons.fire(
                    '¡Aviso!',
                    'No se pudo eliminar la oficina',
                    'warning'
                    )
                }
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
            )
          }
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