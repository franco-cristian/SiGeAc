<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Formateo y validación de Nombre
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-.\']+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            
            // Validación de Contraseña Segura
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            
            // Validación Numérica para DNI y Teléfono
            'dni' => ['required', 'string', 'numeric', 'digits:8', 'unique:'.User::class],
            'phone' => ['required', 'string', 'numeric', 'regex:/^[0-9]{10,15}$/'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            
            // Validación para usuarios de redes sociales (sin la URL base)
            'github_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'linkedin_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'professional_url' => ['nullable', 'url', 'max:255'],
            
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Manejar la subida de la foto
        $photoPath = $request->file('photo')->store('profile_photos', 'local');

        $user = User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            // Construimos la URL completa aquí
            'github_url' => $request->github_username ? 'https://github.com/' . $request->github_username : null,
            'linkedin_url' => $request->linkedin_username ? 'https://www.linkedin.com/in/' . $request->linkedin_username : null,
            'professional_url' => $request->professional_url,
            'photo_path' => $photoPath,
        ]);

        $user->assignRole('Alumno');
        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}