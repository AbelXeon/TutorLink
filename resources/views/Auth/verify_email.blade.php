<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify Your Email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent a 6-digit verification code to your registered email address.
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- AJAX Banner Alerts -->
        <div id="ajax_success_msg" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm"></div>
        <div id="ajax_error_msg" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm"></div>

        <form class="mt-8 space-y-6" action="{{ route('verify.email') }}" method="POST">
            @csrf
            
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                    <input id="code" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required placeholder="123456" class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 text-gray-900 text-center tracking-widest text-lg font-bold focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Verify Code and Complete Setup
                </button>
            </div>
        </form>

        <!-- Dynamic Ticking Resend Controller -->
        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <button type="button" id="resend_code_btn" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 disabled:text-gray-400 disabled:cursor-not-allowed transition duration-200">
                Resend Verification Code
            </button>
            <span id="resend_timer_text" class="text-sm text-gray-500 ml-1 hidden"></span>
        </div>
    </div>

    <!-- TIMER ENGINE & AJAX BRIDGE -->
    <script>
        const resendBtn = document.getElementById('resend_code_btn');
        const resendTimerText = document.getElementById('resend_timer_text');
        const ajaxSuccess = document.getElementById('ajax_success_msg');
        const ajaxError = document.getElementById('ajax_error_msg');
        let countdownInterval;

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

        // Auto-run 60-second cooldown immediately when they load the page to prevent spamming
        startResendTimer(60);

        resendBtn.addEventListener('click', function() {
            ajaxSuccess.classList.add('hidden');
            ajaxError.classList.add('hidden');

            fetch("{{ route('verify.email.resend') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    ajaxSuccess.textContent = res.body.message;
                    ajaxSuccess.classList.remove('hidden');
                    startResendTimer(60); // Reset cooldown to 60 seconds on success
                } else {
                    ajaxError.textContent = res.body.message || "Failed to resend code.";
                    ajaxError.classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                ajaxError.textContent = "A connection error occurred. Please try again.";
                ajaxError.classList.remove('hidden');
            });
        });
    </script>
</body>
</html>