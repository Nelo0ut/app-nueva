@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Agendar</h1>
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
                    <a href="{{ route('agendar.create') }}" class="btn btn-block btn-morado">Agendar</a>
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
                    <h3 class="card-title">Agendados</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Usuario</th>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Fecha agendada</th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($agendas)
                                @foreach ($agendas as $agenda)
                                    <tr>
                                        <td>{{ $agenda->id }}</td>
                                        <td>{{ $agenda->user->name }}</td>
                                        <td>{{ $agenda->name }}</td>
                                        <td>{{ $agenda->description }}</td>
                                        <td>{{ $agenda->feinicio }} - {{ $agenda->fefin }}</td>
                                        <td>
                                            <button class="btn btn-app bg-gradient-success " onclick="verBancos({{ $agenda->id }});">
                                                <i class="fas fa-eye"></i> Ver Bancos
                                            </button>
                                            <a class="btn btn-app" href="{{ route('agendar.edit', $agenda) }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            @php date_default_timezone_set("America/Lima"); @endphp
                                            @if (strtotime($agenda->feinicio) > strtotime(date('Y-m-d H:i:s')))
                                                <button class="btn btn-app bg-gradient-danger" onclick="eliminarDocumentos({{ $agenda->id }})">
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

    
    function verBancos(id){
        listaBancos = "<ul>";
        $.ajax({
            type:'POST',
            url:"{{ route('getbancosxagenda') }}",
            data:{ id: id },
            beforeSend:function(){
            $('.preload').show();
            },
            success:function(data){
                if(data.length > 0){
                    for (let i = 0; i < data.length; i++) { listaBancos +="<li>" + data[i].name + " / " + data[i].alias + "</li>" ; }
                        listaBancos +="</ul>" ; Swal.fire({ title: '<strong>¡Lista de Bancos!</strong>' , icon: 'info' , html:
                        listaBancos, showCloseButton: true, showCancelButton: true, focusConfirm: false, cancelButtonText: 'Cerrar' ,
                        cancelButtonAriaLabel: 'Thumbs down' }) 
                }else{ swalWithBootstrapButtons.fire( '¡Aviso!'
                        , 'El documento no tienen bancos designados.' , 'error' ) 
                } 
            },
            complete:function(){
            $('.preload').hide();
            } 
        }); 
    }
    
    function eliminarDocumentos(idagenda){
        swalWithBootstrapButtons.fire({
        title: '¿Estás seguro de eliminar lo agendado?',
        text: "Si decide eliminar, no podrá recuperar lo eliminados",
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
                url:"{{ route('eliminar_agenda') }}",
                data:{ idagenda:idagenda },
                beforeSend:function(){
                    $('.preload').show();
                },
                success:function(data){
                    console.log(data);
                    if(data.status == 1){
                        swalWithBootstrapButtons.fire(
                        '¡Éxito!',
                        'Se elimino la agenda.',
                        'success'
                        )
                        setTimeout(function(){ location.reload(); }, 3000);
                        
                    }else{
                        swalWithBootstrapButtons.fire(
                        '¡Aviso!',
                        'No se pudo eliminar la agenda',
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
            'Lo agendado fue eliminado con éxito',
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