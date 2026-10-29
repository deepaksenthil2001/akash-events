<?php
/**
 * Akash Event Management - Contact & Event Enquiries
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Enquiry;

$pageTitle = 'Contact Us & Enquiries | Akash Event Management - Hosur';
$pageDescription = 'Get in touch with Akash Event Management for event stage decoration, floral setups, and planning in Hosur, Bangalore & Krishnagiri. Call +91 93442 73433.';
$currentNav = 'contact';
$pageCss = ['assets/css/pages/contact.css'];

$phonePrimary = View::setting('phone_primary', '+91 93442 73433');
$phoneSecondary = View::setting('phone_secondary', '+91 96599 99856');
$emailPrimary = View::setting('email_primary', 'akasheventmanagement87@gmail.com');
$address = View::setting('address_line', '12, Gokul Nagar, Naligabetta Agraharam, Bhavani palace backside, Hosur Tamil Nadu 635109');
$instagram = View::setting('instagram_url', 'https://www.instagram.com/akasheventmanagement');
$googleBusiness = View::setting('google_business_url', 'https://share.google/nUP5Exqa3K1doUEfd');
$whatsapp = View::setting('whatsapp_number', '919344273433');

// Preselect service if query parameter passed
$preselectedService = trim($_GET['service'] ?? '');

$formErrors = [];
$formSuccess = false;
$submittedData = [
    'customer_name' => '',
    'phone' => '',
    'email' => '',
    'event_type' => $preselectedService,
    'preferred_date' => '',
    'event_location' => '',
    'guest_count' => '',
    'message' => '',
];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF verification
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        $formErrors['csrf'] = ['Invalid or expired security token. Please refresh and try again.'];
    } else {
        $submittedData = [
            'customer_name' => trim($_POST['customer_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'event_type' => trim($_POST['event_type'] ?? ''),
            'preferred_date' => trim($_POST['preferred_date'] ?? ''),
            'event_location' => trim($_POST['event_location'] ?? ''),
            'guest_count' => trim($_POST['guest_count'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        $validator = new Validator($submittedData);
        $isValid = $validator->validate([
            'customer_name' => ['required', 'min:2', 'max:100'],
            'phone' => ['required', 'phone'],
            'email' => ['email'],
            'event_type' => ['required'],
            'message' => ['required', 'min:5', 'max:2000'],
        ]);

        if ($isValid) {
            try {
                $enquiryId = Enquiry::create($submittedData);
                if ($enquiryId > 0) {
                    $formSuccess = true;
                    // Reset form fields
                    $submittedData = [
                        'customer_name' => '',
                        'phone' => '',
                        'email' => '',
                        'event_type' => '',
                        'preferred_date' => '',
                        'event_location' => '',
                        'guest_count' => '',
                        'message' => '',
                    ];
                } else {
                    $formErrors['db'] = ['Could not save your enquiry at this time. Please call or contact us directly on WhatsApp.'];
                }
            } catch (\Exception $e) {
                $formErrors['db'] = ['Database transaction error. Please contact us directly via phone.'];
            }
        } else {
            $formErrors = $validator->getErrors();
        }
    }
}

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <div class="site-container">

    <section class="contact-page-header">
      <span class="section-eyebrow">Connect with Us</span>
      <h1 class="section-title">Let's Plan Something Extraordinary</h1>
      <p class="section-description">
        Reach out directly to discuss themes, dates, and stage decorations for your wedding, reception, birthday, or family celebration.
      </p>
    </section>

    <div class="contact-grid">
      
      <!-- Verified Contact Information Panel -->
      <aside class="contact-info-panel">
        
        <div class="contact-card">
          <h2 class="contact-card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Direct Event Consultation
          </h2>
          
          <div class="contact-detail-list">
            <div class="contact-detail-item">
              <span class="contact-detail-label">Lead Decorator</span>
              <span class="contact-detail-val">P. Manobalan (10+ Years Experience)</span>
            </div>

            <div class="contact-detail-item">
              <span class="contact-detail-label">Office & Studio Location</span>
              <span class="contact-detail-val"><?= View::e($address) ?></span>
            </div>

            <div class="contact-detail-item">
              <span class="contact-detail-label">Direct Phones</span>
              <span class="contact-detail-val">
                <a href="tel:<?= preg_replace('/[^\d+]/', '', $phonePrimary) ?>" style="color: var(--color-gold); font-weight: 600;"><?= View::e($phonePrimary) ?></a>
                <br>
                <a href="tel:<?= preg_replace('/[^\d+]/', '', $phoneSecondary) ?>"><?= View::e($phoneSecondary) ?></a>
              </span>
            </div>

            <div class="contact-detail-item">
              <span class="contact-detail-label">Email Address</span>
              <span class="contact-detail-val">
                <a href="mailto:<?= View::e($emailPrimary) ?>"><?= View::e($emailPrimary) ?></a>
              </span>
            </div>
          </div>

          <div class="contact-actions">
            <a href="https://wa.me/<?= $whatsapp ?>?text=<?= urlencode('Hello P. Manobalan, I would like to enquire about stage decoration services.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp" style="width: 100%;">
              Quick Chat on WhatsApp
            </a>
            <a href="<?= View::e($googleBusiness) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" style="width: 100%;">
              View Google Business Profile
            </a>
            <a href="<?= View::e($instagram) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" style="width: 100%;">
              Follow on Instagram
            </a>
          </div>
        </div>

        <!-- Working Hours & Areas -->
        <div class="contact-card">
          <h3 class="contact-card-title" style="font-size: var(--font-size-base);">Service Coverage Area</h3>
          <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); line-height: var(--line-height-relaxed); margin-bottom: 0.75rem;">
            We actively decorate venues and manage celebrations across:
          </p>
          <ul style="font-size: var(--font-size-sm); color: var(--color-text-secondary); display: flex; flex-direction: column; gap: 0.35rem;">
            <li>&bull; Hosur & Krishnagiri District</li>
            <li>&bull; Electronic City, Anekal & Attibele</li>
            <li>&bull; Greater Bangalore & Surrounding Regions</li>
          </ul>
        </div>

      </aside>

      <!-- Customer Enquiry Form Panel -->
      <main class="contact-form-panel">
        <h2 style="font-size: var(--font-size-2xl); font-weight: 600; margin-bottom: 0.5rem; color: var(--color-text-primary);">
          Send an Event Enquiry
        </h2>
        <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); margin-bottom: var(--space-xl);">
          Fill in your celebration details below. Our team reviews every enquiry personally and gets back to you promptly.
        </p>

        <?php if ($formSuccess): ?>
          <div class="alert alert-success" style="margin-bottom: var(--space-xl);">
            <div>
              <strong>Thank you! Your enquiry has been recorded successfully.</strong><br>
              P. Manobalan and the Akash Event Management team will contact you shortly on your provided phone number.
            </div>
          </div>
        <?php endif; ?>

        <?php if (!empty($formErrors['csrf']) || !empty($formErrors['db'])): ?>
          <div class="alert alert-error" style="margin-bottom: var(--space-xl);">
            <div>
              <?= View::e($formErrors['csrf'][0] ?? $formErrors['db'][0] ?? 'An error occurred.') ?>
            </div>
          </div>
        <?php endif; ?>

        <form action="<?= View::url('/contact.php') ?>" method="POST" class="enquiry-form" novalidate>
          <?= Csrf::field() ?>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="customer_name">Your Full Name *</label>
              <input type="text" id="customer_name" name="customer_name" class="form-input" required value="<?= View::e($submittedData['customer_name']) ?>" placeholder="e.g. Ramesh Kumar">
              <?php if (!empty($formErrors['customer_name'])): ?>
                <div class="form-error"><?= View::e($formErrors['customer_name'][0]) ?></div>
              <?php endif; ?>
            </div>

            <div class="form-group">
              <label class="form-label" for="phone">Phone Number *</label>
              <input type="tel" id="phone" name="phone" class="form-input" required value="<?= View::e($submittedData['phone']) ?>" placeholder="e.g. +91 93442 73433">
              <?php if (!empty($formErrors['phone'])): ?>
                <div class="form-error"><?= View::e($formErrors['phone'][0]) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="email">Email Address (Optional)</label>
              <input type="email" id="email" name="email" class="form-input" value="<?= View::e($submittedData['email']) ?>" placeholder="yourname@gmail.com">
              <?php if (!empty($formErrors['email'])): ?>
                <div class="form-error"><?= View::e($formErrors['email'][0]) ?></div>
              <?php endif; ?>
            </div>

            <div class="form-group">
              <label class="form-label" for="event_type">Type of Celebration *</label>
              <select id="event_type" name="event_type" class="form-select" required>
                <option value="">-- Select Event Category --</option>
                <option value="Wedding & Reception" <?= $submittedData['event_type'] === 'Wedding & Reception' || str_contains($submittedData['event_type'], 'Wedding') ? 'selected' : '' ?>>Wedding & Reception</option>
                <option value="Engagement Ceremony" <?= str_contains($submittedData['event_type'], 'Engagement') ? 'selected' : '' ?>>Engagement Ceremony</option>
                <option value="Seemantham & Baby Shower" <?= str_contains($submittedData['event_type'], 'Baby Shower') || str_contains($submittedData['event_type'], 'Traditional') ? 'selected' : '' ?>>Seemantham & Baby Shower</option>
                <option value="Naming Ceremony" <?= str_contains($submittedData['event_type'], 'Naming') ? 'selected' : '' ?>>Naming Ceremony</option>
                <option value="Birthday Celebration" <?= str_contains($submittedData['event_type'], 'Birthday') ? 'selected' : '' ?>>Birthday Celebration</option>
                <option value="Half Saree & Puberty Ceremony" <?= str_contains($submittedData['event_type'], 'Half Saree') ? 'selected' : '' ?>>Half Saree & Puberty Ceremony</option>
                <option value="Fresh Flower Decoration" <?= str_contains($submittedData['event_type'], 'Flower') ? 'selected' : '' ?>>Fresh Flower Decoration</option>
                <option value="Light Production & DJ Sound" <?= str_contains($submittedData['event_type'], 'Light') ? 'selected' : '' ?>>Light Production & DJ Sound</option>
                <option value="Tent House & Catering" <?= str_contains($submittedData['event_type'], 'Tent') ? 'selected' : '' ?>>Tent House & Catering</option>
                <option value="Other Celebration" <?= $submittedData['event_type'] === 'Other Celebration' ? 'selected' : '' ?>>Other Celebration</option>
              </select>
              <?php if (!empty($formErrors['event_type'])): ?>
                <div class="form-error"><?= View::e($formErrors['event_type'][0]) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="preferred_date">Preferred Event Date</label>
              <input type="date" id="preferred_date" name="preferred_date" class="form-input" value="<?= View::e($submittedData['preferred_date']) ?>">
            </div>

            <div class="form-group">
              <label class="form-label" for="event_location">Venue / City Location</label>
              <input type="text" id="event_location" name="event_location" class="form-input" value="<?= View::e($submittedData['event_location']) ?>" placeholder="e.g. Hosur, Attibele, Bangalore">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="message">Your Requirements / Decor Theme *</label>
            <textarea id="message" name="message" class="form-textarea" required placeholder="Describe your preferred theme, colors, stage preferences, or special requests..."><?= View::e($submittedData['message']) ?></textarea>
            <?php if (!empty($formErrors['message'])): ?>
              <div class="form-error"><?= View::e($formErrors['message'][0]) ?></div>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
            Submit Event Enquiry
          </button>
        </form>
      </main>

    </div>

  </div>

<?php 
$pageJs = ['assets/js/forms.js'];
require_once APP_ROOT . '/app/Views/layouts/footer.php'; 
?>
