@extends('Layouts.Layout')

@section('title', 'Book Lesson with ' . $tutor->first_name)

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200">
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900">Book a Lesson</h2>
        <p class="text-sm text-gray-500 mt-1">
            Schedule a session with <strong class="text-indigo-600">{{ $tutor->first_name }} {{ $tutor->last_name }}</strong>
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <strong class="font-bold">Whoops!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tutors.book.store', $tutor->username) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Hidden inputs to submit actual selected date and times -->
        <input type="hidden" id="session_date" name="session_date" required value="{{ old('session_date') }}">
        <input type="hidden" id="start_time" name="start_time" required>
        <input type="hidden" id="end_time" name="end_time" required>

        <!-- 1. The Interactive Highlighted Calendar -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Select Date (Highlighted Days are Available)</label>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <!-- Calendar Header (Month / Year) -->
                <div class="flex justify-between items-center mb-4">
                    <span id="calendar_month_year" class="text-sm font-bold text-gray-900"></span>
                    <div class="flex gap-2">
                        <button type="button" id="prev_month" class="p-1.5 text-gray-600 hover:bg-gray-200 rounded-md text-xs font-bold">&larr;</button>
                        <button type="button" id="next_month" class="p-1.5 text-gray-600 hover:bg-gray-200 rounded-md text-xs font-bold">&rarr;</button>
                    </div>
                </div>

                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                </div>

                <!-- Days Grid -->
                <div id="calendar_days_grid" class="grid grid-cols-7 gap-1 text-center">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
        </div>

        <!-- 2. Dynamic Available Slots -->
        <div id="slots_section" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
            <div id="slots_container" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Populated dynamically via JS -->
            </div>
        </div>

        <!-- 3. Booking Notes -->
        <div>
            <label for="note" class="block text-sm font-medium text-gray-700">Message / Note to Tutor (Optional)</label>
            <textarea id="note" name="note" rows="3" placeholder="Tell the tutor what topics you'd like to focus on during this lesson..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('note') }}</textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('tutors.profile', $tutor->username) }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" id="submit_btn" disabled class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition shadow-sm">
                Request Session Booking
            </button>
        </div>
    </form>
</div>

<!-- JAVASCRIPT FOR HIGHLIGHTED CALENDAR & SCHEDULER -->
<script>
    // Tutor Availability Weekdays mapping
    const tutorSchedules = [
        @foreach($schedules as $sched)
            {
                "day": "{{ $sched->day_of_week }}",
                "start": "{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}",
                "end": "{{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}",
                "display": "{{ \Carbon\Carbon::parse($sched->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('g:i A') }}"
            },
        @endforeach
    ];

    // Extract unique weekdays taught by the tutor (e.g. ['Monday', 'Wednesday'])
    const tutorActiveWeekdays = [...new Set(tutorSchedules.map(s => s.day))];

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

    let currentDate = new Date(); // Track current month displayed
    const today = new Date();

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // Set Month/Year header
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        calendarMonthYear.textContent = `${monthNames[month]} ${year}`;

        calendarDaysGrid.innerHTML = '';

        const firstDayIndex = new Date(year, month, 1).getDay();
        const totalDays = new Date(year, month + 1, 0).getDate();

        // 1. Fill leading empty spaces for alignment
        for (let i = 0; $index = i < firstDayIndex; i++) {
            const blank = document.createElement('span');
            calendarDaysGrid.appendChild(blank);
        }

        // 2. Populate Days
        for (let dayNum = 1; dayNum <= totalDays; dayNum++) {
            const dateObj = new Date(year, month, dayNum);
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
            
            // Map day of the week name
            const weekdaysMap = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = weekdaysMap[dateObj.getDay()];

            const button = document.createElement('button');
            button.type = 'button';
            button.className = "h-8 w-8 text-xs font-semibold rounded-full flex items-center justify-center mx-auto transition select-none ";

            // Check if day is active, in the future, and matches tutor availability
            const isFutureOrToday = dateObj.setHours(0,0,0,0) >= today.setHours(0,0,0,0);
            const isAvailableDay = tutorActiveWeekdays.includes(dayName);

            if (isFutureOrToday && isAvailableDay) {
                // Highlight tutor availability days (Indigo circles)
                button.classList.add('bg-indigo-50', 'text-indigo-600', 'hover:bg-indigo-100', 'cursor-pointer', 'border', 'border-indigo-200');
                button.setAttribute('data-date', dateString);
                button.setAttribute('data-day', dayName);

                button.addEventListener('click', function() {
                    // Reset all calendar selections
                    document.querySelectorAll('[data-date]').forEach(btn => {
                        btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                        btn.classList.add('bg-indigo-50', 'text-indigo-600', 'hover:bg-indigo-100');
                    });

                    // Select this specific calendar day
                    this.classList.remove('bg-indigo-50', 'text-indigo-600', 'hover:bg-indigo-100');
                    this.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');

                    // Save selected date to hidden input
                    sessionDateInput.value = dateString;

                    // Display availability hours below
                    showSlots(dayName);
                });
            } else {
                // Gray out non-availability days
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
        startTimeInput.value = '';
        endTimeInput.value = '';

        const availableSlots = tutorSchedules.filter(sched => sched.day === dayName);

        if (availableSlots.length > 0) {
            slotsSection.classList.remove('hidden');

            availableSlots.forEach(slot => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = "time-slot-btn py-2.5 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:border-indigo-500 hover:bg-indigo-50 transition text-center";
                button.textContent = slot.display;

                button.addEventListener('click', function() {
                    document.querySelectorAll('.time-slot-btn').forEach(btn => {
                        btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                    });

                    this.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');

                    // Save times
                    startTimeInput.value = slot.start;
                    endTimeInput.value = slot.end;
                    submitBtn.disabled = false;
                });

                slotsContainer.appendChild(button);
            });
        }
    }

    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // Run calendar on initial load
    renderCalendar();

    // Restore selected date if redirected back by validation errors
    if (sessionDateInput.value) {
        const parts = sessionDateInput.value.split('-');
        currentDate = new Date(parts[0], parts[1] - 1, parts[2]);
        renderCalendar();
        const activeBtn = document.querySelector(`[data-date="${sessionDateInput.value}"]`);
        if (activeBtn) activeBtn.click();
    }
</script>
@endsection