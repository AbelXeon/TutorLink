<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Tutor Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Complete Your Tutor Profile
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Please fill in your professional teaching details to start receiving bookings.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Whoops!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('tutor.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4">
                <!-- Profile Image Input -->
                <div>
                    <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image (Max 2MB, JPEG/PNG only)</label>
                    <input id="profile_image" name="profile_image" type="file" accept=".jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                </div>

                <!-- Qualification -->
                <div>
                    <label for="qualification" class="block text-sm font-medium text-gray-700">Highest Qualification</label>
                    <select id="qualification" name="qualification" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Qualification</option>
                        <option value="High School Diploma" {{ old('qualification', $tutorProfile->qualification) == 'High School Diploma' ? 'selected' : '' }}>High School Diploma</option>
                        <option value="Bachelor Degree" {{ old('qualification', $tutorProfile->qualification) == 'Bachelor Degree' ? 'selected' : '' }}>Bachelor's Degree</option>
                        <option value="Master Degree" {{ old('qualification', $tutorProfile->qualification) == 'Master Degree' ? 'selected' : '' }}>Master's Degree</option>
                        <option value="PhD" {{ old('qualification', $tutorProfile->qualification) == 'PhD' ? 'selected' : '' }}>PhD / Doctorate</option>
                        <option value="Other Certification" {{ old('qualification', $tutorProfile->qualification) == 'Other Certification' ? 'selected' : '' }}>Other Professional Teaching Certification</option>
                    </select>
                </div>

                <!-- Experience Years & Price -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="experience_years" class="block text-sm font-medium text-gray-700">Years of Experience</label>
                        <input id="experience_years" name="experience_years" type="number" min="0" max="50" required value="{{ old('experience_years', $tutorProfile->experience_years) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="price_per_hour" class="block text-sm font-medium text-gray-700">Price Per Hour (ETB)</label>
                        <input id="price_per_hour" name="price_per_hour" type="number" step="0.01" min="0" required value="{{ old('price_per_hour', $tutorProfile->price_per_hour) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Teaching Mode & Max Students -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="teaching_mode" class="block text-sm font-medium text-gray-700">Teaching Mode</label>
                        <select id="teaching_mode" name="teaching_mode" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="online" {{ old('teaching_mode', $tutorProfile->teaching_mode) == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="in-person" {{ old('teaching_mode', $tutorProfile->teaching_mode) == 'in-person' ? 'selected' : '' }}>In-Person</option>
                            <option value="hybrid" {{ old('teaching_mode', $tutorProfile->teaching_mode) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                    <div>
                        <label for="max_students" class="block text-sm font-medium text-gray-700">Max Students / Session</label>
                        <input id="max_students" name="max_students" type="number" min="1" max="100" required value="{{ old('max_students', $tutorProfile->max_students) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Grade Levels -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grade Levels You Can Teach</label>
                    <div class="space-y-2 bg-gray-50 p-4 rounded-md border border-gray-200">
                        @foreach($gradeLevels as $level)
                            <div class="flex items-center">
                                <input id="grade_level_{{ $level->id }}" name="grade_levels[]" type="checkbox" value="{{ $level->id }}" 
                                    {{ in_array($level->id, old('grade_levels', $tutorProfile->gradeLevels->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="grade_level_{{ $level->id }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $level->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Category Selection (Dropdown Selector) -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Teaching Category (Select One)</label>
                    <select id="category_id" name="category_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ ($tutorProfile->subjects->first() && $tutorProfile->subjects->first()->category_id == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subjects Dynamic Checkbox Selection (Hidden until category is selected) -->
                <div id="subjects_section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Subjects (Choose up to 3)</label>
                    <div id="checkbox_container" class="grid grid-cols-1 md:grid-cols-2 gap-2 bg-gray-50 p-4 rounded-md border border-gray-200">
                        @foreach($categories as $category)
                            @foreach($category->subjects as $subject)
                                <div class="flex items-center subject-checkbox-wrapper" data-category-id="{{ $category->id }}">
                                    <input id="subject_{{ $subject->id }}" name="subjects[]" type="checkbox" value="{{ $subject->id }}" 
                                        {{ in_array($subject->id, old('subjects', $tutorProfile->subjects->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded subject-checkbox">
                                    <label for="subject_{{ $subject->id }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $subject->name }}
                                    </label>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    <p id="warning_text" class="mt-2 text-xs text-red-600 hidden">You can select a maximum of 3 subjects.</p>
                </div>

                <!-- Professional Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">About Me / Bio</label>
                    <textarea id="bio" name="bio" rows="4" required placeholder="Describe your teaching philosophy..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('bio', $tutorProfile->bio) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Minimum 20 characters.</p>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Profile & Publish
                </button>
            </div>
        </form>
    </div>

    <!-- JAVASCRIPT FOR CATEGORY MATCHING AND 3-SUBJECT LIMITATION -->
    <script>
        const categorySelect = document.getElementById('category_id');
        const subjectsSection = document.getElementById('subjects_section');
        const warningText = document.getElementById('warning_text');
        const wrappers = document.querySelectorAll('.subject-checkbox-wrapper');
        const checkboxes = document.querySelectorAll('.subject-checkbox');

        function updateSubjectsDisplay() {
            const selectedCategoryId = categorySelect.value;

            if (selectedCategoryId) {
                subjectsSection.classList.remove('hidden');
                
                wrappers.forEach(wrapper => {
                    if (wrapper.getAttribute('data-category-id') === selectedCategoryId) {
                        wrapper.classList.remove('hidden');
                    } else {
                        wrapper.classList.add('hidden');
                        // Reset checkboxes that belong to other categories if category selection changes
                        const checkbox = wrapper.querySelector('.subject-checkbox');
                        checkbox.checked = false;
                    }
                });
            } else {
                subjectsSection.classList.add('hidden');
                checkboxes.forEach(cb => cb.checked = false);
            }
        }

        // Limit checking up to 3 options
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.subject-checkbox:checked').length;

                if (checkedCount > 3) {
                    this.checked = false; 
                    warningText.classList.remove('hidden');
                } else {
                    warningText.classList.add('hidden');
                }
            });
        });

        categorySelect.addEventListener('change', updateSubjectsDisplay);

        // Run on load
        if (categorySelect.value) {
            updateSubjectsDisplay();
        }
    </script>
</body>
</html>