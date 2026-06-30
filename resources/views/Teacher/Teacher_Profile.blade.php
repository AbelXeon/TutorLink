@extends('Layouts.Layout')

@section('title', $tutor->user->first_name . ' - Tutor Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Profile Details Header Card -->
    <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 flex flex-col md:flex-row gap-8">
        
        <!-- Profile Image -->
        <div class="w-36 h-36 md:w-44 md:h-44 flex-shrink-0 relative">
            @if($tutor->user->profile_image)
                <img src="{{ asset('storage/' . $tutor->user->profile_image) }}" alt="Photo" class="w-full h-full object-cover rounded-md">
            @else
                <div class="w-full h-full bg-indigo-100 rounded-md flex items-center justify-center text-indigo-500 text-3xl font-bold">
                    {{ substr($tutor->user->first_name, 0, 1) }}{{ substr($tutor->user->last_name, 0, 1) }}
                </div>
            @endif
            <span class="absolute bottom-0 right-0 block h-5 w-5 rounded-sm bg-green-500 ring-2 ring-white"></span>
        </div>

        <!-- Info Details -->
        <div class="flex-grow space-y-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">
                    {{ $tutor->user->first_name }} {{ $tutor->user->last_name }}
                </h2>
                <p class="text-indigo-600 font-semibold text-sm mt-1">
                    Specialty: {{ $tutor->subjects->pluck('name')->implode(', ') }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2 border-t border-b border-gray-100 py-4">
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
                    <span class="text-lg font-bold text-gray-900">{{ $tutor->qualification }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase font-bold block">Location</span>
                    <span class="text-lg font-bold text-gray-900">{{ $tutor->user->location?->name }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-2">
                
              <!-- Secure, dynamic link to the booking form passing the tutor's username -->
                <a href="{{ route('tutors.book', $tutor->user->username) }}" class="w-full block text-center py-2 px-3 text-xs font-bold rounded-md text-white bg-rose-700 hover:bg-rose-800 transition shadow-sm">
                  Book lesson
                 </a>
                <a href="#" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                    Send Message
                </a>
            </div>
        </div>
    </div>

    <!-- About Me Section -->
    <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">About Me</h3>
        <p class="text-gray-700 leading-relaxed text-sm whitespace-pre-line">
            {{ $tutor->bio }}
        </p>
    </div>

    <!-- Weekly Schedule Section -->
    <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
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

</div>
@endsection