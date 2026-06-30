@extends('Layouts.Layout')

@section('title', 'Find Tutors - TutorLink')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    <!-- SIDEBAR FILTERS -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-fit">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Filters</h3>
        
        <form action="{{ route('tutors.browse') }}" method="GET" class="space-y-4">
            
            <!-- City Selection -->
            <div>
                <label for="location_id" class="block text-xs font-bold text-gray-700 uppercase">City</label>
                <select id="location_id" name="location_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md text-sm focus:ring-indigo-500">
                    <option value="">All Cities</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Address Selection -->
            <div>
                <label for="address" class="block text-xs font-bold text-gray-700 uppercase">Sub-City / District</label>
                <select id="address" name="address" disabled class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md text-sm focus:ring-indigo-500">
                    <option value="">Select a City first</option>
                </select>
            </div>

            <!-- Category Selection -->
            <div>
                <label for="category_id" class="block text-xs font-bold text-gray-700 uppercase">Category</label>
                <select id="category_id" name="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md text-sm focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject Selection -->
            <div>
                <label for="subject_id" class="block text-xs font-bold text-gray-700 uppercase">Subject</label>
                <select id="subject_id" name="subject_id" disabled class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md text-sm focus:ring-indigo-500">
                    <option value="">Select a Category first</option>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                    Apply Filters
                </button>
                <a href="{{ route('tutors.browse') }}" class="mt-2 w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-semibold rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                    Clear All
                </a>
            </div>
        </form>
    </div>

    <!-- TUTOR RESULTS LIST -->
    <div class="lg:col-span-3 space-y-6">
        @forelse($tutors as $tutor)
            <!-- Tutor Card (Matching Your Image) -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col md:flex-row gap-6 relative">
                

                <!-- Left Column: Square Image with Active Status Indicator -->
                <div class="relative w-32 h-32 md:w-36 md:h-36 flex-shrink-0">
                    @if($tutor->user->profile_image)
                        <img src="{{ asset('storage/' . $tutor->user->profile_image) }}" alt="Photo" class="w-full h-full object-cover rounded-md">
                    @else
                        <div class="w-full h-full bg-indigo-100 rounded-md flex items-center justify-center text-indigo-500 text-2xl font-bold">
                            {{ substr($tutor->user->first_name, 0, 1) }}{{ substr($tutor->user->last_name, 0, 1) }}
                        </div>
                    @endif
                    <!-- Green active status square -->
                    <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-sm bg-green-500 ring-2 ring-white"></span>
                </div>

                <!-- Center Column: Profile Info & Snippet -->
                <div class="flex-grow space-y-2">
                    <div class="flex items-center gap-2">
                        <h4 class="text-xl font-bold text-gray-900">
                            {{ $tutor->user->first_name }} {{ substr($tutor->user->last_name, 0, 1) }}.
                        </h4>
                        <!-- Verification Checkmark -->
                        <span class="text-blue-500" title="Verified Tutor">✓</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 font-medium">
                        <span class="flex items-center gap-1">⭐ Super Tutor</span>
                        <span>•</span>
                        <span>🎓 {{ $tutor->qualification }}</span>
                        <span>•</span>
                        <span>📍 {{ $tutor->user->location?->name }}, {{ $tutor->user->address }}</span>
                    </div>

                    <!-- Specialty Subjects -->
                    <p class="text-sm font-semibold text-indigo-600">
                        {{ $tutor->subjects->pluck('name')->implode(', ') }}
                    </p>

                    <!-- Short Bio Snippet -->
                    <p class="text-sm text-gray-600 line-clamp-2 pr-12">
                        {{ $tutor->bio }}
                    </p>

                    <a href="{{ route('tutors.profile', $tutor->user->username) }}" 
                      aria-label="View {{ $tutor->user->first_name }}'s full teaching profile" 
                      class="inline-block text-xs font-bold text-indigo-600 hover:underline">
                      View profile &rarr;
                    </a>
                </div>

                <!-- Right Column: Price, Statistics, Action Buttons -->
                <div class="w-full md:w-48 flex-shrink-0 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 flex flex-col justify-between">
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900">
                            ETB {{ number_format($tutor->price_per_hour, 2) }}
                        </div>
                        <span class="text-xs text-gray-500">Price_Per-Hour</span>
                    </div>

                    <!-- Statistics (Stars / Students / Lessons) -->
                    <div class="grid grid-cols-3 gap-2 text-center my-3 bg-gray-50 p-2 rounded-md">
                        <div>
                            <span class="block text-xs font-bold text-gray-900">5.0 ★</span>
                            <span class="text-[10px] text-gray-500">reviews</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-900">{{ $tutor->max_students }}</span>
                            <span class="text-[10px] text-gray-500">students</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-900">{{ $tutor->experience_years }}y</span>
                            <span class="text-[10px] text-gray-500">experience</span>
                        </div>
                    </div>

                    <!-- Call To Action Buttons -->
                    <div class="space-y-2">
                <!-- Secure, dynamic link to the booking form passing the tutor's username -->
                 <a href="{{ route('tutors.book', $tutor->user->username) }}" class="w-full block text-center py-2 px-3 text-xs font-bold rounded-md text-white bg-rose-700 hover:bg-rose-800 transition shadow-sm">
                       Book lesson
                     </a>
                        <a href="#" class="w-full block text-center py-2 px-3 text-xs font-bold rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">
                            Send message
                        </a>
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-lg border border-gray-200 p-6">
                <p class="text-gray-500">No tutors found matching your current filter choices.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- JAVASCRIPT FOR CASCADING DROPDOWNS -->
<script>
    // 1. Mapped Locations/Addresses
    const addressOptions = {
        "1": ["Bole", "Megenagna", "Piazza (Addis)", "Arat Kilo", "Sarbet", "Kazanchis"],
        "2": ["Piassa (Hawassa)", "Atote", "Alamura", "Tabor", "Millennium", "Chefe"]
    };

    // 2. Mapped Categories/Subjects
    const categoryOptions = {
        @foreach($categories as $category)
            "{{ $category->id }}": [
                @foreach($category->subjects as $subj)
                    { "id": "{{ $subj->id }}", "name": "{{ $subj->name }}" },
                @endforeach
            ],
        @endforeach
    };

    const locationSelect = document.getElementById('location_id');
    const addressSelect = document.getElementById('address');
    const categorySelect = document.getElementById('category_id');
    const subjectSelect = document.getElementById('subject_id');

    const oldAddress = "{{ request('address') }}";
    const oldSubject = "{{ request('subject_id') }}";

    function updateAddresses() {
        const locId = locationSelect.value;
        addressSelect.innerHTML = '<option value="">All Districts</option>';

        if (locId && addressOptions[locId]) {
            addressSelect.disabled = false;
            addressSelect.classList.remove('bg-gray-100');
            addressOptions[locId].forEach(addr => {
                const opt = document.createElement('option');
                opt.value = addr;
                opt.textContent = addr;
                if (oldAddress === addr) opt.selected = true;
                addressSelect.appendChild(opt);
            });
        } else {
            addressSelect.disabled = true;
            addressSelect.classList.add('bg-gray-100');
        }
    }

    function updateSubjects() {
        const catId = categorySelect.value;
        subjectSelect.innerHTML = '<option value="">All Subjects</option>';

        if (catId && categoryOptions[catId]) {
            subjectSelect.disabled = false;
            subjectSelect.classList.remove('bg-gray-100');
            categoryOptions[catId].forEach(subj => {
                const opt = document.createElement('option');
                opt.value = subj.id;
                opt.textContent = subj.name;
                if (oldSubject === subj.id.toString()) opt.selected = true;
                subjectSelect.appendChild(opt);
            });
        } else {
            subjectSelect.disabled = true;
            subjectSelect.classList.add('bg-gray-100');
        }
    }

    locationSelect.addEventListener('change', updateAddresses);
    categorySelect.addEventListener('change', updateSubjects);

    if (locationSelect.value) updateAddresses();
    if (categorySelect.value) updateSubjects();
</script>
@endsection