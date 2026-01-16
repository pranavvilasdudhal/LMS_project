<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   
   

public function apiRegister(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email|unique:students,student_email',
        'password' => 'required|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    DB::beginTransaction();

    try {
        // 👉 USERS table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        // 👉 STUDENTS table (as per YOUR migration)
        $student = Student::create([
            'student_name' => $request->name,
            'student_email' => $request->email,
            'student_phone' => $request->phone ?? '',
            'student_address' => $request->address ?? '',
            'student_password' => Hash::make($request->password),
        ]);

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Student registered successfully',
            'user' => $user,
            'student' => $student,
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}


    // 🔹 LOGIN API
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        if ($user->role !== 'student') {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only students can login.'
            ], 403);
        }

        $token = $user->createToken('StudentAppToken')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    // 🔹 LOGOUT API
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
