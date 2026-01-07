<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // ------------------------------------------
    // SHOW STUDENT LIST + PROGRESS + CERTIFICATE
    // ------------------------------------------
    public function index()
    {
        $courseId = 1; // default course progress दिखवण्यासाठी

        $students = Student::with('user')->get();

        foreach ($students as $student) {

            // Load course sessions
            $course = Course::with('subject.sections.sessions')->find($courseId);

            $total = 0;
            if ($course) {
                foreach ($course->subject as $sub) {
                    foreach ($sub->sections as $sec) {
                        $total += count($sec->sessions);
                    }
                }
            }

            // Completed sessions
            $completed = Progress::where('student_id', $student->student_id)
                ->where('course_id', $courseId)
                ->where('completed', true)
                ->count();

            // Percentage
            $student->progress_percent = ($total == 0) ? 0 : round(($completed / $total) * 100);

            // Certificate
            $student->certificate = Certificate::where('student_id', $student->student_id)
                ->where('course_id', $courseId)
                ->first();
        }

        return view('students.index', compact('students'));
    }


    // ------------------------------------------
    // ADD NEW STUDENT
    // ------------------------------------------
    public function create()
    {
        return view('students.create');
    }

    public function toggleStatus($student_id)
    {
        $student = Student::findOrFail($student_id);

        $user = User::where('email', $student->student_email)->first();

        if ($user) {
            $user->is_active = !$user->is_active;
            $user->save();
        }

        return redirect()->back()->with('success', 'Student status updated successfully.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_email' => 'required|email',
            'student_phone' => 'required|string|max:15',
            'student_address' => 'required|string|max:255',
            'student_password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|in:student,teacher,admin',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student = Student::create([
            'student_name' => $request->student_name,
            'student_email' => $request->student_email,
            'student_phone' => $request->student_phone,
            'student_address' => $request->student_address,
            'student_password' => bcrypt($request->student_password),
        ]);

        $user = new User();
        $user->name = $request->student_name;
        $user->email = $request->student_email;
        $user->password = bcrypt($request->student_password);
        $user->role = $request->input('role', 'student');
        $user->is_active = true;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        $user->save();

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }


    // ------------------------------------------
    // EDIT STUDENT
    // ------------------------------------------
    public function edit($student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $student_id)
    {
        $student = Student::findOrFail($student_id);

        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_email' => 'required|email|max:255',
            'student_phone' => 'required|string|max:15',
            'student_address' => 'required|string|max:255',
            'student_password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|in:student,teacher,admin',
        ]);

        $student->update([
            'student_name' => $request->student_name,
            'student_email' => $request->student_email,
            'student_phone' => $request->student_phone,
            'student_address' => $request->student_address,
            'student_password' => $request->student_password ? bcrypt($request->student_password) : $student->student_password,
        ]);

        if ($student->user) {
            $user = $student->user;
            $user->name = $request->student_name;
            $user->email = $request->student_email;
            if ($request->student_password) {
                $user->password = bcrypt($request->student_password);
            }
            $user->role = $request->role;

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('profiles', 'public');
                $user->photo = $path;
            }
            $user->save();
        }

        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }


    // ------------------------------------------
    // DELETE STUDENT
    // ------------------------------------------
    public function destroy($student_id)
    {
        Student::findOrFail($student_id)->delete();
        return redirect('/students')->with('success', 'Student deleted successfully');
    }

    public function certificatePage()
{
    // सर्व certificates + student + course joins
    $certificates = Certificate::with(['student', 'course'])->get();

    return view('students.certificates', compact('certificates'));
}

}
