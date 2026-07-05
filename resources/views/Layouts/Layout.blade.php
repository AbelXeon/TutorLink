<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find and book experienced, professional tutors in Ethiopia with TutorLink. Search by subject, city, and sub-city.">
    <title>@yield('title', 'TutorLink')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root{
            --ink:#0a0a0a;
            --paper:#f5f4f1;
            --white:#ffffff;
            --blue:#1350e0;
            --blue-dark:#0d3aa8;
            --line: rgba(10,10,10,0.14);
        }
        * { box-sizing: border-box; }
        html { overflow-x: hidden; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .display-font {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .nav-icon-btn { color: #6b6b6b; transition: color .15s ease; }
        .nav-icon-btn:hover { color: var(--blue); }
        .role-badge {
            border: 1px solid var(--line);
            color: var(--blue);
            font-size: 0.65rem;
            letter-spacing: 0.06em;
            padding: 0.15rem 0.45rem;
            white-space: nowrap;
        }
        .notif-panel {
            border-radius: 0;
            border: 1px solid var(--line);
            box-shadow: 0 8px 24px rgba(10,10,10,0.08);
        }
        .notif-item { border-bottom: 1px solid var(--line); }
        .notif-item:last-child { border-bottom: none; }
        .notif-item.is-unread { border-left: 3px solid var(--blue); background: rgba(19,80,224,0.04); }
        .notif-item.is-read { border-left: 3px solid transparent; }

        .notif-badge {
            background: var(--blue);
            animation: notif-pulse 2.2s ease-in-out infinite;
        }
        @keyframes notif-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(19,80,224,0.45); }
            50%      { box-shadow: 0 0 0 5px rgba(19,80,224,0); }
        }

        /* Mobile safety: brand + badge + 3 icons all need to fit in one row
           on narrow phones (~320px) without wrapping or overflowing. */
        @media (max-width: 480px) {
            .brand-text { font-size: 1.05rem; }
            .role-badge { font-size: 0.58rem; padding: 0.12rem 0.35rem; }
            .nav-icons-row { gap: 0.65rem !important; }
            .nav-icon-btn svg { height: 1.35rem; width: 1.35rem; }
        }
        @media (max-width: 360px) {
            .role-badge { display: none; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between" style="background: var(--paper);">

    <!-- 1. GLOBAL TOP HEADER BAR -->
    <nav style="background: var(--white); border-bottom: 1px solid var(--line);">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <!-- Logo & Brand -->
                <div class="flex items-center gap-2 min-w-0 shrink-0">
                    @php
                        // Dynamically resolve dashboard route based on user roles
                        $dashboardRoute = route('Landing');
                        if (Auth::check()) {
                            $role = strtolower(Auth::user()->role?->role_type ?? '');
                            if ($role === 'teacher') {
                                $dashboardRoute = route('tutor.dashboard');
                            } elseif ($role === 'student') {
                                $dashboardRoute = route('student.dashboard');
                            }
                        }
                    @endphp
                    <!-- Brand text is now clickable and routes users to their specific dashboard -->
                    <a href="{{ $dashboardRoute }}" class="brand-text display-font text-xl shrink-0" style="color: var(--ink);">TutorLink</a>

                    <span class="role-badge capitalize shrink-0">
                        {{ strtolower(Auth::user()->role?->role_type ?? 'User') }}
                    </span>
                </div>

                <!-- Icons (Notification Dropdown, Messages, Settings) -->
                <div class="nav-icons-row flex items-center gap-3 sm:gap-6 shrink-0">
                    <!-- Messages Icon -->
                    <a href="{{ route('messages.index') }}" class="nav-icon-btn" title="Messages">
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
                        <button type="button" id="notification-link" class="nav-icon-btn relative flex items-center justify-center p-1 focus:outline-none" title="Notifications">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span id="notification-badge" class="notif-badge absolute -top-0.5 -right-0.5 block h-4 w-4 rounded-full text-[9px] font-bold text-white text-center leading-4">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- DROPDOWN PANEL (Hidden by default) -->
                        <div id="notification_dropdown" class="notif-panel hidden absolute right-0 mt-2 w-[90vw] max-w-xs sm:w-80 bg-white z-50 overflow-hidden">
                            <div class="px-4 py-3 flex items-center justify-between" style="background: var(--paper); border-bottom: 1px solid var(--line);">
                                <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">Alerts</span>
                                @if(isset($unreadCount) && $unreadCount > 0)
                                    <form action="{{ route('notifications.read.all') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold" style="color: var(--blue);">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                @forelse($recentNotifications ?? [] as $notif)
                                    <div class="notif-item {{ !$notif->read_at ? 'is-unread' : 'is-read' }} p-3 text-xs transition">
                                        <div class="flex justify-between items-baseline mb-0.5">
                                            <span class="font-bold text-gray-900 truncate pr-4">{{ $notif->title }}</span>
                                            <span class="text-[9px] text-gray-400 flex-shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 line-clamp-2">{{ $notif->message }}</p>
                                        @if(!$notif->read_at)
                                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="mt-1">
                                                @csrf
                                                <button type="submit" class="text-[10px] font-bold" style="color: var(--blue);">
                                                    View Details &rarr;
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 italic p-6 text-center">No alerts found.</p>
                                @endforelse
                            </div>

                            <a href="{{ route('notifications.index') }}" class="block text-center py-2 text-[10px] font-bold hover:opacity-80" style="background: var(--paper); border-top: 1px solid var(--line); color: var(--blue);">
                                See all notifications
                            </a>
                        </div>
                    </div>

                    <!-- Settings Icon -->
                    <button type="button" id="open_settings_btn" class="nav-icon-btn focus:outline-none" title="Settings">
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">
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
                    badge.className = 'notif-badge absolute -top-0.5 -right-0.5 block h-4 w-4 rounded-full text-[9px] font-bold text-white text-center leading-4';
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
