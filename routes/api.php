<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CircleController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EnrollmentRequestController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PathController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherCourseController;
use App\Http\Controllers\SecureFileController;
use App\Http\Controllers\SheduleController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\StudentPathController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\ExamSubmissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WajibController;





Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/accept-invite', [InviteController::class, 'acceptInvite']);


Route::middleware(['auth:sanctum'])->group(function () {


    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('/secure-files', [SecureFileController::class, 'getFile']);

    Route::apiResource('teacher-posts', PostController::class);

    Route::apiResource('sessions', ClassSessionController::class);

    Route::apiResource('questions', QuestionController::class);

    Route::apiResource("exams", ExamController::class);

    Route::apiResource('exam-submissions', ExamSubmissionController::class);

    Route::apiResource('requests', EnrollmentRequestController::class);

});


Route::middleware(['auth:sanctum', 'role:student'])->group(function () {

    Route::get('student-paths', [StudentPathController::class, 'index']);
    Route::post("student-paths/enroll/{pathId}", [EnrollmentController::class, "enroll"]);

    Route::get("/schedule-student/{id}", [SheduleController::class, "getByStudentId"]);

    Route::apiResource('student-courses', StudentCourseController::class);

    Route::get('/join-stream', [StreamController::class, 'joinStream']);

    Route::post('/student-submit', [AnswerController::class, 'student_submit']);

    Route::get("/student-current-path/{student_id}", [PathController::class, "student_current_path"]);

    Route::get("/students/profile", [StudentController::class, 'profile']);
    Route::put('/students/profile', [StudentController::class, 'updateProfile']);

    Route::get("/requests/student/{id}", [EnrollmentRequestController::class, 'getRequestsByStudentId']);




});


Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {

    Route::apiResource('teacher-courses', TeacherCourseController::class);

    Route::get("/schedule/{id}", [SheduleController::class, "getByTeacherId"]);

    Route::post('/start-stream', [StreamController::class, 'startStream']);

    Route::post('/start-live/{sessionId}', [StreamController::class, 'startLive']);
    Route::post('/end-live/{sessionId}', [StreamController::class, 'endLive']);

    Route::post('/start-liveCircle/{circleId}', [StreamController::class, 'startCircleLive']);
    Route::post('/end-liveCircle/{circleId}', [StreamController::class, 'endCircleLive']);

    Route::get('/enrollmentsByCourses/{courseId}', [EnrollmentController::class, 'getByCourseId']);

    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

    Route::get('/students-by-course/{course_id}', [UserController::class, 'studentsByCourseId']);

    Route::get('/students-by-circle/{circle_id}', [UserController::class, 'studentsByCircleId']);


    Route::put("/answers/{answerId}/correction", [AnswerController::class, 'updateCorrection']);

    Route::get('teacher-paths/{teacher_id}', [PathController::class, 'getTeacherPaths']);

    Route::get('teacher-courses-all/{teacher_id}', [CourseController::class, 'getTeacherCourses']);

    Route::get("/teachers/profile", [TeacherController::class, 'profile']);
    Route::put('/teachers/profile', [TeacherController::class, 'updateProfile']);

    Route::apiResource("wajibs",WajibController::class);

    Route::get('circles/{id}', [CircleController::class,"show"]);




});


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::apiResource('paths', PathController::class);
    Route::apiResource('courses', CourseController::class);

    Route::apiResource('teachers', TeacherController::class);

    Route::post("/schedule", [SheduleController::class, "save2"]);
    Route::get("/schedule", action: [SheduleController::class, "send"]);

    Route::post('/invite-teacher', [InviteController::class, 'inviteTeacher']);

    Route::get("/admins/profile", [AdminController::class, 'profile']);
    Route::put('/admins/profile', [AdminController::class, 'updateProfile']);

    Route::apiResource('circles', CircleController::class);

    Route::get('/all-enrollments', [EnrollmentController::class, 'getAll']);

    Route::post('/approve-request/{request_id}/{selected_circle_id?}', [EnrollmentRequestController::class, 'approveRequest']);
    Route::post('/reject-request/{request_id}', [EnrollmentRequestController::class, 'rejectRequest']);



});

