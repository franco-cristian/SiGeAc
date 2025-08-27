<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin \Spatie\Permission\Traits\HasRoles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $coursesAsStudent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $coursesAsTeacher
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'professional_url',
        'photo_path',
        'dni',
        'date_of_birth',
        'github_url',
        'linkedin_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    /**
     * The courses that the user teaches.
     */
    public function coursesAsTeacher(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * The courses that the user is enrolled in.
     */
    public function coursesAsStudent(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}
