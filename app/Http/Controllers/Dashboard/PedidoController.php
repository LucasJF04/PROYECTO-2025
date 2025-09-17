<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class PedidoController extends Controller
{
    /**
     * Mostrar pedidos pendientes.
     */
    public function pendientes()
    {
        $row = (int) request('row', 10);
        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro debe ser un número entre 1 y 100.');
        }

        $pedidos = Pedido::where('estado_pedido', 'pendiente')
            ->sortable()
            ->paginate($row);

        return view('pedidos.pendientes', compact('pedidos'));
    }

    /**
     * Mostrar pedidos completados.
     */
    public function completados()
    {
        $row = (int) request('row', 10);
        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro debe ser un número entre 1 y 100.');
        }

        $pedidos = Pedido::where('estado_pedido', 'completado')
            ->sortable()
            ->paginate($row);

        return view('pedidos.completados', compact('pedidos'));
    }

    /**
     * Gestión de stock.
     */
    public function stock()
    {
        $row = (int) request('row', 10);
        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro debe ser un número entre 1 y 100.');
        }

        $productos = Producto::with(['categoria', 'proveedor'])
            ->filter(request(['search']))
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('stock.index', compact('productos'));
    }

    /**
     * Guardar un nuevo pedido.
     */
    public function guardar(Request $request)
    {
        $validatedData = $request->validate([
            'id_cliente'  => 'required|numeric',
            'estado_pago' => 'required|string',
            'pago'        => 'numeric|nullable',
            'deuda'       => 'numeric|nullable',
        ]);

        $numero_factura = IdGenerator::generate([
            'table'  => 'pedidos',
            'field'  => 'numero_factura',
            'length' => 10,
            'prefix' => 'FAC-'
        ]);

        $validatedData['fecha_pedido']   = Carbon::now()->format('Y-m-d');
        $validatedData['estado_pedido']  = 'pendiente';
        $validatedData['total_productos'] = Cart::count();
        $validatedData['subtotal']       = (float) Cart::subtotal(0, '', '');
        $validatedData['iva']            = (float) Cart::tax(0, '', '');
        $validatedData['total']          = (float) Cart::total(0, '', '');
        $validatedData['numero_factura'] = $numero_factura;
        $validatedData['deuda']          = $validatedData['total'] - ($validatedData['pago'] ?? 0);
        $validatedData['created_at']     = Carbon::now();

        $pedido_id = Pedido::insertGetId($validatedData);

        // Guardar detalles del pedido
        foreach (Cart::content() as $item) {
            DetallePedido::insert([
                'id_pedido'     => $pedido_id,
                'id_producto'   => $item->id,
                'cantidad'      => $item->qty,
                'costo_unitario'=> $item->price,
                'total'         => $item->price * $item->qty,
                'created_at'    => Carbon::now(),
            ]);
        }

        // Vaciar carrito
        Cart::destroy();

        return Redirect::route('dashboard')->with('success', '¡Pedido creado exitosamente!');
    }

    /**
     * Mostrar detalles de un pedido.
     */
    public function detalles(Int $id_pedido)
    {
        $pedido = Pedido::where('id_pedido', $id_pedido)->firstOrFail();
        $detallesPedido = DetallePedido::with('producto')
            ->where('id_pedido', $id_pedido)
            ->orderBy('id_detalle', 'DESC')
            ->get();

        return view('pedidos.detalles', compact('pedido', 'detallesPedido'));
    }

    /**
     * Actualizar estado de pedido (pendiente → completado).
     */
    public function actualizarEstado(Request $request)
    {
        $pedido_id = $request->id_pedido;

        // Reducir stock
        $productos = DetallePedido::where('id_pedido', $pedido_id)->get();
        foreach ($productos as $producto) {
            Producto::where('id_producto', $producto->id_producto)
                ->update(['stock' => DB::raw('stock - ' . $producto->cantidad)]);
        }

        Pedido::findOrFail($pedido_id)->update(['estado_pedido' => 'completado']);

        return Redirect::route('pedidos.pendientes')->with('success', '¡Pedido completado!');
    }

    /**
     * Descargar factura.
     */
    public function descargarFactura(Int $id_pedido)
    {
        $pedido = Pedido::where('id_pedido', $id_pedido)->firstOrFail();
        $detallesPedido = DetallePedido::with('producto')
            ->where('id_pedido', $id_pedido)
            ->orderBy('id_detalle', 'DESC')
            ->get();

        return view('pedidos.factura', compact('pedido', 'detallesPedido'));
    }

    /**
     * Pedidos con deuda pendiente.
     */
    public function pendientesPago()
    {
        $row = (int) request('row', 10);
        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro por página debe ser entre 1 y 100.');
        }

        $pedidos = Pedido::where('deuda', '>', 0)
            ->sortable()
            ->paginate($row);

        return view('pedidos.pendientesPago', compact('pedidos'));
    }

    /**
     * Obtener pedido por AJAX.
     */
    public function obtenerPedidoAjax(Int $id)
    {
        $pedido = Pedido::findOrFail($id);
        return response()->json($pedido);
    }

    /**
     * Actualizar monto pendiente.
     */
    public function actualizarPendiente(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|numeric',
            'due'      => 'required|numeric',
        ]);

        $pedido = Pedido::findOrFail($validated['order_id']);

        $pedido->update([
            'deuda' => $pedido->deuda - $validated['due'],
            'pago'  => $pedido->pago + $validated['due'],
        ]);

        return Redirect::route('pedidos.pendientesPago')
            ->with('success', '¡Monto pendiente actualizado correctamente!');
    }
}
