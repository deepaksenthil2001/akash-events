<?php
/**
 * Akash Event Management - Homepage
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Service;
use App\Models\Gallery;

$pageTitle = View::setting('meta_title_default', 'Akash Event Management | Premium Stage Decoration & Event Planning');
$pageDescription = View::setting('meta_description_default', 'Creating moments worth remembering. Premium wedding stages, floral decoration, mandapams, baby showers, and birthday setups in Hosur & Bangalore.');
$currentNav = 'home';
$pageCss = ['assets/css/pages/home.css'];

// Retrieve dynamic database content
$featuredServices = Service::getFeatured(6);
$featuredGallery = Gallery::getFeatured(6);

$heroEyebrow = View::setting('hero_eyebrow', 'Hosur & Bangalore | 10+ Years of Celebrations');
$heroHeadline = View::setting('hero_headline', 'Creating Moments Worth Remembering.');
$heroSubheadline = View::setting('hero_subheadline', 'Thoughtfully designed event experiences, bespoke stage architecture, and breathtaking celebration spaces curated by P. Manobalan and team.');
$heroDesktopImg = View::setting('hero_desktop_image', '/assets/images/hero/hero-desktop.jpg');
$heroMobileImg = View::setting('hero_mobile_image', '/assets/images/hero/hero-mobile.jpg');
$heroCtaPrimaryText = View::setting('hero_cta_primary_text', 'Explore Our Work');
$heroCtaPrimaryLink = View::setting('hero_cta_primary_link', '/gallery.php');
$heroCtaSecondaryText = View::setting('hero_cta_secondary_text', 'Plan Your Celebration');
$heroCtaSecondaryLink = View::setting('hero_cta_secondary_link', '/contact.php');

$phonePrimary = View::setting('phone_primary', '+91 93442 73433');
$whatsapp = View::setting('whatsapp_number', '919344273433');

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <!-- 1. Premium Apple-Inspired Cinematic Hero -->
  <section class="hero-section" id="hero">
    <div class="hero-background">
      <picture class="hero-background-picture">
        <source media="(max-width: 768px)" srcset="<?= View::asset($heroMobileImg) ?>">
        <img src="<?= View::asset($heroDesktopImg) ?>" alt="Akash Event Management Luxury Stage Decoration" class="hero-background-img" fetchpriority="high">
      </picture>
      <div class="hero-gradient-overlay"></div>
    </div>

    <div class="site-container hero-content">
      <div class="hero-eyebrow">
        <span><?= View::e($heroEyebrow) ?></span>
      </div>

      <h1 class="hero-headline"><?= View::e($heroHeadline) ?></h1>
      
      <p class="hero-subheadline">
        <?= View::e($heroSubheadline) ?>
      </p>

      <div class="hero-actions">
        <a href="<?= View::url($heroCtaPrimaryLink) ?>" class="btn btn-gold btn-lg">
          <?= View::e($heroCtaPrimaryText) ?>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </a>
        <a href="<?= View::url($heroCtaSecondaryLink) ?>" class="btn btn-secondary btn-lg" style="background: rgba(255, 255, 255, 0.15); color: #FFFFFF; border-color: rgba(255, 255, 255, 0.3);">
          <?= View::e($heroCtaSecondaryText) ?>
        </a>
      </div>
    </div>
  </section>

  <!-- 2. Experience Highlight & Milestone Metrics -->
  <section class="stats-section">
    <div class="site-container">
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number"><?= View::e(View::setting('stat_1_val', '10+')) ?></div>
          <div class="stat-label"><?= View::e(View::setting('stat_1_label', 'Years of Craftsmanship')) ?></div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?= View::e(View::setting('stat_2_val', '1,200+')) ?></div>
          <div class="stat-label"><?= View::e(View::setting('stat_2_label', 'Celebrations Crafted')) ?></div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?= View::e(View::setting('stat_3_val', '100%')) ?></div>
          <div class="stat-label"><?= View::e(View::setting('stat_3_label', 'Bespoke Artistry')) ?></div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?= View::e(View::setting('stat_4_val', '8+')) ?></div>
          <div class="stat-label"><?= View::e(View::setting('stat_4_label', 'Core Event Domains')) ?></div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. Brief Introduction & Philosophy -->
  <section class="story-section" style="padding-top: var(--space-5xl); padding-bottom: var(--space-4xl);">
    <div class="site-container">
      <div class="story-grid">
        <div class="story-visual">
          <img src="<?= View::asset('assets/images/client/image-013.jpg') ?>" alt="Akash Event Management Champagne Gold Stage Architecture" loading="lazy">
        </div>
        <div class="story-content">
          <span class="section-eyebrow"><?= View::e(View::setting('home_intro_eyebrow', 'The Craft of Celebration')) ?></span>
          <h2 class="section-title"><?= View::e(View::setting('home_intro_title', 'Elevating Rituals into Living Works of Art.')) ?></h2>
          <p>
            <?= nl2br(View::e(View::setting('home_intro_p1', 'Founded by master decorator P. Manobalan, Akash Event Management has spent over a decade curating extraordinary celebration spaces across Hosur, Krishnagiri, and Bangalore.'))) ?>
          </p>
          <p>
            <?= nl2br(View::e(View::setting('home_intro_p2', 'Whether designing a monumental floral mandapam, an intimate champagne-gold engagement stage, or a heartwarming naming ceremony, we harmonize fresh floral fragrances, architectural silhouettes, and warm ambient lighting.'))) ?>
          </p>
          <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <a href="<?= View::url('/about.php') ?>" class="btn btn-secondary">Our Full Story</a>
            <a href="https://wa.me/<?= $whatsapp ?>?text=<?= urlencode('Hello P. Manobalan, I would like to discuss event decoration for an upcoming function.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp">
              Direct WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. Featured Services -->
  <section class="services-section">
    <div class="site-container">
      <div class="section-header text-center">
        <span class="section-eyebrow">Curated Expertise</span>
        <h2 class="section-title">Bespoke Event Solutions</h2>
        <p class="section-description">
          Each celebration requires distinct architectural care. Explore our primary event disciplines designed for modern refinement and sacred tradition.
        </p>
      </div>

      <div class="services-grid">
        <?php foreach ($featuredServices as $service): ?>
          <article class="card">
            <div class="card-media">
              <img src="<?= View::asset($service['cover_image']) ?>" alt="<?= View::e($service['title']) ?>" loading="lazy">
            </div>
            <div class="card-body">
              <h3 class="card-title"><?= View::e($service['title']) ?></h3>
              <p class="card-text"><?= View::e($service['short_description']) ?></p>
              <a href="<?= View::url('/service-details.php?slug=' . urlencode($service['slug'])) ?>" class="btn btn-secondary btn-sm" style="margin-top: auto;">
                Explore Details
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                  <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div style="text-align: center; margin-top: var(--space-3xl);">
        <a href="<?= View::url('/services.php') ?>" class="btn btn-primary btn-lg">
          View All Services Catalog
        </a>
      </div>
    </div>
  </section>

  <!-- 5. Curated Work Showcase (Actual Client Portfolio) -->
  <section style="padding: var(--space-5xl) 0; background-color: var(--color-surface);">
    <div class="site-container">
      <div class="section-header text-center">
        <span class="section-eyebrow">Real Portfolio</span>
        <h2 class="section-title">Moments We Have Shaped</h2>
        <p class="section-description">
          A glimpse into the authentic stage setups, floral walls, and ceremonies crafted for families across the region.
        </p>
      </div>

      <div class="gallery-grid">
        <?php foreach ($featuredGallery as $item): ?>
          <div class="gallery-card" 
               data-src="<?= View::asset($item['file_path']) ?>" 
               data-type="<?= $item['media_type'] ?>" 
               data-title="<?= View::e($item['title']) ?>" 
               data-caption="<?= View::e($item['caption']) ?>"
               data-category="<?= View::e($item['category_slug'] ?? 'all') ?>">
            
            <div class="gallery-media-wrapper">
              <img src="<?= View::asset($item['file_path']) ?>" alt="<?= View::e($item['title']) ?>" loading="lazy">
              
              <div class="gallery-badge">
                <?php if (!empty($item['is_concept'])): ?>
                  <span class="badge badge-concept">Editorial Concept</span>
                <?php else: ?>
                  <span class="badge badge-gold">Authentic Work</span>
                <?php endif; ?>
              </div>
            </div>

            <div class="gallery-info">
              <h4 class="gallery-item-title"><?= View::e($item['title']) ?></h4>
              <p class="gallery-item-caption"><?= View::e($item['caption'] ?? '') ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div style="text-align: center; margin-top: var(--space-3xl);">
        <a href="<?= View::url('/gallery.php') ?>" class="btn btn-secondary btn-lg">
          Explore Complete Gallery & Filter by Event
        </a>
      </div>
    </div>
  </section>

  <!-- 6. Visual Transformation Storytelling -->
  <section style="padding: var(--space-5xl) 0; background-color: var(--color-canvas);">
    <div class="site-container">
      <div class="story-grid">
        <div class="story-content">
          <span class="section-eyebrow"><?= View::e(View::setting('home_story_eyebrow', 'Precision & Execution')) ?></span>
          <h2 class="section-title"><?= View::e(View::setting('home_story_title', 'From Empty Hall to Cinematic Wonder.')) ?></h2>
          <p>
            <?= nl2br(View::e(View::setting('home_story_p1', 'An empty convention hall or outdoor lawn is a blank canvas. Our process begins with understanding the lighting angles, sightlines, and ceremonial flow.'))) ?>
          </p>
          <p>
            <?= nl2br(View::e(View::setting('home_story_p2', 'Our master floral teams arrive hours ahead to hand-wire fresh roses, jasmines, and lilies. Spotlights and chandeliers are meticulously positioned to eliminate shadows, creating the dream backdrop for your wedding photography.'))) ?>
          </p>
          <div style="margin-top: 1.5rem;">
            <a href="<?= View::url('/contact.php') ?>" class="btn btn-primary">Schedule a Consultation</a>
          </div>
        </div>

        <div class="story-visual">
          <img src="<?= View::asset('assets/images/client/image-003.jpg') ?>" alt="Akash Event Management Illuminated Emerald Wave Stage" loading="lazy">
        </div>
      </div>
    </div>
  </section>

  <!-- 7. Call To Action Banner -->
  <section class="site-container">
    <div class="cta-banner">
      <h2 class="cta-headline">Let's Plan Your Unforgettable Celebration</h2>
      <p class="cta-subtext">
        Dates book rapidly during wedding and festive seasons. Speak directly with P. Manobalan or send your event requirements online.
      </p>
      <div class="cta-buttons">
        <a href="<?= View::url('/contact.php') ?>" class="btn btn-gold btn-lg">Book a Consultation</a>
        <a href="tel:<?= preg_replace('/[^\d+]/', '', $phonePrimary) ?>" class="btn btn-secondary btn-lg" style="background: rgba(255, 255, 255, 0.15); color: #FFFFFF; border-color: rgba(255, 255, 255, 0.3);">
          Call: <?= View::e($phonePrimary) ?>
        </a>
      </div>
    </div>
  </section>

<?php 
$pageJs = ['assets/js/gallery.js'];
require_once APP_ROOT . '/app/Views/layouts/footer.php'; 
?>
