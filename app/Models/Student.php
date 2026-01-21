<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_name',
        'student_email',
        'student_phone',
        'student_address',
        'student_password',
    ];

   
    public function user()
    {
        return $this->hasOne(
            User::class,
            'email',          // users.email
            'student_email'   // students.student_email
        );
    }

    /**
     * ✅ Student → Enrollments (FIX FOR ERROR)
     */
    public function enrollments()
    {
        return $this->hasMany(
            Enrollment::class,
            'student_id',     // enrollments.student_id
            'student_id'      // students.student_id
        );
    }

    /**
     * ✅ Student → Progress
     */
    public function progress()
    {
        return $this->hasMany(
            Progress::class,
            'student_id',
            'student_id'
        );
    }
}
