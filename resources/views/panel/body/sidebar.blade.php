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

                {{-- VENTAS --}}
                @if($permisos['ventas'])
                    <li class="{{ Request::is('pos*') ? 'active' : '' }}">
                        <a href="{{ route('pos.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="ml-3">Vender</span>
                        </a>
                    </li>
                @endif

                {{-- PEDIDOS --}}
                @if($permisos['pedidos'])
                    <li>
                        <a href="#orders" class="collapsed d-flex align-items-center" data-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-box"></i>
                            <span class="ml-3">Pedidos</span>
                            <i class="fa-solid fa-chevron-down ml-auto"></i>
                        </a>
                        <ul id="orders" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class="{{ Request::is('pedidos/pendientes*') ? 'active' : '' }}">
                                <a href="{{ route('pedidos.pendientes') }}"><i class="fa-solid fa-circle-arrow-right"></i><span>Pedidos pendientes</span></a>
                            </li>
                            <li class="{{ Request::is('pedidos/completados*') ? 'active' : '' }}">
                                <a href="{{ route('pedidos.completados') }}"><i class="fa-solid fa-circle-arrow-right"></i><span>Pedidos completos</span></a>
                            </li>
                            <li class="{{ Request::is('pedidos/pendientes-pago*') ? 'active' : '' }}">
                                <a href="{{ route('pedidos.pendientesPago') }}"><i class="fa-solid fa-circle-arrow-right"></i><span>Pendiente a vencer</span></a>
                            </li>
                            <li class="{{ Request::is('stock*') ? 'active' : '' }}">
                                <a href="{{ route('pedidos.stock') }}"><i class="fa-solid fa-circle-arrow-right"></i><span>Gestión de inventarios</span></a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- PRODUCTOS --}}
                @if($permisos['productos'])
                    <li>
                        <a href="#products" class="collapsed d-flex align-items-center" data-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span class="ml-3">Productos</span>
                            <i class="fa-solid fa-chevron-down ml-auto"></i>
                        </a>
                        <ul id="products" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li><a href="{{ route('productos.index') }}"><i class="fa-solid fa-circle-arrow-right"></i><span>Productos</span></a></li>
                            <li><a href="{{ route('productos.create') }}"><i class="fa-solid fa-circle-arrow-right"></i><span>Añadir producto</span></a></li>
                            <li><a href="{{ route('categorias.index') }}"><i class="fa-solid fa-circle-arrow-right"></i><span>Categorías</span></a></li>
                        </ul>
                    </li>
                @endif

                {{-- NUEVO: CATÁLOGO --}}
                @if($permisos['catalogo'])
                <li class="{{ Request::is('catalogo*') ? 'active' : '' }}">
                    <a href="{{ route('catalogo.index') }}" class="d-flex align-items-center">
                        <i class="fa-solid fa-store"></i>
                        <span class="ml-3">Mis compras</span>
                    </a>
                </li>
                @endif

                {{-- NUEVO: CARRITO --}}
                <li class="{{ Request::is('carrito*') ? 'active' : '' }}">
                    <a href="{{ route('carrito.index') }}" class="d-flex align-items-center">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <span class="ml-3">Mis pedidos</span>
                    </a>
                </li>

                {{-- EMPLEADOS --}}
                @if($permisos['empleados'])
                    <li>
                        <a href="{{ route('empleados.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="ml-3">Empleados</span>
                        </a>
                    </li>
                @endif

                {{-- CLIENTES --}}
                @if($permisos['clientes'])
                    <li>
                        <a href="{{ route('clientes.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-users"></i>
                            <span class="ml-3">Clientes-Socios</span>
                        </a>
                    </li>
                @endif

                {{-- PROVEEDORES --}}
                @if($permisos['proveedores'])
                    <li>
                        <a href="{{ route('proveedores.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-truck"></i>
                            <span class="ml-3">Proveedores</span>
                        </a>
                    </li>
                @endif

                {{-- ROLES Y PERMISOS --}}
                @if($permisos['rolesPermisos'])
                    <li>
                        <a href="{{ route('roles.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-key"></i>
                            <span class="ml-3">Roles - Permisos</span>
                        </a>
                    </li>
                @endif

                {{-- USUARIOS --}}
                @if($permisos['usuarios'])
                    <li>
                        <a href="{{ route('usuarios.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-user"></i>
                            <span class="ml-3">Usuarios</span>
                        </a>
                    </li>
                @endif

                {{-- PERFIL --}}
                @if($permisos['perfil'])
                    <li>
                        <a href="{{ route('perfil.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-id-badge"></i>
                            <span class="ml-3">Perfil</span>
                        </a>
                    </li>
                @endif

                {{-- REPORTES --}}
                @if($permisos['reportes'])
                    <li class="{{ Request::is('reportes*') ? 'active' : '' }}">
                        <a href="{{ route('reportes.index') }}" class="d-flex align-items-center">
                            <i class="fa-solid fa-history"></i>
                            <span class="ml-3">Historial</span>
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
