<?php
/**
 * Akash Event Management - Homepage Hero & Content Management
 * Clean Apple-Standard Administration Interface
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Helpers\Uploader;
use App\Core\Csrf;
use App\Models\Setting;

$pageTitle = 'Homepage Content Management | Akash Event Management Admin';
$pageHeaderTitle = 'Homepage Hero & Content';
$currentAdminNav = 'hero';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $updates = [
            // 1. Hero
            'hero_eyebrow' => trim($_POST['hero_eyebrow'] ?? ''),
            'hero_headline' => trim($_POST['hero_headline'] ?? ''),
            'hero_subheadline' => trim($_POST['hero_subheadline'] ?? ''),
            'hero_cta_primary_text' => trim($_POST['hero_cta_primary_text'] ?? ''),
            'hero_cta_primary_link' => trim($_POST['hero_cta_primary_link'] ?? ''),
            'hero_cta_secondary_text' => trim($_POST['hero_cta_secondary_text'] ?? ''),
            'hero_cta_secondary_link' => trim($_POST['hero_cta_secondary_link'] ?? ''),

            // 2. Stats Counters
            'stat_1_val' => trim($_POST['stat_1_val'] ?? '10+'),
            'stat_1_label' => trim($_POST['stat_1_label'] ?? 'Years of Craftsmanship'),
            'stat_2_val' => trim($_POST['stat_2_val'] ?? '1,200+'),
            'stat_2_label' => trim($_POST['stat_2_label'] ?? 'Celebrations Crafted'),
            'stat_3_val' => trim($_POST['stat_3_val'] ?? '100%'),
            'stat_3_label' => trim($_POST['stat_3_label'] ?? 'Bespoke Artistry'),
            'stat_4_val' => trim($_POST['stat_4_val'] ?? '8+'),
            'stat_4_label' => trim($_POST['stat_4_label'] ?? 'Core Event Domains'),

            // 3. Homepage Stories
            'home_intro_eyebrow' => trim($_POST['home_intro_eyebrow'] ?? ''),
            'home_intro_title' => trim($_POST['home_intro_title'] ?? ''),
            'home_intro_p1' => trim($_POST['home_intro_p1'] ?? ''),
            'home_intro_p2' => trim($_POST['home_intro_p2'] ?? ''),

            'home_story_eyebrow' => trim($_POST['home_story_eyebrow'] ?? ''),
            'home_story_title' => trim($_POST['home_story_title'] ?? ''),
            'home_story_p1' => trim($_POST['home_story_p1'] ?? ''),
            'home_story_p2' => trim($_POST['home_story_p2'] ?? ''),
        ];

        // Handle Desktop Hero Image Upload
        if (isset($_FILES['desktop_hero_img']) && $_FILES['desktop_hero_img']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = Uploader::uploadImage($_FILES['desktop_hero_img'], 'hero', false);
            if ($up['success']) {
                $updates['hero_desktop_image'] = $up['file_path'];
            } else {
                flash('error', 'Desktop image upload failed: ' . $up['error']);
            }
        }

        // Handle Mobile Hero Image Upload
        if (isset($_FILES['mobile_hero_img']) && $_FILES['mobile_hero_img']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = Uploader::uploadImage($_FILES['mobile_hero_img'], 'hero', false);
            if ($up['success']) {
                $updates['hero_mobile_image'] = $up['file_path'];
            } else {
                flash('error', 'Mobile image upload failed: ' . $up['error']);
            }
        }

        if (Setting::updateBatch($updates)) {
            flash('success', 'Homepage content and stats updated successfully.');
        } else {
            flash('error', 'Failed to update homepage settings.');
        }
    }
    header("Location: " . View::url('/admin/settings/hero.php'));
    exit;
}

$allSettings = Setting::getAll();

$desktopHero = $allSettings['hero_desktop_image'] ?? '/assets/images/hero/hero-desktop.jpg';
$mobileHero = $allSettings['hero_mobile_image'] ?? '/assets/images/hero/hero-mobile.jpg';

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 880px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h1 class="admin-card-title">Homepage Content &amp; Hero Configuration</h1>
        <p class="admin-card-subtitle">
          Manage the hero banner, milestone stats, and featured storytelling sections on the homepage.
        </p>
      </div>
    </div>

    <form action="<?= View::url('/admin/settings/hero.php') ?>" method="POST" enctype="multipart/form-data" novalidate>
      <?= Csrf::field() ?>

      <!-- 1. Primary Hero Banner -->
      <div class="form-section">
        <h2 class="form-section-title">1. Main Hero Banner</h2>
        <p class="form-section-desc">The first impression visitors see on desktop and mobile.</p>

        <div class="form-group">
          <label class="form-label" for="hero_eyebrow">Hero Eyebrow Label</label>
          <input type="text" id="hero_eyebrow" name="hero_eyebrow" class="form-input" 
                 value="<?= View::e($allSettings['hero_eyebrow'] ?? 'Hosur & Bangalore | 10+ Years of Celebrations') ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="hero_headline">Primary Hero Headline <span class="required">*</span></label>
          <input type="text" id="hero_headline" name="hero_headline" class="form-input" required 
                 value="<?= View::e($allSettings['hero_headline'] ?? 'Creating Moments Worth Remembering.') ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="hero_subheadline">Supporting Paragraph Copy</label>
          <textarea id="hero_subheadline" name="hero_subheadline" class="form-textarea" style="min-height: 80px;"><?= View::e($allSettings['hero_subheadline'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="hero_cta_primary_text">Primary Button Text</label>
            <input type="text" id="hero_cta_primary_text" name="hero_cta_primary_text" class="form-input" 
                   value="<?= View::e($allSettings['hero_cta_primary_text'] ?? 'Explore Our Work') ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="hero_cta_primary_link">Primary Button URL</label>
            <input type="text" id="hero_cta_primary_link" name="hero_cta_primary_link" class="form-input" 
                   value="<?= View::e($allSettings['hero_cta_primary_link'] ?? '/gallery.php') ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="hero_cta_secondary_text">Secondary Button Text</label>
            <input type="text" id="hero_cta_secondary_text" name="hero_cta_secondary_text" class="form-input" 
                   value="<?= View::e($allSettings['hero_cta_secondary_text'] ?? 'Plan Your Celebration') ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="hero_cta_secondary_link">Secondary Button URL</label>
            <input type="text" id="hero_cta_secondary_link" name="hero_cta_secondary_link" class="form-input" 
                   value="<?= View::e($allSettings['hero_cta_secondary_link'] ?? '/contact.php') ?>">
          </div>
        </div>

        <div class="form-row" style="margin-top: 1rem;">
          <div class="form-group">
            <label class="form-label">Desktop 16:9 Hero Image</label>
            <img src="<?= View::asset($desktopHero) ?>" alt="Desktop Hero" style="width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: 8px; margin-bottom: 0.5rem; border: 1px solid #E5E5EA;">
            <label class="form-label" for="desktop_hero_img" style="font-size: 0.775rem;">Upload New Desktop Hero</label>
            <input type="file" id="desktop_hero_img" name="desktop_hero_img" accept="image/*" class="form-input">
          </div>

          <div class="form-group">
            <label class="form-label">Mobile 9:16 Portrait Hero Image</label>
            <img src="<?= View::asset($mobileHero) ?>" alt="Mobile Hero" style="width: 140px; aspect-ratio: 9/16; object-fit: cover; border-radius: 8px; margin-bottom: 0.5rem; border: 1px solid #E5E5EA;">
            <label class="form-label" for="mobile_hero_img" style="font-size: 0.775rem;">Upload New Mobile Hero</label>
            <input type="file" id="mobile_hero_img" name="mobile_hero_img" accept="image/*" class="form-input">
          </div>
        </div>
      </div>

      <!-- 2. Milestone Metrics & Stats Counters -->
      <div class="form-section">
        <h2 class="form-section-title">2. Milestone Stats &amp; Counters</h2>
        <p class="form-section-desc">The 4 numerical metrics displayed directly beneath the hero banner.</p>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="stat_1_val">Metric 1 Number / Value</label>
            <input type="text" id="stat_1_val" name="stat_1_val" class="form-input" 
                   value="<?= View::e($allSettings['stat_1_val'] ?? '10+') ?>" placeholder="e.g. 10+">
          </div>

          <div class="form-group">
            <label class="form-label" for="stat_1_label">Metric 1 Label</label>
            <input type="text" id="stat_1_label" name="stat_1_label" class="form-input" 
                   value="<?= View::e($allSettings['stat_1_label'] ?? 'Years of Craftsmanship') ?>" placeholder="e.g. Years of Craftsmanship">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="stat_2_val">Metric 2 Number / Value</label>
            <input type="text" id="stat_2_val" name="stat_2_val" class="form-input" 
                   value="<?= View::e($allSettings['stat_2_val'] ?? '1,200+') ?>" placeholder="e.g. 1,200+">
          </div>

          <div class="form-group">
            <label class="form-label" for="stat_2_label">Metric 2 Label</label>
            <input type="text" id="stat_2_label" name="stat_2_label" class="form-input" 
                   value="<?= View::e($allSettings['stat_2_label'] ?? 'Celebrations Crafted') ?>" placeholder="e.g. Celebrations Crafted">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="stat_3_val">Metric 3 Number / Value</label>
            <input type="text" id="stat_3_val" name="stat_3_val" class="form-input" 
                   value="<?= View::e($allSettings['stat_3_val'] ?? '100%') ?>" placeholder="e.g. 100%">
          </div>

          <div class="form-group">
            <label class="form-label" for="stat_3_label">Metric 3 Label</label>
            <input type="text" id="stat_3_label" name="stat_3_label" class="form-input" 
                   value="<?= View::e($allSettings['stat_3_label'] ?? 'Bespoke Artistry') ?>" placeholder="e.g. Bespoke Artistry">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="stat_4_val">Metric 4 Number / Value</label>
            <input type="text" id="stat_4_val" name="stat_4_val" class="form-input" 
                   value="<?= View::e($allSettings['stat_4_val'] ?? '8+') ?>" placeholder="e.g. 8+">
          </div>

          <div class="form-group">
            <label class="form-label" for="stat_4_label">Metric 4 Label</label>
            <input type="text" id="stat_4_label" name="stat_4_label" class="form-input" 
                   value="<?= View::e($allSettings['stat_4_label'] ?? 'Core Event Domains') ?>" placeholder="e.g. Core Event Domains">
          </div>
        </div>
      </div>

      <!-- 3. Craft of Celebration Story -->
      <div class="form-section">
        <h2 class="form-section-title">3. Craft of Celebration Narrative</h2>
        <p class="form-section-desc">The introductory craftsmanship narrative on the homepage.</p>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="home_intro_eyebrow">Section Eyebrow</label>
            <input type="text" id="home_intro_eyebrow" name="home_intro_eyebrow" class="form-input" 
                   value="<?= View::e($allSettings['home_intro_eyebrow'] ?? 'The Craft of Celebration') ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="home_intro_title">Headline Title</label>
            <input type="text" id="home_intro_title" name="home_intro_title" class="form-input" 
                   value="<?= View::e($allSettings['home_intro_title'] ?? 'Elevating Rituals into Living Works of Art.') ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="home_intro_p1">Paragraph 1 (Founder &amp; Heritage)</label>
          <textarea id="home_intro_p1" name="home_intro_p1" class="form-textarea" style="min-height: 70px;"><?= View::e($allSettings['home_intro_p1'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="home_intro_p2">Paragraph 2 (Floral Artistry &amp; Atmosphere)</label>
          <textarea id="home_intro_p2" name="home_intro_p2" class="form-textarea" style="min-height: 70px;"><?= View::e($allSettings['home_intro_p2'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- 4. From Empty Hall to Cinematic Wonder Transformation -->
      <div class="form-section" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
        <h2 class="form-section-title">4. Transformation Story Section</h2>
        <p class="form-section-desc">The &ldquo;From Empty Hall to Cinematic Wonder&rdquo; story on the homepage.</p>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="home_story_eyebrow">Section Eyebrow</label>
            <input type="text" id="home_story_eyebrow" name="home_story_eyebrow" class="form-input" 
                   value="<?= View::e($allSettings['home_story_eyebrow'] ?? 'Precision & Execution') ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="home_story_title">Headline Title</label>
            <input type="text" id="home_story_title" name="home_story_title" class="form-input" 
                   value="<?= View::e($allSettings['home_story_title'] ?? 'From Empty Hall to Cinematic Wonder.') ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="home_story_p1">Paragraph 1 (The Blank Canvas)</label>
          <textarea id="home_story_p1" name="home_story_p1" class="form-textarea" style="min-height: 70px;"><?= View::e($allSettings['home_story_p1'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="home_story_p2">Paragraph 2 (Master Floral Execution)</label>
          <textarea id="home_story_p2" name="home_story_p2" class="form-textarea" style="min-height: 70px;"><?= View::e($allSettings['home_story_p2'] ?? '') ?></textarea>
        </div>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E5E5EA; display: flex; justify-content: flex-end;">
        <button type="submit" class="admin-btn admin-btn-gold admin-btn-lg">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          Save Homepage Content
        </button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
