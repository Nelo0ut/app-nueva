<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent text-sm" data-widget="treeview"
        role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview{{ request()->is('entidad/ver-oficina') ? ' menu-open' : '' }}{{ request()->is('tipooficina') ? ' menu-open' : '' }}{{ request()->is('entidad/crear-oficina') ? ' menu-open' : '' }}{{ request()->is('entidad/oficina-ent/*/edit') ? ' menu-open' : '' }} {{ request()->is('entidad/oficinas/consultar') ? ' menu-open' : '' }} {{ request()->is('entidad/oficinas/filtrar') ? ' menu-open' : '' }} ">
            <a href="#" class="nav-link{{ request()->is('entidad/ver-oficina') ? ' active' : '' }}{{ request()->is('tipooficina') ? ' active' : '' }}{{ request()->is('entidad/crear-oficina') ? ' active' : '' }} {{ request()->is('entidad/oficina-ent/*/edit') ? ' active' : '' }} {{ request()->is('entidad/oficinas/consultar') ? ' active' : '' }} {{ request()->is('entidad/oficinas/filtrar') ? ' active' : '' }}">
                <i class="nav-icon fas fa-building"></i>
                <p>
                    Oficinas
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('ver-oficina') }}"
                        class="nav-link{{ request()->is('entidad/ver-oficina') ? ' active' : '' }} {{ request()->is('entidad/oficina-ent/*/edit') ? ' active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Consultar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('crear-oficina') }}"
                        class="nav-link{{ request()->is('entidad/crear-oficina') ? ' active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Agregar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('listar-oficinas') }}"
                        class="nav-link{{ request()->is('entidad/oficinas/consultar') ? ' active' : '' }} {{ request()->is('entidad/oficinas/filtrar') ? ' active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Consultar Total</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ url('/entidad/importar-oficinas') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Importar oficinas</p>
                    </a>
                </li> --}}
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('documento-ver') }}" class="nav-link{{ request()->is('entidad/ver-documentos') ? ' active' : '' }}">
                <i class="nav-icon fas fas fa-file-alt"></i>
                <p>
                    Documentos
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview{{ request()->is('entidad/tarifas') ? ' menu-open' : '' }}{{ request()->is('entidad/bilateral') ? ' menu-open' : '' }}{{ request()->is('entidad/tarifa/editar/*/*/*') ? ' menu-open' : '' }} {{ request()->is('entidad/listar-tarifas') ? ' menu-open' : '' }} {{ request()->is('entidad/bilateral/crear') ? ' menu-open' : '' }} {{ request()->is('entidad/bilateral/modificar/*/*/*') ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ request()->is('entidad/tarifas') ? ' active' : '' }}{{ request()->is('entidad/bilateral') ? ' active' : '' }}{{ request()->is('entidad/tarifa/editar/*/*/*') ? ' active' : '' }}  {{ request()->is('entidad/listar-tarifas') ? ' active' : '' }} {{ request()->is('entidad/bilateral/crear') ? ' active' : '' }} {{ request()->is('entidad/bilateral/modificar/*/*/*') ? ' active' : '' }}">
                <i class="nav-icon fas fa-percent"></i>
                <p>
                    Tarifas
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('listar-tarifa') }}" class="nav-link{{ request()->is('entidad/tarifas') ? ' active' : '' }}{{ request()->is('entidad/tarifa/editar/*/*/*') ? ' active' : '' }} {{ request()->is('entidad/listar-tarifas') ? ' active' : '' }} ">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Normal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('entidad_mostrar_vista_bilateral') }}" class="nav-link{{ request()->is('entidad/bilateral') ? ' active' : '' }} {{ request()->is('entidad/bilateral/crear') ? ' active' : '' }} {{ request()->is('entidad/bilateral/modificar/*/*/*') ? ' active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Bilateral</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('entidad-ver') }}" class="nav-link{{ request()->is('entidad/entidad_edit') ? ' active' : '' }}">
                <i class="nav-icon fas fa-hotel"></i>
                <p>
                    Entidad
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('usuario-listar') }}" class="nav-link{{ request()->is('entidad/usuarios') ? ' active' : '' }} {{ request()->is('entidad/usuario_edit/*/edit') ? ' active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Usuarios de Entidad
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('usuario-listar-global') }}" class="nav-link{{ request()->is('entidad/global/usuarios') ? ' active' : '' }} ">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Usuarios Global
                </p>
            </a>
        </li>
    </ul>
</nav>
<!-- /.sidebar-menu -->