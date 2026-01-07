<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;

class ApiCourseController extends Controller
{
    //  All Courses List
    public function getAllCourses()
    {
        $courses = Course::with('subject')->get();

        return response()->json([
            "status" => true,
            "courses" => $courses
        ], 200);
    }

    // Single Course Details
    public function getCourseDetails($id)
    {
        $course = Course::with('subject')->find($id);

        if (!$course) {
            return response()->json(["status" => false, "message" => "Course Not Found"], 404);
        }

        return response()->json([
            "status" => true,
            "course" => $course
        ], 200);
    }
}
