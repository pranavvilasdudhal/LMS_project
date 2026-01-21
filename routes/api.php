<?php

use App\Http\Controllers\Admin\UploadedPdfAdminController;
use App\Http\Controllers\Api\AddToCartController;
use App\Http\Controllers\Api\AdminPdfController;
use App\Http\Controllers\Api\ApiCourseController;
use App\Http\Controllers\Api\ApiSectionController;
use App\Http\Controllers\Api\ApiSessionController;
use App\Http\Controllers\Api\ApiSubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\CourseFullDataController;
use App\Http\Controllers\Api\EnrollmentApiController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Api\UploadedPdfController;


Route::post('/register', [AuthController::class, 'apiRegister']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::get('/courses', [ApiCourseController::class, 'getAllCourses']);
Route::get('/courses/{id}', [ApiCourseController::class, 'getCourseDetails']);
Route::get('/subjects/{course_id}', [ApiSubjectController::class, 'getSubjects']);
Route::get('/sections/{subject_id}', [ApiSectionController::class, 'apiSections']);
Route::get('/sessions/{section_id}', [ApiSessionController::class, 'apiSessions']);
Route::get('/sessions/{section_id}/{user_id}',[ApiSessionController::class, 'getBySection']);
  


Route::post('/add-to-cart', [AddToCartController::class, 'addToCart']);
Route::get('/cart-list', [AddToCartController::class, 'cartList']);
Route::delete('/cart-remove/{id}', [AddToCartController::class, 'removeCartItem']);


Route::get('/my-enrolments/{id}', [EnrollmentApiController::class, 'myEnrolments']);
Route::post('/cart-confirm', [AddToCartController::class, 'confirmCart']);




// Course full data
Route::get('/course-full-data/{course_id}', [CourseFullDataController::class, 'getFullData']);

// Progress update
Route::post('/update-progress', [ProgressController::class, 'updateProgress']);



// Certificate APIs
Route::post('/generate-certificate', [CertificateController::class, 'generateCertificate']);
Route::get('/certificates/{student_id}', [CertificateController::class, 'list']);




// ================= STUDENT =================

// Course progress percentage API
Route::get('/course-progress/{student_id}/{course_id}', [ProgressController::class, 'courseProgress']);

Route::post('/session/task-complete', [ProgressController::class, 'taskComplete']);

    
Route::post('/session/video-complete', [ProgressController::class, 'videoComplete']);
Route::post('/session/pdf-complete', [ProgressController::class, 'pdfComplete']);

Route::post('/upload-pdf', [UploadedPdfController::class, 'upload']);
Route::get('/uploaded-pdf/{session_id}', [UploadedPdfController::class, 'getBySession']);


// ================= ADMIN =================
Route::prefix('admin')->group(function () {
    Route::get('/pending-pdfs', [AdminPdfController::class, 'index']);
    Route::get('/pdf/{id}', [AdminPdfController::class, 'show']);
    Route::post('/approve-pdf/{id}', [AdminPdfController::class, 'approve']);
});


Route::get('/getBySection/{section_id}/{user_id}', [ApiSessionController::class, 'getBySection']);
//progres
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/student/courses', [ProgressController::class,'getStudentCourses']);
    Route::post('/student/complete-session', [ProgressController::class,'completeSession']);
    Route::get('/student/subject-progress/{subject_id}', [ProgressController::class,'getSubjectProgress']);

});



Route::middleware('auth:sanctum')->group(function () {

    // STUDENT  API---------------------------------------------------------------------
    Route::get('/student/courses', [ProgressController::class, 'getStudentCourses']);
    Route::get('/student/subject-progress/{id}', [ProgressController::class, 'getSubjectProgress']);
    Route::get('/student/session-progress/{id}', [ProgressController::class, 'getSessionProgress']);

    // ADMIN  API-----------------------------------------------------------------------
    Route::get('/admin/students-progress', [ProgressController::class, 'adminStudentsProgress']);
    Route::get('/admin/student/{student}/course/{course}', [ProgressController::class, 'adminCourseDetail']);
});



