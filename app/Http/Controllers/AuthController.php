<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter; 
use Illuminate\Validation\Rules\Password; 
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

    public function registerTeacher(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'phone_number'  => 'required|string|max:20|unique:users,phone_number',
            'username'      => 'required|string|min:5|max:16|unique:users,username',
            'password'      => ['required','string','min:8','confirmed',
                   Password::min(8)
                    ->max(16)
                    ->mixedCase() 
                    ->numbers() 
                    ->symbols() 
            ],
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
                'created_at'    => now(), // Manually set created_at
            ]);

            Mail::to($user->email)->send(new SendVerificationCode($code));

            session([
                'pending_verification_email' => $user->email,
                'pending_verification_user_id' => $user->id
            ]);

            return redirect()->route('verify.email.form')->with('success', 'Registration submitted. Please check your email for a verification code.');

        } catch (Exception $e) {
            throw $e; 
        }
    }

   public function resendVerificationCode(Request $request)
    {
        $email = session('pending_verification_email');
        $userId = session('pending_verification_user_id');

        if (!$email || !$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please register again.'
            ], 422);
        }

        // Secure Rate Limiter Gatekeeper: 1 request per 60 seconds
        $throttleKey = 'resend-email-verification:' . $userId;
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $secondsLeft = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Please wait {$secondsLeft} seconds before requesting another code."
            ], 429);
        }

        // Record the request
        RateLimiter::hit($throttleKey, 60);

        $code = random_int(100000, 999999);

        // Generate and save the new verification record
        EmailVerification::create([
            'user_id'       => $userId,
            'email'         => $email,
            'code'          => $code,
            'attempt_count' => 0,
            'is_used'       => false,
            'purpose'       => 'email_verification',
            'expires_at'    => now()->addMinutes(15),
            'created_at'    => now(), // Manually set created_at
        ]);

        // Mail out the new code
        Mail::to($email)->send(new SendVerificationCode($code));

        return response()->json([
            'success' => true,
            'message' => 'A fresh verification code has been sent to your email.'
        ]);
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




   public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $email = session('pending_verification_email');
        $userId = session('pending_verification_user_id');

        if (!$email || !$userId) {
            return redirect()->route('Auth.Teacher_Register')->withErrors(['error' => 'Session expired. Please register again.']);
        }

        // Sorted by auto-increment ID to guarantee retrieval of the latest row
        $verification = EmailVerification::where('user_id', $userId)
            ->where('email', $email)
            ->where('is_used', false)
            ->orderBy('id', 'desc') 
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'No active verification code found. Please request a new one.']);
        }

        if (now()->greaterThan($verification->expires_at)) {
            return back()->withErrors(['code' => 'This verification code has expired.']);
        }

        if ($verification->attempt_count >= 5) {
            return back()->withErrors(['code' => 'Too many failed verification attempts. Please contact support or sign up again.']);
        }

        // Safer integer comparison
        if ((int)$verification->code !== (int)$request->code) {
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

            // Create TutorProfile if the user has the "Teacher" role
            $teacherRole = Role::where('role_type', 'Teacher')->first();
            if ($teacherRole && $user->role_id === $teacherRole->id) {
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
            }

            session()->forget(['pending_verification_email', 'pending_verification_user_id']);

            return redirect()->route('login')->with('success', 'Email verified successfully! Please log in to complete your profile.');
        }

        return redirect()->route('Auth.Teacher_Register')
            ->withErrors(['error' => 'User not found.']);
    }


    // 1. Show Student Registration Form
    public function showStudentRegisterForm()
    {
        $locations = Location::all();
        return view('Auth.Student_Register', compact('locations'));
    }

  public function registerStudent(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'phone_number'  => 'required|string|max:20|unique:users,phone_number',
            'username'      => 'required|string|min:5|max:16|unique:users,username',
            'password'      => [
                'required',
                'string',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->max(16)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'address'       => 'required|string|max:255',
            'location_id'   => 'required|exists:locations,id', 
        ]);

        try {
            $role = Role::where('role_type', 'Student')->firstOrFail();

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
                'created_at'    => now(), // Manually set created_at
            ]);

            Mail::to($user->email)->send(new SendVerificationCode($code));

            session([
                'pending_verification_email' => $user->email,
                'pending_verification_user_id' => $user->id
            ]);

            return redirect()->route('verify.email.form')->with('success', 'Registration submitted. Please check your email for a verification code.');

        } catch (Exception $e) {
            throw $e; 
        }
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

            // Check User Role securely
            $role = Role::find($user->role_id);
            if ($role) {
                $roleType = strtolower($role->role_type); // <-- Declared securely here

                // 1. If Teacher
                if ($roleType === 'teacher') {
                    $profile = TutorProfile::where('user_id', $user->id)->first();
                    
                    if (!$profile || empty($profile->bio)) {
                        return redirect()->route('tutor.profile.edit')->with('success', 'Please complete your tutor profile details.');
                    }

                   return redirect()->route('tutor.dashboard')->with('success', 'Welcome back!');
                }

                // 2. If Student (Redirects directly to dashboard, no profile making needed)
                if ($roleType === 'student') {
                    return redirect()->route('student.dashboard')->with('success', 'Welcome back!');
                }
                

                  if (in_array($roleType, ['admin', 'super_admin'])) {
                   return redirect()->route('admin.dashboard')->with('success', 'Welcome back to the Admin Dashboard!');
                }
            }

           return redirect()->route('Landing')->with('success', 'Welcome back!');
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

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
  }

