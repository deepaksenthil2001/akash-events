<?php
/**
 * Akash Event Management - Curated Event Gallery & Portfolio
 * Presents authentic client celebrations with multi-photo collections (5-10 photographs per event)
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Gallery;
use App\Models\Event;

$pageTitle = 'Event Gallery & Curated Portfolio | Akash Event Management - Hosur';
$pageDescription = 'Explore authentic celebrations crafted by Akash Event Management across Hosur and Bangalore. Browse multi-photo galleries of royal wedding stages, floral mandapams, baby showers, and birthdays.';
$currentNav = 'gallery';
$pageCss = ['assets/css/pages/gallery.css'];

// Retrieve categories and published events
$categories = Gallery::getCategories(true);
$events = Event::getAll(null, null, true);

// Pre-load photos for each event
$eventsWithPhotos = [];
foreach ($events as $ev) {
    $photos = Event::getPhotos((int)$ev['id'], true);
    $ev['photos'] = $photos;
    $eventsWithPhotos[] = $ev;
}

$activeFilter = trim($_GET['category'] ?? 'all');

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <div class="site-container">

    <!-- Header Section -->
    <section class="gallery-page-header">
      <span class="section-eyebrow">Craftsmanship & Milestones</span>
      <h1 class="section-title"><?= View::e(View::setting('gallery_header_title', 'The Artistry of Every Celebration')) ?></h1>
      <p class="section-description">
        <?= View::e(View::setting('gallery_header_subtitle', 'Explore our curated portfolio of authentic celebrations. Each event showcases a bespoke collection of photographs capturing stage architecture, fresh flower arrangements, and ceremonial ambiance.')) ?>
      </p>
    </section>

    <!-- Filter Category Pills -->
    <div class="gallery-filters" role="tablist" aria-label="Event Categories">
      <button type="button" class="filter-pill <?= $activeFilter === 'all' ? 'active' : '' ?>" data-filter="all" role="tab">
        All Celebrations (<?= count($eventsWithPhotos) ?>)
      </button>

      <?php foreach ($categories as $cat): ?>
        <?php 
          // Count events in this category
          $catEventsCount = count(array_filter($eventsWithPhotos, fn($e) => (int)($e['category_id'] ?? 0) === (int)$cat['id']));
          if ($catEventsCount > 0):
        ?>
          <button type="button" 
                  class="filter-pill <?= $activeFilter === $cat['slug'] ? 'active' : '' ?>" 
                  data-filter="<?= View::e($cat['slug']) ?>" 
                  role="tab">
            <?= View::e($cat['name']) ?> (<?= $catEventsCount ?>)
          </button>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <!-- Curated Event Albums Grid -->
    <?php if (!empty($eventsWithPhotos)): ?>
      <div class="event-gallery-grid" id="event-gallery-grid">
        <?php foreach ($eventsWithPhotos as $ev): ?>
          <?php 
            $photoCount = count($ev['photos']);
            $photosJson = json_encode($ev['photos']);
            $coverImg = !empty($ev['cover_image']) ? $ev['cover_image'] : ($ev['photos'][0]['file_path'] ?? '/assets/images/client/image-005.jpg');
            $previewPhotos = array_slice($ev['photos'], 0, 4);
          ?>
          <article class="event-card"
                   data-category="<?= View::e($ev['category_slug'] ?? 'all') ?>"
                   data-event-title="<?= View::e($ev['title']) ?>"
                   data-photos="<?= htmlspecialchars($photosJson, ENT_QUOTES, 'UTF-8') ?>"
                   tabindex="0"
                   role="button"
                   aria-label="View <?= View::e($ev['title']) ?> event gallery">

            <!-- Event Cover Image -->
            <div class="event-card-media">
              <img src="<?= View::asset($coverImg) ?>" alt="<?= View::e($ev['title']) ?>" loading="lazy">
              
              <div class="event-badge-group">
                <span class="badge badge-maroon">
                  <?= View::e($ev['category_name'] ?? 'Celebration') ?>
                </span>
                
                <span class="event-count-badge">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                  </svg>
                  <?= $photoCount ?> Photos
                </span>
              </div>
            </div>

            <!-- Mini Preview Thumbnails (if >= 2 photos) -->
            <?php if (count($previewPhotos) > 1): ?>
              <div class="event-thumbs-preview" aria-hidden="true">
                <?php foreach ($previewPhotos as $idx => $p): ?>
                  <div class="event-thumb-item" data-index="<?= $idx ?>" title="Click to view photo">
                    <img src="<?= View::asset($p['file_path']) ?>" alt="" loading="lazy">
                    <?php if ($idx === 3 && $photoCount > 4): ?>
                      <div class="event-thumb-more">+<?= $photoCount - 3 ?></div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <!-- Card Content -->
            <div class="event-card-body">
              <div class="event-meta-line">
                <span><?= View::e($ev['event_type'] ?? 'Special Celebration') ?></span>
                <?php if (!empty($ev['location'])): ?>
                  <span>&bull;</span>
                  <span><?= View::e($ev['location']) ?></span>
                <?php endif; ?>
              </div>

              <h2 class="event-card-title"><?= View::e($ev['title']) ?></h2>

              <?php if (!empty($ev['description'])): ?>
                <p class="event-card-desc"><?= View::e($ev['description']) ?></p>
              <?php endif; ?>

              <div class="event-card-footer">
                <span class="event-action-link">
                  Browse Event Album
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </span>

                <span style="font-size: 0.75rem; color: var(--color-text-tertiary);">
                  <?= $photoCount ?> <?= $photoCount === 1 ? 'Photograph' : 'Photographs' ?>
                </span>
              </div>
            </div>

          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Empty State Container -->
    <div class="gallery-empty-state" id="gallery-empty-state" style="<?= empty($eventsWithPhotos) ? 'display: block;' : 'display: none;' ?>">
      <h3 style="font-size: var(--font-size-xl); margin-bottom: 0.5rem; color: var(--color-text-primary);">No Celebrations Found in this Category</h3>
      <p style="color: var(--color-text-secondary); max-width: 500px; margin: 0 auto 1.5rem auto;">
        We are actively photographing and uploading new stage setups for this category. Please view all works or connect with our team.
      </p>
      <a href="<?= View::url('/gallery.php') ?>" class="btn btn-secondary btn-sm">View All Celebrations</a>
    </div>

    <!-- Consultation CTA -->
    <section class="cta-banner">
      <h2 class="cta-headline">Envisioning a Similar Stage for Your Event?</h2>
      <p class="cta-subtext">
        We customize every floral palette, backdrop dimension, and lighting tone to complement your celebration venue.
      </p>
      <div class="cta-buttons">
        <a href="<?= View::url('/contact.php') ?>" class="btn btn-gold btn-lg">Request Stage Quotation</a>
        <a href="<?= View::url('/about.php') ?>" class="btn btn-secondary btn-lg" style="background: rgba(255, 255, 255, 0.15); color: #FFFFFF; border-color: rgba(255, 255, 255, 0.3);">
          Our Floral Story
        </a>
      </div>
    </section>

  </div>

<?php 
$pageJs = ['assets/js/gallery.js'];
require_once APP_ROOT . '/app/Views/layouts/footer.php'; 
?>
