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
                <!-- CSRF Protection Token -->
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
                            <!-- Eye toggle -->
                            <button type="button" id="togglePassword" aria-label="Show password" aria-pressed="false" class="eye-toggle absolute inset-y-0 right-0 flex items-center px-3">
                                <svg id="eyeIconShow" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12C1 12 5 5 12 5C19 5 24 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <svg class="h-6 w-6 hidden" id="eye-closed-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-11-7-11-7a21.83 21.83 0 012.185-3.063m3.036-2.906A10.05 10.05 0 0112 5c7 0 11 7 11 7a21.88 21.88 0 01-2.91 4.218m-5.19-1.114a2.915 2.915 0 11-4.122-4.122m-1.42 1.42L3 3m18 18L3 3" />
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
                        <!-- Clicking now triggers the in-page Swiss modal overlay -->
                        <button type="button" onclick="openForgotModal()" class="font-medium focus:outline-none" style="color: var(--blue);">
                            Forgot your password?
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="btn-swiss-primary group relative w-full flex justify-center py-2 px-4 text-sm font-medium text-white">
                        Sign In
                    </button>
                </div>
            </form>

            <!-- Registration Selector Links -->
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

 @include('Layouts.foot')
    <!-- FORGOT PASSWORD SWISS MODAL OVERLAY (Backdrop Updated to Blur/Blue styled overlay) -->
    <div id="forgot_modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md select-none transition duration-300">
        <div class="bg-white rounded-none border border-gray-200 w-full max-w-md p-8 relative shadow-2xl">
            
            <!-- Close (X) Button -->
            <button type="button" onclick="closeForgotModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- SCREEN 1: REQUEST CODE (Email Input) -->
            <div id="forgot_screen_email" class="space-y-6">
                <div class="border-b border-gray-150 pb-4 mb-4">
                    <h2 class="text-2xl display-font text-gray-900">Reset Password</h2>
                    <p class="text-xs text-gray-500 mt-1">Please enter your registered email address to request a security verification code.</p>
                </div>

                <div id="forgot_email_err" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-none text-xs font-semibold"></div>

                <form id="forgot_email_form" action="{{ route('password.send_code') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="forgot_email" class="block text-xs font-bold text-gray-700 uppercase">Registered Email</label>
                        <input id="forgot_email" name="email" type="email" required placeholder="email@example.com" class="mt-1.5 block w-full px-3 py-2 swiss-input text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" onclick="closeForgotModal()" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition rounded-none">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 text-xs font-bold text-white btn-swiss-primary transition rounded-none">
                            Request Code
                        </button>
                    </div>
                </form>
            </div>

            <!-- SCREEN 2: VERIFY CODE (6-Digit Input) -->
            <div id="forgot_screen_code" class="hidden space-y-6">
                <div class="border-b border-gray-150 pb-4 mb-4">
                    <h2 class="text-2xl display-font text-gray-900">Enter Code</h2>
                    <p class="text-xs text-gray-500 mt-1">We've sent a 6-digit code to your email. Enter it below to unlock password modification.</p>
                </div>

                <div id="forgot_code_err" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-none text-xs font-semibold"></div>

                <form id="forgot_code_form" action="{{ route('password.verify_code') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <input id="forgot_code_val" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required placeholder="123456" class="appearance-none swiss-input relative block w-full px-3 py-2 placeholder-gray-400 text-gray-900 text-center tracking-widest text-lg font-bold focus:outline-none">
                    </div>

                    <!-- NEW: Resend Code Button with 60-Second Cooldown Timer -->
                    <div class="text-center pt-2">
                        <button type="button" id="forgot_resend_btn" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 disabled:text-gray-400 disabled:cursor-not-allowed transition duration-200">
                            Resend Code
                        </button>
                        <span id="forgot_resend_timer" class="text-xs text-gray-500 ml-1 hidden"></span>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" onclick="showForgotScreen('email')" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition rounded-none">
                            Back
                        </button>
                        <button type="submit" class="px-6 py-2 text-xs font-bold text-white btn-swiss-primary transition rounded-none">
                            Verify Code
                        </button>
                    </div>
                </form>
            </div>

            <!-- SCREEN 3: UPDATE PASSWORD (Unlocked) -->
            <div id="forgot_screen_reset" class="hidden space-y-6">
                <div class="border-b border-gray-150 pb-4 mb-4">
                    <h2 class="text-2xl display-font text-gray-900">Set New Password</h2>
                    <p class="text-xs text-gray-500 mt-1">Identity verified. You can now securely configure a new login password.</p>
                </div>

                <div id="forgot_reset_err" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-none text-xs font-semibold"></div>

                <form id="forgot_reset_form" action="{{ route('password.update_reset') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="forgot_password_new" class="block text-xs font-bold text-gray-700 uppercase">New Password</label>
                            <input id="forgot_password_new" name="password" type="password" required class="mt-1.5 block w-full px-3 py-2 swiss-input text-sm focus:outline-none">
                        </div>
                        <div>
                            <label for="forgot_password_confirm" class="block text-xs font-bold text-gray-700 uppercase">Confirm Password</label>
                            <input id="forgot_password_confirm" name="password_confirmation" type="password" required class="mt-1.5 block w-full px-3 py-2 swiss-input text-sm focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="py-2.5 px-6 rounded-none text-xs font-bold text-white btn-swiss-primary transition">
                            Apply New Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- JAVASCRIPT: PASSWORD VISIBILITY TOGGLE & SWISS FORGOT MODAL WIZARD -->
    <script>
        (function () {
            // 1. Password Visibility Toggle
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

            // 2. Forgot Password Swiss Modal Wizard Setup
            const forgotModal = document.getElementById('forgot_modal');
            const scrEmail = document.getElementById('forgot_screen_email');
            const scrCode = document.getElementById('forgot_screen_code');
            const scrReset = document.getElementById('forgot_screen_reset');

            const formEmail = document.getElementById('forgot_email_form');
            const formCode = document.getElementById('forgot_code_form');
            const formReset = document.getElementById('forgot_reset_form');

            const errEmail = document.getElementById('forgot_email_err');
            const errCode = document.getElementById('forgot_code_err');
            const errReset = document.getElementById('forgot_reset_err');

            const resendBtn = document.getElementById('forgot_resend_btn');
            const resendTimerText = document.getElementById('forgot_resend_timer');
            let countdownInterval;

            window.openForgotModal = function() {
                forgotModal.classList.remove('hidden');
                showForgotScreen('email');
            }

            window.closeForgotModal = function() {
                forgotModal.classList.add('hidden');
            }

            forgotModal.addEventListener('click', (e) => {
                if (e.target === forgotModal) {
                    closeForgotModal();
                }
            });

            window.showForgotScreen = function(screen) {
                scrEmail.classList.add('hidden');
                scrCode.classList.add('hidden');
                scrReset.classList.add('hidden');

                errEmail.classList.add('hidden');
                errCode.classList.add('hidden');
                errReset.classList.add('hidden');

                if (screen === 'email') scrEmail.classList.remove('hidden');
                if (screen === 'code') {
                    scrCode.classList.remove('hidden');
                    startResendTimer(60); // Auto-start 60-second lock when the code screen first opens
                }
                if (screen === 'reset') scrReset.classList.remove('hidden');
            }

            // Ticking Cooldown Timer logic
            function startResendTimer(seconds) {
                clearInterval(countdownInterval);
                resendBtn.disabled = true;
                resendTimerText.classList.remove('hidden');
                resendTimerText.textContent = `(Wait ${seconds}s)`;

                countdownInterval = setInterval(() => {
                    seconds--;
                    resendTimerText.textContent = `(Wait ${seconds}s)`;

                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        resendBtn.disabled = false;
                        resendTimerText.classList.add('hidden');
                    }
                }, 1000);
            }

            // AJAX Resend Code Trigger
            resendBtn.addEventListener('click', function() {
                errCode.classList.add('hidden');
                
                // Emulate submission of the email form to trigger resending
                const formData = new FormData(formEmail);

                fetch(formEmail.getAttribute('action'), {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(res => {
                    if (res.status === 200) {
                        startResendTimer(60); // Restart 60s cooldown timer
                    } else {
                        errCode.textContent = res.body.message || "Failed to resend code.";
                        errCode.classList.remove('hidden');
                    }
                })
                .catch(err => console.error(err));
            });

            // Screen 1: Submit Email
            formEmail.addEventListener('submit', function(e) {
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
                        showForgotScreen('code');
                    } else {
                        errEmail.textContent = res.body.message || "Request failed. Please verify your email.";
                        errEmail.classList.remove('hidden');
                    }
                })
                .catch(err => console.error(err));
            });

            // Screen 2: Submit Verification Code
            formCode.addEventListener('submit', function(e) {
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
                        showForgotScreen('reset');
                    } else {
                        errCode.textContent = res.body.message || "Invalid or expired verification code.";
                        errCode.classList.remove('hidden');
                    }
                })
                .catch(err => console.error(err));
            });

            // Screen 3: Submit New Password
            formReset.addEventListener('submit', function(e) {
                e.preventDefault();
                errReset.classList.add('hidden');

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
                        errReset.textContent = res.body.message || "Failed to update password.";
                        errReset.classList.remove('hidden');
                    }
                })
                .catch(err => console.error(err));
            });

        })();
    </script>

</body>
</html>