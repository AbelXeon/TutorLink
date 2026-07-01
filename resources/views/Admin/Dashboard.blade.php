@extends('Layouts.Layout')

@section('title', 'Admin Dashboard - TutorLink')

@section('content')
<div class="space-y-8">

    <!-- Header Panel -->
    <div class="border-b border-gray-200 pb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">Admin Dashboard</h2>
        <p class="text-sm text-gray-500 mt-1">Logged in as: <strong class="text-indigo-600">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->role?->role_type }})</strong></p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <!-- STATISTICS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-md">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-gray-900">{{ $totalUsers }}</span>
                <span class="text-sm text-gray-500 font-medium">Total Registered Users</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-md">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-4-9 5 9 4zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 12.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-gray-900">{{ $totalTeachers }}</span>
                <span class="text-sm text-gray-500 font-medium">Active Teachers</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-md">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-gray-900">{{ $totalStudents }}</span>
                <span class="text-sm text-gray-500 font-medium">Active Students</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-md">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-gray-900">{{ $totalBookings }}</span>
                <span class="text-sm text-gray-500 font-medium">Total Lesson Bookings</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-md">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-green-600">{{ $acceptedBookings }}</span>
                <span class="text-sm text-gray-500 font-medium">Accepted Lessons</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-md">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-yellow-600">{{ $pendingBookings }}</span>
                <span class="text-sm text-gray-500 font-medium">Pending Requests</span>
            </div>
        </div>
    </div>

    <!-- REGISTRATION FORM FOR SUBORDINATE ADMINS (Only visible to Super_Admin) -->
    @if(strtolower($user->role?->role_type) === 'super_admin')
        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 max-w-xl">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Register New Administrator</h3>
            <p class="text-xs text-gray-500 mb-6">Create standard admin accounts to manage bookings and monitor system audits.</p>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs font-bold text-gray-700 uppercase">First Name</label>
                        <input id="first_name" name="first_name" type="text" required value="{{ old('first_name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-bold text-gray-700 uppercase">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required value="{{ old('last_name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold text-gray-700 uppercase">Username</label>
                    <input id="username" name="username" type="text" required value="{{ old('username') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="phone_number" class="block text-xs font-bold text-gray-700 uppercase">Phone Number</label>
                    <input id="phone_number" name="phone_number" type="text" required value="{{ old('phone_number') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase">Password</label>
                        <input id="password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="py-2 px-6 border border-transparent text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Register Admin
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>
@endsection