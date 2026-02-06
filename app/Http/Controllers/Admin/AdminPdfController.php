<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\UploadedPdf;
use App\Models\User;
use App\Models\Session;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Models\Session as CourseSession;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Progress;
use App\Models\SessionProgress;
use App\Models\Student;
use Illuminate\Http\Request;


class AdminPdfController extends Controller
{
    // 🔹 Pending PDFs List
    public function index()
    {
        $pdfs = UploadedPdf::with(['user', 'session'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('uploaded_pdfs.pending_pdfs', compact('pdfs'));
    }



    // 🔹 PDF Review Page
    public function show($id)
    {
        $pdf = UploadedPdf::with([
            'user',
            'course',
            'subject',
            'section',
            'session'
        ])->findOrFail($id);

        return view('uploaded_pdfs.pdf_review', compact('pdf'));
    }


    // public function approve($id)
    // {
    //     $pdf = UploadedPdf::findOrFail($id);
    //     $pdf->approved = 1;
    //     $pdf->save();

    //     Session::where('id', $pdf->session_id)->update(['unlocked' => 1]);

    //     $next = Session::where('section_id', $pdf->section_id)->where('id', '>', $pdf->session_id)->orderBy('id', 'asc')->first();


    //     if ($next) {
    //         Session::where('id', $next->id)->update(['unlocked' => 1]);
    //     }

    //     return back()->with('success', 'PDF approved & session unlocked');
    // }

    // 22222222222222222222222222222222222222222222

    public function approve($id)
    {
        $pdf = UploadedPdf::findOrFail($id);
        $pdf->approved = 1;
        $pdf->save();

        // ✅ ADD THIS
        $student = Student::first(); // temp
        Progress::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'session_id' => $pdf->session_id,
                'course_id'  => $pdf->course_id ?? 1,
            ],
            [
                'pdf_completed' => 1
            ]
        );

        Session::where('id', $pdf->session_id)->update(['unlocked' => 1]);

        $next = Session::where('section_id', $pdf->section_id)
            ->where('id', '>', $pdf->session_id)
            ->orderBy('id', 'asc')
            ->first();

        if ($next) {
            Session::where('id', $next->id)->update(['unlocked' => 1]);
        }

        return back()->with('success', 'PDF approved & progress updated');
    }

    // 22222222222222222222222222222222222222222222 



//     public function approveApi($id)
// {
//     $pdf = UploadedPdf::find($id);

//     if (!$pdf) {
//         return response()->json([
//             'status' => false,
//             'message' => 'PDF not found'
//         ], 404);
//     }

//     $pdf->approved = 1;
//     $pdf->save();

//     $student = Student::first(); // TEMP

//     Progress::updateOrCreate(
//         [
//             'student_id' => $student->student_id,
//             'session_id' => $pdf->session_id,
//             'course_id'  => $pdf->course_id ?? 1,
//         ],
//         [
//             'pdf_completed' => 1
//         ]
//     );

//     Session::where('id', $pdf->session_id)->update(['unlocked' => 1]);

//     $next = Session::where('section_id', $pdf->section_id)
//         ->where('id', '>', $pdf->session_id)
//         ->orderBy('id')
//         ->first();

//     if ($next) {
//         Session::where('id', $next->id)->update(['unlocked' => 1]);
//     }

//     return response()->json([
//         'status' => true,
//         'message' => 'PDF approved & session unlocked'
//     ]);
// }

public function reject(Request $request, $id)
{
    $request->validate([    
        'reject_reason' => 'required|string|min:5',
    ]);

    $pdf = UploadedPdf::findOrFail($id);

    $pdf->approved = 0;
    $pdf->rejected = 1;
    $pdf->reject_reason = $request->reject_reason;
    $pdf->save();

    return back()->with('success', 'PDF rejected successfully');
}



    public function index1(Request $request)
    {
        //  logged in user (route has auth middleware)
        $userId = Auth::id();

        // COUNTS
        $totalUsers = User::count();   //  TOTAL USERS

        $approvedPdfCount = UploadedPdf::where('user_id', $userId)
            ->where('approved', true)
            ->count();

        $cartCount = CartItem::where('user_id', $userId)->count();

        $enrolmentCount = Enrollment::where('student_id', $userId)->count();

        return view('dashboard.index', compact(
            'totalUsers',
            'approvedPdfCount',
            'cartCount',
            'enrolmentCount'
        ));
    }
}
