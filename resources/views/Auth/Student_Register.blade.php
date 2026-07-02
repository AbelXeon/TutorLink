<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - TutorLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Register as a Student
            </h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register.student') }}" method="POST">
            @csrf
            
            <div class="rounded-md shadow-sm space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input id="first_name" name="first_name" type="text" required value="{{ old('first_name') }}" class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required value="{{ old('last_name') }}" class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name (Optional)</label>
                    <input id="middle_name" name="middle_name" type="text" value="{{ old('middle_name') }}" class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input id="username" name="username" type="text" required value="{{ old('username') }}" class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">
                        Must be unique and between 5 and 16 characters.
                    </p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input id="phone_number" name="phone_number" type="text" required value="{{ old('phone_number') }}" class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">
                        Must be a unique number. Cannot be shared with another account.
                    </p>
                </div>

                <!-- 1. Location Selection Dropdown -->
                <div>
                    <label for="location_id" class="block text-sm font-medium text-gray-700">Location (City/Region)</label>
                    <select id="location_id" name="location_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Address Dropdown (Structured options based on the chosen Location) -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address (District/Area)</label>
                    <select id="address" name="address" required disabled class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Please select a Location first</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">
                        Must be 8–16 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character (e.g., @, #, $, %).
                    </p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Register and Request Verification Code
                </button>
            </div>
        </form>
    </div>
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
                "Kazanchis"
            ],
            "2": [
                "Piassa (Hawassa)", 
                "Atote", 
                "Alamura", 
                "Tabor", 
                "Millennium", 
                "Chefe"
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
</body>
</html>