<?php
/**
 * Akash Event Management - Services Catalog Page
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Service;

$pageTitle = 'Event Services | Akash Event Management - Stage, Floral & Production';
$pageDescription = 'Explore our verified range of event services: Wedding stages, Mandapams, Seemantham, Naming ceremonies, Birthdays, Fresh flower decoration, and Light production.';
$currentNav = 'services';
$pageCss = ['assets/css/pages/services.css'];

// Fetch only published services from database
$services = Service::getAll(true);

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <div class="site-container">

    <section class="services-page-header">
      <span class="section-eyebrow">Our Disciplines</span>
      <h1 class="section-title">Crafting Unforgettable Experiences</h1>
      <p class="section-description">
        From sacred mandapams to contemporary illuminated stages, explore our full spectrum of event styling and production services across Hosur and Bangalore.
      </p>
    </section>

    <!-- Services Catalog Grid -->
    <div class="services-catalog-grid">
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): ?>
          <article class="card service-catalog-card">
            <div class="card-media">
              <img src="<?= View::asset($service['cover_image']) ?>" alt="<?= View::e($service['title']) ?>" loading="lazy">
            </div>

            <div class="card-body">
              <h2 class="card-title"><?= View::e($service['title']) ?></h2>
              <p class="card-text"><?= View::e($service['short_description']) ?></p>

              <?php if (!empty($service['features_list'])): ?>
                <div class="service-features-list">
                  <?php 
                  $features = array_filter(array_map('trim', explode("\n", $service['features_list'])));
                  foreach (array_slice($features, 0, 3) as $feature): 
                  ?>
                    <div class="service-feature-item">
                      <span class="service-feature-icon">&#10003;</span>
                      <span><?= View::e($feature) ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <div style="display: flex; gap: 0.75rem; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--color-border-subtle);">
                <a href="<?= View::url('/service-details.php?slug=' . urlencode($service['slug'])) ?>" class="btn btn-primary btn-sm" style="flex-grow: 1;">
                  View Details
                </a>
                <a href="<?= View::url('/contact.php?service=' . urlencode($service['title'])) ?>" class="btn btn-secondary btn-sm">
                  Enquire
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem;">
          <p>No published services available at this moment. Please check back shortly.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Call to action -->
    <section class="cta-banner" style="margin-bottom: var(--space-5xl);">
      <h2 class="cta-headline">Have a Custom Event Concept in Mind?</h2>
      <p class="cta-subtext">
        Every love story and milestone is unique. Tell us your vision, colors, and venue, and we will bring it to life.
      </p>
      <div class="cta-buttons">
        <a href="<?= View::url('/contact.php') ?>" class="btn btn-gold btn-lg">Request Custom Consultation</a>
      </div>
    </section>

  </div>

<?php require_once APP_ROOT . '/app/Views/layouts/footer.php'; ?>
