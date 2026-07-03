<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register as a tutor on TutorLink. Share your knowledge, manage your schedules, and earn income teaching students in Ethiopia.">
    <title>Teacher Registration</title>

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

        /* Left narrative panel */
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

        /* Form side */
        .swiss-input, .swiss-select {
            border-radius: 0;
            border: 1px solid var(--line);
            transition: border-color .15s ease;
        }
        .swiss-input:focus, .swiss-select:focus {
            outline: none;
            border-color: var(--ink);
            box-shadow: none;
        }
        .swiss-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: var(--white);
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%230a0a0a' stroke-width='2'><path d='M6 9l6 6 6-6' stroke-linecap='round' stroke-linejoin='round'/></svg>");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 13px;
            padding-right: 2.25rem;
            cursor: pointer;
        }
        .swiss-select:disabled {
            background-color: var(--paper);
            color: #9a9a9a;
            cursor: not-allowed;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23b0b0b0' stroke-width='2'><path d='M6 9l6 6 6-6' stroke-linecap='round' stroke-linejoin='round'/></svg>");
        }
        .field-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9a9a9a;
            pointer-events: none;
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
        .eye-toggle { color: #9a9a9a; transition: color .15s ease; }
        .eye-toggle:hover { color: var(--ink); }

        .strength-seg {
            height: 5px;
            background-color: #e5e5e5;
            transition: background-color .2s ease;
        }
        #strengthLabel { font-size: 0.75rem; }

        @media (max-width: 420px) {
            .name-grid { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between" style="background: var(--paper);">
<main class="flex-grow">
<div class="lg:grid lg:grid-cols-2">

    <!-- LEFT: narrative / brand panel — desktop only, this is what replaces "empty scroll space" -->
    <div class="story-panel hidden lg:flex lg:flex-col lg:justify-between p-12 xl:p-16">
        <div class="story-grid"></div>
        <div class="story-ring"></div>
        <div class="story-ring-small"></div>

        <div class="story-content">
            <p class="text-xs uppercase tracking-widest" style="color:#7d92c9;">TutorLink / Teachers</p>
            <h1 class="display-font text-white mt-3" style="font-size: clamp(2.6rem, 4.2vw, 3.6rem); line-height: 0.95;">
                Teach.<br>Earn.<br><span style="color: var(--blue);">Your way.</span>
            </h1>
            <p class="mt-6 text-sm leading-relaxed" style="color:#b5b5b5; max-width: 34ch;">
                Share your expertise with students across Ethiopia. Set your own rates, control your own schedule, and build an independent tutoring income.
            </p>
        </div>

        <div class="story-content space-y-5 mt-10">
            <div class="benefit-item flex items-start gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
                <span class="text-sm text-white">Get verified once, then start accepting students</span>
            </div>
            <div class="benefit-item flex items-start gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
                <span class="text-sm text-white">Set your own hourly rate and availability</span>
            </div>
            <div class="benefit-item flex items-start gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
                <span class="text-sm text-white">Teach academics, languages, or programming</span>
            </div>
        </div>
    </div>

    <!-- RIGHT: the actual form -->
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-10 xl:px-16">
    <div class="max-w-xl w-full">

        <div class="mb-8">
            <p class="text-xs uppercase tracking-widest" style="color: var(--blue);">Create Account</p>
            <h2 class="display-font text-3xl text-gray-900 mt-1">Register as a Teacher</h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 relative mb-6" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-5" action="{{ route('register.teacher') }}" method="POST">
            @csrf

            <div class="name-grid grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
                        </svg>
                        <input id="first_name" name="first_name" type="text" required value="{{ old('first_name') }}" class="appearance-none swiss-input block w-full pl-10 pr-3 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
                        </svg>
                        <input id="last_name" name="last_name" type="text" required value="{{ old('last_name') }}" class="appearance-none swiss-input block w-full pl-10 pr-3 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name (Optional)</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
                        </svg>
                        <input id="middle_name" name="middle_name" type="text" value="{{ old('middle_name') }}" class="appearance-none swiss-input block w-full pl-10 pr-3 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h4l2 5-2.5 1.5a12 12 0 0 0 6 6L15 14l5 2v4c0 1-1 2-2 2C10 22 2 14 2 6c0-1 1-2 2-2Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <input id="phone_number" name="phone_number" type="text" required value="{{ old('phone_number') }}" class="appearance-none swiss-input block w-full pl-10 pr-3 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Must be unique. Cannot be shared.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M15.5 12v1.5a2.5 2.5 0 0 0 5 0V12a8.5 8.5 0 1 0-3.5 6.9" stroke-linecap="round"/>
                        </svg>
                        <input id="username" name="username" type="text" required value="{{ old('username') }}" class="appearance-none swiss-input block w-full pl-10 pr-3 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">5–16 characters, unique.</p>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="5" width="18" height="14" rx="0.5"/>
                            <path d="M3 6.5L12 13L21 6.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="appearance-none swiss-input block w-full pl-10 pr-3 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- 1. Location Selection Dropdown -->
                <div>
                    <label for="location_id" class="block text-sm font-medium text-gray-700">Location (City/Region)</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 21C12 21 19 14.5 19 9.5C19 5.4 15.9 2 12 2C8.1 2 5 5.4 5 9.5C5 14.5 12 21 12 21Z" stroke-linejoin="round"/>
                            <circle cx="12" cy="9.5" r="2.5"/>
                        </svg>
                        <select id="location_id" name="location_id" required class="swiss-select block w-full pl-10 pr-8 py-2 sm:text-sm">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 2. Address Dropdown (Structured options based on the chosen Location) -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address (District/Area)</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 21V8L12 3L20 8V21" stroke-linejoin="round"/>
                            <path d="M9 21V13H15V21" stroke-linejoin="round"/>
                        </svg>
                        <select id="address" name="address" required disabled class="swiss-select block w-full pl-10 pr-8 py-2 sm:text-sm">
                            <option value="">Please select a Location first</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                    <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="4" y="10" width="16" height="10" rx="0.5"/>
                        <path d="M7 10V7a5 5 0 0 1 10 0v3" stroke-linecap="round"/>
                    </svg>
                    <input id="password" name="password" type="password" required class="appearance-none swiss-input block w-full pl-10 pr-10 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
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
                <div class="mt-2">
                    <div class="flex gap-1">
                        <span class="strength-seg flex-1" data-seg="1"></span>
                        <span class="strength-seg flex-1" data-seg="2"></span>
                        <span class="strength-seg flex-1" data-seg="3"></span>
                        <span class="strength-seg flex-1" data-seg="4"></span>
                    </div>
                    <p id="strengthLabel" class="mt-1 text-gray-400">Password strength</p>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    8–16 characters: uppercase, lowercase, number &amp; special character (e.g., @, #, $, %).
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div class="relative mt-1">
                    <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="4" y="10" width="16" height="10" rx="0.5"/>
                        <path d="M7 10V7a5 5 0 0 1 10 0v3" stroke-linecap="round"/>
                    </svg>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none swiss-input block w-full pl-10 pr-10 py-2 placeholder-gray-500 text-gray-900 sm:text-sm">
                    <button type="button" id="toggleConfirmPassword" aria-label="Show password" aria-pressed="false" class="eye-toggle absolute inset-y-0 right-0 flex items-center px-3">
                        <svg id="eyeIconShowConfirm" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12C1 12 5 5 12 5C19 5 23 12 23 12C23 12 19 19 12 19C5 19 1 12 1 12Z" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="eyeIconHideConfirm" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3L21 21" stroke-linecap="round"/>
                            <path d="M10.6 5.1C11.05 5.03 11.52 5 12 5C19 5 23 12 23 12C23 12 21.9 13.9 20 15.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.6 6.6C3.4 8.5 1 12 1 12C1 12 5 19 12 19C13.9 19 15.5 18.5 16.9 17.7" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.9 9.9A3 3 0 0 0 14.1 14.1" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <p id="matchMsg" class="mt-1 text-xs"></p>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-swiss-primary group relative w-full flex justify-center py-2.5 px-4 text-sm font-medium text-white">
                    Register and Request Verification Code
                </button>
            </div>
        </form>
    </div>
    </div>

</div>
</main>
    @include('Layouts.Footer')

    <!-- JavaScript to dynamically update addresses based on location ID -->
    <script>
        // Mapped directly to the database IDs seeded (1 = Addis Ababa, 2 = Hawassa)
        const addressOptions = {
           "1": [
        "Bole",
        "Megenagna",
        "Piazza (Addis)",
        "Arat Kilo",
        "Sarbet",
        "Kazanchis",
        "4 Kilo",
        "6Kilo",
        "Arada",
        "Akaki Kaliti",
        "Addis Ketema",
        "Kirkos",
        "Kolfe Keranio",
        "Lideta",
        "Nifas Silk-Lafto",
        "Yeka"
    ],
    // 2. Hawassa
    "2": [
        "Piassa (Hawassa)",
        "Atote",
        "Alamura",
        "Millennium",
        "Chefe",
        "Hayk Dar sub-city",
        "Menahariya sub-city",
        "Addisu Menahariya",
        "Misrak sub-city",
        "Tabor sub-city",
        "Hawela Tula sub-city",
        "Bahil Adarash sub-city",
        "Yeshi",
        "Gebeya Sefer",
        "Addisu Gebeya"
    ],
    // 3. Gondar
    "3": [
        "Gondar",
        "Arbaya",
        "Dabat",
        "Dembiya",
        "Debark",
        "Emfranz",
        "Feres Megria",
        "Musebamb Town",
        "Kurbi",
        "Armachiho",
        "Tekeldengy",
        "Gorgora",
        "Metemma"
    ],
    // 4. Bahir Dar
    "4": [
        "Shimbit",
        "Ginbot 20",
        "Shimbit (West)",
        "Kebele 03",
        "Tana",
        "Kebele 14",
        "Fasilo",
        "Mulualem",
        "Belay Zeleke"
    ],
    // 5. Wolayita
    "5": [
        "Sodo Zuria",
        "Abala Abaya",
        "Areka (town)",
        "Bale Hawassa (town)",
        "Bayra Koysha",
        "Bedessa (town)",
        "Boditi (town)",
        "Boloso Bombe",
        "Boloso Sore",
        "Bombe (town)",
        "Damot Gale",
        "Damot Pulasa",
        "Damot Sore",
        "Damot Weyde",
        "Diguna Fango",
        "Dimtu (town)",
        "Gesuba (town)",
        "Gununo (town)",
        "Hobicha",
        "Humbo",
        "Kawo Koysha",
        "Kindo Didaye",
        "Kindo Koysha",
        "Offa",
        "Tebela (town)",
        "Shanto (town)"
    ],
    // 6. Dilla
    "6": [
        "Dilla Town",
        "Dilla Zuria",
        "Piassa (Dilla)",
        "Chichu",
        "Haro",
        "Meleko",
        "Sero",
        "Bulfat",
        "Odola"
    ]
        };

        const locationSelect = document.getElementById('location_id');
        const addressSelect = document.getElementById('address');
        const oldAddressValue = "{{ old('address') }}";

        function updateAddresses() {
            const selectedLocationId = locationSelect.value;
            addressSelect.innerHTML = '';

            if (selectedLocationId && addressOptions[selectedLocationId]) {
                addressSelect.disabled = false;
                addressSelect.classList.remove('bg-gray-100');
                addressSelect.classList.add('bg-white');

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select Area / District';
                addressSelect.appendChild(defaultOption);

                addressOptions[selectedLocationId].forEach(function(address) {
                    const option = document.createElement('option');
                    option.value = address;
                    option.textContent = address;

                    if (oldAddressValue === address) {
                        option.selected = true;
                    }
                    addressSelect.appendChild(option);
                });
            } else {
                addressSelect.disabled = true;
                addressSelect.classList.add('bg-gray-100');
                addressSelect.classList.remove('bg-white');

                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Please select a Location first';
                addressSelect.appendChild(option);
            }
        }

        locationSelect.addEventListener('change', updateAddresses);

        if (locationSelect.value) {
            updateAddresses();
        }
    </script>

    
    <script>
        (function () {
            function wireToggle(btnId, inputId, showId, hideId) {
                const btn = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                const show = document.getElementById(showId);
                const hide = document.getElementById(hideId);
                btn.addEventListener('click', function () {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    show.classList.toggle('hidden', isHidden);
                    hide.classList.toggle('hidden', !isHidden);
                    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                    btn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                });
            }
            wireToggle('togglePassword', 'password', 'eyeIconShow', 'eyeIconHide');
            wireToggle('toggleConfirmPassword', 'password_confirmation', 'eyeIconShowConfirm', 'eyeIconHideConfirm');

            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const segs = document.querySelectorAll('.strength-seg');
            const label = document.getElementById('strengthLabel');
            const matchMsg = document.getElementById('matchMsg');

            const levels = [
                { filled: 0, color: '#e5e5e5', text: 'Password strength', textColor: '#9ca3af' },
                { filled: 1, color: '#dc2626', text: 'Weak',   textColor: '#dc2626' },
                { filled: 2, color: '#f59e0b', text: 'Fair',   textColor: '#b45309' },
                { filled: 3, color: '#1350e0', text: 'Good',   textColor: '#1350e0' },
                { filled: 4, color: '#16a34a', text: 'Strong', textColor: '#16a34a' }
            ];

            function scorePassword(pw) {
                let score = 0;
                if (pw.length >= 8 && pw.length <= 16) score++;
                if (/[A-Z]/.test(pw)) score++;
                if (/[a-z]/.test(pw)) score++;
                if (/[0-9]/.test(pw)) score++;
                if (/[^A-Za-z0-9]/.test(pw)) score++;
                return score;
            }

            function updateStrength() {
                const pw = passwordInput.value;
                let level;
                if (pw.length === 0) {
                    level = levels[0];
                } else {
                    const score = scorePassword(pw);
                    const idx = Math.max(1, Math.min(4, score === 5 ? 4 : score));
                    level = levels[idx];
                }
                segs.forEach(function (seg, i) {
                    seg.style.backgroundColor = (i < level.filled) ? level.color : '#e5e5e5';
                });
                label.textContent = level.text;
                label.style.color = level.textColor;
            }

            function updateMatch() {
                if (confirmInput.value.length === 0) {
                    matchMsg.textContent = '';
                    return;
                }
                if (passwordInput.value === confirmInput.value) {
                    matchMsg.textContent = 'Passwords match';
                    matchMsg.style.color = '#16a34a';
                } else {
                    matchMsg.textContent = 'Passwords do not match';
                    matchMsg.style.color = '#dc2626';
                }
            }

            passwordInput.addEventListener('input', function () {
                updateStrength();
                updateMatch();
            });
            confirmInput.addEventListener('input', updateMatch);
        })();
    </script>
</body>
</html>
