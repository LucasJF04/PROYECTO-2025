@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <!-- Total Gastado -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card text-white bg-success rounded-lg shadow-sm">
                <div class="card-body text-center py-3">
                    <i class="ri-money-dollar-circle-line" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Total Gastado</h6>
                    <h4>Bs. {{ number_format($total_gastado, 2) }}</h4>
                </div>
            </div>
        </div>

        <!-- Pedidos en Proceso -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card text-white bg-warning rounded-lg shadow-sm">
                <div class="card-body text-center py-3">
                    <i class="ri-truck-line" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Pedidos en Proceso</h6>
                    <h4>{{ $pedidos_proceso }}</h4>
                </div>
            </div>
        </div>

        <!-- Pedidos Entregados -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card text-white bg-primary rounded-lg shadow-sm">
                <div class="card-body text-center py-3">
                    <i class="ri-check-line" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Pedidos Entregados</h6>
                    <h4>{{ $pedidos_entregados }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Últimos Pedidos -->
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header bg-primary text-white">
                    <i class="ri-shopping-cart-line"></i> Últimos Pedidos
                </div>
                <div class="card-body">
                    @if($ultimos_pedidos->isEmpty())
                        <p class="text-center text-muted">No tienes pedidos recientes.</p>
                    @else
                    <ul class="list-group">
                        @foreach($ultimos_pedidos as $pedido)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Pedido #{{ $pedido->id_pedido }} - Bs. {{ number_format($pedido->pago, 2) }}
                                <span class="badge 
                                    @if($pedido->estado_pedido == 'entregado') bg-success
                                    @elseif($pedido->estado_pedido == 'en_proceso') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst(str_replace('_',' ',$pedido->estado_pedido)) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    @endif
                </div>
            </div>
        </div>

        <!-- Productos Favoritos / Más Comprados -->
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header bg-success text-white">
                    <i class="ri-star-line"></i> Productos Favoritos
                </div>
                <div class="card-body">
                    @if($productos_favoritos->isEmpty())
                        <p class="text-center text-muted">No hay productos favoritos aún.</p>
                    @else
                        <ul class="list-group">
                            @foreach($productos_favoritos as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $producto->nombre_producto }}
                                    <span class="badge bg-primary">{{ $producto->cantidad ?? 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
