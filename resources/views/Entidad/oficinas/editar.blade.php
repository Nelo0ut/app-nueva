@extends('layouts.app')
@section('title')
Editar oficina
@endsection
@section('content')

<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Editar Oficina</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Oficinas</a></li>
              <li class="breadcrumb-item active">Editar</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <form action="{{ route('actualizar-oficina', $oficina) }}" method="post" autocomplete="off" role="form" id="quickForm" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-6">
            <div class="card card-naranja">
              <div class="card-header">
                <h3 class="card-title">General</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="card-body">
                @include('includes.form-error')
                @include('includes.mensajes')
                <input type="hidden" name="entidad_id" value="{{ session()->get('identidad') }}">
                <div class="form-group">
                  <label for="name">Nombre de oficina *</label>
                  <input type="text" id="name" name="name" class="form-control" maxlength="35" required value="{{ old('name', $oficina->name) }}">
                </div>
                <div class="form-group">
                  <label for="numero">Número de oficina *</label>
                  <input type="text" id="numero" name="numero" class="form-control" maxlength="3" required value="{{ old('numero', $oficina->numero) }}">
                </div>
                <div class="form-group">
                  <label for="domicilio">Domicilio *</label>
                  <input type="text" id="domicilio" name="domicilio" class="form-control" maxlength="80" required value="{{ old('domicilio', $oficina->domicilio) }}">
                </div>
                <div class="form-group">
                  <label for="localidad">Localidad *</label>
                  <input type="text" id="localidad" name="localidad" class="form-control" maxlength="35" required value="{{ old('localidad', $oficina->localidad) }}">
                </div>
                <div class="form-group">
                  <label for="nameplaza">Nombre de la Plaza *</label>
                  <input type="text" id="nameplaza" name="nameplaza" class="form-control" maxlength="35" required value="{{ old('nameplaza', $oficina->nameplaza) }}">
                </div>
                <div class="form-group">
                  <label for="numeroplaza">Número de oficina en la Plaza *</label>
                  <input type="text" id="numeroplaza" name="numeroplaza" class="form-control" maxlength="4" required value="{{ old('numeroplaza', $oficina->numeroplaza) }}">
                </div>
                <div class="form-group">
                  <label for="tipooficina_id">Tipo de oficina</label>
                  <select class="form-control custom-select" name="tipooficina_id" id="tipooficina_id" required>
                    <option value="">:: SELECCIONE ::</option>
                    @foreach ($tipooficinas as $tipooficina)
                      <option value="{{ $tipooficina->id }}" @if ($oficina->tipooficina_id == $tipooficina->id) selected @endif>{{ $tipooficina->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="preftelefono">Prefijo de teléfono larga distancia</label>
                  <input type="text" id="preftelefono" name="preftelefono" class="form-control" maxlength="3" value="{{ old('preftelefono', $oficina->preftelefono) }}">
                </div>
                <div class="form-group">
                  <label for="telefono1">Número telefónico 1</label>
                  <input type="text" id="telefono1" name="telefono1" class="form-control" maxlength="8" value="{{ old('telefono1', $oficina->telefono1) }}">
                </div>
                <div class="form-group">
                  <label for="telefono2">Número telefónico 2</label>
                  <input type="text" id="telefono2" name="telefono2" class="form-control" maxlength="8" value="{{ old('telefono2', $oficina->telefono2) }}">
                </div>
                <div class="form-group">
                  <label for="fax">FAX</label>
                  <input type="text" id="fax" name="fax" class="form-control" maxlength="8" value="{{ old('fax', $oficina->fax) }}">
                </div>
                <div class="form-group">
                  <label for="centraltelefonica">Central telefónica</label>
                  <input type="text" id="centraltelefonica" name="centraltelefonica" maxlength="8" class="form-control" value="{{ old('centraltelefonica', $oficina->centraltelefonica) }}">
                </div>
                <div class="form-group">
                    <label for="motivo">Motivo de actualización *</label>
                    <select class="form-control custom-select" name="motivo" id="motivo" required>
                        <option value="">:: SELECCIONE ::</option>
                        <option value="1" @if ($oficina->motivo == 1) selected @endif>Alta</option>
                        <option value="2" @if ($oficina->motivo == 2) selected @endif>Baja</option>
                        <option value="3" @if ($oficina->motivo == 3) selected @endif>Modificación de datos</option>
                        <option value="4" @if ($oficina->motivo == 4) selected @endif>Baja definitiva</option>
                    </select>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-6">
            <div class="card card-morado">
              <div class="card-header">
                <h3 class="card-title">Ubigeo</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputUbiDis">Ubigeo distrital *</label>
                </div>
                <div class="form-group">
                  <label for="inputDistriDep">Departamento</label>
                  <select required class="form-control custom-select" required name="inputDistriDep" id="inputDistriDep">
                    <option value="">:: SELECCIONE ::</option>
                    @foreach ($ubigeos as $ubigeo)
                      <option value="{{ $ubigeo->coddepa }}">{{ $ubigeo->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputDistriPro">Provincia</label>
                  <select required class="form-control custom-select" required name="inputDistriPro" id="inputDistriPro">
                    <option value="">:: SELECCIONE ::</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputDistriDis">Distrito</label>
                  <select required class="form-control custom-select" required name="inputDistriDis" id="inputDistriDis">
                    <option value="">:: SELECCIONE ::</option>
                  </select>
                </div>
                <hr>
                <div class="form-group">
                  <label for="inputUbiDis">Ubigeo cheques *</label>
                </div>
                <div class="form-group">
                  <label for="inputChequeDep">Departamento</label>
                  <select required class="form-control custom-select" required name="inputChequeDep" id="inputChequeDep">
                    <option value="">:: SELECCIONE ::</option>
                    @foreach ($ubigeos as $ubigeo)
                      <option value="{{ $ubigeo->coddepa }}">{{ $ubigeo->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputChequePro">Provincia</label>
                  <select required class="form-control custom-select" required name="inputChequePro" id="inputChequePro">
                    <option value="">:: SELECCIONE ::</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputChequeDis">Distrito</label>
                  <select required class="form-control custom-select" required name="inputChequeDis" id="inputChequeDis">
                    <option value="">:: SELECCIONE ::</option>
                  </select>
                </div>
                <hr>
                <div class="form-group">
                  <label for="inputUbiDis">Ubigeo transferencias *</label>
                </div>
                <div class="form-group">
                  <label for="inputTransfeDep">Departamento</label>
                  <select required class="form-control custom-select" required name="inputTransfeDep" id="inputTransfeDep">
                    <option value="">:: SELECCIONE ::</option>
                    @foreach ($ubigeos as $ubigeo)
                      <option value="{{ $ubigeo->coddepa }}">{{ $ubigeo->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputTransfePro">Provincia</label>
                  <select required class="form-control custom-select" required name="inputTransfePro" id="inputTransfePro">
                    <option value="">:: SELECCIONE ::</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputTransfeDis">Distrito</label>
                  <select required class="form-control custom-select" required name="inputTransfeDis" id="inputTransfeDis">
                    <option value="">:: SELECCIONE ::</option>
                  </select>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <a href="{{ route('ver-oficina') }}" class="btn btn-naranja ">Regresar</a>
            <button type="submit" class="btn btn-morado">Actualizar oficina</button>
          </div>
        </div>
      </form>
    </section>
    <!-- /.content -->
      <!-- page script -->
<script>
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (function() {
      'use strict';
      window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
          }
          form.classList.add('was-validated');
          }, false);
      });
      }, false);
  })();
  $(function () {
    $('.select2').select2();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Cargamos los Provincias del Ubigeo
    $('#inputDistriDep').change(function(){
      cmb1 = "<option value=''> :: SELECCIONE :: </option>";
      $('#inputDistriDis').html(cmb1);
      $.ajax({
          type:'POST',
          url:"{{ route('getubigeo') }}",
          data:{ depa:$(this).val() },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            console.log(data);
            if(data.length > 0){
              for (var i = 0; i < data.length; i++) {
                cmb1 += "<option value='"+data[i].codprov+"'>"+data[i].name+"</option>";
              }
              //llenamos el combo on
              $('#inputDistriPro').html(cmb1);
              @if ($oficina->ubigeodistrital)
                $('#inputDistriPro').val('{{ $ubigeodis[0]->codprov }}').trigger('change');
              @endif
            }else{
              alert('Por el momento no tenemos información para su petición.');
            }
          },
          complete:function(){
          $('.preload').hide();
          }
      });
    });

    //Cargamos los Provincias del Ubigeo
    $('#inputDistriPro').change(function(){
      depa = $('#inputDistriDep').val();
      cmb2 = "<option value=''> :: SELECCIONE :: </option>";
      $('#inputDistriDis').html(cmb2);
      $.ajax({
          type:'POST',
          url:"{{ route('getubigeo') }}",
          data:{ depa: depa, prov: $(this).val() },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            console.log(data);
            if(data.length > 0){
              for (var i = 0; i < data.length; i++) {
                cmb2 += "<option value='"+data[i].codist+"'>"+data[i].name+"</option>";
              }
              //llenamos el combo on
              $('#inputDistriDis').html(cmb2);
              @if ($oficina->ubigeodistrital)
                $('#inputDistriDis').val('{{ $ubigeodis[0]->codist }}').trigger('change');
              @endif
            }else{
              alert('Por el momento no tenemos información para su petición.');
            }
          },
          complete:function(){
          $('.preload').hide();
          }
      });
    });


    //Cargamos los Provincias del Ubigeo
    $('#inputChequeDep').change(function(){
      cmb3 = "<option value=''> :: SELECCIONE :: </option>";
      $('#inputChequeDis').html(cmb3);
      $.ajax({
          type:'POST',
          url:"{{ route('getubigeo') }}",
          data:{ depa:$(this).val() },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            console.log(data);
            if(data.length > 0){
              for (var i = 0; i < data.length; i++) {
                cmb3 += "<option value='"+data[i].codprov+"'>"+data[i].name+"</option>";
              }
              //llenamos el combo on
              $('#inputChequePro').html(cmb3);
              @if ($oficina->ubigeocheques)
                $('#inputChequePro').val('{{ $ubigeoche[0]->codprov }}').trigger('change');
              @endif
            }else{
              alert('Por el momento no tenemos información para su petición.');
            }
          },
          complete:function(){
          $('.preload').hide();
          }
      });
    });

    //Cargamos los Provincias del Ubigeo
    $('#inputChequePro').change(function(){
      depa2 = $('#inputChequeDep').val();
      cmb4 = "<option value=''> :: SELECCIONE :: </option>";
      $('#inputChequeDis').html(cmb4);
      $.ajax({
          type:'POST',
          url:"{{ route('getubigeo') }}",
          data:{ depa: depa2, prov: $(this).val() },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            console.log(data);
            if(data.length > 0){
              for (var i = 0; i < data.length; i++) {
                cmb4 += "<option value='"+data[i].codist+"'>"+data[i].name+"</option>";
              }
              //llenamos el combo on
              $('#inputChequeDis').html(cmb4);
              @if ($oficina->ubigeocheques)
                $('#inputChequeDis').val('{{ $ubigeoche[0]->codist }}').trigger('change');
              @endif
            }else{
              alert('Por el momento no tenemos información para su petición.');
            }
          },
          complete:function(){
          $('.preload').hide();
          }
      });
    });



    //Cargamos los Provincias del Ubigeo
    $('#inputTransfeDep').change(function(){
      cmb5 = "<option value=''> :: SELECCIONE :: </option>";
      $('#inputTransfeDis').html(cmb5);
      $.ajax({
          type:'POST',
          url:"{{ route('getubigeo') }}",
          data:{ depa:$(this).val() },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            console.log(data);
            if(data.length > 0){
              for (var i = 0; i < data.length; i++) {
                cmb5 += "<option value='"+data[i].codprov+"'>"+data[i].name+"</option>";
              }
              //llenamos el combo on
              $('#inputTransfePro').html(cmb5);
              @if ($oficina->ubigeotransferencia)
                $('#inputTransfePro').val('{{ $ubigeotra[0]->codprov }}').trigger('change');
              @endif
            }else{
              alert('Por el momento no tenemos información para su petición.');
            }
          },
          complete:function(){
          $('.preload').hide();
          }
      });
    });

    //Cargamos los Provincias del Ubigeo
    $('#inputTransfePro').change(function(){
      depa3 = $('#inputTransfeDep').val();
      cmb6 = "<option value=''> :: SELECCIONE :: </option>";
      $('#inputTransfeDis').html(cmb6);
      $.ajax({
          type:'POST',
          url:"{{ route('getubigeo') }}",
          data:{ depa: depa3, prov: $(this).val() },
          beforeSend:function(){
            $('.preload').show();
          },
          success:function(data){
            console.log(data);
            if(data.length > 0){
              for (var i = 0; i < data.length; i++) {
                cmb6 += "<option value='"+data[i].codist+"'>"+data[i].name+"</option>";
              }
              //llenamos el combo on
              $('#inputTransfeDis').html(cmb6);
              @if ($oficina->ubigeotransferencia)
                $('#inputTransfeDis').val('{{ $ubigeotra[0]->codist }}').trigger('change');
              @endif
            }else{
              alert('Por el momento no tenemos información para su petición.');
            }
          },
          complete:function(){
          $('.preload').hide();
          }
      });
    });

    //Change selected
    @if ($oficina->ubigeodistrital)
      $('#inputDistriDep').val('{{ $ubigeodis[0]->coddepa }}').trigger('change');
    @endif

    //Change selected
    @if ($oficina->ubigeocheques)
      $('#inputChequeDep').val('{{ $ubigeoche[0]->coddepa }}').trigger('change');
    @endif

    //Change selected
    @if ($oficina->ubigeotransferencia)
      $('#inputTransfeDep').val('{{ $ubigeotra[0]->coddepa }}').trigger('change');
    @endif

  });
</script>
    @endsection
