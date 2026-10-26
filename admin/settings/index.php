<?php
/**
 * Akash Event Management - Business Settings Management
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Setting;

$pageTitle = 'Business Settings | Akash Event Management Admin';
$pageHeaderTitle = 'Business & Contact Settings';
$currentAdminNav = 'settings';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $settingsToUpdate = [
            'business_name' => trim($_POST['business_name'] ?? ''),
            'brand_initials' => trim($_POST['brand_initials'] ?? ''),
            'founder_name' => trim($_POST['founder_name'] ?? ''),
            'founder_title' => trim($_POST['founder_title'] ?? ''),
            'experience_statement' => trim($_POST['experience_statement'] ?? ''),
            'phone_primary' => trim($_POST['phone_primary'] ?? ''),
            'phone_secondary' => trim($_POST['phone_secondary'] ?? ''),
            'email_primary' => trim($_POST['email_primary'] ?? ''),
            'email_secondary' => trim($_POST['email_secondary'] ?? ''),
            'address_line' => trim($_POST['address_line'] ?? ''),
            'service_areas' => trim($_POST['service_areas'] ?? ''),
            'instagram_url' => trim($_POST['instagram_url'] ?? ''),
            'google_business_url' => trim($_POST['google_business_url'] ?? ''),
            'whatsapp_number' => trim($_POST['whatsapp_number'] ?? ''),
            'meta_title_default' => trim($_POST['meta_title_default'] ?? ''),
            'meta_description_default' => trim($_POST['meta_description_default'] ?? ''),
        ];

        if (Setting::updateBatch($settingsToUpdate)) {
            flash('success', 'Business settings successfully saved.');
        } else {
            flash('error', 'Failed to save settings.');
        }
    }
    header("Location: " . View::url('/admin/settings/index.php'));
    exit;
}

$allSettings = Setting::getAll();

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 860px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h2 class="admin-card-title">Verified Business Profile & Contact Details</h2>
        <p style="font-size: 0.85rem; color: #718096; margin-top: 2px;">
          Changes saved here automatically update across the entire customer website, header, and footer.
        </p>
      </div>
    </div>

    <form action="<?= View::url('/admin/settings/index.php') ?>" method="POST" novalidate>
      <?= Csrf::field() ?>

      <h3 style="font-size: 1rem; color: #2D3748; margin: 1.5rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid var(--admin-border);">
        1. Company & Founder Identity
      </h3>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="business_name">Official Business Name</label>
          <input type="text" id="business_name" name="business_name" class="form-input" value="<?= View::e($allSettings['business_name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="brand_initials">Brand Monogram Initials</label>
          <input type="text" id="brand_initials" name="brand_initials" class="form-input" value="<?= View::e($allSettings['brand_initials'] ?? 'MHA') ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="founder_name">Founder & Lead Decorator</label>
          <input type="text" id="founder_name" name="founder_name" class="form-input" value="<?= View::e($allSettings['founder_name'] ?? 'P. Manobalan') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="founder_title">Founder Designation</label>
          <input type="text" id="founder_title" name="founder_title" class="form-input" value="<?= View::e($allSettings['founder_title'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="experience_statement">Experience Statement</label>
        <input type="text" id="experience_statement" name="experience_statement" class="form-input" value="<?= View::e($allSettings['experience_statement'] ?? '10+ Years of Service') ?>">
      </div>

      <h3 style="font-size: 1rem; color: #2D3748; margin: 2rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid var(--admin-border);">
        2. Verified Phone, Email & Address
      </h3>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="phone_primary">Primary Phone Number *</label>
          <input type="text" id="phone_primary" name="phone_primary" class="form-input" value="<?= View::e($allSettings['phone_primary'] ?? '+91 93442 73433') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="phone_secondary">Secondary Phone Number</label>
          <input type="text" id="phone_secondary" name="phone_secondary" class="form-input" value="<?= View::e($allSettings['phone_secondary'] ?? '+91 96599 99856') ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="email_primary">Primary Enquiries Email</label>
          <input type="email" id="email_primary" name="email_primary" class="form-input" value="<?= View::e($allSettings['email_primary'] ?? 'akasheventmanagement87@gmail.com') ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="email_secondary">Secondary Business Email</label>
          <input type="email" id="email_secondary" name="email_secondary" class="form-input" value="<?= View::e($allSettings['email_secondary'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="address_line">Physical Office / Venue Address</label>
        <input type="text" id="address_line" name="address_line" class="form-input" value="<?= View::e($allSettings['address_line'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label" for="service_areas">Service Coverage Region</label>
        <input type="text" id="service_areas" name="service_areas" class="form-input" value="<?= View::e($allSettings['service_areas'] ?? '') ?>">
      </div>

      <h3 style="font-size: 1rem; color: #2D3748; margin: 2rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid var(--admin-border);">
        3. Social Channels & Business Links
      </h3>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="whatsapp_number">WhatsApp Number (with country code)</label>
          <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-input" value="<?= View::e($allSettings['whatsapp_number'] ?? '919344273433') ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="instagram_url">Instagram Profile URL</label>
          <input type="url" id="instagram_url" name="instagram_url" class="form-input" value="<?= View::e($allSettings['instagram_url'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="google_business_url">Google Business Profile URL</label>
        <input type="url" id="google_business_url" name="google_business_url" class="form-input" value="<?= View::e($allSettings['google_business_url'] ?? '') ?>">
      </div>

      <h3 style="font-size: 1rem; color: #2D3748; margin: 2rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid var(--admin-border);">
        4. Global Search Engine Optimization (SEO)
      </h3>

      <div class="form-group">
        <label class="form-label" for="meta_title_default">Default Website Meta Title</label>
        <input type="text" id="meta_title_default" name="meta_title_default" class="form-input" value="<?= View::e($allSettings['meta_title_default'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label" for="meta_description_default">Default Website Meta Description</label>
        <textarea id="meta_description_default" name="meta_description_default" class="form-input" style="min-height: 70px;"><?= View::e($allSettings['meta_description_default'] ?? '') ?></textarea>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end;">
        <button type="submit" class="admin-btn admin-btn-primary">
          Save All Business Settings
        </button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
