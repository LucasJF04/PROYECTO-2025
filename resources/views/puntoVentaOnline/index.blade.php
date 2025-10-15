@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <!-- === Carrito === -->
        <div class="col-lg-6 col-md-12 mb-3">
            <table class="table">
                <thead>
                    <tr class="ligth">
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>SubTotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productItem as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td style="min-width: 140px;">
                            <form action="{{ route('pos.updateCart', $item->rowId) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="number" class="form-control" name="qty" required value="{{ old('qty', $item->qty) }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success border-none"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->subtotal }}</td>
                        <td>
                            <a href="{{ route('pos.deleteCart', $item->rowId) }}" class="btn btn-danger border-none"><i class="fa-solid fa-trash mr-0"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row text-center mb-3">
                <div class="col-sm-6"><p class="h4 text-primary">Cantidad: {{ Cart::count() }}</p></div>
                <div class="col-sm-6"><p class="h4 text-primary">Total: {{ Cart::subtotal() }}</p></div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="button" id="btnPagar" class="btn btn-success">
                    Pagar
                </button>
            </div>
        </div>

        <!-- === Productos === -->
        <div class="col-lg-6 col-md-12">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <form method="get" class="mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <select class="form-control" id="categoriaSelect" name="categoria">
                                    <option value="">-- Filtrar por categoría --</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <input type="text" id="search" class="form-control" name="search" placeholder="Buscar producto" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text bg-primary">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                        <a href="{{ route('productos.index') }}" class="input-group-text bg-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        @forelse($productos as $producto)
                            @php
                                $rutaImagen = $producto->imagen_producto && file_exists(public_path('storage/products/' . $producto->imagen_producto))
                                    ? asset('storage/products/' . $producto->imagen_producto)
                                    : asset('assets/images/product/default.webp');
                            @endphp
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ $rutaImagen }}" class="card-img-top img-fluid rounded" alt="{{ $producto->nombre_producto }}">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{ $producto->nombre_producto }}</h6>
                                        <p class="text-primary font-weight-bold">${{ number_format($producto->precio_venta, 2) }}</p>
                                        <form action="{{ route('pos.addCart') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $producto->id }}">
                                            <input type="hidden" name="name" value="{{ $producto->nombre_producto }}">
                                            <input type="hidden" name="price" value="{{ $producto->precio_venta }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-cart-plus"></i> Agregar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center">No hay productos disponibles.</div>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center">{{ $productos->links() }}</div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- === Modal Pagar === -->
<div class="modal fade" id="pagarModal" tabindex="-1" role="dialog" aria-labelledby="pagarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{ route('pos.storePedido') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pagar Pedido</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_cliente" value="{{ auth()->user()->id }}">
          <input type="hidden" name="metodo_pago" value="transferencia">

          <div class="form-row">
            <!-- Totales y monto a pagar -->
            <div class="form-group col-md-4">
              <label>Total Productos</label>
              <input type="text" class="form-control" value="{{ Cart::count() }}" readonly>
            </div>

            <div class="form-group col-md-4">
              <label>Total</label>
              <input type="text" class="form-control" value="{{ Cart::subtotal() }}" readonly>
            </div>

            <div class="form-group col-md-4">
              <label>Monto a pagar</label>
              <input type="number" step="0.01" class="form-control" name="pago" value="0">
            </div>

            <!-- Tipo de entrega -->
            <div class="form-group col-md-7">
              <label>Tipo de entrega</label>
              <select class="form-control" name="tipo_entrega" id="tipo_entrega" required>
                <option value="sucursal">Recojo en sucursal</option>
                <option value="domicilio">Entrega en ubicación</option>
              </select>

              <!-- Mapa: aparece solo si es domicilio -->
              <div id="mapa_entrega_group" style="display:none; margin-top:10px;">
                <label>Selecciona ubicación en el mapa</label>
                <div id="mapa_entrega" style="height: 300px;"></div>
                <input type="hidden" name="latitud" id="latitud">
                <input type="hidden" name="longitud" id="longitud">
              </div>
            </div>

            <!-- Datos de pago siempre a la derecha -->
            <div class="col-md-5 text-center">
              <label>Datos de Pago (Transferencia)</label>
              @if(isset($infoPago))
                <div class="border rounded p-2 bg-light">
                  <p class="mb-1"><strong>Titular:</strong> {{ $infoPago->nombre_titular }}</p>
                  <p class="mb-1"><strong>Banco:</strong> {{ $infoPago->banco }}</p>
                  <p class="mb-1"><strong>N° Cuenta:</strong> {{ $infoPago->numero_cuenta }}</p>
                  <img src="{{ asset('storage/' . $infoPago->qr_imagen) }}" 
                       alt="QR Pago" 
                       class="img-fluid rounded mt-2" 
                       style="max-width: 100%;">
                </div>
              @else
                <p class="text-danger">No hay información de pago registrada.</p>
              @endif
            </div>

            <!-- Comprobante -->
            <div class="form-group col-md-12" style="margin-top:15px;">
              <label>Comprobante de pago (opcional)</label>
              <input type="file" class="form-control" name="comprobante_pago" accept="image/*,application/pdf">
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Confirmar Pago</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Mensaje flotante tipo toast -->
@if(session('error'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1051;">
    <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
    </div>
</div>
@endif

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="{{ asset('js/mapaEntrega.js') }}"></script>

<!-- Script para mostrar/ocultar mapa según tipo de entrega -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoEntrega = document.getElementById('tipo_entrega');
    const mapaGroup = document.getElementById('mapa_entrega_group');

    tipoEntrega.addEventListener('change', function() {
        if (this.value === 'domicilio') {
            mapaGroup.style.display = 'block';
        } else {
            mapaGroup.style.display = 'none';
        }
    });

    const btnPagar = document.getElementById('btnPagar');
    btnPagar.addEventListener('click', function() {
        const cartCount = {{ Cart::count() }};
        if (cartCount === 0) {
            window.location = "{{ route('carrito.vacio') }}";
            return;
        }
        $('#pagarModal').modal('show');
    });
});
</script>
@endsection
