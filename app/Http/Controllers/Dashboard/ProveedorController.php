<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('proveedores.index', [
            'proveedores' => Proveedor::filter(request(['search']))->sortable()->paginate($row)->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'foto' => 'image|file|max:1024',
            'nombre' => 'required|string|max:50',
            'correo' => 'required|email|max:50|unique:proveedores,correo,'.$proveedor->id,
            'telefono' => 'required|string|max:15|unique:proveedores,telefono,'.$proveedor->id,
            'nombre_tienda' => 'required|string|max:50',
            'tipo' => 'required|string|max:25',
            'titular_cuenta' => 'max:50',
            'numero_cuenta' => 'max:25',
            'banco' => 'max:25',
            'sucursal_banco' => 'max:50',
            'ciudad' => 'required|string|max:50',
            'direccion' => 'required|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        /**
         * Handle upload image with Storage.
         */
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/suppliers/';

            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        Proveedor::create($validatedData);

        return Redirect::route('proveedores.index')->with('success', 'Supplier has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', [
            'proveedor' => $proveedor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', [
            'proveedor' => $proveedor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $rules = [
            'foto' => 'image|file|max:1024',
            'nombre' => 'required|string|max:50',
            'correo' => 'required|email|max:50|unique:proveedores,correo,'.$proveedor->id,
            'telefono' => 'required|string|max:15|unique:proveedores,telefono,'.$proveedor->id,
            'nombre_tienda' => 'required|string|max:50',
            'tipo' => 'required|string|max:25',
            'titular_cuenta' => 'max:50',
            'numero_cuenta' => 'max:25',
            'banco' => 'max:25',
            'sucursal_banco' => 'max:50',
            'ciudad' => 'required|string|max:50',
            'direccion' => 'required|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        /**
         * Handle upload image with Storage.
         */
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/suppliers/';

            /**
             * Delete photo if exists.
             */
            if($proveedor->foto){
                Storage::delete($path . $proveedor->foto);
            }

            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        Proveedor::where('id', $proveedor->id)->update($validatedData);

        return Redirect::route('proveedores.index')->with('success', 'Proveedor ha sido actualizado!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        /**
         * Delete photo if exists.
         */
        if($proveedor->photo){
            Storage::delete('public/suppliers/' . $proveedor->foto);
        }

        Proveedor::destroy($proveedor->id);

        return Redirect::route('proveedor.index')->with('success', 'Supplier has been deleted!');
    }
}
