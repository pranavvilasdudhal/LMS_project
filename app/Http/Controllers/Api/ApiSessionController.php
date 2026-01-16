<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Section;
use App\Models\SessionProgress;
use Illuminate\Http\Request;


class ApiSessionController extends Controller
{
    // All Sessions of a Section
    public function getSessions($section_id)
    {
        $section = Section::with('sessions')->find($section_id);

        if (!$section) {
            return response()->json(["status" => false, "message" => "Section Not Found"], 404);
        }

        return response()->json([
            "status" => true,
            "sessions" => $section->sessions
        ]);
    }

    // Single Session (Video / PDF / Task / Exam)
    public function getSingleSession($session_id)
    {
        $session = Session::find($session_id);

        if (!$session) {
            return response()->json(["status" => false, "message" => "Session Not Found"], 404);
        }

        $session->pdf = $session->pdf ? asset('storage/' . $session->pdf) : null;
        $session->video = $session->video ? $session->video : null;

        return response()->json([
            "status" => true,
            "session" => $session
        ]);
    }

    public function apiSessions($section_id)
    {
        $sessions = Session::where('section_id', $section_id)->get();

        $formatted = $sessions->map(function ($s) {
            return [
                "id" => $s->id,
                "title" => $s->title,
                "type" => $s->type,   // video/pdf/note/task/quiz
                "url"  => $s->video ?? ($s->pdf ? asset('storage/' . $s->pdf) : null),
            ];
        });

        return response()->json([
            'status' => true,
            'sessions' => $formatted
        ]);
    }

    // 🔹 Section → Sessions (SAFE API)
public function getBySection($section_id, $user_id)
{
    $sessions = Session::where('section_id', $section_id)
        ->orderBy('id', 'asc')
        ->get();

    $final = [];

    foreach ($sessions as $index => $s) {

        // 🔑 RULE: THIS SECTION FIRST SESSION ALWAYS UNLOCK
        if ($index === 0) {

           
            $unlocked = true;

          
            Session::where('id', $s->id)->update([
                'unlocked' => 1
            ]);

        } else {

            $unlocked = ($s->unlocked == 1);
        }

        $final[] = [
            'id' => $s->id,
            'titel' => $s->titel,
            'type' => $s->type,
            'video' => $s->video,
            'pdf' => $s->pdf,
            'task' => $s->task,
            'exam' => $s->exam,

    
            'unlocked' => $unlocked,
        ];
    }

    return response()->json([
        'status' => true,
        'data' => $final,
    ]);
}




    // 🔹 Single Session Detail
    public function detail($session_id, $user_id)
    {
        $session = Session::find($session_id);

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Session Not Found'
            ], 404);
        }

        $progress = SessionProgress::firstOrCreate(
            [
                'user_id' => $user_id,
                'session_id' => $session_id
            ],
            [
                'unlocked' => false
            ]
        );

        if (!$progress->unlocked) {
            return response()->json([
                'status' => false,
                'message' => 'Session Locked'
            ], 403);
        }

        $session->progress = $progress;

        return response()->json([
            'status' => true,
            'data' => $session
        ]);
    }

    // 🔹 Video Completed
    public function videoComplete(Request $request)
    {
        $progress = SessionProgress::where('user_id', $request->user_id)
            ->where('session_id', $request->session_id)
            ->firstOrFail();

        $progress->video_completed = true;
        $progress->save();

        return response()->json([
            'status' => true,
            'message' => 'Video Completed'
        ]);
    }

    // 🔹 PDF Completed + Unlock Next Session
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
                    'session_id' => $nextSession->id
                ],
                [
                    'unlocked' => true
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'PDF Completed & Next Session Unlocked'
        ]);
    }
}
