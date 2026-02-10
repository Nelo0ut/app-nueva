@extends('layouts.app')

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
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body p-0">
                        <!-- THE CALENDAR -->
                        <div id="calendar"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- Page specific script -->
<script>
  $(function () {
    var Calendar = FullCalendar.Calendar;
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
      locale: 'es',
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      'themeSystem': 'bootstrap',
      //Random default events
      events    : {!! $agendados !!},
      editable  : false,
      droppable : false, // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
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
