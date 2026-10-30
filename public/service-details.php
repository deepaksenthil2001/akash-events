<?php
/**
 * Akash Event Management - Service Details Page
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Service;

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) {
    header("Location: " . View::url('/services.php'));
    exit;
}

$service = Service::getBySlug($slug);

// If service not found or not published
if (!$service || empty($service['is_published'])) {
    http_response_code(404);
    require_once __DIR__ . '/404.php';
    exit;
}

$pageTitle = !empty($service['meta_title']) ? $service['meta_title'] : $service['title'] . ' | Akash Event Management';
$pageDescription = !empty($service['meta_description']) ? $service['meta_description'] : $service['short_description'];
$currentNav = 'services';
$pageCss = ['assets/css/pages/services.css'];

$phonePrimary = View::setting('phone_primary', '+91 93442 73433');
$whatsapp = View::setting('whatsapp_number', '919344273433');

$bannerImg = !empty($service['banner_image']) ? $service['banner_image'] : $service['cover_image'];

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <div class="site-container">

    <!-- Breadcrumb -->
    <div style="padding-top: var(--space-2xl); font-size: var(--font-size-xs); color: var(--color-text-tertiary);">
      <a href="<?= View::url('/index.php') ?>" style="color: var(--color-text-secondary);">Home</a> &nbsp;/&nbsp;
      <a href="<?= View::url('/services.php') ?>" style="color: var(--color-text-secondary);">Services</a> &nbsp;/&nbsp;
      <span><?= View::e($service['title']) ?></span>
    </div>

    <!-- Page Header -->
    <header class="service-detail-header">
      <span class="section-eyebrow">Service Overview</span>
      <h1 class="section-title" style="margin-bottom: var(--space-sm);"><?= View::e($service['title']) ?></h1>
      <p style="font-size: var(--font-size-lg); color: var(--color-text-secondary); max-width: 800px;">
        <?= View::e($service['short_description']) ?>
      </p>
    </header>

    <!-- Visual Banner -->
    <div class="service-detail-banner">
      <img src="<?= View::asset($bannerImg) ?>" alt="<?= View::e($service['title']) ?>" fetchpriority="high">
    </div>

    <!-- Content & Action Sidebar Grid -->
    <div class="service-detail-layout">
      
      <!-- Detailed Narrative -->
      <div class="service-content-body">
        <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--space-md); color: var(--color-text-primary);">
          Craftsmanship, Material Selection & Execution
        </h2>
        
        <?= nl2br(View::e($service['full_description'])) ?>

        <?php if (!empty($service['features_list'])): ?>
          <div style="margin-top: var(--space-3xl); padding: var(--space-xl); background-color: var(--color-surface); border-radius: var(--radius-xl); border: 1px solid var(--color-border);">
            <h3 style="font-size: var(--font-size-xl); margin-bottom: var(--space-md);">What is Included in This Service:</h3>
            <ul style="display: flex; flex-direction: column; gap: 0.75rem;">
              <?php 
              $features = array_filter(array_map('trim', explode("\n", $service['features_list'])));
              foreach ($features as $f): 
              ?>
                <li style="display: flex; align-items: flex-start; gap: 0.75rem; font-size: var(--font-size-sm); color: var(--color-text-secondary);">
                  <span style="color: var(--color-gold); font-weight: bold; flex-shrink: 0;">&#10003;</span>
                  <span><?= View::e($f) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sticky Enquiry Sidebar -->
      <aside>
        <div class="service-sidebar-card">
          <h3 style="font-size: var(--font-size-xl); margin-bottom: 0.5rem;">Book This Service</h3>
          <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); margin-bottom: 1.5rem;">
            Discuss dates, venue layout, and customization with our lead event team.
          </p>

          <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <a href="<?= View::url('/contact.php?service=' . urlencode($service['title'])) ?>" class="btn btn-primary" style="width: 100%;">
              Send Booking Enquiry
            </a>
            <a href="https://wa.me/<?= $whatsapp ?>?text=<?= urlencode('Hello P. Manobalan, I am interested in booking ' . $service['title'] . '.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp" style="width: 100%;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.79 14.1c-.24.68-1.2 1.28-1.83 1.34-.48.05-1.1.07-3.17-.79-2.65-1.1-4.36-3.79-4.49-3.96-.13-.18-1.07-1.42-1.07-2.72 0-1.29.67-1.93.92-2.19.24-.26.54-.33.72-.33.18 0 .36 0 .52.01.17.01.39-.06.61.47.23.55.78 1.9.85 2.04.07.15.11.32.02.5-.09.18-.14.29-.28.45-.14.16-.3.35-.43.48-.14.15-.29.31-.13.58.17.28.74 1.22 1.6 1.98 1.1.98 2.03 1.28 2.31 1.43.29.14.45.12.62-.07.17-.2.72-.84.91-1.13.2-.29.39-.24.66-.14.27.1 1.72.81 2.02.96.29.15.49.22.56.34.07.13.07.72-.17 1.4z"/></svg>
              Inquire via WhatsApp
            </a>
            <a href="tel:<?= preg_replace('/[^\d+]/', '', $phonePrimary) ?>" class="btn btn-secondary" style="width: 100%;">
              Call <?= View::e($phonePrimary) ?>
            </a>
          </div>

          <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border); font-size: var(--font-size-xs); color: var(--color-text-tertiary);">
            <strong>Service Locations:</strong><br>
            Available across Hosur, Krishnagiri, Bangalore urban/rural, and surrounding areas.
          </div>
        </div>
      </aside>

    </div>

  </div>

<?php require_once APP_ROOT . '/app/Views/layouts/footer.php'; ?>
