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
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;





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

    // Legal Routes
Route::get('/terms-of-service', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy-policy', function () {
    return view('legal.privacy');
})->name('privacy');

    // Email Verification
    Route::get('/verify-email', [AuthController::class, 'showVerifyForm'])
    ->name('verify.email.form');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])
    ->name('verify.email');

    Route::post('/forgot-password/send-code',
     [AuthController::class, 'sendResetCode'])->name('password.send_code');

    Route::post('/forgot-password/verify-code',
     [AuthController::class, 'verifyResetCode'])->name('password.verify_code');
     
    Route::post('/forgot-password/reset',
     [AuthController::class, 'resetPassword'])->name('password.update_reset');

    Route::post('/verify-email/resend', 
    [AuthController::class, 'resendVerificationCode'])
    ->name('verify.email.resend');

    // Login Form
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});




Route::middleware('auth')->group(function () {
     Route::get('/profile/setup', 
     [ProfileController::class, 'showTutorProfileSetup'])
     ->name('tutor.profile.edit');

    Route::post('/profile/setup', 
    [ProfileController::class, 'storeTutorProfileSetup'])
    ->name('tutor.profile.store');

    
    Route::get('/teacher/dashboard', 
    [ProfileController::class, 'showTeacherDashboard'])
    ->name('tutor.dashboard');

    Route::get('/student/dashboard', 
    [StudentController::class, 'showStudentDashboard'])
    ->name('student.dashboard');

    

    Route::get('/tutors', 
    [SearchController::class, 'browseTutors'])
    ->name('tutors.browse');

    Route::get('/tutors/{username}', 
    [SearchController::class, 'showTutorProfile'])
    ->name('tutors.profile');



    Route::post('/tutors/{username}/book', 
    [BookingController::class, 'storeBooking'])->name('tutors.book.store');

    Route::post('/bookings/{id}/accept', 
    [BookingController::class, 'acceptBooking'])->name('bookings.accept');

    Route::post('/bookings/{id}/reject', 
    [BookingController::class, 'rejectBooking'])->name('bookings.reject');



    Route::get('/notifications', 
    [NotificationController::class, 'index'])->name('notifications.index');

    Route::post('/notifications/read-all', 
    [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.read.all');

    Route::get('/api/notifications/unread-count',
     [NotificationController::class, 'getUnreadCount'])
     ->name('notifications.unread.count');

    Route::post('/notifications/{id}/read', 
    [NotificationController::class, 'markAsRead'])->name('notifications.read');



    Route::get('/messages', 
    [MessageController::class, 'index'])->name('messages.index');

    Route::get('/messages/{username}', 
    [MessageController::class, 'show'])->name('messages.show');

    Route::post('/conversations/{id}/send', 
    [MessageController::class, 'sendMessage'])->name('messages.store');

    Route::get('/api/conversations/{id}/updates', 
    [MessageController::class, 'getNewMessages'])->name('messages.updates');

 Route::post('/settings/send-code',
  [SettingController::class, 'sendCode'])
  ->name('settings.send_code');

    Route::post('/settings/verify-code', 
    [SettingController::class, 'verifyCode'])
    ->name('settings.verify_code');

    Route::post('/settings/update-username',
     [SettingController::class, 'updateUsername'])
     ->name('settings.update_username');

    Route::post('/settings/update-password',
    [SettingController::class, 'updatePassword'])
    ->name('settings.update_password');
 Route::post('/bookings/{id}/review', 
 [SearchController::class, 'storeReview'])->name('bookings.review.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});




Route::middleware(['auth', 'admin'])->group(function () {
    
    
    Route::get('/admin/dashboard', 
    [AdminController::class, 'showDashboard'])->name('admin.dashboard');
    
    
    Route::post('/admin/register-admin', 
    [AdminController::class, 'storeAdmin'])->name('admin.store');
    
});



Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    if (!$conversation) return false;
    return $user->id === $conversation->student_id || $user->id === $conversation->tutor_id;
});