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

            <!-- Teaching Mode Selection -->
            <div>
                <label for="teaching_mode" class="block text-xs font-bold text-gray-700 uppercase">Teaching Method</label>
                <select id="teaching_mode" name="teaching_mode" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md text-sm focus:ring-indigo-500">
                    <option value="">All Methods</option>
                    <option value="online" {{ request('teaching_mode') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="in-person" {{ request('teaching_mode') == 'in-person' ? 'selected' : '' }}>In-Person</option>
                    <option value="hybrid" {{ request('teaching_mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
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
            <!-- Tutor Card -->
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
                    <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-sm bg-green-500 ring-2 ring-white"></span>
                </div>

                <!-- Center Column: Profile Info & Snippet -->
                <div class="flex-grow space-y-2">
                    <div class="flex items-center gap-2">
                        <h4 class="text-xl font-bold text-gray-900">
                            {{ $tutor->user->first_name }} {{ substr($tutor->user->last_name, 0, 1) }}.
                        </h4>
                        <span class="text-blue-500" title="Verified Tutor">✓</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-gray-500 font-medium">
                        <span class="flex items-center gap-1">⭐ Super Tutor</span>
                        <span>•</span>
                        <span>🎓 {{ $tutor->qualification }}</span>
                        <span>•</span>
                        <span>📍 {{ $tutor->user->location?->name }}, {{ $tutor->user->address }}</span>
                        <span>•</span>
                        <span class="capitalize text-indigo-700 bg-indigo-50/60 px-2 py-0.5 rounded border border-indigo-100/50 font-bold">
                            💻 {{ $tutor->teaching_mode }}
                        </span>
                    </div>

                    <p class="text-sm font-semibold text-indigo-600">
                        {{ $tutor->subjects->pluck('name')->implode(', ') }}
                    </p>

                    <p class="text-sm text-gray-600 line-clamp-2 pr-12">
                        {{ $tutor->bio }}
                    </p>

                    <a href="{{ route('tutors.profile', $tutor->user->username) }}" aria-label="View {{ $tutor->user->first_name }}'s full teaching profile" class="inline-block text-xs font-bold text-indigo-600 hover:underline">
                        View profile &rarr;
                    </a>
                </div>

                <!-- Right Column: Price, Statistics, Action Buttons -->
                <div class="w-full md:w-48 flex-shrink-0 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 flex flex-col justify-between">
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900">
                            ETB {{ number_format($tutor->price_per_hour, 2) }}
                        </div>
                        <span class="text-xs text-gray-500">per-hour lesson</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center my-3 bg-gray-50 p-2 rounded-md">
                        <div>
                            <span class="block text-xs font-bold text-gray-900">{{ number_format($tutor->average_rating, 1) }} ★</span>
                            <span class="text-[10px] text-gray-500">({{ $tutor->reviews_count }}) revs</span>
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

                    <!-- Call To Action Buttons (Updated to intercept messaging if unbooked) -->
                    <div class="space-y-2">
                        <button type="button" 
                            onclick="openBookingModal('{{ $tutor->user->username }}', '{{ $tutor->user->first_name }} {{ $tutor->user->last_name }}', {{ json_encode($tutor->user->schedules) }})"
                            class="w-full block text-center py-2 px-3 text-xs font-bold rounded-md text-white bg-rose-700 hover:bg-rose-800 transition shadow-sm">
                            Book lesson
                        </button>

                        @if(Auth::check() && in_array($tutor->user_id, $allowedTutorIds))
                            <!-- Active Direct Chat Link -->
                            <a href="{{ route('messages.show', $tutor->user->username) }}" class="w-full block text-center py-2 px-3 text-xs font-bold rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition shadow-sm">
                                Send message
                            </a>
                        @else
                            <!-- Informative Front-End Booking Prompt Button -->
                            <button type="button" 
                                onclick="alert('Security Restriction: You must book a lesson with {{ $tutor->user->first_name }} and have it accepted before you can message them.')" 
                                class="w-full block text-center py-2 px-3 text-xs font-bold rounded-md text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed transition">
                                Send message
                            </button>
                        @endif
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

<!-- INTERACTIVE BOOKING MODAL (Blurred backdrop overlay) -->
<div id="booking_modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm select-none transition duration-300">
    <div class="bg-white rounded-lg shadow-xl border border-gray-200 w-full max-w-xl p-8 relative">
        <button type="button" id="close_booking_modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900">Book a Lesson</h2>
            <p class="text-sm text-gray-500 mt-1">Schedule a session with <strong id="modal_tutor_name" class="text-indigo-600"></strong></p>
        </div>

        <form id="booking_form" action="" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" id="session_date" name="session_date" required>
            <input type="hidden" id="start_time" name="start_time" required>
            <input type="hidden" id="end_time" name="end_time" required>
            <input type="hidden" id="old_tutor_username" name="old_tutor_username">
            <input type="hidden" id="old_tutor_name" name="old_tutor_name">
            <input type="hidden" id="old_tutor_schedules" name="old_tutor_schedules">

            <!-- 1. Highlighted Calendar Grid -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Date (Highlighted Days are Available)</label>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <span id="calendar_month_year" class="text-sm font-bold text-gray-900"></span>
                        <div class="flex gap-2">
                            <button type="button" id="prev_month" class="p-1.5 text-gray-600 hover:bg-gray-200 rounded-md text-xs font-bold">&larr;</button>
                            <button type="button" id="next_month" class="p-1.5 text-gray-600 hover:bg-gray-200 rounded-md text-xs font-bold">&rarr;</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>
                    <div id="calendar_days_grid" class="grid grid-cols-7 gap-1 text-center font-bold"></div>
                </div>
            </div>

            <!-- 2. Dynamic Available Slots -->
            <div id="slots_section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
                <div id="slots_container" class="grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
            </div>

            <!-- 3. Booking Notes -->
            <div>
                <label for="note" class="block text-sm font-medium text-gray-700">Message / Note to Tutor (Optional)</label>
                <textarea id="note" name="note" rows="3" placeholder="Tell the tutor what topics you'd like to focus on during this lesson..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('note') }}</textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" id="cancel_booking_modal" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" id="submit_btn" disabled class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50 transition shadow-sm">Request Session Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- SIDEBAR DROPDOWNS MAPS ---
    const addressOptions = {
        "1": ["Bole", "Megenagna", "Piazza (Addis)", "Arat Kilo", "Sarbet", "Kazanchis"],
        "2": ["Piassa (Hawassa)", "Atote", "Alamura", "Tabor", "Millennium", "Chefe"]
    };

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


    // --- DYNAMIC BOOKING MODAL CONTROLLER ---
    const bookingModal = document.getElementById('booking_modal');
    const closeBookingBtn = document.getElementById('close_booking_modal');
    const cancelBookingBtn = document.getElementById('cancel_booking_modal');
    const modalTutorName = document.getElementById('modal_tutor_name');
    const bookingForm = document.getElementById('booking_form');

    const calendarMonthYear = document.getElementById('calendar_month_year');
    const calendarDaysGrid = document.getElementById('calendar_days_grid');
    const prevMonthBtn = document.getElementById('prev_month');
    const nextMonthBtn = document.getElementById('next_month');

    const sessionDateInput = document.getElementById('session_date');
    const slotsSection = document.getElementById('slots_section');
    const slotsContainer = document.getElementById('slots_container');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const submitBtn = document.getElementById('submit_btn');

    const oldTutorUsername = document.getElementById('old_tutor_username');
    const oldTutorName = document.getElementById('old_tutor_name');
    const oldTutorSchedules = document.getElementById('old_tutor_schedules');

    let currentDate = new Date();
    const today = new Date();
    let activeTutorSchedules = [];
    let tutorActiveWeekdays = [];

    function openBookingModal(username, fullName, schedulesJson) {
        bookingForm.action = "/tutors/" + username + "/book";
        modalTutorName.textContent = fullName;
        activeTutorSchedules = schedulesJson;
        tutorActiveWeekdays = [...new Set(activeTutorSchedules.map(s => s.day_of_week))];

        oldTutorUsername.value = username;
        oldTutorName.value = fullName;
        oldTutorSchedules.value = JSON.stringify(schedulesJson);

        sessionDateInput.value = '';
        startTimeInput.value = '';
        endTimeInput.value = '';
        slotsSection.classList.add('hidden');
        submitBtn.disabled = true;

        currentDate = new Date();
        renderCalendar();
        bookingModal.classList.remove('hidden');
    }

    function closeBookingModal() { bookingModal.classList.add('hidden'); }

    closeBookingBtn.addEventListener('click', closeBookingModal);
    cancelBookingBtn.addEventListener('click', closeBookingModal);
    bookingModal.addEventListener('click', (e) => { if (e.target === bookingModal) closeBookingModal(); });

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        calendarMonthYear.textContent = `${monthNames[month]} ${year}`;
        calendarDaysGrid.innerHTML = '';

        const firstDayIndex = new Date(year, month, 1).getDay();
        const totalDays = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDayIndex; i++) {
            calendarDaysGrid.appendChild(document.createElement('span'));
        }

        for (let dayNum = 1; dayNum <= totalDays; dayNum++) {
            const dateObj = new Date(year, month, dayNum);
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
            const weekdaysMap = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = weekdaysMap[dateObj.getDay()];

            const button = document.createElement('button');
            button.type = 'button';
            button.className = "h-8 w-8 text-xs font-semibold rounded-full flex items-center justify-center mx-auto transition select-none ";

            const isFutureOrToday = dateObj.setHours(0,0,0,0) >= today.setHours(0,0,0,0);
            const isAvailableDay = tutorActiveWeekdays.includes(dayName);

            if (isFutureOrToday && isAvailableDay) {
                button.classList.add('bg-indigo-50', 'text-indigo-600', 'hover:bg-indigo-100', 'cursor-pointer', 'border', 'border-indigo-200');
                button.setAttribute('data-date', dateString);
                button.addEventListener('click', function() {
                    document.querySelectorAll('[data-date]').forEach(btn => {
                        btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                        btn.classList.add('bg-indigo-50', 'text-indigo-600', 'hover:bg-indigo-100');
                    });
                    this.classList.remove('bg-indigo-50', 'text-indigo-600', 'hover:bg-indigo-100');
                    this.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                    sessionDateInput.value = dateString;
                    showSlots(dayName);
                });
            } else {
                button.classList.add('text-gray-300', 'cursor-not-allowed');
                button.disabled = true;
            }
            button.textContent = dayNum;
            calendarDaysGrid.appendChild(button);
        }
    }

    function showSlots(dayName) {
        slotsContainer.innerHTML = '';
        submitBtn.disabled = true;
        const availableSlots = activeTutorSchedules.filter(sched => sched.day_of_week === dayName);

        if (availableSlots.length > 0) {
            slotsSection.classList.remove('hidden');
            availableSlots.forEach(slot => {
                const button = document.createElement('button');
                button.type = 'button';
                const startClean = slot.start_time.substring(0, 5);
                const endClean = slot.end_time.substring(0, 5);
                button.className = "time-slot-btn py-2.5 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:border-indigo-500 hover:bg-indigo-50 transition text-center";
                button.textContent = `${startClean} - ${endClean}`;
                button.addEventListener('click', function() {
                    document.querySelectorAll('.time-slot-btn').forEach(btn => btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700'));
                    this.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                    startTimeInput.value = slot.start_time.substring(0, 5);
                    endTimeInput.value = slot.end_time.substring(0, 5);
                    submitBtn.disabled = false;
                });
                slotsContainer.appendChild(button);
            });
        }
    }

    prevMonthBtn.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); });
    nextMonthBtn.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); });

    @if($errors->any() && old('session_date'))
        window.addEventListener('DOMContentLoaded', () => {
            const rawScheds = {!! old('old_tutor_schedules') !!};
            openBookingModal("{{ old('old_tutor_username') }}", "{{ old('old_tutor_name') }}", rawScheds);
            sessionDateInput.value = "{{ old('session_date') }}";
            const parts = sessionDateInput.value.split('-');
            currentDate = new Date(parts[0], parts[1] - 1, parts[2]);
            renderCalendar();
            const activeBtn = document.querySelector(`[data-date="${sessionDateInput.value}"]`);
            if (activeBtn) activeBtn.click();
        });
    @endif
</script>
@endsection