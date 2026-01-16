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

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\SessionProgress;
use Symfony\Component\HttpFoundation\Request;

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





public function approve($id)
{
    $pdf = UploadedPdf::findOrFail($id);

    //  Approve PDF
    $pdf->approved = 1;
    $pdf->save();

    //  Unlock CURRENT session (session table)
    Session::where('id', $pdf->session_id)
        ->update(['unlocked' => 1]);

    //  Find NEXT session in same section
    $next = Session::where('section_id', $pdf->section_id)
        ->where('id', '>', $pdf->session_id)
        ->orderBy('id', 'asc')
        ->first();

    // 4 Unlock NEXT session (session table)
    if ($next) {
        Session::where('id', $next->id)
            ->update(['unlocked' => 1]);
    }

    return back()->with('success', 'PDF approved & session unlocked');
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
