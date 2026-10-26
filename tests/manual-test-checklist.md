# Manual Quality Assurance & Acceptance Test Checklist
### Akash Event Management Web Platform

Use this checklist to perform final verification of the website on your local development machine or production hosting environment.

---

## 1. Public Website Verification

### Homepage (`/index.php` or `/`)
- [ ] **Desktop Hero (16:9)**: Verify the cinematic hero banner displays with warm ivory typography, subtle gradient overlay, and clear buttons.
- [ ] **Mobile Hero (9:16)**: Inspect in mobile viewport (375px - 430px) and verify the picture element serves the portrait image without stretching or overflow.
- [ ] **Metrics Section**: Confirm 10+ Years Experience, 1,200+ Celebrations, and Master Floral Decorator cards display with refined champagne gold borders.
- [ ] **Services Carousel / Grid**: Verify all 8 primary service cards display high-resolution imagery, short descriptions, and 'Explore Service' links.
- [ ] **Craftsmanship Section**: Read through the P. Manobalan story and philosophy card. Check that the visiting card or emblem preview renders cleanly.
- [ ] **Client Gallery Showcase**: Verify authentic client images (reception arches, seemantham, 1st birthday) render with clear 'Authentic Work' badges.
- [ ] **Sticky Frosted Header**: Scroll down the page and verify the header applies a subtle background blur and shrinks gracefully.
- [ ] **Mobile Navigation Drawer**: Tap the mobile hamburger button; verify the drawer slides smoothly from the right, links are easily tappable, and pressing ESC or outside closes the drawer.
- [ ] **Footer**: Confirm all quick links, phone numbers (`+91 93442 73433`, `+91 96599 99856`), Hosur address, and Instagram links are accurate.

### About Us (`/about.php`)
- [ ] **Lead Narrative**: Verify founder P. Manobalan's background and 10+ years of local Hosur & Bangalore experience are prominently featured.
- [ ] **Core Philosophy Card**: Check the 4 commitment pillars (Art-Directed Floral Craft, Structural Integrity & Safety, Punctual Execution, Transparent Pricing).
- [ ] **Celebration Spectrum**: Verify grid showcasing weddings, engagements, traditional rituals, and milestone birthdays.

### Services Catalog & Service Details (`/services.php` & `/service-details.php`)
- [ ] **Service Catalog Grid**: Verify all 8 services appear with custom cover images.
- [ ] **Individual Detail Page**: Click into 'Wedding & Reception Stage Decoration' (`/service-details.php?slug=wedding-reception-decoration`).
- [ ] **Features Checklist**: Check that the bulleted list of inclusions (floral backdrops, royal sofa, ambient lighting, on-site setup) displays clearly.
- [ ] **Sidebar Booking Card**: Confirm the sidebar has direct Call, WhatsApp, and Enquiry links.

### Event Gallery & Interactive Lightbox (`/gallery.php`)
- [ ] **Category Filter Pills**: Click on 'Weddings & Receptions', 'Traditional Ceremonies', 'Birthdays & Milestones'. Confirm the grid filters instantly without page reload.
- [ ] **Interactive Lightbox Modal**: Click on any gallery card.
  - [ ] Lightbox opens with full-screen dark backdrop blur.
  - [ ] Image scales to fit screen proportionally.
  - [ ] Title and caption display underneath.
  - [ ] Close button ('×') works.
  - [ ] Keyboard navigation: ESC closes lightbox, Arrow Left / Arrow Right navigates between items.

### Contact & Enquiry Form (`/contact.php`)
- [ ] **Business Contacts**: Confirm Primary Phone, Secondary Phone, Email, and Address display correctly.
- [ ] **Interactive Links**:
  - [ ] Clicking the phone number triggers a `tel:` link.
  - [ ] Clicking 'Chat on WhatsApp' opens WhatsApp Web / mobile app with pre-filled enquiry text.
  - [ ] Clicking Google Business Profile link opens Google Maps.
- [ ] **Enquiry Form Validation**:
  - [ ] Attempt submitting an empty form; confirm browser and custom validation alerts show required fields.
  - [ ] Enter a valid submission:
    - Customer Name: *Test Customer*
    - Phone: *9876543210*
    - Email: *test@example.com*
    - Event Type: *Wedding Stage Decoration*
    - Event Date: *2026-12-20*
    - Guest Count: *300*
    - Message: *Test enquiry from manual checklist*
  - [ ] Submit form; verify page reloads with a green confirmation banner: *"Thank you! Your enquiry has been received..."*
  - [ ] Confirm double-submission is blocked (button disables upon click).

---

## 2. Administrative Portal Verification

### Authentication (`/admin/login.php`)
- [ ] **Unauthenticated Access**: Try visiting `/admin/dashboard.php` directly without logging in. Verify you are automatically redirected to `/admin/login.php`.
- [ ] **Invalid Login**: Try logging in with wrong password. Verify an alert displays *"Invalid username or password."*
- [ ] **Valid Login**: Enter `admin` / `Admin@Akash2026!`. Verify smooth redirect to `/admin/dashboard.php`.

### Dashboard Overview (`/admin/dashboard.php`)
- [ ] **Metrics Cards**: Verify active services, gallery items, total enquiries, and new enquiries counters reflect the database.
- [ ] **Recent Enquiries Widget**: Confirm the test enquiry submitted in step 1 appears in the recent table.

### Enquiries Management (`/admin/enquiries/index.php`)
- [ ] **Status Filters**: Test filtering by *All*, *New*, *Read*, *Contacted*, *Completed*.
- [ ] **View Enquiry Details** (`/admin/enquiries/view.php?id=1`):
  - [ ] Verify customer details, event date, guest count, and message are displayed.
  - [ ] Click 'Call Customer' and 'WhatsApp Customer' buttons to verify quick actions.
  - [ ] Change status to 'Contacted', add an internal note, and click 'Update Status & Notes'.
  - [ ] Confirm the update saves and a success banner displays.

### Services Management (`/admin/services/index.php`)
- [ ] **Service Catalog List**: Confirm all services display with cover thumbnails, display order, and active/inactive toggles.
- [ ] **Create New Service** (`/admin/services/create.php`):
  - [ ] Fill title, short description, features list, and choose a cover image.
  - [ ] Confirm live image preview shows the selected file before upload.
  - [ ] Save and confirm the new service appears in the list.
- [ ] **Edit Service** (`/admin/services/edit.php`):
  - [ ] Update title or display order.
  - [ ] Toggle 'Publish Status' and confirm changes reflect on the public `/services.php` page.

### Gallery Management (`/admin/gallery/index.php`)
- [ ] **Media Catalog**: Verify all photos and videos are listed with category badges and authentic/concept flags.
- [ ] **Upload New Media** (`/admin/gallery/upload.php`):
  - [ ] Select category and enter title.
  - [ ] Select a JPEG/PNG photo.
  - [ ] Check 'Mark as Authentic Client Work'.
  - [ ] Submit and verify the image is uploaded, thumbnail is generated, and item displays in the gallery table and public `/gallery.php` page.
- [ ] **Delete Action**: Test deleting a test media item; verify confirmation dialog appears before removal.

### Settings Management (`/admin/settings/index.php` & `/admin/settings/hero.php`)
- [ ] **General Settings**: Update contact phone number or primary email address. Verify the updated value is immediately rendered in the public header and footer.
- [ ] **Hero Art Direction**:
  - [ ] Check desktop hero headline, subheadline, and button text fields.
  - [ ] Check desktop and mobile image replacement inputs.

### Admin Logout (`/admin/logout.php`)
- [ ] Click 'Sign Out' in the top right header.
- [ ] Verify session is destroyed and browser redirects to `/admin/login.php`.
- [ ] Press the browser's Back button; confirm the protected dashboard is inaccessible.

---

## 3. Responsive & Cross-Browser Verification

- [ ] **Desktop (1920x1080 & 1440x900)**: Clean typography, proper card grids, no horizontal overflow.
- [ ] **Tablet (768px - 1024px, iPad Pro / Air)**: Grids smoothly collapse from 3 columns to 2 columns.
- [ ] **Mobile (375px - 430px, iPhone / Android)**: Single-column flow, touch-friendly buttons (minimum 44px tap targets), fixed bottom WhatsApp action bar on key landing pages.
