<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseFullDataController extends Controller
{
    public function getFullData($course_id)
    {
        $course = Course::with([
            'subject.sections.sessions'
        ])->find($course_id);

        if (!$course) {
            return response()->json([
                "status" => false,
                "message" => "Course not found"
            ], 404);
        }

        return response()->json([
            "status" => true,
            "course" => $course
        ]);
    }
}
