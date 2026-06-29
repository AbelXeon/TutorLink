<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorLink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center w-100">

        <h1 class="fw-bold mb-3">Welcome to TutorLink</h1>
        <p class="text-muted mb-5">
            Join our platform as a student or tutor and start learning today.
        </p>

        <div class="row justify-content-center g-4">

            <!-- Student Card -->
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-3">🎓 Student</h3>
                        <p class="text-muted">
                            Find experienced tutors, book lessons, and improve your skills.
                        </p>

                        <a href="{{route('Auth.Student_Register')}}" class="btn btn-primary w-100">
                            Register as Student
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tutor Card -->
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-3">👨‍🏫 Tutor</h3>
                        <p class="text-muted">
                            Share your knowledge, manage your schedule, and earn money teaching.
                        </p>

                        <a href="{{route('Auth.Teacher_Register')}}" class="btn btn-success w-100">
                            Register as Tutor
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-5">
            <p class="text-muted">
                Already have an account?
                <a href="{{route('Auth.Login')}}">Login</a>
            </p>
        </div>

    </div>
</div>

</body>
</html>