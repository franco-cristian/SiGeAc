<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dni' => ['required', 'string', 'max:10', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'professional_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // 2MB Max
        ]);

        // Manejar la subida de la foto
        $photoPath = $request->file('photo')->store('profile_photos', 'local');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'phone' => $request->phone,
            'professional_url' => $request->professional_url,
            'photo_path' => $photoPath,
        ]);

        // Asignar el rol de Alumno por defecto
        $user->assignRole('Alumno');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}