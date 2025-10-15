<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\InfoPago;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosController extends Controller
{
    public function index(Request $request, $tipo = 'local')
    {
        
        $todayDate = Carbon::now();
        $row = (int) $request->get('row', 10);
    
        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro por página debe estar entre 1 y 100.');
        }
    
        $view = $tipo === 'online' ? 'puntoVentaOnline.index' : 'puntoVentaLocal.index';
    
        // Consulta base
        $query = Producto::with(['categoria', 'proveedor']);
    
        // Filtro de búsqueda por nombre
        if ($search = $request->get('search')) {
            $query->where('nombre_producto', 'like', "%{$search}%");
        }
        $infoPago = InfoPago::first();
        // Filtro por categoría
        if ($categoria = $request->get('categoria')) {
            $query->where('categoria_id', $categoria);
        }
    
        $productos = $query->sortable()
                           ->paginate($row)
                           ->appends($request->query());
                           
        
        return view($view, [
            'usuarios'     => Usuario::where('rol', 'socio')->orderBy('nombre')->get(),
            'productItem'  => Cart::content(),
            'productos'    => $productos,
            'categorias'   => Categoria::all(),
            'infoPago'     => $infoPago,
        ]);
    }
    

    public function clearCart()
    {
        Cart::destroy();
        return redirect()->back()->with('success', 'Carrito vaciado correctamente');
    }

    public function addCart(Request $request)
    {
        $validated = $request->validate([
            'id'    => 'required|numeric',
            'name'  => 'required|string',
            'price' => 'required|numeric',
        ]);

        Cart::add([
            'id'      => $validated['id'],
            'name'    => $validated['name'],
            'qty'     => 1,
            'price'   => $validated['price'],
            'options' => ['size' => 'large']
        ]);

        return redirect()->back()->with('success', 'Producto agregado correctamente!');
    }

    public function updateCart(Request $request, $rowId)
    {
        $validated = $request->validate(['qty' => 'required|numeric']);
        Cart::update($rowId, $validated['qty']);

        return redirect()->back()->with('success', 'Carrito actualizado!');
    }

    public function deleteCart(Request $request, string $rowId)
    {
        Cart::remove($rowId);
        return redirect()->back()->with('success', 'Producto eliminado!');
    }

    public function storePedido(Request $request)
    {
        $validated = $request->validate([
            'id_cliente'       => 'required|exists:usuarios,id',
            'metodo_pago'      => 'required|string|in:efectivo,transferencia',
            'pago'             => 'nullable|numeric|min:0',
            'tipo_entrega'     => 'required|in:sucursal,domicilio',
            'direccion_entrega'=> 'nullable|string|max:255',
        ]);

        // Subtotal y total del carrito
        $subtotal = (float) str_replace(',', '', Cart::subtotal(0, '', ''));
        $total    = $subtotal;

        // Estado y pendiente según rol
        if(auth()->user()->rol === 'administrador'){
            $estado    = 'entregado';
            $pendiente = 0;
        } else {
            $estado    = 'pendiente';
            $pendiente = $total - ($validated['pago'] ?? 0);
        }

        $pedidoData = [
            'id_cliente'       => $validated['id_cliente'],
            'fecha_pedido'     => Carbon::now(),
            'estado_pedido'    => $estado,
            'total_productos'  => Cart::count(),
            'subtotal'         => $subtotal,
            'total'            => $total,
            'tipo_entrega'     => $validated['tipo_entrega'],
            'direccion_entrega'=> $validated['tipo_entrega'] === 'domicilio' ? $request->latitud . ',' . $request->longitud : null,
            'metodo_pago'      => $validated['metodo_pago'],
            'pago'             => $validated['pago'] ?? 0,
            'pendiente'        => $pendiente,
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now(),
        ];

        // Manejo de comprobante
        if ($request->hasFile('comprobante_pago')) {
            $file = $request->file('comprobante_pago');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/comprobantes', $nombreArchivo);
            $pedidoData['comprobante_pago'] = $nombreArchivo;
        }

        // Crear pedido
        $pedido_id = Pedido::insertGetId($pedidoData);

        // Guardar detalles del pedido
        foreach (Cart::content() as $item) {
            DetallePedido::insert([
                'id_pedido'      => $pedido_id,
                'id_producto'    => $item->id,
                'cantidad'       => $item->qty,
                'costo_unitario' => $item->price,
                'total'          => $item->price * $item->qty,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);
        }

        // Vaciar carrito
        Cart::destroy();

        // Redirigir según rol
        if(auth()->user()->rol === 'administrador'){
            return redirect()->route('pos.local')->with('success', 'Venta registrada con éxito');
        } else {
            return redirect()->route('pos.online')->with('success', 'Venta registrada con éxito');
        }
    }

}
