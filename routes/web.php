<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompradorController;
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


// Ruta de logout (requiere autenticación)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware(['ldap.auth'])
    ->name('logout');

// Rutas protegidas que requieren autenticación
Route::middleware(['ldap.auth', 'no.cache'])->group(function () {
    Route::get('/dashboard', [UsuarioController::class, 'index'])->name('dashboard');

    /**
     * SUB-RUTAS
     */
    //Solicitud
    Route::prefix('solicitud')->name('solicitud.')->group(function() {
        Route::get('/', [SolicitudController::class, 'index'])->name('home');
        //Route::get('/nueva', [SolicitudController::class, 'nueva'])->name('nueva');
        Route::get('/productos', [SolicitudController::class, 'productos'])->name('productos');
        Route::post('/nueva', [SolicitudController::class, 'nueva'])->name('nueva');
        Route::get('/{anno}/{numb}', [SolicitudController::class, 'mostrar'])->name('mostrar');
        Route::post('/save', [SolicitudController::class, 'addSolicitud'])->name('save');
        Route::post('/update', [SolicitudController::class, 'updSolicitud'])->name('update');
    });

    //gestion
    Route::prefix('gestion')->name('gestion.')->group(function() {
        Route::get('/', [CompradorController::class,'index'])->name('home');
        Route::get('/solicitud/{anno}/{numb}', [CompradorController::class,'estado'])->name('solicitud');
        //Route::post('/nueva', [UsuarioController::class, 'addSolicitud'])->name('addSolicitud');
    });

    //administracion
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/', [AdminController::class, 'index'])->name('home');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        //Route::post('/nueva', [UsuarioController::class, 'addSolicitud'])->name('addSolicitud');
    });
});

// API Routes (también protegidas)
Route::prefix('api')
    ->name('api.')
    ->middleware(['ldap.auth', 'no.cache'])
    ->group(function () {
        Route::post('/search-products', [ProductoController::class, 'search'])->name('producto');
        Route::get('/p/{id}', [ProductoController::class, 'existsProducto'])->name('existsProducto');
        Route::post('/delsolicitud/{id}', [SolicitudController::class, 'destroy'])->name('deleteSolicitud');
        Route::post('/solicitud/{id}', [CompradorController::class, 'changeState'])->name('changeStateSolicitud');
        Route::get('/profile', [PerfilController::class, 'index'])->name('perfil');
    });