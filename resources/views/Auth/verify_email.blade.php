<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Confirm your identity. Enter the secure 6-digit verification code sent to your email to complete your registration.">
    <title>Verify Email</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root{
            --ink:#0a0a0a;
            --paper:#f5f4f1;
            --white:#ffffff;
            --blue:#1350e0;
            --blue-dark:#0d3aa8;
            --line: rgba(10,10,10,0.14);
        }
        body { font-family: 'Inter', sans-serif; }
        .display-font {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .swiss-panel { border-radius: 0; border: 1px solid var(--line); box-shadow: none; }
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
        .btn-swiss-primary:hover, .btn-swiss-primary:focus {
            background-color: var(--blue);
            border-color: var(--blue);
        }
        .btn-swiss-primary:disabled {
            background-color: #d4d4d4;
            border-color: #d4d4d4;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background: var(--paper);">
    <div class="swiss-panel max-w-md w-full space-y-8 bg-white p-8">
        <div>
            <h2 class="mt-2 text-center text-3xl display-font text-gray-900">
                Verify Your Email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent a 6-digit verification code to your registered email address.
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 relative" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- AJAX Banner Alerts -->
        <div id="ajax_success_msg" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative text-sm"></div>
        <div id="ajax_error_msg" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 relative text-sm"></div>

        <form class="mt-8 space-y-6" action="{{ route('verify.email') }}" method="POST">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                <input id="code" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required placeholder="123456" class="mt-1 appearance-none swiss-input relative block w-full px-3 py-2 placeholder-gray-400 text-gray-900 text-center tracking-widest text-lg font-bold sm:text-sm">
            </div>

            <div>
                <button type="submit" class="btn-swiss-primary group relative w-full flex justify-center py-2 px-4 text-sm font-medium text-white">
                    Verify Code and Complete Setup
                </button>
            </div>
        </form>

        <!-- Dynamic Ticking Resend Controller -->
        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <button type="button" id="resend_code_btn" class="text-sm font-bold transition duration-200" style="color: var(--blue);" onmouseover="this.style.color='var(--blue-dark)'" onmouseout="this.style.color='var(--blue)'">
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