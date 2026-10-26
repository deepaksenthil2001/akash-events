<?php
/**
 * Akash Event Management - Homepage Hero Management
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Helpers\Uploader;
use App\Core\Csrf;
use App\Models\Setting;

$pageTitle = 'Homepage Hero Management | Akash Event Management Admin';
$pageHeaderTitle = 'Homepage Hero & Call to Actions';
$currentAdminNav = 'hero';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $updates = [
            'hero_eyebrow' => trim($_POST['hero_eyebrow'] ?? ''),
            'hero_headline' => trim($_POST['hero_headline'] ?? ''),
            'hero_subheadline' => trim($_POST['hero_subheadline'] ?? ''),
            'hero_cta_primary_text' => trim($_POST['hero_cta_primary_text'] ?? ''),
            'hero_cta_primary_link' => trim($_POST['hero_cta_primary_link'] ?? ''),
            'hero_cta_secondary_text' => trim($_POST['hero_cta_secondary_text'] ?? ''),
            'hero_cta_secondary_link' => trim($_POST['hero_cta_secondary_link'] ?? ''),
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
            flash('success', 'Homepage hero updated successfully.');
        } else {
            flash('error', 'Failed to update hero settings.');
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

  <div class="admin-card" style="max-width: 860px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h2 class="admin-card-title">Art-Directed Hero Configuration</h2>
        <p style="font-size: 0.85rem; color: #718096; margin-top: 2px;">
          Manage the visual first impression, headline, and primary call to actions for desktop and mobile visitors.
        </p>
      </div>
    </div>

    <form action="<?= View::url('/admin/settings/hero.php') ?>" method="POST" enctype="multipart/form-data" novalidate>
      <?= Csrf::field() ?>

      <div class="form-group">
        <label class="form-label" for="hero_eyebrow">Hero Eyebrow Label</label>
        <input type="text" id="hero_eyebrow" name="hero_eyebrow" class="form-input" value="<?= View::e($allSettings['hero_eyebrow'] ?? '') ?>" placeholder="e.g. Hosur & Bangalore | 10+ Years of Celebrations">
      </div>

      <div class="form-group">
        <label class="form-label" for="hero_headline">Primary Hero Headline *</label>
        <input type="text" id="hero_headline" name="hero_headline" class="form-input" required value="<?= View::e($allSettings['hero_headline'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label" for="hero_subheadline">Supporting Paragraph Copy</label>
        <textarea id="hero_subheadline" name="hero_subheadline" class="form-textarea" style="min-height: 80px;"><?= View::e($allSettings['hero_subheadline'] ?? '') ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="hero_cta_primary_text">Primary Button Text</label>
          <input type="text" id="hero_cta_primary_text" name="hero_cta_primary_text" class="form-input" value="<?= View::e($allSettings['hero_cta_primary_text'] ?? 'Explore Our Work') ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="hero_cta_primary_link">Primary Button URL</label>
          <input type="text" id="hero_cta_primary_link" name="hero_cta_primary_link" class="form-input" value="<?= View::e($allSettings['hero_cta_primary_link'] ?? '/gallery.php') ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="hero_cta_secondary_text">Secondary Button Text</label>
          <input type="text" id="hero_cta_secondary_text" name="hero_cta_secondary_text" class="form-input" value="<?= View::e($allSettings['hero_cta_secondary_text'] ?? 'Plan Your Celebration') ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="hero_cta_secondary_link">Secondary Button URL</label>
          <input type="text" id="hero_cta_secondary_link" name="hero_cta_secondary_link" class="form-input" value="<?= View::e($allSettings['hero_cta_secondary_link'] ?? '/contact.php') ?>">
        </div>
      </div>

      <h3 style="font-size: 1rem; color: #2D3748; margin: 2rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid var(--admin-border);">
        Hero Visuals (Art-Directed Separately for Desktop & Mobile)
      </h3>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Desktop 16:9 Hero Image</label>
          <img src="<?= View::asset($desktopHero) ?>" alt="Desktop Hero" style="width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 0.75rem; box-shadow: var(--shadow-sm);">
          <label class="form-label" for="desktop_hero_img">Replace Desktop Hero (16:9)</label>
          <input type="file" id="desktop_hero_img" name="desktop_hero_img" accept="image/*" class="form-input">
        </div>

        <div class="form-group">
          <label class="form-label">Mobile 9:16 Portrait Hero Image</label>
          <img src="<?= View::asset($mobileHero) ?>" alt="Mobile Hero" style="width: 160px; aspect-ratio: 9/16; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 0.75rem; box-shadow: var(--shadow-sm);">
          <label class="form-label" for="mobile_hero_img">Replace Mobile Hero (9:16)</label>
          <input type="file" id="mobile_hero_img" name="mobile_hero_img" accept="image/*" class="form-input">
        </div>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end;">
        <button type="submit" class="admin-btn admin-btn-primary">
          Save Hero Settings
        </button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
