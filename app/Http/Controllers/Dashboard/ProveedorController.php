<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProveedorController extends Controller
{
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be un número entre 1 y 100.');
        }

        return view('proveedores.index', [
            'proveedores' => Proveedor::filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'nombre_tienda' => 'required|string|max:50',
            'correo' => 'required|email|max:100|unique:proveedores,correo',
            'telefono' => 'required|string|max:50|unique:proveedores,telefono',
            'foto' => 'nullable|image|file|max:1024',
            'tipo' => 'required|string|max:25',
            'titular_cuenta' => 'nullable|string|max:50',
            'numero_cuenta' => 'nullable|string|max:25',
            'banco' => 'nullable|string|max:25',
            'sucursal' => 'nullable|string|max:50',
            'ciudad' => 'required|string|max:50',
            'direccion' => 'required|string|max:100',
        ];
        
        

        $validatedData = $request->validate($rules);

        // Subida de imagen
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/suppliers/';
            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        Proveedor::create($validatedData);

        return Redirect::route('proveedores.index')->with('success', 'Proveedor creado con éxito!');
    }

    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'nombre_tienda' => 'required|string|max:50',
            'correo' => 'required|email|max:100|unique:proveedores,correo',
            'telefono' => 'required|string|max:50|unique:proveedores,telefono',
            'foto' => 'nullable|image|file|max:1024',
            'tipo' => 'required|string|max:25',
            'titular_cuenta' => 'nullable|string|max:50',
            'numero_cuenta' => 'nullable|string|max:25',
            'banco' => 'nullable|string|max:25',
            'sucursal' => 'nullable|string|max:50',
            'ciudad' => 'required|string|max:50',
            'direccion' => 'required|string|max:100',
        ];
        
        
        $validatedData = $request->validate($rules);

        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/suppliers/';

            if ($proveedor->foto) {
                Storage::delete($path . $proveedor->foto);
            }

            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        $proveedor->update($validatedData);

        return Redirect::route('proveedores.index')->with('success', 'Proveedor actualizado con éxito!');
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->foto) {
            Storage::delete('public/suppliers/' . $proveedor->foto);
        }

        $proveedor->delete();

        return Redirect::route('proveedores.index')->with('success', 'Proveedor eliminado con éxito!');
    }
}
