@extends('Layouts.Layout')

@section('title', 'Student Dashboard - TutorLink')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap');

    :root{
        --ink:#0a0a0a;
        --paper:#f5f4f1;
        --white:#ffffff;
        --blue:#1350e0;
        --blue-dark:#0d3aa8;
        --line: rgba(10,10,10,0.14);
    }
    .dash-wrap { font-family: 'Inter', sans-serif; }
    .display-font {
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .swiss-panel { border-radius: 0; border: 1px solid var(--line); box-shadow: none; background: var(--white); }
    .panel-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        letter-spacing: 0.13em;
        text-transform: uppercase;
        color: #8a8a8a;
        margin-bottom: 1rem;
    }
    .panel-title svg { color: var(--blue); }
    .welcome-band {
        background: var(--ink);
        position: relative;
        overflow: hidden;
    }
    .welcome-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
        background-size: 32px 32px;
    }
    .welcome-ring {
        position: absolute;
        right: -80px;
        bottom: -100px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        border: 1.5px solid rgba(19,80,224,0.4);
        background: radial-gradient(circle at 35% 30%, rgba(19,80,224,0.18), transparent 70%);
    }
    .btn-swiss-white {
        border-radius: 0;
        background: var(--white);
        color: var(--ink);
        border: 1px solid var(--white);
        transition: background-color .15s ease, color .15s ease;
    }
    .btn-swiss-white:hover { background: var(--paper); }
    .status-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 0;
        padding: 0.2rem 0.65rem;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .status-accepted { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-other { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .btn-chat {
        border-radius: 0;
        border: 1px solid rgba(19,80,224,.3);
        background: rgba(19,80,224,.06);
        color: var(--blue-dark);
        transition: background-color .15s ease;
    }
    .btn-chat:hover { background: rgba(19,80,224,.14); }
    .stat-box { border: 1px solid var(--line); padding: 1rem; }
    .stat-row {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.55rem 0;
        border-bottom: 1px solid var(--line);
    }
    .stat-row:last-child { border-bottom: none; }
    .stat-icon { color: var(--blue); flex-shrink: 0; margin-top: 2px; }
</style>

<div class="dash-wrap">

    <!-- Welcome Card -->
    <div class="welcome-band text-white p-8 mb-8">
        <div class="welcome-grid"></div>
        <div class="welcome-ring"></div>
        <div class="relative z-10">
            <h2 class="text-3xl display-font">Hello, {{ $user->first_name }}!</h2>
            <p class="mt-2 text-sm max-w-xl" style="color:#b5b5b5;">
                Welcome to your Student Dashboard. Here you can track your active tutoring bookings, check messages, and connect with experienced tutors.
            </p>

            <!-- Primary Action: Browse Teachers -->
            <div class="mt-6">
                <a href="{{route('tutors.browse')}}" class="btn-swiss-white inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="M21 21L16.65 16.65" stroke-linecap="round"/>
                    </svg>
                    Browse &amp; Find Tutors
                </a>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Booked Tutor List Card -->
        <div class="lg:col-span-2 swiss-panel p-6">
            <p class="panel-title">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="5" width="18" height="16" rx="0.5"/>
                    <path d="M3 10H21" stroke-linecap="round"/>
                    <path d="M8 3V6M16 3V6" stroke-linecap="round"/>
                </svg>
                My Booked Tutors
            </p>

            @if($bookings->count() > 0)
                
                <!-- DESKTOP VIEW: CLEAN LAYOUT TABLE -->
                <div class="hidden sm:block overflow-x-auto border" style="border-color: var(--line);">
                    <table class="min-w-full divide-y" style="border-color: var(--line);">
                        <thead style="background: var(--paper);">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-white text-sm" style="border-color: var(--line);">
                            @foreach($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 flex items-center">
                                        <span>{{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}</span>

                                        <!-- If booking is accepted, show direct Chat Button -->
                                        @if($booking->status == 'accepted')
                                            @php
                                                $conv = \App\Models\Conversation::where('student_id', Auth::id())
                                                    ->where('tutor_id', $booking->tutor_id)
                                                    ->first();
                                            @endphp
                                            <a href="{{ route('messages.show', $booking->tutor->username) }}" class="btn-chat text-[10px] font-bold px-2 py-1 ml-3 inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M4 5h16v11H8l-4 4V5Z" stroke-linejoin="round"/>
                                                </svg>
                                                Chat
                                            </a>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }} at
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->status == 'pending')
                                            <span class="status-chip status-pending">
                                                {{ $booking->status }}
                                            </span>
                                        @elseif($booking->status == 'accepted')
                                            <span class="status-chip status-accepted">
                                                {{ $booking->status }}
                                            </span>
                                        @else
                                            <span class="status-chip status-other">
                                                {{ $booking->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE VIEW: RE-DESIGNED CARDS -->
                <div class="block sm:hidden space-y-4">
                    @foreach($bookings as $booking)
                        <div class="p-4 border bg-white flex flex-col justify-between" style="border-color: var(--line);">
                            <!-- Top Header: Name & Status -->
                            <div class="flex justify-between items-start gap-2 mb-3">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Tutor</span>
                                    <h4 class="font-bold text-gray-900 text-sm mt-0.5">
                                        {{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}
                                    </h4>
                                </div>
                                <div>
                                    @if($booking->status == 'pending')
                                        <span class="status-chip status-pending">
                                            {{ $booking->status }}
                                        </span>
                                    @elseif($booking->status == 'accepted')
                                        <span class="status-chip status-accepted">
                                            {{ $booking->status }}
                                        </span>
                                    @else
                                        <span class="status-chip status-other">
                                            {{ $booking->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Date and Time block details -->
                            <div class="text-xs text-gray-700 bg-[#f5f4f1] border border-gray-100 p-2.5 flex items-center gap-2 mb-3">
                                <svg class="w-3.5 h-3.5 text-[#1350e0]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="5" width="18" height="16" rx="0.5"/>
                                    <path d="M3 10H21" stroke-linecap="round"/>
                                </svg>
                                <span>
                                    {{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }} at 
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}
                                </span>
                            </div>

                            <!-- Accepted Chat button link -->
                            @if($booking->status == 'accepted')
                                <div class="border-t border-gray-100 pt-3 mt-1 flex justify-end">
                                    <a href="{{ route('messages.show', $booking->tutor->username) }}" class="btn-chat text-xs font-bold py-2 px-4 inline-flex items-center gap-2 w-full justify-center">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 5h16v11H8l-4 4V5Z" stroke-linejoin="round"/>
                                        </svg>
                                        Chat with Tutor
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

            @else
                <div class="text-center py-10" style="background: var(--paper); border: 1px dashed var(--line);">
                    <p class="text-gray-500 text-sm">You haven't booked any tutoring sessions yet.</p>
                    <a href="{{route('tutors.browse')}}" class="mt-3 inline-flex items-center text-xs font-bold" style="color: var(--blue);">
                        Find a tutor now &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Right: Summary Panel -->
        <div class="space-y-6">
            <!-- Stats Widget -->
            <div class="swiss-panel p-6">
                <p class="panel-title">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19V5M10 19V9M16 19V13M22 19H2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Activity Summary
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="stat-box">
                        <span class="block text-2xl display-font" style="color: var(--ink);">{{ $bookings->count() }}</span>
                        <span class="text-xs text-gray-500 font-medium">Total Lessons</span>
                    </div>
                    <div class="stat-box">
                        <span class="block text-2xl display-font" style="color: var(--blue);">
                            {{ $bookings->where('status', 'accepted')->count() }}
                        </span>
                        <span class="text-xs text-gray-500 font-medium">Active Lessons</span>
                    </div>
                </div>
            </div>

            <!-- Profile Details Box -->
            <div class="swiss-panel p-6">
                <p class="panel-title">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="3.5"/>
                        <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
                    </svg>
                    My Information
                </p>
                <div>
                    <div class="stat-row">
                        <svg class="stat-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="0.5"/><path d="M3 6.5L12 13L21 6.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div>
                            <span class="text-gray-500 block text-xs">Email</span>
                            <span class="font-semibold text-sm text-gray-900">{{ $user->email }}</span>
                        </div>
                    </div>
                    <div class="stat-row">
                        <svg class="stat-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h4l2 5-2.5 1.5a12 12 0 0 0 6 6L15 14l5 2v4c0 1-1 2-2 2C10 22 2 14 2 6c0-1 1-2 2-2Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div>
                            <span class="text-gray-500 block text-xs">Phone</span>
                            <span class="font-semibold text-sm text-gray-900">{{ $user->phone_number }}</span>
                        </div>
                    </div>
                    <div class="stat-row">
                        <svg class="stat-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21C12 21 19 14.5 19 9.5C19 5.4 15.9 2 12 2C8.1 2 5 5.4 5 9.5C5 14.5 12 21 12 21Z" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                        <div>
                            <span class="text-gray-500 block text-xs">Primary Address</span>
                            <span class="font-semibold text-sm text-gray-900">{{ $user->address }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection