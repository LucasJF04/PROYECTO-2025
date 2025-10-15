<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\CuentaCreada;


class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->get('tipo', 'socio'); // Valor por defecto: socio
        $search = $request->get('search'); // Capturamos el texto de búsqueda
    
        // Consulta base según tipo (rol)
        $query = Usuario::where('rol', $tipo);
    
        // Si hay texto de búsqueda, aplicamos filtro
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('usuario', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%");
            });
        }
    
        $usuarios = $query->paginate(10)->appends(['search' => $search, 'tipo' => $tipo]);
    
        // Renderizamos según el tipo
        if ($tipo === 'administrador') {
            return view('usuarios.administradores', compact('usuarios'));
        } else {
            return view('usuarios.socios', compact('usuarios'));
        }
    }
    


    // Mostrar solo administradores
    public function administradores()
    {
        $usuarios = Usuario::where('rol', 'administrador')->paginate(10);
        return view('usuarios.administradores', compact('usuarios'));
    }

    // Mostrar solo socios/clientes
    public function socios()
    {
        $usuarios = Usuario::where('rol', 'socio')->paginate(10);
        return view('usuarios.socios', compact('usuarios'));
    }

    // Crear usuario
    public function create()
    {
        return view('usuarios.create', [
            'roles' => ['administrador', 'socio'],
        ]);
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:50',
        'usuario' => 'required|string|min:4|max:25|alpha_dash|unique:usuarios,usuario',
        'correo' => 'required|string|email|max:150|unique:usuarios,correo',
        'rol' => 'required|in:socio,administrador',
        'foto' => 'nullable|image|file|max:1024',
    ]);

    $password = Str::random(10);
    $validatedData['contrasena'] = Hash::make($password);

    $usuario = Usuario::create($validatedData);

    Mail::to($usuario->correo)->send(new CuentaCreada($usuario, $password));

    // Redirección dinámica según el rol
    if ($usuario->rol === 'administrador') {
        return redirect()->route('usuarios.administradores')
                         ->with('success', 'Administrador creado correctamente. Se envió la contraseña al correo.');
    } else {
        return redirect()->route('usuarios.socios')
                         ->with('success', 'Socio creado correctamente. Se envió la contraseña al correo.');
    }
}


    // Editar usuario
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', [
            'userData' => $usuario,
            'roles' => ['administrador', 'socio'],
        ]);
    }

    // Actualizar usuario
    public function update(Request $request, Usuario $usuario)
    {
        $rules = [
            'nombre' => 'required|max:50',
            'foto' => 'image|file|max:1024',
            'correo' => 'required|email|max:50|unique:usuarios,correo,' . $usuario->id,
            'usuario' => 'required|min:4|max:25|alpha_dash|unique:usuarios,usuario,' . $usuario->id,
            'rol' => 'required|in:administrador,socio',
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

        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = 'public/profile/';
            if ($usuario->foto) {
                Storage::delete($path . $usuario->foto);
            }
            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        $usuario->update($validatedData);

        if ($usuario->rol === 'administrador') {
            return Redirect::route('usuarios.administradores')
                           ->with('success', 'Administrador actualizado correctamente!');
        } else {
            return Redirect::route('usuarios.socios')
                           ->with('success', 'Socio actualizado correctamente!');
        }
    }

    // Eliminar usuario
    public function destroy(Usuario $usuario)
    {
        if ($usuario->foto) {
            Storage::delete('public/profile/' . $usuario->foto);
        }

        $usuario->delete();

        if ($usuario->rol === 'administrador') {
            return Redirect::route('usuarios.administradores')
                           ->with('success', 'Administrador eliminado correctamente!');
        } else {
            return Redirect::route('usuarios.socios')
                           ->with('success', 'Socio eliminado correctamente!');
        }
        
    }
}
