@extends('layouts.app')
@section('title')
Registrar documentos
@endsection
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
            <h1>Importar documentos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Documentos</a></li>
              <li class="breadcrumb-item active">Importar</li>
            </ol>
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
                        <div class="alert alert-warning alert-dismissable mb-3" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <div class="alert-text">
                                @foreach ($errors->all() as $message)
                                {{ $message }}
                                @endforeach
                            </div>
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
                                <input type="checkbox" id="checkboxPrimary0">
                                <label for="checkboxPrimary0">
                                Seleccionar todo
                                </label>
                            </div>
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entidads as $entidad)
                            <tr>
                                <td>{{ $entidad->code }}</td>
                                <td>{{ $entidad->alias }}</td>
                                <td>
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline check_todos">
                                        <input type="checkbox" id="checkboxPrimary{{ $entidad->id }}" value="{{ $entidad->id }}">
                                        <label for="checkboxPrimary{{ $entidad->id }}"></label>
                                    </div>
                                    </div>
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
            <div class="col-12 col-md-6">
                <!-- general form elements -->
                <div class="card card-morado">
                    <div class="card-header">
                        <h3 class="card-title">Datos</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('documento.store') }}" method="POST" enctype="multipart/form-data" role="form" id="quickForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="hiddenInput"></div>
                            <div class="form-group">
                                <label for="name">Nombre del documento *</label>
                                <input type="text" id="name" name="name" class="form-control" value="" required placeholder="Ingrese el Nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="tipodocumento_id">Tipo de documento</label>
                                <select class="form-control custom-select" id="tipodocumento_id" name="tipodocumento_id" required>
                                    <option disabled>:: SELECCIONE ::</option>
                                    @foreach ($tipoDocumentos as $tipodocumento)
                                        <option value="{{ $tipodocumento->id }}">{{ $tipodocumento->tipodoc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- checkbox -->
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="enviarnotificiacion" name="enviar_notification" value="1">
                                    <label for="enviarnotificiacion">Enviar notificación por correo electrónico</label>
                                </div>
                            </div>
                            <!-- Date dd/mm/yyyy -->
                            {{-- <div class="form-group" id="FechaEnvio" style="display:none;">
                                <label>Horario de agenda:</label>
                            
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="enviarfechahora" name="feinsert">
                                </div>
                                <!-- /.input group -->
                            </div> --}}
                            <!-- /.form group -->
                            <div class="form-group">
                                <label for="exampleInputFile">Archivos a importar</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="documentos[]" id="documentos" required multiple accept=".jpeg,.jpg,.png,.gif, .doc, .docx, .xls, .xlsx, .pdf">
                                        <label class="custom-file-label" for="documentos" data-browse="Escoger"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-titles" style="display:none">
                                <div class="form-group">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-morado" id="submit-all">Subir archivo</button>
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
    <script  type = "text/javascript">
        (function() {
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

            $('#enviarfechahora').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePickerIncrement: true,
                locale: {
                    format: 'YYYY-MM-DD hh:mm A'
                }
            });

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

            // $('#enviarnotificiacion').change(function () {
            //     if($(this).is(':checked')){
            //         $('#FechaEnvio').show();
            //         $('#datemask').attr('required', 'required');
            //     }else{
            //         $('#FechaEnvio').hide();
            //         $('#datemask').removeAttr('required');
            //     }
            // });

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
                    if(size <= 10 && $.inArray(openFile(files[i].name),['jpg','png','jpeg','gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf']) >= 0){
                        addInput += '<input type="text" name="name_doc[]" class="form-control mb-3" value="'+ openFileName(files[i].name) +'" placeholder="Ingrese el Nombre" required>';
                        a++;
                        $(this).next('.custom-file-label').append(openFileName(files[i].name) + ', ');
                    }else{
                        msgInput +=  'El archivo: ' + files[i].name + ' pesa más de 10 MB o no es un tipo de archivo permitido.<br>';
                        //files[i].replaceWith(files[i].val('').clone(true));
                        //console.log(files[i]);
                        b++;
                    }
                }
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

            // myDropzone = new Dropzone("#my_awesome_dropzone",{
            //     url: "{{ route('documento.store') }}",
            //     uploadMultiple: false,
            //     maxFilesize: 12,
            //     renameFile: function(file) {
            //         var dt = new Date();
            //         var time = dt.getTime();
            //         console.log(time+slugify(file.name));
            //         return time+slugify(file.name);
            //     },
            //     init: function() {
            //         dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

            //         // for Dropzone to process the queue (instead of default form behavior):
            //         document.getElementById("submit-all").addEventListener("click", function(e) {
            //             // Make sure that the form isn't actually being sent.
            //             e.preventDefault();
            //             e.stopPropagation();
            //             dzClosure.processQueue();
            //         });

            //         //send all the form data along with the files:
            //         this.on("sendingmultiple", function(data, xhr, formData) {
            //             formData.append("_token", "{{ csrf_token() }}");
            //             formData.append("entidad_id", $("#entidad_id").val());
            //             formData.append("name", $("#name").val());
            //             formData.append("tipodocumento_id", $("#tipodocumento_id").val());
            //             formData.append("feinsert", $("#datemask").val());
            //             formData.append("enviarnotificiacion", $("#enviarnotificiacion").val());
            //             console.log(formData);
            //         });
            //     },
            //     autoProcessQueue: false,
            //     addRemoveLinks: true,
            //     acceptedFiles: ".jpeg,.jpg,.png,.gif, .doc, .docx, .xls, .xlsx, .pdf",
            //     paramName: "file", // The name that will be used to transfer the file
            //     maxFilesize: 10, // MB
            //     sending: function(file, xhr, formData) {
            //         formData.append("_token", "{{ csrf_token() }}");
            //         formData.append("entidad_id", $("#entidad_id").val());
            //         formData.append("name", $("#name").val());
            //         formData.append("tipodocumento_id", $("#tipodocumento_id").val());
            //         formData.append("feinsert", $("#datemask").val());
            //         formData.append("enviarnotificiacion", $("#enviarnotificiacion").val());
            //         console.log(formData);
            //     },
            //     success: function (file, response) {
            //         console.log(response);
            //     },
            //     error: function (file, response) {
            //         console.log(response);
            //         return false;
            //     }
            // });
            

            // $('#submit-all').click(function(formData) {
            //     var count= myDropzone.files.length;
            //     if(count > 0){
            //         myDropzone.processQueue();
            //     }
            // });

        });

        function openFile(file) {
            var extension = file.substr( (file.lastIndexOf('.') +1) ).toLowerCase();
            return extension;
        };

        function openFileName(file) {
            var name = file.substr( 0,file.lastIndexOf('.') ).toLowerCase();
            return name;
        };

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

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        // (function() {
        //     'use strict';
        //     window.addEventListener('load', function() {
        //     // Fetch all the forms we want to apply custom Bootstrap validation styles to
        //     var forms = document.getElementsByClassName('needs-validation');
        //     // Loop over them and prevent submission
        //     var validation = Array.prototype.filter.call(forms, function(form) {
        //         form.addEventListener('submit', function(event) {
        //         if (form.checkValidity() === false) {
        //             form.classList.add('was-validated');
        //             event.preventDefault();
        //             event.stopPropagation();
        //             $('#submit-all').removeAttr('disabled');
        //         }else{
        //             event.preventDefault();
        //             event.stopPropagation();
        //             $('#submit-all').attr('disabled','disabled');
        //             var count= myDropzone.files.length;
        //             if(count > 0){
        //                 myDropzone.processQueue();
        //             }else{
        //                 $('#submit-all').removeAttr('disabled');
        //             }
        //         }
        //         form.classList.add('was-validated');
        //         }, false);
        //     });
        //     }, false);
        // })();
    </script>
@endsection