<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    // Mostrar tienda con productos y carrito
    public function tienda(Request $request)
    {
        $search = $request->input('search');
        $productos = Producto::when($search, function($query, $search){
            return $query->where('nombre_producto', 'like', "%$search%");
        })->paginate(12);

        $carrito = $request->session()->get('carrito', []);

        return view('clientes.tienda', compact('productos', 'carrito'));
    }

    // Agregar producto al carrito
    public function addCart(Request $request)
{
    $carrito = $request->session()->get('carrito', []);

    $productoId = $request->id;

    // Crear un índice único para que cada agregado sea independiente
    $key = uniqid();

    $carrito[$key] = [
        'id' => $productoId,
        'name' => $request->name,
        'price' => $request->price,
        'qty' => 1, // siempre 1 al agregar
    ];

    $request->session()->put('carrito', $carrito);

    return redirect()->back()->with('success', 'Producto agregado al carrito');
}

public function updateCart(Request $request)
{
    $carrito = $request->session()->get('carrito', []);
    $key = $request->key;
    $cantidad = max(1, $request->qty);

    if(isset($carrito[$key])){
        $carrito[$key]['qty'] = $cantidad;
        $request->session()->put('carrito', $carrito);
    }

    return redirect()->back()->with('success', 'Cantidad actualizada');
}

public function removeCart(Request $request)
{
    $carrito = $request->session()->get('carrito', []);
    $key = $request->key;

    if(isset($carrito[$key])){
        unset($carrito[$key]);
        $request->session()->put('carrito', $carrito);
    }

    return redirect()->back()->with('success', 'Producto eliminado');
}


    // Vaciar carrito
    public function clearCart(Request $request)
    {
        $request->session()->forget('carrito');
        return redirect()->back()->with('success', 'Carrito vaciado');
    }

    // Simular pago
    public function pagar(Request $request)
    {
        $carrito = $request->session()->get('carrito', []);

        if(empty($carrito)){
            return redirect()->back()->with('error', 'Carrito vacío');
        }

        $request->session()->forget('carrito');
        return redirect()->back()->with('success', 'Pago realizado correctamente');
    }
}
