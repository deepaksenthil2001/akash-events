# Akash Event Management — Production Web Platform & Administrative Portal

A premium, bespoke digital web platform engineered for **Akash Event Management** (Hosur & Bangalore). Designed with Apple-inspired visual restraint, generous whitespace, warm ivory and champagne gold accents, dynamic responsive typography, and an enterprise-grade administrative management portal.

Built entirely with **Native PHP (OOP / MVC-inspired architecture)**, **MySQL (PDO with prepared statements)**, **Vanilla CSS3**, and **Vanilla JavaScript** — strictly zero heavy third-party CSS or JS frameworks (no Tailwind, Bootstrap, React, or jQuery).

---

## 1. Verified Business Identity & Assets

- **Business Name**: Akash Event Management
- **Brand Emblem / Monogram**: MHA
- **Founder & Master Floral Decorator**: P. Manobalan
- **Primary Phone**: `+91 93442 73433`
- **Secondary Phone**: `+91 96599 99856`
- **Enquiries Email**: `akasheventmanagement87@gmail.com` / `akasheventmanagement@gmail.com`
- **Physical Address**: Bhavani Palace, Back side, Gokul Nagar, Hosur, Tamil Nadu 635109
- **Service Regions**: Hosur, Krishnagiri, Bangalore & Surrounding Regions
- **Experience Milestone**: 10+ Years of Craftsmanship & Celebrations (1,200+ Events)
- **Instagram**: [instagram.com/akasheventmanagement](https://www.instagram.com/akasheventmanagement)
- **Google Business Profile**: [share.google/nUP5Exqa3K1doUEfd](https://share.google/nUP5Exqa3K1doUEfd)

---

## 2. Technology Stack & Architectural Standards

- **Backend**: Native PHP 8.x (PSR-4 autoloading, modular OOP design)
- **Database**: MySQL 5.7+ / 8.0+ / MariaDB (PDO driver with prepared statements & transactions)
- **Fallback Engine**: Automatic local SQLite PDO fallback during CLI development when MySQL is offline
- **Styling**: Vanilla CSS3 Custom Properties (tokens system, fluid typography, dark frosted glassmorphism)
- **Frontend Interactivity**: Vanilla JavaScript ES6+ (lightbox, mobile drawer, client-side validation, live image upload previews)
- **Environment Compatibility**: Fully compatible with standard XAMPP, WAMP, LAMP, MAMP, and PHP built-in server (`php -S`)

---

## 3. Directory Layout

```
AKASH EVENT MANAGEMENT/
├── .htaccess                       # Apache rewrite & security protection rules
├── router.php                      # CLI development server router
├── index.php                       # Root entry point
├── about.php                       # Root proxy forwarder
├── services.php                    # Root proxy forwarder
├── service-details.php             # Root proxy forwarder
├── gallery.php                     # Root proxy forwarder
├── contact.php                     # Root proxy forwarder
├── 404.php                         # Root proxy forwarder
├── admin/                          # Administrative Dashboard Portal
│   ├── index.php                   # Entry router / auth gate
│   ├── login.php                   # Secure login view & rate limiting
│   ├── logout.php                  # Secure session destruction
│   ├── dashboard.php               # Executive statistics & activity
│   ├── enquiries/                  # Customer enquiries manager
│   │   ├── index.php               # Enquiries table with status filter
│   │   └── view.php                # Enquiry inspector, notes & status updater
│   ├── gallery/                    # Portfolio & Media Manager
│   │   ├── index.php               # Media listing with badge indicators
│   │   ├── upload.php              # Multi-attribute media uploader
│   │   └── edit.php                # Media editor & delete actions
│   ├── services/                   # Event Services Manager
│   │   ├── index.php               # Services catalog manager
│   │   ├── create.php              # Create new service with cover/banner
│   │   └── edit.php                # Edit service description & checklist
│   └── settings/                   # System & Identity Configuration
│       ├── index.php               # General business info & SEO
│       ├── hero.php                # Art-directed hero (16:9 desktop & 9:16 mobile)
│       └── about.php               # Founder narrative & philosophy
├── app/                            # Application Core & Models
│   ├── bootstrap.php               # PSR-4 autoloader, session init & global helpers
│   ├── Config/
│   │   ├── config.php              # Environment and database configuration
│   │   └── config.example.php      # Sample configuration template
│   ├── Core/
│   │   ├── Database.php            # PDO singleton with MySQL & SQLite support
│   │   ├── Auth.php                # Bcrypt password verification & session guards
│   │   ├── Csrf.php                # Cryptographic token generation & validation
│   │   ├── Validator.php           # Sanitization & field validation rules
│   │   └── Session.php             # Hardened HTTP-only session manager
│   ├── Models/
│   │   ├── Admin.php               # Admin credentials & security
│   │   ├── Service.php             # Services CRUD & query helpers
│   │   ├── Gallery.php             # Media & category management
│   │   ├── Enquiry.php             # Customer lead tracking & admin notes
│   │   └── Setting.php             # Key-value site settings repository
│   ├── Helpers/
│   │   ├── Sanitizer.php           # String, slug, phone, and XSS sanitizers
│   │   ├── View.php                # Dynamic URL, asset paths & template helpers
│   │   └── Uploader.php            # Secure MIME inspection, sizing & GD thumbnailing
│   └── Views/
│       └── layouts/                # Shared header, footer, and admin chrome
│           ├── header.php          # Public site navigation & metadata
│           ├── footer.php          # Public site footer & quick links
│           ├── admin_header.php    # Admin sidebar & topbar navigation
│           └── admin_footer.php    # Admin footer & script imports
├── database/
│   └── akash_event_management.sql  # Production MySQL schema & seed data
├── public/                         # Public Customer Website
│   ├── index.php                   # Homepage (Hero, metrics, services, showcase)
│   ├── about.php                   # Founder story, philosophy, and history
│   ├── services.php                # Complete services catalog
│   ├── service-details.php         # Dedicated service view with features
│   ├── gallery.php                 # Media portfolio with category filters & lightbox
│   ├── contact.php                 # Contact details, map links & enquiry form
│   ├── 404.php                     # Custom error landing page
│   ├── robots.txt                  # Search engine crawler directives
│   ├── sitemap.xml                 # XML sitemap for SEO indexation
│   ├── assets/                     # Frontend Assets
│   │   ├── css/                    # Modular CSS architecture
│   │   │   ├── tokens.css          # Design tokens (colors, typography, spacing)
│   │   │   ├── reset.css           # Modern box-sizing reset
│   │   │   ├── global.css          # Base typography & layout foundations
│   │   │   ├── components.css      # Reusable buttons, cards, forms, badges
│   │   │   ├── responsive.css      # Media query breakpoints (320px to 4K)
│   │   │   ├── pages/              # Page-specific stylesheets
│   │   │   └── admin/              # Admin dashboard styling
│   │   ├── js/                     # Vanilla JS interactive modules
│   │   │   ├── main.js             # Scroll observer & animations
│   │   │   ├── navigation.js       # Mobile drawer & sticky header
│   │   │   ├── gallery.js          # Filter tabs & lightbox dialog
│   │   │   ├── forms.js            # Client validation & anti-double-submit
│   │   │   └── admin/              # Live previews & confirmation modals
│   │   └── images/                 # Optimized authentic client & brand photos
│   └── uploads/                    # User uploaded images & media
├── scripts/                        # Database management CLI scripts
│   ├── create_admin.php            # Provision admin account
│   └── seed_data.php               # Seed default settings, services & media
├── storage/                        # Persistent storage (logs, uploads, sqlite fallback)
└── tests/                          # Automated test suites
    ├── run_tests.php               # Model & database integrity suite
    ├── test_render_pages.php       # Clean page rendering test
    ├── test_http.php               # HTTP endpoint response test
    ├── run_e2e_qa.php              # Comprehensive automated E2E QA suite
    └── manual-test-checklist.md    # Manual QA verification checklist
```

---

## 4. Installation & Setup Guide

### Option A: Local Apache / XAMPP Installation

1. **Move Project to XAMPP**:
   - Copy or clone the `AKASH EVENT MANAGEMENT` directory into your `C:\xampp\htdocs\` folder:
     ```
     C:\xampp\htdocs\AKASH EVENT MANAGEMENT\
     ```

2. **Start Apache & MySQL**:
   - Open the **XAMPP Control Panel**.
   - Click **Start** for **Apache** and **MySQL**.

3. **Import MySQL Database via phpMyAdmin**:
   - Navigate to `http://localhost/phpmyadmin/` in your web browser.
   - Click **New** in the left sidebar and enter Database name: `akash_event_management`. Set Collation to `utf8mb4_unicode_ci` and click **Create**.
   - Select the newly created `akash_event_management` database.
   - Click the **Import** tab in the top navigation bar.
   - Click **Choose File** and select:
     `C:\xampp\htdocs\AKASH EVENT MANAGEMENT\database\akash_event_management.sql`
   - Scroll to the bottom and click **Import**. All 7 tables and initial seeds will be created instantly.

4. **Access the Website**:
   - **Public Website**: `http://localhost/AKASH%20EVENT%20MANAGEMENT/`
   - **Admin Portal**: `http://localhost/AKASH%20EVENT%20MANAGEMENT/admin/login.php`

---

### Option B: PHP Built-in Web Server (CLI Quick Start)

If you wish to test or run the application immediately without configuring Apache:

1. Open PowerShell or Terminal in the project root:
   ```powershell
   cd "C:\Users\ADMIN\Desktop\AKASH EVENT MANAGEMENT"
   ```

2. Launch the server using the bundled router:
   ```powershell
   php -S 127.0.0.1:8000 router.php
   ```

3. Open your browser:
   - **Public Website**: `http://127.0.0.1:8000/`
   - **Admin Portal**: `http://127.0.0.1:8000/admin/login.php`

*(Note: The application will automatically connect to your local MySQL server if running, or seamlessly use the pre-seeded SQLite database in `storage/database.sqlite` during standalone testing).*

---

## 5. Administrative Credentials

- **Admin Login URL**: `/admin/login.php`
- **Username**: `admin`
- **Password**: `Admin@Akash2026!`

*(You can update your credentials or email anytime inside `/admin/settings/index.php` or via CLI with `php scripts/create_admin.php`).*

---

## 6. Running Automated Tests

A comprehensive suite of test runners is provided:

```powershell
# 1. Run database and core models test
php tests/run_tests.php

# 2. Run clean HTML rendering test
php tests/test_render_pages.php

# 3. Run full automated E2E QA test (requires server running on port 8000)
php tests/run_e2e_qa.php
```

---

## 7. Key Features & Implementation Details

1. **Art-Directed Dual Hero (Desktop & Mobile)**:
   - Uses HTML5 `<picture>` element with `<source media="(max-width: 768px)" srcset="...">` to display a dedicated portrait 9:16 hero on smartphones and a cinematic 16:9 hero on desktop screens.
2. **Authentic Photography Showcase**:
   - Utilizes genuine client event photographs from P. Manobalan's actual portfolio (mandapams, stage floral arches, seemantham, 1st birthday setups).
   - Clear distinction between "Authentic Work" badges and "Editorial Concept" badges.
3. **Interactive Filterable Gallery**:
   - Tabbed category pills (*Weddings & Receptions*, *Traditional Ceremonies*, *Birthdays*, etc.) filter client cards with zero page reloads.
   - Accessible keyboard-friendly lightbox modal dialog with backdrop blur, full captions, and ESC key listener.
4. **End-to-End Customer Enquiry Pipeline**:
   - CSRF-protected contact form with real-time validation, rate limiting, and instant recording into the database.
   - Admin enquiries dashboard with status tracking (*New*, *Read*, *Contacted*, *Completed*), direct WhatsApp messaging links, direct phone calling links, and internal notes.
5. **Dynamic Administrative CMS**:
   - Complete CRUD management for Event Services, Gallery Media, Site Identity Settings, SEO Metadata, and Hero art direction.
   - Live drag-and-drop / file input image preview with automatic GD thumbnail generation.

---

## 8. License & Commercial Rights

Designed and developed for **Akash Event Management** (Hosur, Tamil Nadu). All rights reserved.
