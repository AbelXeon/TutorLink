@extends('Layouts.Layout')

@section('title', 'Find Tutors - TutorLink')

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
    .browse-wrap { font-family: 'Inter', sans-serif; }
    .display-font {
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .swiss-panel { border-radius: 0; border: 1px solid var(--line); box-shadow: none; background: var(--white); }
    .swiss-select {
        border-radius: 0;
        border: 1px solid var(--line);
        appearance: none;
        -webkit-appearance: none;
        background-color: var(--white);
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%230a0a0a' stroke-width='2'><path d='M6 9l6 6 6-6' stroke-linecap='round' stroke-linejoin='round'/></svg>");
        background-repeat: no-repeat;
        background-position: right 0.65rem center;
        background-size: 12px;
        padding-right: 2rem;
        cursor: pointer;
    }
    .swiss-select:disabled {
        background-color: var(--paper);
        color: #9a9a9a;
        cursor: not-allowed;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23b0b0b0' stroke-width='2'><path d='M6 9l6 6 6-6' stroke-linecap='round' stroke-linejoin='round'/></svg>");
    }
    .swiss-select:focus { outline: none; border-color: var(--ink); }
    .swiss-input {
        border-radius: 0;
        border: 1px solid var(--line);
    }
    .swiss-input:focus { outline: none; border-color: var(--ink); box-shadow: none; }
    .btn-swiss-primary {
        border-radius: 0;
        background-color: var(--ink);
        border: 1px solid var(--ink);
        transition: background-color .15s ease, border-color .15s ease;
    }
    .btn-swiss-primary:hover { background-color: var(--blue); border-color: var(--blue); }
    .btn-swiss-outline {
        border-radius: 0;
        border: 1px solid var(--line);
        color: #374151;
        background: var(--white);
        transition: background-color .15s ease;
    }
    .btn-swiss-outline:hover { background: var(--paper); }
    .btn-swiss-accent {
        border-radius: 0;
        background-color: var(--blue);
        border: 1px solid var(--blue);
        transition: background-color .15s ease;
    }
    .btn-swiss-accent:hover { background-color: var(--blue-dark); }
    .btn-disabled-flat {
        border-radius: 0;
        border: 1px solid var(--line);
        background: var(--paper);
        color: #9a9a9a;
        cursor: not-allowed;
    }

    /* Fixed Layout Sidebar position selectors */
    @media (min-width: 1024px) {
        .td-fixed-filters {
            position: fixed;
            top: 100px; /* Aligned nicely below topnav wrapper height of 64px */
            width: 288px; /* 72 Spacing unit block */
            z-index: 30;
        }
    }

    .verified-tick { color: var(--blue); }
    .meta-icon { color: #9a9a9a; }
    .mode-chip {
        text-transform: capitalize;
        color: var(--blue-dark);
        background: rgba(19,80,224,0.06);
        border: 1px solid rgba(19,80,224,0.25);
        padding: 0.15rem 0.5rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .stat-block { background: var(--paper); border: 1px solid var(--line); }
    .status-dot { border-radius: 2px; }

    /* Booking modal */
    #booking_modal .swiss-panel { border-radius: 0; }
    .cal-day-available {
        background: rgba(19,80,224,0.06);
        color: var(--blue-dark);
        border: 1px solid rgba(19,80,224,0.25);
    }
    .cal-day-available:hover { background: rgba(19,80,224,0.14); }
    .cal-day-selected {
        background: var(--ink) !important;
        color: var(--white) !important;
        border: 1px solid var(--ink) !important;
    }
    .cal-day-disabled { color: #d0d0d0; cursor: not-allowed; }
    .time-slot-btn {
        border-radius: 0;
        border: 1px solid var(--line);
        transition: border-color .15s ease, background-color .15s ease;
    }
    .time-slot-btn:hover { border-color: var(--blue); background: rgba(19,80,224,0.05); }
    .time-slot-selected {
        border-color: var(--ink) !important;
        background: var(--ink) !important;
        color: var(--white) !important;
    }
</style>

<!-- MOBILE TOP BAR WITH FILTER HAMBURGER BUTTON -->
<div class="lg:hidden fixed top-[64px] left-0 right-0 z-40 bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between" style="border-bottom: 1px solid rgba(10,10,10,0.14);">
    <div class="flex items-center gap-2">
        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tutors browse</span>
    </div>
    <button id="mobile-filter-trigger" class="flex items-center gap-2 bg-[#0a0a0a] text-white px-3 py-1.5 text-xs font-bold tracking-wider uppercase select-none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4l11 4m-11 4l11-4M3 16l11-4" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5h8M12 10h8M12 15h8M12 20h8" />
        </svg>
        Filter Tutors
    </button>
</div>

<!-- Extra top gap spacer for mobile -->
<div class="h-10 lg:hidden"></div>

<!-- MOBILE SLIDE-OUT FILTER DRAWER -->
<div id="mobile-drawer-overlay" class="fixed inset-0 bg-black/50 z-50 hidden transition-opacity duration-300"></div>
<div id="mobile-drawer" class="fixed top-0 left-0 bottom-0 h-full w-80 max-w-[85vw] bg-white z-50 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between border-r" style="border-right: 1px solid rgba(10,10,10,0.14);">
    <div class="p-6 overflow-y-auto flex-grow">
        <div class="flex items-center justify-between pb-4 mb-6 border-b" style="border-bottom: 1px solid rgba(10,10,10,0.14);">
            <span class="display-font text-lg text-gray-900 tracking-wider">Search Filters</span>
            <button id="mobile-drawer-close" class="text-gray-500 hover:text-black focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Target slot where the Form is dynamically repositioned in mobile view -->
        <div id="mobile-form-slot"></div>
    </div>
    <div class="p-6 bg-[#f5f4f1] border-t text-center text-[11px] font-bold text-[#595959]" style="border-top: 1px solid rgba(10,10,10,0.14);">
        TutorLink &copy; {{ date('Y') }}
    </div>
</div>

<!-- MAIN BROWSE LAYOUT -->
<div class="browse-wrap flex flex-col lg:flex-row gap-8 items-start relative">

    <!-- DESKTOP FIXED SIDEBAR WRAPPER -->
    <aside class="hidden lg:block w-72 shrink-0 td-fixed-filters">
        <div class="swiss-panel p-6">
            <p class="text-xs uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" style="color: var(--blue);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 5H20L14 12.5V19L10 21V12.5L4 5Z" stroke-linejoin="round"/>
                </svg>
                Filters
            </p>
            
            <!-- Target slot where the Form is dynamically repositioned in desktop view -->
            <div id="desktop-form-slot"></div>
        </div>
    </aside>

    <!-- INVISIBLE SPACER FOR PC GRID ALIGNMENT -->
    <div class="hidden lg:block w-72 shrink-0"></div>

    <!-- TUTOR RESULTS LIST (SCROLLS INDEPENDENTLY) -->
    <div class="flex-grow w-full lg:col-span-3 space-y-6">
        @forelse($tutors as $tutor)
            <!-- Tutor Card -->
            <div class="swiss-panel p-6 flex flex-col md:flex-row gap-6 relative">

                <!-- Left Column: Square Image with Active Status Indicator -->
                <div class="relative w-32 h-32 md:w-36 md:h-36 flex-shrink-0">
                    @if($tutor->user->profile_image)
                        <img src="{{ asset('storage/' . $tutor->user->profile_image) }}" alt="Photo" class="w-full h-full object-cover" style="border: 1px solid var(--line);">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl display-font" style="background: var(--paper); color: var(--ink); border: 1px solid var(--line);">
                            {{ substr($tutor->user->first_name, 0, 1) }}{{ substr($tutor->user->last_name, 0, 1) }}
                        </div>
                    @endif
                    <span class="status-dot absolute bottom-0 right-0 block h-4 w-4 bg-green-500 ring-2 ring-white"></span>
                </div>

                <!-- Center Column: Profile Info & Snippet -->
                <div class="flex-grow space-y-2">
                    <div class="flex items-center gap-2">
                        <h4 class="text-xl display-font text-gray-900">
                            {{ $tutor->user->first_name }} {{ substr($tutor->user->last_name, 0, 1) }}.
                        </h4>
                        <svg class="verified-tick w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="Verified Tutor">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M8.5 12.5L11 15L16 9" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-gray-500 font-medium">
                        <span class="flex items-center gap-1">
                            <svg class="meta-icon w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3L14.6 9L21 9.8L16.5 14.1L17.6 20.5L12 17.4L6.4 20.5L7.5 14.1L3 9.8L9.4 9L12 3Z" stroke-linejoin="round"/></svg>
                            Super Tutor
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            <svg class="meta-icon w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3L2 8L12 13L22 8L12 3Z" stroke-linejoin="round"/></svg>
                            {{ $tutor->qualification }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            <svg class="meta-icon w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21C12 21 19 14.5 19 9.5C19 5.4 15.9 2 12 2C8.1 2 5 5.4 5 9.5C5 14.5 12 21 12 21Z" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                            {{ $tutor->user->location?->name }}, {{ $tutor->user->address }}
                        </span>
                        <span>•</span>
                        <span class="mode-chip">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="12" rx="0.5"/><path d="M3 13H21" stroke-linecap="round"/><path d="M7 20H17" stroke-linecap="round"/></svg>
                            {{ $tutor->teaching_mode }}
                        </span>
                    </div>

                    <p class="text-sm font-semibold" style="color: var(--blue);">
                        {{ $tutor->subjects->pluck('name')->implode(', ') }}
                    </p>

                    <p class="text-sm text-gray-600 line-clamp-2 pr-12">
                        {{ $tutor->bio }}
                    </p>

                    <a href="{{ route('tutors.profile', $tutor->user->username) }}" aria-label="View {{ $tutor->user->first_name }}'s full teaching profile" class="inline-block text-xs font-bold hover:underline" style="color: var(--blue);">
                        View profile &rarr;
                    </a>
                </div>

                <!-- Right Column: Price, Statistics, Action Buttons -->
                <div class="w-full md:w-48 flex-shrink-0 border-t md:border-t-0 md:border-l pt-4 md:pt-0 md:pl-6 flex flex-col justify-between" style="border-color: var(--line);">
                    <div>
                        <div class="text-2xl display-font text-gray-900">
                            ETB {{ number_format($tutor->price_per_hour, 2) }}
                        </div>
                        <span class="text-xs text-gray-500">per-hour lesson</span>
                    </div>

                    <div class="stat-block grid grid-cols-3 gap-2 text-center my-3 p-2">
                        <div>
                            <span class="block text-xs font-bold text-gray-900">{{ number_format($tutor->average_rating, 1) }} ★</span>
                            <span class="text-[10px] text-gray-500">({{ $tutor->reviews_count }}) revs</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-900">{{ $tutor->max_students }}</span>
                            <span class="text-[10px] text-gray-500">students</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-900">{{ $tutor->experience_years }}y</span>
                            <span class="text-[10px] text-gray-500">experience</span>
                        </div>
                    </div>

                    <!-- Call To Action Buttons -->
                    <div class="space-y-2">
                        <button type="button"
                            onclick="openBookingModal('{{ $tutor->user->username }}', '{{ $tutor->user->first_name }} {{ $tutor->user->last_name }}', {{ json_encode($tutor->user->schedules) }})"
                            class="btn-swiss-accent w-full block text-center py-2 px-3 text-xs font-bold text-white">
                            Book lesson
                        </button>

                        @if(Auth::check() && in_array($tutor->user_id, $allowedTutorIds))
                            <a href="{{ route('messages.show', $tutor->user->username) }}" class="btn-swiss-outline w-full block text-center py-2 px-3 text-xs font-bold">
                                Send message
                            </a>
                        @else
                            <button type="button"
                                onclick="alert('Security Restriction: You must book a lesson with {{ $tutor->user->first_name }} and have it accepted before you can message them.')"
                                class="btn-disabled-flat w-full block text-center py-2 px-3 text-xs font-bold">
                                Send message
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div class="swiss-panel text-center py-12 p-6 w-full">
                <p class="text-gray-500">No tutors found matching your current filter choices.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- MASTER FILTER FORM (One single instance declared in DOM, dynamically re-parented via JS depending on screen width) -->
<div class="hidden">
    <form id="tutor-filters-form" action="{{ route('tutors.browse') }}" method="GET" class="space-y-4">
        <!-- City Selection -->
        <div>
            <label for="location_id" class="block text-xs font-bold text-gray-700 uppercase">City</label>
            <select id="location_id" name="location_id" class="swiss-select mt-1 block w-full py-2 px-3 text-sm">
                <option value="">All Cities</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Address Selection -->
        <div>
            <label for="address" class="block text-xs font-bold text-gray-700 uppercase">Sub-City / District</label>
            <select id="address" name="address" disabled class="swiss-select mt-1 block w-full py-2 px-3 text-sm">
                <option value="">Select a City first</option>
            </select>
        </div>

        <!-- Category Selection -->
        <div>
            <label for="category_id" class="block text-xs font-bold text-gray-700 uppercase">Category</label>
            <select id="category_id" name="category_id" class="swiss-select mt-1 block w-full py-2 px-3 text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Subject Selection -->
        <div>
            <label for="subject_id" class="block text-xs font-bold text-gray-700 uppercase">Subject</label>
            <select id="subject_id" name="subject_id" disabled class="swiss-select mt-1 block w-full py-2 px-3 text-sm">
                <option value="">Select a Category first</option>
            </select>
        </div>

        <!-- Teaching Mode Selection -->
        <div>
            <label for="teaching_mode" class="block text-xs font-bold text-gray-700 uppercase">Teaching Method</label>
            <select id="teaching_mode" name="teaching_mode" class="swiss-select mt-1 block w-full py-2 px-3 text-sm">
                <option value="">All Methods</option>
                <option value="online" {{ request('teaching_mode') == 'online' ? 'selected' : '' }}>Online</option>
                <option value="in-person" {{ request('teaching_mode') == 'in-person' ? 'selected' : '' }}>In-Person</option>
                <option value="hybrid" {{ request('teaching_mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
            </select>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-swiss-primary w-full flex justify-center py-2 px-4 text-sm font-semibold text-white">
                Apply Filters
            </button>
            <a href="{{ route('tutors.browse') }}" class="btn-swiss-outline mt-2 w-full flex justify-center py-2 px-4 text-sm font-semibold">
                Clear All
            </a>
        </div>
    </form>
</div>

<!-- INTERACTIVE BOOKING MODAL -->
<div id="booking_modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm select-none transition duration-300">
    <div class="swiss-panel bg-white w-full max-w-xl p-8 relative">
        <button type="button" id="close_booking_modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <div class="pb-4 mb-6" style="border-bottom: 1px solid var(--line);">
            <h2 class="text-2xl display-font text-gray-900">Book a Lesson</h2>
            <p class="text-sm text-gray-500 mt-1">Schedule a session with <strong id="modal_tutor_name" style="color: var(--blue);"></strong></p>
        </div>

        <form id="booking_form" action="" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" id="session_date" name="session_date" required>
            <input type="hidden" id="start_time" name="start_time" required>
            <input type="hidden" id="end_time" name="end_time" required>
            <input type="hidden" id="old_tutor_username" name="old_tutor_username">
            <input type="hidden" id="old_tutor_name" name="old_tutor_name">
            <input type="hidden" id="old_tutor_schedules" name="old_tutor_schedules">

            <!-- 1. Highlighted Calendar Grid -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Date (Highlighted Days are Available)</label>
                <div class="p-4" style="background: var(--paper); border: 1px solid var(--line);">
                    <div class="flex justify-between items-center mb-4">
                        <span id="calendar_month_year" class="text-sm font-bold text-gray-900"></span>
                        <div class="flex gap-2">
                            <button type="button" id="prev_month" class="p-1.5 text-gray-600 hover:bg-gray-200 text-xs font-bold">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 6L9 12L15 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <button type="button" id="next_month" class="p-1.5 text-gray-600 hover:bg-gray-200 text-xs font-bold">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6L15 12L9 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>
                    <div id="calendar_days_grid" class="grid grid-cols-7 gap-1 text-center font-bold"></div>
                </div>
            </div>

            <!-- 2. Dynamic Available Slots -->
            <div id="slots_section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
                <div id="slots_container" class="grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
            </div>

            <!-- 3. Booking Notes -->
            <div>
                <label for="note" class="block text-sm font-medium text-gray-700">Message / Note to Tutor (Optional)</label>
                <textarea id="note" name="note" rows="3" placeholder="Tell the tutor what topics you'd like to focus on during this lesson..." class="swiss-input mt-1 block w-full px-3 py-2 sm:text-sm">{{ old('note') }}</textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3" style="border-top: 1px solid var(--line);">
                <button type="button" id="cancel_booking_modal" class="btn-swiss-outline px-4 py-2 text-sm font-semibold">Cancel</button>
                <button type="submit" id="submit_btn" disabled class="btn-swiss-primary px-5 py-2 text-sm font-semibold text-white disabled:opacity-50">Request Session Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- DYNAMIC FORM RE-PARENTING CONTROLLER ---
    const mainFiltersForm = document.getElementById('tutor-filters-form');
    const desktopFormSlot = document.getElementById('desktop-form-slot');
    const mobileFormSlot = document.getElementById('mobile-form-slot');

    function syncFormPosition() {
        if (window.innerWidth >= 1024) { // Desktop
            if (desktopFormSlot && mainFiltersForm.parentNode !== desktopFormSlot) {
                desktopFormSlot.appendChild(mainFiltersForm);
            }
        } else { // Mobile
            if (mobileFormSlot && mainFiltersForm.parentNode !== mobileFormSlot) {
                mobileFormSlot.appendChild(mainFiltersForm);
            }
        }
    }

    window.addEventListener('resize', syncFormPosition);
    syncFormPosition(); // Trigger on initial load


    // --- MOBILE DRAWER EVENT HANDLERS ---
    const mobileFilterTrigger = document.getElementById('mobile-filter-trigger');
    const mobileDrawerOverlay = document.getElementById('mobile-drawer-overlay');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const mobileDrawerClose = document.getElementById('mobile-drawer-close');

    function openMobileFilters() {
        mobileDrawerOverlay.classList.remove('hidden');
        setTimeout(() => {
            mobileDrawerOverlay.classList.add('opacity-100');
            mobileDrawer.classList.remove('-translate-x-full');
        }, 50);
    }

    function closeMobileFilters() {
        mobileDrawerOverlay.classList.remove('opacity-100');
        mobileDrawer.classList.add('-translate-x-full');
        setTimeout(() => {
            mobileDrawerOverlay.classList.add('hidden');
        }, 300);
    }

    if (mobileFilterTrigger) mobileFilterTrigger.addEventListener('click', openMobileFilters);
    if (mobileDrawerClose) mobileDrawerClose.addEventListener('click', closeMobileFilters);
    if (mobileDrawerOverlay) mobileDrawerOverlay.addEventListener('click', closeMobileFilters);


    // --- SIDEBAR DROPDOWNS MAPS ---
    const addressOptions = {
        "1": ["Bole", "Megenagna", "Piazza (Addis)", "Arat Kilo", "Sarbet", "Kazanchis"],
        "2": ["Piassa (Hawassa)", "Atote", "Alamura", "Tabor", "Millennium", "Chefe"]
    };

    const categoryOptions = {
        @foreach($categories as $category)
            "{{ $category->id }}": [
                @foreach($category->subjects as $subj)
                    { "id": "{{ $subj->id }}", "name": "{{ $subj->name }}" },
                @endforeach
            ],
        @endforeach
    };

    const locationSelect = document.getElementById('location_id');
    const addressSelect = document.getElementById('address');
    const categorySelect = document.getElementById('category_id');
    const subjectSelect = document.getElementById('subject_id');

    const oldAddress = "{{ request('address') }}";
    const oldSubject = "{{ request('subject_id') }}";

    function updateAddresses() {
        const locId = locationSelect.value;
        addressSelect.innerHTML = '<option value="">All Districts</option>';

        if (locId && addressOptions[locId]) {
            addressSelect.disabled = false;
            addressSelect.classList.remove('bg-gray-100');
            addressOptions[locId].forEach(addr => {
                const opt = document.createElement('option');
                opt.value = addr;
                opt.textContent = addr;
                if (oldAddress === addr) opt.selected = true;
                addressSelect.appendChild(opt);
            });
        } else {
            addressSelect.disabled = true;
            addressSelect.classList.add('bg-gray-100');
        }
    }

    function updateSubjects() {
        const catId = categorySelect.value;
        subjectSelect.innerHTML = '<option value="">All Subjects</option>';

        if (catId && categoryOptions[catId]) {
            subjectSelect.disabled = false;
            subjectSelect.classList.remove('bg-gray-100');
            categoryOptions[catId].forEach(subj => {
                const opt = document.createElement('option');
                opt.value = subj.id;
                opt.textContent = subj.name;
                if (oldSubject === subj.id.toString()) opt.selected = true;
                subjectSelect.appendChild(opt);
            });
        } else {
            subjectSelect.disabled = true;
            subjectSelect.classList.add('bg-gray-100');
        }
    }

    locationSelect.addEventListener('change', updateAddresses);
    categorySelect.addEventListener('change', updateSubjects);

    if (locationSelect.value) updateAddresses();
    if (categorySelect.value) updateSubjects();


    // --- DYNAMIC BOOKING MODAL CONTROLLER ---
    const bookingModal = document.getElementById('booking_modal');
    const closeBookingBtn = document.getElementById('close_booking_modal');
    const cancelBookingBtn = document.getElementById('cancel_booking_modal');
    const modalTutorName = document.getElementById('modal_tutor_name');
    const bookingForm = document.getElementById('booking_form');

    const calendarMonthYear = document.getElementById('calendar_month_year');
    const calendarDaysGrid = document.getElementById('calendar_days_grid');
    const prevMonthBtn = document.getElementById('prev_month');
    const nextMonthBtn = document.getElementById('next_month');

    const sessionDateInput = document.getElementById('session_date');
    const slotsSection = document.getElementById('slots_section');
    const slotsContainer = document.getElementById('slots_container');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const submitBtn = document.getElementById('submit_btn');

    const oldTutorUsername = document.getElementById('old_tutor_username');
    const oldTutorName = document.getElementById('old_tutor_name');
    const oldTutorSchedules = document.getElementById('old_tutor_schedules');

    let currentDate = new Date();
    const today = new Date();
    let activeTutorSchedules = [];
    let tutorActiveWeekdays = [];

    function openBookingModal(username, fullName, schedulesJson) {
        bookingForm.action = "/tutors/" + username + "/book";
        modalTutorName.textContent = fullName;
        activeTutorSchedules = schedulesJson;
        tutorActiveWeekdays = [...new Set(activeTutorSchedules.map(s => s.day_of_week))];

        oldTutorUsername.value = username;
        oldTutorName.value = fullName;
        oldTutorSchedules.value = JSON.stringify(schedulesJson);

        sessionDateInput.value = '';
        startTimeInput.value = '';
        endTimeInput.value = '';
        slotsSection.classList.add('hidden');
        submitBtn.disabled = true;

        currentDate = new Date();
        renderCalendar();
        bookingModal.classList.remove('hidden');
    }

    function closeBookingModal() { bookingModal.classList.add('hidden'); }

    closeBookingBtn.addEventListener('click', closeBookingModal);
    cancelBookingBtn.addEventListener('click', closeBookingModal);
    bookingModal.addEventListener('click', (e) => { if (e.target === bookingModal) closeBookingModal(); });

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        calendarMonthYear.textContent = `${monthNames[month]} ${year}`;
        calendarDaysGrid.innerHTML = '';

        const firstDayIndex = new Date(year, month, 1).getDay();
        const totalDays = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDayIndex; i++) {
            calendarDaysGrid.appendChild(document.createElement('span'));
        }

        for (let dayNum = 1; dayNum <= totalDays; dayNum++) {
            const dateObj = new Date(year, month, dayNum);
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
            const weekdaysMap = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = weekdaysMap[dateObj.getDay()];

            const button = document.createElement('button');
            button.type = 'button';
            button.className = "h-8 w-8 text-xs font-semibold flex items-center justify-center mx-auto transition select-none ";

            const isFutureOrToday = dateObj.setHours(0,0,0,0) >= today.setHours(0,0,0,0);
            const isAvailableDay = tutorActiveWeekdays.includes(dayName);

            if (isFutureOrToday && isAvailableDay) {
                button.classList.add('cal-day-available', 'cursor-pointer');
                button.setAttribute('data-date', dateString);
                button.addEventListener('click', function() {
                    document.querySelectorAll('[data-date]').forEach(btn => {
                        btn.classList.remove('cal-day-selected');
                        btn.classList.add('cal-day-available');
                    });
                    this.classList.remove('cal-day-available');
                    this.classList.add('cal-day-selected');
                    sessionDateInput.value = dateString;
                    showSlots(dayName);
                });
            } else {
                button.classList.add('cal-day-disabled');
                button.disabled = true;
            }
            button.textContent = dayNum;
            calendarDaysGrid.appendChild(button);
        }
    }

    function showSlots(dayName) {
        slotsContainer.innerHTML = '';
        submitBtn.disabled = true;
        const availableSlots = activeTutorSchedules.filter(sched => sched.day_of_week === dayName);

        if (availableSlots.length > 0) {
            slotsSection.classList.remove('hidden');
            availableSlots.forEach(slot => {
                const button = document.createElement('button');
                button.type = 'button';
                const startClean = slot.start_time.substring(0, 5);
                const endClean = slot.end_time.substring(0, 5);
                button.className = "time-slot-btn py-2.5 px-4 text-sm font-medium text-gray-700 text-center";
                button.textContent = `${startClean} - ${endClean}`;
                button.addEventListener('click', function() {
                    document.querySelectorAll('.time-slot-btn').forEach(btn => btn.classList.remove('time-slot-selected'));
                    this.classList.add('time-slot-selected');
                    startTimeInput.value = slot.start_time.substring(0, 5);
                    endTimeInput.value = slot.end_time.substring(0, 5);
                    submitBtn.disabled = false;
                });
                slotsContainer.appendChild(button);
            });
        }
    }

    prevMonthBtn.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); });
    nextMonthBtn.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); });

    @if($errors->any() && old('session_date'))
        window.addEventListener('DOMContentLoaded', () => {
            const rawScheds = {!! old('old_tutor_schedules') !!};
            openBookingModal("{{ old('old_tutor_username') }}", "{{ old('old_tutor_name') }}", rawScheds);
            sessionDateInput.value = "{{ old('session_date') }}";
            const parts = sessionDateInput.value.split('-');
            currentDate = new Date(parts[0], parts[1] - 1, parts[2]);
            renderCalendar();
            const activeBtn = document.querySelector(`[data-date="${sessionDateInput.value}"]`);
            if (activeBtn) activeBtn.click();
        });
    @endif
</script>
@endsection