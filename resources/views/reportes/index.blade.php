@extends('panel.body.main')

@section('container')
<div class="container-fluid">

    @if (session()->has('success'))
        <div class="alert text-white bg-success" role="alert">
            <div class="iq-alert-text">{{ session('success') }}</div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h4 class="mb-3">Mis compras</h4>
    </div>

    <div class="table-responsive rounded mb-3">
        <table class="table table-bordered mb-0" style="min-width: 1000px;">
            <thead class="bg-white text-uppercase">
                <tr class="ligth ligth-data">
                    <th>N°</th>
                    <th>Pedido</th>
                    <th>Fecha del Pedido</th>
                    <th>Total Pagado</th>
                    <th>Método de Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="ligth-body">
            @forelse ($pedidos as $pedido)
            <tr>
                <td>{{ ($pedidos->currentPage() - 1) * $pedidos->perPage() + $loop->iteration }}</td>
                <td>Pedido #{{ $pedido->id_pedido }}</td>
                <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d-m-Y') }}</td>
                <td>${{ number_format($pedido->pago ?? 0, 2) }}</td>
                <td>{{ ucfirst($pedido->metodo_pago ?? '-') }}</td>
                <td>
                    <span class="badge {{ $pedido->colorEstado() }}">
                        {{ ucfirst(str_replace('_', ' ', $pedido->estado_pedido)) }}
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center list-action">
                        <a href="javascript:void(0)" class="btn btn-info mr-2 btn-detalle-pedido" data-id="{{ $pedido->id_pedido }}">
                            Ver Detalles
                        </a>
                        <a class="btn btn-success mr-2" href="{{ route('pedidos.nota', $pedido->id_pedido) }}">Descargar</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No tienes compras registradas.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $pedidos->links() }}

</div>
@endsection

<!-- Modal Detalles del Pedido -->
<div class="modal fade" id="detallePedidoModal" tabindex="-1" role="dialog" aria-labelledby="detallePedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Pedido</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div id="contenidoDetallePedido">
                            <p class="text-center">Cargando...</p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div id="imagenComprobante" class="text-center">
                            <p class="text-muted">Sin comprobante disponible</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@section('specificpagescripts')
<script>
$(document).ready(function() {
    $('.btn-detalle-pedido').click(function() {
        var idPedido = $(this).data('id');
        $('#detallePedidoModal').modal('show');
        $('#contenidoDetallePedido').html('<p class="text-center">Cargando...</p>');
        $('#imagenComprobante').html('<p class="text-muted">Sin comprobante disponible</p>');

        $.ajax({
            url: '/pedidos/detalles/' + idPedido,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let html = `<h5>Pedido #${response.pedido.id}</h5>
                            <p><strong>Cliente:</strong> ${response.pedido.cliente}</p>
                            <p><strong>Fecha:</strong> ${new Date(response.pedido.fecha).toLocaleDateString()}</p>
                            <p><strong>Método de Pago:</strong> ${response.pedido.metodo_pago ?? '-'}</p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                response.detalles.forEach(detalle => {
                    html += `<tr>
                                <td>${detalle.producto}</td>
                                <td>${detalle.cantidad}</td>
                                <td>$${parseFloat(detalle.costo_unitario).toFixed(2)}</td>
                                <td>$${parseFloat(detalle.total).toFixed(2)}</td>
                             </tr>`;
                });

                html += `</tbody></table>
                         <p><strong>Total Pedido:</strong> $${parseFloat(response.pedido.pago).toFixed(2)}</p>`;

                $('#contenidoDetallePedido').html(html);

                if(response.comprobante){
                    const ext = response.comprobante.split('.').pop().toLowerCase();
                    if(['jpg','jpeg','png','gif'].includes(ext)){
                        $('#imagenComprobante').html(`<img src="${response.comprobante}" alt="Comprobante" style="width:100%; height:400px; object-fit:contain;" class="rounded shadow">`);
                    } else if(ext === 'pdf'){
                        $('#imagenComprobante').html(`<iframe src="${response.comprobante}" style="width:100%; height:400px;" frameborder="0"></iframe>`);
                    } else {
                        $('#imagenComprobante').html('<p class="text-muted">Tipo de archivo no soportado</p>');
                    }
                } else {
                    $('#imagenComprobante').html('<p class="text-muted">Sin comprobante disponible</p>');
                }
            },
            error: function() {
                $('#contenidoDetallePedido').html('<p class="text-danger text-center">Error al cargar los detalles.</p>');
                $('#imagenComprobante').html('<p class="text-muted">Sin comprobante disponible</p>');
            }
        });
    });
});
</script>
@endsection

<style>
.table th, .table td {
    white-space: nowrap;
}
</style>
