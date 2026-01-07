<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Progress;
use App\Models\Session;
use App\Models\SessionProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{


     // 🎥 VIDEO COMPLETE → PDF UNLOCK
    public function videoComplete(Request $request)
    {
        $progress = SessionProgress::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'session_id' => $request->session_id,
            ],
            [
                'video_completed' => true,
                'unlocked' => true
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Video completed. PDF unlocked.'
        ]);
    }

    // 📄 PDF COMPLETE → NEXT SESSION UNLOCK
    public function pdfComplete(Request $request)
    {
        $progress = SessionProgress::where('user_id', $request->user_id)
            ->where('session_id', $request->session_id)
            ->firstOrFail();

        $progress->pdf_completed = true;
        $progress->save();

        // 🔓 Unlock next session
        $nextSession = Session::where('section_id', $request->section_id)
            ->where('id', '>', $request->session_id)
            ->orderBy('id')
            ->first();

        if ($nextSession) {
            SessionProgress::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'session_id' => $nextSession->id,
                ],
                [
                    'unlocked' => true
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'PDF completed. Next session unlocked.'
        ]);
    }




    public function updateProgress(Request $req)
    {
        try {
            $req->validate([
                'student_id' => 'required',
                'course_id'  => 'required',
                'session_id' => 'required',
            ]);

            // Validate foreign keys
            if (!DB::table('students')->where('student_id', $req->student_id)->exists()) {
                return response()->json(["status" => false, "error" => "Invalid student_id"], 400);
            }

            if (!DB::table('course')->where('course_id', $req->course_id)->exists()) {
                return response()->json(["status" => false, "error" => "Invalid course_id"], 400);
            }

            if (!DB::table('session')->where('id', $req->session_id)->exists()) {
                return response()->json(["status" => false, "error" => "Invalid session_id"], 400);
            }

            Progress::updateOrCreate(
                [
                    'student_id' => $req->student_id,
                    'course_id' => $req->course_id,
                    'session_id' => $req->session_id,
                ],
                ['completed' => true]
            );

            return response()->json([
                'status' => true,
                'message' => 'Progress updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "error" => $e->getMessage()], 500);
        }
    }

    // NEW API → Course Progress Calculation
    public function courseProgress($student_id, $course_id)
    {
        try {

            // 1. total sessions for this course
            $total = DB::table('session')
                ->join('section', 'section.section_id', '=', 'session.section_id')
                ->join('subject', 'subject.subject_id', '=', 'section.subject_id')
                ->where('subject.course_id', $course_id)
                ->count();

            // 2. completed sessions
            $completed = Progress::where('student_id', $student_id)
                ->where('course_id', $course_id)
                ->where('completed', true)
                ->count();

            $percent = $total == 0 ? 0 : round(($completed / $total) * 100);

            return response()->json([
                'status' => true,
                'total_sessions' => $total,
                'completed_sessions' => $completed,
                'progress_percent' => $percent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => $e->getMessage()
            ], 500);
        }
    }

  public function taskComplete(Request $request)
{
    $request->validate([
        'user_id' => 'required',
        'session_id' => 'required',
        'section_id' => 'required',
    ]);

    SessionProgress::updateOrCreate(
        [
            'user_id' => $request->user_id,
            'session_id' => $request->session_id,
        ],
        [
            'task_completed' => true,
            'unlocked' => true,
        ]
    );

    // 🔓 Unlock EXAM
    $examSession = Session::where('section_id', $request->section_id)
        ->where('type', 'exam')
        ->first();

    if ($examSession) {
        SessionProgress::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'session_id' => $examSession->id,
            ],
            [
                'unlocked' => true,
            ]
        );
    }

    return response()->json([
        'status' => true,
        'message' => 'Task completed, Exam unlocked',
    ]);
}

}
