@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <!-- Usuarios -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary rounded-lg shadow-sm">
                <div class="card-body text-center py-3">
                    <i class="ri-user-line" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Usuarios</h6>
                    <h4>{{ $total_usuarios }}</h4>
                </div>
            </div>
        </div>

        <!-- Socios -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success rounded-lg shadow-sm">
                <div class="card-body text-center py-3">
                    <i class="ri-hand-coin-line" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Socios</h6>
                    <h4>{{ $total_socios }}</h4>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning rounded-lg shadow-sm">
                <div class="card-body text-center py-3">
                    <i class="ri-shopping-bag-line" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Productos</h6>
                    <h4>{{ $total_productos }}</h4>
                </div>
            </div>
        </div>

        <!-- Ventas -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-danger rounded-lg shadow-sm">
                <div class="card-body text-center py-3">
                    <i class="ri-bar-chart-line" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Ventas</h6>
                    <h4>{{ $total_ventas }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Productos más vendidos -->
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header bg-primary text-white">
                    <i class="ri-star-line"></i> Productos Más Vendidos
                </div>
                <div class="card-body">
                    <canvas id="chartMasVendidos"></canvas>
                </div>
            </div>
        </div>

        <!-- Productos con stock mínimo -->
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header bg-warning text-dark">
                    <i class="ri-alert-line"></i> Productos con Stock Mínimo
                </div>
                <div class="card-body">
                    <canvas id="chartStockMinimo"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    
    const labelsVendidos = [
        @foreach($productos_mas_vendidos as $producto)
            "{{ $producto->nombre_producto }}",
        @endforeach
    ];
    const dataVendidos = {
        labels: labelsVendidos,
        datasets: [{
            label: 'Cantidad Vendida',
            data: [
                @foreach($productos_mas_vendidos as $producto)
                    {{ $producto->detalles_pedido_sum_cantidad ?? 0 }},
                @endforeach
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    const configVendidos = {
        type: 'bar',
        data: dataVendidos,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        },
    };
    new Chart(document.getElementById('chartMasVendidos'), configVendidos);

    
    const labelsStock = [
        @foreach($productos_stock_minimo as $producto)
            "{{ $producto->nombre_producto }}",
        @endforeach
    ];

    const dataStockValues = [
        @foreach($productos_stock_minimo as $producto)
            {{ $producto->stock }},
        @endforeach
    ];

    const backgroundColors = dataStockValues.map(stock => {
        if(stock <= 5) return 'rgba(255, 99, 132, 0.7)'; 
        if(stock <= 10) return 'rgba(255, 206, 86, 0.7)'; 
        return 'rgba(75, 192, 192, 0.7)'; 
    });

    const dataStock = {
        labels: labelsStock,
        datasets: [{
            label: 'Stock',
            data: dataStockValues,
            backgroundColor: backgroundColors,
            borderColor: 'rgba(0,0,0,0.1)',
            borderWidth: 1
        }]
    };

    const configStock = {
        type: 'bar',
        data: dataStock,
        options: {
            indexAxis: 'y', 
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        },
    };
    new Chart(document.getElementById('chartStockMinimo'), configStock);
</script>
@endsection
