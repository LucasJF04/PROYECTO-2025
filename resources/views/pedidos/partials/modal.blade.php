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
                    <!-- Columna izquierda: Detalles -->
                    <div class="col-md-7">
                        <div id="contenidoDetallePedido">
                            <p class="text-center">Cargando...</p>
                        </div>
                    </div>
                    <!-- Columna derecha: Comprobante -->
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
