@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <!-- Columna izquierda: CARRITO -->
        <div class="col-md-4">
    <div class="card shadow-sm card-carrito"> {{-- quitamos h-100 --}}
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-shopping-cart"></i> Carrito</h5>
        </div>
        <div class="card-body carrito-body p-0"> {{-- quitamos altura fija --}}
            @php $total = 0; @endphp
            @if(!empty($carrito))
                <ul class="list-group list-group-flush">
                    @foreach($carrito as $key => $item)
                        @php 
                            $subtotal = $item['price'] * $item['qty']; 
                            $total += $subtotal; 
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item['name'] }}</strong><br>
                                <small>
                                    <form action="{{ route('cliente.updateCart') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $key }}">
                                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" style="width:50px">
                                        <button type="submit" class="btn btn-sm btn-success">✔</button>
                                    </form>
                                    x ${{ number_format($item['price'], 2) }}
                                </small>
                            </div>
                            <div>
                                <span class="text-primary">${{ number_format($subtotal, 2) }}</span>
                                <form action="{{ route('cliente.removeCart') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <button type="submit" class="btn btn-sm btn-danger ml-2">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-3 text-center text-muted">
                    <i class="fa fa-info-circle"></i> Carrito vacío
                </div>
            @endif
        </div>
        <div class="card-footer">
            <p class="mb-1">Total: <strong>${{ number_format($total, 2) }}</strong></p>

            @if(!empty($carrito))
                <div class="d-flex justify-content-between mt-3">
                    <form action="{{ route('cliente.clearCart') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times"></i> Vaciar
                        </button>
                    </form>

                    <form action="{{ route('cliente.pagar') }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPago">
                            <i class="fa fa-money-bill"></i> Pagar
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>


        <!-- Columna derecha: PRODUCTOS -->
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fa fa-box"></i> Productos</h4>
                <form class="form-inline" method="GET" action="{{ route('clientes.tienda') }}">
                    <input type="text" class="form-control" name="search" placeholder="Buscar producto..." value="{{ request('search') }}">
                    <button class="btn btn-primary ml-2"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <div class="row">
                @forelse($productos as $producto)
                    @php
                        $rutaImagen = $producto->imagen_producto 
                            && file_exists(public_path('storage/products/' . $producto->imagen_producto))
                            ? asset('storage/products/' . $producto->imagen_producto)
                            : asset('assets/images/product/sinfoto.png');
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ $rutaImagen }}" class="card-img-top img-producto" alt="{{ $producto->nombre_producto }}">
                            <div class="card-body text-center">
                                <h6 class="card-title">{{ $producto->nombre_producto }}</h6>
                                <p class="text-primary font-weight-bold">${{ number_format($producto->precio_venta, 2) }}</p>
                                <form action="{{ route('cliente.addCart') }}" method="POST" class="d-flex justify-content-center">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $producto->id_producto }}">
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
                        <div class="alert alert-warning text-center">
                            No hay productos disponibles.
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
<!-- Modal Pago -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalPagoLabel">Confirmar Pago</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form action="{{ route('cliente.pagar') }}" method="POST">
        @csrf
        <div class="modal-body">
            <h6>Detalle del Pedido</h6>
            <ul class="list-group mb-3">
                @foreach($carrito as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item['name'] }} (x{{ $item['qty'] }})
                        <span>${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <p><strong>Total a pagar: ${{ number_format($total, 2) }}</strong></p>

            <div class="form-group mt-3">
                <label for="metodo_pago">Método de pago</label>
                <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                </select>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Confirmar Pago</button>
        </div>
      </form>

    </div>
  </div>
</div>

