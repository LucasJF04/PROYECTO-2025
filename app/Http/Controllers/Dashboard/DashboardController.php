<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        return view('panel.index', [
            'total_paid' => Pedido::sum('pago'),
            'total_due' => Pedido::sum('pendiente'),
            'complete_orders' => Pedido::where('estado_pedido', 'complete')->get(),
            'productos' => Producto::orderBy('tienda_producto')->take(5)->get(),
            'new_products' => Producto::orderBy('fecha_compra')->take(2)->get(),
        ]);
    }
}
