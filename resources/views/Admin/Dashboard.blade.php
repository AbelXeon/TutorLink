<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Access the secure TutorLink Admin Dashboard to monitor user statistics, manage tutor registrations, and review system audits.">
    <title>Admin Dashboard - TutorLink</title>
    <!-- Tailwind CSS Engine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Secure Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex font-sans antialiased">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="w-64 bg-gray-950 border-r border-gray-800 flex flex-col justify-between shrink-0">
        <div class="p-6">
            <!-- Brand Header -->
            <div class="flex items-center space-x-3 mb-8">
                <span class="text-xl font-black text-indigo-500 tracking-wider">TutorLink</span>
                <span class="bg-indigo-900/50 text-indigo-400 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-indigo-800">
                    {{ strtolower($user->role?->role_type) }}
                </span>
            </div>

            <!-- Nav Links -->
            <nav class="space-y-1.5">
                <button type="button" onclick="switchTab('overview')" id="nav-overview" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-semibold transition bg-indigo-600 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zm10 0a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                    <span>Overview</span>
                </button>

                @if(strtolower($user->role?->role_type) === 'super_admin')
                <button type="button" onclick="switchTab('register-admin')" id="nav-register-admin" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-semibold text-gray-400 hover:text-gray-100 hover:bg-gray-900 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    <span>Register Admin</span>
                </button>
                @endif

                <button type="button" onclick="switchTab('settings')" id="nav-settings" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-semibold text-gray-400 hover:text-gray-100 hover:bg-gray-900 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <span>Change Credentials</span>
                </button>
            </nav>
        </div>

        <!-- Sidebar footer / Logout Area -->
        <div class="p-6 border-t border-gray-800">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                    {{ substr($user->first_name, 0, 1) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-gray-200 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-[10px] text-gray-500 truncate">Platform Administrator</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('admin_logout_form').submit()" class="w-full flex items-center justify-center space-x-2 py-2 px-4 rounded-md text-xs font-semibold text-red-400 hover:text-red-300 bg-red-950/20 hover:bg-red-950/40 border border-red-900/40 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span>Logout Account</span>
            </button>
        </div>
    </aside>

    <!-- MAIN INTERFACE PANEL -->
    <main class="flex-grow p-8 overflow-y-auto max-w-7xl mx-auto w-full">

        <!-- TAB 1: OVERVIEW -->
        <div id="tab-content-overview" class="space-y-8">
            <div class="border-b border-gray-800 pb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">System Overview</h1>
                    <p class="text-sm text-gray-400 mt-1">Live metrics and analytical aggregates</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-950/30 border border-emerald-800/50 text-emerald-400 px-4 py-3 rounded-lg text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- METRIC CARDS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80 flex items-center gap-4">
                    <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-lg">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-white">{{ $totalUsers }}</span>
                        <span class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Registered Users</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80 flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-lg">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-4-9 5 9 4zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 12.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-white">{{ $totalTeachers }}</span>
                        <span class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Active Tutors</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80 flex items-center gap-4">
                    <div class="p-3 bg-blue-500/10 text-blue-400 rounded-lg">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-white">{{ $totalStudents }}</span>
                        <span class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Registered Students</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80 flex items-center gap-4">
                    <div class="p-3 bg-rose-500/10 text-rose-400 rounded-lg">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-white">{{ $totalBookings }}</span>
                        <span class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Total Lesson Bookings</span>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80 flex items-center gap-4">
                    <div class="p-3 bg-teal-500/10 text-teal-400 rounded-lg">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-teal-400">{{ $acceptedBookings }}</span>
                        <span class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Accepted Lessons</span>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80 flex items-center gap-4">
                    <div class="p-3 bg-amber-500/10 text-amber-400 rounded-lg">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-amber-400">{{ $pendingBookings }}</span>
                        <span class="text-xs text-gray-400 font-semibold tracking-wider uppercase">Pending Requests</span>
                    </div>
                </div>
            </div>

            <!-- ANALYTICS VISUALIZATIONS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- User Distribution Chart -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80">
                    <h3 class="text-sm font-bold text-gray-200 tracking-wider uppercase mb-4">User Base Metrics</h3>
                    <div class="h-64 relative">
                        <canvas id="userMetricsChart"></canvas>
                    </div>
                </div>

                <!-- Booking Ratio Chart -->
                <div class="bg-gray-950 p-6 rounded-xl border border-gray-800/80">
                    <h3 class="text-sm font-bold text-gray-200 tracking-wider uppercase mb-4">Lesson Booking Ratio</h3>
                    <div class="h-64 relative">
                        <canvas id="bookingRatioChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: REGISTER ADMIN (Super_Admin Only) -->
        @if(strtolower($user->role?->role_type) === 'super_admin')
        <div id="tab-content-register-admin" class="hidden space-y-8">
            <div class="border-b border-gray-800 pb-6">
                <h1 class="text-3xl font-extrabold text-white">Register Administrator</h1>
                <p class="text-sm text-gray-400 mt-1">Create standard subordinate accounts</p>
            </div>

            <div class="bg-gray-950 p-8 rounded-xl border border-gray-800/80 max-w-xl">
                @if ($errors->any())
                    <div class="bg-red-950/40 border border-red-900/50 text-red-400 px-4 py-3 rounded-lg text-xs font-semibold mb-6">
                        <ul class="list-disc list-inside space-y-1">
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
                            <label for="first_name" class="block text-xs font-bold text-gray-400 uppercase">First Name</label>
                            <input id="first_name" name="first_name" type="text" required value="{{ old('first_name') }}" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label for="last_name" class="block text-xs font-bold text-gray-400 uppercase">Last Name</label>
                            <input id="last_name" name="last_name" type="text" required value="{{ old('last_name') }}" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <div>
                        <label for="username" class="block text-xs font-bold text-gray-400 uppercase">Username</label>
                        <input id="username" name="username" type="text" required value="{{ old('username') }}" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase">Email Address</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-xs font-bold text-gray-400 uppercase">Phone Number</label>
                        <input id="phone_number" name="phone_number" type="text" required value="{{ old('phone_number') }}" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-400 uppercase">Password</label>
                            <input id="password" name="password" type="password" required class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="py-2.5 px-6 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            Register Subordinate Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- TAB 3: ACCOUNT SETTINGS (WIZARD-INTEGRATED) -->
        <div id="tab-content-settings" class="hidden space-y-8">
            <div class="border-b border-gray-800 pb-6">
                <h1 class="text-3xl font-extrabold text-white">Administrative Credentials</h1>
                <p class="text-sm text-gray-400 mt-1">Safely modify your authentication records</p>
            </div>

            <div class="bg-gray-950 p-8 rounded-xl border border-gray-800/80 max-w-xl">
                
                <!-- STAGE 1: ACTION TYPE SELECTOR -->
                <div id="settings_wizard_menu" class="space-y-4">
                    <p class="text-xs text-gray-400">Choose the credential task to update after securing identity verification.</p>
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <button type="button" onclick="initiateWizard('username')" class="flex flex-col items-center justify-center p-6 bg-gray-900 hover:bg-gray-850 border border-gray-800 hover:border-gray-700 rounded-xl transition text-center group">
                            <span class="text-2xl mb-2">👤</span>
                            <span class="text-sm font-bold text-white">Change Username</span>
                        </button>
                        <button type="button" onclick="initiateWizard('password')" class="flex flex-col items-center justify-center p-6 bg-gray-900 hover:bg-gray-850 border border-gray-800 hover:border-gray-700 rounded-xl transition text-center group">
                            <span class="text-2xl mb-2">🔑</span>
                            <span class="text-sm font-bold text-white">Change Password</span>
                        </button>
                    </div>
                </div>

                <!-- STAGE 2: ENTER REGISTERED EMAIL -->
                <div id="settings_wizard_email" class="hidden space-y-6">
                    <div class="border-b border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-white">Verify Identity</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Please confirm your active account email address.</p>
                    </div>
                    <div id="wizard_email_err" class="hidden bg-red-950/40 border border-red-900/50 text-red-400 px-4 py-2.5 rounded-lg text-xs font-semibold"></div>
                    <form id="wizard_email_form" action="{{ route('settings.send_code') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" id="wizard_selected_action" name="action">
                        <div>
                            <label for="wizard_email_input" class="block text-xs font-bold text-gray-400 uppercase">Registered Email</label>
                            <input id="wizard_email_input" name="email" type="email" required placeholder="admin@tutorlink.com" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" onclick="showWizardStep('menu')" class="px-4 py-2 text-xs font-semibold text-gray-400 hover:text-white transition">Back</button>
                            <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition">Request Code</button>
                        </div>
                    </form>
                </div>

                <!-- STAGE 3: ENTER 6-DIGIT CODE -->
                <div id="settings_wizard_code" class="hidden space-y-6">
                    <div class="border-b border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-white">Enter Security Token</h3>
                        <p class="text-xs text-gray-500 mt-0.5">We sent a 6-digit code to your inbox.</p>
                    </div>
                    <div id="wizard_code_err" class="hidden bg-red-950/40 border border-red-900/50 text-red-400 px-4 py-2.5 rounded-lg text-xs font-semibold"></div>
                    <form id="wizard_code_form" action="{{ route('settings.verify_code') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="wizard_code_input" class="block text-xs font-bold text-gray-400 uppercase mb-2">6-Digit Code</label>
                            <input id="wizard_code_input" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required placeholder="123456" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-center tracking-widest font-black text-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <button type="button" id="wizard_resend_btn" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 disabled:text-gray-600 disabled:cursor-not-allowed">Resend Code</button>
                            <div class="flex gap-3">
                                <button type="button" onclick="showWizardStep('email')" class="px-4 py-2 text-xs font-semibold text-gray-400 hover:text-white transition">Back</button>
                                <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition">Verify</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- STAGE 4: UPDATE USERNAME (UNLOCKED) -->
                <div id="settings_wizard_username" class="hidden space-y-6">
                    <div class="border-b border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-white">Update Username</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Security verified. Pick a new platform handler.</p>
                    </div>
                    <div id="wizard_username_err" class="hidden bg-red-950/40 border border-red-900/50 text-red-400 px-4 py-2.5 rounded-lg text-xs font-semibold"></div>
                    <form id="wizard_username_form" action="{{ route('settings.update_username') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="wizard_new_username" class="block text-xs font-bold text-gray-400 uppercase">New Username</label>
                            <input id="wizard_new_username" name="username" type="text" required value="{{ $user->username }}" class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        </div>
                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="py-2.5 px-6 rounded-md text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition">Apply Changes</button>
                        </div>
                    </form>
                </div>

                <!-- STAGE 5: UPDATE PASSWORD (UNLOCKED) -->
                <div id="settings_wizard_password" class="hidden space-y-6">
                    <div class="border-b border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-white">Configure New Password</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Choose a secure, complex character combination.</p>
                    </div>
                    <div id="wizard_password_err" class="hidden bg-red-950/40 border border-red-900/50 text-red-400 px-4 py-2.5 rounded-lg text-xs font-semibold"></div>
                    <form id="wizard_password_form" action="{{ route('settings.update_password') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="wizard_new_password" class="block text-xs font-bold text-gray-400 uppercase">New Password</label>
                                <input id="wizard_new_password" name="password" type="password" required class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                            </div>
                            <div>
                                <label for="wizard_confirm_password" class="block text-xs font-bold text-gray-400 uppercase">Confirm Password</label>
                                <input id="wizard_confirm_password" name="password_confirmation" type="password" required class="mt-1.5 block w-full bg-gray-900 text-white px-3.5 py-2.5 border border-gray-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                            </div>
                        </div>
                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="py-2.5 px-6 rounded-md text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition">Apply New Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </main>

    <!-- Hidden Logout Form -->
    <form id="admin_logout_form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- CONTROL JAVASCRIPT SYSTEM -->
    <script>
        // Tab Router Logic
        function switchTab(targetTab) {
            // Hide all tab containers
            document.getElementById('tab-content-overview').classList.add('hidden');
            const registerTab = document.getElementById('tab-content-register-admin');
            if (registerTab) registerTab.classList.add('hidden');
            document.getElementById('tab-content-settings').classList.add('hidden');

            // Deactivate all navigation tabs styling
            resetNavLink('nav-overview');
            resetNavLink('nav-register-admin');
            resetNavLink('nav-settings');

            // Activate chosen components
            document.getElementById(`tab-content-${targetTab}`).classList.remove('hidden');
            const activeNav = document.getElementById(`nav-${targetTab}`);
            if (activeNav) {
                activeNav.className = "w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-semibold transition bg-indigo-600 text-white";
            }
        }

        function resetNavLink(navId) {
            const el = document.getElementById(navId);
            if (el) {
                el.className = "w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-semibold text-gray-400 hover:text-gray-100 hover:bg-gray-900 transition";
            }
        }

        // Settings Verification Wizard Logic
        const stepMenu = document.getElementById('settings_wizard_menu');
        const stepEmail = document.getElementById('settings_wizard_email');
        const stepCode = document.getElementById('settings_wizard_code');
        const stepUsername = document.getElementById('settings_wizard_username');
        const stepPassword = document.getElementById('settings_wizard_password');

        const errEmail = document.getElementById('wizard_email_err');
        const errCode = document.getElementById('wizard_code_err');
        const errUsername = document.getElementById('wizard_username_err');
        const errPassword = document.getElementById('wizard_password_err');

        function showWizardStep(step) {
            stepMenu.classList.add('hidden');
            stepEmail.classList.add('hidden');
            stepCode.classList.add('hidden');
            stepUsername.classList.add('hidden');
            stepPassword.classList.add('hidden');

            errEmail.classList.add('hidden');
            errCode.classList.add('hidden');
            errUsername.classList.add('hidden');
            errPassword.classList.add('hidden');

            if (step === 'menu') stepMenu.classList.remove('hidden');
            if (step === 'email') stepEmail.classList.remove('hidden');
            if (step === 'code') stepCode.classList.remove('hidden');
            if (step === 'username') stepUsername.classList.remove('hidden');
            if (step === 'password') stepPassword.classList.remove('hidden');
        }

        function initiateWizard(actionType) {
            document.getElementById('wizard_selected_action').value = actionType;
            showWizardStep('email');
        }

        // STEP 1 AJAX Code Dispatch
        document.getElementById('wizard_email_form').addEventListener('submit', function(e) {
            e.preventDefault();
            errEmail.classList.add('hidden');

            fetch(this.getAttribute('action'), {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    showWizardStep('code');
                } else {
                    errEmail.textContent = res.body.message || "Email validation failed.";
                    errEmail.classList.remove('hidden');
                }
            })
            .catch(err => console.error(err));
        });

        // STEP 2 AJAX Verification Token Check
        document.getElementById('wizard_code_form').addEventListener('submit', function(e) {
            e.preventDefault();
            errCode.classList.add('hidden');

            fetch(this.getAttribute('action'), {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    showWizardStep(res.body.action);
                } else {
                    errCode.textContent = res.body.message || "Invalid or expired token.";
                    errCode.classList.remove('hidden');
                }
            })
            .catch(err => console.error(err));
        });

        // STEP 3A AJAX Apply Username
        document.getElementById('wizard_username_form').addEventListener('submit', function(e) {
            e.preventDefault();
            errUsername.classList.add('hidden');

            fetch(this.getAttribute('action'), {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    window.location.reload();
                } else {
                    errUsername.textContent = res.body.message || "Failed to update username.";
                    errUsername.classList.remove('hidden');
                }
            })
            .catch(err => console.error(err));
        });

        // STEP 3B AJAX Apply Password
        document.getElementById('wizard_password_form').addEventListener('submit', function(e) {
            e.preventDefault();
            errPassword.classList.add('hidden');

            fetch(this.getAttribute('action'), {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    window.location.reload();
                } else {
                    errPassword.textContent = res.body.message || "Failed to update password.";
                    errPassword.classList.remove('hidden');
                }
            })
            .catch(err => console.error(err));
        });

        // INITIALIZE CHART GRAPHICS (Chart.js)
        document.addEventListener("DOMContentLoaded", () => {
            // Chart 1: User Base Demographics Chart
            const ctxUser = document.getElementById('userMetricsChart').getContext('2d');
            new Chart(ctxUser, {
                type: 'bar',
                data: {
                    labels: ['Tutors', 'Students', 'Total Users'],
                    datasets: [{
                        label: 'Total Accounts Registered',
                        data: [{{ $totalTeachers }}, {{ $totalStudents }}, {{ $totalUsers }}],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.2)', // Emerald green
                            'rgba(59, 130, 246, 0.2)', // Blue
                            'rgba(99, 102, 241, 0.2)'  // Indigo
                        ],
                        borderColor: [
                            'rgb(16, 185, 129)',
                            'rgb(59, 130, 246)',
                            'rgb(99, 102, 241)'
                        ],
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(75, 85, 99, 0.1)' },
                            ticks: { color: '#9ca3af' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af' }
                        }
                    }
                }
            });

            // Chart 2: Booking Status Ratio (Doughnut)
            const ctxBooking = document.getElementById('bookingRatioChart').getContext('2d');
            new Chart(ctxBooking, {
                type: 'doughnut',
                data: {
                    labels: ['Accepted Sessions', 'Pending Requests'],
                    datasets: [{
                        data: [{{ $acceptedBookings }}, {{ $pendingBookings }}],
                        backgroundColor: [
                            'rgba(20, 184, 166, 0.2)', // Teal
                            'rgba(245, 158, 11, 0.2)'  // Amber
                        ],
                        borderColor: [
                            'rgb(20, 184, 166)',
                            'rgb(245, 158, 11)'
                        ],
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#9ca3af', font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>