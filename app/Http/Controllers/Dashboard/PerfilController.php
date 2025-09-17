<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Usuario;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PerfilController extends Controller
{
    /**
     * Mostrar perfil según rol del usuario.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
    
        $permisos = [
            'dashboard' => true,
            'perfil' => true,
            'ventas' => $user->rol === 'administrador',
            'pedidos' => true,
            'productos' => $user->rol === 'administrador',
            'categorias' => $user->rol === 'administrador',
            'empleados' => $user->rol === 'administrador',
            'clientes' => $user->rol === 'administrador',
            'proveedores' => $user->rol === 'administrador',
            'rolesPermisos' => $user->rol === 'administrador',
            'usuarios' => $user->rol === 'administrador',
            
            
        ];
    
        return view('perfil.index', compact('user', 'permisos'));
    }
    

    /**
     * Mostrar formulario de cambio de contraseña
     */
    public function changePassword(Request $request): View
    {
        return view('perfil.change-password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mostrar formulario de edición de perfil
     */
    public function edit(Request $request): View
    {
        return view('perfil.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Usuario::find(auth()->user()->id);

        $rules = [
            'nombre' => 'required|max:50',
            'foto' => 'image|file|max:1024',
            'correo' => 'required|email|max:50|unique:usuarios,correo,'.$user->id,
            'usuario' => 'required|min:4|max:25|alpha_dash|unique:usuarios,usuario,'.$user->id
        ];

        $validatedData = $request->validate($rules);

        if ($validatedData['correo'] != $user->correo) {
            $validatedData['correo_verificado_en'] = null;
        }

        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/profile/';
            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        Usuario::where('id', $user->id)->update($validatedData);

        return Redirect::route('perfil.edit')->with('success', 'Perfil ha sido actualizado!');
    }

    /**
     * Eliminar usuario (opcional)
     */
    public function destroy(string $id)
    {
        //
    }
}
