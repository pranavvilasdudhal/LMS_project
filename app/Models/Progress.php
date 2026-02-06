<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $table = 'progress';

     protected $fillable = [
        'student_id',
        'course_id',
        'session_id',
        'video_completed',
        'pdf_completed',
        'task_completed',
        'exam_completed',
        'completed'
    ];

    protected $casts = [
        'video_completed' => 'boolean',
        'pdf_completed'   => 'boolean',
        'task_completed'  => 'boolean',
        'exam_completed'  => 'boolean',
        'completed'       => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id', 'id');
    }
}
