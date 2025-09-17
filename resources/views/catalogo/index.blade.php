@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <!-- Columna izquierda: CARRITO -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100 card-carrito">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-shopping-cart"></i> Carrito</h5>
                </div>
                <div class="card-body carrito-body">
                    @if(Cart::count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach(Cart::content() as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item->name }}</strong><br>
                                        <small>
                                            <form action="{{ route('cliente.addCart') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <input type="hidden" name="name" value="{{ $item->name }}">
                                                <input type="hidden" name="price" value="{{ $item->price }}">
                                                <input type="number" name="qty" value="{{ $item->qty }}" style="width:50px">
                                                <button type="submit" class="btn btn-sm btn-success">✔</button>
                                            </form>
                                            x ${{ number_format($item->price, 2) }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="text-primary">${{ number_format($item->subtotal, 2) }}</span>
                                        <form action="{{ route('cliente.addCart') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="remove" value="{{ $item->rowId }}">
                                            <button type="submit" class="btn btn-sm btn-danger ml-2"><i class="fa fa-trash"></i></button>
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
                    <p class="mb-1">Subtotal: <strong>${{ Cart::subtotal() }}</strong></p>
                    <p class="mb-1">Total: <strong>${{ Cart::total() }}</strong></p>

                    @if(Cart::count() > 0)
                        <div class="d-flex justify-content-between mt-3">
                            <form action="{{ route('cliente.clearCart') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Vaciar</button>
                            </form>

                            <form action="{{ route('cliente.pagar') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success"><i class="fa fa-money-bill"></i> Pagar</button>
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
                <form class="form-inline" method="GET" action="{{ route('cliente.tienda') }}">
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
                                <form action="{{ route('cliente.addCart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $producto->id }}">
                                    <input type="hidden" name="name" value="{{ $producto->nombre_producto }}">
                                    <input type="hidden" name="price" value="{{ $producto->precio_venta }}">
                                    <button type="submit" class="btn btn-success btn-sm btn-block">
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

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
