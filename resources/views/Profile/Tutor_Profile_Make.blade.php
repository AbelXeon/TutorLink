<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Tutor Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow border-0">
                <div class="card-body p-4">

                    <h3 class="mb-4 text-center">👨‍🏫 Complete Your Tutor Profile</h3>

                    <form action="{{ route('Profile.Tutor_Profile_make.store') }}" method="POST">
                        @csrf

                        <!-- Error Display box -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">

                            <!-- BIO -->
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" name="bio" rows="3" placeholder="Write about yourself..." required></textarea>
                            </div>

                            <!-- EXPERIENCE + STATUS -->
                            <div class="col-md-4">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" name="experience_years" min="0" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Availability Status</label>
                                <select class="form-control" name="availability_status" required>
                                    <option value="available">Available</option>
                                    <option value="busy">Busy</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Mode</label>
                                <select class="form-control" name="mode" required>
                                    <option value="online">Online</option>
                                    <option value="physical">Physical</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>

                            <!-- QUALIFICATION (Predefined Selection) -->
                            <div class="col-md-6">
                                <label class="form-label">Qualification</label>
                                <select class="form-control" name="qualification" required>
                                    <option value="">Select your qualification</option>
                                    <option value="High School Graduate">High School Graduate</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Bachelor's Degree (BSc/BA)">Bachelor's Degree (BSc/BA)</option>
                                    <option value="Master's Degree (MSc/MA)">Master's Degree (MSc/MA)</option>
                                    <option value="PhD / Doctorate">PhD / Doctorate</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Grade Level</label>
                                <input type="text" class="form-control" name="grade_level" placeholder="High School / University" required>
                            </div>

                            <!-- PRICE + LIMIT -->
                            <div class="col-md-6">
                                <label class="form-label">Price / Hour (ETB)</label>
                                <input type="number" step="0.01" class="form-control" name="price_per_hour" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Max Students</label>
                                <input type="number" class="form-control" name="max_students" min="1" required>
                            </div>

                            <!-- CATEGORY & DYNAMIC SUBJECTS -->
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-control" id="category-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label d-block">Select Subjects you Teach</label>
                                <div id="subjects-container" class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                                    <span class="text-muted">Please select a category first.</span>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">📅 Weekly Schedule</h5>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Day of Week</label>
                                <select class="form-control" name="day_of_week" required>
                                    <option>Monday</option>
                                    <option>Tuesday</option>
                                    <option>Wednesday</option>
                                    <option>Thursday</option>
                                    <option>Friday</option>
                                    <option>Saturday</option>
                                    <option>Sunday</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control" name="start_time" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-control" name="end_time" required>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-4">
                            Save Profile
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<!-- Vanilla JavaScript to handle dynamic cascading checkboxes -->
<script>
    // Embed database subjects as a JSON object inside JS
    const subjectsList = @json($subjects);

    const categorySelect = document.getElementById('category-select');
    const subjectsContainer = document.getElementById('subjects-container');

    categorySelect.addEventListener('change', function() {
        const selectedCategoryId = this.value;

        // Clear previous list
        subjectsContainer.innerHTML = '';

        if (!selectedCategoryId) {
            subjectsContainer.innerHTML = '<span class="text-muted">Please select a category first.</span>';
            return;
        }

        // Filter subjects that belong to the chosen Category ID
        const filteredSubjects = subjectsList.filter(subject => subject.category_id == selectedCategoryId);

        if (filteredSubjects.length === 0) {
            subjectsContainer.innerHTML = '<span class="text-danger">No subjects found in this category.</span>';
            return;
        }

        // Generate checkboxes dynamically
        filteredSubjects.forEach(subject => {
            const wrapper = document.createElement('div');
            wrapper.className = 'form-check mb-2';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input';
            checkbox.name = 'subject_ids[]';
            checkbox.value = subject.id;
            checkbox.id = `subject_${subject.id}`;

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