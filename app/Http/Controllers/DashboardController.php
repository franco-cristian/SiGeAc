<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('SuperAdmin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Docente')) {
            // Redirección a una ruta genérica.
            return redirect()->route('docente.dashboard');
        } elseif ($user->hasRole('Alumno')) {
            // La ruta 'dashboard' por defecto de Breeze.
            return redirect()->route('student.dashboard');
        }

        // Fallback por si un usuario no tiene rol
        Auth::logout();
        return redirect('/login')->with('error', 'No tienes un rol asignado.');
    }
}