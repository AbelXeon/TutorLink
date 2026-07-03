<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connect with verified academic and programming tutors in Ethiopia. Browse tutor profiles, view schedules, read reviews, and book lessons easily on TutorLink.">
    <title>TutorLink - Find and Book Verified Tutors in Ethiopia</title>

    <!-- Bootstrap CDN for Landing Page layouts (grid only, visuals overridden below) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Swiss-style type pairing: Bebas Neue (display) + Inter (body/utility) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite assets integration -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root{
            --ink:#0a0a0a;
            --ink-hover:#242424;
            --paper:#f5f4f1;
            --white:#ffffff;
            --blue:#1350e0;
            --blue-dark:#0d3aa8;
            --line: rgba(10,10,10,0.14);
        }

        * { box-sizing: border-box; }

        html { overflow-x: hidden; }
        body { overflow-x: hidden; }

        body{
            background: var(--paper);
            color: var(--ink);
            font-family: 'Inter', -apple-system, sans-serif;
            font-weight: 400;
        }

        h1, h2, h3, h4, h5,
        .display-font, .eyebrow, .step-num, .btn, .badge, .pill-label {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            font-weight: 400;
        }

        p, .lede, .text-muted { font-family: 'Inter', sans-serif; }

        .rule { border: none; border-top: 1px solid var(--line); }

        /* Hero */
        .swiss-hero {
            background: var(--white);
            border-bottom: 1px solid var(--line);
            padding: 6rem 0 5rem;
        }
        .eyebrow {
            display: inline-block;
            font-size: 0.8rem;
            letter-spacing: 0.18em;
            padding-bottom: 6px;
            border-bottom: 2px solid var(--blue);
            margin-bottom: 2rem;
        }
        .swiss-hero h1 {
            font-size: clamp(3.2rem, 8vw, 6rem);
            line-height: 0.95;
            margin-bottom: 1.75rem;
        }
        .swiss-hero h1 span { color: var(--blue); }
        .swiss-hero .lede {
            font-size: 1.15rem;
            line-height: 1.6;
            color: #4a4a4a;
            max-width: 640px;
        }

        /* Buttons — flat, square, Swiss. Hover states use solid color swaps
           (never opacity) so the button never lightens/washes out. */
        .btn-swiss-primary {
            background-color: var(--ink);
            color: var(--white) !important;
            border: 1px solid var(--ink);
            border-radius: 0;
            padding: 0.85rem 2rem;
            font-size: 0.95rem;
            letter-spacing: 0.08em;
            transition: background-color .15s ease, border-color .15s ease;
        }
        .btn-swiss-primary:hover,
        .btn-swiss-primary:focus {
            background-color: var(--blue);
            border-color: var(--blue);
            color: var(--white) !important;
        }

        .btn-swiss-outline {
            background-color: transparent;
            color: var(--ink) !important;
            border: 1px solid var(--ink);
            border-radius: 0;
            padding: 0.85rem 2rem;
            font-size: 0.95rem;
            letter-spacing: 0.08em;
            transition: background-color .15s ease, color .15s ease;
        }
        .btn-swiss-outline:hover,
        .btn-swiss-outline:focus {
            background-color: var(--ink);
            color: var(--white) !important;
        }

        .btn-swiss-accent {
            background-color: var(--blue);
            color: var(--white) !important;
            border: 1px solid var(--blue);
            border-radius: 0;
            padding: 0.85rem 2rem;
            font-size: 0.95rem;
            letter-spacing: 0.08em;
            transition: background-color .15s ease, border-color .15s ease;
        }
        .btn-swiss-accent:hover,
        .btn-swiss-accent:focus {
            background-color: var(--blue-dark);
            border-color: var(--blue-dark);
            color: var(--white) !important;
        }

        /* Registration cards */
        .section-label {
            font-size: 0.85rem;
            letter-spacing: 0.2em;
            color: #6b6b6b;
        }
        .section-title { font-size: 2.4rem; }

        .swiss-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 0;
            border-top: 4px solid var(--ink);
            padding: 3rem 2.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .swiss-card.accent-blue { border-top-color: var(--blue); }

        .swiss-icon {
            width: 44px;
            height: 44px;
            margin-bottom: 1.5rem;
        }

        .swiss-card h3 { font-size: 1.6rem; margin-bottom: 1rem; }
        .swiss-card p { color: #4a4a4a; line-height: 1.6; }

        /* How it works */
        .swiss-panel {
            background: var(--white);
            border: 1px solid var(--line);
            padding: 2.75rem;
            height: 100%;
        }
        .pill-label {
            display: inline-block;
            font-size: 0.85rem;
            letter-spacing: 0.15em;
            padding: 0.3rem 0.9rem;
            border: 1px solid var(--ink);
            color: var(--ink);
            margin-bottom: 2rem;
        }
        .swiss-panel.accent-blue .pill-label {
            border-color: var(--blue);
            color: var(--blue);
        }

        .step-row { display: flex; padding: 1.4rem 0; border-top: 1px solid var(--line); }
        .step-row:first-of-type { border-top: none; }
        .step-num {
            font-size: 2rem;
            color: var(--blue);
            width: 3rem;
            flex-shrink: 0;
            line-height: 1;
        }
        .swiss-panel.accent-blue .step-num { color: var(--ink); }
        .step-row h5 { font-size: 1.05rem; margin-bottom: 0.35rem; text-transform: none; font-family: 'Inter', sans-serif; font-weight: 700; letter-spacing: 0; }
        .step-row .text-sm { color: #5a5a5a; font-size: 0.92rem; line-height: 1.55; }

        /* CTA */
        .swiss-cta {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 0;
            padding: 3.5rem 2rem;
        }

        /* Auto-scrolling marquee — content travels right to left, seamless loop */
        .marquee-section {
            background: var(--ink);
            overflow: hidden;
            padding: 1.1rem 0;
            border-top: 1px solid var(--ink);
            border-bottom: 1px solid var(--ink);
        }
        .marquee-track {
            display: flex;
            width: max-content;
            align-items: center;
            white-space: nowrap;
            animation: marquee-scroll 32s linear infinite;
        }
        .marquee-item {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.08em;
            font-size: 1.35rem;
            color: var(--white);
            padding: 0 1.25rem;
        }
        .marquee-dot {
            color: var(--blue);
            font-size: 1.1rem;
        }
        @keyframes marquee-scroll {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        @media (prefers-reduced-motion: reduce) {
            .marquee-track { animation: none; }
        }

        /* ============ RESPONSIVE / MOBILE ============ */
        @media (max-width: 991px) {
            .section-title { font-size: 2rem; }
        }

        @media (max-width: 767px) {
            .swiss-hero { padding: 4rem 0 3rem; }
            .swiss-hero .lede { font-size: 1rem; padding: 0 0.5rem; }
            .swiss-card { padding: 2.25rem 1.75rem; }
            .swiss-panel { padding: 2rem 1.5rem; }
            .swiss-cta { padding: 2.5rem 1.5rem; }
            .section-title { font-size: 1.75rem; }
            .btn-swiss-primary, .btn-swiss-outline, .btn-swiss-accent {
                width: 100%;
                text-align: center;
            }
            .swiss-hero .d-flex.gap-3 { flex-direction: column; }
            .marquee-item { font-size: 1.05rem; padding: 0 0.9rem; }
            .marquee-track { animation-duration: 22s; }
            .step-num { font-size: 1.6rem; width: 2.4rem; }
        }

        @media (max-width: 480px) {
            .swiss-hero h1 { font-size: 2.6rem; }
            .eyebrow { font-size: 0.7rem; }
            .swiss-card h3 { font-size: 1.35rem; }
            .swiss-icon { width: 36px; height: 36px; }
            .marquee-item { font-size: 0.9rem; padding: 0 0.65rem; }
            .marquee-track { animation-duration: 16s; }
        }
    </style>
</head>
<body>

<!-- HERO SECTION -->
<div class="swiss-hero">
    <div class="container py-4 text-center">
        <span class="eyebrow">Connecting Minds</span>
        <h1 class="display-font">Welcome to <span>TutorLink</span></h1>
        <p class="lede mx-auto mb-4">
            TutorLink is a modern platform that connects passionate, verified educators with students seeking academic support and programming excellence across Ethiopia. Whether you want to master a coding language or prepare for academic exams, we make finding the perfect mentor simple.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="#how-it-works" class="btn btn-swiss-outline px-4 py-2">Learn How It Works</a>
            <a href="{{ route('login') }}" class="btn btn-swiss-primary px-4 py-2">Sign In to Account</a>
        </div>
    </div>
</div>

<!-- REGISTRATION CHOICES -->
<div class="container py-5">
    <div class="text-center mb-5">
        <div class="section-label mb-2">Get Started</div>
        <h2 class="display-font section-title">Get Started with TutorLink</h2>
        <p class="text-muted text-sm">Choose your path and register an account today</p>
    </div>

    <div class="row justify-content-center g-4">
        <!-- Student Card -->
        <div class="col-md-5">
            <div class="swiss-card">
                <div>
                    <svg class="swiss-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 6L4 16L24 26L44 16L24 6Z" stroke="#0a0a0a" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M12 21V32C12 32 16 37 24 37C32 37 36 32 36 32V21" stroke="#0a0a0a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M44 16V28" stroke="#0a0a0a" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h3 class="display-font">Register as a Student</h3>
                    <p class="mb-4">
                        Gain access to experienced private tutors. Browse verified profiles, match based on subjects or location within Ethiopia, and book structured lessons to reach your goals.
                    </p>
                </div>
                <a href="{{route('Auth.Student_Register')}}" class="btn btn-swiss-primary w-100 py-2.5">
                    Create Student Account
                </a>
            </div>
        </div>

        <!-- Tutor Card -->
        <div class="col-md-5">
            <div class="swiss-card accent-blue">
                <div>
                    <svg class="swiss-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="6" y="8" width="36" height="24" rx="1" stroke="#1350e0" stroke-width="2"/>
                        <path d="M6 26H42" stroke="#1350e0" stroke-width="2"/>
                        <path d="M14 26L10 40" stroke="#1350e0" stroke-width="2" stroke-linecap="round"/>
                        <path d="M34 26L38 40" stroke="#1350e0" stroke-width="2" stroke-linecap="round"/>
                        <path d="M14 40H38" stroke="#1350e0" stroke-width="2" stroke-linecap="round"/>
                        <path d="M13 16L20 21L30 12" stroke="#1350e0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3 class="display-font">Register as a Tutor</h3>
                    <p class="mb-4">
                        Share your academic or software development expertise, set your own hourly rates, manage your session availability schedules, and grow your independent tutoring income.
                    </p>
                </div>
                <a href="{{route('Auth.Teacher_Register')}}" class="btn btn-swiss-accent w-100 py-2.5">
                    Create Tutor Account
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MARQUEE: SUBJECTS / SKILLS TICKER -->
<div class="marquee-section">
    <div class="marquee-track">
        <span class="marquee-item">Mathematics</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Python</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Physics</span><span class="marquee-dot">/</span>
        <span class="marquee-item">JavaScript</span><span class="marquee-dot">/</span>
        <span class="marquee-item">English</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Chemistry</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Web Development</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Amharic</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Biology</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Data Structures</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Calculus</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Java</span><span class="marquee-dot">/</span>
        <!-- duplicate set for a seamless infinite loop -->
        <span class="marquee-item">Mathematics</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Python</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Physics</span><span class="marquee-dot">/</span>
        <span class="marquee-item">JavaScript</span><span class="marquee-dot">/</span>
        <span class="marquee-item">English</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Chemistry</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Web Development</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Amharic</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Biology</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Data Structures</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Calculus</span><span class="marquee-dot">/</span>
        <span class="marquee-item">Java</span><span class="marquee-dot">/</span>
    </div>
</div>

<!-- HOW IT WORKS SECTION -->
<div id="how-it-works" class="py-5" style="background: var(--white); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <div class="section-label mb-2">The Process</div>
            <h2 class="display-font section-title">How TutorLink Works</h2>
            <p class="text-muted col-md-6 mx-auto">Explore the structured steps designed to make private tutoring simple, secure, and highly effective.</p>
        </div>

        <div class="row g-4">
            <!-- How it works for Students -->
            <div class="col-md-6">
                <div class="swiss-panel">
                    <span class="pill-label">For Students</span>

                    <div class="step-row">
                        <div class="step-num display-font">01</div>
                        <div>
                            <h5>Search &amp; Filter</h5>
                            <p class="text-sm mb-0">Browse through our database of active, verified tutors. Filter your choices by specific subject, city, or local sub-city details.</p>
                        </div>
                    </div>

                    <div class="step-row">
                        <div class="step-num display-font">02</div>
                        <div>
                            <h5>Select a Schedule</h5>
                            <p class="text-sm mb-0">Review the tutor's open schedule slots. Book a lesson directly through the platform, choosing a time slot that matches your availability.</p>
                        </div>
                    </div>

                    <div class="step-row">
                        <div class="step-num display-font">03</div>
                        <div>
                            <h5>Start Learning</h5>
                            <p class="text-sm mb-0">Once the tutor accepts your lesson booking, you will receive real-time dashboard notifications. Meet your tutor and start your session.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How it works for Tutors -->
            <div class="col-md-6">
                <div class="swiss-panel accent-blue">
                    <span class="pill-label">For Tutors</span>

                    <div class="step-row">
                        <div class="step-num display-font">01</div>
                        <div>
                            <h5>Build a Profile</h5>
                            <p class="text-sm mb-0">Create an account, submit your educational details or programming experience, set your profile image, and list your pricing rates.</p>
                        </div>
                    </div>

                    <div class="step-row">
                        <div class="step-num display-font">02</div>
                        <div>
                            <h5>Configure Calendar</h5>
                            <p class="text-sm mb-0">Use your tutor dashboard to configure your weekly availability schedule, letting prospective students see when you are open for bookings.</p>
                        </div>
                    </div>

                    <div class="step-row">
                        <div class="step-num display-font">03</div>
                        <div>
                            <h5>Confirm &amp; Teach</h5>
                            <p class="text-sm mb-0">Accept or reject pending lesson requests from students via your dashboard, coordinate sessions, and grow your teaching career.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ACCOUNT RETRIEVAL AND LOGIN PROMPT -->
<div class="container py-5 text-center">
    <div class="swiss-cta col-md-8 mx-auto">
        <h4 class="display-font mb-2" style="font-size: 1.8rem;">Already Registered?</h4>
        <p class="text-muted text-sm mb-4">Log in to your student, tutor, or administrator portal directly.</p>
        <a href="{{route('login')}}" class="btn btn-swiss-outline px-5 py-2.5">
            Go to Login Page
        </a>
    </div>
</div>

@include('Layouts.Footer')

</body>
</html>