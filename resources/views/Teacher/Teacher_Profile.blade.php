@extends('Layouts.Layout')

@section('title', $tutor->user->first_name . ' - Tutor Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 text-gray-800">

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Details Header Card -->
    <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 flex flex-col md:flex-row gap-8 items-start md:items-center">
        
        <!-- Profile Image -->
        <div class="w-36 h-36 md:w-44 md:h-44 flex-shrink-0 relative mx-auto md:mx-0">
            @if($tutor->user->profile_image)
                <img src="{{ asset('storage/' . $tutor->user->profile_image) }}" alt="Photo" class="w-full h-full object-cover rounded-xl shadow-inner">
            @else
                <div class="w-full h-full bg-indigo-50 rounded-xl border border-indigo-100 flex items-center justify-center text-indigo-500 text-3xl font-bold">
                    {{ substr($tutor->user->first_name, 0, 1) }}{{ substr($tutor->user->last_name, 0, 1) }}
                </div>
            @endif
            <span class="absolute bottom-1 right-1 block h-5 w-5 rounded-sm bg-green-500 ring-2 ring-white"></span>
        </div>

        <!-- Info Details -->
        <div class="flex-grow space-y-4 w-full text-center md:text-left">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ $tutor->user->first_name }} {{ $tutor->user->last_name }}
                    </h2>
                    
                    <!-- Dynamic rating badge -->
                    <div class="flex items-center gap-1.5 text-sm bg-indigo-50 text-indigo-700 font-bold px-3.5 py-1 rounded-full border border-indigo-100 w-fit mx-auto md:mx-0">
                        <span>{{ number_format($averageRating, 1) }} ★</span>
                        <span class="text-indigo-300">|</span>
                        <span class="text-xs text-indigo-600 font-medium">{{ $reviews->count() }} reviews</span>
                    </div>
                </div>
                
                <p class="text-indigo-600 font-semibold text-sm mt-1">
                    Specialty: {{ $tutor->subjects->pluck('name')->implode(', ') }}
                </p>
            </div>

            <!-- Your exact metric columns -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-b border-gray-100 py-4 text-center">
                <div>
                    <span class="text-xs text-gray-500 uppercase font-bold block">Rate</span>
                    <span class="text-lg font-bold text-gray-900">ETB {{ $tutor->price_per_hour }}/hr</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase font-bold block">Experience</span>
                    <span class="text-lg font-bold text-gray-900">{{ $tutor->experience_years }} Years</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase font-bold block">Qualification</span>
                    <span class="text-lg font-bold text-gray-900 truncate block" title="{{ $tutor->qualification }}">{{ $tutor->qualification }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase font-bold block">Location</span>
                    <span class="text-lg font-bold text-gray-900">{{ $tutor->user->location?->name }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <!-- Trigger interactive booking modal dynamically -->
                <button type="button" 
                    onclick="openBookingModal('{{ $tutor->user->username }}', '{{ $tutor->user->first_name }} {{ $tutor->user->last_name }}', {{ json_encode($tutor->user->schedules) }})"
                    class="w-full text-center py-2.5 px-3 text-xs font-bold rounded-md text-white bg-rose-700 hover:bg-rose-800 transition shadow-sm">
                    Book lesson
                </button>
                
                <!-- Conditionally render Review or Message Button -->
                @if(isset($unreviewedBooking) && $unreviewedBooking)
                    <button type="button" onclick="openReviewModal()" class="w-full text-center py-2.5 px-3 text-xs font-bold rounded-md text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition shadow-sm">
                        ⭐ Write a Review
                    </button>
                @elseif(isset($canMessage) && $canMessage)
                    <!-- Direct Chat Link (Only for students with accepted bookings) -->
                    <a href="{{ route('messages.show', $tutor->user->username) }}" class="w-full text-center py-2.5 px-3 text-xs font-bold rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">
                        Send Message
                    </a>
                @else
                    <!-- Front-End Interactive Booking Warning Alert -->
                    <button type="button" 
                        onclick="alert('Security Restriction: You must book a lesson with {{ $tutor->user->first_name }} and have it accepted before you can message them.')" 
                        class="w-full text-center py-2.5 px-3 text-xs font-bold rounded-md text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed transition">
                        Send Message
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Teaching Overview -->
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-lg font-extrabold text-gray-950 mb-4 border-b border-gray-100 pb-2">Teaching Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div>
                <ul class="space-y-3">
                    <li><strong class="text-gray-900 font-semibold">Highest Qualification:</strong> {{ $tutor->qualification }}</li>
                    <li><strong class="text-gray-900 font-semibold">Experience Years:</strong> {{ $tutor->experience_years }} Years</li>
                    <li><strong class="text-gray-900 font-semibold">Teaching Mode:</strong> <span class="capitalize">{{ $tutor->teaching_mode }}</span></li>
                </ul>
            </div>
            <div>
                <ul class="space-y-3">
                    <li><strong class="text-gray-900 font-semibold">Max Students Per Session:</strong> {{ $tutor->max_students }}</li>
                    <li><strong class="text-gray-900 font-semibold">Hourly Rate:</strong> <span class="text-indigo-600 font-bold">{{ number_format($tutor->price_per_hour, 2) }} ETB/hr</span></li>
                    <li><strong class="text-gray-900 font-semibold">District / Sub-City:</strong> {{ $tutor->user->address }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- About Me Section -->
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">About Me</h3>
        <p class="text-gray-700 leading-relaxed text-sm whitespace-pre-line bg-gray-50 p-6 rounded-md border border-gray-100">
            {{ $tutor->bio }}
        </p>
    </div>

    <!-- Weekly Schedule Section -->
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Weekly Availability Schedule</h3>
        
        @if($tutor->user->schedules->count() > 0)
            <div class="overflow-hidden border border-gray-200 rounded-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Day</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Start Time</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">End Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white text-sm text-gray-700">
                        @foreach($tutor->user->schedules as $sched)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $sched->day_of_week }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($sched->start_time)->format('g:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($sched->end_time)->format('g:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500 italic">No available timeslots configured by this tutor.</p>
        @endif
    </div>

    <!-- Student Reviews List Card -->
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-2">Student Reviews</h3>
        
        @if($reviews->count() > 0)
            <div class="space-y-6">
                @foreach($reviews as $review)
                    <div class="border-b border-gray-100 pb-6 last:border-b-0 last:pb-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                    {{ substr($review->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">{{ $review->first_name }} {{ $review->last_name }}</h4>
                                    <span class="text-[10px] text-gray-400 font-semibold">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-amber-500 font-bold text-sm">
                                {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-md border border-gray-100">
                            "{{ $review->comment }}"
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic text-center py-6">No reviews received yet. Be the first to book a session and leave feedback!</p>
        @endif
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

            <!-- Calendar Grid -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Date</label>
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
                    <div id="calendar_days_grid" class="grid grid-cols-7 gap-1 text-center"></div>
                </div>
            </div>

            <!-- Time Slots -->
            <div id="slots_section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
                <div id="slots_container" class="grid grid-cols-2 gap-3"></div>
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-gray-700">Message / Note to Tutor (Optional)</label>
                <textarea id="note" name="note" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" id="cancel_booking_modal" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" id="submit_btn" disabled class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50 transition shadow-sm">Request Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- INTERACTIVE RATING MODAL (Blurred backdrop overlay) -->
@if(isset($unreviewedBooking) && $unreviewedBooking)
<div id="review_modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm transition duration-300">
    <div class="bg-white rounded-xl shadow-xl border border-gray-200 w-full max-w-md p-8 relative">
        <button type="button" onclick="closeReviewModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-2xl font-black text-gray-900">Review Lesson</h2>
            <p class="text-sm text-gray-500 mt-1">Rate your experience with <strong class="text-indigo-600">{{ $tutor->user->first_name }}</strong></p>
        </div>

        <form action="{{ route('bookings.review.store', $unreviewedBooking->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Secure Star Selector (Interactive styling via label focus triggers) -->
            <div>
                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Your Rating</label>
                <div class="flex items-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" required>
                            <span class="text-3xl text-gray-300 peer-checked:text-amber-500 hover:text-amber-400 transition">★</span>
                        </label>
                    @endfor
                </div>
            </div>

            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700">Write your feedback</label>
                <textarea id="comment" name="comment" rows="4" required placeholder="Describe your learning experience..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/40 text-sm"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition shadow-sm">Post Review</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    // --- CALENDAR HANDLERS ---
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

    let currentDate = new Date();
    const today = new Date();
    let activeTutorSchedules = [];
    let tutorActiveWeekdays = [];

    function openBookingModal(username, fullName, schedulesJson) {
        bookingForm.action = "/tutors/" + username + "/book";
        modalTutorName.textContent = fullName;
        activeTutorSchedules = schedulesJson;
        tutorActiveWeekdays = [...new Set(activeTutorSchedules.map(s => s.day_of_week))];

        sessionDateInput.value = '';
        startTimeInput.value = '';
        endTimeInput.value = '';
        slotsSection.classList.add('hidden');
        submitBtn.disabled = true;

        currentDate = new Date();
        renderCalendar();
        bookingModal.classList.remove('hidden');
    }

    function closeBookingModal() {
        bookingModal.classList.add('hidden');
    }

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

    // --- REVIEWS MODAL OVERLAY HANDLERS ---
    const reviewModal = document.getElementById('review_modal');
    function openReviewModal() { if (reviewModal) reviewModal.classList.remove('hidden'); }
    function closeReviewModal() { if (reviewModal) reviewModal.classList.add('hidden'); }
</script>
@endsection