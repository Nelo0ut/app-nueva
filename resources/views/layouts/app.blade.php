<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Extranet CCE</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('overlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}" rel="stylesheet">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('styles')

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset('datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script> --}}
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('moment/moment.min.js') }}"></script>
    <script src="{{ asset('inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('jquery-mousewheel/jquery.mousewheel.js') }}"></script>

    @yield('scripts')
    
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed accent-purple">
  <div class="preload">
    <div class="loading">
      <img src="{{asset('img/loading.svg')}}" class="img-fluid" alt="">
    </div>
  </div>
  <div class="wrapper">
    <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-dark navbar-purple">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            @if (session()->get('role_id') == 1 || session()->get('role_id') == 2)
              <a href="{{ route('home') }}" class="nav-link">Home</a>
            @else
              <a href="{{ route('home_ent') }}" class="nav-link">Home</a>
            @endif
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          {{-- @if (session()->get('role_id') == 3 || session()->get('role_id') == 4) --}}
            {{-- <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">3 Notificaciones</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-envelope mr-2"></i> 2 documentos nuevos
                  <span class="float-right text-muted text-sm">3 min</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-users mr-2"></i> 1 documento nuevo
                  <span class="float-right text-muted text-sm">12 horas</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-file mr-2"></i> 3 documentos nuevos
                  <span class="float-right text-muted text-sm">2 días</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{route('documento-ver')}}" class="dropdown-item dropdown-footer">Ver todos los documentos</a>
              </div>
            </li> --}}
          {{-- @endif --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                Salir
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-purple elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img src="{{ asset('img/logo 1-01.jpg') }}" alt="Transferencia Interbancarias Logo" class="brand-image"
            style="opacity: .8">
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image d-flex align-items-center">
            <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <small style="color: #fff;">{{ session()->get('rol_nombre') }}</small>
            <a href="#" class="d-block">{{ session()->get('nombre') }}</a>
          </div>
        </div>
        @if (session()->get('role_id') == 1 || session()->get('role_id') == 2)
            @include('layouts/menu_admin')
        @else
          @if (!empty(session()->get('entidad_logo')))
              <div class="mt-3 pb-3 mb-3 d-flex">
                <div class="image d-flex align-items-center">
                  <img src="{{ url('storage/'.session()->get('entidad_logo')) }}" class="elevation-2 img-fluid" alt="Entidad Image">
                </div>
              </div>
          @endif
          @include('layouts/menu_entidad')
        @endif
        
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @yield('content')
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    <script>
      $(function () {
        $('.valnumero').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g,'');
        });
        $('.valdecimal').on('input', function () {
            this.value = this.value.replace(/[^0-9,.]/g, '').replace(/,/g, '.');
        });

        $('.alphaonly').on('input', function (e) {
          if (!/^[ a-z0-9áéíóúüñ]*$/i.test(this.value)) {
            this.value = this.value.replace(/[^ a-z0-9áéíóúüñ]+/ig,"");
          }
        });
      });

      function validateEmail(id)
      {
        var email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
        if(!email_regex.test($("#"+id).val()))
        {
          var div = $("#"+id).closest("div");
          div.removeClass("has-success");
          $("#glypcn"+id).remove();
          div.addClass("has-error has-feedback");
          div.append('<span id="glypcn'+id+'" class="glyphicon glyphicon-remove form-control-feedback"></span>');
          return false;
        }
        else{
          var div = $("#"+id).closest("div");
          div.removeClass("has-error");
          $("#glypcn"+id).remove();
          div.addClass("has-success has-feedback");
          div.append('<span id="glypcn'+id+'" class="glyphicon glyphicon-ok form-control-feedback"></span>');
              return true;
        }

      }
    </script>
    <!-- Main Footer -->
    <footer class="main-footer text-sm">
      <strong>Copyright &copy; 2020 <a href="http://165.22.3.24/cce/">CCE</a>.</strong>
      Todos los derechos reservados.
      <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 1.0.0
      </div>
    </footer>
  </div>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1SF0P8BFT9"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
  
    gtag('config', 'G-1SF0P8BFT9');
  </script>
</body>
</html>
