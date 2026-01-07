<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\section;
use App\Models\Subject;

class ApiSectionController extends Controller
{
    public function getSections($subject_id)
    {
        $subject = Subject::with('sections')->find($subject_id);

        if (!$subject) {
            return response()->json(["status" => false, "message" => "Subject Not Found"], 404);
        }

        return response()->json([
            "status" => true,
            "sections" => $subject->sections
        ]);
    }

    public function apiSections($subject_id)
    {
        $sections = section::where('subject_id', $subject_id)->get();

        return response()->json([
            'status' => true,
            'sections' => $sections
        ]);
    }
}
