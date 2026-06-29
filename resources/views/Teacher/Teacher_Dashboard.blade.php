<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - TutorLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- TOP HEADER BAR -->
    <nav class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <div class="flex items-center space-x-2">
                    <span class="text-xl font-bold text-indigo-600">TutorLink</span>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tutor</span>
                </div>

                <!-- Header Icons -->
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition" title="Messages">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </a>

                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition" title="Notifications">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </a>

                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition" title="Settings">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

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
</body>
</html>