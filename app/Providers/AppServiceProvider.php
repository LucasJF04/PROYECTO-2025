<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- importante
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // View composer para pasar $permisos a todas las vistas
        View::composer('*', function ($view) {
            $user = Auth::user();
            if ($user) {
                $permisos = [
                    'dashboard' => $user->rol === 'administrador',
                    'dashboard2' => $user->rol === 'socio',
                    'perfil' => true,
                    'ventas' => $user->rol === 'administrador', // Solo admin puede "Vender Local"
                    'mis_compras' => $user->rol === 'socio', 
                    'pedidos' => $user->rol === 'administrador',
                    'productos' => $user->rol === 'administrador',
                    'categorias' => $user->rol === 'administrador',
                    'empleados' => $user->rol === 'administrador',
                    'clientes' => $user->rol === 'administrador',
                    'proveedores' => $user->rol === 'administrador',
                    'rolesPermisos' => $user->rol === 'administrador',
                    'usuarios' => $user->rol === 'administrador',
                    'reportes' => true,
                ];
                $view->with('permisos', $permisos);
            }
        });
    }
}
