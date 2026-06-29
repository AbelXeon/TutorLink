<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tutor Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white py-3">
            <h3 class="mb-0 fw-bold">✏️ Edit Tutor Profile</h3>
        </div>

        <div class="card-body p-4">

            <!-- Form Opening with route details and file support -->
            <form action="{{ route('Teacher.Teacher_Profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Image Upload section -->
                <div class="text-center mb-4">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}"
                             class="rounded-circle mb-3 border shadow-sm"
                             width="150"
                             height="150" style="object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/150"
                             class="rounded-circle mb-3 border shadow-sm"
                             width="150"
                             height="150">
                    @endif

                    <div class="col-md-4 mx-auto">
                        <label class="form-label text-muted small">Update Profile Image</label>
                        <input type="file" class="form-control" name="profile_image">
                    </div>
                </div>

                <!-- Bio / About Me -->
                <div class="mb-3">
                    <label class="form-label fw-bold">About Me</label>
                    <textarea class="form-control" name="bio" rows="5" required>{{ old('bio', $profile->bio) }}</textarea>
                </div>

                <div class="row g-3">

                    <!-- Predefined Qualifications dropdown -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Qualification</label>
                        <select class="form-control" name="qualification" required>
                            <option value="High School Graduate" {{ $profile->qualification == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                            <option value="Diploma" {{ $profile->qualification == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="Bachelor's Degree (BSc/BA)" {{ $profile->qualification == "Bachelor's Degree (BSc/BA)" ? 'selected' : '' }}>Bachelor's Degree (BSc/BA)</option>
                            <option value="Master's Degree (MSc/MA)" {{ $profile->qualification == "Master's Degree (MSc/MA)" ? 'selected' : '' }}>Master's Degree (MSc/MA)</option>
                            <option value="PhD / Doctorate" {{ $profile->qualification == 'PhD / Doctorate' ? 'selected' : '' }}>PhD / Doctorate</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Experience (Years)</label>
                        <input type="number" class="form-control" name="experience_years" min="0" value="{{ old('experience_years', $profile->experience_years) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Grade Level</label>
                        <input type="text" class="form-control" name="grade_level" value="{{ old('grade_level', $profile->grade_level) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Price Per Hour (ETB)</label>
                        <input type="number" step="0.01" class="form-control" name="price_per_hour" value="{{ old('price_per_hour', $profile->price_per_hour) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Maximum Students</label>
                        <input type="number" class="form-control" name="max_students" min="1" value="{{ old('max_students', $profile->max_students) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Teaching Mode</label>
                        <select class="form-select" name="mode" required>
                            <option value="online" {{ $profile->mode == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="physical" {{ $profile->mode == 'physical' ? 'selected' : '' }}>Physical</option>
                            <option value="both" {{ $profile->mode == 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Availability</label>
                        <select class="form-select" name="availability_status" required>
                            <option value="available" {{ $profile->availability_status == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="busy" {{ $profile->availability_status == 'busy' ? 'selected' : '' }}>Busy</option>
                            <option value="offline" {{ $profile->availability_status == 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                    </div>

                    <!-- Dynamic Category/Subjects logic -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Select Category</label>
                        <select class="form-control" id="category-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Select Subjects you Teach</label>
                        <div id="subjects-container" class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                            <span class="text-muted">Please select a category above.</span>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <!-- Weekly Schedule edit matching database columns -->
                <h4 class="fw-bold text-dark mb-3">📅 Weekly Schedule</h4>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Day of Week</label>
                        <select class="form-control" name="day_of_week" required>
                            <option value="Monday" {{ $schedule && $schedule->day_of_week == 'Monday' ? 'selected' : '' }}>Monday</option>
                            <option value="Tuesday" {{ $schedule && $schedule->day_of_week == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="Wednesday" {{ $schedule && $schedule->day_of_week == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="Thursday" {{ $schedule && $schedule->day_of_week == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="Friday" {{ $schedule && $schedule->day_of_week == 'Friday' ? 'selected' : '' }}>Friday</option>
                            <option value="Saturday" {{ $schedule && $schedule->day_of_week == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                            <option value="Sunday" {{ $schedule && $schedule->day_of_week == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Start Time</label>
                        <input type="time" class="form-control" name="start_time" value="{{ $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '' }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">End Time</label>
                        <input type="time" class="form-control" name="end_time" value="{{ $schedule ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}" required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-end">
                    <a href="{{ route('Teacher.Teacher_Dashboard') }}" class="btn btn-secondary me-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<!-- Vanilla JS to dynamically handle checkboxes and mark existing selections as checked -->
<script>
    const subjectsList = @json($subjects);
    const selectedSubjectIds = @json($selectedSubjectIds); // IDs of subjects this teacher already teaches

    const categorySelect = document.getElementById('category-select');
    const subjectsContainer = document.getElementById('subjects-container');

    categorySelect.addEventListener('change', function() {
        const selectedCategoryId = this.value;

        subjectsContainer.innerHTML = '';

        if (!selectedCategoryId) {
            subjectsContainer.innerHTML = '<span class="text-muted">Please select a category above.</span>';
            return;
        }

        const filteredSubjects = subjectsList.filter(subject => subject.category_id == selectedCategoryId);

        if (filteredSubjects.length === 0) {
            subjectsContainer.innerHTML = '<span class="text-danger">No subjects found in this category.</span>';
            return;
        }

        filteredSubjects.forEach(subject => {
            const wrapper = document.createElement('div');
            wrapper.className = 'form-check mb-2';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input';
            checkbox.name = 'subject_ids[]';
            checkbox.value = subject.id;
            checkbox.id = `subject_${subject.id}`;

            // Check the box automatically if the teacher already teaches this subject
            if (selectedSubjectIds.includes(subject.id)) {
                checkbox.checked = true;
            }

            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = `subject_${subject.id}`;
            label.textContent = subject.name;

            wrapper.appendChild(checkbox);
            wrapper.appendChild(label);
            subjectsContainer.appendChild(wrapper);
        });
    });
</script>

</body>
</html>