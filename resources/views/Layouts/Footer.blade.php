<footer class="bg-zinc-900 text-zinc-400 py-12 border-t border-zinc-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">

        <!-- Col 1: Brand details -->
        <div class="space-y-4">
            <span class="text-xl font-extrabold text-white tracking-wider">TutorLink</span>
            <p class="text-xs text-zinc-500 leading-relaxed">
                Connecting passionate, verified educators with students seeking academic and programming excellence across Ethiopia.
            </p>
        </div>

        <!-- Col 2: Services / Navigation -->
        <div class="space-y-3">
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Services</h5>
            <ul class="space-y-2 text-xs">
                <li><a href="{{ route('tutors.browse') }}" class="hover:text-indigo-400 transition">🔍 Browse Active Tutors</a></li>
                <li><a href="{{ route('Auth.Teacher_Register') }}" class="hover:text-indigo-400 transition">👨‍🏫 Register as Teacher</a></li>
                <li><a href="{{ route('Auth.Student_Register') }}" class="hover:text-indigo-400 transition">🎓 Register as Student</a></li>
            </ul>
        </div>

        <!-- Col 3: Legal & Terms -->
        <div class="space-y-3">
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Legal & Compliance</h5>
            <ul class="space-y-2 text-xs">
                <li><a href="{{ route('terms') }}" class="hover:text-indigo-400 transition">📄 Terms of Service</a></li>
                <li><a href="{{ route('privacy') }}" class="hover:text-indigo-400 transition">🛡️ Privacy Policy</a></li>
            </ul>
        </div>

        <!-- Col 4: Support / Help Center -->
        <div class="space-y-3">
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Help & Support</h5>
            <ul class="space-y-2 text-xs">
                <li><a href="mailto:support@tutorlink.com?subject=TutorLink%20Support%20Request" class="hover:text-indigo-400 transition">✉️ Contact Us (abeltiruneh2468@gmail.com)</a></li>
                <li class="text-zinc-500 text-[10px]">Response time: Under 24 Hours</li>
            </ul>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t border-zinc-800 text-center text-xs text-zinc-500">
        TutorLink &copy; {{ date('Y') }} . All rights reserved.
    </div>
</footer>