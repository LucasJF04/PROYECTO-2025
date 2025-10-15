<?php

namespace App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Producto;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class PedidoController extends Controller
{
    public function pendientes()
{
    $row = (int) request('row', 10);
    $search = request('search');
    $tipoEntrega = request('tipo_entrega');
    $fechaInicio = request('fecha_inicio');
    $fechaFin = request('fecha_fin');

    $query = Pedido::where('estado_pedido', Pedido::STATUS_PENDING);

    // 🔍 Filtro por búsqueda
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('id_pedido', $search)
              ->orWhereHas('cliente', function($q2) use ($search) {
                  $q2->where('nombre', 'like', "%{$search}%");
              });
        });
    }

    // 📦 Filtro por tipo de entrega
    if ($tipoEntrega) {
        $query->where('tipo_entrega', $tipoEntrega);
    }

    // 🗓️ Filtro por fechas
    if ($fechaInicio && $fechaFin) {
        $query->whereBetween('fecha_pedido', [$fechaInicio, $fechaFin]);
    } elseif ($fechaInicio) {
        $query->whereDate('fecha_pedido', '>=', $fechaInicio);
    } elseif ($fechaFin) {
        $query->whereDate('fecha_pedido', '<=', $fechaFin);
    }

    $pedidos = $query->sortable()->paginate($row);
    $pedidos->appends(request()->all());

    return view('pedidos.pendientes', compact('pedidos'));
}


public function completados()
{
    $row = (int) request('row', 10);
    $search = request('search');           // parámetro de búsqueda
    $estadoFiltro = request('estado');     // filtro por estado
    $fechaInicio = request('fecha_inicio'); // filtro por fecha inicio
    $fechaFin = request('fecha_fin');       // filtro por fecha fin

    $query = Pedido::query();

    // Solo mostrar pedidos completados (estados permitidos)
    $estadosPermitidos = [
        Pedido::STATUS_VERIFIED,
        Pedido::STATUS_EN_PROCESO,
        Pedido::STATUS_LISTO,
        Pedido::STATUS_EN_DESPACHO,
        Pedido::STATUS_ENTREGADO,
    ];
    $query->whereIn('estado_pedido', $estadosPermitidos);

    // Aplicar filtro por estado si se selecciona
    if ($estadoFiltro && in_array($estadoFiltro, $estadosPermitidos)) {
        $query->where('estado_pedido', $estadoFiltro);
    }

    // Filtro por rango de fechas
    if ($fechaInicio) {
        $query->whereDate('fecha_pedido', '>=', $fechaInicio);
    }
    if ($fechaFin) {
        $query->whereDate('fecha_pedido', '<=', $fechaFin);
    }

    // Búsqueda por número de pedido o nombre de cliente
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('id_pedido', $search)
              ->orWhereHas('cliente', function($q2) use ($search) {
                  $q2->where('nombre', 'like', "%{$search}%");
              });
        });
    }

    $pedidos = $query->sortable()->paginate($row);

    // Mantener filtros en la paginación
    $pedidos->appends(request()->all());

    // Pasar lista de estados para el select de la vista
    $estados = ['verificado','en_proceso','listo','en_despacho','entregado'];

    return view('pedidos.completados', compact('pedidos', 'estados'));
}


    public function generarNota($id)
{
    $pedido = Pedido::with('cliente', 'detalles')->findOrFail($id);

    $datos = [
        'id' => $pedido->id_pedido,
        'cliente' => $pedido->cliente->nombre ?? '-',
        'fecha' => $pedido->fecha_pedido,
        'total' => $pedido->total,
        'metodo_pago' => $pedido->metodo_pago,
        'tipo_entrega' => $pedido->tipo_entrega,
        'direccion' => $pedido->direccion_entrega,
        'estado' => $pedido->estado_pedido,
        'productos' => $pedido->detalles ?? [],
    ];

    $pdf = Pdf::loadView('pedidos.nota', compact('datos'));
    return $pdf->download('NotaVenta_'.$pedido->id_pedido.'.pdf');
}
    


public function detalles(Int $id_pedido)
{
    $pedido = Pedido::findOrFail($id_pedido);
    $detallesPedido = DetallePedido::with('producto')
        ->where('id_pedido', $id_pedido)
        ->orderBy('id_detalle', 'DESC')
        ->get();

    if(request()->ajax()) {
        return response()->json([
            'pedido' => [
                'id' => $pedido->id_pedido,
                'cliente' => $pedido->cliente->nombre ?? '-',
                'fecha' => $pedido->fecha_pedido,
                'pago' => $pedido->pago,
                'metodo_pago' => $pedido->metodo_pago ?? '-', 
                'total' => $pedido->total
            ],
            'detalles' => $detallesPedido->map(function($detalle) {
                return [
                    'producto' => $detalle->producto->nombre_producto ?? '-',
                    'cantidad' => $detalle->cantidad,
                    'costo_unitario' => $detalle->costo_unitario,
                    'total' => $detalle->total,
                ];
            }),
            'comprobante' => $pedido->comprobante_pago ? asset('storage/comprobantes/'.$pedido->comprobante_pago) : null,
        ]);
    }

    return view('pedidos.detalles', compact('pedido', 'detallesPedido'));
}




    public function actualizarEstado(Request $request)
{
    $validated = $request->validate([
        'id_pedido' => 'required|numeric|exists:pedidos,id_pedido',
        'estado' => 'required|string'
    ]);

    $pedido = Pedido::with('detalles.producto')->findOrFail($validated['id_pedido']);
    $newEstado = $validated['estado'];

    // Si se pasa a 'verificado' reducimos stock y marcamos pago
    if ($newEstado === Pedido::STATUS_VERIFIED) {
        foreach ($pedido->detalles as $detalle) {
            if ($detalle->producto) {
                $detalle->producto->decrement('stock', $detalle->cantidad);
            }
        }

        $pedido->update([
            'estado_pedido' => Pedido::STATUS_VERIFIED,
        ]);

        return Redirect::route('pedidos.completados')->with('success', 'Pago validado y pedido verificado.');
    }

    // Otros cambios de estado (en_proceso, listo, en_despacho, entregado)
    if (in_array($newEstado, Pedido::allowedStates())) {
        $pedido->update(['estado_pedido' => $newEstado]);
        return Redirect::back()->with('success', 'Estado actualizado correctamente.');
    }

    return Redirect::back()->with('error', 'Estado no permitido.');
}

    



    public function pendientesPago()
    {
        $row = (int) request('row', 10);
        $pedidos = Pedido::where('pendiente', '>', 0)
            ->sortable()
            ->paginate($row);

        return view('pedidos.pendientesPago', compact('pedidos'));
    }

    public function actualizarPendiente(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|numeric',
            'due'      => 'required|numeric',
        ]);

        $pedido = Pedido::findOrFail($validated['order_id']);
        $pedido->update([
            'pendiente' => $pedido->pendiente - $validated['due'],
            'pago' => $pedido->pago + $validated['due'],
        ]);

        return Redirect::route('pedidos.pendientesPago')
            ->with('success', 'Monto pendiente actualizado correctamente!');
    }











    public function misCompras()
    {
        $row = (int) request('row', 10);
    
        // Solo los pedidos del cliente logueado
        $pedidos = Pedido::where('id_cliente', Auth::id())
                          ->sortable()
                          ->paginate($row);
    
        return view('reportes.index', compact('pedidos'));
    }
    


    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,verificado,en_proceso,listo,en_despacho,entregado',
        ]);

        $pedido->estado_pedido = $request->estado;
        $pedido->save();

        return redirect()->back()->with('success', 'Estado del pedido actualizado correctamente');
    }


    public function datosPago()
    {
        $informacion = \DB::table('informacion_pago')->first(); // si solo habrá un registro
        return view('pedidos.datos-pago', compact('informacion'));
    }

    public function guardarDatosPago(Request $request)
    {
        $request->validate([
            'nombre_titular' => 'required|string|max:255',
            'banco' => 'required|string|max:255',
            'numero_cuenta' => 'required|string|max:100',
            'tipo_cuenta' => 'nullable|string|max:100',
            'qr_imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nombre_titular', 'banco', 'numero_cuenta', 'tipo_cuenta']);

        if ($request->hasFile('qr_imagen')) {
            $path = $request->file('qr_imagen')->store('qr_pagos', 'public');
            $data['qr_imagen'] = $path;
        }

        $existe = \DB::table('informacion_pago')->first();

        if ($existe) {
            \DB::table('informacion_pago')->update($data);
        } else {
            \DB::table('informacion_pago')->insert($data);
        }

        return back()->with('success', 'Datos de pago actualizados correctamente.');
    }

    
    // Actualizar pedido pendiente
    public function actualizarPedido(Request $request)
    {
        $request->validate([
            'id_pedido' => 'required|exists:pedidos,id_pedido',
            'cantidades' => 'required|array',
        ]);
    
        $pedido = Pedido::with('detalles')->findOrFail($request->id_pedido);
    
        $totalProductos = 0; 
        $subtotal = 0;      
    
        
        foreach ($pedido->detalles as $detalle) {
            $productoNombre = $detalle->producto->nombre_producto ?? null;
            if ($productoNombre && isset($request->cantidades[$productoNombre])) {
                $detalle->cantidad = (int) $request->cantidades[$productoNombre];
                $detalle->total = $detalle->cantidad * $detalle->costo_unitario;
                $detalle->save();
    
                $totalProductos += $detalle->cantidad;   
                $subtotal += $detalle->total;           
            }
        }
    
        // Actualizar el pedido
        $pedido->subtotal = $subtotal;
        $pedido->total = $subtotal;  
        $pedido->total_productos = $totalProductos; 
        $pedido->save();
    
        return redirect()->back()->with('success');
    }
    

    
}

