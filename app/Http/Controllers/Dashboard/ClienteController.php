<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ClienteController extends Controller
{
    /**
     * Mostrar listado de clientes.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro de registros por página debe estar entre 1 y 100.');
        }

        return view('clientes.index', [
            'clientes' => Cliente::filter(request(['search']))->sortable()->paginate($row)->appends(request()->query()),
        ]);
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guardar un nuevo cliente.
     */
    public function store(Request $request)
    {
        $rules = [
            'foto' => 'image|file|max:1024',
            'nombre' => 'required|string|max:50',
            'correo' => 'required|email|max:50|unique:clientes,correo',
            'telefono' => 'required|string|max:15|unique:clientes,telefono',
            'nombre_tienda' => 'required|string|max:50',
            'titular_cuenta' => 'max:50',
            'numero_cuenta' => 'max:25',
            'nombre_banco' => 'max:25',
            'sucursal_banco' => 'max:50',
            'ciudad' => 'required|string|max:50',
            'direccion' => 'required|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        /**
         * Subir imagen con Storage.
         */
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/clientes/';

            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        Cliente::create($validatedData);

        return Redirect::route('clientes.index')->with('success', '¡Cliente creado con éxito!');
    }

    /**
     * Mostrar un cliente.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', [
            'cliente' => $cliente
        ]);
    }

    /**
     * Actualizar cliente.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $rules = [
            'foto' => 'image|file|max:1024',
            'nombre' => 'required|string|max:50',
            'correo' => 'required|email|max:50|unique:clientes,correo,'.$cliente->id,
            'telefono' => 'required|string|max:15|unique:clientes,telefono,'.$cliente->id,
            'nombre_tienda' => 'required|string|max:50',
            'titular_cuenta' => 'max:50',
            'numero_cuenta' => 'max:25',
            'nombre_banco' => 'max:25',
            'sucursal_banco' => 'max:50',
            'ciudad' => 'required|string|max:50',
            'direccion' => 'required|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        /**
         * Subir imagen con Storage.
         */
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/clientes/';

            /**
             * Eliminar foto anterior si existe.
             */
            if($cliente->foto){
                Storage::delete($path . $cliente->foto);
            }

            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        Cliente::where('id', $cliente->id)->update($validatedData);

        return Redirect::route('clientes.index')->with('success', '¡Cliente actualizado con éxito!');
    }

    /**
     * Eliminar cliente.
     */
    public function destroy(Cliente $cliente)
    {
        /**
         * Eliminar foto si existe.
         */
        if($cliente->foto){
            Storage::delete('public/clientes/' . $cliente->foto);
        }

        Cliente::destroy($cliente->id);

        return Redirect::route('clientes.index')->with('success', '¡Cliente eliminado con éxito!');
    }
}
