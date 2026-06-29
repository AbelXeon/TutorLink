<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - TutorLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🎓 TutorLink Student Portal</a>
            <div class="d-flex align-items-center">

                 <!-- 💬 Messages Link -->
                <a href="{{ route('Messages.Message') }}" class="btn btn-outline-light btn-sm me-3">
                    💬 Messages
                </a>

<a href="{{ route('Notification.Notification') }}" id="notification-bell-btn" class="btn btn-outline-light btn-sm position-relative me-3">
    🔔 Notifications
    <span id="notification-badge-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadNotificationsCount > 0 ? '' : 'd-none' }}">
        {{ $unreadNotificationsCount }}
    </span>
</a>

                <!-- Logout Form -->
                <form action="{{ route('Auth.Logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Welcome card -->
                <div class="card shadow border-0 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;" alt="Profile Image">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 70px; height: 70px; font-size: 1.5rem;">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="fw-bold mb-0">Welcome, {{ $user->first_name }}!</h3>
                            <p class="text-muted mb-0">Manage your bookings or discover new tutors.</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Requests Status List -->
                <div class="card shadow border-0 p-4 mb-4 bg-white">
                    <h4 class="fw-bold text-dark mb-3">🗓️ Your Lesson Requests</h4>
                    
                    @forelse($bookings as $booking)
                        <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-0 text-dark">Tutor: {{ $booking->tutor_first_name }} {{ $booking->tutor_last_name }}</h5>
                                
                                <!-- Status badge indicator -->
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning text-dark p-2 fs-6">Pending Approval</span>
                                @elseif($booking->status == 'accepted')
                                    <span class="badge bg-success p-2 fs-6">Approved 🎉</span>
                                @else
                                    <span class="badge bg-danger p-2 fs-6">Declined ❌</span>
                                @endif
                            </div>

                            <p class="text-secondary small mb-2">Message: <em>"{{ $booking->message }}"</em></p>

                            <h6 class="fw-semibold text-dark mb-1">Proposed Slots:</h6>
                            <div class="mt-1">
                                @if(isset($booking->slots) && is_array($booking->slots))
                                    @foreach($booking->slots as $slot)
                                        <span class="badge bg-secondary me-2 mb-1 p-2">🗓️ {{ $slot['date'] }} at 🕒 {{ $slot['time'] }}</span>
                                    @endforeach
                                @endif

                                <!-- If approved, show chat link -->
                                @if($booking->status == 'accepted')
                                    <a href="{{ route('Messages.Message') }}" class="btn btn-sm btn-outline-primary ms-auto">💬 Chat with Tutor</a>
                                @endif


                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">You have not booked any lessons yet.</p>
                    @endforelse
                </div>

                <!-- Call to action card -->
                <div class="card shadow border-0 p-5 text-center bg-white">
                    <div class="mb-3 fs-1">🔍</div>
                    <h4 class="fw-bold mb-3">Find Your Perfect Tutor</h4>
                    <p class="text-muted px-md-5 mb-4">
                        Search and connect with verified, professional tutors teaching programming, school subjects, languages, and specific skills.
                    </p>
                    <div class="d-grid gap-2 col-md-6 mx-auto">
                        <a href="{{ route('Search.Tutor_View') }}" class="btn btn-primary btn-lg shadow-sm">
                            Browse All Tutors
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Floating Live Notification Alert Box -->
    <div id="notification-toast" class="position-fixed bottom-0 start-0 m-3 p-3 bg-dark text-white rounded shadow-lg d-none" style="z-index: 1055; width: 300px;">
        <div class="d-flex align-items-center gap-2">
            <span class="fs-4">🔔</span>
            <div>
                <strong class="d-block">New Alert!</strong>
                <span class="small text-white-50">You received a new update. Check your notifications.</span>
            </div>
            <button type="button" class="btn-close btn-close-white ms-auto align-self-start" onclick="document.getElementById('notification-toast').classList.add('d-none')"></button>
        </div>
    </div>

    <!-- Live check script -->
    <script>
        let currentCount = {{ $unreadNotificationsCount }};

        function playNotificationChime() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                osc.frequency.setValueAtTime(880.00, ctx.currentTime + 0.12);
                
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
                
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.5);
            } catch (e) {
                console.log("Audio play blocked by browser sandbox permissions.");
            }
        }

        function pollNotifications() {
            fetch("{{ route('api.notifications.unread') }}")
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge-count');
                    
                    if (data.count > currentCount) {
                        currentCount = data.count;
                        
                        if (badge) {
                            badge.textContent = data.count;
                            badge.classList.remove('d-none');
                        }

                        playNotificationChime();

                        const toast = document.getElementById('notification-toast');
                        toast.classList.remove('d-none');
                        setTimeout(() => {
                            toast.classList.add('d-none');
                        }, 5000);
                    } else if (data.count === 0 && badge) {
                        badge.classList.add('d-none');
                        currentCount = 0;
                    }
                })
                .catch(err => console.error("Error syncing notifications:", err));
        }

        setInterval(pollNotifications, 10000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>