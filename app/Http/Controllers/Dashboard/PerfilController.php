<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Usuario;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class PerfilController extends Controller
{
    /**
     * Mostrar perfil del usuario
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        return view('perfil.index', compact('user'));
    }

    /**
     * Mostrar formulario de edición de perfil
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        return view('perfil.edit', compact('user'));
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'nombre' => 'required|max:50',
            'foto' => 'image|file|max:1024',
            'correo' => 'required|email|max:50|unique:usuarios,correo,'.$user->id,
            'usuario' => 'required|min:4|max:25|alpha_dash|unique:usuarios,usuario,'.$user->id
        ]);

        // Si se sube una foto, guardarla
        if ($file = $request->file('foto')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/profile/';
            $file->storeAs($path, $fileName);
            $validatedData['foto'] = $fileName;
        }

        $user->update($validatedData);

        return Redirect::route('perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Mostrar formulario de cambio de contraseña
     */
    public function changePassword(Request $request): View
    {
        $user = $request->user();
        return view('perfil.change-password', compact('user'));
    }
}
