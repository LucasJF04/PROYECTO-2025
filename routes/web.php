<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductoController;
use App\Http\Controllers\Dashboard\PerfilController;
use App\Http\Controllers\Dashboard\CategoriaController;
use App\Http\Controllers\Dashboard\ClienteController;
use App\Http\Controllers\Dashboard\EmpleadoController;
use App\Http\Controllers\Dashboard\ProveedorController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PedidoController;
use App\Http\Controllers\Dashboard\PosController;
use App\Http\Controllers\Dashboard\UsuarioController;
use App\Http\Controllers\Dashboard\CatalogoController;
use App\Http\Controllers\Dashboard\CarritoController;
use App\Http\Controllers\Dashboard\ReporteController;
use App\Http\Controllers\Dashboard\TiendaController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PERFIL
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.index');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/cambiar-password', [PerfilController::class, 'changePassword'])->name('perfil.change-password');

    // USUARIOS (excepto show)
    Route::resource('/usuarios', UsuarioController::class)->except(['show']);

    // Listados independientes
    Route::get('usuarios/administradores', [UsuarioController::class, 'administradores'])->name('usuarios.administradores');
    Route::get('usuarios/socios', [UsuarioController::class, 'socios'])->name('usuarios.socios');

    // PROVEEDORES
    Route::resource('/proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor'
    ]);

    // EMPLEADOS
    Route::resource('/empleados', EmpleadoController::class);

    // PRODUCTOS
    Route::get('/productos/importar', [ProductoController::class, 'importView'])->name('productos.importView');
    Route::post('/productos/importar', [ProductoController::class, 'importStore'])->name('productos.importStore');
    Route::get('/productos/exportar', [ProductoController::class, 'exportData'])->name('productos.exportData');
    Route::resource('/productos', ProductoController::class);

    // ROLES (solo admin)
    Route::middleware(['rol:administrador'])->group(function () {
        Route::resource('/roles', RoleController::class);
    });

    // CATEGORÍAS
    Route::resource('/categorias', CategoriaController::class);

    // CATALOGO
    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

    // TIENDA
    Route::get('/tienda', [CarritoController::class, 'tienda'])->name('clientes.tienda');
    Route::post('/add-cart', [CarritoController::class, 'addCart'])->name('cliente.addCart');
    Route::post('/update-cart', [CarritoController::class, 'updateCart'])->name('cliente.updateCart');
    Route::post('/remove-cart', [CarritoController::class, 'removeCart'])->name('cliente.removeCart');
    Route::post('/clear-cart', [CarritoController::class, 'clearCart'])->name('cliente.clearCart');
    Route::post('/cliente/pagar', [PedidoController::class, 'store'])->name('cliente.pagar');

    // POS unificado
    Route::prefix('pos')->group(function() {
        Route::get('/local', [PosController::class, 'index'])->name('pos.local')->defaults('tipo', 'local');
        Route::get('/online', [PosController::class, 'index'])->name('pos.online')->defaults('tipo', 'online');

        Route::post('/agregar', [PosController::class, 'addCart'])->name('pos.addCart');
        Route::post('/actualizar/{rowId}', [PosController::class, 'updateCart'])->name('pos.updateCart');
        Route::get('/eliminar/{rowId}', [PosController::class, 'deleteCart'])->name('pos.deleteCart');
        Route::get('/clear', [PosController::class, 'clearCart'])->name('pos.clearCart');
        Route::post('/pedido', [PosController::class, 'storePedido'])->name('pos.storePedido');
    });

    // PEDIDOS
    Route::get('/pedidos/pendientes', [PedidoController::class, 'pendientes'])->name('pedidos.pendientes');
    Route::get('/pedidos/completados', [PedidoController::class, 'completados'])->name('pedidos.completados');
    Route::get('/pedidos/detalles/{pedido_id}', [PedidoController::class, 'detalles'])->name('pedidos.detalles');
    Route::post('/pedidos/actualizar-estado', [PedidoController::class, 'actualizarEstado'])->name('pedidos.actualizarEstado');
    Route::get('/pedidos/nota/{id}', [PedidoController::class, 'generarNota'])->name('pedidos.nota');
    // DATOS DE PAGO (para el administrador)
    Route::get('/pedidos/datos-pago', [PedidoController::class, 'datosPago'])->name('pedidos.datos-pago');
    Route::post('/pedidos/guardarDatosPago', [PedidoController::class, 'guardarDatosPago'])->name('pedidos.guardarDatosPago');


    // Pendientes de pago
    Route::get('/pedidos/pendientes-pago', [PedidoController::class, 'pendientesPago'])->name('pedidos.pendientesPago');
    Route::get('/pedido/ajax-pendiente/{id}', [PedidoController::class, 'ajaxPendiente'])->name('pedidos.ajaxPendiente');
    Route::post('/pedidos/actualizar-pendiente', [PedidoController::class, 'actualizarPendiente'])->name('pedidos.actualizarPendiente');

    // Gestión de stock
    Route::get('/stock', [PedidoController::class, 'stock'])->name('pedidos.stock');

    // Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');

        Route::middleware(['rol:socio'])->group(function() {
            Route::get('/mis-compras', [PedidoController::class, 'misCompras'])->name('pedidos.misCompras');
        });
    });

    // Cambiar estado pedido
    Route::post('/pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');
    // Actualizar pedido pendiente
    Route::post('/pedidos/actualizar-pedido', [PedidoController::class, 'actualizarPedido'])->name('pedidos.actualizarPedido');
    // MENSAJE FLOTANTE ERROR
    Route::get('/carrito-vacio', function() {
        return redirect()->back()->with('error', 'Debe seleccionar al menos 1 producto.');
    })->name('carrito.vacio');
    
    


    
});


require __DIR__.'/auth.php';
