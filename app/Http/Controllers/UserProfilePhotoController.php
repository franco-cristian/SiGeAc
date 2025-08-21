<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserProfilePhotoController extends Controller
{
    public function show(User $user)
    {
        // Policy para verificar permisos
        // el usuario logueado debe tener permiso para ver esta foto.
        
        if (!Storage::disk('local')->exists($user->photo_path)) {
            abort(404);
        }

        return Storage::disk('local')->response($user->photo_path);
    }
}