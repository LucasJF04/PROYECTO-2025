<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'usuario_o_correo' => 'required|string',
            'contrasena' => 'required|string',
        ]);
    
        $login_type = filter_var($request->usuario_o_correo, FILTER_VALIDATE_EMAIL) ? 'correo' : 'usuario';
    
        $credentials = [
            $login_type => $request->usuario_o_correo,
            'password' => $request->contrasena,
        ];
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            // Redirigir según rol
            $user = auth()->user();
            if ($user->rol === 'socio') {
                return redirect()->route('dashboard');
            }
    
            return redirect()->route('dashboard'); // otros roles
        }
    
        return back()->withErrors([
            'usuario_o_correo' => 'Usuario o contraseña incorrectos.',
        ])->onlyInput('usuario_o_correo');
    }
    

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
