@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">

        <!-- Datos del cliente y del pedido -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Detalles del Pedido</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Cliente</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->cliente->nombre ?? '-' }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->cliente->correo ?? '-' }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Teléfono</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->cliente->telefono ?? '-' }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Fecha del Pedido</label>
                            <input type="text" class="form-control bg-white" value="{{ \Carbon\Carbon::parse($order->fecha_pedido)->format('d-m-Y') }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Factura N°</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->nro_factura ?? '-' }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Estado de Pago</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->estado_pago ?? '-' }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Pagado</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->pago ?? 0 }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Pendiente</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->pendiente ?? 0 }}" readonly>
                        </div>
                    </div>

                    @if ($order->estado_pedido === 'pending')
                        <form action="{{ route('pedidos.actualizarEstado') }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $order->id_pedido }}">
                            <button type="submit" class="btn btn-success">Completar Pedido</button>
                            <a href="{{ route('pedidos.pendientes') }}" class="btn btn-danger">Cancelar</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Productos del pedido -->
        <div class="col-lg-12 mt-3">
            <div class="table-responsive rounded mb-3">
                <table class="table table-bordered mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr>
                            <th>No.</th>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orderDetails as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $item->producto->imagen ?? asset('storage/products/default.webp') }}" class="avatar-60 rounded">
                            </td>
                            <td>{{ $item->producto->nombre ?? '-' }}</td>
                            <td>{{ $item->producto->codigo ?? '-' }}</td>
                            <td>{{ $item->cantidad ?? 0 }}</td>
                            <td>{{ number_format($item->precio_unitario ?? 0, 2) }}</td>
                            <td>{{ number_format($item->total ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay productos en este pedido.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
