<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Session;
use App\Models\Student;
use App\Models\subject;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    /* =====================================================
     |  WEB (ADMIN) – Student Progress Pages (UNCHANGED)
     ===================================================== */

    public function index()
    {
        $students = Student::with('enrollments.course')->get();
        return view('student_progres.index', compact('students'));
    }

    public function show($student_id)
    {
        $student = Student::with([
            'enrollments.course.subject.sections.sessions'
        ])->findOrFail($student_id);

        return view('student_progres.show', compact('student'));
    }

    public function courseDetail($student_id, $course_id)
    {
        $student = Student::with([
            'enrollments.course.subject.sections.sessions'
        ])->findOrFail($student_id);

        $enroll = $student->enrollments->firstWhere('course_id', $course_id);
        $course = $enroll->course;

        return view('student_progres.course_detail', compact('student', 'course'));
    }

    /* =====================================================
     |  TEMP STUDENT RESOLVER (API ONLY)
     ===================================================== */

    private function apiStudent()
    {
        // TEMP: bina auth API sathi
        return Student::first();
    }

    /* =====================================================
     |  CORE PROGRESS LOGIC (UNCHANGED)
     ===================================================== */

    public static function sessionProgress($studentId, $sessionId)
    {
        $progress = Progress::where('student_id', $studentId)
            ->where('session_id', $sessionId)
            ->first();

        if (!$progress) return 0;

        $percent = 0;

        if ($progress->video_completed) $percent += 25;
        if ($progress->pdf_completed)   $percent += 25;
        if ($progress->task_completed)  $percent += 25;
        if ($progress->exam_completed)  $percent += 25;

        return $percent;
    }




    public static function subjectProgress($studentId, $subjectId)
    {
        $sessions = Session::whereHas('section', function ($q) use ($subjectId) {
            $q->where('subject_id', $subjectId);
        })->get();

        if ($sessions->count() == 0) return 0;

        $total = 0;

        foreach ($sessions as $session) {
            $total += self::sessionProgress($studentId, $session->id);
        }

        return round($total / $sessions->count());
    }


    public static function courseProgress($studentId, $courseId)
    {
        $subjects = Subject::where('course_id', $courseId)->get();

        if ($subjects->count() == 0) return 0;

        $total = 0;

        foreach ($subjects as $subject) {
            $total += self::subjectProgress($studentId, $subject->subject_id);
        }

        return round($total / $subjects->count());
    }


    public static function sectionProgress($studentId, $sectionId)
    {
        $sessions = Session::where('section_id', $sectionId)->get();
        if ($sessions->count() == 0) return 0;

        $total = 0;
        foreach ($sessions as $s) {
            $total += self::sessionProgress($studentId, $s->id);
        }

        return round($total / $sessions->count());
    }



    /* =====================================================
     |  STUDENT APIs (FIXED – NO AUTH REQUIRED)
     ===================================================== */

    public function getStudentCourses(Request $request)
    {
        // TEMP: student_id hardcode (later auth)
        $student = Student::with('enrollments.course')->first();

        $data = [];
        foreach ($student->enrollments as $enroll) {
            $data[] = [
                'course_id' => $enroll->course->course_id,
                'course_name' => $enroll->course->course_name,
                'progress_percent' => self::courseProgress(
                    $student->student_id,
                    $enroll->course->course_id
                )
            ];
        }

        return response()->json($data);
    }

    public function getSubjectProgress($subject_id)
    {
        $student = Student::first();

        return response()->json([
            'subject_id' => $subject_id,
            'progress_percent' =>
            self::subjectProgress($student->student_id, $subject_id)
        ]);
    }


    public function sessionProgressApi($session_id)
    {
        $student = Student::first();

        return response()->json([
            'session_id' => $session_id,
            'progress_percent' =>
            self::sessionProgress($student->student_id, $session_id)
        ]);
    }

    public function videoComplete(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:session,id'
        ]);

        $student = Student::first(); // testing purpose

        $session = Session::with('section.subject.courses')->findOrFail($request->session_id);

        $course = $session->section->subject->courses->first();

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found for this session'
            ], 404);
        }

        Progress::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'session_id' => $session->id,
                'course_id'  => $course->course_id,
            ],
            [
                'video_completed' => 1
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Video completed (+25%)'
        ]);
    }


    public function pdfUploaded(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:session,id'
        ]);

        $student = Student::first();

        $session = Session::with('section.subject.courses')->findOrFail($request->session_id);
        $course = $session->section->subject->courses->first();

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found for this session'
            ], 404);
        }

        Progress::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'session_id' => $session->id,
                'course_id'  => $course->course_id,
            ],
            [
                'pdf_completed' => 1
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'PDF uploaded (pending approval)'
        ]);
    }


    /* =====================================================
     |  ADMIN APIs (NO AUTH – TEMP)
     ===================================================== */

    public function adminStudentsProgress()
    {
        $students = Student::with('enrollments.course')->get();
        $data = [];

        foreach ($students as $student) {
            foreach ($student->enrollments as $enroll) {
                $data[] = [
                    'student' => $student->student_name,
                    'email' => $student->student_email,
                    'course' => $enroll->course->course_name,
                    'progress_percent' =>
                    self::courseProgress(
                        $student->student_id,
                        $enroll->course->course_id
                    )
                ];
            }
        }

        return response()->json($data);
    }


    public function adminCourseDetail($student_id, $course_id)
    {
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $course = Course::with('subjects.sections.sessions')
            ->find($course_id);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $subjects = [];

        foreach ($course->subjects as $sub) {
            $subjects[] = [
                'subject_id' => $sub->subject_id,
                'subject_name' => $sub->subject_name,
                'progress_percent' =>
                self::subjectProgress(
                    $student->student_id,
                    $sub->subject_id
                )
            ];
        }

        return response()->json([
            'status' => true,
            'student' => [
                'id' => $student->student_id,
                'name' => $student->student_name,
                'email' => $student->student_email
            ],
            'course' => [
                'id' => $course->course_id,
                'name' => $course->course_name
            ],
            'subjects' => $subjects
        ]);
    }
}
