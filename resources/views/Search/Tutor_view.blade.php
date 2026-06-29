<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Tutors - TutorLink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar back to Student Dashboard -->
<nav class="navbar navbar-dark bg-primary mb-5 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('Student.Student_Dashboard') }}">← Back to Dashboard</a>
    </div>
</nav>

<div class="container py-3">

    <h2 class="fw-bold mb-4">Browse Tutors</h2>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search tutor or subject...">
        </div>
    </div>

    <!-- Tutors Dynamic Loop -->
    @forelse ($tutors as $tutor)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">

                <div class="row align-items-center g-3">

                    <!-- Tutor Image -->
                    <div class="col-md-2 text-center">
                        @if($tutor->profile_image)
                            <img src="{{ asset('storage/' . $tutor->profile_image) }}"
                                 class="img-fluid rounded border shadow-sm"
                                 style="width: 120px; height: 120px; object-fit: cover;"
                                 alt="Tutor Image">
                        @else
                            <div class="rounded border shadow-sm bg-secondary text-white d-inline-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px; font-size: 2.5rem;">
                                {{ strtoupper(substr($tutor->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Tutor Body Info -->
                    <div class="col-md-7">

                        <h4 class="fw-bold text-primary">{{ $tutor->first_name }} {{ substr($tutor->last_name, 0, 1) }}.</h4>

                        <p class="text-muted mb-1">
                            <strong>Subjects:</strong> 
                            @if(count($tutor->subjects) > 0)
                                <span class="text-dark">{{ implode(', ', $tutor->subjects) }}</span>
                            @else
                                <span class="text-muted">No subjects assigned</span>
                            @endif
                        </p>

                        <p class="text-muted mb-1">
                            <strong>Experience:</strong> 
                            <span class="text-dark">{{ $tutor->experience_years }} Years</span>
                        </p>

                        <p class="text-muted mb-1">
                            <strong>Price:</strong> 
                            <span class="text-success fw-bold">{{ $tutor->price_per_hour }} ETB / hour</span>
                        </p>

                        <p class="text-muted mb-2">
                            <strong>Mode:</strong> 
                            <span class="text-dark text-capitalize">{{ $tutor->mode }}</span>
                        </p>

                        <p class="mt-2 text-secondary">
                            {{ $tutor->bio }}
                        </p>

                    </div>

                    <!-- Actions -->
                    <div class="col-md-3 text-center border-start">

                        <h4 class="text-success fw-bold">{{ $tutor->price_per_hour }} ETB</h4>
                        <p class="text-warning">⭐⭐⭐⭐⭐ (0 Reviews)</p>

                        <a href="{{ route('Booking.show', $tutor->id) }}" class="btn btn-primary w-100 mt-3 shadow-sm">
                      Book Lesson
                      </a>
                      
                        <a href="{{ route('Teacher.Teacher_Profile', $tutor->id) }}" class="btn btn-outline-secondary w-100">
                              See More
                          </a>

                    </div>

                </div>

            </div>
        </div>
    @empty
        <!-- Empty State -->
        <div class="card p-5 text-center shadow-sm border-0">
            <div class="fs-1 mb-2">🔍</div>
            <h5 class="fw-bold text-muted">No tutors available yet.</h5>
            <p class="text-muted mb-0">Check back later or register a tutor profile to see them here.</p>
        </div>
    @endforelse

</div>

</body>
</html>