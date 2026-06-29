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
});


Route::get('/Auth/Teacher_Register', 
[AuthController::class,'TeacherRegister'])
->name('Auth.Teacher_Register');

// Add this new route to handle form submission
Route::post('/Auth/Teacher_Register', 
[AuthController::class, 'StoreTeacherRegister'])
->name('Auth.Teacher_Register.store');

Route::get('/Auth/Student_Register', 
[AuthController::class,'StudentRegister'])
->name('Auth.Student_Register');

// Add this new route to handle student registration submission
Route::post('/Auth/Student_Register', 
[AuthController::class, 'StoreStudentRegister'])
->name('Auth.Student_Register.store');

Route::get('/Auth/Login', 
[AuthController::class, 'Login'])->name('Auth.Login');

// Add this new route to handle login submission
Route::post('/Auth/Login', [AuthController::class, 'StoreLogin'])
    ->name('Auth.Login.submit');

Route::post('/logout', 
[AuthController::class, 'Logout'])->name('Auth.Logout');



Route::get('/Profile/Tutor_Profile_make', 
[ProfileController::class, 'TutorProfile'])
->name('Profile.Tutor_Profile_make');

Route::post('/Profile/Tutor_Profile_make', 
[ProfileController::class, 'StoreTutorProfile'])
->name('Profile.Tutor_Profile_make.store');

Route::get('/Teacher/Teacher_Dashboard', [TeacherController::class, 'Dashboard'])
    ->name('Teacher.Teacher_Dashboard')
    ->middleware('auth');

Route::get('/Search/Tutor_View', 
[SearchController::class ,'SearchTutorView'])->name('Search.Tutor_View');



Route::get('/Notification/Notification', 
[NotificationController::class,'Notification'])
->name('Notification.Notification');

// Route for Student Dashboard
Route::get('/Student/Student_Dashboard', [StudentController::class, 'Dashboard'])
->name('Student.Student_Dashboard')
->middleware('auth');


Route::get('/Teacher/Teacher_Profile/{id}', 
[TeacherController::class, 'TeacherProfile'])
->name('Teacher.Teacher_Profile');


// Route to load the edit form
Route::get('/Teacher/Teacher_Profile_Edit', [TeacherController::class, 'TeacherProfileEdit'])
->name('Teacher.Teacher_Profile_Edit')
->middleware('auth');

// Route to process and save the edits
Route::post('/Teacher/Teacher_Profile_Edit', [TeacherController::class, 'UpdateTeacherProfile'])
->name('Teacher.Teacher_Profile.update')
->middleware('auth');

// Route to show the booking form for a specific tutor
Route::get('/Book/{tutor_id}', [BookingController::class, 'ShowBookingForm'])
    ->name('Booking.show')
    ->middleware('auth');

// Route to process the booking submission
Route::post('/Book', [BookingController::class, 'StoreBooking'])
    ->name('Booking.store')
    ->middleware('auth');

    
// 10. Booking Action Submissions (Accept & Decline)
Route::post('/Booking/{id}/Accept', [BookingController::class, 'AcceptBooking'])
    ->name('Booking.accept')
    ->middleware('auth');

Route::post('/Booking/{id}/Decline', [BookingController::class, 'DeclineBooking'])
    ->name('Booking.decline')
    ->middleware('auth');

// 11. Messages (Optional parameter to load a specific conversation)
Route::get('/Messages/Message/{conversation_id?}', [MessageController::class, 'Message'])
    ->name('Messages.Message')
    ->middleware('auth');

Route::post('/Messages/Message/Send', [MessageController::class, 'SendMessage'])
    ->name('Messages.Send')
    ->middleware('auth');

// Background API to check unread notification counts
Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])
    ->name('api.notifications.unread')
    ->middleware('auth');

// Background API to check message counts in a thread
Route::get('/api/conversations/{id}/message-count', [MessageController::class, 'getMessageCount'])
    ->name('api.messages.count')
    ->middleware('auth');