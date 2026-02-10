@extends('layouts.app')
@section('title','Eventos')

@section('styles')
    <link href="{{ asset('daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script src="{{ asset('daterangepicker/daterangepicker.js') }}"></script>
@endsection

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

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-12 col-md-6">
            <!-- general form elements -->
            <div class="card card-morado">
                <div class="card-header">
                    <h3 class="card-title">Escoger Bancos</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if ($errors->all())
                        <div class="alert alert-warning mb-3" role="alert">
                            @foreach ($errors->all() as $message)
                                {{ $message }}
                            @endforeach
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="msgEntidad" style="display:none;">
                        <div class="alert alert-warning mb-3" role="alert">Debe escoger al menos un banco.</div>
                    </div>
                    <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimary0" @if (count($entidads) == count($entxage)) checked @endif>
                                        <label for="checkboxPrimary0">
                                            Seleccionar todo
                                        </label>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($entidads)
                            @foreach ($entidads as $entidad)
                            <tr>
                                <td>{{ $entidad->code }}</td>
                                <td>{{ $entidad->alias }}</td>
                                <td>
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline check_todos">
                                            <input type="checkbox" id="checkboxPrimary{{ $entidad->id }}" value="{{ $entidad->id }}" @if (in_array($entidad->id, $entxage)) checked @endif>
                                            <label for="checkboxPrimary{{ $entidad->id }}"></label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Flayer del Evento</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($evento->flayer)
                                        <tr>
                                            <td>
                                                    <a href="javascript:void(0);" data-img="{{ asset('/storage/'.$evento->flayer) }}" class="verImg"><img src="{{ asset('/storage/'.$evento->flayer) }}" alt="" class="img-fluid" ></a>
                                            </td>
                                            <td>
                                                <button class="btn btn-app bg-gradient-danger eliminarDocumentos" data-id="{{ $evento->id }}">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-12 col-md-6">
            <!-- general form elements -->
            <div class="card card-morado">
                <div class="card-header">
                    <h3 class="card-title">Datos</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('evento.update',$evento) }}" method="POST" enctype="multipart/form-data"
                        role="form" id="quickForm" class="needs-validation" novalidate>
                            
                        <div class="hiddenInput">
                                @if ($evento->entidads)
                                    @foreach ($evento->entidads as $entidad)
                                        <input type="hidden" value="{{ $entidad->id }}" name="entidad_id[]" id="entidad_id_{{ $entidad->id }}">
                                    @endforeach
                                @endif
                            </div>
                        <div class="form-group">
                            <label for="titulo">Título *</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" value="{{ old('titulo', $evento->titulo)}}" required
                                placeholder="Ingrese el Nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Horario:</label>
                        
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                            <input type="text" class="form-control float-right" id="reservationtime" name="fechainifin" value="{{ old('fechainifin',$evento->fechainifin)}}">
                            </div>
                            <!-- /.input group -->
                        <!-- /.form group -->
                        <div class="form-group">
                            <label for="exampleInputFile">Flayer</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="documentos" id="documentos"  accept=".jpeg,.jpg,.png" >
                                    <label class="custom-file-label" for="documentos" data-browse="Escoger"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-titles" style="display:none">
                            <div class="form-group">
                            </div>
                        </div>
                        <div class="form-group">
                            @csrf
                        @method('PUT')
                            <button type="submit" class="btn btn-morado" id="submit-all">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content-wrapper -->


<!-- /.content -->
<!-- page script -->
<script type="text/javascript">

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
                    form.classList.add('was-validated');
                    event.preventDefault();
                    event.stopPropagation();
                    $('#submit-all').removeAttr('disabled');
                }
                else{
                    event.preventDefault();
                    event.stopPropagation();
                    $('#submit-all').attr('disabled','disabled');
                    if($('input[name="entidad_id[]"]').length > 0){
                        $('form').submit();
                    }else{
                        $('.msgEntidad').show();
                        $('#submit-all').removeAttr('disabled');
                    }
                }
                form.classList.add('was-validated');
                }, false);
            });
            }, false);
        })();
        
        // Dropzone.autoDiscover = false;
        // var myDropzone;
        $(function () {
            $('.verImg').click(function () {
                img = $(this).data('img');
                $('#imgModalDoc').removeAttr('src').attr('src', img);
                $('#modal-default').modal('show');
            });
            $('#documentos').change(function() {
                files = $(this)[0].files;
                if(files.length == 1){
                    addInput = '<label for="name_doc">Nombre del documento *</label>';
                }else{
                    addInput = '<label for="name_doc">Nombres de los documentos *</label>';
                }
                $(this).next('.custom-file-label').html('');
                
                msgInput = '<div class="alert alert-warning" role="alert">';
                a = 0; b = 0;
                for (let i = 0; i < files.length; i++) {
                    size = (files[i].size / 1024 / 1024).toFixed(2);
                    console.log(files[i].name.split(".")[1].toLowerCase());
                    console.log($.inArray(files[i].name.split(".")[1].toLowerCase(),['jpg','png','jpeg','gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf']));
                    if(size <= 10 && $.inArray(files[i].name.split(".")[1].toLowerCase(),['jpg','png','jpeg','gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf']) >= 0){
                        addInput += '<input type="text" name="name_doc[]" class="form-control mb-3" value="'+ files[i].name.split(".")[0] +'" placeholder="Ingrese el Nombre" required>';
                        a++;
                        $(this).next('.custom-file-label').append(files[i].name.split(".")[0].toLowerCase() + ', ');
                    }else{
                        msgInput +=  'El archivo: ' + files[i].name + ' pesa más de 10 MB o no es un tipo de archivo permitido.<br>';
                        //files[i].replaceWith(files[i].val('').clone(true));
                        //console.log(files[i]);
                        b++;
                    }
                }
                console.log("a: " + a + " / total: " + files.length);
                if(a == files.length){
                    $('.form-titles > .form-group').html(addInput);
                    $('.form-titles > .alert').remove();
                    $('.form-titles').show();
                }
                else if(a > 0 && a < files.length){
                    $('.form-titles > .form-group').html(addInput);
                    $('.form-titles > .alert').remove();
                    $('.form-titles').append(msgInput + '</div>');
                    $('.form-titles').show();
                }
                
                if(b > 0){
                    msgInput += 'Escoger nuevamente los documentos.<br>';
                    $('.form-titles > .form-group').html('');
                    $('.form-titles > .alert').remove();
                    $('.form-titles').show();
                    $(this).next('.custom-file-label').html('');
                    $(this).replaceWith($(this).val('').clone(true));
                }
                // files.each(function() {
                //     console.log($(this).name);
                // });
                
            });
            $('#reservationtime').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'YYYY-MM-DD hh:mm A'
                }
            })
            $('#example').DataTable( {
                "scrollY":        "310px",
                "scrollCollapse": true,
                "paging":         false
            } );
            $("#checkboxPrimary0").change(function () {
                if($(this).is(':checked')){
                    $('.hiddenInput').html('');
                    $(".check_todos input").each(function(){
                        $('.hiddenInput').append('<input type="hidden" name="entidad_id[]" value="'+$(this).val()+'" id="entidad_id_'+$(this).val()+'">');
                    });
                    $('.msgEntidad').hide();
                }else{
                    $('.hiddenInput').html('');
                    $('.msgEntidad').show();
                }
                $(".check_todos input:checkbox").prop('checked', $(this).prop("checked"));
            });

            $(".check_todos input").change(function () {
                if($(this).is(':checked')){
                    $('.hiddenInput').append('<input type="hidden" name="entidad_id[]" value="'+$(this).val()+'" id="entidad_id_'+$(this).val()+'">');
                    $('.msgEntidad').hide();
                }else{
                    $('#entidad_id_'+$(this).val()).remove();
                }
            });
        });
        const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-morado',
        cancelButton: 'btn btn-naranja'
      },
      buttonsStyling: false
    });

    $('.eliminarDocumentos').click(function() {
        idevento = $(this).data("id");

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
                url:"{{ route('eliminarFlayerEvento') }}",
                data:{ idevento:idevento },
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
    });

        function slugify(string) {
            return string
                .toString()
                .trim()
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "")
                .replace(/\-\-+/g, "-")
                .replace(/^-+/, "")
                .replace(/-+$/, "");
        }

        
</script>
@endsection