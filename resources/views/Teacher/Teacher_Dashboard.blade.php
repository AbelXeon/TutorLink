@extends('Layouts.Layout')

@section('title', 'Teacher Dashboard - TutorLink')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .td-dashboard { font-family: 'Inter', sans-serif; }
    .td-display {
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .td-card {
        background: #ffffff;
        border: 1px solid rgba(10,10,10,0.14);
        border-top: 3px solid #0a0a0a;
        border-radius: 0;
    }
    .td-card.td-accent-blue { border-top-color: #1350e0; }
    .td-section-label {
        font-size: 0.72rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #8a8a8a;
    }
    .td-icon-row { display: flex; align-items: flex-start; gap: 0.65rem; }
    .td-icon-row svg { color: #1350e0; flex-shrink: 0; margin-top: 2px; }
    .td-chip {
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(10,10,10,0.14);
        padding: 0.4rem 0.85rem;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 0;
    }
    .td-chip.td-chip-ink { color: #0a0a0a; }
    .td-chip.td-chip-blue { color: #1350e0; border-color: rgba(19,80,224,0.35); }
    .td-btn-primary {
        background: #0a0a0a;
        color: #fff;
        border-radius: 0;
        transition: background-color .15s ease;
    }
    .td-btn-primary:hover { background: #1350e0; }
    .td-btn-outline {
        background: #fff;
        color: #0a0a0a;
        border: 1px solid rgba(10,10,10,0.14);
        border-radius: 0;
        transition: background-color .15s ease, color .15s ease;
    }
    .td-btn-outline:hover { background: #0a0a0a; color: #fff; }
    .td-btn-decline {
        background: #fff;
        color: #dc2626;
        border: 1px solid rgba(220,38,38,0.3);
        border-radius: 0;
        transition: background-color .15s ease, color .15s ease;
    }
    .td-btn-decline:hover { background: #dc2626; color: #fff; }
    .td-table th {
        font-size: 0.68rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #8a8a8a;
        border-bottom: 1px solid rgba(10,10,10,0.14);
    }
    .td-table td { border-bottom: 1px solid rgba(10,10,10,0.08); }
    .td-table tr:last-child td { border-bottom: none; }
</style>

<!-- MAIN BODY -->
<div class="td-dashboard max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-8">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="td-card mb-6 overflow-hidden">
        <div class="h-28" style="background: #0a0a0a;"></div>

        <div class="px-8 pb-8 relative">
            <div class="absolute -top-14 left-8">
                @if($user->profile_image)
                    <img class="h-28 w-28 rounded-full object-cover border-4 border-white" src="{{ asset('storage/' . $user->profile_image) }}" alt="Tutor Photo">
                @else
                    <div class="h-28 w-28 border-4 border-white rounded-full flex items-center justify-center td-display text-3xl" style="background: #f5f4f1; color: #1350e0;">
                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                    </div>
                @endif
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('tutor.profile.edit') }}" class="td-btn-outline inline-flex items-center gap-2 justify-center py-2 px-4 text-sm font-medium">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 20h4L18.5 9.5a2.1 2.1 0 0 0-3-3L5 17v3Z" stroke-linejoin="round"/>
                        <path d="M14 6l4 4" stroke-linecap="round"/>
                    </svg>
                    Edit Profile
                </a>
            </div>

            <div class="mt-6">
                <h2 class="text-3xl td-display text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Username: {{ $user->username }}</p>
                <p class="inline-flex items-center gap-1.5 text-sm font-semibold mt-2" style="color: #1350e0;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                    Active Tutor
                </p>
            </div>
        </div>
    </div>

    <!-- Teaching Overview & Rates -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="td-card td-accent-blue p-6">
            <h4 class="td-section-label mb-4">Teaching Overview</h4>
            <ul class="space-y-3.5 text-sm text-gray-900">
                <li class="td-icon-row">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3L2 8L12 13L22 8L12 3Z" stroke-linejoin="round"/>
                        <path d="M6 10.5V16C6 16 8.5 19 12 19C15.5 19 18 16 18 16V10.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span><strong>Highest Qualification:</strong> {{ $tutorProfile->qualification }}</span>
                </li>
                <li class="td-icon-row">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7V12L15.5 14" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span><strong>Experience:</strong> {{ $tutorProfile->experience_years }} Years</span>
                </li>
                <li class="td-icon-row">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="12" rx="0.5"/>
                        <path d="M3 13H21" stroke-linecap="round"/>
                        <path d="M7 20H17" stroke-linecap="round"/>
                    </svg>
                    <span><strong>Teaching Mode:</strong> <span class="capitalize">{{ $tutorProfile->teaching_mode }}</span></span>
                </li>
                <li class="td-icon-row">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="8" r="3"/>
                        <path d="M2.5 19C2.5 15.5 5.4 13.5 9 13.5C12.6 13.5 15.5 15.5 15.5 19" stroke-linecap="round"/>
                        <circle cx="17" cy="8.5" r="2.3"/>
                        <path d="M15 13.7C18 13.9 20 15.7 20.5 18.6" stroke-linecap="round"/>
                    </svg>
                    <span><strong>Max Students Per Session:</strong> {{ $tutorProfile->max_students }}</span>
                </li>
            </ul>
        </div>

        <div class="td-card p-6">
            <h4 class="td-section-label mb-4">Rates &amp; Bookings</h4>
            <ul class="space-y-3.5 text-sm text-gray-900">
                <li class="td-icon-row">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2V22" stroke-linecap="round"/>
                        <path d="M17 6.5C17 5.1 14.8 4 12 4C9.2 4 7 5.1 7 6.5C7 7.9 9.2 9 12 9C14.8 9 17 10.1 17 11.5C17 12.9 14.8 14 12 14C9.2 14 7 12.9 7 11.5" stroke-linecap="round"/>
                    </svg>
                    <span><strong>Price Per Hour:</strong> <span class="font-bold" style="color: #1350e0;">{{ $tutorProfile->price_per_hour }} ETB/hr</span></span>
                </li>
                <li class="td-icon-row">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color:#f59e0b;">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.922-.755 1.688-1.54 1.118l-3.367-2.448a1 1 0 00-1.175 0l-3.367 2.448c-.784.57-1.838-.196-1.539-1.118l1.286-3.955a1 1 0 00-.363-1.118L2.02 9.382c-.784-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.955z"/>
                    </svg>
                    <span><strong>Tutor Score Rating:</strong> <span class="font-bold" style="color:#b45309;">{{ number_format($averageRating, 1) }} ({{ $reviews->count() }} reviews)</span></span>
                </li>
                <li class="td-icon-row">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="0.5"/>
                        <path d="M3 6.5L12 13L21 6.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span><strong>Contact Email:</strong> {{ $user->email }}</span>
                </li>
                <li class="td-icon-row">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h4l2 5-2.5 1.5a12 12 0 0 0 6 6L15 14l5 2v4c0 1-1 2-2 2C10 22 2 14 2 6c0-1 1-2 2-2Z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span><strong>Contact Phone:</strong> {{ $user->phone_number }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- 1. Pending Lesson Requests Panel -->
    <div class="td-card mb-6 p-6">
        <h4 class="td-section-label mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16v12H8l-4 4V4Z" stroke-linejoin="round"/>
            </svg>
            Pending Booking Requests
        </h4>

        @if($pendingBookings->count() > 0)
            <div class="space-y-4">
                @foreach($pendingBookings as $booking)
                    <div class="p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" style="background: #f5f4f1; border: 1px solid rgba(10,10,10,0.1);">
                        <div class="space-y-1.5">
                            <p class="text-sm text-gray-900 font-bold">
                                Request from: {{ $booking->student->first_name }} {{ $booking->student->last_name }}
                            </p>
                            <p class="text-xs font-semibold inline-flex items-center gap-1.5" style="color: #1350e0;">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="5" width="18" height="16" rx="0.5"/>
                                    <path d="M3 10H21" stroke-linecap="round"/>
                                    <path d="M8 3V6" stroke-linecap="round"/>
                                    <path d="M16 3V6" stroke-linecap="round"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }} at
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </p>
                            @if($booking->note)
                                <p class="text-xs text-gray-600 bg-white p-2.5 border border-gray-100 mt-2 italic max-w-lg">
                                    "{{ $booking->note }}"
                                </p>
                            @endif
                        </div>

                        <!-- Accept / Decline Action Buttons -->
                        <div class="flex gap-2 flex-shrink-0 w-full md:w-auto">
                            <!-- Accept Form -->
                            <form action="{{ route('bookings.accept', $booking->id) }}" method="POST" class="w-1/2 md:w-auto">
                                @csrf
                                <button type="submit" class="td-btn-primary w-full py-2 px-4 text-xs font-bold">
                                    Accept
                                </button>
                            </form>

                            <!-- Decline Form -->
                            <form action="{{ route('bookings.reject', $booking->id) }}" method="POST" class="w-1/2 md:w-auto">
                                @csrf
                                <button type="submit" class="td-btn-decline w-full py-2 px-4 text-xs font-bold">
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">No pending lesson requests at the moment.</p>
        @endif
    </div>

    <!-- NEW: Active Students & Lessons Panel -->
    <div class="td-card td-accent-blue mb-6 p-6">
        <h4 class="td-section-label mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="8" r="3"/>
                <path d="M2.5 19C2.5 15.5 5.4 13.5 9 13.5C12.6 13.5 15.5 15.5 15.5 19" stroke-linecap="round"/>
                <circle cx="17" cy="8.5" r="2.3"/>
                <path d="M15 13.7C18 13.9 20 15.7 20.5 18.6" stroke-linecap="round"/>
            </svg>
            My Active Students &amp; Lessons
        </h4>

        @if($activeBookings->count() > 0)
            <div class="td-table overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Scheduled Date</th>
                            <th class="px-4 py-3 text-left">Time Slot</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @foreach($activeBookings as $active)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {{ $active->student->first_name }} {{ $active->student->last_name }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                                    {{ \Carbon\Carbon::parse($active->session_date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs font-semibold" style="color: #1350e0;">
                                    {{ \Carbon\Carbon::parse($active->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($active->end_time)->format('g:i A') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('messages.show', $active->student->username) }}" class="td-btn-outline inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold select-none">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16v12H8l-4 4V4Z" stroke-linejoin="round"/>
                                        </svg>
                                        Chat with Student
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8" style="background:#f5f4f1; border: 1px dashed rgba(10,10,10,0.14);">
                <p class="text-sm text-gray-500 italic">No active students or scheduled lessons at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Selected Grade Levels as styled chips -->
    <div class="td-card mb-6 p-6">
        <h4 class="td-section-label mb-3 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 3L2 8L12 13L22 8L12 3Z" stroke-linejoin="round"/>
                <path d="M6 10.5V16C6 16 8.5 19 12 19C15.5 19 18 16 18 16V10.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Grade Levels You Teach
        </h4>
        <div class="flex flex-wrap gap-2">
            @forelse($tutorProfile->gradeLevels as $level)
                <span class="td-chip td-chip-ink">{{ $level->name }}</span>
            @empty
                <span class="text-sm text-gray-500 italic">No grade levels selected yet.</span>
            @endforelse
        </div>
    </div>

    <!-- Selected Category & Subjects as separate chips -->
    <div class="td-card td-accent-blue mb-6 p-6">
        <h4 class="td-section-label mb-3 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6C3 5 4 4 5 4H9L11 6H19C20 6 21 7 21 8V17C21 18 20 19 19 19H5C4 19 3 18 3 17V6Z" stroke-linejoin="round"/>
            </svg>
            Teaching Specialty
        </h4>

        @if($tutorProfile->subjects->count() > 0)
            <p class="text-sm text-gray-700 mb-3">
                Main Category: <strong style="color: #1350e0;">{{ $tutorProfile->subjects->first()->category?->name }}</strong>
            </p>
            <div class="flex flex-wrap gap-2">
                @foreach($tutorProfile->subjects as $subject)
                    <span class="td-chip td-chip-blue">{{ $subject->name }}</span>
                @endforeach
            </div>
        @else
            <span class="text-sm text-gray-500 italic">No teaching specialty selected yet.</span>
        @endif
    </div>

    <!-- Weekly Availability Schedule -->
    <div class="td-card mb-6 p-6">
        <h4 class="td-section-label mb-3 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="5" width="18" height="16" rx="0.5"/>
                <path d="M3 10H21" stroke-linecap="round"/>
                <path d="M8 3V6" stroke-linecap="round"/>
                <path d="M16 3V6" stroke-linecap="round"/>
            </svg>
            Weekly Availability Schedule
        </h4>

        @if($schedules->count() > 0)
            <div class="td-table overflow-hidden">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">Day</th>
                            <th class="px-4 py-3 text-left">Start Time</th>
                            <th class="px-4 py-3 text-left">End Time</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @foreach($schedules as $sched)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $sched->day_of_week }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($sched->start_time)->format('g:i A') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($sched->end_time)->format('g:i A') }}</td>
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
    <div class="td-card td-accent-blue mb-6 p-6">
        <h4 class="td-section-label mb-3 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="8" r="3.5"/>
                <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
            </svg>
            About Me / Professional Bio
        </h4>
        <p class="text-gray-700 leading-relaxed text-sm p-6" style="background:#f5f4f1; border: 1px solid rgba(10,10,10,0.1);">
            {{ $tutorProfile->bio ?: 'No professional bio added yet.' }}
        </p>
    </div>

    <!-- REVIEWS PANEL DISPLAY (Only visible to the logged-in tutor) -->
    <div class="td-card p-6">
        <h4 class="td-section-label mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color:#f59e0b;">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.922-.755 1.688-1.54 1.118l-3.367-2.448a1 1 0 00-1.175 0l-3.367 2.448c-.784.57-1.838-.196-1.539-1.118l1.286-3.955a1 1 0 00-.363-1.118L2.02 9.382c-.784-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.955z"/>
            </svg>
            Reviews Received from Students
        </h4>

        @if($reviews->count() > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="p-6" style="background:#f5f4f1; border: 1px solid rgba(10,10,10,0.1);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm" style="background:#fff; color:#1350e0; border: 1px solid rgba(19,80,224,0.3);">
                                    {{ substr($review->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-gray-900">{{ $review->first_name }} {{ $review->last_name }}</h5>
                                    <span class="text-[10px] text-gray-400 font-semibold">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" style="color: {{ $i <= $review->rating ? '#f59e0b' : '#d4d4d4' }};">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.922-.755 1.688-1.54 1.118l-3.367-2.448a1 1 0 00-1.175 0l-3.367 2.448c-.784.57-1.838-.196-1.539-1.118l1.286-3.955a1 1 0 00-.363-1.118L2.02 9.382c-.784-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.955z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600 bg-white p-4 border border-gray-100 italic">
                            "{{ $review->comment }}"
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">No reviews received from your students yet.</p>
        @endif
    </div>

</div>
@endsection