<?php
use App\Helpers\View;

$siteName = View::setting('business_name', 'Akash Event Management');
$phonePrimary = View::setting('phone_primary', '+91 93442 73433');
$phoneSecondary = View::setting('phone_secondary', '+91 96599 99856');
$whatsapp = View::setting('whatsapp_number', '919344273433');

$pageTitle = $pageTitle ?? View::setting('meta_title_default', 'Akash Event Management | Stage Decoration & Event Planning');
$pageDescription = $pageDescription ?? View::setting('meta_description_default', 'Akash Event Management specializes in luxury wedding stages, floral decoration, reception mandapams, baby showers, and birthday setups in Hosur.');
$currentNav = $currentNav ?? '';
$pageCss = $pageCss ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= View::e($pageTitle) ?></title>
  <meta name="description" content="<?= View::e($pageDescription) ?>">
  <meta name="theme-color" content="#FBF9F5">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Akash Events">

  <!-- Favicon & Touch Icons -->
  <link rel="icon" type="image/png" href="<?= View::asset('assets/images/brand/favicon.png') ?>">
  <link rel="apple-touch-icon" href="<?= View::asset('assets/images/brand/favicon.png') ?>">

  <!-- Open Graph / Social Sharing -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= View::e($pageTitle) ?>">
  <meta property="og:description" content="<?= View::e($pageDescription) ?>">
  <meta property="og:image" content="<?= View::asset('assets/images/hero/hero-desktop.jpg') ?>">

  <!-- Google Fonts: Plus Jakarta Sans (Apple-inspired clarity) & Cinzel (Subtle Royal Gold Accents) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Core Stylesheets -->
  <link rel="stylesheet" href="<?= View::asset('assets/css/tokens.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/reset.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/global.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/components.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/responsive.css') ?>">

  <?php foreach ($pageCss as $css): ?>
    <link rel="stylesheet" href="<?= View::asset($css) ?>">
  <?php endforeach; ?>
</head>
<body>

  <!-- Premium Frosted Header -->
  <header class="site-header" id="site-header">
    <div class="site-container site-header-inner">
      
      <!-- Brand Logo -->
      <a href="<?= View::url('/index.php') ?>" class="brand-link" aria-label="Akash Event Management Home">
        <img src="<?= View::asset('assets/images/brand/logo-header.png') ?>" alt="Akash Event Management MHA Emblem" class="brand-emblem" width="65" height="64">
        <div class="brand-meta">
          <span class="brand-name">Akash Event Management</span>
          <span class="brand-subtitle">Floral Artistry &bull; 10+ Yrs</span>
        </div>
      </a>

      <!-- Desktop Primary Navigation -->
      <nav class="desktop-nav" aria-label="Primary Navigation">
        <a href="<?= View::url('/index.php') ?>" class="nav-link <?= View::active($currentNav, 'home') ?>">Home</a>
        <a href="<?= View::url('/about.php') ?>" class="nav-link <?= View::active($currentNav, 'about') ?>">About Us</a>
        <a href="<?= View::url('/services.php') ?>" class="nav-link <?= View::active($currentNav, 'services') ?>">Services</a>
        <a href="<?= View::url('/gallery.php') ?>" class="nav-link <?= View::active($currentNav, 'gallery') ?>">Gallery</a>
        <a href="<?= View::url('/contact.php') ?>" class="nav-link <?= View::active($currentNav, 'contact') ?>">Contact</a>
      </nav>

      <!-- Header Quick Actions -->
      <div class="header-actions">
        <a href="tel:<?= preg_replace('/[^\d+]/', '', $phonePrimary) ?>" class="btn btn-secondary btn-sm" aria-label="Call Akash Event Management">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
          <span style="display: inline-block;"><?= View::e($phonePrimary) ?></span>
        </a>

        <a href="<?= View::url('/contact.php') ?>" class="btn btn-primary btn-sm">Enquire Now</a>

        <!-- Mobile Menu Hamburger Trigger -->
        <button type="button" class="mobile-menu-btn" aria-label="Toggle navigation menu" aria-expanded="false">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
      </div>

    </div>
  </header>

  <!-- Mobile Drawer -->
  <div class="mobile-drawer" id="mobile-drawer" role="dialog" aria-modal="true" aria-label="Mobile Menu">
    <div class="mobile-nav-links">
      <a href="<?= View::url('/index.php') ?>" class="mobile-nav-link <?= View::active($currentNav, 'home') ?>">Home</a>
      <a href="<?= View::url('/about.php') ?>" class="mobile-nav-link <?= View::active($currentNav, 'about') ?>">About Us</a>
      <a href="<?= View::url('/services.php') ?>" class="mobile-nav-link <?= View::active($currentNav, 'services') ?>">Services</a>
      <a href="<?= View::url('/gallery.php') ?>" class="mobile-nav-link <?= View::active($currentNav, 'gallery') ?>">Gallery</a>
      <a href="<?= View::url('/contact.php') ?>" class="mobile-nav-link <?= View::active($currentNav, 'contact') ?>">Contact</a>
    </div>

    <div style="margin-top: auto; display: flex; flex-direction: column; gap: 0.75rem;">
      <a href="tel:<?= preg_replace('/[^\d+]/', '', $phonePrimary) ?>" class="btn btn-secondary" style="width: 100%;">
        Call: <?= View::e($phonePrimary) ?>
      </a>
      <a href="https://wa.me/<?= $whatsapp ?>?text=<?= urlencode('Hello Akash Event Management, I would like to enquire about event decoration services.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp" style="width: 100%;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.79 14.1c-.24.68-1.2 1.28-1.83 1.34-.48.05-1.1.07-3.17-.79-2.65-1.1-4.36-3.79-4.49-3.96-.13-.18-1.07-1.42-1.07-2.72 0-1.29.67-1.93.92-2.19.24-.26.54-.33.72-.33.18 0 .36 0 .52.01.17.01.39-.06.61.47.23.55.78 1.9.85 2.04.07.15.11.32.02.5-.09.18-.14.29-.28.45-.14.16-.3.35-.43.48-.14.15-.29.31-.13.58.17.28.74 1.22 1.6 1.98 1.1.98 2.03 1.28 2.31 1.43.29.14.45.12.62-.07.17-.2.72-.84.91-1.13.2-.29.39-.24.66-.14.27.1 1.72.81 2.02.96.29.15.49.22.56.34.07.13.07.72-.17 1.4z"/></svg>
        Chat on WhatsApp
      </a>
    </div>
  </div>
