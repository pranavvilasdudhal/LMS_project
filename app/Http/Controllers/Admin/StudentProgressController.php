<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentProgressController extends Controller
{
     public function index()
    {
        $students = Student::with('enrollments.course')->get();
        return view('student_progres.index',compact('students'));
    }

    // public function show($id)
    // {
    //     $student = Student::with('enrollments.course.subjects.sections.sessions')->findOrFail($id);

    //     return view('student_progres.show',compact('student'));
    // }

 
    public function show($student_id)
    {
        $student = Student::with([
            'enrollments.course.subject.sections.sessions'
        ])->findOrFail($student_id);

        return view('student_progres.show', compact('student'));
    }
}
