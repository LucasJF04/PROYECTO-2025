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
                                        <button type="submit" class="btn btn-success border-none">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->subtotal }}</td>
                        <td>
                            <a href="{{ route('pos.deleteCart', $item->rowId) }}" class="btn btn-danger border-none">
                                <i class="fa-solid fa-trash mr-0"></i>
                            </a>
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
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#crearFacturaModal">
                    Registrar venta
                </button>
            </div>
        </div>

        <!-- === Productos con tarjetas === -->
        <div class="col-lg-6 col-md-12">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">

                    <!-- Buscador + Selección de Categoría -->
                    <form method="get" class="mb-3">
                        <div class="row">
                            <!-- Selección de categoría -->
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

                            <!-- Buscador -->
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

<!-- Modal Registrar Venta -->
<div class="modal fade" id="crearFacturaModal" tabindex="-1" role="dialog" aria-labelledby="crearFacturaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('pos.storePedido') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="crearFacturaModalLabel">Registrar Venta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Cliente</label>
                    <select class="form-control" name="id_cliente" required>
                        <option selected disabled>-- Seleccionar cliente --</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Total Productos</label>
                    <input type="text" class="form-control" value="{{ Cart::count() }}" readonly>
                </div>

                <div class="form-group col-md-3">
                    <label>Total</label>
                    <input type="text" class="form-control" value="{{ Cart::subtotal() }}" readonly>
                </div>

                <div class="form-group col-md-4">
                    <label>Método de pago</label>
                    <select class="form-control" name="metodo_pago" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label>Monto a pagar</label>
                    <input type="number" step="0.01" class="form-control" name="pago" value="{{ Cart::subtotal() }}">
                </div>

                

                <div class="form-group col-md-6">
                    <label>Tipo de entrega</label>
                    <input type="text" class="form-control" name="tipo_entrega" value="sucursal" readonly>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Registrar Venta</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>

</script>
<script src="{{ asset('js/filtroCategoria.js') }}"></script>
@endsection




