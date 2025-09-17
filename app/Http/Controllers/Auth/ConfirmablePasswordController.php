<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Mostrar la vista para confirmar contraseña.
     */
    public function mostrar(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirmar la contraseña del usuario.
     */
    public function almacenar(Request $request): RedirectResponse
    {
        // Validar la contraseña usando los campos en español
        if (! Auth::guard('web')->validate([
            'correo' => $request->user()->correo,
            'contrasena' => $request->contrasena,
        ])) {
            throw ValidationException::withMessages([
                'contrasena' => __('auth.password'),
            ]);
        }

        // Guardar el momento de la confirmación en la sesión
        $request->session()->put('auth.password_confirmed_at', time());

        // Redirigir a la ruta de inicio
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
