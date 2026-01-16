<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Session;
use App\Models\SessionProgress;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{


    public function completeSession(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'session_id' => 'required|integer',
            'video_completed' => 'required|boolean',
            'pdf_completed' => 'required|boolean',
        ]);

        $user = $request->user();

        // 🔹 Student ID (email based mapping)
        $student = $user->student;
        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // 🔹 Update session_progress
        DB::table('session_progress')->updateOrInsert(
            [
                'user_id' => $user->id,
                'session_id' => $request->session_id,
            ],
            [
                'video_completed' => $request->video_completed,
                'pdf_completed' => $request->pdf_completed,
                'unlocked' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // 🔹 Session completed rule
        if ($request->video_completed && $request->pdf_completed) {
            Progress::updateOrCreate(
                [
                    'student_id' => $student->student_id,
                    'course_id' => $request->course_id,
                    'session_id' => $request->session_id,
                ],
                [
                    'completed' => true,
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Session progress updated successfully'
        ], 200);
    }


    public function studentProgress(Request $request, $course_id)
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // 🔹 Total sessions
        $course = Course::with('subject.sections.sessions')->find($course_id);

        $totalSessions = 0;
        if ($course) {
            foreach ($course->subject as $sub) {
                foreach ($sub->sections as $sec) {
                    $totalSessions += count($sec->sessions);
                }
            }
        }

        // 🔹 Completed sessions
        $completedSessions = Progress::where('student_id', $student->student_id)
            ->where('course_id', $course_id)
            ->where('completed', true)
            ->count();

        $percentage = ($totalSessions == 0)
            ? 0
            : round(($completedSessions / $totalSessions) * 100);

        return response()->json([
            'status' => true,
            'total_sessions' => $totalSessions,
            'completed_sessions' => $completedSessions,
            'progress_percent' => $percentage
        ], 200);
    }



    public function adminProgress(Request $request, $course_id)
{
    $user = $request->user();

    if ($user->role !== 'admin') {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $students = Student::all();
    $result = [];

    foreach ($students as $student) {

        $totalSessions = DB::table('session')
            ->where('course_id', $course_id)
            ->count();

        $completedSessions = Progress::where('student_id', $student->student_id)
            ->where('course_id', $course_id)
            ->where('completed', true)
            ->count();

        $percentage = ($totalSessions == 0)
            ? 0
            : round(($completedSessions / $totalSessions) * 100);

        $result[] = [
            'student_id' => $student->student_id,
            'name' => $student->student_name,
            'email' => $student->student_email,
            'progress_percent' => $percentage,
        ];
    }

    return response()->json([
        'status' => true,
        'students' => $result
    ], 200);
}


    // // 🎥 VIDEO COMPLETE → PDF UNLOCK
    // public function videoComplete(Request $request)
    // {
    //     $progress = SessionProgress::updateOrCreate(
    //         [
    //             'user_id' => $request->user_id,
    //             'session_id' => $request->session_id,
    //         ],
    //         [
    //             'video_completed' => true,
    //             'unlocked' => true
    //         ]
    //     );

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Video completed. PDF unlocked.'
    //     ]);
    // }

    // // 📄 PDF COMPLETE → NEXT SESSION UNLOCK
    // public function pdfComplete(Request $request)
    // {
    //     $progress = SessionProgress::where('user_id', $request->user_id)
    //         ->where('session_id', $request->session_id)
    //         ->firstOrFail();

    //     $progress->pdf_completed = true;
    //     $progress->save();

    //     // 🔓 Unlock next session
    //     $nextSession = Session::where('section_id', $request->section_id)
    //         ->where('id', '>', $request->session_id)
    //         ->orderBy('id')
    //         ->first();

    //     if ($nextSession) {
    //         SessionProgress::updateOrCreate(
    //             [
    //                 'user_id' => $request->user_id,
    //                 'session_id' => $nextSession->id,
    //             ],
    //             [
    //                 'unlocked' => true
    //             ]
    //         );
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'PDF completed. Next session unlocked.'
    //     ]);
    // }




    // public function updateProgress(Request $req)
    // {
    //     try {
    //         $req->validate([
    //             'student_id' => 'required',
    //             'course_id'  => 'required',
    //             'session_id' => 'required',
    //         ]);

    //         // Validate foreign keys
    //         if (!DB::table('students')->where('student_id', $req->student_id)->exists()) {
    //             return response()->json(["status" => false, "error" => "Invalid student_id"], 400);
    //         }

    //         if (!DB::table('course')->where('course_id', $req->course_id)->exists()) {
    //             return response()->json(["status" => false, "error" => "Invalid course_id"], 400);
    //         }

    //         if (!DB::table('session')->where('id', $req->session_id)->exists()) {
    //             return response()->json(["status" => false, "error" => "Invalid session_id"], 400);
    //         }

    //         Progress::updateOrCreate(
    //             [
    //                 'student_id' => $req->student_id,
    //                 'course_id' => $req->course_id,
    //                 'session_id' => $req->session_id,
    //             ],
    //             ['completed' => true]
    //         );

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Progress updated successfully'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(["status" => false, "error" => $e->getMessage()], 500);
    //     }
    // }

    // // NEW API → Course Progress Calculation
    // public function courseProgress($student_id, $course_id)
    // {
    //     try {

    //         // 1. total sessions for this course
    //         $total = DB::table('session')
    //             ->join('section', 'section.section_id', '=', 'session.section_id')
    //             ->join('subject', 'subject.subject_id', '=', 'section.subject_id')
    //             ->where('subject.course_id', $course_id)
    //             ->count();

    //         // 2. completed sessions
    //         $completed = Progress::where('student_id', $student_id)
    //             ->where('course_id', $course_id)
    //             ->where('completed', true)
    //             ->count();

    //         $percent = $total == 0 ? 0 : round(($completed / $total) * 100);

    //         return response()->json([
    //             'status' => true,
    //             'total_sessions' => $total,
    //             'completed_sessions' => $completed,
    //             'progress_percent' => $percent
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             "status" => false,
    //             "error" => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function taskComplete(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required',
    //         'session_id' => 'required',
    //         'section_id' => 'required',
    //     ]);

    //     SessionProgress::updateOrCreate(
    //         [
    //             'user_id' => $request->user_id,
    //             'session_id' => $request->session_id,
    //         ],
    //         [
    //             'task_completed' => true,
    //             'unlocked' => true,
    //         ]
    //     );

    //     // 🔓 Unlock EXAM
    //     $examSession = Session::where('section_id', $request->section_id)
    //         ->where('type', 'exam')
    //         ->first();

    //     if ($examSession) {
    //         SessionProgress::updateOrCreate(
    //             [
    //                 'user_id' => $request->user_id,
    //                 'session_id' => $examSession->id,
    //             ],
    //             [
    //                 'unlocked' => true,
    //             ]
    //         );
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Task completed, Exam unlocked',
    //     ]);
    // }
}
