<div align="center">

<img src="./public/assets/banner.svg" alt="TutorLink banner" width="100%" />

<br/>

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![Vite](https://img.shields.io/badge/Vite-Build-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Database-4169E1?style=flat-square&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Render](https://img.shields.io/badge/Deployed_on-Render-46E3B7?style=flat-square&logo=render&logoColor=white)](https://tutorlink-km9e.onrender.com)
[![License](https://img.shields.io/badge/License-MIT-black?style=flat-square)](#license)

**[Live Demo → tutorlink-km9e.onrender.com](https://tutorlink-km9e.onrender.com)**

</div>

<br/>

## About TutorLink

In most communities, finding a tutor still works the same way it did decades ago: a parent asks a neighbor, a neighbor asks a friend, and eventually someone knows "a guy who teaches math." There's no way to compare qualifications, see real availability, check pricing up front, or read what other parents and students actually thought of that tutor. It's slow, it's limited to whoever happens to be in your immediate circle, and it leaves a lot of good tutors undiscovered.

**TutorLink replaces that word-of-mouth chain with an open, searchable marketplace.**

- **Learners** — whether that's a parent booking lessons for their child, or an adult picking up a new subject or skill for themselves — can search verified tutors by subject, category, city, sub-city, and teaching mode (online, in-person, or hybrid), then book a lesson directly against a tutor's real weekly availability.
- **Tutors** — anyone with expertise to teach, academic or technical — can build a public profile, set their own hourly rate, define their availability, and start receiving booking requests, without needing to already know their students.

The platform handles the entire relationship end-to-end: discovery, booking, scheduling, in-app messaging, and post-lesson reviews — so trust is built on verified profiles and real feedback, not just who happens to live nearby.

---

## Table of Contents

- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Live Demo](#live-demo)
- [Getting Started](#getting-started)
- [Project Structure](#project-structure)
- [Design System](#design-system)
- [Roadmap](#roadmap)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

---

## Key Features

### For Students / Learners
- Account registration with email verification (6-digit code, resend cooldown)
- Search and filter tutors by city, sub-city, category, subject, and teaching mode
- Detailed tutor profiles: qualifications, experience, rate, weekly schedule, and student reviews
- Interactive booking flow — pick an available day from a live calendar, then a specific open time slot
- Direct, real-time messaging with a booked tutor, including image, document, and live-location sharing
- Leave a star rating and written review after a completed lesson

### For Tutors
- Guided profile setup: qualification, experience, hourly rate, teaching mode, max students per session
- Category and subject selection, grade levels taught, and a dynamic weekly availability builder
- Dashboard with pending booking requests (accept/decline), active students, and schedule overview
- Profile editing with a limited number of edits per day to encourage accurate, stable listings
- Direct messaging with students once a booking is accepted

### Platform-Wide
- Secure authentication (login, registration, email verification) with CSRF protection throughout
- Real-time notification system with unread counts, sound alerts, and a live-updating dropdown
- Account settings modal: change username or password via a verified-email, code-based flow
- Fully responsive, mobile-first layout — from the landing page down to the in-app chat window
- Consistent, minimal visual design system (see [Design System](#design-system))

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP / Laravel |
| Frontend Templating | Laravel Blade |
| Styling | Tailwind CSS |
| Build Tooling | Vite |
| Database | PostgreSQL |
| Hosting | Render |
| Real-time UX | Polling-based live messaging & notifications (AJAX / Fetch API) |
| Fonts | Bebas Neue (display), Inter (body) |

---

## Live Demo

TutorLink is deployed on Render and available here:

**[https://tutorlink-km9e.onrender.com](https://tutorlink-km9e.onrender.com)**

> Note: the free Render tier may spin down after inactivity, so the first load can take a few seconds while the instance wakes up.

---

## Getting Started

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & npm
- PostgreSQL (or another Laravel-supported database)

### Installation

```bash
# Clone the repository
git clone https://github.com/your-org/tutorlink.git
cd tutorlink

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Set up your environment file
cp .env.example .env
php artisan key:generate
```

Update `.env` with your PostgreSQL credentials (`DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), then run the migrations:

```bash
php artisan migrate --seed
```

### Running Locally

```bash
# Terminal 1 — compile and watch frontend assets
npm run dev

# Terminal 2 — serve the application
php artisan serve
```

For a production build:

```bash
npm run build
```

---

## Project Structure

A partial view of the layout, focused on the areas most relevant to day-to-day development:

```
resources/
├── views/
│   ├── Layouts/
│   │   ├── Layout.blade.php       # Shared app shell: nav, notifications, footer
│   │   └── Footer.blade.php
│   ├── Setting/
│   │   └── Setting.blade.php      # Account settings modal (username/password/logout)
│   ├── Auth/
│   │   ├── Login.blade.php
│   │   ├── Student_Register.blade.php
│   │   └── Teacher_Register.blade.php
│   ├── Tutor/
│   │   ├── ProfileSetup.blade.php
│   │   ├── ProfileEdit.blade.php
│   │   ├── Dashboard.blade.php
│   │   └── Profile.blade.php      # Public tutor profile + booking modal
│   ├── Student/
│   │   └── Dashboard.blade.php
│   └── Messages/
│       └── Inbox.blade.php        # Real-time chat, attachments, geolocation
├── css/
└── js/
```

---

## Design System

TutorLink uses a deliberately minimal, Swiss-inspired visual language across every page:

- **Palette** — ink black, off-white paper, and a single confident blue accent; no gradients, no decorative color for its own sake
- **Typography** — Bebas Neue for headlines and labels, Inter for body copy and UI text
- **Shape language** — flat, square edges throughout (no rounded corners), hairline borders instead of drop shadows
- **Iconography** — custom line icons everywhere instead of emoji, for a consistent, professional feel across languages and platforms
- **Layout** — generous whitespace, clear grids, and a recurring half-cropped ring motif as a subtle brand signature

---

## Roadmap

- [ ] Payment integration for lesson fees
- [ ] Video call integration for online sessions
- [ ] Admin moderation dashboard for tutor verification
- [ ] Multi-language support (Amharic / English)
- [ ] Push notifications (mobile)

---

## Contributing

Contributions, issues, and feature requests are welcome. If you'd like to contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/your-feature`)
3. Commit your changes
4. Open a pull request describing what you changed and why

---

## License

This project is licensed under the MIT License — see the `LICENSE` file for details, or update this section if you intend to keep the project closed-source.

---

## Contact

For questions, support, or partnership inquiries: **support@tutorlink.com**

<br/>

<div align="center">
<sub>Built to make finding — and becoming — a tutor as easy as it should have always been.</sub>
</div>
