<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\Users\UserList;
use Illuminate\Support\Facades\Route;
use App\Livewire\Student\CourseList;

// Ruta de bienvenida pública
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta de distribución post-login, protegida por 'auth' y 'verified(desactivado)'
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rutas del SuperAdmin
Route::middleware(['auth', 'role:SuperAdmin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/users', UserList::class)->name('users.index');
});

// Rutas del Docente
Route::middleware(['auth', 'role:Docente'])->name('docente.')->prefix('docente')->group(function () {
    Route::get('/dashboard', function () { return view('docente.dashboard'); })->name('dashboard');
});

// Rutas del Alumno
Route::middleware(['auth', 'role:Alumno'])->name('student.')->prefix('student')->group(function () {
    // La ruta del dashboard ahora apunta directamente al componente Livewire de la lista de cursos
    Route::get('/dashboard', CourseList::class)->name('dashboard');
});


// Rutas de perfil (comunes a todos los roles logueados)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
