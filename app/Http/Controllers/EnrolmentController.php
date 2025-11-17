<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrolmentController extends Controller
{
    public function index()
    {
        $enrolments = Enrollment::with(['student', 'course'])->get();
        return view('Enrolments.index', compact('enrolments'));
    }

    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        return view('Enrolments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'course_id' => 'required|exists:course,course_id',
            'mrp' => 'required|numeric',
            'sell_price' => 'required|numeric',
        ]);

        Enrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'mrp' => $request->mrp,
            'sell_price' => $request->sell_price,
        ]);

        return redirect()->route('enrolments.index')->with('success', 'Enrolment created successfully!');
    }

    public function edit($id)
    {
        $enrolment = Enrollment::findOrFail($id);
        $students = Student::all();
        $courses = Course::all();
        return view('Enrolments.edit', compact('enrolment', 'students', 'courses'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'course_id' => 'required|exists:course,course_id',
            'mrp' => 'required|numeric',
            'sell_price' => 'required|numeric',
        ]);

        $enrolment = Enrollment::findOrFail($id);
        $enrolment->update($request->all());

        return redirect()->route('enrolments.index')->with('success', 'Enrolment updated successfully!');
    }

    public function destroy($id)
    {
        Enrollment::destroy($id);
        return redirect()->route('enrolments.index')->with('success', 'Enrolment deleted successfully!');
    }
}
