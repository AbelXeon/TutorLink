<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - TutorLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">👨‍🏫 TutorLink Teacher Portal</a>
            <div class="d-flex align-items-center">

                 <!-- 💬 Messages Link -->
                <a href="{{ route('Messages.Message') }}" class="btn btn-outline-light btn-sm me-3">
                    💬 Messages
                </a>


                <!-- Update your Notification Bell to have IDs: id="notification-bell-btn" and id="notification-badge-count" -->
<a href="{{ route('Notification.Notification') }}" id="notification-bell-btn" class="btn btn-outline-light btn-sm position-relative me-3">
    🔔 Notifications
    <span id="notification-badge-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadNotificationsCount > 0 ? '' : 'd-none' }}">
        {{ $unreadNotificationsCount }}
    </span>
</a>

                <form action="{{ route('Auth.Logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <!-- Welcome Alert & Dynamic Status Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            
            <!-- Left Panel: Profile Quick Look -->
            <div class="col-md-4">
                <div class="card shadow border-0 text-center p-4">
                    <div class="mb-3">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" class="rounded-circle img-thumbnail" style="width: 130px; height: 130px; object-fit: cover;" alt="Profile Picture">
                        @else
                            <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 130px; height: 130px; font-size: 3rem;">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge bg-success p-2 fs-6 mb-2">Role: Teacher</span>
                    <span class="badge bg-secondary p-2 fs-6 d-block w-50 mx-auto">Status: {{ ucfirst($profile->availability_status ?? 'N/A') }}</span>
                    
                    <!-- Edit Profile Button -->
                    <div class="mt-4 d-grid">
                        <a href="{{ route('Teacher.Teacher_Profile_Edit') }}" class="btn btn-warning shadow-sm fw-bold">✏️ Edit Profile</a>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Tutor Details -->
            <div class="col-md-8">

                <!-- 📬 Incoming Booking Requests Section -->
                <div class="card shadow border-0 p-4 mb-4 bg-white">
                    <h4 class="fw-bold text-dark mb-4">📬 Incoming Booking Requests</h4>
                    
                    @forelse($bookings as $booking)
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold text-primary mb-0">{{ $booking->first_name }} {{ $booking->last_name }}</h5>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}</small>
                            </div>
                            
                            <p class="text-secondary small mb-2">Contact Email: {{ $booking->email }}</p>

                            <!-- Student custom message -->
                            <p class="bg-white p-2 border rounded text-secondary mb-3">
                                <em>"{{ $booking->message }}"</em>
                            </p>

                            <h6 class="fw-bold text-dark mb-2">Requested Time Slots:</h6>
                            <div class="mb-3">
                                @if(isset($booking->slots) && is_array($booking->slots))
                                    @foreach($booking->slots as $slot)
                                        <span class="badge bg-secondary me-2 p-2 mb-1">🗓️ {{ $slot['date'] }} at 🕒 {{ $slot['time'] }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Action Forms (Only show if booking status is pending) -->
                            @if($booking->status == 'pending')
                                <div class="d-flex gap-2">
                                    <form action="{{ route('Booking.accept', $booking->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm">Accept Request</button>
                                    </form>
                                    <form action="{{ route('Booking.decline', $booking->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm px-3 shadow-sm">Decline</button>
                                    </form>

                                    @if($booking->status == 'accepted')
                                        <a href="{{ route('Messages.Message') }}" class="btn btn-sm btn-outline-success">💬 Chat with Student</a>
                                    @endif

                                </div>
                            @else
                                <span class="badge bg-dark p-2 text-capitalize">Status: {{ $booking->status }}</span>
                            @endif

                        </div>
                    @empty
                        <p class="text-muted mb-0">No incoming pending lesson requests.</p>
                    @endforelse
                </div>

                <!-- Professional Information Card -->
                <div class="card shadow border-0 p-4 bg-white">
                    <h4 class="fw-bold text-success mb-4">Your Professional Information</h4>

                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="fw-semibold">Bio</h5>
                            <p class="text-secondary bg-light p-3 rounded border">
                                {{ $profile->bio ?? 'No bio added yet.' }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-semibold text-muted mb-1">Qualification</h6>
                            <p class="fs-5 fw-bold">{{ $profile->qualification ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-semibold text-muted mb-1">Experience</h6>
                            <p class="fs-5 fw-bold">{{ $profile->experience_years ?? '0' }} Years</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-semibold text-muted mb-1">Teaching Mode</h6>
                            <p class="fs-5 fw-bold text-capitalize">{{ $profile->mode ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-semibold text-muted mb-1">Hourly Rate</h6>
                            <p class="fs-5 fw-bold text-success">{{ $profile->price_per_hour ?? '0.00' }} ETB / hour</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-semibold text-muted mb-1">Grade Levels Taught</h6>
                            <p class="fs-5 fw-bold">{{ $profile->grade_level ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-semibold text-muted mb-1">Max Class Limit</h6>
                            <p class="fs-5 fw-bold">{{ $profile->max_students ?? '1' }} Students</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Subjects section -->
                    <h5 class="fw-bold mb-3">Subjects You Teach</h5>
                    <div class="mb-3">
                        @forelse($subjects as $subject)
                            <span class="badge bg-primary fs-6 p-2 me-2 mb-2">{{ $subject->name }}</span>
                        @empty
                            <span class="text-muted">No subjects selected yet.</span>
                        @endforelse
                    </div>

                    <hr class="my-4">

                    <!-- Schedule section -->
                    <h5 class="fw-bold mb-3">Weekly Schedule</h5>
                    @if($schedule)
                        <div class="bg-light p-3 rounded border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold text-success">{{ $schedule->day_of_week }}s</span>
                            </div>
                            <div>
                                <span>🕛 {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                            </div>
                        </div>
                    @else
                        <span class="text-muted">No schedule defined yet.</span>
                    @endif

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

        // Synthesizes a soft dual-tone chime sound
        function playNotificationChime() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.setValueAtTime(880.00, ctx.currentTime + 0.12); // A5
                
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
                
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.5);
            } catch (e) {
                console.log("Audio play blocked by browser sandbox permissions.");
            }
        }

        // Checks for new notifications
        function pollNotifications() {
            fetch("{{ route('api.notifications.unread') }}")
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge-count');
                    
                    if (data.count > currentCount) {
                        currentCount = data.count;
                        
                        // 1. Update navbar badge
                        if (badge) {
                            badge.textContent = data.count;
                            badge.classList.remove('d-none');
                        }

                        // 2. Play chime
                        playNotificationChime();

                        // 3. Display popup toast
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

        // Run check every 10 seconds
        setInterval(pollNotifications, 10000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>