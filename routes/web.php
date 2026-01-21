<?php

use App\Http\Controllers\Admin\AdminPdfController;
use App\Http\Controllers\Admin\StudentProgressController;
use App\Http\Controllers\Admin\UploadedPdfAdminController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\sectionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\subjectController;
use App\Http\Controllers\UserController;
use Illuminate\Contracts\Session\Session;
use App\Http\Controllers\EnrolmentController;
use Illuminate\Support\Facades\Auth;

Route::get('/dashboard', [AdminPdfController::class, 'index1'])
    ->name('dashboard');
Route::get('/', function () {
    return view('layouts.app');
});
Route::get('/login', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/studentdashabord', function () {
    return view('layout.student.master');
});
Route::patch('/students/{id}/toggle', [StudentController::class, 'toggleStatus'])->name('student.toggle');
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home2', [HomeController::class, 'index2'])->name('home');


Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::get('/profile/view', [UserController::class, 'view'])->name('profile.view');
Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');

// USERS MANAGEMENT
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::patch('/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


//StudentController routes-----------------------------------------------------------

Route::get('students', [StudentController::class, 'index'])->name('students.index');

Route::get('students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('students', [StudentController::class, 'store'])->name('students.store');

Route::get('/student/edit/{id}', [StudentController::class, 'edit'])->name('studentedit');
Route::post('/student/update/{id}', [StudentController::class, 'update'])->name('studentupdate');

Route::delete('students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

Route::patch('/students/toggle/{id}', [StudentController::class, 'toggleStatus'])->name('student.toggle');

Route::get('/student/progress/{student}/{course}', [StudentController::class, 'courseProgress']);

Route::get('/certificates', [StudentController::class, 'certificatePage'])->name('certificates.page');



//subjectController routes------------------------------------------------------------

Route::get('/subject', [SubjectController::class, 'index'])->name('subject.index');

Route::get('/subject/create', [SubjectController::class, 'create'])->name('subject.create');

Route::post('/subject/store', [SubjectController::class, 'store'])->name('subject.store');

Route::get('/subject/edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');

Route::post('/subject/update/{id}', [SubjectController::class, 'update'])->name('subject.update');
Route::delete('/subject/delete/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');



//sectioncontroller routes------------------------------------------------------

// Subject
Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');

// Sections
Route::get('/subjects/{id}/sections', [SectionController::class, 'index'])->name('sections.index');

Route::get('/subjects/{id}/sections/create', [SectionController::class, 'create'])->name('sections.create');

Route::post('/subjects/{id}/sections', [SectionController::class, 'store'])->name('sections.store');

Route::get('/sections/edit/{id}', [SectionController::class, 'edit'])->name('sections.edit');
Route::put('/sections/update/{id}', [SectionController::class, 'update'])->name('sections.update');
Route::delete('/sections/delete/{id}', [SectionController::class, 'destroy'])->name('sections.delete');




// sessionController-------------------------------------------------------


// Sessions routes
// Sessions routes
// Route::get('/sections/{section_id}/sessions', [SessionController::class, 'index'])->name('sessions.index');

// Route::get('/sections/{section_id}/sessions/create', [SessionController::class, 'create'])->name('sessions.create');

// Route::post('/sections/{section_id}/sessions', [SessionController::class, 'store'])->name('sessions.store');

// Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');


// Route::get('/sections/{section_id}/sessions/{id}/edit', [SessionController::class, 'edit'])->name('sessions.edit');

// Route::put('sections/{section}/sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');


// Route::put('/sessions/{id}', [SessionController::class, 'update'])->name('sessions.update');


// Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');


// Sessions
Route::get('/sections/{section_id}/sessions', [SessionController::class, 'index'])
    ->name('sessions.index');

Route::get('/sections/{section_id}/sessions/create', [SessionController::class, 'create'])
    ->name('sessions.create');

Route::post('/sections/{section_id}/sessions', [SessionController::class, 'store'])
    ->name('sessions.store');

Route::get('/sections/{section_id}/sessions/{id}/edit', [SessionController::class, 'edit'])
    ->name('sessions.edit');

Route::put('/sections/{section_id}/sessions/{id}', [SessionController::class, 'update'])
    ->name('sessions.update');

Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])
    ->name('sessions.destroy');




//CourseController-----------------------------------------------------------


Route::get('/courselist', [CourseController::class, 'index'])->name('courselist');

Route::get('/courseadd', [CourseController::class, 'create'])->name('courseadd');

Route::post('/coursestore', [CourseController::class, 'store'])->name('coursestore');

Route::get('/courseedit/{id}', [CourseController::class, 'edit'])->name('courseedit');

Route::post('/courseupdate/{id}', [CourseController::class, 'update'])->name('courseupdate');

Route::delete('/coursedelete/{id}', [CourseController::class, 'destroy'])->name('coursedelete');



//EnrollmentController------------------------------------------------------------------------
// List page
Route::get('/enrolments', [EnrolmentController::class, 'index'])->name('enrolments.index');

Route::get('/enrolments/create', [EnrolmentController::class, 'create'])->name('enrolments.create');

Route::post('/enrolments', [EnrolmentController::class, 'store'])->name('enrolments.store');

Route::get('/enrolments/{id}/edit', [EnrolmentController::class, 'edit'])->name('enrolments.edit');

Route::put('/enrolments/{id}', [EnrolmentController::class, 'update'])->name('enrolments.update');

Route::delete('/enrolments/{id}', [EnrolmentController::class, 'destroy'])->name('enrolments.destroy');




// Route::prefix('admin')->group(function () {

//     Route::get('/uploaded-pdfs', [UploadedPdfAdminController::class, 'index'])
//         ->name('admin.uploaded_pdfs');

//     Route::post('/uploaded-pdfs/approve/{id}', [UploadedPdfAdminController::class, 'approveWeb'])
//         ->name('admin.uploaded_pdfs.approve');

//     Route::post('/uploaded-pdfs/reject/{id}', [UploadedPdfAdminController::class, 'rejectWeb'])
//         ->name('admin.uploaded_pdfs.reject');
// });


Route::prefix('admin')->group(function () {

    Route::get('/pending-pdfs', [AdminPdfController::class, 'index'])
        ->name('admin.pending.pdfs');

    Route::get('/pdf-review/{id}', [AdminPdfController::class, 'show'])
        ->name('admin.pdf.review');

    Route::post('/approve-pdf/{id}', [AdminPdfController::class, 'approve'])
        ->name('admin.pdf.approve');
});



/*
|--------------------------------------------------------------------------
| ADMIN PROGRESS ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get(
        '/student-progress',
        [ProgressController::class, 'index']
    )->name('student.progress.index');

    Route::get(
        '/student-progress/{student_id}',
        [ProgressController::class, 'show']
    )->name('student.progress.show');

    Route::get('/certificates', function () {
        return view('admin.certificates.index');
    })->name('admin.certificates');
});


// StudentProgressController-------------------------------------------------------------------------------

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get(
        '/student-progress',
        [ProgressController::class, 'index']
    )->name('student.progress.index');

    Route::get(
        '/student-progress/{student_id}',
        [ProgressController::class, 'show']
    )->name('student.progress.show');

    Route::get('/admin/course-progress/{student_id}/{course_id}',
    [ProgressController::class, 'courseDetail']
)->name('student.course.detail');

});

Route::get('/admin/course-progress/{student_id}/{course_id}', function($student_id, $course_id) {

    $student = \App\Models\Student::findOrFail($student_id);
    $course = \App\Models\Course::with('subject.sections.sessions')->findOrFail($course_id);

    return view('student_progres.course_detail', compact('student','course'));

})->name('admin.course.progress');
