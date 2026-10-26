<?php
use App\Helpers\View;
use App\Core\Auth;
use App\Models\Enquiry;

Auth::requireLogin();

$user = Auth::user();
$newEnquiriesCount = Enquiry::count('new');
$pageTitle = $pageTitle ?? 'Admin Dashboard | Akash Event Management';
$currentAdminNav = $currentAdminNav ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= View::e($pageTitle) ?></title>
  
  <link rel="stylesheet" href="<?= View::asset('assets/css/tokens.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/admin/admin.css') ?>">
</head>
<body class="admin-body">

  <div class="admin-wrapper">
    
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <div class="admin-sidebar-brand">
        <img src="<?= View::asset('assets/images/brand/logo-admin.png') ?>" alt="Akash Event Management" width="64" height="36" style="object-fit: contain; filter: drop-shadow(0 2px 6px rgba(197, 160, 89, 0.3));">
        <div>
          <div class="admin-brand-title">Akash Events</div>
          <div class="admin-brand-badge">Control Center</div>
        </div>
      </div>

      <nav class="admin-sidebar-nav">
        <a href="<?= View::url('/admin/dashboard.php') ?>" class="admin-nav-item <?= $currentAdminNav === 'dashboard' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7"></rect>
            <rect x="14" y="3" width="7" height="7"></rect>
            <rect x="14" y="14" width="7" height="7"></rect>
            <rect x="3" y="14" width="7" height="7"></rect>
          </svg>
          Dashboard
        </a>

        <a href="<?= View::url('/admin/services/index.php') ?>" class="admin-nav-item <?= $currentAdminNav === 'services' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
          </svg>
          Services
        </a>

        <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-nav-item <?= $currentAdminNav === 'gallery' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
          </svg>
          Gallery Media
        </a>

        <a href="<?= View::url('/admin/enquiries/index.php') ?>" class="admin-nav-item <?= $currentAdminNav === 'enquiries' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          Enquiries
          <?php if ($newEnquiriesCount > 0): ?>
            <span class="admin-nav-badge"><?= $newEnquiriesCount ?></span>
          <?php endif; ?>
        </a>

        <a href="<?= View::url('/admin/settings/hero.php') ?>" class="admin-nav-item <?= $currentAdminNav === 'hero' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
            <line x1="8" y1="21" x2="16" y2="21"></line>
            <line x1="12" y1="17" x2="12" y2="21"></line>
          </svg>
          Hero Management
        </a>

        <a href="<?= View::url('/admin/settings/about.php') ?>" class="admin-nav-item <?= $currentAdminNav === 'about' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
          </svg>
          About Content
        </a>

        <a href="<?= View::url('/admin/settings/index.php') ?>" class="admin-nav-item <?= $currentAdminNav === 'settings' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
          Business Settings
        </a>
      </nav>

      <div class="admin-sidebar-footer">
        <div style="font-size: 0.85rem; font-weight: 600; color: #FFFFFF; margin-bottom: 2px;">
          <?= View::e($user['full_name'] ?? 'P. Manobalan') ?>
        </div>
        <div style="font-size: 0.72rem; color: #718096; margin-bottom: 0.75rem;">
          Logged in as <?= View::e($user['username'] ?? 'admin') ?>
        </div>
        <a href="<?= View::url('/admin/logout.php') ?>" class="admin-btn admin-btn-danger admin-btn-sm" style="width: 100%;">
          Logout
        </a>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main">
      
      <!-- Top Header Bar -->
      <header class="admin-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
          <button type="button" class="admin-sidebar-toggle" style="display: none; background: transparent; border: none; cursor: pointer;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
          <h1 class="admin-header-title"><?= View::e($pageHeaderTitle ?? 'Dashboard') ?></h1>
        </div>

        <div class="admin-header-actions">
          <a href="<?= View::url('/index.php') ?>" target="_blank" rel="noopener noreferrer" class="admin-btn admin-btn-secondary admin-btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
              <polyline points="15 3 21 3 21 9"></polyline>
              <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            View Live Website
          </a>
        </div>
      </header>

      <div class="admin-content">
        <?php require APP_ROOT . '/app/Views/components/flash_messages.php'; ?>
