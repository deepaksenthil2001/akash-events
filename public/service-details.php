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
