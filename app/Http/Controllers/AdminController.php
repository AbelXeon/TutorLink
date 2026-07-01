<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Booking;
use App\Models\AdminAction;

class AdminController extends Controller
{
    // Show Dashboard with statistics
    public function showDashboard()
    {
        $user = Auth::user();

        $totalUsers = User::count();
        $totalTeachers = User::whereHas('role', function($q) { $q->where('role_type', 'Teacher'); })->count();
        $totalStudents = User::whereHas('role', function($q) { $q->where('role_type', 'Student'); })->count();
        $totalBookings = Booking::count();
        $acceptedBookings = Booking::where('status', 'accepted')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        return view('Admin.Dashboard', compact(
            'user', 'totalUsers', 'totalTeachers', 'totalStudents', 'totalBookings', 'acceptedBookings', 'pendingBookings'
        ));
    }

    // Register a standard Admin (Only accessible to Super_Admin)
    public function storeAdmin(Request $request)
    {
        $user = Auth::user();

        if (strtolower($user->role?->role_type) !== 'super_admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20|unique:users,phone_number',
            'username'     => 'required|string|min:5|max:16|unique:users,username',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $adminRole = Role::where('role_type', 'Admin')->firstOrFail();

        $newAdmin = User::create([
            'role_id'        => $adminRole->id,
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone_number'   => $request->phone_number,
            'username'       => $request->username,
            'password'       => Hash::make($request->password),
            'address'        => 'Admin Headquarters',
            'location_id'    => 1,
            'account_status' => 'active',
        ]);

        // Securely log this action in your admin_actions table
        AdminAction::create([
            'admin_id'       => $user->id,
            'target_user_id' => $newAdmin->id,
            'action_type'    => 'create_admin',
            'description'    => 'Registered a new standard administrator: ' . $newAdmin->username,
        ]);

        return back()->with('success', 'New Administrator registered successfully!');
    }
}