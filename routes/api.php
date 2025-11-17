<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\EnrollmentApiController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::get('/courses', [CourseApiController::class, 'index']);
Route::get('/courses/{id}', [CourseApiController::class, 'show']);
Route::post('/courses', [CourseApiController::class, 'store']);
Route::post('/courses/{id}', [CourseApiController::class, 'update']);
Route::delete('/courses/{id}', [CourseApiController::class, 'destroy']);  


Route::get('/student/{id}/enrolments', [EnrollmentApiController::class, 'getStudentEnrolments']);
Route::post('/enrol', [EnrollmentApiController::class, 'store']);