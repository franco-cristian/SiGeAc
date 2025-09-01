<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Genera un PDF con la lista de alumnos de un curso específico.
     */
    public function generateStudentListPdf(Request $request, Course $course)
    {
        // 1. Autorización
        if ($request->user()->id !== $course->teacher_id) {
            abort(403);
        }

        // 2. Validación
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'columns' => 'required|array',
            'columns.*' => 'in:photo,name,email,dni,phone,date_of_birth,linkedin_url,github_url,professional_url',
            'orientation' => 'required|in:portrait,landscape',
        ]);
        
        $selectedColumns = collect($validated['columns']);
        
        // 3. Obtención de Datos
        $students = User::whereIn('id', $validated['student_ids'])->orderBy('name')->get();
        
        // --- INICIO: LÓGICA PARA FOTOS EN BASE64 ---
        // Si la columna 'photo' fue seleccionada, procesamos las imágenes
        if ($selectedColumns->contains('photo')) {
            $students->each(function ($student) {
                if ($student->photo_path && Storage::disk('local')->exists($student->photo_path)) {
                    $file = Storage::disk('local')->get($student->photo_path);
                    $type = Storage::disk('local')->mimeType($student->photo_path);
                    // Creamos una nueva propiedad en el objeto del estudiante con la imagen en base64
                    $student->photo_base64 = 'data:' . $type . ';base64,' . base64_encode($file);
                } else {
                    $student->photo_base64 = null; // O un placeholder si lo tuviéramos
                }
            });
        }
        // --- FIN: LÓGICA PARA FOTOS EN BASE64 ---
        
        // 4. Preparación de Datos para la Vista
        $data = [
            'course' => $course->load('subject'),
            'students' => $students,
            'selectedColumns' => $selectedColumns,
            'generationDate' => now()->format('d/m/Y H:i'),
        ];

        // 5. Generación del PDF
        $pdf = Pdf::loadView('reports.student-list', $data)
                  ->setPaper('a4', $validated['orientation']);
        
        // 6. Descarga
        $fileName = 'alumnos-' . Str::slug($course->subject->name . '-' . $course->name) . '.pdf';
        return $pdf->download($fileName);
    }
}