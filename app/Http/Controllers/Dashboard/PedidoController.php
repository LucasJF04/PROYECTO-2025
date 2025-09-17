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

        return view('pedidos.pendientes', [
            'pedidos' => $pedidos
        ]);
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

        return view('pedidos.completados', [
            'pedidos' => $pedidos
        ]);
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

        return view('stock.index', [
            'productos' => Producto::with(['categoria', 'proveedor'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Guardar un nuevo pedido.
     */
    public function guardar(Request $request)
    {
        $rules = [
            'id_cliente' => 'required|numeric',
            'estado_pago' => 'required|string',
            'pago' => 'numeric|nullable',
            'deuda' => 'numeric|nullable',
        ];

        $numero_factura = IdGenerator::generate([
            'table' => 'pedidos',
            'field' => 'numero_factura',
            'length' => 10,
            'prefix' => 'FAC-'
        ]);

        $validatedData = $request->validate($rules);
        $validatedData['fecha_pedido'] = Carbon::now()->format('Y-m-d');
        $validatedData['estado_pedido'] = 'pendiente';
        $validatedData['total_productos'] = Cart::count();
        $validatedData['subtotal'] = Cart::subtotal();
        $validatedData['iva'] = Cart::tax();
        $validatedData['numero_factura'] = $numero_factura;
        $validatedData['total'] = Cart::total();
        $validatedData['deuda'] = Cart::total() - $validatedData['pago'];
        $validatedData['created_at'] = Carbon::now();

        $pedido_id = Pedido::insertGetId($validatedData);

        // Guardar detalles del pedido
        $contenido = Cart::content();
        $detalles = array();

        foreach ($contenido as $item) {
            $detalles['id_pedido'] = $pedido_id;
            $detalles['id_producto'] = $item->id;
            $detalles['cantidad'] = $item->qty;
            $detalles['costo_unitario'] = $item->price;
            $detalles['total'] = $item->total;
            $detalles['created_at'] = Carbon::now();

            DetallePedido::insert($detalles);
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
        $pedido = Pedido::where('id_pedido', $id_pedido)->first();
        $detallesPedido = DetallePedido::with('producto')
            ->where('id_pedido', $id_pedido)
            ->orderBy('id_detalle', 'DESC')
            ->get();

        return view('pedidos.detalles', [
            'pedido' => $pedido,
            'detallesPedido' => $detallesPedido,
        ]);
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
                ->update(['stock' => DB::raw('stock-'.$producto->cantidad)]);
        }

        Pedido::findOrFail($pedido_id)->update(['estado_pedido' => 'completado']);

        return Redirect::route('pedidos.pendientes')->with('success', '¡Pedido completado!');
    }

    /**
     * Descargar factura.
     */
    public function descargarFactura(Int $id_pedido)
    {
        $pedido = Pedido::where('id_pedido', $id_pedido)->first();
        $detallesPedido = DetallePedido::with('producto')
            ->where('id_pedido', $id_pedido)
            ->orderBy('id_detalle', 'DESC')
            ->get();

        return view('pedidos.factura', [
            'pedido' => $pedido,
            'detallesPedido' => $detallesPedido,
        ]);
    }

    /**
     * Pedidos con deuda pendiente.
     */
    public function pendientesPago() { $row = (int) request('row', 10); if ($row < 1 || $row > 100) abort(400, 'El parámetro por página debe ser entre 1 y 100.'); $pedidos = Pedido::where('pendiente', '>', 0)->sortable()->paginate($row); return view('pedidos.pendientesPago', compact('pedidos')); } /** * Ajax monto pendiente */ public function ajaxPendiente(int $id) { $pedido = Pedido::findOrFail($id); return response()->json($pedido); }

    public function obtenerPedidoAjax(Int $id)
    {
        $pedido = Pedido::findOrFail($id);

        return response()->json($pedido);
    }

    public function actualizarPendiente(Request $request) { $validated = $request->validate([ 'order_id' => 'required|numeric', 'due' => 'required|numeric', ]); $pedido = Pedido::findOrFail($validated['order_id']); $pedido->update([ 'pendiente' => $pedido->pendiente - $validated['due'], 'pago' => $pedido->pago + $validated['due'], ]); return Redirect::route('pedidos.pendientesPago')->with('success', '¡Monto pendiente actualizado correctamente!'); }
}
