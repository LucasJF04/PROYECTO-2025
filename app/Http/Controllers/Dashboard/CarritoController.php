<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    // Mostrar carrito
    public function index()
    {
        $carrito = session()->get('carrito', []); // recupera el carrito de sesión
        return view('carrito.index', compact('carrito'));
    }

    // Añadir producto al carrito
    public function agregar($id)
    {
        $producto = Producto::findOrFail($id);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                "nombre" => $producto->nombre,
                "precio" => $producto->precio,
                "cantidad" => 1
            ];
        }

        session()->put('carrito', $carrito);

        return redirect()->back()->with('success', 'Producto añadido al carrito!');
    }

    // Eliminar producto del carrito
    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito!');
    }
}
