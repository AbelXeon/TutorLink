@extends('Layouts.Layout')

@section('title', 'Teacher Dashboard - TutorLink')

@section('content')

    <!-- MAIN BODY -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <!-- Profile Cover / Header Background -->
            <div class="h-32 bg-indigo-600"></div>

            <div class="px-8 pb-8 relative">
                <!-- Avatar Positioning -->
                <div class="absolute -top-16 left-8">
                    @if($user->profile_image)
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-md" src="{{ asset('storage/' . $user->profile_image) }}" alt="Tutor Photo">
                    @else
                        <div class="h-32 w-32 bg-indigo-100 border-4 border-white shadow-md rounded-full flex items-center justify-center text-indigo-700 text-4xl font-bold">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="flex justify-end pt-4">
                    <a href="{{ route('tutor.profile.edit') }}" class="inline-flex justify-center py-2 px-4 border border-indigo-600 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit Profile
                    </a>
                </div>

                <!-- Profile Bio Information -->
                <div class="mt-6">
                    <h2 class="text-3xl font-extrabold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Username: {{ $user->username }}</p>
                    <p class="text-sm text-indigo-600 font-semibold mt-2">Active Tutor</p>
                </div>

                <!-- Detailed Statistics Grid -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-8">
                    <div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Teaching Overview</h4>
                        <ul class="mt-3 space-y-3 text-sm text-gray-900">
                            <li><strong>Highest Qualification:</strong> {{ $tutorProfile->qualification }}</li>
                            <li><strong>Experience:</strong> {{ $tutorProfile->experience_years }} Years</li>
                            <li><strong>Teaching Mode:</strong> <span class="capitalize">{{ $tutorProfile->teaching_mode }}</span></li>
                            <li><strong>Max Students Per Session:</strong> {{ $tutorProfile->max_students }}</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Rates & Bookings</h4>
                        <ul class="mt-3 space-y-3 text-sm text-gray-900">
                            <li><strong>Price Per Hour:</strong> <span class="text-indigo-600 font-bold">{{ $tutorProfile->price_per_hour }} ETB/hr</span></li>
                            <li><strong>Total Reviews Received:</strong> {{ $tutorProfile->total_reviews }}</li>
                            <li><strong>Contact Email:</strong> {{ $user->email }}</li>
                            <li><strong>Contact Phone:</strong> {{ $user->phone_number }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Selected Grade Levels as styled badges -->
                <div class="mt-8 border-t border-gray-100 pt-8">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Grade Levels You Teach</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($tutorProfile->gradeLevels as $level)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $level->name }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500 italic">No grade levels selected yet.</span>
                        @endforelse
                    </div>
                </div>

                <!-- UPDATED: Selected Category & Subjects as separate badges -->
                <div class="mt-8 border-t border-gray-100 pt-8">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Teaching Specialty</h4>
                    
                    @if($tutorProfile->subjects->count() > 0)
                        <!-- Displays Category -->
                        <p class="text-sm text-gray-700 mb-2">
                            Main Category: <strong class="text-indigo-600">{{ $tutorProfile->subjects->first()->category->name }}</strong>
                        </p>
                        
                        <!-- Displays Subjects -->
                        <div class="flex flex-wrap gap-2">
                            @foreach($tutorProfile->subjects as $subject)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                    {{ $subject->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-sm text-gray-500 italic">No teaching specialty selected yet.</span>
                    @endif
                </div>

                
<!-- NEW: Weekly Availability Schedule displays on the Dashboard -->
<div class="mt-8 border-t border-gray-100 pt-8">
    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Weekly Availability Schedule</h4>
    
    @if($schedules->count() > 0)
        <div class="overflow-hidden border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200 bg-gray-50">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Day</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Start Time</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">End Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white text-sm text-gray-700">
                    @foreach($schedules as $sched)
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
        <span class="text-sm text-gray-500 italic">No availability slots configured yet. Click "Edit Profile" to add.</span>
    @endif
</div>

                <!-- Tutor Biography -->
                <div class="mt-8 border-t border-gray-100 pt-8">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">About Me / Professional Bio</h4>
                    <p class="text-gray-700 leading-relaxed text-sm bg-gray-50 p-6 rounded-md border border-gray-200">
                        {{ $tutorProfile->bio ?: 'No professional bio added yet.' }}
                    </p>
                </div>

            </div>
        </div>
    </div>



@endsection