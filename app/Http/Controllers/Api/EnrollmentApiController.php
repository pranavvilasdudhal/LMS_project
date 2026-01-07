<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Session;
use Illuminate\Http\Request;

class EnrollmentApiController extends Controller
{



    public function myEnrolments($user_id)
    {
        $enrolments = Enrollment::with('course')
            ->where('student_id', $user_id)
            ->get();

        return response()->json([
            "status" => true,
            "enrolments" => $enrolments
        ]);
    }

    public function confirmCart(Request $request)
    {
        $request->validate([
            "user_id" => "required|exists:users,id"
        ]);

        $cartItems = CartItem::with('course')
            ->where('user_id', $request->user_id)
            ->get();

        if ($cartItems->count() == 0) {
            return response()->json([
                "status" => false,
                "message" => "Cart is empty"
            ]);
        }

        foreach ($cartItems as $item) {
            Enrollment::create([
                "student_id" => $request->user_id,
                "course_id" => $item->course_id,
                "mrp" => $item->course->mrp,
                "sell_price" => $item->course->sell_price,
                "status" => "pending",
            ]);
        }

        // Cart empty
        CartItem::where("user_id", $request->user_id)->delete();

        return response()->json([
            "status" => true,
            "message" => "Courses moved to Enrolments"
        ]);
    }


    public function studentEnrolments($student_id)
    {

        $enrolments = Enrollment::where('student_id', $student_id)
            ->with([
                'course' => function ($q) {
                    $q->with([
                        'subject' => function ($sub) {
                            $sub->with([
                                'sections' => function ($sec) {
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
