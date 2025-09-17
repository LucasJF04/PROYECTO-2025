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

    // PERFIL (única ruta para todos los roles)
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.index');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/cambiar-password', [PerfilController::class, 'changePassword'])->name('perfil.change-password');

    // USUARIOS
    Route::resource('/usuarios', UsuarioController::class)->except(['show']);

    // Rutas exclusivas para administradores
    Route::middleware('rol:administrador')->group(function () {
        Route::resource('/usuarios', UsuarioController::class);
    });

    // CLIENTES
    Route::resource('/clientes', ClienteController::class);

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
    //ROLES
    Route::middleware('auth', 'rol:administrador')->group(function () {
        Route::resource('/roles', RoleController::class);
    });

    // CATEGORÍAS
    Route::resource('/categorias', CategoriaController::class);


    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

    Route::get('/tienda', [CarritoController::class, 'tienda'])->name('clientes.tienda');
Route::post('/add-cart', [CarritoController::class, 'addCart'])->name('cliente.addCart');
Route::post('/update-cart', [CarritoController::class, 'updateCart'])->name('cliente.updateCart');
Route::post('/remove-cart', [CarritoController::class, 'removeCart'])->name('cliente.removeCart');
Route::post('/clear-cart', [CarritoController::class, 'clearCart'])->name('cliente.clearCart');
Route::post('/pagar', [CarritoController::class, 'pagar'])->name('cliente.pagar');
Route::post('/cliente/pagar', [PedidoController::class, 'store'])->name('cliente.pagar');





    // PUNTO DE VENTA
    Route::get('/pos', [PosController::class,'index'])->name('pos.index');
    Route::post('/pos/agregar', [PosController::class, 'addCart'])->name('pos.addCart');
    Route::post('/pos/actualizar/{rowId}', [PosController::class, 'updateCart'])->name('pos.updateCart');
    Route::get('/pos/eliminar/{rowId}', [PosController::class, 'deleteCart'])->name('pos.deleteCart');
    Route::post('/pos/factura/crear', [PosController::class, 'createInvoice'])->name('pos.createInvoice');
    Route::post('/pos/factura/imprimir', [PosController::class, 'printInvoice'])->name('pos.printInvoice');
    Route::get('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clearCart');


    // Crear pedido
    Route::post('/pos/pedido', [PedidoController::class, 'store'])->name('pos.storeOrder');

    // PEDIDOS
    Route::get('/pedidos/pendientes', [PedidoController::class, 'pendientes'])->name('pedidos.pendientes');
    Route::get('/pedidos/completados', [PedidoController::class, 'completados'])->name('pedidos.completados');
    Route::get('/pedidos/detalles/{pedido_id}', [PedidoController::class, 'detalles'])->name('pedidos.detalles');
    Route::put('/pedidos/actualizar-estado', [PedidoController::class, 'actualizarEstado'])->name('pedidos.actualizarEstado');
    Route::get('/pedidos/factura/descargar/{pedido_id}', [PedidoController::class, 'descargarFactura'])->name('pedidos.factura');

    // Pendientes de pago
    Route::get('/pedidos/pendientes-pago', [PedidoController::class, 'pendientesPago'])->name('pedidos.pendientesPago');
    Route::get('/pedido/ajax-pendiente/{id}', [PedidoController::class, 'ajaxPendiente'])->name('pedidos.ajaxPendiente');
    Route::post('/pedidos/actualizar-pendiente', [PedidoController::class, 'actualizarPendiente'])->name('pedidos.actualizarPendiente');

    // Gestión de stock
    Route::get('/stock', [PedidoController::class, 'stock'])->name('pedidos.stock');


    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');
    });

  
});

require __DIR__.'/auth.php';
