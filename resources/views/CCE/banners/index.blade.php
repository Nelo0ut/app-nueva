@extends('layouts.app')

@section('content')
<div>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Banners</h1>
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
                    <a href="{{ route('banner.create') }}" class="btn btn-block btn-morado">Crear Banner</a>
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
                    <h3 class="card-title">Banners</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Posición</th>
                                <th>Código</th>
                                <th>Título</th>
                                <th>Subtitulo</th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($banners)
                                @foreach ($banners as $banner)
                                    <tr>
                                        <td>{{ $banner->posicion }}</td>
                                        <td>{{ $banner->id }}</td>
                                        <td>{{ $banner->titulo }}</td>
                                        <td>{{ $banner->subtitulo }}</td>
                                        <td>
                                            <a class="btn btn-app" href="{{ route('banner.edit', $banner) }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <button class="btn btn-app bg-gradient-danger " onclick="eliminarBanner({{ $banner->id }});" >
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
<!-- /.content-wrapper -->
<!-- page script -->

<script src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
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

    function eliminarBanner(idbanner){
        swalWithBootstrapButtons.fire({
        title: '¿Estás seguro de eliminar el banner?',
        text: "Si decide eliminar, no podrá recuperar lo eliminado",
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
                url:"{{ route('eliminar_banner') }}",
                data:{ idbanner:idbanner },
                beforeSend:function(){
                $('.preload').show();
                },
                success:function(data){
                    console.log(data);
                    if(data.status == 1){
                        swalWithBootstrapButtons.fire(
                        '¡Éxito!',
                        'Se eliminó el banner.',
                        'success'
                        )
                        setTimeout(function(){ location.reload(); }, 3000);
                        
                    }else{
                        swalWithBootstrapButtons.fire(
                        '¡Aviso!',
                        'No se pudo eliminar el banner',
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
          )
        }
      })
    }
    $(function () {

    var table = $('#example2').DataTable({
      pageLength: 10,
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: true,
      responsive: true,
      rowReorder: true,
      dom: '<"html5buttons"B>lTfgitp',
      buttons: []
    });

    table.on( 'row-reorder', function ( e, diff, edit ) {
        resuelto = false;
        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
            var rowData=table.row( diff[i].node ).data(); 
            //Aquí va el ajax
            $.ajax({
                type:'POST',
                url:"{{ route('actualizar_posicion_banner') }}",
                data:{ idbanner:rowData[1], posicion: diff[i].newData },
                beforeSend:function(){
                    $('.preload').show();
                },
                success:function(data){
                    resuelto = true;
                },
                complete:function(){
                    $('.preload').hide();
                }
            });
            // console.log(result);
        }

        if(resuelto){
            swalWithBootstrapButtons.fire(
            '¡Éxito!',
            'Se actualizó la posición del banner.',
            'success'
            )
        }
    });
  });
</script>

@endsection