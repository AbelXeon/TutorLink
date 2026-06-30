<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;



Route::get('/', function () {
    return view('Landing');
})->name('Landing');


Route::middleware('guest')->group(function () {
    // Teacher Registration
    Route::get('/Auth/teacher_Register', 
    [AuthController::class, 'showTeacherRegisterForm'])
    ->name('Auth.Teacher_Register');

    Route::post('/register/teacher', 
    [AuthController::class, 'registerTeacher'])->name('register.teacher');

    
      // Student Registration
    Route::get('/Auth/student_Register', 
    [AuthController::class, 'showStudentRegisterForm'])
    ->name('Auth.Student_Register'); 

    Route::post('/register/student', 
    [AuthController::class, 'registerStudent'])
    ->name('register.student'); 


    // Email Verification
    Route::get('/verify-email', [AuthController::class, 'showVerifyForm'])
    ->name('verify.email.form');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])
    ->name('verify.email');

    // Login Form
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});



// 3. Authenticated-Only Routes (Must be logged in)
Route::middleware('auth')->group(function () {
    // Tutor profile setup
    Route::get('/profile/setup', 
    [ProfileController::class, 'showTutorProfileSetup'])
    ->name('tutor.profile.edit');
    Route::post('/profile/setup', 
    [ProfileController::class, 'storeTutorProfileSetup'])
    ->name('tutor.profile.store');

    // Teacher Dashboard
    Route::get('/teacher/dashboard', 
    [ProfileController::class, 'showTeacherDashboard'])
    ->name('tutor.dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout'); 
});


