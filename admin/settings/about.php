<?php
/**
 * Akash Event Management - About Us Page Content Management
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Setting;

$pageTitle = 'About Us Content Management | Akash Event Management Admin';
$pageHeaderTitle = 'About Us Page Content';
$currentAdminNav = 'about';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $updates = [
            'about_intro_lead' => trim($_POST['about_intro_lead'] ?? ''),
            'about_philosophy' => trim($_POST['about_philosophy'] ?? ''),
            'founder_name' => trim($_POST['founder_name'] ?? ''),
            'founder_title' => trim($_POST['founder_title'] ?? ''),
        ];

        if (Setting::updateBatch($updates)) {
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
        <h2 class="admin-card-title">Editorial About Page Narrative</h2>
        <p style="font-size: 0.85rem; color: #718096; margin-top: 2px;">
          Customize the introduction lead statement, philosophy quote, and founder credentials.
        </p>
      </div>
    </div>

    <form action="<?= View::url('/admin/settings/about.php') ?>" method="POST" novalidate>
      <?= Csrf::field() ?>

      <div class="form-group">
        <label class="form-label" for="about_intro_lead">Hero / Lead Intro Statement</label>
        <textarea id="about_intro_lead" name="about_intro_lead" class="form-textarea" style="min-height: 90px;"><?= View::e($allSettings['about_intro_lead'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label" for="about_philosophy">Event Philosophy & Heritage Narrative</label>
        <textarea id="about_philosophy" name="about_philosophy" class="form-textarea" style="min-height: 140px;"><?= View::e($allSettings['about_philosophy'] ?? '') ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="founder_name">Lead Decorator Name</label>
          <input type="text" id="founder_name" name="founder_name" class="form-input" value="<?= View::e($allSettings['founder_name'] ?? 'P. Manobalan') ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="founder_title">Founder Designation</label>
          <input type="text" id="founder_title" name="founder_title" class="form-input" value="<?= View::e($allSettings['founder_title'] ?? '') ?>">
        </div>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end;">
        <button type="submit" class="admin-btn admin-btn-primary">
          Save About Us Content
        </button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
