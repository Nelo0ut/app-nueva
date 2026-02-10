@extends('layouts.app')
@section('title')
Editar Banner
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
                <h1>Banner</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="#">Banner</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <form action="{{ route('banner.update', $banner) }}" method="POST" role="form" id="quickForm" class="needs-validation"
        enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        <div class="row">
            <!-- left column -->
            <div class="col-12 col-md-6">
                <div class="card card-morado">
                    <div class="card-header">
                        <h3 class="card-title">Datoss</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputFile">Imagen</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="banners" id="banners"
                                        accept=".jpeg,.jpg,.png">
                                    <label class="custom-file-label" for="banners" data-browse="Escoger"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo">Título *</label>
                            <input type="text" id="titulo" name="titulo" class="form-control" value="{{ old('titulo', $banner->titulo)}}"
                                placeholder="Ingrese el título">
                        </div>
                        <div class="form-group">
                            <label for="subtitulo">Subtitulo *</label>
                            <input type="text" id="subtitulo" name="subtitulo" class="form-control" value="{{ old('subtitulo', $banner->subtitulo)}}"
                                placeholder="Ingrese el subtitulo">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-morado" id="submit-all">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            @if ($banner->url_banner)
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <img src="{{asset($banner->url_banner)}}" alt="" class="img-fluid">
                </div>
            </div>   
            @endif
            
            <!-- /.col -->
        </div>
    </form>
    {{-- {{ var_dump(old('feinicio',$agendar->feinicio) ,old('fefin',$agendar->fefin)) }} --}}
    <!-- /.row -->
</section>
<!-- /.content-wrapper -->

<!-- /.content -->
<!-- page script -->
<script type="text/javascript">
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
                }
                form.classList.add('was-validated');
                }, false);
            });
            }, false);
        })();
        // Dropzone.autoDiscover = false;
        // var myDropzone;
        $(function () {

            $('#banners').change(function() {
                files = $(this)[0].files;
                
                $(this).next('.custom-file-label').html('');
                
                msgInput = '<div class="alert alert-warning" role="alert">';
                size = (files[0].size / 1024 / 1024).toFixed(2);
                if(size <= 10 && $.inArray(files[0].name.split(".")[1].toLowerCase(),['jpg','png','jpeg'])>= 0){
                    $(this).next('.custom-file-label').append(files[0].name.split(".")[0].toLowerCase());
                }else{
                    msgInput += 'El archivo: ' + files[i].name + ' pesa más de 10 MB o no es un tipo de archivo permitido.<br>';
                    $(this).next('.custom-file-label').html('');
                    $(this).replaceWith($(this).val('').clone(true));
                }
                // files.each(function() {
                // console.log($(this).name);
                // });
        
            });
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