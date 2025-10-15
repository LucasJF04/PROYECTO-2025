<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota de Venta #{{ $datos['id'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        h2, h4 { margin: 0; text-align: center; }
        .info { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Nota de Venta</h2>
    

    <div class="info">
        <p><strong>Pedido: #</strong> {{ $datos['id'] }}</p>
        <p><strong>Cliente:</strong> {{ $datos['cliente'] }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($datos['fecha'])->format('d-m-Y') }}</p>
        <p><strong>Tipo de entrega:</strong> {{ ucfirst($datos['tipo_entrega']) }}</p>
        <p><strong>Dirección:</strong> {{ $datos['direccion'] ?? '-' }}</p>
        <p><strong>Método de pago:</strong> {{ $datos['metodo_pago'] }}</p>
        <p><strong>Estado:</strong> {{ ucfirst(str_replace('_', ' ', $datos['estado'])) }}</p>
    </div>

    @if(count($datos['productos']) > 0)
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['productos'] as $prod)
            <tr>
                <td>{{ $prod->producto->nombre_producto ?? '-' }}</td>
                <td>{{ $prod->cantidad }}</td>
                <td>${{ number_format($prod->costo_unitario, 2) }}</td>
                <td>${{ number_format($prod->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h4 style="text-align: right; margin-top: 20px;">Total: ${{ number_format($datos['total'], 2) }}</h4>
</body>
</html>
