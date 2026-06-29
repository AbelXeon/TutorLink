<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Location;

class AuthController extends Controller
{
    public function Login(){
   
    return view('Auth.Login');

    }

    public function TeacherRegister(){

    return view('Auth.Teacher_Register');
    }

    public function StudentRegister(){

    return view('Auth.Student_Register');
    }


  // Processes the teacher registration form submission
    public function StoreTeacherRegister(Request $request)
    {
        // 1. Validate the incoming form inputs
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'phone_number'   => 'required|string|max:20',
            'username'       => 'required|string|max:255|unique:users,username',
            'password'       => 'required|string|min:6',
            'location'       => 'required|string',
            'address'        => 'required|string',
            'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Fetch or create the 'Teacher' role dynamically (since tables were reset)
        $role = Role::firstOrCreate([
            'role_type' => 'Teacher'
        ]);

        // 3. Fetch or create the location dynamically based on the city selected
        $location = Location::firstOrCreate([
            'name' => $request->location
        ]);

        // 4. Handle the profile image upload if provided
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // 5. Create the User record in the database
        User::create([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone_number'   => $request->phone_number,
            'username'       => $request->username,
            'password'       => Hash::make($request->password), // Always hash passwords
            'location_id'    => $location->id,                  // If migration column is named location_id, change key here
            'address'        => $request->address,
            'profile_image'  => $profileImagePath,
            'role_id'        => $role->id,
            'account_status' => 'active',
        ]);

        // 6. Redirect to the Login route with a success message
        return redirect()->route('Auth.Login')->with('success', 'Registration successful! Please log in.');
    }


    // Processes the student registration form submission
    public function StoreStudentRegister(Request $request)
    {
        // 1. Validate the incoming form inputs
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'phone_number'   => 'required|string|max:20',
            'username'       => 'required|string|max:255|unique:users,username',
            'password'       => 'required|string|min:6',
            'location'       => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Retrieve the 'Student' role from the database securely 
        // (If the Seeder wasn't run, firstOrCreate acts as a fallback to avoid errors)
        $role = Role::firstOrCreate([
            'role_type' => 'Student'
        ]);

        // 3. Resolve the location record dynamically
        $location = Location::firstOrCreate([
            'name' => $request->location
        ]);

        // 4. Handle the profile image upload if provided
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // 5. Create the Student User record
        User::create([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone_number'   => $request->phone_number,
            'username'       => $request->username,
            'password'       => Hash::make($request->password), 
            'location_id'    => $location->id, 
            'address'        => $request->address,
            'profile_image'  => $profileImagePath,
            'role_id'        => $role->id, // Handled securely on the server
            'account_status' => 'active',
        ]);

        // 6. Redirect to the Login route with a success message
        return redirect()->route('Auth.Login')->with('success', 'Student registration successful! Please log in.');
    }

    // Handles the login request
    public function StoreLogin(Request $request)
    {
        // 1. Validate inputs
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Identify if the user logged in using Email or Username
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->login,
            'password'  => $request->password,
        ];

        // 3. Attempt authentication
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            // 4. Fetch the user's role from the roles database table
            $role = Role::find($user->role_id);

            // 5. Redirect based on role
            if ($role && $role->role_type === 'Teacher') {
                // Check if this teacher has already completed their profile
                $hasProfile = \App\Models\TutorProfile::where('user_id', $user->id)->exists();

                if ($hasProfile) {
                    // Profile exists: Take them to the dashboard
                    return redirect()->route('Teacher.Teacher_Dashboard')
                        ->with('success', 'Welcome back!');
                }

                // Profile does NOT exist: Take them to fill out their profile
                return redirect()->route('Profile.Tutor_Profile_make')
                    ->with('success', 'Logged in successfully! Please complete your profile information.');
            }

            
            if ($role && $role->role_type === 'Student') {
                // Redirect Student to their Student Dashboard
                return redirect()->route('Student.Student_Dashboard')
                    ->with('success', 'Logged in successfully!');
            }

        // 6. If login fails, redirect back with error messages
        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
        }
        }


    public function Logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('Auth.Login')->with('success', 'You have been logged out.');
   }
}
