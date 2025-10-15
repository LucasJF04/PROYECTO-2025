@extends('panel.body.main')
@include('pedidos.partials.modal')

@section('container')
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <h4 class="mb-3">Pedidos verificados</h4>
                <a href="{{ route('pedidos.completados') }}" class="btn btn-danger add-list">
                    <i class="fa-solid fa-trash mr-3"></i>Borrar búsqueda
                </a>
            </div>
        </div>

        <!-- === Filtros === -->
        <div class="col-lg-12">
            <form action="{{ route('pedidos.completados') }}" method="get" class="mb-3">
                <div class="row align-items-end">

                    <!-- Tipo de entrega -->
                    <div class="col-md-3 mb-2">
                        <label for="tipo_entrega" class="font-weight-bold">Tipo de entrega:</label>
                        <select name="tipo_entrega" id="tipo_entrega" class="form-control">
                            <option value="">Todos</option>
                            <option value="sucursal" {{ request('tipo_entrega') == 'sucursal' ? 'selected' : '' }}>Sucursal</option>
                            <option value="domicilio" {{ request('tipo_entrega') == 'domicilio' ? 'selected' : '' }}>Domicilio</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-2 mb-2">
                        <label for="estado" class="font-weight-bold">Estado:</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="">Todos</option>
                            @foreach(['verificado','en_proceso','listo','en_despacho','entregado'] as $estado)
                                <option value="{{ $estado }}" @if(request('estado') == $estado) selected @endif>
                                    {{ ucfirst(str_replace('_', ' ', $estado)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    

                    <!-- Fecha inicio -->
                    <div class="col-md-2 mb-2">
                        <label for="fecha_inicio" class="font-weight-bold">Desde:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                               value="{{ request('fecha_inicio') }}">
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-2 mb-2">
                        <label for="fecha_fin" class="font-weight-bold">Hasta:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
                               value="{{ request('fecha_fin') }}">
                    </div>

                    <!-- Buscar pedido -->
                    <div class="col-md-3 mb-2">
                        <label for="search" class="font-weight-bold">Buscar:</label>
                        <div class="input-group">
                            <input type="text" id="search" class="form-control" name="search" placeholder="N° pedido o cliente..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <!-- === Tabla de pedidos completados === -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3 shadow-sm">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>N°</th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total Pagado</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($pedidos as $pedido)
                        <tr>
                            <td>{{ ($pedidos->currentPage() - 1) * $pedidos->perPage() + $loop->iteration }}</td>
                            <td>Pedido #{{ $pedido->id_pedido }}</td>
                            <td>{{ $pedido->cliente->nombre ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d-m-Y') }}</td>
                            <td>${{ number_format($pedido->pago ?? 0, 2) }}</td>

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
                                @if(auth()->user()->rol === 'administrador')
                                    <form action="{{ route('pedidos.cambiarEstado', $pedido->id_pedido) }}" method="POST">
                                        @csrf
                                        <select name="estado" class="form-control form-control-sm {{ $pedido->colorEstado() }}" onchange="this.form.submit()">
                                            @foreach(['pendiente','verificado','en_proceso','listo','en_despacho','entregado'] as $estado)
                                                <option value="{{ $estado }}" class="{{ $pedido->colorEstado() }}" @if($pedido->estado_pedido === $estado) selected @endif>
                                                    {{ ucfirst(str_replace('_', ' ', $estado)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    <span class="badge {{ $pedido->colorEstado() }}">{{ ucfirst(str_replace('_', ' ', $pedido->estado_pedido)) }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a href="javascript:void(0)" class="btn btn-info mr-2 btn-detalle-pedido" data-id="{{ $pedido->id_pedido }}">
                                        Detalles
                                    </a>
                                    <a class="btn btn-success mr-2" href="{{ route('pedidos.nota', $pedido->id_pedido) }}">
                                        Descargar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay pedidos completados.</td>
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
@endsection

@section('specificpagescripts')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="{{ asset('js/pedidos.js') }}"></script>

<script>
let map;

$('#mapaPedidoModal').on('shown.bs.modal', function (e) {
    const button = $(e.relatedTarget); // botón que abrió el modal
    const coordenadas = button.data('coordenadas')?.split(',');
    if (!coordenadas) return;

    const lat = parseFloat(coordenadas[0]);
    const lng = parseFloat(coordenadas[1]);

    if (map) {
        map.remove();
    }

    map = L.map('mapaPedido').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map);
});
</script>
@endsection
