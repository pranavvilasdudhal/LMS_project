<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Session;
use Illuminate\Http\Request;

class EnrollmentApiController extends Controller
{
    /**
     * 🔥 Student → Enrolled Courses → Subjects → Sections → Sessions (FULL TREE)
     */
    public function studentEnrolments($student_id)
    {
        // Get enrolments
        $enrolments = Enrollment::where('student_id', $student_id)
            ->with([
                'course' => function($q) {
                    $q->with([
                        'subject' => function($sub){
                            $sub->with([
                                'sections' => function($sec){
                                    $sec->with(['sessions']);
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Enrolments fetched successfully',
            'data' => $enrolments
        ], 200);
    }
}
