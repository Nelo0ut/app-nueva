@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Eventos</h1>
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
                    <a href="{{ route('evento.create') }}" class="btn btn-block btn-morado">Crear evento</a>
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
                    <h3 class="card-title">Eventos</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Usuario</th>
                                <th>Fecha y Hora</th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($eventos)
                                @foreach ($eventos as $evento)
                                    <tr>
                                        <td>{{ $evento->id }}</td>
                                        <td>{{ $evento->titulo }}</td>
                                        <td>{{ $evento->user->name }}</td>
                                        {{-- <td>{{ $evento->titulo }}</td> --}}
                                        <td>{{ $evento->fecha }}</td>
                                        <td>
                                            <button class="btn btn-app bg-gradient-success" onclick="verBancos({{ $evento->id }});" >
                                                <i class="fas fa-eye"></i> Ver Bancos
                                            </button>
                                            <a class="btn btn-app" href="{{ route('evento.edit', $evento) }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <button class="btn btn-app bg-gradient-danger" onclick="eliminarEvento({{ $evento->id }});">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
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
          url:"{{ route('getbancosxevento') }}",
          data:{ id: id },
          success:function(data){
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
          }
      });
    }

    function eliminarEvento(idevento){
        swalWithBootstrapButtons.fire({
        title: '¿Estás seguro de eliminar el evento?',
        text: "Si decide eliminar, no podrá recuperar el evento eliminado",
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
                url:"{{ route('eliminar_evento') }}",
                data:{ idevento:idevento },
                success:function(data){
                    console.log(data);
                    if(data.status == 1){
                        swalWithBootstrapButtons.fire(
                        '¡Éxito!',
                        'Se elimino el evento.',
                        'success'
                        )
                        setTimeout(function(){ location.reload(); }, 3000);
                        
                    }else{
                        swalWithBootstrapButtons.fire(
                        '¡Aviso!',
                        'No se pudo eliminar el evento',
                        'warning'
                        )
                    }
                }
            });

          swalWithBootstrapButtons.fire(
            '¡Eliminado!',
            'El evento fue eliminado con éxito',
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

