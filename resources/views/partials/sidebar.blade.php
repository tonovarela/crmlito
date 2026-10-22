<aside class="main-sidebar sidebar-dark-primary">
    {{-- <button class="btn btn-default" data-toggle="control-sidebar">Toggle Right Sidebar</button> --}}
    <!-- Brand Logo -->
    <a href="{{ route('Dashboard') }}" class="brand-link">
        <img src="{{ asset('img/Logo.png') }}" alt="Lito" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Candy CRM</span>
    </a>
    <ul style="margin-bottom: -50px">
        <li class="nav-item">
            <a id="btnMenu" class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"
                    style="font-size: 30px"></i></a>
        </li>
    </ul>
    <!-- Sidebar -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel pb-3 d-flex">
            <div class="image" title="{{ session('nombre') }}">
                <img src="{{ asset('img/user.png') }}" class="img-circle elevation-2"
                    alt="User Image">
                {{-- <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image"> --}}
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ session('nombre') }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="nav nav-pills nav-sidebar flex-column">
            <li class="nav-item menu-open" title="Dashboard">
                <a href="{{ route('Dashboard') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            {{-- @if (session('rol_usuario') == 8 || session('rol_usuario') == 1) --}}
            @if (session('prospectos') === 'AccesoModulo')
            <li class="nav-item menu-open" title="Candidatos">
                <a href="{{ route('Candidatos') }}" onclick="ajustarTablas(); loaderON()"
                    class="nav-link {{ request()->is('candidatos*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <p>
                        Candidatos
                    </p>
                </a>
            </li>
            @endif
            @if (session('clientes') === 'AccesoModulo' && session('usuario') != 'smmorenoc')
            <li class="nav-item menu-open" title="Clientes">
                <a href="{{ route('Clientes') }}" onclick="ajustarTablas(); loaderON()"
                    class="nav-link {{ request()->is('clientes*') ? 'active' : '' }} {{ request()->is('contactos*') ? 'active' : '' }} {{ request()->is('requisitos*') ? 'active' : '' }} {{ request()->is('sucursales*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>
                        Clientes
                    </p>
                </a>
            </li>
            @endif
            {{-- @if (session('prospectos') === 'AccesoModulo') --}}
            {{-- @if (session('rol_usuario') == 8 || session('rol_usuario') == 4 || session('rol_usuario') == 9) --}}
            @if (session('oportunidades') === 'AccesoModulo')
            <li class="nav-item menu-open" title="Oportunidades">
                <a href="{{ route('Oportunidades') }}"
                    class="nav-link {{ request()->is('oportunidades*') ? 'active' : '' }} {{ request()->is('correos*') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <!-- <i class="nav-icon fas fa-folder-plus"></i> -->
                    <i class="nav-icon fas fa-search-dollar"></i>
                    <p>
                        Oportunidades
                    </p>
                </a>
            </li>
            @endif
            @if (session('envios') === 'AccesoModulo' && session('usuario') != 'mlegorreta' && session('usuario') != 'smmorenoc')
            <li class="nav-item menu-open" title="Oportunidades de Envíos">
                <a href="{{ route('Envíos') }}" class="nav-link {{ request()->is('envios*') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <i class="nav-icon fas fa-truck"></i>
                    <p>
                        Oportunidades Envíos
                    </p>
                </a>
            </li>
            @endif
            @if ((session('rol_usuario') == 8 || session('rol_usuario') == 1) && session('usuario') != 'smmorenoc')
            <li class="nav-item menu-open" title="Elementos Presupuestados">
                <a href="{{ route('elements.index') }}"
                    class="nav-link {{ request()->is('elementos*') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    <p style="font-size: 15px !important">
                        Elementos Presupuestados
                    </p>
                </a>
            </li>
            @endif
            @if ((session('rol_usuario') == 8 || session('rol_usuario') == 1 || session('rol_usuario') == 1020 || session('rol_usuario') == 1021 || session('rol_usuario') == 1022 || session('rol_usuario') == 1026 || session('rol_usuario') == 4 || session('rol_usuario') == 9) && session('usuario') != 'smmorenoc')
            <li class="nav-item menu-open" title="Diseño Estructural">
                <a href="{{ route('DiseñoEstructural') }}"
                    class="nav-link {{ request()->is('estructural*') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <img src="{{ asset('img/herramienta-de-diseno.png') }}" width="25px" style="margin-top: -10px">
                    <p style="font-size: 15px !important">
                        Diseño Estructural
                    </p>
                </a>
            </li>
            @endif
            {{--  @if (session('Entregas') === 'AccesoModulo')
            <li class="nav-item menu-open" title="Entregas">
                <a href="{{ route('Entregas') }}" class="nav-link {{ request()->is('entregas*') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <i class="nav-icon fas fa-dolly"></i>
                    <p style="font-size: 15px !important">
                        Entregas
                    </p>
                </a>
            </li>
            @endif  --}}
            @if (session('Llamadas') === 'AccesoModulo' || session('Reuniones') === 'AccesoModulo')
            <li class="nav-item menu-open" title="Agenda">
                <a href="{{ route('Agenda') }}" class="nav-link {{ request()->is('agenda*') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p style="font-size: 15px !important">
                        Agenda
                    </p>
                </a>
            </li>
            @endif
            @if (session('usuario') == 'cgonzalez' || session('usuario') == 'maflores' || session('rol_usuario_admin') == 5)
            <li class="nav-item menu-open" title="Documentos Administrativos">
                <a href="{{ route('Documentos Administrativos') }}"
                    class="nav-link {{ request()->is('documentos*') ? 'active' : '' }}" onclick="loaderON()">
                    <i class="nav-icon fas fa-file-pdf"></i>
                    <p style="font-size: 15px !important">
                        Documentos Administrativos
                    </p>
                </a>
            </li>
            @endif
            
            {{-- Acceso al modulo de administración de personal --}}
            @if (session('usuario') == 'cgonzalez' || session('usuario') == 'maflores' || session('usuario') == 'creyes' || session('usuario') == 'gcasas')
            <li class="nav-item menu-open" title="Activación de usuarios Cotizadores y Customers">
                <a href="{{ route('Administracion de cotizadores y customers') }}"
                    class="nav-link {{ request()->is('activacion-cotizadores-customers*') ? 'active' : '' }}" onclick="loaderON()">
                    <i class="nav-icon fas fa-cog"></i>
                    <p style="font-size: 15px !important">
                        Activación de usuarios Cotizadores y Customers
                    </p>
                </a>
            </li>
            @endif

            @if (session('usuario') == 'jmontoya' || session('usuario') == 'cgonzalez')
            <li class="nav-item menu-open" title="Administración de comisiones">
                <a href="{{ url('admin-comisiones') }}"
                    class="nav-link {{ request()->is('admin-comisiones*') ? 'active' : '' }}" onclick="loaderON()">
                    <div class="nav-icon d-flex justify-between-center align-items-center">
                        <img src="{{ asset('img/apedidos.svg') }}" alt="icon-comisiones" width="25px" height="25px">
                    </div>
                    <p style="font-size: 15px !important">
                        Administración de comisiones
                    </p>
                </a>
            </li>
            @endif
            
            @if (session('usuario') == 'cgonzalez' || session('usuario') == 'maflores')
            <li class="nav-item menu-open" title="Usuarios">
                <a href="{{ route('Usuarios') }}" class="nav-link {{ request()->is('usuarios*') ? 'active' : '' }}"
                    onclick="ajustarTablas(); loaderON()">
                    <i class="nav-icon fas fa-users"></i>
                    <p style="font-size: 15px !important">
                        Usuarios
                    </p>
                </a>
            </li>
            <li class="nav-item menu-open" title="Refrescar">
                {{-- <a href="javascript:localStorage.clear()" class="nav-link""> --}}
                <a href="javascript:location.reload(true)" class="nav-link">
                    <i class="nav-icon fa fa-sync" aria-hidden="true"></i>
                    <p>
                        Refrescar
                    </p>
                </a>
            </li>
            @endif
            <li class="nav-item menu-open" title="Manual Candy V1">
                <a href="{{ asset('manual/manual_v1.pdf') }}" class="nav-link" target="_blank">
                    <i class="nav-icon fa fa-book" aria-hidden="true"></i>
                    <p style="font-size: 15px !important">
                        Manual V1
                    </p>
                </a>
            </li>
            <li class="nav-item menu-open" title="Manual Candy V2">
                <a href="{{ asset('manual/manual.pdf') }}" class="nav-link" target="_blank">
                    <i class="nav-icon fa fa-book" aria-hidden="true"></i>
                    <p style="font-size: 15px !important">
                        Manual V2
                    </p>
                </a>
            </li>
            <li class="nav-item menu-open" title="Salir">
                <a href="{{ route('Logout') }}" class="nav-link" onclick="loaderON()">
                    <i class="nav-icon fa fa-door-open" aria-hidden="true"></i>
                    <p>
                        Salir
                    </p>
                </a>
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>