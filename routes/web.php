<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de encuesta
Route::prefix('encuesta')->group(function () {
    Route::get('/crear', [EncuestaController::class, 'create'])->name('encuesta.create');
    Route::post('/guardar', [EncuestaController::class, 'store'])->name('encuesta.store');
    Route::get('/exito', [EncuestaController::class, 'success'])->name('encuesta.success');
    Route::get('/obras-por-colonia/{colonia}', [EncuestaController::class, 'getObrasByColonia'])
        ->name('encuesta.obras-por-colonia');
});

// Rutas del panel de administración
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/estadisticas', [DashboardController::class, 'estadisticas'])->name('estadisticas');
    Route::get('/obras-por-colonia/{colonia}', [DashboardController::class, 'getObrasPorColonia'])->name('obras-por-colonia');
    Route::get('/encuestas', [DashboardController::class, 'encuestas'])->name('encuestas.index');
    Route::get('/encuestas/{id}', [DashboardController::class, 'showEncuesta'])->name('encuestas.show');
    Route::delete('/encuestas/{id}', [DashboardController::class, 'destroy'])->name('encuestas.destroy');
    Route::get('/export/encuestas', [ExportController::class, 'encuestas'])->name('export.encuestas');
    Route::get('/export/estadisticas-pdf', [ExportController::class, 'estadisticasPdf'])->name('export.estadisticas-pdf');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
