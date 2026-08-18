<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Access the secure TutorLink Admin Dashboard to monitor user statistics, manage tutor registrations, and review system audits.">
    <title>Admin Dashboard - TutorLink</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Engine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Secure Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root{
            --ink:#0a0a0a;
            --paper:#f5f4f1;
            --white:#ffffff;
            --blue:#1350e0;
            --blue-dark:#0d3aa8;
            --line: rgba(10,10,10,0.14);
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        .display-font {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .swiss-panel { border-radius: 0; border: 1px solid var(--line); box-shadow: none; background: var(--white); }
        .swiss-input {
            border-radius: 0;
            background: var(--paper);
            border: 1px solid var(--line);
            transition: border-color .15s ease;
        }
        .swiss-input:focus { outline: none; border-color: var(--ink); box-shadow: none; }
        .btn-swiss-primary {
            border-radius: 0;
            background-color: var(--ink);
            border: 1px solid var(--ink);
            transition: background-color .15s ease, border-color .15s ease;
        }
        .btn-swiss-primary:hover { background-color: var(--blue); border-color: var(--blue); }
        .nav-link-flat {
            border-radius: 0;
            color: #a3a3a3;
            transition: background-color .15s ease, color .15s ease;
        }
        .nav-link-flat:hover { background: rgba(255,255,255,0.06); color: var(--white); }
        .nav-link-flat.active { background: var(--blue); color: var(--white); }
        .icon-chip { border-radius: 0; border: 1px solid var(--line); }

        /* ===== Enhancements: cards, motion, mobile nav ===== */
        .metric-card {
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, border-color .2s ease;
        }
        .metric-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--accent, var(--blue));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s ease;
        }
        .metric-card:hover { transform: translateY(-3px); border-color: rgba(10,10,10,0.28); }
        .metric-card:hover::before { transform: scaleX(1); }
        .metric-card .icon-chip { transition: transform .25s ease; }
        .metric-card:hover .icon-chip { transform: scale(1.08) rotate(-2deg); }

        .metric-track {
            height: 4px;
            width: 100%;
            background: rgba(10,10,10,0.07);
            overflow: hidden;
            margin-top: 12px;
        }
        .metric-track > span {
            display: block;
            height: 100%;
            width: 0%;
            background: var(--accent, var(--blue));
            transition: width 1s cubic-bezier(.16,1,.3,1);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp .5s ease both; }

        /* Gauge (pure CSS conic-gradient "pie") */
        .gauge {
            --pct: 0;
            width: 148px;
            height: 148px;
            border-radius: 50%;
            background: conic-gradient(var(--blue) calc(var(--pct) * 1%), rgba(10,10,10,0.08) 0);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 1s ease;
            position: relative;
        }
        .gauge::after {
            content: "";
            position: absolute;
            width: 108px;
            height: 108px;
            border-radius: 50%;
            background: var(--white);
            border: 1px solid var(--line);
        }
        .gauge-label {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        /* Mobile off-canvas sidebar */
        #sidebar {
            transition: transform .3s ease;
        }
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                top: 0; left: 0; bottom: 0;
                z-index: 50;
                transform: translateX(-100%);
            }
            #sidebar.sidebar-open { transform: translateX(0); }
        }
        #sidebar-overlay {
            transition: opacity .25s ease;
        }

        .hamburger-btn span {
            display: block;
            height: 2px;
            background: currentColor;
            transition: transform .25s ease, opacity .2s ease;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row font-sans antialiased" style="background: var(--paper); color: var(--ink);">

    <!-- MOBILE TOPBAR (hamburger + brand) -->
    <header class="lg:hidden sticky top-0 z-30 flex items-center justify-between px-4 py-3" style="background: var(--ink); border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center space-x-2">
            <span class="display-font text-lg text-white">TutorLink</span>
            <span class="text-[9px] font-bold px-1.5 py-0.5 uppercase" style="background: rgba(19,80,224,0.15); color: #7d9cf0; border: 1px solid rgba(19,80,224,0.4);">
                {{ strtolower($user->role?->role_type) }}
            </span>
        </div>
        <button type="button" onclick="openSidebar()" aria-label="Open menu" class="hamburger-btn w-9 h-9 flex flex-col items-center justify-center gap-[5px] text-white" style="border: 1px solid rgba(255,255,255,0.15);">
            <span class="w-4"></span>
            <span class="w-4"></span>
            <span class="w-4"></span>
        </button>
    </header>

    <!-- MOBILE OVERLAY -->
    <div id="sidebar-overlay" onclick="closeSidebar()" class="hidden fixed inset-0 z-40 lg:hidden" style="background: rgba(10,10,10,0.55);"></div>

    <!-- SIDEBAR NAVIGATION -->
    <aside id="sidebar" class="w-64 flex flex-col justify-between shrink-0" style="background: var(--ink); border-right: 1px solid rgba(255,255,255,0.08);">
        <div class="p-6">
            <!-- Brand Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <span class="display-font text-xl text-white">TutorLink</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 uppercase" style="background: rgba(19,80,224,0.15); color: #7d9cf0; border: 1px solid rgba(19,80,224,0.4);">
                        {{ strtolower($user->role?->role_type) }}
                    </span>
                </div>
                <!-- Mobile close (X) button -->
                <button type="button" onclick="closeSidebar()" aria-label="Close menu" class="lg:hidden w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition" style="border: 1px solid rgba(255,255,255,0.15);">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Nav Links -->
            <nav class="space-y-1.5">
                <button type="button" onclick="switchTab('overview'); closeSidebarOnMobile();" id="nav-overview" class="nav-link-flat active w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zm10 0a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                    <span>Overview</span>
                </button>

                @if(strtolower($user->role?->role_type) === 'super_admin')
                <button type="button" onclick="switchTab('register-admin'); closeSidebarOnMobile();" id="nav-register-admin" class="nav-link-flat w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    <span>Register Admin</span>
                </button>
                @endif

                <button type="button" onclick="switchTab('settings'); closeSidebarOnMobile();" id="nav-settings" class="nav-link-flat w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <span>Change Credentials</span>
                </button>
            </nav>
        </div>

        <!-- Sidebar footer / Logout Area -->
        <div class="p-6" style="border-top: 1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center space-x-3 mb-4">
                <div class="icon-chip w-8 h-8 flex items-center justify-center font-bold" style="background: rgba(19,80,224,0.15); color: #7d9cf0; border-color: rgba(19,80,224,0.35);">
                    {{ substr($user->first_name, 0, 1) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-[10px] text-gray-500 truncate">Platform Administrator</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('admin_logout_form').submit()" class="w-full flex items-center justify-center space-x-2 py-2 px-4 text-xs font-semibold transition" style="border-radius:0; color:#f87171; background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.3);">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span>Logout Account</span>
            </button>
        </div>
    </aside>

    <!-- MAIN INTERFACE PANEL -->
    <main class="flex-grow p-4 sm:p-6 lg:p-8 overflow-y-auto max-w-7xl mx-auto w-full">

        <!-- TAB 1: OVERVIEW -->
        <div id="tab-content-overview" class="space-y-6 sm:space-y-8">
            <div class="pb-6 flex items-center justify-between" style="border-bottom: 1px solid var(--line);">
                <div>
                    <h1 class="text-2xl sm:text-3xl display-font text-gray-900">System Overview</h1>
                    <p class="text-sm text-gray-500 mt-1">Live metrics and analytical aggregates</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-700 px-4 py-3 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- METRIC CARDS GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Card 1 -->
                <div class="metric-card swiss-panel p-5 sm:p-6 fade-up" style="--accent: var(--blue); animation-delay: .02s;">
                    <div class="flex items-center gap-4">
                        <div class="icon-chip p-3" style="background: rgba(19,80,224,0.06); color: var(--blue); border-color: rgba(19,80,224,0.25);">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <span class="block text-2xl display-font text-gray-900" data-countup data-target="{{ (int) $totalUsers }}">0</span>
                            <span class="text-xs text-gray-500 font-semibold tracking-wider uppercase">Registered Users</span>
                        </div>
                    </div>
                    <div class="metric-track"><span data-fill="100"></span></div>
                </div>

                <!-- Card 2 -->
                <div class="metric-card swiss-panel p-5 sm:p-6 fade-up" style="--accent: var(--ink); animation-delay: .06s;">
                    <div class="flex items-center gap-4">
                        <div class="icon-chip p-3" style="background: rgba(13,58,168,0.06); color: var(--ink); border-color: var(--line);">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-4-9 5 9 4zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 12.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                        </div>
                        <div>
                            <span class="block text-2xl display-font text-gray-900" data-countup data-target="{{ (int) $totalTeachers }}">0</span>
                            <span class="text-xs text-gray-500 font-semibold tracking-wider uppercase">Active Tutors</span>
                        </div>
                    </div>
                    <div class="metric-track"><span data-fill="{{ $totalUsers > 0 ? round(($totalTeachers / $totalUsers) * 100) : 0 }}"></span></div>
                </div>

                <!-- Card 3 -->
                <div class="metric-card swiss-panel p-5 sm:p-6 fade-up" style="--accent: var(--blue); animation-delay: .1s;">
                    <div class="flex items-center gap-4">
                        <div class="icon-chip p-3" style="background: rgba(19,80,224,0.06); color: var(--blue); border-color: rgba(19,80,224,0.25);">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div>
                            <span class="block text-2xl display-font text-gray-900" data-countup data-target="{{ (int) $totalStudents }}">0</span>
                            <span class="text-xs text-gray-500 font-semibold tracking-wider uppercase">Registered Students</span>
                        </div>
                    </div>
                    <div class="metric-track"><span data-fill="{{ $totalUsers > 0 ? round(($totalStudents / $totalUsers) * 100) : 0 }}"></span></div>
                </div>

                <!-- Card 4 -->
                <div class="metric-card swiss-panel p-5 sm:p-6 fade-up" style="--accent: var(--ink); animation-delay: .14s;">
                    <div class="flex items-center gap-4">
                        <div class="icon-chip p-3" style="background: rgba(10,10,10,0.05); color: var(--ink); border-color: var(--line);">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <span class="block text-2xl display-font text-gray-900" data-countup data-target="{{ (int) $totalBookings }}">0</span>
                            <span class="text-xs text-gray-500 font-semibold tracking-wider uppercase">Total Lesson Bookings</span>
                        </div>
                    </div>
                    <div class="metric-track"><span data-fill="100"></span></div>
                </div>

                <!-- Card 5 -->
                <div class="metric-card swiss-panel p-5 sm:p-6 fade-up" style="--accent: #0f8f6f; animation-delay: .18s;">
                    <div class="flex items-center gap-4">
                        <div class="icon-chip p-3" style="background: rgba(16,163,127,0.08); color: #0f8f6f; border-color: rgba(16,163,127,0.3);">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <span class="block text-2xl display-font" style="color:#0f8f6f;" data-countup data-target="{{ (int) $acceptedBookings }}">0</span>
                            <span class="text-xs text-gray-500 font-semibold tracking-wider uppercase">Accepted Lessons</span>
                        </div>
                    </div>
                    <div class="metric-track"><span data-fill="{{ $totalBookings > 0 ? round(($acceptedBookings / $totalBookings) * 100) : 0 }}"></span></div>
                </div>

                <!-- Card 6 -->
                <div class="metric-card swiss-panel p-5 sm:p-6 fade-up" style="--accent: #b45309; animation-delay: .22s;">
                    <div class="flex items-center gap-4">
                        <div class="icon-chip p-3" style="background: rgba(217,119,6,0.08); color:#b45309; border-color: rgba(217,119,6,0.3);">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <span class="block text-2xl display-font" style="color:#b45309;" data-countup data-target="{{ (int) $pendingBookings }}">0</span>
                            <span class="text-xs text-gray-500 font-semibold tracking-wider uppercase">Pending Requests</span>
                        </div>
                    </div>
                    <div class="metric-track"><span data-fill="{{ $totalBookings > 0 ? round(($pendingBookings / $totalBookings) * 100) : 0 }}"></span></div>
                </div>
            </div>

            <!-- ANALYTICS VISUALIZATIONS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- User Distribution Chart -->
                <div class="swiss-panel p-5 sm:p-6 lg:col-span-1">
                    <h3 class="text-xs font-bold text-gray-500 tracking-wider uppercase mb-4">User Base Metrics</h3>
                    <div class="h-56 sm:h-64 relative">
                        <canvas id="userMetricsChart"></canvas>
                    </div>
                </div>

                <!-- Booking Ratio Chart -->
                <div class="swiss-panel p-5 sm:p-6 lg:col-span-1">
                    <h3 class="text-xs font-bold text-gray-500 tracking-wider uppercase mb-4">Lesson Booking Ratio</h3>
                    <div class="h-56 sm:h-64 relative">
                        <canvas id="bookingRatioChart"></canvas>
                    </div>
                </div>

                <!-- Acceptance Rate Gauge -->
                <div class="swiss-panel p-5 sm:p-6 lg:col-span-1 flex flex-col">
                    <h3 class="text-xs font-bold text-gray-500 tracking-wider uppercase mb-4">Acceptance Rate</h3>
                    @php
                        $ratedBookings = $acceptedBookings + $pendingBookings;
                        $acceptPct = $ratedBookings > 0 ? round(($acceptedBookings / $ratedBookings) * 100) : 0;
                    @endphp
                    <div class="flex-grow flex items-center justify-center h-56 sm:h-64">
                        <div class="gauge" id="acceptanceGauge" style="--pct: 0;" data-pct="{{ $acceptPct }}">
                            <div class="gauge-label">
                                <span class="block text-2xl display-font text-gray-900" id="acceptanceGaugeLabel">0%</span>
                                <span class="block text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Accepted</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: REGISTER ADMIN (Super_Admin Only) -->
        @if(strtolower($user->role?->role_type) === 'super_admin')
        <div id="tab-content-register-admin" class="hidden space-y-8">
            <div class="pb-6" style="border-bottom: 1px solid var(--line);">
                <h1 class="text-2xl sm:text-3xl display-font text-gray-900">Register Administrator</h1>
                <p class="text-sm text-gray-500 mt-1">Create standard subordinate accounts</p>
            </div>

            <div class="swiss-panel p-5 sm:p-8 max-w-xl">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 text-xs font-semibold mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-xs font-bold text-gray-500 uppercase">First Name</label>
                            <input id="first_name" name="first_name" type="text" required value="{{ old('first_name') }}" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                        </div>
                        <div>
                            <label for="last_name" class="block text-xs font-bold text-gray-500 uppercase">Last Name</label>
                            <input id="last_name" name="last_name" type="text" required value="{{ old('last_name') }}" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                        </div>
                    </div>

                    <div>
                        <label for="username" class="block text-xs font-bold text-gray-500 uppercase">Username</label>
                        <input id="username" name="username" type="text" required value="{{ old('username') }}" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase">Email Address</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-xs font-bold text-gray-500 uppercase">Phone Number</label>
                        <input id="phone_number" name="phone_number" type="text" required value="{{ old('phone_number') }}" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-500 uppercase">Password</label>
                            <input id="password" name="password" type="password" required class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="btn-swiss-primary py-2.5 px-6 text-xs font-bold text-white w-full sm:w-auto">
                            Register Subordinate Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- TAB 3: ACCOUNT SETTINGS (WIZARD-INTEGRATED) -->
        <div id="tab-content-settings" class="hidden space-y-8">
            <div class="pb-6" style="border-bottom: 1px solid var(--line);">
                <h1 class="text-2xl sm:text-3xl display-font text-gray-900">Administrative Credentials</h1>
                <p class="text-sm text-gray-500 mt-1">Safely modify your authentication records</p>
            </div>

            <div class="swiss-panel p-5 sm:p-8 max-w-xl">

                <!-- STAGE 1: ACTION TYPE SELECTOR -->
                <div id="settings_wizard_menu" class="space-y-4">
                    <p class="text-xs text-gray-500">Choose the credential task to update after securing identity verification.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <button type="button" onclick="initiateWizard('username')" class="swiss-panel flex flex-col items-center justify-center p-6 hover:border-gray-400 transition text-center" style="background: var(--paper);">
                            <svg class="w-6 h-6 mb-2" style="color: var(--blue);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="3.5"/>
                                <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
                            </svg>
                            <span class="text-sm font-bold text-gray-900">Change Username</span>
                        </button>
                        <button type="button" onclick="initiateWizard('password')" class="swiss-panel flex flex-col items-center justify-center p-6 hover:border-gray-400 transition text-center" style="background: var(--paper);">
                            <svg class="w-6 h-6 mb-2" style="color: var(--blue);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="4" y="10" width="16" height="10" rx="0.5"/>
                                <path d="M7 10V7a5 5 0 0 1 10 0v3" stroke-linecap="round"/>
                            </svg>
                            <span class="text-sm font-bold text-gray-900">Change Password</span>
                        </button>
                    </div>
                </div>

                <!-- STAGE 2: ENTER REGISTERED EMAIL -->
                <div id="settings_wizard_email" class="hidden space-y-6">
                    <div class="pb-4" style="border-bottom: 1px solid var(--line);">
                        <h3 class="text-lg font-bold text-gray-900">Verify Identity</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Please confirm your active account email address.</p>
                    </div>
                    <div id="wizard_email_err" class="hidden bg-red-50 border border-red-300 text-red-700 px-4 py-2.5 text-xs font-semibold"></div>
                    <form id="wizard_email_form" action="{{ route('settings.send_code') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" id="wizard_selected_action" name="action">
                        <div>
                            <label for="wizard_email_input" class="block text-xs font-bold text-gray-500 uppercase">Registered Email</label>
                            <input id="wizard_email_input" name="email" type="email" required placeholder="admin@tutorlink.com" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" onclick="showWizardStep('menu')" class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-900 transition">Back</button>
                            <button type="submit" class="btn-swiss-primary px-6 py-2 text-xs font-bold text-white">Request Code</button>
                        </div>
                    </form>
                </div>

                <!-- STAGE 3: ENTER 6-DIGIT CODE -->
                <div id="settings_wizard_code" class="hidden space-y-6">
                    <div class="pb-4" style="border-bottom: 1px solid var(--line);">
                        <h3 class="text-lg font-bold text-gray-900">Enter Security Token</h3>
                        <p class="text-xs text-gray-500 mt-0.5">We sent a 6-digit code to your inbox.</p>
                    </div>
                    <div id="wizard_code_err" class="hidden bg-red-50 border border-red-300 text-red-700 px-4 py-2.5 text-xs font-semibold"></div>
                    <form id="wizard_code_form" action="{{ route('settings.verify_code') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="wizard_code_input" class="block text-xs font-bold text-gray-500 uppercase mb-2">6-Digit Code</label>
                            <input id="wizard_code_input" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required placeholder="123456" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-center tracking-widest font-black text-xl text-gray-900">
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-2">
                            <button type="button" id="wizard_resend_btn" class="text-xs font-bold disabled:text-gray-400 disabled:cursor-not-allowed text-left" style="color: var(--blue);">Resend Code</button>
                            <div class="flex gap-3 justify-end">
                                <button type="button" onclick="showWizardStep('email')" class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-900 transition">Back</button>
                                <button type="submit" class="btn-swiss-primary px-6 py-2 text-xs font-bold text-white">Verify</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- STAGE 4: UPDATE USERNAME (UNLOCKED) -->
                <div id="settings_wizard_username" class="hidden space-y-6">
                    <div class="pb-4" style="border-bottom: 1px solid var(--line);">
                        <h3 class="text-lg font-bold text-gray-900">Update Username</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Security verified. Pick a new platform handler.</p>
                    </div>
                    <div id="wizard_username_err" class="hidden bg-red-50 border border-red-300 text-red-700 px-4 py-2.5 text-xs font-semibold"></div>
                    <form id="wizard_username_form" action="{{ route('settings.update_username') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="wizard_new_username" class="block text-xs font-bold text-gray-500 uppercase">New Username</label>
                            <input id="wizard_new_username" name="username" type="text" required value="{{ $user->username }}" class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                        </div>
                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="btn-swiss-primary py-2.5 px-6 text-xs font-bold text-white w-full sm:w-auto">Apply Changes</button>
                        </div>
                    </form>
                </div>

                <!-- STAGE 5: UPDATE PASSWORD (UNLOCKED) -->
                <div id="settings_wizard_password" class="hidden space-y-6">
                    <div class="pb-4" style="border-bottom: 1px solid var(--line);">
                        <h3 class="text-lg font-bold text-gray-900">Configure New Password</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Choose a secure, complex character combination.</p>
                    </div>
                    <div id="wizard_password_err" class="hidden bg-red-50 border border-red-300 text-red-700 px-4 py-2.5 text-xs font-semibold"></div>
                    <form id="wizard_password_form" action="{{ route('settings.update_password') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="wizard_new_password" class="block text-xs font-bold text-gray-500 uppercase">New Password</label>
                                <input id="wizard_new_password" name="password" type="password" required class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                            </div>
                            <div>
                                <label for="wizard_confirm_password" class="block text-xs font-bold text-gray-500 uppercase">Confirm Password</label>
                                <input id="wizard_confirm_password" name="password_confirmation" type="password" required class="swiss-input mt-1.5 block w-full px-3.5 py-2.5 text-sm text-gray-900">
                            </div>
                        </div>
                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="btn-swiss-primary py-2.5 px-6 text-xs font-bold text-white w-full sm:w-auto">Apply New Password</button>
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
        // ===== Mobile sidebar controls (new — purely presentational, no tab/business logic touched) =====
        const sidebarEl = document.getElementById('sidebar');
        const sidebarOverlayEl = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebarEl.classList.add('sidebar-open');
            sidebarOverlayEl.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebarEl.classList.remove('sidebar-open');
            sidebarOverlayEl.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function closeSidebarOnMobile() {
            if (window.innerWidth < 1024) closeSidebar();
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebarOverlayEl.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

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
                activeNav.className = "nav-link-flat active w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold";
            }
        }

        function resetNavLink(navId) {
            const el = document.getElementById(navId);
            if (el) {
                el.className = "nav-link-flat w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold";
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

        // ===== Presentational-only enhancements (do not touch app data) =====

        // Animate the metric-card progress tracks in on load
        document.querySelectorAll('.metric-track > span').forEach(bar => {
            const pct = bar.getAttribute('data-fill') || 0;
            requestAnimationFrame(() => {
                setTimeout(() => { bar.style.width = pct + '%'; }, 150);
            });
        });

        // Count-up animation for the numbers already rendered by the server
        function animateValue(el, end, duration) {
            if (isNaN(end)) return;
            const start = 0;
            const startTime = performance.now();
            function tick(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(start + (end - start) * eased);
                if (progress < 1) requestAnimationFrame(tick);
                else el.textContent = end;
            }
            requestAnimationFrame(tick);
        }
        document.querySelectorAll('[data-countup]').forEach(el => {
            const target = parseInt(el.getAttribute('data-target'), 10);
            animateValue(el, target, 900);
        });

        // Animate the acceptance-rate CSS gauge
        const gaugeEl = document.getElementById('acceptanceGauge');
        const gaugeLabelEl = document.getElementById('acceptanceGaugeLabel');
        if (gaugeEl) {
            const targetPct = parseInt(gaugeEl.getAttribute('data-pct'), 10) || 0;
            let current = 0;
            const gaugeStart = performance.now();
            function tickGauge(now) {
                const progress = Math.min((now - gaugeStart) / 900, 1);
                current = Math.floor(targetPct * progress);
                gaugeEl.style.setProperty('--pct', current);
                gaugeLabelEl.textContent = current + '%';
                if (progress < 1) requestAnimationFrame(tickGauge);
                else {
                    gaugeEl.style.setProperty('--pct', targetPct);
                    gaugeLabelEl.textContent = targetPct + '%';
                }
            }
            requestAnimationFrame(tickGauge);
        }

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
                            'rgba(19, 80, 224, 0.12)',   // Blue
                            'rgba(10, 10, 10, 0.08)',    // Ink
                            'rgba(19, 80, 224, 0.22)'    // Blue, deeper
                        ],
                        borderColor: [
                            'rgb(19, 80, 224)',
                            'rgb(10, 10, 10)',
                            'rgb(13, 58, 168)'
                        ],
                        borderWidth: 1.5,
                        borderRadius: 2,
                        maxBarThickness: 56
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeOutCubic' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0a0a0a',
                            padding: 10,
                            titleFont: { family: 'Inter' },
                            bodyFont: { family: 'Inter' }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(10, 10, 10, 0.08)' },
                            ticks: { color: '#6b7280', precision: 0 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280' }
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
                            'rgba(19, 80, 224, 0.15)',   // Blue
                            'rgba(217, 119, 6, 0.15)'    // Amber
                        ],
                        borderColor: [
                            'rgb(19, 80, 224)',
                            'rgb(217, 119, 6)'
                        ],
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    animation: { duration: 900, easing: 'easeOutCubic' },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#6b7280', font: { size: 11 } }
                        },
                        tooltip: {
                            backgroundColor: '#0a0a0a',
                            padding: 10
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>