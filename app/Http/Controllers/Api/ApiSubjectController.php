<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;

class ApiSubjectController extends Controller
{
    public function getSubjects($course_id)
    {
        $course = Course::with('subject')->find($course_id);

        if (!$course) {
            return response()->json(["status" => false, "message" => "Course Not Found"], 404);
        }

        return response()->json([
            "status" => true,
            "subjects" => $course->subject
        ]);
    }
}
