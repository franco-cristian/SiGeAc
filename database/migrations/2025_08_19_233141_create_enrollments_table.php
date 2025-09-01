<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            // Relación con Alumnos (Users)
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            
            // Relación con Cursos (Courses)
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            $table->enum('status', ['cursando', 'aprobado', 'reprobado', 'lista_de_espera'])->default('cursando');
            $table->timestamps();

            // Un alumno no puede inscribirse dos veces al mismo curso
            $table->unique(['student_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};