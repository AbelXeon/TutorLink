@extends('Layouts.Layout')

@section('title', 'Student Dashboard - TutorLink')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap');

    :root{
        --ink:#0a0a0a;
        --paper:#f5f4f1;
        --white:#ffffff;
        --blue:#1350e0;
        --blue-dark:#0d3aa8;
        --line: rgba(10,10,10,0.14);
    }
    .edit-wrap { font-family: 'Inter', sans-serif; }
    .display-font {
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .swiss-panel { border-radius: 0; border: 1px solid var(--line); box-shadow: none; background: var(--white); }
    .swiss-input, .swiss-select, .swiss-textarea {
        border-radius: 0;
        border: 1px solid var(--line);
        transition: border-color .15s ease;
    }
    .swiss-input:focus, .swiss-select:focus, .swiss-textarea:focus {
        outline: none;
        border-color: var(--ink);
        box-shadow: none;
    }
    .swiss-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: var(--white);
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%230a0a0a' stroke-width='2'><path d='M6 9l6 6 6-6' stroke-linecap='round' stroke-linejoin='round'/></svg>");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 13px;
        padding-right: 2.25rem;
        cursor: pointer;
    }
    .field-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9a9a9a;
        pointer-events: none;
    }
    .btn-swiss-primary {
        border-radius: 0;
        background-color: var(--ink);
        border: 1px solid var(--ink);
        transition: background-color .15s ease, border-color .15s ease;
    }
    .btn-swiss-primary:hover, .btn-swiss-primary:focus {
        background-color: var(--blue);
        border-color: var(--blue);
    }
    .btn-swiss-outline {
        border-radius: 0;
        border: 1px solid var(--ink);
        color: var(--ink);
        background: var(--white);
        transition: background-color .15s ease, color .15s ease;
    }
    .btn-swiss-outline:hover { background: var(--ink); color: var(--white); }

    .section-label {
        font-size: 0.72rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #8a8a8a;
    }

    .chip-check {
        position: absolute;
        width: 1px; height: 1px;
        padding: 0; margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        white-space: nowrap;
        border: 0;
    }
    .chip-label {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid var(--line);
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
        cursor: pointer;
        user-select: none;
        transition: background-color .15s ease, color .15s ease, border-color .15s ease;
        background: var(--white);
    }
    .chip-check:checked + .chip-label {
        background-color: var(--ink);
        color: var(--white);
        border-color: var(--ink);
    }
    .chip-check.chip-blue:checked + .chip-label {
        background-color: var(--blue);
        border-color: var(--blue);
    }
    .chip-check:focus-visible + .chip-label {
        outline: 2px solid var(--blue);
        outline-offset: 2px;
    }

    .avatar-ring {
        width: 6.5rem; height: 6.5rem;
        border-radius: 50%;
        border: 1.5px dashed var(--line);
        background: var(--paper);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .avatar-edit-btn {
        border-radius: 50%;
        background: var(--ink);
        color: var(--white);
        width: 2rem; height: 2rem;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid var(--white);
        cursor: pointer;
        transition: background-color .15s ease;
    }
    .avatar-edit-btn:hover { background-color: var(--blue); }
    .sr-only-file {
        position: absolute; width: 1px; height: 1px;
        padding: 0; margin: -1px; overflow: hidden;
        clip: rect(0,0,0,0); white-space: nowrap; border: 0;
    }

    @media (max-width: 640px) {
        .edit-card { padding: 1.5rem !important; }
        .edit-card h2 { font-size: 1.75rem !important; }
    }
</style>

<div class="edit-wrap edit-card swiss-panel max-w-2xl mx-auto space-y-8 bg-white p-8">

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pb-6" style="border-bottom: 1px solid var(--line);">
        <div>
            <h2 class="text-2xl sm:text-3xl display-font text-gray-900">
                Edit Tutor Profile
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Update your details below. You are allowed 3 edits per day.
            </p>
        </div>
        <a href="{{ route('tutor.dashboard') }}" class="btn-swiss-outline inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold w-fit">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6L9 12L15 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Back to Dashboard
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 relative">
            <strong class="font-bold">Whoops!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tutor.profile.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="space-y-8">

            <!-- Profile Image: avatar-style uploader with live preview -->
            <div class="flex flex-col items-center gap-3">
                <div class="relative">
                    <div id="avatarRing" class="avatar-ring">
                        <svg id="avatarPlaceholder" class="w-9 h-9" viewBox="0 0 24 24" fill="none" stroke="#b0b0b0" stroke-width="1.6">
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M4.5 20C4.5 16 7.8 13.5 12 13.5C16.2 13.5 19.5 16 19.5 20" stroke-linecap="round"/>
                        </svg>
                        <img id="avatarPreviewImg" class="hidden w-full h-full object-cover" alt="Profile preview">
                    </div>
                    <label for="profile_image" class="avatar-edit-btn absolute bottom-0 right-0" aria-label="Upload profile photo">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 8h3l2-2h6l2 2h3v11H4V8Z" stroke-linejoin="round"/>
                            <circle cx="12" cy="13.5" r="3.5"/>
                        </svg>
                    </label>
                </div>
                <input id="profile_image" name="profile_image" type="file" accept=".jpg,.jpeg,.png" class="sr-only-file" />
                <p id="avatarFileName" class="text-xs text-gray-500">JPEG or PNG, max 2MB. Leave empty to keep your current photo.</p>
            </div>

            <!-- Qualification -->
            <div>
                <label for="qualification" class="block text-sm font-medium text-gray-700">Highest Qualification</label>
                <div class="relative mt-1">
                    <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3L2 8L12 13L22 8L12 3Z" stroke-linejoin="round"/>
                        <path d="M6 10.5V16C6 16 8.5 19 12 19C15.5 19 18 16 18 16V10.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <select id="qualification" name="qualification" required class="swiss-select block w-full pl-10 pr-8 py-2 sm:text-sm">
                        <option value="High School Diploma" {{ $tutorProfile->qualification == 'High School Diploma' ? 'selected' : '' }}>High School Diploma</option>
                        <option value="Bachelor Degree" {{ $tutorProfile->qualification == 'Bachelor Degree' ? 'selected' : '' }}>Bachelor's Degree</option>
                        <option value="Master Degree" {{ $tutorProfile->qualification == 'Master Degree' ? 'selected' : '' }}>Master's Degree</option>
                        <option value="PhD" {{ $tutorProfile->qualification == 'PhD' ? 'selected' : '' }}>PhD / Doctorate</option>
                        <option value="Other Certification" {{ $tutorProfile->qualification == 'Other Certification' ? 'selected' : '' }}>Other Professional Teaching Certification</option>
                    </select>
                </div>
            </div>

            <!-- Experience Years & Price -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="experience_years" class="block text-sm font-medium text-gray-700">Years of Experience</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7V12L15.5 14" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <input id="experience_years" name="experience_years" type="number" min="0" max="50" required value="{{ $tutorProfile->experience_years }}" class="swiss-input block w-full pl-10 pr-3 py-2 sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="price_per_hour" class="block text-sm font-medium text-gray-700">Price Per Hour (ETB)</label>
                    <div class="relative mt-1">
                        <span class="field-icon text-xs font-semibold" style="left:0.7rem;">ETB</span>
                        <input id="price_per_hour" name="price_per_hour" type="number" step="0.01" min="0" required value="{{ $tutorProfile->price_per_hour }}" class="swiss-input block w-full pl-12 pr-3 py-2 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Teaching Mode & Max Students -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="teaching_mode" class="block text-sm font-medium text-gray-700">Teaching Mode</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="12" rx="0.5"/>
                            <path d="M3 13H21" stroke-linecap="round"/>
                            <path d="M7 20H17" stroke-linecap="round"/>
                        </svg>
                        <select id="teaching_mode" name="teaching_mode" required class="swiss-select block w-full pl-10 pr-8 py-2 sm:text-sm">
                            <option value="online" {{ $tutorProfile->teaching_mode == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="in-person" {{ $tutorProfile->teaching_mode == 'in-person' ? 'selected' : '' }}>In-Person</option>
                            <option value="hybrid" {{ $tutorProfile->teaching_mode == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="max_students" class="block text-sm font-medium text-gray-700">Max Students / Session</label>
                    <div class="relative mt-1">
                        <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="8" r="3"/>
                            <path d="M2.5 19C2.5 15.5 5.4 13.5 9 13.5C12.6 13.5 15.5 15.5 15.5 19" stroke-linecap="round"/>
                            <circle cx="17" cy="8.5" r="2.3"/>
                            <path d="M15 13.7C18 13.9 20 15.7 20.5 18.6" stroke-linecap="round"/>
                        </svg>
                        <input id="max_students" name="max_students" type="number" min="1" max="100" required value="{{ $tutorProfile->max_students }}" class="swiss-input block w-full pl-10 pr-3 py-2 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Grade Levels -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Grade Levels You Can Teach</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($gradeLevels as $level)
                        <input id="grade_level_{{ $level->id }}" name="grade_levels[]" type="checkbox" value="{{ $level->id }}"
                            {{ in_array($level->id, $tutorProfile->gradeLevels->pluck('id')->toArray()) ? 'checked' : '' }}
                            class="chip-check">
                        <label for="grade_level_{{ $level->id }}" class="chip-label">{{ $level->name }}</label>
                    @endforeach
                </div>
            </div>

            <!-- Category Selection -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Teaching Category (Select One)</label>
                <div class="relative mt-1">
                    <svg class="field-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6C3 5 4 4 5 4H9L11 6H19C20 6 21 7 21 8V17C21 18 20 19 19 19H5C4 19 3 18 3 17V6Z" stroke-linejoin="round"/>
                    </svg>
                    <select id="category_id" name="category_id" required class="swiss-select block w-full pl-10 pr-8 py-2 sm:text-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ ($tutorProfile->subjects->first() && $tutorProfile->subjects->first()->category_id == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Subjects list -->
            <div id="subjects_section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Subjects (Choose up to 3)</label>
                <div id="checkbox_container" class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        @foreach($category->subjects as $subject)
                            <div class="subject-checkbox-wrapper" data-category-id="{{ $category->id }}">
                                <input id="subject_{{ $subject->id }}" name="subjects[]" type="checkbox" value="{{ $subject->id }}"
                                    {{ in_array($subject->id, $tutorProfile->subjects->pluck('id')->toArray()) ? 'checked' : '' }}
                                    class="chip-check chip-blue subject-checkbox">
                                <label for="subject_{{ $subject->id }}" class="chip-label">{{ $subject->name }}</label>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                <p id="warning_text" class="mt-2 text-xs text-red-600 hidden">You can select a maximum of 3 subjects.</p>
            </div>

            <!-- DYNAMIC WEEKLY SCHEDULE BUILDER -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Weekly Availability Schedule</label>
                <div id="schedule_wrapper" class="space-y-3 p-4" style="background: var(--paper); border: 1px solid var(--line);">

                    <!-- Existing Schedules (populated if editing) -->
                    @foreach($schedules as $index => $sched)
                        <div class="flex flex-wrap items-center gap-3 schedule-row pb-3 border-b last:border-b-0 last:pb-0" style="border-color: var(--line);">
                            <select name="schedules[{{ $index }}][day]" required class="swiss-select block py-1.5 pl-3 pr-8 text-sm">
                                <option value="Monday" {{ $sched->day_of_week == 'Monday' ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ $sched->day_of_week == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ $sched->day_of_week == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ $sched->day_of_week == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ $sched->day_of_week == 'Friday' ? 'selected' : '' }}>Friday</option>
                                <option value="Saturday" {{ $sched->day_of_week == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="Sunday" {{ $sched->day_of_week == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>

                            <span class="text-xs text-gray-500">Start:</span>
                            <input type="time" name="schedules[{{ $index }}][start]" required value="{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}" class="swiss-input py-1 px-2 text-sm">

                            <span class="text-xs text-gray-500">End:</span>
                            <input type="time" name="schedules[{{ $index }}][end]" required value="{{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}" class="swiss-input py-1 px-2 text-sm">

                            <button type="button" class="remove-schedule-btn text-xs text-red-600 hover:text-red-800 font-semibold ml-auto">Remove</button>
                        </div>
                    @endforeach

                </div>
                <button type="button" id="add_schedule_btn" class="btn-swiss-outline mt-3 inline-flex items-center px-3 py-1.5 text-xs font-semibold">
                    + Add Available Day/Time
                </button>
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700">About Me / Bio</label>
                <textarea id="bio" name="bio" rows="4" required class="swiss-textarea mt-1 block w-full px-3 py-2 sm:text-sm">{{ $tutorProfile->bio }}</textarea>
            </div>
        </div>

        <div class="flex justify-end pt-6" style="border-top: 1px solid var(--line);">
            <button type="submit" class="btn-swiss-primary w-full sm:w-auto py-2.5 px-6 text-sm font-medium text-white">
                Save Changes &amp; Publish
            </button>
        </div>
    </form>
</div>

<!-- JAVASCRIPT FOR CATEGORY MATCHING, 3-SUBJECT LIMITATION, AND DYNAMIC SCHEDULE ROWS -->
<script>
    const categorySelect = document.getElementById('category_id');
    const subjectsSection = document.getElementById('subjects_section');
    const warningText = document.getElementById('warning_text');
    const wrappers = document.querySelectorAll('.subject-checkbox-wrapper');
    const checkboxes = document.querySelectorAll('.subject-checkbox');

    // Dynamic Subjects Selection based on Category choice
    function updateSubjectsDisplay() {
        const selectedCategoryId = categorySelect.value;

        if (selectedCategoryId) {
            subjectsSection.classList.remove('hidden');
            
            wrappers.forEach(wrapper => {
                if (wrapper.getAttribute('data-category-id') === selectedCategoryId) {
                    wrapper.classList.remove('hidden');
                } else {
                    wrapper.classList.add('hidden');
                    const checkbox = wrapper.querySelector('.subject-checkbox');
                    checkbox.checked = false;
                }
            });
        } else {
            subjectsSection.classList.add('hidden');
            checkboxes.forEach(cb => cb.checked = false);
        }
    }

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
    if (categorySelect.value) {
        updateSubjectsDisplay();
    }

    // --- DYNAMIC SCHEDULE ROW GENERATION ---
    const scheduleWrapper = document.getElementById('schedule_wrapper');
    const addScheduleBtn = document.getElementById('add_schedule_btn');
    let rowIndex = {{ $schedules->count() }}; // Track indexes safely

    addScheduleBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = "flex flex-wrap items-center gap-3 schedule-row pb-3 border-b last:border-b-0 last:pb-0";
        row.style.borderColor = "rgba(10,10,10,0.14)";

        row.innerHTML = `
            <select name="schedules[${rowIndex}][day]" required class="swiss-select block py-1.5 pl-3 pr-8 text-sm">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>

            <span class="text-xs text-gray-500">Start:</span>
            <input type="time" name="schedules[${rowIndex}][start]" required class="swiss-input py-1 px-2 text-sm">
            
            <span class="text-xs text-gray-500">End:</span>
            <input type="time" name="schedules[${rowIndex}][end]" required class="swiss-input py-1 px-2 text-sm">

            <button type="button" class="remove-schedule-btn text-xs text-red-600 hover:text-red-800 font-semibold ml-auto">Remove</button>
        `;

        scheduleWrapper.appendChild(row);
        rowIndex++;
        toggleScheduleWrapperPlaceholder();
    });

    // Event delegation for removal
    scheduleWrapper.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-schedule-btn')) {
            e.target.closest('.schedule-row').remove();
            toggleScheduleWrapperPlaceholder();
        }
    });

    function toggleScheduleWrapperPlaceholder() {
        if (scheduleWrapper.children.length === 0) {
            scheduleWrapper.innerHTML = `<p class="text-sm text-gray-500 italic text-center py-2" id="no-schedule-text">No available slots added yet. Click "+ Add Available Day/Time" below.</p>`;
        } else {
            const placeholder = document.getElementById('no-schedule-text');
            if (placeholder) placeholder.remove();
        }
    }

    // Avatar live preview: purely front-end, the file input's name/id/accept
    // attributes are unchanged, so the submitted file field is unaffected.
    (function () {
        const input = document.getElementById('profile_image');
        const placeholder = document.getElementById('avatarPlaceholder');
        const previewImg = document.getElementById('avatarPreviewImg');
        const fileNameText = document.getElementById('avatarFileName');
        const defaultFileNameText = fileNameText.textContent;

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            if (!file) {
                fileNameText.textContent = defaultFileNameText;
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
            fileNameText.textContent = file.name;
        });
    })();
</script>

@endsection