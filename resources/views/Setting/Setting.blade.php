<!-- GLOBAL SETTINGS MODAL OVERLAY -->
<div id="settings_modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm select-none transition duration-300">
    <div class="bg-white rounded-lg shadow-xl border border-gray-200 w-full max-w-md p-8 relative">
        
        <!-- Close (X) Button -->
        <button type="button" id="close_settings_modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- SCREEN 1: MAIN SETTINGS MENU -->
        <div id="settings_screen_menu" class="space-y-6">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h2 class="text-2xl font-extrabold text-gray-900">Account Settings</h2>
                <p class="text-xs text-gray-500 mt-1">Select an administrative task to modify your credentials.</p>
            </div>

            <div class="space-y-3">
                <button type="button" onclick="selectAction('username')" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-md border border-gray-200 text-sm font-semibold text-gray-800 transition">
                    <span>👤 Change Username</span>
                    <span>&rarr;</span>
                </button>
                <button type="button" onclick="selectAction('password')" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-md border border-gray-200 text-sm font-semibold text-gray-800 transition">
                    <span>🔑 Change Password</span>
                    <span>&rarr;</span>
                </button>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <!-- Secure Logout Form inside Settings Menu -->
                <button type="button" onclick="document.getElementById('settings_logout_form').submit()" class="w-full py-2.5 px-4 rounded-md text-sm font-semibold text-center text-red-600 border border-red-200 bg-red-50 hover:bg-red-100/50 transition">
                    Logout Account
                </button>
            </div>
        </div>

        <!-- SCREEN 2: ENTER EMAIL FOR VERIFICATION -->
        <div id="settings_screen_email" class="hidden space-y-6">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h2 class="text-2xl font-extrabold text-gray-900">Verify Identity</h2>
                <p class="text-sm text-gray-500 mt-1">Please enter your registered email address to request a verification code.</p>
            </div>

            <div id="email_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-xs font-semibold"></div>

            <form id="settings_email_form" action="{{ route('settings.send_code') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="selected_action" name="action">
                
                <div>
                    <label for="verification_email" class="block text-xs font-bold text-gray-700 uppercase">Registered Email</label>
                    <input id="verification_email" name="email" type="email" required placeholder="email@example.com" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500">
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" onclick="showScreen('menu')" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        Back
                    </button>
                    <button type="submit" class="px-6 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Request Code
                    </button>
                </div>
            </form>
        </div>

        <!-- SCREEN 3: ENTER 6-DIGIT CODE -->
        <div id="settings_screen_code" class="hidden space-y-6">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h2 class="text-2xl font-extrabold text-gray-900">Enter Code</h2>
                <p class="text-sm text-gray-500 mt-1">We've sent a 6-digit code to your inbox. Please enter it below.</p>
            </div>

            <div id="code_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-xs font-semibold"></div>

            <form id="settings_code_form" action="{{ route('settings.verify_code') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input id="settings_code" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required placeholder="123456" class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 text-gray-900 text-center tracking-widest text-lg font-bold focus:outline-none focus:ring-indigo-500">
                </div>

                <!-- Resend Code Link with Ticking Countdown Timer -->
                <div class="text-center">
                    <button type="button" id="resend_code_btn" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 disabled:text-gray-400 disabled:cursor-not-allowed">
                        Resend Code
                    </button>
                    <span id="resend_timer_text" class="text-xs text-gray-500 ml-1 hidden"></span>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" onclick="showScreen('email')" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        Back
                    </button>
                    <button type="submit" class="px-6 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Verify Code
                    </button>
                </div>
            </form>
        </div>

        <!-- SCREEN 4: UPDATE USERNAME FORM -->
        <div id="settings_screen_username" class="hidden space-y-6">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h2 class="text-2xl font-extrabold text-gray-900">Change Username</h2>
                <p class="text-sm text-gray-500 mt-1">Identity verified. You can now securely choose a new username.</p>
            </div>

            <div id="username_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-xs font-semibold"></div>

            <form id="settings_username_form" action="{{ route('settings.update_username') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="new_username" class="block text-xs font-bold text-gray-700 uppercase">New Username</label>
                    <input id="new_username" name="username" type="text" required value="{{ Auth::user()->username }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500">
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="py-2 px-6 rounded-md text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Apply Username Change
                    </button>
                </div>
            </form>
        </div>

        <!-- SCREEN 5: UPDATE PASSWORD FORM -->
        <div id="settings_screen_password" class="hidden space-y-6">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h2 class="text-2xl font-extrabold text-gray-900">Change Password</h2>
                <p class="text-sm text-gray-500 mt-1">Identity verified. You can now securely configure a new account password.</p>
            </div>

            <div id="password_error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-xs font-semibold"></div>

            <form id="settings_password_form" action="{{ route('settings.update_password') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-xs font-bold text-gray-700 uppercase">New Password</label>
                        <input id="new_password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold text-gray-700 uppercase">Confirm New</label>
                        <input id="new_password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="py-2 px-6 rounded-md text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
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