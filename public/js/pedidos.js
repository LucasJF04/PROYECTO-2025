$(document).ready(function () {
    $('.btn-detalle-pedido').click(function () {
        var idPedido = $(this).data('id');
        $('#detallePedidoModal').modal('show');
        $('#contenidoDetallePedido').html('<p class="text-center">Cargando...</p>');
        $('#imagenComprobante').html('<p class="text-muted">Sin comprobante disponible</p>');

        $.ajax({
            url: '/pedidos/detalles/' + idPedido,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
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
                         <p><strong>Total Pedido:</strong> $${parseFloat(response.pedido.total).toFixed(2)}</p>
                         <p><strong>Total Pagado:</strong> $${parseFloat(response.pedido.pago).toFixed(2)}</p>`;

                $('#contenidoDetallePedido').html(html);

                if (response.comprobante) {
                    const ext = response.comprobante.split('.').pop().toLowerCase();
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                        $('#imagenComprobante').html(`<img src="${response.comprobante}" alt="Comprobante" style="width:100%; height:400px; object-fit:contain;" class="rounded shadow">`);
                    } else if (ext === 'pdf') {
                        $('#imagenComprobante').html(`<iframe src="${response.comprobante}" style="width:100%; height:400px;" frameborder="0"></iframe>`);
                    } else {
                        $('#imagenComprobante').html('<p class="text-muted">Tipo de archivo no soportado</p>');
                    }
                } else {
                    $('#imagenComprobante').html('<p class="text-muted">Sin comprobante disponible</p>');
                }
            },
            error: function () {
                $('#contenidoDetallePedido').html('<p class="text-danger text-center">Error al cargar los detalles.</p>');
                $('#imagenComprobante').html('<p class="text-muted">Sin comprobante disponible</p>');
            }
        });
    });
});
