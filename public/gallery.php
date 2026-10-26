<?php
/**
 * Akash Event Management - Gallery & Portfolio
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Gallery;

$pageTitle = 'Event Gallery & Portfolio | Akash Event Management - Hosur';
$pageDescription = 'Browse the authentic event decoration portfolio of Akash Event Management: Grand wedding stages, fresh flower mandapams, romantic engagement arches, and birthday setups.';
$currentNav = 'gallery';
$pageCss = ['assets/css/pages/gallery.css'];

// Retrieve categories and published media from database
$categories = Gallery::getCategories(true);
$mediaItems = Gallery::getMedia(null, null, true);

$activeFilter = trim($_GET['category'] ?? 'all');

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <div class="site-container">

    <!-- Header Section -->
    <section class="gallery-page-header">
      <span class="section-eyebrow">Visual Excellence</span>
      <h1 class="section-title">The Artistry of Every Moment</h1>
      <p class="section-description">
        Explore our curated portfolio of authentic celebrations. Every stage, floral arch, and ambient lighting production is designed and installed with meticulous care.
      </p>
    </section>

    <!-- Filter Category Pills -->
    <div class="gallery-filters" role="tablist" aria-label="Event Categories">
      <button type="button" class="filter-pill <?= $activeFilter === 'all' ? 'active' : '' ?>" data-filter="all" role="tab">
        All Works (<?= count($mediaItems) ?>)
      </button>
      
      <?php 
        $videoCount = count(array_filter($mediaItems, fn($i) => ($i['media_type'] ?? '') === 'video'));
        if ($videoCount > 0): 
      ?>
        <button type="button" 
                class="filter-pill <?= $activeFilter === 'video' ? 'active' : '' ?>" 
                data-filter="video" 
                role="tab">
          Stage Videos (<?= $videoCount ?>)
        </button>
      <?php endif; ?>

      <?php foreach ($categories as $cat): ?>
        <?php if ($cat['media_count'] > 0): ?>
          <button type="button" 
                  class="filter-pill <?= $activeFilter === $cat['slug'] ? 'active' : '' ?>" 
                  data-filter="<?= View::e($cat['slug']) ?>" 
                  role="tab">
            <?= View::e($cat['name']) ?> (<?= (int) $cat['media_count'] ?>)
          </button>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <!-- Gallery Media Grid -->
    <?php if (!empty($mediaItems)): ?>
      <div class="gallery-grid" id="gallery-grid">
        <?php foreach ($mediaItems as $item): ?>
          <article class="gallery-card"
                   data-category="<?= View::e($item['category_slug'] ?? 'all') ?>"
                   data-src="<?= View::asset($item['file_path']) ?>"
                   data-type="<?= $item['media_type'] ?>"
                   data-title="<?= View::e($item['title']) ?>"
                   data-caption="<?= View::e($item['caption'] ?? '') ?>"
                   tabindex="0"
                   role="button"
                   aria-label="View <?= View::e($item['title']) ?>">

            <div class="gallery-media-wrapper">
              <?php if ($item['media_type'] === 'video'): ?>
                <video src="<?= View::asset($item['file_path']) ?>" muted playsinline></video>
                <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.3);">
                  <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(255, 255, 255, 0.9); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-md);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#1D1D1F">
                      <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                  </div>
                </div>
              <?php else: ?>
                <img src="<?= View::asset($item['file_path']) ?>" alt="<?= View::e($item['title']) ?>" loading="lazy">
              <?php endif; ?>

              <div class="gallery-badge">
                <?php if (!empty($item['is_concept'])): ?>
                  <span class="badge badge-concept">Editorial Concept</span>
                <?php else: ?>
                  <span class="badge badge-gold">Authentic Work</span>
                <?php endif; ?>
              </div>
            </div>

            <div class="gallery-info">
              <h2 class="gallery-item-title"><?= View::e($item['title']) ?></h2>
              <?php if (!empty($item['caption'])): ?>
                <p class="gallery-item-caption"><?= View::e($item['caption']) ?></p>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="gallery-empty-state">
        <h3 style="margin-bottom: 0.5rem;">No Published Media Available</h3>
        <p>New gallery photographs and videos are currently being prepared. Please check back soon.</p>
      </div>
    <?php endif; ?>

    <!-- Consultation CTA -->
    <section class="cta-banner" style="margin-bottom: var(--space-5xl);">
      <h2 class="cta-headline">Envisioning a Similar Stage for Your Event?</h2>
      <p class="cta-subtext">
        We customize every color, floral variety, and backdrop dimension to match your celebration hall.
      </p>
      <div class="cta-buttons">
        <a href="<?= View::url('/contact.php') ?>" class="btn btn-gold btn-lg">Request Stage Quotation</a>
      </div>
    </section>

  </div>

<?php 
$pageJs = ['assets/js/gallery.js'];
require_once APP_ROOT . '/app/Views/layouts/footer.php'; 
?>
