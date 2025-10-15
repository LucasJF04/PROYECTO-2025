<div id="mini-carrito">
<table class="table">
    <thead>
        <tr class="ligth">
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>SubTotal</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productItem as $item)
        <tr data-rowid="{{ $item->rowId }}">
            <td>{{ $item->name }}</td>
            <td>
                <input type="number" class="qty-input form-control" value="{{ $item->qty }}" min="1">
            </td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->subtotal }}</td>
            <td>
                <button class="btn btn-danger btn-sm btn-delete"><i class="fa-solid fa-trash"></i></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-between">
    <p>Cantidad: {{ Cart::count() }}</p>
    <p>Subtotal: {{ Cart::subtotal() }}</p>
    <p>IVA: {{ Cart::tax() }}</p>
    <p>Total: {{ Cart::total() }}</p>
</div>
</div>
