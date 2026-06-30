@extends('Layouts.Layout')

@section('title', 'Book Lesson with ' . $tutor->first_name)

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200">
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900">Book a Lesson</h2>
        <p class="text-sm text-gray-500 mt-1">
            Booking a session with <strong class="text-indigo-600">{{ $tutor->first_name }} {{ $tutor->last_name }}</strong>
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

        <!-- 1. Select Date -->
        <div>
            <label for="session_date" class="block text-sm font-medium text-gray-700">Select Date</label>
            <input id="session_date" name="session_date" type="date" required min="{{ date('Y-m-d') }}" value="{{ old('session_date') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- 2. Dynamic Available Slots (Hidden until date is selected) -->
        <div id="slots_section" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots for this day</label>
            <div id="slots_container" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Dynamically populated via JS -->
            </div>
            
            <!-- Hidden inputs to submit actual selected times -->
            <input type="hidden" id="start_time" name="start_time" required>
            <input type="hidden" id="end_time" name="end_time" required>
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
            <button type="submit" id="submit_btn" disabled class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                Request Session Booking
            </button>
        </div>
    </form>
</div>

<!-- JAVASCRIPT FOR DYNAMIC AVAILABILITY MATCHING -->
<script>
    // Tutor Availability Slots populated from database
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

    const dateInput = document.getElementById('session_date');
    const slotsSection = document.getElementById('slots_section');
    const slotsContainer = document.getElementById('slots_container');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const submitBtn = document.getElementById('submit_btn');

    function checkAvailability() {
        const dateVal = dateInput.value;
        slotsContainer.innerHTML = '';
        submitBtn.disabled = true;
        startTimeInput.value = '';
        endTimeInput.value = '';

        if (dateVal) {
            // Analyze chosen day of the week
            const selectedDate = new Date(dateVal);
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const selectedDay = days[selectedDate.getDay()];

            // Filter tutor slots matching that day
            const availableSlots = tutorSchedules.filter(sched => sched.day === selectedDay);

            if (availableSlots.length > 0) {
                slotsSection.classList.remove('hidden');

                availableSlots.forEach((slot, index) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = "time-slot-btn py-2.5 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:border-indigo-500 hover:bg-indigo-50 transition text-center";
                    button.textContent = slot.display;
                    button.setAttribute('data-start', slot.start);
                    button.setAttribute('data-end', slot.end);

                    button.addEventListener('click', function() {
                        // Reset all buttons style
                        document.querySelectorAll('.time-slot-btn').forEach(btn => {
                            btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                        });

                        // Select this button
                        this.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');

                        // Save times into hidden fields
                        startTimeInput.value = slot.start;
                        endTimeInput.value = slot.end;
                        
                        // Enable form submission
                        submitBtn.disabled = false;
                    });

                    slotsContainer.appendChild(button);
                });
            } else {
                slotsSection.classList.remove('hidden');
                slotsContainer.innerHTML = `<p class="text-sm text-red-500 italic col-span-2">This tutor has no available sessions configured for ${selectedDay}s.</p>`;
            }
        } else {
            slotsSection.classList.add('hidden');
        }
    }

    dateInput.addEventListener('change', checkAvailability);

    // Initial check if returning with validation errors
    if (dateInput.value) {
        checkAvailability();
    }
</script>
@endsection