<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Si es socio, mostrar dashboard de socios
        if ($user->rol === 'socio') {
            // Recopilamos info relevante para el socio
            $total_gastado = Pedido::where('id_cliente', $user->id)->sum('pago');
            $pedidos_proceso = Pedido::where('id_cliente', $user->id)
                                     ->where('estado_pedido', 'en_proceso')
                                     ->count();
            $pedidos_entregados = Pedido::where('id_cliente', $user->id)
                                        ->where('estado_pedido', 'entregado')
                                        ->count();
            $ultimos_pedidos = Pedido::where('id_cliente', $user->id)
                                     ->orderByDesc('fecha_pedido')
                                     ->take(5)
                                     ->get();
            // Productos favoritos o más comprados
            $productos_favoritos = Producto::withCount(['detallesPedido as cantidad' => function($query) use ($user) {
                                        $query->whereHas('pedido', function($q) use ($user){
                                            $q->where('id_cliente', $user->id);
                                        });
                                    }])
                                    ->orderByDesc('cantidad')
                                    ->take(5)
                                    ->get();

            return view('panel.index2', compact(
                'total_gastado',
                'pedidos_proceso',
                'pedidos_entregados',
                'ultimos_pedidos',
                'productos_favoritos'
            ));
        }

        // Si es admin u otro rol, mostrar dashboard normal
        return view('panel.index', [
            'total_usuarios' => Usuario::count(),
            'total_socios' => Usuario::where('rol', 'socio')->count(),
            'total_productos' => Producto::count(),
            'total_ventas' => Pedido::count(),
            'productos_mas_vendidos' => Producto::withSum('detallesPedido', 'cantidad')
                                                ->orderByDesc('detalles_pedido_sum_cantidad')
                                                ->take(5)
                                                ->get(),
            'productos_stock_minimo' => Producto::orderBy('stock', 'asc')->take(5)->get(),
        ]);
    }
}

