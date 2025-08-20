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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ej: "COM 2.2"
            $table->year('year');
            $table->string('term'); // ej: "Cuarto Cuatrimestre"

            // Relación con Materias (Subject)
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');

            // Relación con Docentes (Users)
            // Si el docente se elimina, el curso queda sin docente (SET NULL)
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');

            $table->unsignedInteger('capacity')->default(40); // Cupo máximo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};