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
            $studentIds = $currentUser->coursesAsTeacher()
                ->with('students:id')
                ->get()
                ->pluck('students.*.id')
                ->flatten()
                ->unique();
            
            return $studentIds->contains($targetUser->id);
        }

        // --- NUEVA REGLA 4: Un Alumno puede ver la foto si el targetUser es uno de sus docentes. ---
        if ($currentUser->hasRole('Alumno')) {
            // Obtenemos los IDs de todos los docentes del alumno
            $teacherIds = $currentUser->coursesAsStudent()
                ->pluck('teacher_id')
                ->unique();

            // Verificamos si el ID del usuario objetivo (el profesor) está en la lista.
            return $teacherIds->contains($targetUser->id);
        }

        // Si ninguna de las reglas anteriores se cumple, se deniega el acceso.
        return false;
    }
}