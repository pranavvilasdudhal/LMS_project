<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\UploadedPdf;
use App\Models\SessionProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UploadedPdfController extends Controller
{
    // ===============================
    // 1️⃣ UPLOAD PDF (STUDENT)
    // ===============================
    public function upload(Request $request)
    {
      
        // return response()->json([
        //     'all' => $request->all(),
        //     'has_pdf' => $request->hasFile('pdf')
        // ]);

        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|integer',
            'course_id'  => 'required|integer',
            'subject_id' => 'required|integer',
            'section_id' => 'required|integer',
            'session_id' => 'required|integer',
            'pdf'        => 'required|file|mimes:pdf|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!$request->hasFile('pdf')) {
            return response()->json([
                'status' => false,
                'message' => 'PDF file missing in request',
            ], 422);
        }

        $filePath = $request->file('pdf')->store('uploaded_pdfs', 'public');

        $uploaded = UploadedPdf::create([
            'user_id'    => $request->user_id,
            'course_id'  => $request->course_id,
            'subject_id' => $request->subject_id,
            'section_id' => $request->section_id,
            'session_id' => $request->session_id,
            'pdf'        => $filePath,
            'approved'   => false,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'PDF Uploaded Successfully',
            'data'    => $uploaded,
        ], 200);
    }



    // ===============================
    // 2️⃣ GET PDFs BY SESSION
    // ===============================
    public function getBySession($session_id)
    {
        try {
            $pdfs = UploadedPdf::where('session_id', $session_id)->get();

            return response()->json([
                'status' => true,
                'count'  => $pdfs->count(),
                'data'   => $pdfs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error Fetching PDFs',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ===============================
    // 3️⃣ ADMIN: PENDING PDFs
    // ===============================
    public function pending()
    {
        try {
            $pdfs = UploadedPdf::where('approved', false)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'count'  => $pdfs->count(),
                'data'   => $pdfs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to load pending PDFs',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

   



}
