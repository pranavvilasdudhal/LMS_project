<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $primaryKey = 'enrollment_id';

    protected $fillable = [
        'student_id',
        'course_id',
        'mrp',
        'sell_price',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    // ✅ ADD THIS
    public function progress()
    {
        return $this->hasMany(Progress::class, 'course_id', 'course_id')
                    ->whereColumn('progress.student_id', 'enrollments.student_id');
    }
}
