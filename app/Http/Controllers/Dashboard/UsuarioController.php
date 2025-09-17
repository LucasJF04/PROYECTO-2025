<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class UsuarioController extends Controller
{
    /**
     * Listar usuarios
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'El parámetro de filas debe ser un número entre 1 y 100.');
        }

        return view('usuarios.index', [
            'usuarios' => Usuario::filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('usuarios.create', [
            'roles' => ['administrador', 'cliente'], // lista fija de roles
        ]);
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|max:50',
            'foto' => 'image|file|max:1024',
            'correo' => 'required|email|max:50|unique:usuarios,correo',
            'usuario' => 'required|min:4|max:25|alpha_dash:ascii|unique:usuarios,usuario',
            'contrasena' => 'min:6|required_with:contrasena_confirmation',
            'contrasena_confirmation' => 'min:6|same:contrasena',
            'rol' => 'required|in:administrador,cliente',
        ];

        $validatedData = $request->validate($rules);
        $validatedData['contrasena'] = Hash::make($request->contrasena);

        // Subir imagen
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = 'public/profile/';
            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        Usuario::create($validatedData);

        return Redirect::route('usuarios.index')->with('success', 'Nuevo usuario creado correctamente!');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', [
            'userData' => $usuario,
            'roles' => ['administrador', 'cliente'],
        ]);
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, Usuario $usuario)
    {
        $rules = [
            'nombre' => 'required|max:50',
            'foto' => 'image|file|max:1024',
            'correo' => 'required|email|max:50|unique:usuarios,correo,' . $usuario->id,
            'usuario' => 'required|min:4|max:25|alpha_dash:ascii|unique:usuarios,usuario,' . $usuario->id,
            'rol' => 'required|in:administrador,cliente',
        ];

        if ($request->contrasena || $request->contrasena_confirmation) {
            $rules['contrasena'] = 'min:6|required_with:contrasena_confirmation';
            $rules['contrasena_confirmation'] = 'min:6|same:contrasena';
        }

        $validatedData = $request->validate($rules);

        if ($request->contrasena) {
            $validatedData['contrasena'] = Hash::make($request->contrasena);
        } else {
            unset($validatedData['contrasena']);
        }

        // Subir nueva foto
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = 'public/profile/';

            // Borrar foto antigua
            if ($usuario->foto) {
                Storage::delete($path . $usuario->foto);
            }

            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        $usuario->update($validatedData);

        return Redirect::route('usuarios.index')->with('success', 'Usuario actualizado correctamente!');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(Usuario $usuario)
    {
        // Borrar foto si existe
        if ($usuario->foto) {
            Storage::delete('public/profile/' . $usuario->foto);
        }

        $usuario->delete();

        return Redirect::route('usuarios.index')->with('success', 'Usuario eliminado correctamente!');
    }
}
