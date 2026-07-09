<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\SendVerificationCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class SettingController extends Controller
{
    // Step 1: Generate and send the 6-digit verification code (Rate-Limited to 1 per minute)
    public function sendCode(Request $request)
    {
        $request->validate([
            'email'  => 'required|email',
            'action' => 'required|string|in:username,password'
        ]);

        $user = Auth::user();

        // Safety verification: Email must match authenticated user's email
        if ($request->email !== $user->email) {
            return response()->json([
                'success' => false,
                'message' => 'The email address provided does not match our records.'
            ], 422);
        }
        
        // Define a unique throttle key for this user's settings code requests
        $throttleKey = 'send-settings-code:' . $user->id;

        // Secure Gatekeeper: Allow only 1 request per 60 seconds
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $secondsLeft = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Please wait {$secondsLeft} seconds before requesting another code."
            ], 429);
        }

        // Record the attempt (expires/cools down in 60 seconds)
        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);

        $code = random_int(100000, 999999);

        // Store selected action inside the session for step 2 verification check
        session(['settings_action' => $request->action]);

        EmailVerification::create([
            'user_id'       => $user->id,
            'email'         => $user->email,
            'code'          => $code,
            'attempt_count' => 0,
            'is_used'       => false,
            'purpose'       => 'settings_verify', 
            'expires_at'    => now()->addMinutes(15),
            'created_at'    => now(), // Manually supply created_at because $timestamps = false; is active on the model
        ]);

        // Dynamically set the email type depending on the action
        $emailType = $request->action === 'username' ? 'change_username' : 'change_password';
        Mail::to($user->email)->send(new SendVerificationCode($code, $emailType));

        return response()->json([
            'success' => true,
            'message' => 'A verification code has been sent to your email.'
        ]);
    }

    // Step 2: Verify the 6-digit code and temporarily unlock settings
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();

        // Retrieve the newest record by sorting by the auto-increment primary key ID
        $verification = EmailVerification::where('user_id', $user->id)
            ->where('is_used', false)
            ->orderBy('id', 'desc') // Guaranteeing the newest code is fetched
            ->first();

        if (!$verification) {
            return response()->json(['success' => false, 'message' => 'No active verification code found.'], 422);
        }

        // Parse standard created_at column safely
        $createdAt = Carbon::parse($verification->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            return response()->json(['success' => false, 'message' => 'Verification code has expired.'], 422);
        }

        // Clean input and cast values to integers for precise, type-safe comparison
        $inputCode = (int) trim($request->code);
        $dbCode = (int) $verification->code;

        if ($dbCode !== $inputCode) {
            $verification->increment('attempt_count');
            return response()->json(['success' => false, 'message' => 'Incorrect verification code.'], 422);
        }

        $verification->update(['is_used' => true]);

        // Unlock settings inside the secure session
        session(['settings_unlocked' => true]);

        return response()->json([
            'success' => true,
            'action'  => session('settings_action') // Returns 'username' or 'password' to JS
        ]);
    }

    // Step 3A: Save New Username
    public function updateUsername(Request $request)
    {
        // Security Gatekeeper
        if (!session('settings_unlocked') || session('settings_action') !== 'username') {
            return response()->json(['success' => false, 'message' => 'Security Restriction: Unauthorized access.'], 403);
        }

        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|min:5|max:16|unique:users,username,' . $user->id,
        ]);

        $user->update([
            'username' => $request->username
        ]);

        // Clean up session flags
        session()->forget(['settings_unlocked', 'settings_action', 'settings_email']);

        return response()->json(['success' => true]);
    }

    // Step 3B: Save New Hashed Password
    public function updatePassword(Request $request)
    {
        // Security Gatekeeper
        if (!session('settings_unlocked') || session('settings_action') !== 'password') {
            return response()->json(['success' => false, 'message' => 'Security Restriction: Unauthorized access.'], 403);
        }

        $user = Auth::user();

        $request->validate([
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->max(16)->mixedCase()->numbers()->symbols()
            ],
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clean up session flags
        session()->forget(['settings_unlocked', 'settings_action', 'settings_email']);

        return response()->json(['success' => true]);
    }
}