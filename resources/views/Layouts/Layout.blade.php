<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find and book experienced, professional tutors in Ethiopia with TutorLink. Search by subject, city, and sub-city.">
    <title>@yield('title', 'TutorLink')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- GLOBAL TOP HEADER BAR -->
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

                <!-- Icons (Notification, Messages, Settings) -->
                <div class="flex items-center space-x-6">
                    <!-- Messages Icon -->
                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition" title="Messages">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </a>

                    <!-- Notification Icon (Updated with IDs for Javascript targets) -->
                    @auth
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('read_at', false)->count();
                        @endphp
                    @endauth
                    <a href="{{ route('notifications.index') }}" id="notification-link" class="text-gray-500 hover:text-indigo-600 transition relative inline-flex items-center justify-center p-1 rounded-full" title="Notifications">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span id="notification-badge" class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500 text-[9px] font-bold text-white text-center leading-4 shadow-sm">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Settings Icon -->
                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition" title="Settings">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>

                    <!-- Logout Form -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <!-- CONTENT PLACEHOLDER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    <!-- REAL-TIME JAVASCRIPT ALERT & SOUND ENGINE -->
    @auth
    <script>
        let currentUnreadCount = {{ $unreadCount ?? 0 }};
        let isAudioUnlocked = false;

        // 1. AUDIO UNLOCKER: Unlocks browser autoplay rules on the user's first click
        function unlockAudioContext() {
            if (isAudioUnlocked) return;

            let silentAudio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-120.wav');
            silentAudio.volume = 0; // Play silently to unlock
            silentAudio.play()
                .then(() => {
                    isAudioUnlocked = true;
                    // Remove listener once successfully unlocked
                    document.removeEventListener('click', unlockAudioContext);
                })
                .catch(e => console.log("Audio unlock deferred until user interaction."));
        }

        // Listen for first click anywhere on the page to unlock audio safely
        document.addEventListener('click', unlockAudioContext);

        function checkNotifications() {
            fetch("{{ route('notifications.unread.count') }}")
                .then(response => response.json())
                .then(data => {
                    const newCount = data.unread_count;

                    if (newCount > currentUnreadCount) {
                        currentUnreadCount = newCount;

                        


                   let alertSound = new Audio('/sounds/notification.mp3');



                        alertSound.volume = 0.8; // Set volume to 80%
                        alertSound.play().catch(error => {
                            console.log("Audio playback was blocked. Please click on the page to enable sound.");
                        });

                        updateBadgeDOM(newCount);
                    } 
                    else if (newCount !== currentUnreadCount) {
                        currentUnreadCount = newCount;
                        updateBadgeDOM(newCount);
                    }
                })
                .catch(error => console.error("Error fetching notifications:", error));
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