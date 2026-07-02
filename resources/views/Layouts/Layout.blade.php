<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find and book experienced, professional tutors in Ethiopia with TutorLink. Search by subject, city, and sub-city.">
    <title>@yield('title', 'TutorLink')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">

    <!-- 1. GLOBAL TOP HEADER BAR -->
    <nav class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-2">
                    <span class="text-xl font-bold text-indigo-600">TutorLink</span>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded capitalize">
                        {{ strtolower(Auth::user()->role?->role_type ?? 'User') }}
                    </span>
                </div>

                <!-- Icons (Notification Dropdown, Messages, Settings) -->
                <div class="flex items-center space-x-6">
                    <!-- Messages Icon -->
                    <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-indigo-600 transition" title="Messages">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </a>

                    <!-- Notification Icon -->
                    @auth
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('read_at', false)->count();
                            $recentNotifications = \App\Models\Notification::where('user_id', Auth::id())
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                    @endauth
                    <div class="relative inline-block text-left" id="notification_dropdown_container">
                        <button type="button" id="notification-link" class="text-gray-500 hover:text-indigo-600 transition relative flex items-center justify-center p-1 rounded-full focus:outline-none" title="Notifications">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span id="notification-badge" class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500 text-[9px] font-bold text-white text-center leading-4 shadow-sm">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- DROPDOWN PANEL (Hidden by default) -->
                        <div id="notification_dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-150 flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">Alerts</span>
                                @if(isset($unreadCount) && $unreadCount > 0)
                                    <form action="{{ route('notifications.read.all') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                                @forelse($recentNotifications ?? [] as $notif)
                                    <div class="p-3 text-xs transition {{ !$notif->read_at ? 'bg-indigo-50/50' : '' }}">
                                        <div class="flex justify-between items-baseline mb-0.5">
                                            <span class="font-bold text-gray-900 truncate pr-4">{{ $notif->title }}</span>
                                            <span class="text-[9px] text-gray-400 flex-shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 line-clamp-2">{{ $notif->message }}</p>
                                        @if(!$notif->read_at)
                                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="mt-1">
                                                @csrf
                                                <button type="submit" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800">
                                                    View Details &rarr;
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 italic p-6 text-center">No alerts found.</p>
                                @endforelse
                            </div>

                            <a href="{{ route('notifications.index') }}" class="block text-center py-2 bg-gray-50 border-t border-gray-150 text-[10px] font-bold text-indigo-600 hover:bg-gray-100">
                                See all notifications
                            </a>
                        </div>
                    </div>

                    <!-- Settings Icon -->
                    <button type="button" id="open_settings_btn" class="text-gray-500 hover:text-indigo-600 transition focus:outline-none" title="Settings">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </nav>

    <!-- 2. CONTENT PLACEHOLDER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow">
        @yield('content')
    </main>

    @include('Setting.Setting')

    <!-- Global Footer included for all layout-extending pages -->
    @include('Layouts.Footer')

    <!-- REAL-TIME JAVASCRIPT ALERT & SOUND ENGINE -->
    @auth
    <script>
        let currentUnreadCount = {{ $unreadCount ?? 0 }};
        let isAudioUnlocked = false;

        const dropdownContainer = document.getElementById('notification_dropdown_container');
        const dropdownBtn = document.getElementById('notification-link');
        const dropdownPanel = document.getElementById('notification_dropdown');

        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownPanel.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!dropdownContainer.contains(e.target)) {
                dropdownPanel.classList.add('hidden');
            }
        });

        function unlockAudioContext() {
            if (isAudioUnlocked) return;

            let silentAudio = new Audio('/sounds/notification.mp3');
            silentAudio.volume = 0;
            silentAudio.play()
                .then(() => {
                    isAudioUnlocked = true;
                    document.removeEventListener('click', unlockAudioContext);
                })
                .catch(e => console.log("Audio unlock deferred."));
        }

        document.addEventListener('click', unlockAudioContext);

        function checkNotifications() {
            fetch("{{ route('notifications.unread.count') }}")
                .then(response => response.json())
                .then(data => {
                    const newCount = data.unread_count;
                    if (newCount > currentUnreadCount) {
                        currentUnreadCount = newCount;
                        let alertSound = new Audio('/sounds/notification.mp3');
                        alertSound.volume = 0.8;
                        alertSound.play().catch(e => console.log("Sound play deferred"));
                        updateBadgeDOM(newCount);
                    } 
                    else if (newCount !== currentUnreadCount) {
                        currentUnreadCount = newCount;
                        updateBadgeDOM(newCount);
                    }
                })
                .catch(error => console.error(error));
        }

        function updateBadgeDOM(count) {
            const link = document.getElementById('notification-link');
            let badge = document.getElementById('notification-badge');
            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                } else {
                    badge = document.createElement('span');
                    badge.id = 'notification-badge';
                    badge.className = 'absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500 text-[9px] font-bold text-white text-center leading-4 shadow-sm';
                    badge.textContent = count;
                    link.appendChild(badge);
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        }

        setInterval(checkNotifications, 10000);
    </script>
    @endauth

</body>
</html>