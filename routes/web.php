<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserProfilePhotoController;
use App\Livewire\Admin\Users\UserList;
use App\Livewire\Student\CourseList;
use App\Livewire\Teacher\StudentList;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rutas Públicas ---
Route::get('/', function () {
    return redirect()->route('login');
});

// --- Rutas de Autenticación (Login, Registro, etc.) ---
require __DIR__.'/auth.php';


// --- Rutas Protegidas (Requieren Login) ---
Route::middleware(['auth'])->group(function () {

    // Ruta de distribución post-login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ruta para servir fotos de perfil de forma segura
    Route::get('/users/{user}/photo', [UserProfilePhotoController::class, 'show'])->name('users.photo');

    // Rutas de Perfil (comunes a todos los roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rutas Específicas por Rol ---

    // Rutas del SuperAdmin
    Route::middleware(['role:SuperAdmin'])->name('admin.')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
        Route::get('/users', UserList::class)->name('users.index');
    });

    // Rutas del Docente
    Route::middleware(['role:Docente'])->name('docente.')->prefix('docente')->group(function () {
        Route::get('/dashboard', StudentList::class)->name('dashboard');
    });

    // Rutas del Alumno
    Route::middleware(['role:Alumno'])->name('student.')->prefix('student')->group(function () {
        Route::get('/dashboard', CourseList::class)->name('dashboard');
    });

});