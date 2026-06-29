<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register - TutorLink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow border-0">
                <div class="card-body p-4">

                    <h3 class="mb-4 text-center">🎓 Student Registration</h3>

                    <form action="{{ route('Auth.Student_Register.store') }}" method="POST" enctype="multipart/form-data">
                          @csrf

                          <!-- Error Display Box -->
    @if ($errors->any())
        <div class="alert alert-danger col-12 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middle_name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image">
                            </div>

                           
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4">
                            Register
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>