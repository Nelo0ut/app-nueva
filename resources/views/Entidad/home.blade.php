@extends('layouts.app')
@section('title')
Dashboard
@endsection
@section('content')

<!-- fullCalendar -->
<link rel="stylesheet" href="{{ asset("fullcalendar/main.min.css") }}">
<link rel="stylesheet" href="{{ asset("fullcalendar-daygrid/main.min.css") }}">
<link rel="stylesheet" href="{{ asset("fullcalendar-timegrid/main.min.css") }}">
<link rel="stylesheet" href="{{ asset("fullcalendar-bootstrap/main.min.css") }}">
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset("moment/moment.min.js") }}"></script>
<script src="{{ asset("fullcalendar/main.min.js") }}"></script>
<script src="{{ asset("fullcalendar/locales-all.min.js") }}"></script>
<script src="{{ asset("fullcalendar-daygrid/main.min.js") }}"></script>
<script src="{{ asset("fullcalendar-timegrid/main.min.js") }}"></script>
<script src="{{ asset("fullcalendar-interaction/main.min.js") }}"></script>
<script src="{{ asset("fullcalendar-bootstrap/main.min.js") }}"></script>
<!-- jQuery UI -->
<script src="{{ asset("jquery-ui/jquery-ui.min.js") }}"></script>
<style>
  .fc-content{
    color: white!important;
    font-weight: 700;
  }
</style>
@if ($banners)
@php
    $i = 0; $a = 0;
@endphp
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body p-0">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                    @foreach ($banners as $banner)
                      <li data-target="#carouselExampleIndicators" data-slide-to="{{$a}}" @if ($a == 0) class="active" @else class="" @endif></li>
                        @php
                            $a++;
                        @endphp
                    @endforeach
                    {{-- <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1" class=""></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2" class=""></li> --}}
                  </ol>
                  <div class="carousel-inner">
                    @foreach ($banners as $banner)
                        <div class="carousel-item @if ($i == 0) active @endif">
                          <img src="{{ asset($banner->url_banner) }}" class="img-fluid" alt="" >
                          @if ($banner->titulo || $banner->subtitulo)
                              <div class="descripcion-banner d-flex justify-content-left align-items-center">
                                <div class="centrar-descripcion">
                                  @if ($banner->titulo)
                                  <p class="titulo-banner">{{$banner->titulo}}</p>
                                  @endif
                                  @if ($banner->subtitulo)
                                  <p class="subtitulo-banner">{{$banner->subtitulo}}</p>
                                  @endif
                                </div>
                              </div>
                          @endif
                        </div>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                    {{-- <div class="carousel-item active">
                      <img src="{{ asset('img/banner-2.png') }}" alt="" class="img-fluid">
                    </div>
                    <div class="carousel-item">
                      <img src="{{ asset('img/banner-2.png') }}" alt="" class="img-fluid">
                    </div>
                    <div class="carousel-item">
                      <img src="{{ asset('img/banner-2.png') }}" alt="" class="img-fluid">
                    </div> --}}
                  </div>
                  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
                <!-- THE CALENDAR -->
    
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
@endif

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Calendario</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary">
                    <div class="card-body p-0">
                        <!-- THE CALENDAR -->
                        <div id="calendar"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-3">
              @if ($documentos)
                  @foreach ($documentos as $documento)
                      <div class="card card-outline card-danger">
                        <div class="card-header">
                          <h3 class="card-title">Documento</h3>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool docread" data-id="{{$documento->id}}" data-card-widget="remove"><i class="fas fa-times"></i></button>
                          </div>
                        </div>
                        <div class="card-body">
                          <dl>
                            <dt>Nombre general</dt>
                            <dd>{{$documento->name}}</dd>
                            <dt>Tipo</dt>
                            <dd>{{$documento->tipodoc}}</dd>
                            <dt>Documentos</dt>
                            <dd>
                              <ul>
                                <li>
                                  @if (explode(".",$documento->documento)[1] == "doc" ||
                                    explode(".",$documento->documento)[1] == "docx" ||
                                    explode(".",$documento->documento)[1] == "xls" ||
                                    explode(".",$documento->documento)[1] == "xlsx" ||
                                    explode(".",$documento->documento)[1] == "pdf")
                                    <a href="{{ url("storage/$documento->documento") }}" target="_blank">Descargar {{$documento->nombre_doc}}</a>
                                  @elseif(explode(".",$documento->documento)[1] == "pdf")
                                    <a href="{{ url("storage/$documento->documento") }}" target="_blank">Abrir {{$documento->nombre_doc}}</a>
                                  @else
                                    <a href="javascript:void(0);" data-img="{{ url("storage/$documento->documento") }}" class="verImg">Ver imagen {{$documento->nombre_doc}}</a>
                                  @endif
                                </li>
                              </ul>
                            </dd>
                          </dl>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                  @endforeach
              @endif
              @if ($eventos)
                  @foreach ($eventos as $evento)
                      <div class="card card-outline card-danger">
                        <div class="card-header">
                          <h3 class="card-title">Evento</h3>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool evenread" data-id="{{$evento->id}}" data-card-widget="remove"><i class="fas fa-times"></i></button>
                          </div>
                        </div>
                        <div class="card-body">
                          <dl>
                            <dt>Nombre</dt>
                            <dd>{{$evento->titulo}}</dd>
                            <dt>Fecha del Evento</dt>
                            <dd>{{$evento->fecha}}</dd>
                          </dl>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                  @endforeach
              @endif
              @if ($agendas)
                  @foreach ($agendas as $agenda)
                      <div class="card card-outline card-danger">
                        <div class="card-header">
                          <h3 class="card-title">Agenda</h3>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool ageread" data-id="{{$agenda->id}}" data-card-widget="remove"><i class="fas fa-times"></i></button>
                          </div>
                        </div>
                        <div class="card-body">
                          <dl>
                            <dt>Nombre</dt>
                            <dd>{{$agenda->name}}</dd>
                            <dt>Descripción</dt>
                            <dd>{{$agenda->description}}</dd>
                            <dt>Fecha y hora inicio:</dt>
                            <dd>{{$agenda->feinicio}}</dd>
                            <dt>Fecha y hora final:</dt>
                            <dd>{{$agenda->fefin}}</dd>
                          </dl>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                  @endforeach
              @endif
              
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ver imagen</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card">
          <img src="" id="imgModalDoc" alt="" class="img-fluid">
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.content -->

<style>
  .accent-purple .btn-link, .accent-purple
  a:not(.dropdown-item):not(.btn-app):not(.nav-link):not(.brand-link):not(.page-link) {
  color: #3c8dbc;
  }
</style>
<!-- Page specific script -->
<script>
    $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
      });

      $('.verImg').click(function () {
        img = $(this).data('img');
        $('#imgModalDoc').removeAttr('src').attr('src', img);
        $('#modal-default').modal('show');
      });

      $('.docread').click(function(){
        id = $(this).data("id");
        $.ajax({
            type:'POST',
            url:"{{ route('read_documento') }}",
            data:{ id:id },
            beforeSend:function(){
              $('.preload').show();
            },
            success:function(data){
                console.log(data);
                if(data.status == 1){
                    swalWithBootstrapButtons.fire(
                    '¡Éxito!',
                    'Se cambió como visto.',
                    'success'
                    )
                }else{
                    swalWithBootstrapButtons.fire(
                    '¡Aviso!',
                    'No se pudo cambiar como visto',
                    'warning'
                    )
                }
            },
            complete:function(){
              $('.preload').hide();
            }
        });
      });

      $('.ageread').click(function(){
        id = $(this).data("id");
        $.ajax({
            type:'POST',
            url:"{{ route('read_agenda') }}",
            data:{ id:id },
            beforeSend:function(){
              $('.preload').show();
            },
            success:function(data){
                console.log(data);
                if(data.status == 1){
                    swalWithBootstrapButtons.fire(
                    '¡Éxito!',
                    'Se cambió como visto.',
                    'success'
                    )
                }else{
                    swalWithBootstrapButtons.fire(
                    '¡Aviso!',
                    'No se pudo cambiar como visto',
                    'warning'
                    )
                }
            },
            complete:function(){
              $('.preload').hide();
            }
        });
      });

      $('.everead').click(function(){
        id = $(this).data("id");
        $.ajax({
            type:'POST',
            url:"{{ route('read_evento') }}",
            data:{ id:id },
            beforeSend:function(){
              $('.preload').show();
            },
            success:function(data){
                console.log(data);
                if(data.status == 1){
                    swalWithBootstrapButtons.fire(
                    '¡Éxito!',
                    'Se cambió como visto.',
                    'success'
                    )
                }else{
                    swalWithBootstrapButtons.fire(
                    '¡Aviso!',
                    'No se pudo cambiar como visto',
                    'warning'
                    )
                }
            },
            complete:function(){
              $('.preload').hide();
            }
        });
      });

      var Calendar = FullCalendar.Calendar;
      var checkbox = document.getElementById('drop-remove');
      var calendarEl = document.getElementById('calendar');
      
      var calendar = new Calendar(calendarEl, {
      locale: 'es',
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header : {
      left : 'prev,next today',
      center: 'title',
      right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      'themeSystem': 'bootstrap',
      //Random default events
      events : {!! $agendados !!},
      editable : false,
      droppable : false, // this allows things to be dropped onto the calendar !!!
      drop : function(info) {
      // is the "remove after drop" checkbox checked?
      if (checkbox.checked) {
      // if so, remove the element from the "Draggable Events" list
      info.draggedEl.parentNode.removeChild(info.draggedEl);
      }
      }
      });
      calendar.setOption('locale', 'es');
      calendar.render();
  })
</script>
@endsection
