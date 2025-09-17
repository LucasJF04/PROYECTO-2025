@extends('panel.body.main')

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
                <h4 class="mb-3">Lista de pedidos completos</h4>
                <a href="{{ route('pedidos.completados') }}" class="btn btn-danger add-list">
                    <i class="fa-solid fa-trash mr-3"></i>Borrar búsqueda
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="col-lg-12">
            <form action="{{ route('pedidos.completados') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Filas:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="row">
                                @foreach([10,25,50,100] as $num)
                                    <option value="{{ $num }}" @if(request('row') == $num) selected @endif>{{ $num }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 align-self-center" for="search">Buscar:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" name="search" placeholder="Buscar pedido" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-primary">
                                        <i class="fa-solid fa-magnifying-glass font-size-20"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de pedidos completos -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>N°</th>
                            <th>Factura N°</th>
                            <th>Nombre del Cliente</th>
                            <th>Fecha del Pedido</th>
                            <th>Total Pagado</th>
                            <th>Método de Pago</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td>{{ $order->nro_factura }}</td>
                            <td>{{ $order->cliente->nombre ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->fecha_pedido)->format('d-m-Y') }}</td>
                            <td>{{ $order->pago }}</td>
                            <td>{{ $order->estado_pago }}</td>
                            <td>
                                <span class="badge badge-success">{{ ucfirst($order->estado_pedido) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="btn btn-info mr-2" href="{{ route('pedidos.detalles', $order->id_pedido) }}">
                                        Detalles
                                    </a>
                                    <a class="btn btn-success mr-2" href="{{ route('pedidos.factura', $order->id_pedido) }}">
                                        Imprimir
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            {{ $pedidos->links() }}
        </div>

    </div>
</div>
@endsection
