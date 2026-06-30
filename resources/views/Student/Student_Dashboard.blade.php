@extends('Layouts.Layout')

@section('title', 'Student Dashboard - TutorLink')

@section('content')

    <!-- Welcome Card -->
    <div class="bg-indigo-600 text-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-3xl font-extrabold">Hello, {{ $user->first_name }}! 👋</h2>
        <p class="mt-2 text-indigo-100 text-sm max-w-xl">
            Welcome to your Student Dashboard. Here you can track your active tutoring bookings, check messages, and connect with experienced tutors.
        </p>
        
        <!-- Primary Action: Browse Teachers -->
        <div class="mt-6">
            <a href="{{route('tutors.browse')}}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-md text-indigo-700 bg-white hover:bg-indigo-50 transition shadow-sm">
                🔍 Browse & Find Tutors
            </a>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Booked Tutor List Card -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">My Booked Tutors</h3>

            @if($bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white text-sm">
                            @foreach($bookings as $booking)
                                <tr>
                                    <!-- FIXED: Accessing $booking->tutor property directly -->
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                        {{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }} at 
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->status == 'pending')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 capitalize">
                                                {{ $booking->status }}
                                            </span>
                                        @elseif($booking->status == 'accepted')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 capitalize">
                                                {{ $booking->status }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 capitalize">
                                                {{ $booking->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-md border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">You haven't booked any tutoring sessions yet.</p>
                    <a href="{{route('tutors.browse')}}" class="mt-3 inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-500">
                        Find a tutor now &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Right: Summary Panel -->
        <div class="space-y-6">
            <!-- Stats Widget -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Activity Summary</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-indigo-50 p-4 rounded-md">
                        <span class="block text-2xl font-extrabold text-indigo-600">{{ $bookings->count() }}</span>
                        <span class="text-xs text-gray-500 font-medium">Total Lessons</span>
                    </div>
                    <div class="bg-green-50 p-4 rounded-md">
                        <span class="block text-2xl font-extrabold text-green-600">
                            {{ $bookings->where('status', 'accepted')->count() }}
                        </span>
                        <span class="text-xs text-gray-500 font-medium">Active Lessons</span>
                    </div>
                </div>
            </div>

            <!-- Profile Details Box -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">My Information</h4>
                <div class="space-y-3 text-sm text-gray-700">
                    <div>
                        <span class="text-gray-500 block text-xs">Email:</span>
                        <span class="font-semibold">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">Phone:</span>
                        <span class="font-semibold">{{ $user->phone_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">Primary Address:</span>
                        <span class="font-semibold">{{ $user->address }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection