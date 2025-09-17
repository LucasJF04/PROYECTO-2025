<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto; // tu modelo de productos

class CatalogoController extends Controller
{
    // Mostrar todos los productos
    public function index()
    {
        $productos = Producto::paginate(12); // ✅ ahora $productos es paginable

        return view('catalogo.index', compact('productos'));
    }
}
