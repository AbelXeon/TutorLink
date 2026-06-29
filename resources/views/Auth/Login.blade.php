<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TutorLink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">

    <div class="col-md-5">

        <div class="card shadow border-0">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">🔐 Login</h3>
                @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<form action="{{ route('Auth.Login.submit') }}" method="POST">
    @csrf

    <!-- Error Display Box -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

                    <div class="mb-3">
        <label class="form-label">Email or Username</label>
        <input type="text" class="form-control" name="login" value="{{ old('login') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Login
    </button>
</form>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        Don’t have an account?
                        <a href="{{ route('Auth.Student_Register') }}">Register</a>
                    </small>
                </div>

            </div>
        </div>

    </div>

</div>

</body>
</html>