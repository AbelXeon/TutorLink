<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter; 
use Illuminate\Support\Str;                  
use App\Models\User;
use App\Models\Role;
use App\Models\Location;
use App\Models\TutorProfile;
use App\Models\EmailVerification;
use App\Mail\SendVerificationCode;
use Exception;

class AuthController extends Controller
{
     // Show Teacher Registration Form
    public function showTeacherRegisterForm()
    {
        // Get locations for the dropdown
        $locations = Location::all();
        // Uses the dot notation matching your views folder 'resources/views/auth/Teacher_Register.blade.php'
        return view('Auth.Teacher_Register', compact('locations'));
    }

    // Handle Teacher Registration Form Submission
    public function registerTeacher(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'phone_number'  => 'required|string|max:20',
            'username'      => 'required|string|max:255|unique:users,username',
            'password'      => 'required|string|min:8|confirmed',
            'address'       => 'required|string|max:255',
            'location_id'   => 'required|exists:locations,id', 
        ]);

        try {
            $role = Role::where('role_type', 'Teacher')->firstOrFail();

            $user = User::create([
                'role_id'        => $role->id,
                'first_name'     => $request->first_name,
                'middle_name'    => $request->middle_name,
                'last_name'      => $request->last_name,
                'email'          => $request->email,
                'phone_number'   => $request->phone_number,
                'username'       => $request->username,
                'password'       => Hash::make($request->password),
                'address'        => $request->address,
                'location_id'    => $request->location_id,
                'account_status' => 'unverified',
            ]);

            $code = random_int(100000, 999999);

            EmailVerification::create([
                'user_id'       => $user->id,
                'email'         => $user->email,
                'code'          => $code,
                'attempt_count' => 0,
                'is_used'       => false,
                'purpose'       => 'email_verification',
                'expires_at'    => now()->addMinutes(15),
            ]);

            Mail::to($user->email)->send(new SendVerificationCode($code));

            session([
                'pending_verification_email' => $user->email,
                'pending_verification_user_id' => $user->id
            ]);

            return redirect()->route('verify.email.form')->with('success', 'Registration submitted. Please check your email for a verification code.');

        } catch (Exception $e) {
    // This will temporarily bypass the generic message and show the real error screen
    throw $e; 
}
    }

    // Show Verification Form
    public function showVerifyForm()
    {
        // Ensure user has a pending verification session active
        if (!session()->has('pending_verification_email')) {
            // FIX: Changed from Auth.teacher_Register to Auth.Teacher_Register
            return redirect()->route('Auth.Teacher_Register')
            ->withErrors(['error' => 'No active registration session found.']);
        }

        return view('Auth.verify_email');
    }

    // Verify Email Code
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $email = session('pending_verification_email');
        $userId = session('pending_verification_user_id');

        if (!$email || !$userId) {
            // FIX: Changed from Auth.Teacher_register to Auth.Teacher_Register
            return redirect()->route('Auth.Teacher_Register')->withErrors(['error' => 'Session expired. Please register again.']);
        }

        $verification = EmailVerification::where('user_id', $userId)
            ->where('email', $email)
            ->where('is_used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'No verification code found. Please request a new one.']);
        }

        if (now()->greaterThan($verification->expires_at)) {
            return back()->withErrors(['code' => 'This verification code has expired.']);
        }

        if ($verification->attempt_count >= 5) {
            return back()->withErrors(['code' => 'Too many failed verification attempts. Please contact support or sign up again.']);
        }

        if ($verification->code !== $request->code) {
            $verification->increment('attempt_count');
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $verification->update([
            'is_used' => true,
            'verified_at' => now(),
        ]);

        $user = User::find($userId);
        if ($user) {
            $user->update([
                'account_status' => 'active'
            ]);

            TutorProfile::create([
                'user_id' => $user->id,
                'bio' => '',
                'experience_years' => 0,
                'qualification' => '',
                'max_students' => 1,
                'price_per_hour' => 0.00,
                'total_reviews' => 0,
                'availability_status' => 'inactive',
                'teaching_mode' => 'online',
            ]);

            session()->forget(['pending_verification_email', 'pending_verification_user_id']);

            Auth::login($user);

            return redirect()->route('login')->with('success', 'Email verified successfully! Welcome to your dashboard.');
        }

        return redirect()->route('Auth.Teacher_Register')
        ->withErrors(['error' => 'User not found.']);
    }

    // 1. Show the Login Form
    public function showLoginForm()
    {
        return view('Auth.Login');
    }

    // Process Secure Login with 5-Attempt Rate Limiting
    public function login(Request $request)
    {
        $request->validate([
            'login_input' => 'required|string',
            'password'    => 'required|string',
        ]);

        // Generate a unique key for this user + IP combination
        $throttleKey = Str::lower($request->input('login_input')) . '|' . $request->ip();

        // 1. Check if the user has exceeded 5 failed attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $secondsLeft = RateLimiter::availableIn($throttleKey);
            $hoursLeft = ceil($secondsLeft / 3600); // Convert seconds to hours

            return back()->withInput()->withErrors([
                'login_input' => "Too many login attempts. For security, your account is locked. Please try again in {$hoursLeft} hours."
            ]);
        }

        $loginField = filter_var($request->login_input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->login_input,
            'password'  => $request->password,
        ];

        $remember = $request->has('remember');

        // Attempt Login securely
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->account_status === 'unverified') {
                Auth::logout();
                return back()->withInput()->withErrors([
                    'login_input' => 'Your email is unverified. Please verify your email first.'
                ]);
            }

            // Success! Clear the rate limiter attempts
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();
  // Check if the logged-in user is a Teacher
            $role = Role::find($user->role_id);
            if ($role && strtolower($role->role_type) === 'teacher') {
                
                // Fetch their tutor profile
                $profile = TutorProfile::where('user_id', $user->id)->first();
                
                // If they don't have a profile yet, or if their Bio is still blank, send them to the setup page
                if (!$profile || empty($profile->bio)) {
                    return redirect()->route('tutor.profile.edit')->with('success', 'Please complete your tutor profile details.');
                }

                // If profile is already complete, send them to their dashboard
                return redirect()->intended(route('tutor.dashboard'))->with('success', 'Welcome back!');
            }

            // If user is not a teacher, or after teacher handling, send to generic landing
            return redirect()->intended(route('Landing'))->with('success', 'Welcome back!');
        }


        // 2. Failure! Record the attempt. 
        // Lockout duration set to 86400 seconds (24 Hours)
        RateLimiter::hit($throttleKey, 86400); 

        $attemptsLeft = RateLimiter::retriesLeft($throttleKey, 5);

        return back()->withInput()->withErrors([
            'login_input' => "These credentials do not match our records. You have {$attemptsLeft} attempts remaining.",
        ]);
    }

    
    // 3. Process Logout Safely
    public function logout(Request $request)
    {
        Auth::logout();


        // Invalidate current session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Landing')->with('success', 'Logged out successfully.');
    }
  }

