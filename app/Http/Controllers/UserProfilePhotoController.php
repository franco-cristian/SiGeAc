<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserProfilePhotoController extends Controller
{
    use AuthorizesRequests;

    public function show(User $user)
    {
        $this->authorize('viewPhoto', $user);

        if (!$user->photo_path || !Storage::disk('local')->exists($user->photo_path)) {
            abort(404);
        }

        return Storage::disk('local')->response($user->photo_path);
    }
}