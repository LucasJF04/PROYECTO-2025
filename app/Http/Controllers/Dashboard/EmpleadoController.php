<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Empleado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class EmpleadoController extends Controller
{
    /**
     * Mostrar listado de empleados.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro por página debe ser un número entre 1 y 100.');
        }

        $empleados = Empleado::filter(request(['search']))
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('empleados.index', compact('empleados'));
    }

    /**
     * Mostrar formulario para crear nuevo empleado.
     */
    public function create()
    {
        return view('empleados.create');
    }

    /**
     * Guardar nuevo empleado en la base de datos.
     */
    public function store(Request $request)
    {
        $reglas = [
            'foto' => 'image|file|max:1024',
            'nombre' => 'required|string|max:50',
            'correo' => 'required|email|max:50|unique:empleados,correo',
            'telefono' => 'required|string|max:15|unique:empleados,telefono',
            'experiencia' => 'max:6|nullable',
            'salario' => 'required|numeric',
            'vacaciones' => 'max:50|nullable',
            'ciudad' => 'required|max:50',
            'direccion' => 'required|max:100',
        ];

        $datosValidados = $request->validate($reglas);

        // Manejo de foto
        if ($archivo = $request->file('foto')) {
            $nombreArchivo = hexdec(uniqid()) . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/empleados', $nombreArchivo);
            $datosValidados['foto'] = $nombreArchivo;
        }

        Empleado::create($datosValidados);

        return Redirect::route('empleados.index')
            ->with('success', 'Empleado ha sido creado correctamente!');
    }

    /**
     * Mostrar empleado específico.
     */
    public function show(Empleado $empleado)
    {
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Mostrar formulario para editar empleado.
     */
    public function edit(Empleado $empleado)
    {
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Actualizar empleado en la base de datos.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $reglas = [
            'foto' => 'image|file|max:1024',
            'nombre' => 'required|string|max:50',
            'correo' => 'required|email|max:50|unique:empleados,correo,' . $empleado->id,
            'telefono' => 'required|string|max:15|unique:empleados,telefono,' . $empleado->id,
            'experiencia' => 'max:6|nullable',
            'salario' => 'numeric',
            'vacaciones' => 'max:50|nullable',
            'ciudad' => 'max:50',
            'direccion' => 'required|max:100',
        ];

        $datosValidados = $request->validate($reglas);

        // Manejo de foto
        if ($archivo = $request->file('foto')) {
            $nombreArchivo = hexdec(uniqid()) . '.' . $archivo->getClientOriginalExtension();
            $path = 'public/empleados/';

            // Eliminar foto anterior si existe
            if ($empleado->foto) {
                Storage::delete($path . $empleado->foto);
            }

            $archivo->storeAs($path, $nombreArchivo);
            $datosValidados['foto'] = $nombreArchivo;
        }

        $empleado->update($datosValidados);

        return Redirect::route('empleados.index')
            ->with('success', 'Empleado ha sido actualizado correctamente!');
    }

    /**
     * Eliminar empleado de la base de datos.
     */
    public function destroy(Empleado $empleado)
    {
        // Eliminar foto si existe
        if ($empleado->foto) {
            Storage::delete('public/empleados/' . $empleado->foto);
        }

        $empleado->delete();

        return Redirect::route('empleados.index')
            ->with('success', 'Empleado eliminado correctamente!');
    }
}
