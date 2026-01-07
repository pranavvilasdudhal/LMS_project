<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UploadedPdf;
use App\Models\SessionProgress;
use Illuminate\Http\Request;

class AdminPdfController extends Controller
{
    // 🔹 Pending PDFs list
    public function pendingPdfs()
    {
        try {
            $pdfs = UploadedPdf::with(['student', 'session'])
                ->orderBy('created_at', 'desc')
                ->get()

                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $pdfs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 🔹 Approve PDF
    public function approvePdf($id)
    {
        try {
            $pdf = UploadedPdf::findOrFail($id);

            $pdf->approved = true;
            $pdf->save();

            // ✅ Mark PDF completed
            SessionProgress::updateOrCreate(
                [
                    'user_id' => $pdf->user_id,
                    'session_id' => $pdf->session_id
                ],
                [
                    'pdf_completed' => true,
                    'unlocked' => true
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'PDF Approved Successfully',
                'data' => $pdf
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Approval Failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
