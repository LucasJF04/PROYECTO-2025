@extends('panel.body.main')

@section('container')
<div class="container mt-4">
    <h2>Mis pedidos</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($carrito) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carrito as $id => $item)
                <tr>
                    <td>{{ $item['nombre'] }}</td>
                    <td>${{ number_format($item['precio'], 2) }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                    <td>
                        <a href="{{ route('carrito.eliminar', $id) }}" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
     
    @endif
</div>
@endsection
