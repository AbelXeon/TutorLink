<!-- GLOBAL SETTINGS MODAL OVERLAY -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap');
    :root{
        --ink:#0a0a0a;
        --paper:#f5f4f1;
        --white:#ffffff;
        --blue:#1350e0;
        --blue-dark:#0d3aa8;
        --line: rgba(10,10,10,0.14);
    }
    #settings_modal { font-family: 'Inter', sans-serif; }
    #settings_modal .display-font {
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    #settings_modal .swiss-panel { border-radius: 0; border: 1px solid var(--line); box-shadow: none; }
    #settings_modal .swiss-input {
        border-radius: 0;
        border: 1px solid var(--line);
        transition: border-color .15s ease;
    }
    #settings_modal .swiss-input:focus {
        outline: none;
        border-color: var(--ink);
        box-shadow: none;
    }
    #settings_modal .menu-btn {
        border-radius: 0;
        border: 1px solid var(--line);
        background: var(--white);
        transition: background-color .15s ease, border-color .15s ease;
    }
    #settings_modal .menu-btn:hover { background: var(--paper); border-color: var(--ink); }
    #settings_modal .menu-btn svg.leading { color: var(--blue); }
    #settings_modal .btn-swiss-primary {
        border-radius: 0;
        background-color: var(--ink);
        border: 1px solid var(--ink);
        transition: background-color .15s ease, border-color .15s ease;
    }
    #settings_modal .btn-swiss-primary:hover { background-color: var(--blue); border-color: var(--blue); }
    #settings_modal .btn-swiss-outline {
        border-radius: 0;
        border: 1px solid var(--line);
        color: #374151;
        background: var(--white);
        transition: background-color .15s ease;
    }
    #settings_modal .btn-swiss-outline:hover { background: var(--paper); }
    #settings_modal .btn-danger-outline {
        border-radius: 0;
        border: 1px solid #fecaca;
        color: #dc2626;
        background: #fef2f2;
        transition: background-color .15s ease;
    }
    #settings_modal .btn-danger-outline:hover { background: #fde8e8; }

    @media (max-width: 480px) {
        #settings_modal .modal-panel { padding: 1.5rem !important; }
        #settings_modal h2 { font-size: 1.5rem !important; }
    }
</style>

<div id="settings_modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm select-none transition duration-300">
    <div class="modal-panel swiss-panel bg-white w-full max-w-md p-8 relative" style="background: var(--white);">

        <!-- Close (X) Button -->
        <button type="button" id="close_settings_modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- SCREEN 1: MAIN SETTINGS MENU -->
        <div id="settings_screen_menu" class="space-y-6">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid var(--line);">
                <h2 class="text-2xl display-font text-gray-900">Account Settings</h2>
                <p class="text-xs text-gray-500 mt-1">Select an administrative task to modify your credentials.</p>
            </div>

            <div class="space-y-3">
                <button type="button" onclick="selectAction('username')" class="menu-btn w-full flex justify-between items-center px-4 py-3 text-sm font-semibold text-gray-800">
                    <span class="inline-flex items-center gap-2">
                        <svg class="leading w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
                        </svg>
                        Change Username
                    </span>
                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button type="button" onclick="selectAction('password')" class="menu-btn w-full flex justify-between items-center px-4 py-3 text-sm font-semibold text-gray-800">
                    <span class="inline-flex items-center gap-2">
                        <svg class="leading w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="4" y="10" width="16" height="10" rx="0.5"/>
                            <path d="M7 10V7a5 5 0 0 1 10 0v3" stroke-linecap="round"/>
                        </svg>
                        Change Password
                    </span>
                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div class="pt-6" style="border-top: 1px solid var(--line);">
                <!-- Secure Logout Form inside Settings Menu -->
                <button type="button" onclick="document.getElementById('settings_logout_form').submit()" class="btn-danger-outline w-full py-2.5 px-4 text-sm font-semibold text-center">
                    Logout Account
                </button>
            </div>
        </div>

        <!-- SCREEN 2: ENTER EMAIL FOR VERIFICATION -->
        <div id="settings_screen_email" class="hidden space-y-6">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid var(--line);">
                <h2 class="text-2xl display-font text-gray-900">Verify Identity</h2>
                <p class="text-sm text-gray-500 mt-1">Please enter your registered email address to request a verification code.</p>
            </div>

            <div id="email_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 text-xs font-semibold"></div>

            <form id="settings_email_form" action="{{ route('settings.send_code') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="selected_action" name="action">

                <div>
                    <label for="verification_email" class="block text-xs font-bold text-gray-700 uppercase">Registered Email</label>
                    <input id="verification_email" name="email" type="email" required placeholder="email@example.com" class="swiss-input mt-1 block w-full px-3 py-2 text-sm">
                </div>

                <div class="pt-4 flex justify-end gap-3" style="border-top: 1px solid var(--line);">
                    <button type="button" onclick="showScreen('menu')" class="btn-swiss-outline px-4 py-2 text-xs font-semibold">
                        Back
                    </button>
                    <button type="submit" class="btn-swiss-primary px-6 py-2 text-xs font-semibold text-white">
                        Request Code
                    </button>
                </div>
            </form>
        </div>

        <!-- SCREEN 3: ENTER 6-DIGIT CODE -->
        <div id="settings_screen_code" class="hidden space-y-6">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid var(--line);">
                <h2 class="text-2xl display-font text-gray-900">Enter Code</h2>
                <p class="text-sm text-gray-500 mt-1">We've sent a 6-digit code to your inbox. Please enter it below.</p>
            </div>

            <div id="code_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 text-xs font-semibold"></div>

            <form id="settings_code_form" action="{{ route('settings.verify_code') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input id="settings_code" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required placeholder="123456" class="swiss-input appearance-none relative block w-full px-3 py-2 placeholder-gray-400 text-gray-900 text-center tracking-widest text-lg font-bold">
                </div>

                <!-- Resend Code Link with Ticking Countdown Timer -->
                <div class="text-center">
                    <button type="button" id="resend_code_btn" class="text-xs font-bold disabled:text-gray-400 disabled:cursor-not-allowed" style="color: var(--blue);">
                        Resend Code
                    </button>
                    <span id="resend_timer_text" class="text-xs text-gray-500 ml-1 hidden"></span>
                </div>

                <div class="pt-4 flex justify-end gap-3" style="border-top: 1px solid var(--line);">
                    <button type="button" onclick="showScreen('email')" class="btn-swiss-outline px-4 py-2 text-xs font-semibold">
                        Back
                    </button>
                    <button type="submit" class="btn-swiss-primary px-6 py-2 text-xs font-semibold text-white">
                        Verify Code
                    </button>
                </div>
            </form>
        </div>

        <!-- SCREEN 4: UPDATE USERNAME FORM -->
        <div id="settings_screen_username" class="hidden space-y-6">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid var(--line);">
                <h2 class="text-2xl display-font text-gray-900">Change Username</h2>
                <p class="text-sm text-gray-500 mt-1">Identity verified. You can now securely choose a new username.</p>
            </div>

            <div id="username_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 text-xs font-semibold"></div>

            <form id="settings_username_form" action="{{ route('settings.update_username') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="new_username" class="block text-xs font-bold text-gray-700 uppercase">New Username</label>
                    <input id="new_username" name="username" type="text" required value="{{ Auth::user()->username }}" class="swiss-input mt-1 block w-full px-3 py-2 text-sm">
                </div>

                <div class="pt-4 flex justify-end" style="border-top: 1px solid var(--line);">
                    <button type="submit" class="btn-swiss-primary py-2 px-6 text-xs font-semibold text-white">
                        Apply Username Change
                    </button>
                </div>
            </form>
        </div>

        <!-- SCREEN 5: UPDATE PASSWORD FORM -->
        <div id="settings_screen_password" class="hidden space-y-6">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid var(--line);">
                <h2 class="text-2xl display-font text-gray-900">Change Password</h2>
                <p class="text-sm text-gray-500 mt-1">Identity verified. You can now securely configure a new account password.</p>
            </div>

            <div id="password_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 text-xs font-semibold"></div>

            <form id="settings_password_form" action="{{ route('settings.update_password') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-xs font-bold text-gray-700 uppercase">New Password</label>
                        <input id="new_password" name="password" type="password" required class="swiss-input mt-1 block w-full px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold text-gray-700 uppercase">Confirm New</label>
                        <input id="new_password_confirmation" name="password_confirmation" type="password" required class="swiss-input mt-1 block w-full px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="pt-4 flex justify-end" style="border-top: 1px solid var(--line);">
                    <button type="submit" class="btn-swiss-primary py-2 px-6 text-xs font-semibold text-white">
                        Apply Password Change
                    </button>
                </div>
            </form>
        </div>

        <!-- Hidden Logout Form -->
        <form id="settings_logout_form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

    </div>
</div>

<!-- JAVASCRIPT WIZARD CONTROLLER -->
<script>
    const setModal = document.getElementById('settings_modal');
    const openSetBtn = document.getElementById('open_settings_btn');
    const closeSetBtn = document.getElementById('close_settings_modal');

    const screenMenu = document.getElementById('settings_screen_menu');
    const screenEmail = document.getElementById('settings_screen_email');
    const screenCode = document.getElementById('settings_screen_code');
    const screenUsername = document.getElementById('settings_screen_username');
    const screenPassword = document.getElementById('settings_screen_password');

    const emailForm = document.getElementById('settings_email_form');
    const codeForm = document.getElementById('settings_code_form');
    const usernameForm = document.getElementById('settings_username_form');
    const passwordForm = document.getElementById('settings_password_form');

    const emailError = document.getElementById('email_error');
    const codeError = document.getElementById('code_error');
    const usernameError = document.getElementById('username_error');
    const passwordError = document.getElementById('password_error');

    const actionInput = document.getElementById('selected_action');

    // Resend Timer elements
    const resendBtn = document.getElementById('resend_code_btn');
    const resendTimerText = document.getElementById('resend_timer_text');
    let countdownInterval;

    openSetBtn.addEventListener('click', () => {
        setModal.classList.remove('hidden');
        showScreen('menu');
    });

    closeSetBtn.addEventListener('click', () => {
        setModal.classList.add('hidden');
    });

    setModal.addEventListener('click', (e) => {
        if (e.target === setModal) {
            setModal.classList.add('hidden');
        }
    });

    function showScreen(screenName) {
        screenMenu.classList.add('hidden');
        screenEmail.classList.add('hidden');
        screenCode.classList.add('hidden');
        screenUsername.classList.add('hidden');
        screenPassword.classList.add('hidden');

        emailError.classList.add('hidden');
        codeError.classList.add('hidden');
        usernameError.classList.add('hidden');
        passwordError.classList.add('hidden');

        if (screenName === 'menu') screenMenu.classList.remove('hidden');
        if (screenName === 'email') screenEmail.classList.remove('hidden');
        if (screenName === 'code') {
            screenCode.classList.remove('hidden');
            startResendTimer(60); 
        }
        if (screenName === 'username') screenUsername.classList.remove('hidden');
        if (screenName === 'password') screenPassword.classList.remove('hidden');
    }

    function selectAction(actionType) {
        actionInput.value = actionType; 
        showScreen('email');
    }

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

    // Trigger Resend Code manually
    resendBtn.addEventListener('click', function() {
        codeError.classList.add('hidden');
        
        const formData = new FormData(emailForm);

        fetch(emailForm.getAttribute('action'), {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                startResendTimer(60); 
            } else {
                codeError.textContent = res.body.message || "Failed to resend code.";
                codeError.classList.remove('hidden');
            }
        })
        .catch(err => console.error(err));
    });

    // STEP 1: Request 6-Digit Email Code via AJAX
    emailForm.addEventListener('submit', function(e) {
        e.preventDefault();
        emailError.classList.add('hidden');

        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                showScreen('code');
            } else {
                emailError.textContent = res.body.message || "Email address verification failed.";
                emailError.classList.remove('hidden');
            }
        })
        .catch(err => console.error(err));
    });

    // STEP 2: Verify 6-Digit Code via AJAX
    codeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        codeError.classList.add('hidden');

        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                showScreen(res.body.action); 
            } else {
                codeError.textContent = res.body.message || "Invalid or expired code.";
                codeError.classList.remove('hidden');
            }
        })
        .catch(err => console.error(err));
    });

    // STEP 3A: Update Username
    usernameForm.addEventListener('submit', function(e) {
        e.preventDefault();
        usernameError.classList.add('hidden');

        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                window.location.reload();
            } else {
                usernameError.textContent = res.body.message || "Failed to update username.";
                usernameError.classList.remove('hidden');
            }
        })
        .catch(err => console.error(err));
    });

    // STEP 3B: Update Password
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        passwordError.classList.add('hidden');

        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                window.location.reload();
            } else {
                passwordError.textContent = res.body.message || "Failed to update password.";
                passwordError.classList.remove('hidden');
            }
        })
        .catch(err => console.error(err));
    });
</script>