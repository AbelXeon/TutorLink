<footer class="site-footer bg-white text-gray-600 py-12 border-t" style="border-color: rgba(10,10,10,0.14); position: relative; overflow: hidden;">

    <!-- Decorative ring: cropped by the footer's own overflow:hidden, so only
         the right half ever shows, tucked behind the TutorLink brand column. -->
    <div aria-hidden="true" class="footer-ring"></div>

    <style>
        /* Scoped, self-contained footer styles.
           These are written as plain CSS (not Tailwind utility classes) so the
           footer looks identical on every page regardless of whether that page
           loads Bootstrap, Tailwind, both, or in what order — no more links
           inheriting a stray browser/Bootstrap default blue-underline style. */
        .site-footer a {
            color: #4b5563;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color .15s ease;
        }
        .site-footer a:hover,
        .site-footer a:focus {
            color: #1350e0;
            text-decoration: none;
        }
        .site-footer p,
        .site-footer li {
            font-size: 0.9rem;
        }
        .site-footer .footer-brand {
            font-size: 1.6rem;
        }
        .site-footer h5 {
            font-size: 0.85rem;
        }
        .site-footer .footer-meta {
            font-size: 0.75rem;
        }
        .footer-ring {
            position: absolute;
            left: -150px;
            top: 50%;
            transform: translateY(-50%);
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 1.5px solid rgba(19, 80, 224, 0.35);
            background: radial-gradient(circle at 35% 30%, rgba(19, 80, 224, 0.10), transparent 70%);
            pointer-events: none;
            z-index: 0;
        }
        .footer-content { position: relative; z-index: 1; }
        @media (max-width: 767px) {
            .footer-ring { width: 200px; height: 200px; left: -100px; opacity: 0.7; }
        }
    </style>

    <div class="footer-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-8">

        <!-- Col 1: Brand details -->
        <div class="space-y-4">
            <span class="footer-brand text-gray-900 tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">TutorLink</span>
            <p class="text-gray-600 leading-relaxed">
                Connecting passionate, verified educators with students seeking academic and programming excellence across Ethiopia.
            </p>
        </div>

        <!-- Col 2: Services / Navigation -->
        <div class="space-y-3">
            <h5 class="text-gray-900 uppercase tracking-widest" style="font-family: 'Bebas Neue', sans-serif;">Services</h5>
            <ul class="space-y-2.5">
                <li>
                    <a href="{{ route('tutors.browse') }}" class="inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="M21 21L16.65 16.65" stroke-linecap="round"/>
                        </svg>
                        <span>Browse Active Tutors</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('Auth.Teacher_Register') }}" class="inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="12" rx="0.5"/>
                            <path d="M3 13H21" stroke-linecap="round"/>
                            <path d="M7 20H17" stroke-linecap="round"/>
                        </svg>
                        <span>Register as Teacher</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('Auth.Student_Register') }}" class="inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3L2 8L12 13L22 8L12 3Z" stroke-linejoin="round"/>
                            <path d="M6 10.5V16C6 16 8.5 19 12 19C15.5 19 18 16 18 16V10.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Register as Student</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Col 3: Legal & Terms -->
        <div class="space-y-3">
            <h5 class="text-gray-900 uppercase tracking-widest" style="font-family: 'Bebas Neue', sans-serif;">Legal &amp; Compliance</h5>
            <ul class="space-y-2.5">
                <li>
                    <a href="{{ route('terms') }}" class="inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 3H15L19 7V21H7V3Z" stroke-linejoin="round"/>
                            <path d="M15 3V7H19" stroke-linejoin="round"/>
                            <path d="M9.5 12H14.5" stroke-linecap="round"/>
                            <path d="M9.5 15.5H14.5" stroke-linecap="round"/>
                        </svg>
                        <span>Terms of Service</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('privacy') }}" class="inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3L4 6V11C4 16 7.5 19.5 12 21C16.5 19.5 20 16 20 11V6L12 3Z" stroke-linejoin="round"/>
                        </svg>
                        <span>Privacy Policy</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Col 4: Support / Help Center -->
        <div class="space-y-3">
            <h5 class="text-gray-900 uppercase tracking-widest" style="font-family: 'Bebas Neue', sans-serif;">Help &amp; Support</h5>
            <ul class="space-y-2.5">
                <li>
                    <a href="mailto:support@tutorlink.com?subject=TutorLink%20Support%20Request" class="inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="5" width="18" height="14" rx="0.5"/>
                            <path d="M3 6.5L12 13L21 6.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Contact Us (abeltiruneh2468@gmail.com)</span>
                    </a>
                </li>
                <li class="footer-meta text-gray-600 pl-5.5">Response time: Under 24 Hours</li>
            </ul>
        </div>
    </div>

    <div class="footer-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t footer-meta text-center text-gray-600" style="border-color: rgba(10,10,10,0.14);">
        TutorLink &copy; {{ date('Y') }} . All rights reserved.
    </div>
</footer>