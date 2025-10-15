@extends('panel.body.main')
@include('pedidos.partials.modal')

@section('container')
<div class="container-fluid">
    <div class="row">

        <!-- Mensaje de éxito -->
        <div class="col-lg-12">
            @if (session()->has('success'))
                <div class="alert text-white bg-success" role="alert">
                    <div class="iq-alert-text">{{ session('success') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif

            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <h4 class="mb-3">Pedidos Pendientes</h4>
                <a href="{{ route('pedidos.pendientes') }}" class="btn btn-danger add-list">
                    <i class="fa-solid fa-trash mr-3"></i>Borrar búsqueda
                </a>
            </div>
        </div>

       <!-- Filtros -->
<div class="col-lg-12">
    <form action="{{ route('pedidos.pendientes') }}" method="get" class="mb-3">
        <div class="row align-items-end">

            <!-- Tipo de entrega (izquierda) -->
            <div class="col-md-4 mb-3">
                <label for="tipo_entrega" class="form-label">Tipo de entrega:</label>
                <div class="input-group">
                    <select name="tipo_entrega" id="tipo_entrega" class="form-control">
                        <option value="">Todos</option>
                        <option value="sucursal" {{ request('tipo_entrega') == 'sucursal' ? 'selected' : '' }}>Sucursal</option>
                        <option value="domicilio" {{ request('tipo_entrega') == 'domicilio' ? 'selected' : '' }}>Domicilio</option>
                    </select>
                   
                </div>
            </div>

            <!-- Fechas y buscador (derecha) -->
            <div class="col-md-8">
                <div class="row">

                    <!-- Desde -->
                    <div class="col-md-4 mb-3">
                        <label for="fecha_inicio" class="form-label">Desde:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                               value="{{ request('fecha_inicio') }}">
                    </div>

                    <!-- Hasta -->
                    <div class="col-md-4 mb-3">
                        <label for="fecha_fin" class="form-label">Hasta:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                               value="{{ request('fecha_fin') }}">
                    </div>

                    <!-- Buscar pedido -->
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Buscar pedido:</label>
                        <div class="input-group">
                            <input type="text" id="search" class="form-control" name="search"
                                   placeholder="N° pedido o cliente..." value="{{ request('search') }}">
                            <button type="submit" class="input-group-text bg-primary text-white">
                                <i class="fa-solid fa-magnifying-glass font-size-20"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>


        <!-- Tabla de pedidos -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table table-bordered mb-0" style="min-width: 900px;">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th style="min-width: 50px;">N°</th>
                            <th style="min-width: 100px;">Pedido</th>
                            <th style="min-width: 150px;">Cliente</th>
                            <th style="min-width: 120px;">Fecha</th>
                            <th style="min-width: 120px;">Total Pagado</th>
                            <th style="min-width: 150px;">Ubicación</th>
                            <th style="min-width: 100px;">Estado</th>
                            <th style="min-width: 200px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($pedidos as $pedido)
                        <tr>
                            <td>{{ ($pedidos->currentPage() - 1) * $pedidos->perPage() + $loop->iteration }}</td>
                            <td>Pedido #{{ $pedido->id_pedido }}</td>
                            <td>{{ $pedido->cliente->nombre ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d-m-Y') }}</td>
                            <td>${{ number_format($pedido->pendiente ?? 0, 2) }}</td>

                            <!-- Ubicación -->
                            <td>
                                @if($pedido->tipo_entrega == 'domicilio' && $pedido->direccion_entrega)
                                    <a href="#" class="btn btn-sm btn-primary btn-ver-mapa" data-toggle="modal" data-target="#mapaPedidoModal"
                                       data-coordenadas="{{ $pedido->direccion_entrega }}">
                                       Ver Mapa
                                    </a>
                                @else
                                    Sucursal
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-{{ $pedido->estado_pedido == 'pendiente' ? 'danger' : 'success' }}">
                                    {{ ucfirst($pedido->estado_pedido ?? '-') }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <!-- Botón de detalles -->
                                    <a href="javascript:void(0)" class="btn btn-info mr-2 btn-detalle-pedido" data-id="{{ $pedido->id_pedido }}">
                                        Detalles
                                    </a>

                                    <!-- Botón de editar -->
                                    <a href="javascript:void(0)" class="btn btn-warning mr-2 btn-editar-pedido" data-id="{{ $pedido->id_pedido }}">
                                        Editar
                                    </a>

                                    @if($pedido->comprobante_pago && $pedido->estado_pedido == \App\Models\Pedido::STATUS_PENDING)
                                        <form action="{{ route('pedidos.actualizarEstado') }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <input type="hidden" name="id_pedido" value="{{ $pedido->id_pedido }}">
                                            <input type="hidden" name="estado" value="{{ \App\Models\Pedido::STATUS_VERIFIED }}">
                                            <button type="submit" class="btn btn-success btn-sm">Validar Pago</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay pedidos pendientes.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $pedidos->links() }}
        </div>

    </div>
</div>

<!-- Modal para mostrar ubicación en Leaflet -->
<div class="modal fade" id="mapaPedidoModal" tabindex="-1" role="dialog" aria-labelledby="mapaPedidoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Ubicación del Pedido</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div id="mapaPedido" style="width:100%; height:400px;"></div>
          </div>
      </div>
  </div>
</div>

<!-- Modal de edición de pedido -->
<div class="modal fade" id="editarPedidoModal" tabindex="-1" role="dialog" aria-labelledby="editarPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <form id="formEditarPedido" method="POST" action="{{ route('pedidos.actualizarPedido') }}">
    @csrf
    <input type="hidden" name="id_pedido" id="editar_id_pedido">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Cliente:</label>
                        <input type="text" class="form-control" id="editar_nombre_cliente" disabled>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaEditarProductos">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llenará con JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Actualizar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('specificpagescripts')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="{{ asset('js/pedidos.js') }}"></script>

<script>
let map;

$('#mapaPedidoModal').on('shown.bs.modal', function (e) {
    const button = $(e.relatedTarget);
    const coordenadas = button.data('coordenadas')?.split(',');
    if (!coordenadas) return;

    const lat = parseFloat(coordenadas[0]);
    const lng = parseFloat(coordenadas[1]);

    if (map) {
        map.remove();
    }

    map = L.map('mapaPedido').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map);
});

// Botón editar pedido
$('.btn-editar-pedido').click(function(){
    const id_pedido = $(this).data('id');
    $.ajax({
        url: '/pedidos/detalles/' + id_pedido,
        method: 'GET',
        success: function(res){
            $('#editar_id_pedido').val(res.pedido.id);
            $('#editar_nombre_cliente').val(res.pedido.cliente);

            const tbody = $('#tablaEditarProductos tbody');
            tbody.empty();
            res.detalles.forEach(detalle => {
                tbody.append(`
                    <tr>
                        <td>${detalle.producto}</td>
                        <td><input type="number" name="cantidades[${detalle.producto}]" value="${detalle.cantidad}" class="form-control" min="1"></td>
                        <td>${detalle.costo_unitario}</td>
                        <td>${detalle.total}</td>
                    </tr>
                `);
            });

            $('#editarPedidoModal').modal('show');
        }
    });
});
</script>
@endsection
