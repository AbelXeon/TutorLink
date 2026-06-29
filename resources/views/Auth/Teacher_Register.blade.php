<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Register - TutorLink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-9">

            <div class="card shadow border-0">
                <div class="card-body p-4">

                    <h3 class="mb-4 text-center">👨‍🏫 Teacher Registration</h3>

                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <form action="{{ route('Auth.Teacher_Register.store') }}" method="POST" enctype="multipart/form-data">
    @csrf


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

                            <!-- LOCATION (FIXED) -->
                            <div class="col-md-6">
                                <label class="form-label">Location (City)</label>
                                <select class="form-select" name="location" required>
                                    <option value="">Select City</option>
                                    <option value="Addis Ababa">Addis Ababa</option>
                                    <option value="Hawassa">Hawassa</option>
                                    <option value="Dire Dawa">Dire Dawa</option>
                                    <option value="Mekelle">Mekelle</option>
                                    <option value="Bahir Dar">Bahir Dar</option>
                                    <option value="Jimma">Jimma</option>
                                    <option value="Dessie">Dessie</option>
                                    <option value="Gondar">Gondar</option>
                                    <option value="Adama">Adama</option>
                                    <option value="Harar">Harar</option>
                                </select>
                            </div>

                            <!-- ADDRESS (STRUCTURED AREA) -->
                            <div class="col-md-6">
                                <label class="form-label">Area / Sub Location</label>
                                <select class="form-select" name="address" required>
                                    <option value="">Select Area</option>

                                    <optgroup label="Addis Ababa">
                                        <option value="Piassa">Piassa</option>
                                        <option value="Bole">Bole</option>
                                        <option value="Kazanchis">Kazanchis</option>
                                        <option value="Megenagna">Megenagna</option>
                                        <option value="Kality">Kality</option>
                                    </optgroup>

                                    <optgroup label="Hawassa">
                                        <option value="City Center">City Center</option>
                                        <option value="Haile Resort Area">Haile Resort Area</option>
                                        <option value="Lake Side">Lake Side</option>
                                    </optgroup>

                                    <optgroup label="Dire Dawa">
                                        <option value="Kezira">Kezira</option>
                                        <option value="Sabiyan">Sabiyan</option>
                                    </optgroup>

                                    <optgroup label="Other">
                                        <option value="Other Area">Other Area</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image">
                            </div>

                            <!-- hidden system fields -->
                            <input type="hidden" name="role_id" value="1">
                            <input type="hidden" name="account_status" value="active">

                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-4">
                            Register as Teacher
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>