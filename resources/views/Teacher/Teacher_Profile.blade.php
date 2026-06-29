<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->first_name }}'s Profile - TutorLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar / Navigation Back -->
<nav class="navbar navbar-dark bg-primary mb-5 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('Search.Tutor_View') }}">← Back to Browse Tutors</a>
    </div>
</nav>

<div class="container py-3">

    <!-- Tutor Profile Header Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <div class="row align-items-center">

                <!-- Profile Left column (Image + Rate) -->
                <div class="col-md-3 text-center border-end">
                    @if($profile->profile_image)
                        <img src="{{ asset('storage/' . $profile->profile_image) }}"
                             class="img-fluid rounded-circle mb-3 shadow-sm border" 
                             style="width: 170px; height: 170px; object-fit: cover;" alt="Profile Picture">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                             style="width: 170px; height: 170px; font-size: 4rem;">
                            {{ strtoupper(substr($profile->first_name, 0, 1)) }}
                        </div>
                    @endif

                    <h4 class="fw-bold">{{ $profile->first_name }} {{ substr($profile->last_name, 0, 1) }}.</h4>
                    <p class="text-muted mb-2">Tutor Profile</p>
                    <h5 class="text-success fw-bold">{{ $profile->price_per_hour }} ETB / hour</h5>

                    <a href="{{ route('Booking.show', $profile->id) }}" class="btn btn-primary w-100 mt-3 shadow-sm">
                        Book Lesson
                    </a>
                    
                </div>

                <!-- Profile Right column (Bio + Specs) -->
                <div class="col-md-9 ps-md-4">
                    <h3 class="fw-bold text-primary mb-3">About Me</h3>
                    <p class="text-secondary fs-5" style="line-height: 1.6;">
                        {{ $profile->bio }}
                    </p>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Experience:</strong> {{ $profile->experience_years }} Years</p>
                            <p class="mb-2"><strong>Qualification:</strong> {{ $profile->qualification }}</p>
                            <p class="mb-2"><strong>Subjects:</strong> {{ count($subjects) > 0 ? implode(', ', $subjects) : 'N/A' }}</p>
                            <p class="mb-2"><strong>Grade Level:</strong> {{ $profile->grade_level }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Mode:</strong> <span class="text-capitalize">{{ $profile->mode }}</span></p>
                            <p class="mb-2"><strong>Availability:</strong> <span class="badge bg-success">{{ ucfirst($profile->availability_status) }}</span></p>
                            <p class="mb-2"><strong>Max Students Limit:</strong> {{ $profile->max_students }} Students</p>
                            <p class="mb-2"><strong>Rating:</strong> ⭐ 5.0 (0 Reviews)</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Weekly Schedule Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold text-dark mb-0">📅 Weekly Schedule</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Day of the Week</th>
                        <th>Available Time Slot</th>
                    </tr>
                </thead>
                <tbody>
                    @if($schedule)
                        <tr>
                            <td class="fw-bold text-primary">{{ $schedule->day_of_week }}</td>
                            <td>🕛 {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No schedule defined yet.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reviews Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold text-dark mb-0">⭐ Student Reviews</h5>
        </div>
        <div class="card-body">
            @forelse($reviews as $review)
                <div class="border rounded p-3 mb-3 bg-light">
                    <h5 class="text-warning mb-1">
                        {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                    </h5>
                    <strong>{{ $review->first_name }} {{ substr($review->last_name, 0, 1) }}.</strong>
                    <p class="text-secondary mt-2 mb-0">
                        {{ $review->comment }}
                    </p>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <p class="mb-0">No reviews have been added for this tutor yet.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>