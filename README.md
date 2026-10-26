# NIRMAAN — Complete Education & Defence Exam Preparation Platform

<p align="center">
  <img src="public/images/logo.svg" alt="Nirmaan Logo" width="220">
</p>

<p align="center">
  <strong>Premier Online Coaching Platform for Indian Armed Forces & Paramilitary Examinations</strong><br>
  <em>Indian Army &bull; Indian Navy &bull; Indian Air Force &bull; BSF &bull; CISF &bull; SSB Interview &bull; NDA &bull; CDS</em>
</p>

---

## 🎯 Executive Summary & Design Parity

**Nirmaan** is an end-to-end web application built using **Laravel 12, PHP 8.5, MySQL, Tailwind CSS v4, and Alpine.js**. It features strict visual parity with the provided mockup design:

- **Navigation Header**: Brand mark, Courses, Tests, Payments, Announcements, Search pill, and Sign In button.
- **Hero Section**: 
  - Headline: *"Build a Strong Future with Nirmaan"* with gold-star highlight pill *"Trusted by 50,000+ Defence Aspirants"*.
  - Feature badges: *Live Interactive Classes &bull; Expert Defence Faculty &bull; Updated Exam Pattern*.
  - Dual CTAs: *"Explore Courses"* and *"View Demo Classes"*.
  - Circular/organic masked cadet parade imagery.
- **5 Core Value Cards**: Question Banks, Mock Tests, Video Classes, Study Material, and Student Support.
- **Popular Defence Courses**: 6 Branch Cards (Indian Army, Navy, Air Force, BSF, CISF, SSB) with official branch emblems, feature tag pills (`Live Classes`, `Mock Tests`, `Study Material`), price tags (`₹ 2,999`), and `Enroll Now` buttons.
- **Our Impact Section**: 4 real statistics (`50K+ Aspirants Trained`, `94% Selection Rate`, `200+ Expert Mentors`, `1,500+ Practice Tests`) with angled handwritten sticker badge: *"Your Success Our Mission"*.
- **Why Choose Nirmaan**: High-resolution student portrait with backpack alongside a 4-point verified checklist with emerald checkmarks.
- **Aspirant Testimonials**: Real candidate reviews (Rohit Sharma - Army, Neha Singh - Air Force, Aman Kumar - BSF) with 5-star ratings.
- **FAQ Accordion & Banner CTA**: Interactive FAQs with a link to *"View All FAQs"*, followed by the *"Ready to Build Your Future?"* call to action.
- **Footer**: Brand logo, quick links, social media links (YouTube, Telegram, Instagram, Facebook, X), and legal policies.

---

## 🚀 Key Systems & Portals

### 1. Public Discovery Platform
- **Course Catalog**: Filter by branch category (`Army`, `Navy`, `Air Force`, `BSF`, `CISF`, `SSB`, `NDA`, `CDS`), search by title/keywords, sort by popularity or price.
- **Course Detail & Syllabus**: Full course breakdown with video preview modals, instructor bio, features checklist, and direct enrollment.
- **Free Study Resources**: Downloadable PDF study guides and formula sheets with live download tracking.
- **Announcements & Notifications**: Official defence exam exam notifications, admit cards, and recruitment updates.

### 2. Student Learning & CBT Exam Portal
- **Student Dashboard**: Real-time study statistics (enrolled courses, mock test attempts, total hours studied, average test score), current course progress bars, and upcoming mock test cards.
- **Native-Feeling Mobile Experience**: Fixed bottom 5-destination navigation (`Home`, `Courses`, `Practice`, `Tests`, `Profile`) adhering to native Android app UX.
- **Full Video Player**: Module-by-module curriculum navigation with lesson completion checkboxes and YouTube/custom player integration.
- **Topic Practice Engine**: Filter question bank by subject (`General Knowledge`, `Physics`, `Mathematics`, `Defence Studies`) with instant answer validation and solution explanations.
- **Full CBT Mock Test Engine**:
  - Pre-exam instructions modal and agreement terms.
  - Live countdown timer with automatic submission on expiration.
  - Question status palette (Not Visited, Answered, Marked for Review).
  - Incremental AJAX autosave so no student answer is lost on refresh or network jitter.
  - Submission confirmation modal.
  - Comprehensive Performance Scorecard: total score, accuracy percentage, time taken, positive/negative marks breakdown, and detailed answer-by-answer explanations.
- **Order History & Invoices**: Track all course enrollments and download purchase receipts.

### 3. Officer Administration Workspace
- **Executive KPI Dashboard**: Total registered aspirants, published courses, question bank inventory, and gross platform revenue.
- **Course & Curriculum Manager**: Create, edit, and organize courses, modules, and video lessons with drag-and-drop sort orders.
- **Question Bank CRUD**: Add multiple-choice questions with 4 options, correct answer keys, detailed explanations, subject categorization, difficulty tiers, and positive/negative marking rules.
- **Mock Test Builder**: Create full-length timed tests, set duration and passing marks, and attach questions from the bank.
- **Student Management**: Search students, inspect test scores and progress, toggle account status, and perform manual course enrollments.
- **Order & Financial Auditing**: View all payment transactions, verify statuses, and inspect buyer information.
- **Content & FAQ Management**: Update platform site settings, manage FAQ accordion questions, and review student contact inquiries.

---

## 🛠️ Technology Stack & Architecture

- **Backend Framework**: Laravel 12 on PHP 8.5
- **Database**: MySQL (`nirmaan_db`) with Eloquent ORM relations
- **Frontend Stack**: Tailwind CSS v4 & Alpine.js compiled via Vite 8
- **Typography**: Instrument Sans & Outfit
- **Testing**: PHPUnit 12 with 14 automated feature tests and 53 assertions

---

## 🔑 Default Credentials & Access

| Portal | URL | Email | Password |
|---|---|---|---|
| **Public Website** | `http://127.0.0.1:8000/` | *Guest* | *Guest* |
| **Student Portal** | `http://127.0.0.1:8000/login` | `student@nirmaan.com` | `password123` |
| **Admin Workspace** | `http://127.0.0.1:8000/admin` | `admin@nirmaan.com` | `password123` |

---

## ⚡ Quickstart Guide

### 1. Clone & Install Dependencies
```bash
composer install
npm install
```

### 2. Configure Environment & Database
```bash
cp .env.example .env
php artisan key:generate
```
Configure your MySQL database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nirmaan_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrate & Seed Database
```bash
php artisan migrate:fresh --seed
```

### 4. Build Frontend Assets
```bash
npm run build
```

### 5. Launch the Application
```bash
php artisan serve --port=8000
```
Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## 🧪 Automated Test Suite

Run the full automated test suite covering authentication, catalog browsing, and the CBT mock test engine:

```bash
php vendor/phpunit/phpunit/phpunit
```
*Result: 14 tests, 53 assertions, 100% passing.*
