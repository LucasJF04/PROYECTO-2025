<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar la vista de registro.
     */
    public function crear(): View
    {
        return view('auth.register');
    }

    /**
     * Manejar una solicitud de registro.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function almacenar(Request $request): RedirectResponse
    {
        // Validación de los datos usando reglas
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÀ-ÿ\s]+$/u'],
            'usuario' => ['required', 'string', 'min:4', 'max:100', 'alpha_dash', 'unique:usuarios,usuario'],
            'correo' => ['required', 'string', 'email', 'max:150', 'unique:usuarios,correo'],
            'contrasena' => ['required', 'string', 'min:6', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        // Crear el usuario
        $usuario = Usuario::create([
            'nombre' => $validated['nombre'],
            'usuario' => $validated['usuario'],
            'correo' => $validated['correo'],
            'contrasena' => Hash::make($validated['contrasena']),
            'rol' => 'cliente',
        ]);

        // Disparar evento de registro
        event(new Registered($usuario));

        // Iniciar sesión automáticamente
        Auth::login($usuario);

        // Redirigir a la ruta principal
        return redirect(RouteServiceProvider::HOME)->with('success', 'Cuenta creada correctamente.');
    }
}
