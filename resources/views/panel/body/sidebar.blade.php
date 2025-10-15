<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid rounded-normal light-logo" alt="logo">
            <h5 class="logo-title light-logo ml-3">J. TripleJ</h5>
        </a>
        <div class="iq-menu-bt-sidebar ml-0">
            <i class="las la-bars wrapper-menu"></i>
        </div>
    </div>

    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">

                {{-- PANEL --}}
                @if($permisos['dashboard'])
                    <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span class="ml-3">Panel</span>
                        </a>
                    </li>
                @endif
                {{-- PANEL SOCIO --}}
                @if($permisos['dashboard2'])
                    <li class="{{ Request::is('dashboard-socio') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-gauge"></i>
                            <span class="ml-3">Panel</span>
                        </a>
                    </li>
                @endif
                

                {{-- VENTAS --}}
                @if($permisos['ventas'])
                    <li class="{{ Route::currentRouteNamed('pos.local') ? 'active' : '' }}">
                        <a href="{{ route('pos.local') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="ml-3">Vender</span>
                        </a>
                    </li>
                @endif

                {{-- PEDIDOS --}}
                @if($permisos['pedidos'])
                    @php
                        $activeOrderMenu = Request::is('pedidos*') || Request::is('stock*');
                    @endphp
                    <li>
                        <a href="#orders" class="d-flex align-items-center {{ $activeOrderMenu ? '' : 'collapsed' }}"
                           data-toggle="collapse"
                           aria-expanded="{{ $activeOrderMenu ? 'true' : 'false' }}">
                            <i class="fa-solid fa-box"></i>
                            <span class="ml-3">Pedidos</span>
                            <i class="fa-solid fa-chevron-down ml-auto"></i>
                        </a>
                        <ul id="orders" class="iq-submenu collapse {{ $activeOrderMenu ? 'show' : '' }}" data-parent="#iq-sidebar-toggle">
                            <li class="{{ Request::is('pedidos/pendientes*') ? 'active' : '' }}">
                                <a href="{{ route('pedidos.pendientes') }}"><span>Pedidos pendientes</span></a>
                            </li>
                            <li class="{{ Request::is('pedidos/completados*') ? 'active' : '' }}">
                                <a href="{{ route('pedidos.completados') }}"><span>Pedidos verificados</span></a>
                            </li>
                            <li class="{{ Request::is('pedidos/datos-pago*') ? 'active' : '' }}">
                                <a href="{{ route('pedidos.datos-pago') }}"><span>Datos de pago</span></a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- PRODUCTOS --}}
                @if($permisos['productos'])
                    @php
                        $activeProductMenu = Request::is('productos*') || Request::is('categorias*');
                    @endphp
                    <li>
                        <a href="#products" class="d-flex align-items-center {{ $activeProductMenu ? '' : 'collapsed' }}"
                           data-toggle="collapse"
                           aria-expanded="{{ $activeProductMenu ? 'true' : 'false' }}">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span class="ml-3">Productos</span>
                            <i class="fa-solid fa-chevron-down ml-auto"></i>
                        </a>
                        <ul id="products" class="iq-submenu collapse {{ $activeProductMenu ? 'show' : '' }}" data-parent="#iq-sidebar-toggle">
                            <li class="{{ Request::is('productos') ? 'active' : '' }}">
                                <a href="{{ route('productos.index') }}"><span>Productos</span></a>
                            </li>
                            <li class="{{ Request::is('productos/create') ? 'active' : '' }}">
                                <a href="{{ route('productos.create') }}"><span>Añadir producto</span></a>
                            </li>
                            <li class="{{ Request::is('categorias*') ? 'active' : '' }}">
                                <a href="{{ route('categorias.index') }}"><span>Categorías</span></a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- MIS COMPRAS --}}
                @if($permisos['mis_compras'])
                    <li class="{{ Route::currentRouteNamed('pos.online') ? 'active' : '' }}">
                        <a href="{{ route('pos.online') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-store"></i>
                            <span class="ml-3">Comprar</span>
                        </a>
                    </li>
                @endif


                {{-- USUARIOS --}}
                @if($permisos['usuarios'])
                    <li>
                        <a href="#users" class="d-flex align-items-center {{ Request::is('usuarios*') ? '' : 'collapsed' }}"
                        data-toggle="collapse" aria-expanded="{{ Request::is('usuarios*') ? 'true' : 'false' }}">
                            <i class="fa-solid fa-user"></i>
                            <span class="ml-3">Usuarios</span>
                            <i class="fa-solid fa-chevron-down ml-auto"></i>
                        </a>
                        <ul id="users" class="iq-submenu collapse {{ Request::is('usuarios*') ? 'show' : '' }}" data-parent="#iq-sidebar-toggle">
                            <li class="{{ Request::is('usuarios/administradores') ? 'active' : '' }}">
                                <a href="{{ route('usuarios.administradores') }}"><span>Administradores</span></a>
                            </li>
                            <li class="{{ Request::is('usuarios/socios') ? 'active' : '' }}">
                                <a href="{{ route('usuarios.socios') }}"><span>Socios</span></a>
                            </li>
                        </ul>
                    </li>
                @endif


                {{-- PROVEEDORES --}}
                @if($permisos['proveedores'])
                    <li class="{{ Request::is('proveedores*') ? 'active' : '' }}">
                        <a href="{{ route('proveedores.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-truck"></i>
                            <span class="ml-3">Proveedores</span>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->rol === 'socio')
                    <li class="{{ Route::currentRouteNamed('pedidos.misCompras') ? 'active' : '' }}">
                        <a href="{{ route('pedidos.misCompras') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-chart-line"></i>
                            <span class="ml-3">Mis compras</span>
                        </a>
                    </li>
                @endif

                {{-- PERFIL --}}
                @if($permisos['perfil'])
                    <li class="{{ Request::is('perfil*') ? 'active' : '' }}">
                        <a href="{{ route('perfil.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-id-badge"></i>
                            <span class="ml-3">Perfil</span>
                        </a>
                    </li>
                @endif

              

                {{-- SALIR --}}
                <li>
                    <a href="{{ route('logout') }}" class="d-flex align-items-center"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="ml-3">Salir</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
