@extends('layouts.app')

@section('content')
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset("moment/moment.min.js") }}"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Bitacoras de ingreso</h1>
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
          <h3 class="card-title">Lista de logins</h3>
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
                <th>Banco</th>
                <th>Perfil</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Fecha y hora</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($bitacoras as $bitacora)
              <tr>
                <td>{{ $bitacora->alias }}</td>
                <td>{{ $bitacora->tipo }}</td>
                <td>{{ $bitacora->name }}</td>
                <td>{{ $bitacora->email }}</td>
                <td>{{ $bitacora->usuario }}</td>
                <td>{{ $bitacora->created_at }}</td>
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
<script>
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
