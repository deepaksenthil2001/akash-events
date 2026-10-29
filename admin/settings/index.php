<?php
/**
 * Akash Event Management - Business Settings Management
 * Clean Apple-Standard Administration Interface
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Setting;

$pageTitle = 'Business Settings | Akash Event Management Admin';
$pageHeaderTitle = 'Business Settings';
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
        ];

        if (Setting::updateBatch($settingsToUpdate)) {
            flash('success', 'Business settings saved successfully.');
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

  <div class="admin-card" style="max-width: 820px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h1 class="admin-card-title">Business Settings</h1>
        <p class="admin-card-subtitle">
          Contact details and brand information displayed across the website header, footer, and contact page.
        </p>
      </div>
    </div>

    <form action="<?= View::url('/admin/settings/index.php') ?>" method="POST" novalidate>
      <?= Csrf::field() ?>

      <!-- 1. Business & Founder Identity -->
      <div class="form-section">
        <h2 class="form-section-title">Brand &amp; Founder</h2>
        <p class="form-section-desc">Company title, monogram, and founder credentials.</p>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="business_name">Business Name <span class="required">*</span></label>
            <input type="text" id="business_name" name="business_name" class="form-input" value="<?= View::e($allSettings['business_name'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="brand_initials">Monogram Initials</label>
            <input type="text" id="brand_initials" name="brand_initials" class="form-input" value="<?= View::e($allSettings['brand_initials'] ?? 'MHA') ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="founder_name">Founder Name <span class="required">*</span></label>
            <input type="text" id="founder_name" name="founder_name" class="form-input" value="<?= View::e($allSettings['founder_name'] ?? 'P. Manobalan') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="founder_title">Founder Designation</label>
            <input type="text" id="founder_title" name="founder_title" class="form-input" value="<?= View::e($allSettings['founder_title'] ?? '') ?>">
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="experience_statement">Experience Statement</label>
          <input type="text" id="experience_statement" name="experience_statement" class="form-input" value="<?= View::e($allSettings['experience_statement'] ?? '10+ Years of Craftsmanship &amp; Service') ?>">
        </div>
      </div>

      <!-- 2. Phone, WhatsApp & Address -->
      <div class="form-section">
        <h2 class="form-section-title">Contact &amp; Location</h2>
        <p class="form-section-desc">Official telephone, WhatsApp number, and verified address.</p>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="phone_primary">Primary Phone <span class="required">*</span></label>
            <input type="text" id="phone_primary" name="phone_primary" class="form-input" value="<?= View::e($allSettings['phone_primary'] ?? '+91 93442 73433') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="phone_secondary">Secondary Phone</label>
            <input type="text" id="phone_secondary" name="phone_secondary" class="form-input" value="<?= View::e($allSettings['phone_secondary'] ?? '+91 96599 99856') ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="whatsapp_number">WhatsApp Number (e.g. 919344273433)</label>
            <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-input" value="<?= View::e($allSettings['whatsapp_number'] ?? '919344273433') ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="email_primary">Primary Email</label>
            <input type="email" id="email_primary" name="email_primary" class="form-input" value="<?= View::e($allSettings['email_primary'] ?? 'akasheventmanagement87@gmail.com') ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="address_line">Office / Venue Address</label>
          <input type="text" id="address_line" name="address_line" class="form-input" value="<?= View::e($allSettings['address_line'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="service_areas">Service Coverage Region</label>
          <input type="text" id="service_areas" name="service_areas" class="form-input" value="<?= View::e($allSettings['service_areas'] ?? 'Hosur, Krishnagiri, Bangalore, Dharmapuri, and surrounding regions') ?>">
        </div>
      </div>

      <!-- 3. Social Profiles -->
      <div class="form-section">
        <h2 class="form-section-title">Online Profiles</h2>
        <p class="form-section-desc">Direct links to social media and business directory listings.</p>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="instagram_url">Instagram Profile URL</label>
            <input type="url" id="instagram_url" name="instagram_url" class="form-input" value="<?= View::e($allSettings['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/...">
          </div>

          <div class="form-group">
            <label class="form-label" for="google_business_url">Google Maps / Business Listing</label>
            <input type="url" id="google_business_url" name="google_business_url" class="form-input" value="<?= View::e($allSettings['google_business_url'] ?? '') ?>" placeholder="https://maps.google.com/...">
          </div>
        </div>
      </div>

      <div style="margin-top: 2rem;">
        <button type="submit" class="admin-btn admin-btn-gold admin-btn-lg">
          Save Settings
        </button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
