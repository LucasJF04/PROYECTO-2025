<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Cart; // Gloudemans\Shoppingcart
use Illuminate\Support\Facades\Auth;


class TiendaController extends Controller
{
    // Mostrar productos y carrito
    public function index(Request $request)
    {
        $productos = Producto::where('stock', '>', 0)
            ->when($request->search, fn($q) => $q->where('nombre_producto', 'like', "%{$request->search}%"))
            ->paginate(12);

        return view('cliente.tienda', compact('productos'));
    }

    // Agregar, actualizar cantidad o eliminar
    public function addCart(Request $request)
    {
        // Si viene remove, eliminar del carrito
        if($request->has('remove')){
            Cart::remove($request->remove);
            return back()->with('success', 'Producto eliminado del carrito');
        }

        $validated = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'nullable|numeric|min:1'
        ]);

        $exists = Cart::content()->where('id', $validated['id'])->first();

        if($exists){
            // Actualiza cantidad
            $qty = $validated['qty'] ?? 1;
            Cart::update($exists->rowId, $qty);
        } else {
            // Agrega nuevo producto
            Cart::add([
                'id' => $validated['id'],
                'name' => $validated['name'],
                'qty' => $validated['qty'] ?? 1,
                'price' => $validated['price']
            ]);
        }

        return back()->with('success', 'Carrito actualizado correctamente');
    }

    // Vaciar carrito
    public function clearCart()
    {
        Cart::destroy();
        return back()->with('success', 'Carrito vaciado correctamente');
    }

    // Pagar y crear pedido
    public function pagar()
    {
        $cart = Cart::content();
        if($cart->isEmpty()){
            return back()->with('error', 'El carrito está vacío');
        }

        $subtotal = Cart::subtotal(2, '.', '');
        $total = Cart::total(2, '.', '');

        $pedido = Pedido::create([
            'id_cliente' => Auth::id(),
            'fecha_pedido' => now(),
            'subtotal' => $subtotal,
            'total' => $total,
            'estado_pago' => 'pendiente',
        ]);

        foreach ($cart as $item) {
            DetallePedido::create([
                'id_pedido' => $pedido->id_pedido,
                'id_producto' => $item->id,
                'cantidad' => $item->qty,
                'costo_unitario' => $item->price,
                'total' => $item->subtotal
            ]);

            $producto = Producto::find($item->id);
            if($producto){
                $producto->stock -= $item->qty;
                $producto->save();
            }
        }

        Cart::destroy();

        return redirect()->route('cliente.tienda')->with('success', 'Compra realizada correctamente');
    }

    // Historial de compras
    public function historial()
    {
        $pedidos = Pedido::where('id_cliente', Auth::id())->with('detalles')->orderBy('fecha_pedido', 'desc')->get();
        return view('cliente.historial', compact('pedidos'));
    }
}
