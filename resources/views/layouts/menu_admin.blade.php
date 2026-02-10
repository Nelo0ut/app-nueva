<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent text-sm" data-widget="treeview"
        role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
        <li
            class="nav-item has-treeview{{ request()->is('admin/oficina') ? ' menu-open' : '' }}{{ request()->is('admin/tipooficina') ? ' menu-open' : '' }}{{ request()->is('admin/oficina/create') ? ' menu-open' : '' }}{{ request()->is('admin/importar-oficinas') ? ' menu-open' : '' }} {{ request()->is('admin/tipooficina/*/edit') ? ' menu-open' : '' }} {{ request()->is('admin/oficina/*/edit') ? ' menu-open' : '' }} {{ request()->is('admin/oficina/filtrar') ? ' menu-open' : '' }}">
            <a href="#"
                class="nav-link{{ request()->is('admin/oficina') ? ' active' : '' }}{{ request()->is('admin/tipooficina') ? ' active' : '' }}{{ request()->is('admin/oficina/create') ? ' active' : '' }} {{ request()->is('admin/importar-oficinas') ? ' active' : '' }} {{ request()->is('admin/tipooficina/*/edit') ? ' active' : '' }}  {{ request()->is('admin/oficina/*/edit') ? ' active' : '' }} {{ request()->is('admin/oficina/filtrar') ? ' active' : '' }}">
                <i class="nav-icon fas fa-building"></i>
                <p>
                    Oficinas
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('tipooficina.index') }}"
                        class="nav-link {{ request()->is('admin/tipooficina') ? 'active' : '' }} {{ request()->is('admin/tipooficina/*/edit') ? ' active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipo oficina</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('oficina.index') }}"
                        class="nav-link {{ request()->is('admin/oficina') ? 'active' : '' }} {{ request()->is('admin/oficina/*/edit') ? ' active' : '' }} {{ request()->is('admin/oficina/filtrar') ? ' active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Consultar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('oficina.create') }}"
                        class="nav-link {{ request()->is('admin/oficina/create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Agregar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/importar-oficinas') }}"
                     class="nav-link {{ request()->is('admin/importar-oficinas') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Importar oficinas</p>
                    </a>
                </li>
            </ul>
        </li>
        <li
            class="nav-item has-treeview{{ request()->is('admin/documento') ? ' menu-open' : '' }}{{ request()->is('admin/documento/create') ? ' menu-open' : '' }}{{ request()->is('admin/tipoDocumento') ? ' menu-open' : '' }} {{ request()->is('admin/documento/*/edit') ? ' menu-open' : '' }} {{ request()->is('admin/tipoDocumento/*/edit') ? ' menu-open' : '' }}">
            <a href="#"
                class="nav-link{{ request()->is('admin/documento') ? ' active' : '' }}{{ request()->is('admin/tipoDocumento') ? ' active' : '' }}{{ request()->is('admin/documento/create') ? ' active' : '' }}{{ request()->is('admin/documento/*/edit') ? ' active' : '' }} {{ request()->is('admin/tipoDocumento/*/edit') ? ' active' : '' }}">
                <i class="nav-icon fas fas fa-file-alt"></i>
                <p>
                    Documentos
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('tipoDocumento.index') }}"
                        class="nav-link {{ request()->is('admin/tipoDocumento') ? 'active' : '' }} {{ request()->is('admin/tipoDocumento/*/edit') ? ' active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipo de documento</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('documento.index') }}"
                        class="nav-link {{ request()->is('admin/documento') ? 'active' : '' }} {{ request()->is('admin/documento/*/edit') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Consultar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('documento.create') }}"
                        class="nav-link {{ request()->is('admin/documento/create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Subir</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.entidad.index') }}" class="nav-link{{ request()->is('admin/entidad') ? ' active' : '' }}{{ request()->is('admin/entidad/*/edit') ? ' active' : '' }}{{ request()->is('admin/entidad/bilateral') ? ' active' : '' }}">
                <i class="nav-icon fas fa-hotel"></i>
                <p>
                    Entidades
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview{{ request()->is('admin/tarifa') ? ' menu-open' : '' }}{{ request()->is('admin/bilateral') ? ' menu-open' : '' }} {{ request()->is('admin/modificar') ? ' menu-open' : '' }} {{ request()->is('admin/tarifa-entidad') ? ' menu-open' : '' }} {{ request()->is('admin/tarifa/editar/*/*/*') ? 'menu-open' : '' }} {{ request()->is('admin/bilateral/crear') ? 'menu-open' : '' }} {{ request()->is('admin/bilateral/modificar/*/*/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link{{ request()->is('admin/tarifa') ? ' active' : '' }}{{ request()->is('admin/bilateral') ? ' active' : '' }} {{ request()->is('admin/modificar') ? 'active' : '' }}  {{ request()->is('admin/tarifa-entidad') ? 'active' : '' }} {{ request()->is('admin/tarifa/editar/*/*/*') ? 'active' : '' }} {{ request()->is('admin/bilateral/crear') ? 'active' : '' }} {{ request()->is('admin/bilateral/modificar/*/*/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-percent"></i>
                <p>
                    Tarifas
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('tarifa.index') }}"
                        class="nav-link {{ request()->is('admin/tarifa') ? 'active' : '' }} {{ request()->is('admin/tarifa-entidad') ? 'active' : '' }} {{ request()->is('admin/tarifa/editar/*/*/*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Normal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('mostrar_vista_bilateral') }}"
                        class="nav-link {{ request()->is('admin/bilateral') ? 'active' : '' }} {{ request()->is('admin/bilateral/crear') ? 'active' : '' }} {{ request()->is('admin/bilateral/modificar/*/*/*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Bilateral</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('mostrar_vista_modificar_tarifas') }}"
                        class="nav-link {{ request()->is('admin/modificar') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Modificar</p>
                    </a>
                </li>
            </ul>
        </li>
        {{-- COMENTARIO:  AGREGE UN LI , PARA LISTAR EN LA VENTANA PRINCIPAL --}}
        
        {{-- <li class="nav-item">
            <a href="{{ route('evento.index') }}" class="nav-link{{ request()->is('admin/evento') ? ' active' : '' }}{{ request()->is('admin/evento/create') ? ' active' : '' }}{{ request()->is('admin/evento/*/edit') ? ' active' : '' }}">
                <i class="nav-icon fas fa-calendar-day"></i>
                <p>
                    Eventos
                </p>
            </a>
        </li> --}}
        
        {{-- FIN DE INSERCION  --}}
        <li class="nav-item">
            <a href="{{ route('agendar.index') }}" class="nav-link{{ request()->is('admin/agendar') ? ' active' : '' }}{{ request()->is('admin/agendar/create') ? ' active' : '' }}{{ request()->is('admin/agendar/*/edit') ? ' active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>
                    Agendar
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.index') }}" class="nav-link{{ request()->is('admin/user') ? ' active' : '' }} {{ request()->is('admin/user/*/edit') ? ' active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Usuarios
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('banner.index') }}"
                class="nav-link{{ request()->is('admin/banner') ? ' active' : '' }}{{ request()->is('admin/banner/create') ? ' active' : '' }}{{ request()->is('admin/banner/*/edit') ? ' active' : '' }}">
                <i class="nav-icon fas fa-photo-video"></i>
                <p>
                    Banners
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bitacora') }}" class="nav-link{{ request()->is('admin/bitacora') ? ' active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Bitacora de ingreso
                </p>
            </a>
        </li>
    </ul>
</nav>
<!-- /.sidebar-menu -->