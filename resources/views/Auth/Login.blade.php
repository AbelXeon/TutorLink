<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Log in to your secure TutorLink account. Access your dashboard, manage lesson schedules, or message your tutor.">
    <title>Login - TutorLink</title>

    <!-- Swiss-style type pairing: Bebas Neue (display) + Inter (body/utility) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        html { overflow-x: hidden; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .display-font {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .swiss-panel {
            border-radius: 0;
            border: 1px solid var(--line);
            box-shadow: none;
        }
        .swiss-input {
            border-radius: 0;
            border: 1px solid var(--line);
        }
        .swiss-input:focus {
            outline: none;
            border-color: var(--ink);
            box-shadow: none;
        }
        .btn-swiss-primary {
            border-radius: 0;
            background-color: var(--ink);
            border: 1px solid var(--ink);
            transition: background-color .15s ease, border-color .15s ease;
        }
        .btn-swiss-primary:hover,
        .btn-swiss-primary:focus {
            background-color: var(--blue);
            border-color: var(--blue);
        }
        .role-link {
            border-radius: 0;
            transition: background-color .15s ease, color .15s ease, border-color .15s ease;
        }
        .role-link.role-student {
            color: var(--ink);
            border: 1px solid var(--line);
        }
        .role-link.role-student:hover {
            background-color: var(--ink);
            color: var(--white);
        }
        .role-link.role-tutor {
            color: var(--blue);
            border: 1px solid var(--blue);
        }
        .role-link.role-tutor:hover {
            background-color: var(--blue);
            color: var(--white);
        }
        .eye-toggle {
            color: #9a9a9a;
            transition: color .15s ease;
        }
        .eye-toggle:hover { color: var(--ink); }

        /* Left narrative panel — same system as the registration pages */
        .story-panel {
            background: var(--ink);
            position: relative;
            overflow: hidden;
        }
        .story-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.045) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .story-ring {
            position: absolute;
            right: -140px;
            bottom: -140px;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            border: 1.5px solid rgba(19,80,224,0.4);
            background: radial-gradient(circle at 35% 30%, rgba(19,80,224,0.16), transparent 70%);
        }
        .story-ring-small {
            position: absolute;
            right: 40px;
            bottom: 60px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 1px solid rgba(19,80,224,0.5);
        }
        .story-content { position: relative; z-index: 1; }
        .benefit-item svg { color: var(--blue); }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between" style="background: var(--paper);">

    <main class="flex-grow">
    <div class="lg:grid lg:grid-cols-2">

        <!-- LEFT: narrative / brand panel — desktop only -->
        <div class="story-panel hidden lg:flex lg:flex-col lg:justify-between p-12 xl:p-16">
            <div class="story-grid"></div>
            <div class="story-ring"></div>
            <div class="story-ring-small"></div>

            <div class="story-content">
                <p class="text-xs uppercase tracking-widest" style="color:#7d92c9;">TutorLink / Welcome Back</p>
                <h1 class="display-font text-white mt-3" style="font-size: clamp(2.6rem, 4.2vw, 3.6rem); line-height: 0.95;">
                    Pick Up<br>Where You<br><span style="color: var(--blue);">Left Off.</span>
                </h1>
                <p class="mt-6 text-sm leading-relaxed" style="color:#b5b5b5; max-width: 34ch;">
                    Sign in to reach your dashboard, manage lesson schedules, and message your tutor or student directly.
                </p>
            </div>

            <div class="story-content space-y-5 mt-10">
                <div class="benefit-item flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                    <span class="text-sm text-white">All your lessons, in one dashboard</span>
                </div>
                <div class="benefit-item flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                    <span class="text-sm text-white">Message your tutor or student directly</span>
                </div>
                <div class="benefit-item flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                    <span class="text-sm text-white">Track upcoming sessions at a glance</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: the login form -->
        <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-10 xl:px-16">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl display-font text-gray-900">
                    Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 font-medium">
                    Welcome back to TutorLink
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                <!-- CSRF Protection Token: Crucial for security against Cross-Site Request Forgery -->
                @csrf

                <div class="space-y-4">
                    <!-- Username / Email Input -->
                    <div>
                        <label for="login_input" class="block text-sm font-medium text-gray-700">Email Address or Username</label>
                        <input id="login_input" name="login_input" type="text" required value="{{ old('login_input') }}" placeholder="username or email@example.com" class="mt-1 appearance-none swiss-input relative block w-full px-3 py-2 placeholder-gray-400 text-gray-900 sm:text-sm">
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative mt-1">
                            <input id="password" name="password" type="password" required class="appearance-none swiss-input relative block w-full px-3 py-2 pr-10 placeholder-gray-400 text-gray-900 sm:text-sm">
                            <!-- Eye toggle: show/hide password. Purely front-end, does not touch the submitted value. -->
                            <button type="button" id="togglePassword" aria-label="Show password" aria-pressed="false" class="eye-toggle absolute inset-y-0 right-0 flex items-center px-3">
                                <svg id="eyeIconShow" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12C1 12 5 5 12 5C19 5 23 12 23 12C23 12 19 19 12 19C5 19 1 12 1 12Z" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="eyeIconHide" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 3L21 21" stroke-linecap="round"/>
                                    <path d="M10.6 5.1C11.05 5.03 11.52 5 12 5C19 5 23 12 23 12C23 12 21.9 13.9 20 15.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6.6 6.6C3.4 8.5 1 12 1 12C1 12 5 19 12 19C13.9 19 15.5 18.5 16.9 17.7" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.9 9.9A3 3 0 0 0 14.1 14.1" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Remember Me option -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 border-gray-300" style="accent-color: var(--ink);">
                        <label for="remember" class="ml-2 block text-sm text-gray-900 select-none">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium" style="color: var(--blue);">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="btn-swiss-primary group relative w-full flex justify-center py-2 px-4 text-sm font-medium text-white">
                        Sign In
                    </button>
                </div>
            </form>

            <!-- UPDATED SECTION: Structured Registration Selector Links -->
            <div class="text-center mt-6 border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-600 mb-3">Don't have an account? Register as:</p>
                <div class="flex justify-center gap-4">
                    <!-- Register as Student Link -->
                    <a href="{{ route('Auth.Student_Register') }}" class="role-link role-student inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 shadow-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3L2 8L12 13L22 8L12 3Z" stroke-linejoin="round"/>
                            <path d="M6 10.5V16C6 16 8.5 19 12 19C15.5 19 18 16 18 16V10.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Student
                    </a>
                    <!-- Register as Teacher Link -->
                    <a href="{{ route('Auth.Teacher_Register') }}" class="role-link role-tutor inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 shadow-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="12" rx="0.5"/>
                            <path d="M3 13H21" stroke-linecap="round"/>
                            <path d="M7 20H17" stroke-linecap="round"/>
                        </svg>
                        Teacher
                    </a>
                </div>
            </div>
        </div>
        </div>

    </div>
    </main>

    @include('Layouts.Footer')

    <script>
        // Password show/hide toggle — purely presentational, does not alter
        // the form field's name/value, so nothing submitted to the backend changes.
        (function () {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const iconShow = document.getElementById('eyeIconShow');
            const iconHide = document.getElementById('eyeIconHide');

            toggleBtn.addEventListener('click', function () {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                iconShow.classList.toggle('hidden', isHidden);
                iconHide.classList.toggle('hidden', !isHidden);
                toggleBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                toggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            });
        })();
    </script>

</body>
</html>