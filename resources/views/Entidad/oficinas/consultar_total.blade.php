@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Mostrar Todas las Oficinas</h1>
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
              <form action="{{ route('filtrar_oficinas') }}" method="POST" role="form" id="quickForm" class="needs-validation"  novalidate>
                @csrf
                <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="entidad1_id">Entidad</label>
                    <select class="form-control select2 select2-purple" data-dropdown-css-class="select2-purple" style="width: 100%;"
                        required id="entidad1_id" name="entidad_select" >
                        <option value="">:: Seleccione ::</option>
                        @foreach ($entidads as $entidad)
                        <option value="{{ $entidad->id }}" {{ old('entidad1_id') == $entidad->id ? 'selected' : '' }}>
                            {{ $entidad->alias }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                  <label for="numero">Código de oficina </label>
                  <input type="search" class="form-control form-control-sm valnumero" name="numero" placeholder="" aria-controls="example1ee" style="height:40px" required>
                </div>
                <div class="form-group col-md-3">
                  <label for="codigo">Numero de plaza </label>
                  <input type="search" class="form-control form-control-sm valnumero" name="codigo_entidad" placeholder="" aria-controls="example1ee" style="height:40px" required>
                </div>
                <div class="form-group col-md-3 pt-4">
                  <button type="submit" class="btn btn-morado mt-2 mr-2">Filtrar</button>
                  <a href="{{route('descargar_txt_oficinas_ent')}}" target="_blank" class="btn btn-naranja mt-2 mr-2">Exportar TXT</a>
                  <a href="{{route('descargar_excel_oficinas_ent')}}" target="_blank" class="btn btn-success mt-2 mr-2">Exportar Excel</a>
              </div>
              </div>
              </form>
              <div class="row mb-3">
                <div class="col-12">
                    @include('includes.form-error')
                    @include('includes.mensajes')
                </div>
            </div>
            <script>

            </script>  
              <hr>
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
                </tr>
                </thead>
                <tbody>
                  
                  @if($activar_filtro)
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
  $(function () {
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