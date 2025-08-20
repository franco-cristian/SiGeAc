<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'term',
        'subject_id',
        'teacher_id',
        'capacity',
    ];

    /**
     * Get the subject that owns the course.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher that owns the course.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    /**
     * The students that belong to the course.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}