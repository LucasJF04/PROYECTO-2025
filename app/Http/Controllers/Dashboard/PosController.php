<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosController extends Controller
{
    public function index()
    {
        $todayDate = Carbon::now();
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('punto-venta.index', [
            'clientes' => Cliente::all()->sortBy('nombre'),
            'productItem' => Cart::content(),
            'productos' => Producto::where('fecha_expiracion', '>', $todayDate)->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    public function clearCart()
    {
        Cart::destroy(); // Vacía todo el carrito
        return redirect()->back()->with('success', 'Carrito vaciado correctamente');
    }


    public function addCart(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::add([
            'id' => $validatedData['id'],
            'name' => $validatedData['name'],
            'qty' => 1,
            'price' => $validatedData['price'],
            'options' => ['size' => 'large']
        ]);

        return Redirect::back()->with('success', 'Producto agregado correctamente!');
    }

    public function updateCart(Request $request, $rowId)
    {
        $rules = [
            'qty' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::update($rowId, $validatedData['qty']);

        return Redirect::back()->with('success', 'El carrito ha sido actualizado!');
    }

    public function deleteCart(String $rowId)
    {
        Cart::remove($rowId);

        return Redirect::back()->with('success', 'El carrito ha sido eliminado!');
    }

    public function createInvoice(Request $request)
    {
        $rules = [
            'customer_id' => 'required'
        ];

        $validatedData = $request->validate($rules);
        $customer = Cliente::where('id', $validatedData['customer_id'])->first();
        $content = Cart::content();

        return view('punto-venta.create-invoice', [
            'customer' => $customer,
            'content' => $content
        ]);
    }

    public function printInvoice(Request $request)
    {
        $rules = [
            'customer_id' => 'required'
        ];

        $validatedData = $request->validate($rules);
        $customer = Cliente::where('id', $validatedData['customer_id'])->first();
        $content = Cart::content();

        return view('punto-venta.print-invoice', [
            'customer' => $customer,
            'content' => $content
        ]);
    }
}
