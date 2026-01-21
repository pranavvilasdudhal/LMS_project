<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{

    /* =====================================================
     |  WEB (ADMIN) – Student Progress Pages
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

    // 🔹 Mark Session Complete
    public function completeSession(Request $request)
    {
        $student = $request->user()->student;

        $request->validate([
            'session_id' => 'required',
            'course_id' => 'required',
        ]);

        Progress::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'session_id' => $request->session_id,
                'course_id' => $request->course_id,
            ],
            [
                'completed' => 1
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Session completed'
        ]);
    }

    // Session progress (4 parts = 25% each)
    public static function sessionProgress($studentId, $sessionId)
    {
        $p = Progress::where('student_id', $studentId)
            ->where('session_id', $sessionId)
            ->first();

        $percent = 0;

        if ($p) {
            if ($p->video_completed) $percent += 25;
            if ($p->pdf_completed)   $percent += 25;
            if ($p->task_completed)  $percent += 25;
            if ($p->exam_completed)  $percent += 25;
        }

        return $percent;
    }

    public static function sectionProgress($studentId, $sectionId)
    {
        $total = Session::where('section_id', $sectionId)->count();
        $completed = Progress::where('student_id', $studentId)
            ->whereHas('session', fn($q) => $q->where('section_id', $sectionId))
            ->where('completed', 1)->count();

        return $total == 0 ? 0 : round(($completed / $total) * 100);
    }

    // Subject progress
    public static function subjectProgress($studentId, $subjectId)
    {
        $totalSessions = Session::whereHas('section', function ($q) use ($subjectId) {
            $q->where('subject_id', $subjectId);
        })->count();

        $completedSessions = Progress::where('student_id', $studentId)
            ->where('completed', 1)
            ->whereHas('session.section', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })->count();

        return $totalSessions == 0 ? 0 : round(($completedSessions / $totalSessions) * 100);
    }


    // Course progress
    public static function courseProgress($studentId, $courseId)
    {
        $totalSessions = Session::whereHas('section.subject', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->count();

        $completedSessions = Progress::where('student_id', $studentId)
            ->where('completed', 1)
            ->whereHas('session.section.subject', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })->count();

        return $totalSessions == 0 ? 0 : round(($completedSessions / $totalSessions) * 100);
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


    //-------------------------------- API CODE------------------------------------//

    //---------------- --------------- API CODE------------------------------------//


    // 🔹 Get Student Courses + Progress API code
    public function getStudentCourses(Request $request)
    {
        $student = $request->user()->student;

        $courses = $student->enrollments()->with('course')->get();

        $result = [];

        foreach ($courses as $enroll) {
            $result[] = [
                'course_id' => $enroll->course->course_id,
                'course_name' => $enroll->course->course_name,
                'progress_percent' => self::courseProgress(
                    $student->student_id,
                    $enroll->course->course_id
                )
            ];
        }

        return response()->json($result);
    }

    // 🔹 Get Subject Progress (For Flutter) API code

    // public function getSubjectProgress(Request $request, $subject_id)
    // {
    //     $student = $request->user()->student;

    //     return response()->json(
    //         self::subjectProgress($student->student_id, $subject_id)
    //     );
    // }


    public function getSubjectProgress(Request $request, $subject_id)
    {
        $student = $request->user()->student;

        return response()->json([
            'subject_id' => $subject_id,
            'progress_percent' => self::subjectProgress(
                $student->student_id,
                $subject_id
            )
        ]);
    }


    public function getSessionProgress(Request $request, $session_id)
    {
        $student = $request->user()->student;

        return response()->json([
            'session_id' => $session_id,
            'progress_percent' => self::sessionProgress(
                $student->student_id,
                $session_id
            )
        ]);
    }

    public function adminStudentsProgress()
    {
        $students = Student::with('enrollments.course')->get();

        $result = [];

        foreach ($students as $student) {
            foreach ($student->enrollments as $enroll) {
                $result[] = [
                    'student_name' => $student->student_name,
                    'email' => $student->student_email,
                    'course' => $enroll->course->course_name,
                    'progress' => self::courseProgress(
                        $student->student_id,
                        $enroll->course->course_id
                    )
                ];
            }
        }

        return response()->json($result);
    }



    public function adminCourseDetail($student_id, $course_id)
    {
        $student = Student::findOrFail($student_id);

        $course = Course::with('subject.sections.sessions')
            ->findOrFail($course_id);

        $subjects = [];

        foreach ($course->subject as $sub) {
            $subjects[] = [
                'subject_name' => $sub->subject_name,
                'progress' => self::subjectProgress(
                    $student->student_id,
                    $sub->subject_id
                )
            ];
        }

        return response()->json([
            'student' => $student->student_name,
            'course' => $course->course_name,
            'subjects' => $subjects
        ]);
    }

    public function videoComplete(Request $request)
    {
        $student = $request->user()->student;

        Progress::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'session_id' => $request->session_id,
            ],
            [
                'video_completed' => 1
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Video completed'
        ]);
    }


    // POST /api/student/session/pdf-uploaded

    public function pdfUploaded(Request $request)
    {
        $student = $request->user()->student;

        Progress::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'session_id' => $request->session_id,
            ],
            [
                'pdf_completed' => 1 // TEMPORARY (admin approve करेल)
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'PDF uploaded, waiting for approval'
        ]);
    }
}
