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
use Illuminate\Validation\Rules;
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
        // Validación de los datos usando los campos en español
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'usuario' => ['required', 'string', 'max:255', 'unique:'.Usuario::class, 'alpha_dash'],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:'.Usuario::class],
            'contrasena' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Crear el usuario en la base de datos
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'usuario' => $request->usuario,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
        ]);

        // Disparar evento de registro
        event(new Registered($usuario));

        // Iniciar sesión automáticamente
        Auth::login($usuario);

        // Redirigir a la ruta principal
        return redirect(RouteServiceProvider::HOME);
    }
}
