<!DOCTYPE html>
<html lang="es">
<head>
    <title>Factura - POS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
</head>
<body>
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="invoice-inner-9" id="invoice_wrapper">

                <!-- Encabezado -->
                <div class="invoice-top row">
                    <div class="col-6">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo">
                    </div>
                    <div class="col-6 text-end">
                        <h1>#{{ $order->nro_factura ?? '-' }}</h1>
                    </div>
                </div>

                <!-- Información del cliente y pedido -->
                <div class="invoice-info row mt-3">
                    <div class="col-sm-6">
                        <h4>Cliente</h4>
                        <p>{{ $order->cliente->nombre ?? '-' }}</p>
                        <p>{{ $order->cliente->correo ?? '-' }}</p>
                        <p>{{ $order->cliente->telefono ?? '-' }}</p>
                        <p>{{ $order->cliente->direccion ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 text-end">
                        <h4>Detalles del Pedido</h4>
                        <p>Fecha: {{ \Carbon\Carbon::parse($order->fecha_pedido)->format('d-m-Y') }}</p>
                        <p>Estado de Pago: {{ ucfirst($order->estado_pago ?? '-') }}</p>
                        <p>Pagado: ${{ number_format($order->pago ?? 0, 2) }}</p>
                        <p>Pendiente: ${{ number_format($order->pendiente ?? 0, 2) }}</p>
                    </div>
                </div>

                <!-- Resumen de productos -->
                <div class="order-summary mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Total (+IVA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orderDetails as $item)
                            <tr>
                                <td>{{ $item->producto->nombre ?? '-' }}</td>
                                <td>${{ number_format($item->precio_unitario ?? 0, 2) }}</td>
                                <td>{{ $item->cantidad ?? 0 }}</td>
                                <td>${{ number_format($item->total ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay productos en este pedido</td>
                            </tr>
                            @endforelse
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td><strong>${{ number_format($order->total ?? 0, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Botón imprimir -->
                <div class="text-end mt-3">
                    <button onclick="window.print()" class="btn btn-primary">Imprimir</button>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
