<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompradorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    // Verifica si el usuario está autenticado
    if (session()->has('logged')) {
        // Obtiene el rol del usuario autenticado
        $userRol = strtolower(session('logged.rol', ''));
        // Redirige a la vista correspondiente según el rol
        return match ($userRol) {
            'supervisor' => redirect()->route('admin.dashboard'),
            'comprador' => redirect()->route('compra.dashboard'),
            'usuario' => redirect()->route('user.dashboard'),
            default => redirect()->route('login'),
        };
    }
    // Si no hay usuario autenticado, redirige al login
    return redirect()->route('login');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post('/login', [AuthController::class, 'login'])->name('signin');
Route::get(
    '/off',
    function () {
        session()->flush();
        session()->invalidate();
        return redirect()->route('login');
        //return view('off');
    }
)->name('off');


// Grupo para supervisores (autenticación + rol supervisor)
Route::middleware(['ldap.auth', 'role:supervisor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    // Otras rutas admin aquí
});

// Grupo para compradores (autenticación + rol comprador)
Route::middleware(['ldap.auth', 'role:comprador'])->prefix('compra')->name('compra.')->group(function () {
    Route::get('/', [CompradorController::class, 'index'])->name('dashboard');
    // Otras rutas compra aquí
});

// Grupo para usuarios (autenticación + rol usuario)
Route::middleware(['ldap.auth', 'role:usuario'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', [SolicitudController::class, 'dashboard'])->name('dashboard');
    Route::get('/solicitud', [SolicitudController::class, 'add'])->name('solicitud');
    Route::post('/add-solicitud', [SolicitudController::class, 'addSolicitud'])->name('addSolicitud');
});


Route::prefix('api')->name('api.')->group(function () {
    Route::get('/search-products', [ProductoController::class, 'search'])->name('producto');
});
