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

            <!-- Cabecera y acciones -->
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <h4 class="mb-3">Lista de pedidos con monto pendiente</h4>
                <a href="{{ route('pedidos.pendientesPago') }}" class="btn btn-danger add-list">
                    <i class="fa-solid fa-trash mr-3"></i>Eliminar búsqueda
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="col-lg-12">
            <form action="{{ route('pedidos.pendientesPago') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">

                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Filas:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="row">
                                @foreach ([10,25,50,100] as $r)
                                    <option value="{{ $r }}" @if(request('row') == $r) selected @endif>{{ $r }}</option>
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

        <!-- Tabla de pedidos pendientes -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>N°</th>
                            <th>Factura N°</th>
                            <th>Nombre del Cliente</th>
                            <th>Fecha del Pedido</th>
                            <th>Método de Pago</th>
                            <th>Pagado</th>
                            <th>Pendiente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ ($pedidos->currentPage() - 1) * $pedidos->perPage() + $loop->iteration }}</td>
                            <td>{{ $pedido->nro_factura ?? '-' }}</td>
                            <td>
                                {{ $pedido->cliente->nombre ?? '-' }}
                                {{ $pedido->cliente->apellido_Paterno ?? '' }}
                                {{ $pedido->cliente->apellido_Materno ?? '' }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d-m-Y') }}</td>
                            <td>{{ ucfirst($pedido->estado_pago ?? '-') }}</td>
                            <td><span class="btn btn-warning text-white">${{ number_format($pedido->pago ?? 0, 2) }}</span></td>
                            <td><span class="btn btn-danger text-white">${{ number_format($pedido->pendiente ?? 0, 2) }}</span></td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="btn btn-info mr-2" title="Detalles" href="{{ route('pedidos.detalles', $pedido->id_pedido) }}">
                                        Detalles
                                    </a>
                                    <button type="button" class="btn btn-primary-dark mr-2" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="payDue({{ $pedido->id_pedido }})">
                                        Pagar Pendiente
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $pedidos->links() }}
        </div>

    </div>
</div>

<!-- Modal para pagar pendiente -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pedidos.actualizarPendiente') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" id="order_id">
                <div class="modal-body">
                    <h3 class="modal-title text-center">Pagar Pendiente</h3>
                    <div class="form-group">
                        <label for="due">Monto a Pagar</label>
                        <input type="text" class="form-control bg-white @error('due') is-invalid @enderror" id="due" name="due">
                        @error('due')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Pagar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function payDue(id){
    $.ajax({
        type: 'GET',
        url: '/order/due/' + id,
        dataType: 'json',
        success: function(data) {
            $('#due').val(data.pendiente);
            $('#order_id').val(data.id_pedido);
        }
    });
}
</script>
@endsection
