<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompradorController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Verifica si el usuario está autenticado
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Si no hay usuario autenticado, redirige al login
    return redirect()->route('login');
})->name('home');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    
    Route::post('/login', [AuthController::class, 'login'])->name('signin');
});
/*
Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post('/login', [AuthController::class, 'login'])->name('signin');
Route::post('/off',[AuthController::class,'logout'])->name('off');

/*
 Route::get(
    '/off',
    function () {
        session()->flush();
        session()->invalidate();
        return redirect()->route('login');
        //return view('off');
    }
)->name('off');
 */
/*

// Grupo para compradores (autenticación + rol comprador)
Route::middleware(['ldap.auth', 'no.cache'])->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('dashboard');
    Route::get('/solicitud', [SolicitudController::class, 'add'])->name('solicitud');
    Route::post('/add-solicitud', [UsuarioController::class, 'addSolicitud'])->name('addSolicitud');
});


Route::prefix('api')->name('api.')->group(function () {
    Route::get('/search-products', [ProductoController::class, 'search'])->name('producto');
    Route::delete('/solicitud/{id}', [SolicitudController::class, 'destroy'])->name('deleteSolicitud');
    Route::put('/history/state/{id}', [HistoricoController::class, 'state'])->name('changeStateSolicitud');
    Route::get('/profile', [PerfilController::class, 'index'])->name('perfil');
});
*/


// Ruta de logout (requiere autenticación)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware(['ldap.auth'])
    ->name('logout');

// Rutas protegidas que requieren autenticación
Route::middleware(['ldap.auth', 'no.cache'])->group(function () {
    Route::get('/dashboard', [UsuarioController::class, 'index'])->name('dashboard');
    Route::get('/solicitud', [SolicitudController::class, 'add'])->name('solicitud');
    Route::post('/add-solicitud', [UsuarioController::class, 'addSolicitud'])->name('addSolicitud');
});

// API Routes (también protegidas)
Route::prefix('api')
    ->name('api.')
    ->middleware(['ldap.auth', 'no.cache'])
    ->group(function () {
        Route::get('/search-products', [ProductoController::class, 'search'])->name('producto');
        Route::delete('/solicitud/{id}', [SolicitudController::class, 'destroy'])->name('deleteSolicitud');
        Route::put('/history/state/{id}', [HistoricoController::class, 'state'])->name('changeStateSolicitud');
        Route::get('/profile', [PerfilController::class, 'index'])->name('perfil');
    });