<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function generateCertificate(Request $req)
    {
        try {
            $req->validate([
                'student_id' => 'required',
                'course_id'  => 'required',
            ]);

            // Validate student + course
            if (!DB::table('students')->where('student_id', $req->student_id)->exists()) {
                return response()->json(["status" => false, "error" => "Invalid student_id"]);
            }

            if (!DB::table('course')->where('course_id', $req->course_id)->exists()) {
                return response()->json(["status" => false, "error" => "Invalid course_id"]);
            }

            $existing = Certificate::where('student_id', $req->student_id)
                ->where('course_id', $req->course_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'status' => true,
                    'message' => 'Certificate already exists',
                    'certificate_url' => asset($existing->certificate_url)
                ]);
            }

            $certificateUrl = "certificates/" . $req->student_id . "_" . $req->course_id . ".png";

            Certificate::create([
                'student_id'      => $req->student_id,
                'course_id'       => $req->course_id,
                'certificate_url' => $certificateUrl
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Certificate generated successfully',
                'certificate_url' => asset($certificateUrl)
            ]);

        } catch (\Exception $e) {
            return response()->json(["status" => false, "error" => $e->getMessage()], 500);
        }
    }

    public function list($student_id)
    {
        $certs = Certificate::where('student_id', $student_id)->get();

        return response()->json([
            'status' => true,
            'certificates' => $certs
        ]);
    }
}
