@extends('Layouts.Layout')

@section('title', 'Notifications - TutorLink')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center border-b border-gray-200 pb-6 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900">Notifications</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your updates and platform alerts</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($notifications as $notif)
            <!-- Notification Card: Unread items have a light blue background -->
            <div class="p-5 rounded-lg border transition shadow-sm flex items-start gap-4 {{ !$notif->read_at ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-gray-200' }}">
                
                <!-- Icon Indicator based on Notification Type -->
                <div class="flex-shrink-0 p-2 rounded-md {{ !$notif->read_at ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500' }}">
                    @if($notif->notification_type == 'booking_request')
                        <!-- Booking Icon -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @elseif($notif->notification_type == 'message')
                        <!-- Message Icon -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    @else
                        <!-- System Alert Icon -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>

                <!-- Text Details -->
                <div class="flex-grow space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-gray-900">{{ $notif->title }}</h4>
                        <span class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 pr-12">{{ $notif->message }}</p>
                    
                    <!-- Action Link (if provided) -->
                    @if($notif->action_url && !$notif->read_at)
                        <div class="pt-2">
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                    View Details &rarr;
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Mark as Read Button (Shown only for unread items) -->
                @if(!$notif->read_at)
                    <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="flex-shrink-0 self-center">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 border border-indigo-200 bg-white hover:bg-indigo-50 px-3 py-1.5 rounded-md transition shadow-sm">
                            Mark Read
                        </button>
                    </form>
                @endif

            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-lg border border-gray-200 p-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-500 mt-4 text-sm font-medium">Your inbox is clean. No notifications found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection