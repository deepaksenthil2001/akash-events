<?php
/**
 * Akash Event Management - Enquiry Details & Workflow Management
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Enquiry;

$id = (int)($_GET['id'] ?? 0);
$enquiry = Enquiry::getById($id);

if (!$enquiry) {
    flash('error', 'Enquiry record not found.');
    header("Location: " . View::url('/admin/enquiries/index.php'));
    exit;
}

// Automatically transition status from 'new' to 'read' on first open
if ($enquiry['status'] === 'new') {
    Enquiry::updateStatus($id, 'read');
    $enquiry['status'] = 'read';
}

$pageTitle = 'Enquiry: ' . $enquiry['customer_name'] . ' | Admin';
$pageHeaderTitle = 'Enquiry Details';
$currentAdminNav = 'enquiries';

// Handle status or notes update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $status = $_POST['status'] ?? $enquiry['status'];
        $notes = trim($_POST['admin_notes'] ?? '');

        if (Enquiry::updateStatus($id, $status, $notes)) {
            flash('success', 'Enquiry updated successfully.');
            $enquiry = Enquiry::getById($id);
        } else {
            flash('error', 'Failed to update enquiry.');
        }
    }
}

$cleanPhone = preg_replace('/[^\d]/', '', $enquiry['phone']);
$whatsappUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode("Hello {$enquiry['customer_name']}, this is P. Manobalan from Akash Event Management regarding your event enquiry for {$enquiry['event_type']}.");

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div style="max-width: 860px; margin: 0 auto;">
    
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
      <a href="<?= View::url('/admin/enquiries/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
        &larr; Back to Enquiries
      </a>

      <div style="display: flex; gap: 0.5rem;">
        <a href="tel:<?= preg_replace('/[^\d+]/', '', $enquiry['phone']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
          Call Customer
        </a>
        <a href="<?= $whatsappUrl ?>" target="_blank" rel="noopener noreferrer" class="admin-btn admin-btn-gold admin-btn-sm">
          Chat on WhatsApp
        </a>
      </div>
    </div>

    <!-- Enquiry Details Card -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title"><?= View::e($enquiry['customer_name']) ?></h2>
          <span style="font-size: 0.8rem; color: #718096;">Received on <?= date('F d, Y \a\t h:i A', strtotime($enquiry['created_at'])) ?></span>
        </div>

        <span class="status-pill status-<?= $enquiry['status'] ?>" style="font-size: 0.85rem; padding: 0.4rem 1rem;">
          Status: <?= ucfirst($enquiry['status']) ?>
        </span>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-xl); margin-bottom: 2rem;">
        <div>
          <label class="form-label">Phone Number</label>
          <div style="font-size: 1.1rem; font-weight: 600; color: #1A202C;">
            <a href="tel:<?= preg_replace('/[^\d+]/', '', $enquiry['phone']) ?>" style="color: var(--color-gold-deep);">
              <?= View::e($enquiry['phone']) ?>
            </a>
          </div>
        </div>

        <div>
          <label class="form-label">Email Address</label>
          <div style="font-size: 1rem; color: #1A202C;">
            <?= !empty($enquiry['email']) ? View::e($enquiry['email']) : '<span style="color:#A0AEC0;">Not provided</span>' ?>
          </div>
        </div>

        <div>
          <label class="form-label">Event Category</label>
          <div style="font-size: 1rem; font-weight: 600; color: #1A202C;">
            <?= View::e($enquiry['event_type']) ?>
          </div>
        </div>

        <div>
          <label class="form-label">Preferred Celebration Date</label>
          <div style="font-size: 1rem; color: #1A202C;">
            <?= View::formatDate($enquiry['preferred_date']) ?>
          </div>
        </div>

        <div>
          <label class="form-label">Venue / City Location</label>
          <div style="font-size: 1rem; color: #1A202C;">
            <?= !empty($enquiry['event_location']) ? View::e($enquiry['event_location']) : '<span style="color:#A0AEC0;">Not specified</span>' ?>
          </div>
        </div>

        <div>
          <label class="form-label">Estimated Guest Count</label>
          <div style="font-size: 1rem; color: #1A202C;">
            <?= !empty($enquiry['guest_count']) ? View::e($enquiry['guest_count']) : '<span style="color:#A0AEC0;">Not specified</span>' ?>
          </div>
        </div>
      </div>

      <div style="margin-bottom: 2rem;">
        <label class="form-label">Customer Requirements & Message</label>
        <div style="background-color: #F8FAFC; border: 1px solid var(--admin-border); border-radius: var(--radius-md); padding: 1.25rem; font-size: 0.95rem; line-height: 1.6; color: #2D3748;">
          <?= nl2br(View::e($enquiry['message'])) ?>
        </div>
      </div>

      <!-- Workflow Update Form -->
      <form action="<?= View::url('/admin/enquiries/view.php?id=' . $id) ?>" method="POST" style="padding-top: 1.5rem; border-top: 1px solid var(--admin-border);">
        <?= Csrf::field() ?>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="status">Update Lead Status</label>
            <select name="status" id="status" class="form-select">
              <option value="new" <?= $enquiry['status'] === 'new' ? 'selected' : '' ?>>New (Unread)</option>
              <option value="read" <?= $enquiry['status'] === 'read' ? 'selected' : '' ?>>Read / In Review</option>
              <option value="contacted" <?= $enquiry['status'] === 'contacted' ? 'selected' : '' ?>>Contacted Customer</option>
              <option value="completed" <?= $enquiry['status'] === 'completed' ? 'selected' : '' ?>>Completed / Booked</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="admin_notes">Internal Administrator Notes</label>
            <textarea name="admin_notes" id="admin_notes" class="form-input" style="min-height: 80px;" placeholder="Add private notes on quotation, discussion, client requests..."><?= View::e($enquiry['admin_notes'] ?? '') ?></textarea>
          </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
          <button type="submit" class="admin-btn admin-btn-primary">
            Update Status & Save Notes
          </button>
        </div>
      </form>
    </div>

  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
