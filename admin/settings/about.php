<?php
/**
 * Akash Event Management - About Us Page Content Management
 * Clean Apple-Standard Administration Interface
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Setting;

$pageTitle = 'About Us Content Management | Akash Event Management Admin';
$pageHeaderTitle = 'About Founder & Story';
$currentAdminNav = 'about';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $updates = [
            'about_intro_lead' => trim($_POST['about_intro_lead'] ?? ''),
            'about_philosophy' => trim($_POST['about_philosophy'] ?? ''),
            'founder_quote' => trim($_POST['founder_quote'] ?? ''),
            'founder_name' => trim($_POST['founder_name'] ?? 'P. Manobalan'),
            'founder_title' => trim($_POST['founder_title'] ?? ''),
            'experience_statement' => trim($_POST['experience_statement'] ?? '10+ Years of Craftsmanship & Service'),
        ];

        if (Setting::updateBatch($updates, 'about')) {
            flash('success', 'About Us page content updated successfully.');
        } else {
            flash('error', 'Failed to update About content.');
        }
    }
    header("Location: " . View::url('/admin/settings/about.php'));
    exit;
}

$allSettings = Setting::getAll();

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 860px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h1 class="admin-card-title">About Founder &amp; Story Configuration</h1>
        <p class="admin-card-subtitle">
          Customize the introduction lead statement, philosophy quote, and founder credentials.
        </p>
      </div>
    </div>

    <form action="<?= View::url('/admin/settings/about.php') ?>" method="POST" novalidate>
      <?= Csrf::field() ?>

      <!-- 1. Hero & Philosophy Narrative -->
      <div class="form-section">
        <h2 class="form-section-title">1. Introduction &amp; Heritage Philosophy</h2>
        <p class="form-section-desc">Displayed at the top of the About Us page.</p>

        <div class="form-group">
          <label class="form-label" for="about_intro_lead">Hero / Lead Intro Statement</label>
          <textarea id="about_intro_lead" name="about_intro_lead" class="form-textarea" style="min-height: 80px;"><?= View::e($allSettings['about_intro_lead'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="about_philosophy">Event Philosophy &amp; Heritage Narrative</label>
          <textarea id="about_philosophy" name="about_philosophy" class="form-textarea" style="min-height: 120px;"><?= View::e($allSettings['about_philosophy'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- 2. Founder Quote -->
      <div class="form-section">
        <h2 class="form-section-title">2. Founder's Personal Commitment Quote</h2>
        <p class="form-section-desc">Highlighted in the large blockquote card on the About page.</p>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="founder_quote">Founder Quote</label>
          <textarea id="founder_quote" name="founder_quote" class="form-textarea" style="min-height: 90px;"><?= View::e($allSettings['founder_quote'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- 3. Founder Details -->
      <div class="form-section" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
        <h2 class="form-section-title">3. Founder Credentials</h2>
        <p class="form-section-desc">Official name, professional designation, and experience milestone.</p>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="founder_name">Lead Decorator Name</label>
            <input type="text" id="founder_name" name="founder_name" class="form-input" 
                   value="<?= View::e($allSettings['founder_name'] ?? 'P. Manobalan') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="founder_title">Founder Designation</label>
            <input type="text" id="founder_title" name="founder_title" class="form-input" 
                   value="<?= View::e($allSettings['founder_title'] ?? 'Master Floral Decorator & Event Architect') ?>">
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="experience_statement">Experience Milestone Statement</label>
          <input type="text" id="experience_statement" name="experience_statement" class="form-input" 
                 value="<?= View::e($allSettings['experience_statement'] ?? '10+ Years of Craftsmanship & Service') ?>">
        </div>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E5E5EA; display: flex; justify-content: flex-end;">
        <button type="submit" class="admin-btn admin-btn-gold admin-btn-lg">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          Save About Us Content
        </button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
