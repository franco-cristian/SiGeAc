<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            // Extraemos los nombres de usuario de las URLs para mostrarlos en los inputs
            'github_username' => $request->user()->github_url ? Str::afterLast($request->user()->github_url, '/') : '',
            'linkedin_username' => $request->user()->linkedin_url ? Str::afterLast($request->user()->linkedin_url, '/') : '',
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Rellenar los campos validados del request
        $request->user()->fill($request->validated());

        // Si el email fue modificado, resetear la fecha de verificación
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // --- MANEJO DE CAMPOS PERSONALIZADOS ---

        // Construir y guardar las URLs de redes sociales
        $request->user()->github_url = $request->github_username ? 'https://github.com/' . $request->github_username : null;
        $request->user()->linkedin_url = $request->linkedin_username ? 'https://www.linkedin.com/in/' . $request->linkedin_username : null;
        $request->user()->professional_url = $request->professional_url;
        $request->user()->phone = $request->phone;

        // Manejar la subida de la foto de perfil si se proporcionó una nueva
        if ($request->hasFile('photo')) {
            // Borrar la foto anterior si existe
            if ($request->user()->photo_path) {
                Storage::disk('local')->delete($request->user()->photo_path);
            }
            // Guardar la nueva foto
            $request->user()->photo_path = $request->file('photo')->store('profile_photos', 'local');
        }

        // Guardar todos los cambios
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}