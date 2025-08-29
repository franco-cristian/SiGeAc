<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view the model's photo.
     */
    public function viewPhoto(User $currentUser, User $targetUser): bool
    {
        // Regla 1: Un usuario siempre puede ver su propia foto.
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Regla 2: Un SuperAdmin puede ver la foto de cualquier usuario.
        if ($currentUser->hasRole('SuperAdmin')) {
            return true;
        }

        // Regla 3: Un Docente puede ver la foto si el targetUser es uno de sus alumnos.
        if ($currentUser->hasRole('Docente')) {
            // Obtenemos los IDs de todos los alumnos del docente
            $studentIds = $currentUser->coursesAsTeacher()
                ->with('students:id') // Carga solo el ID del estudiante para optimizar
                ->get()
                ->pluck('students.*.id')
                ->flatten()
                ->unique();
            
            // Verificamos si el ID del usuario objetivo está en la lista de alumnos
            return $studentIds->contains($targetUser->id);
        }

        // Si ninguna de las reglas anteriores se cumple, se deniega el acceso.
        return false;
    }
}