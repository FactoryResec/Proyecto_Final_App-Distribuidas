<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PedidoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.inicio');
})->name('inicio');

// Rutas de autenticación
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('auth.principal');
    })->name('dashboard');
});

//Rutas de los proveedores
Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
Route::get('/proveedores/{id}', [ProveedorController::class, 'show'])->name('proveedores.show');
Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
Route::get('/proveedores/{proveedor}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');

//Rutas de los productos
Route::get('/dashboard', [App\Http\Controllers\ProductoController::class, 'regreso'])->name('productos.regreso');
Route::get('/productos', [App\Http\Controllers\ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/create', [App\Http\Controllers\ProductoController::class, 'create'])->name('productos.create');
Route::get('/productos/{id}', [App\Http\Controllers\ProductoController::class, 'show'])->name('productos.show');
Route::delete('/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'destroy'])->name('productos.destroy');
Route::post('/productos', [App\Http\Controllers\ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{producto}/edit', [App\Http\Controllers\ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{id}', [App\Http\Controllers\ProductoController::class, 'update'])->name('productos.update');
Route::get('/grafica-productos-mas-vendidos', [App\Http\Controllers\ProductoController::class, 'graficaProductosMasVendidos'])->name('grafica.productos.mas.vendidos');
Route::get('/products/low-stock', [App\Http\Controllers\ProductoController::class, 'lowStock'])->name('products.lowStock');


//Rutas de los Usuarios 
Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/create', [App\Http\Controllers\UserController::class, 'create'])->name('usuarios.create');
Route::get('/usuarios/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('usuarios.show');
Route::post('/usuarios', [App\Http\Controllers\UserController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{id}/editar', [App\Http\Controllers\UserController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('usuarios.destroy');


//Rutas de las ventas
Route::get('/ventas/create', [App\Http\Controllers\VentaController::class, 'create'])->name('ventas.create');
Route::get('/product/search', [App\Http\Controllers\ProductoController::class, 'search'])->name('product.search');
Route::post('/generate-pdf', [App\Http\Controllers\VentaController::class, 'generatePDF'])->name('generate.pdf');
Route::get('/ventas/create/Mail', [App\Http\Controllers\VentaController::class, 'createMail'])->name('ventas.createMail');
Route::post('/report/send_report', [App\Http\Controllers\VentaController::class, 'sendReport'])->name('report.sendReport');

