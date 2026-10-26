<?php
/**
 * Akash Event Management - Administrative Dashboard Overview
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Service;
use App\Models\Gallery;
use App\Models\Enquiry;

$pageTitle = 'Executive Overview | Akash Event Management Admin';
$pageHeaderTitle = 'Executive Business Dashboard';
$currentAdminNav = 'dashboard';

// Fetch genuine real-time database metric statistics
$totalServices = Service::count(false);
$publishedServices = Service::count(true);
$totalImages = Gallery::count(false, 'image');
$totalVideos = Gallery::count(false, 'video');
$totalEnquiries = Enquiry::count('all');
$newEnquiries = Enquiry::count('new');

$recentEnquiries = Enquiry::getRecent(6);
$recentMedia = Gallery::getMedia(null, null, false, 4, 0);

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <!-- Real Metric Cards -->
  <div class="metrics-grid">
    <div class="metric-card">
      <div class="metric-label">Published Services</div>
      <div class="metric-value"><?= $publishedServices ?></div>
      <div class="metric-sub"><?= $totalServices ?> total in catalog</div>
    </div>

    <div class="metric-card">
      <div class="metric-label">Gallery Media</div>
      <div class="metric-value"><?= $totalImages + $totalVideos ?></div>
      <div class="metric-sub"><?= $totalImages ?> images &bull; <?= $totalVideos ?> videos</div>
    </div>

    <div class="metric-card">
      <div class="metric-label">Total Enquiries</div>
      <div class="metric-value"><?= $totalEnquiries ?></div>
      <div class="metric-sub">Customer leads recorded</div>
    </div>

    <div class="metric-card" style="border-left: 4px solid var(--color-gold);">
      <div class="metric-label">New / Unread Leads</div>
      <div class="metric-value" style="color: var(--color-gold-deep);"><?= $newEnquiries ?></div>
      <div class="metric-sub">Pending contact follow-up</div>
    </div>
  </div>

  <!-- Quick Action Banner -->
  <div class="admin-card" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; background: linear-gradient(135deg, #FFFFFF 0%, #FBF9F5 100%);">
    <div>
      <h2 style="font-size: 1.15rem; font-weight: 700; color: #1A202C; margin-bottom: 0.25rem;">Quick Management Actions</h2>
      <p style="font-size: 0.85rem; color: #718096;">Add new celebration services, upload event photos, or adjust business contact information.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
      <a href="<?= View::url('/admin/services/create.php') ?>" class="admin-btn admin-btn-primary">
        + Add New Service
      </a>
      <a href="<?= View::url('/admin/gallery/upload.php') ?>" class="admin-btn admin-btn-gold">
        + Upload Media
      </a>
      <a href="<?= View::url('/admin/settings/index.php') ?>" class="admin-btn admin-btn-secondary">
        Edit Business Settings
      </a>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 1.8fr 1.2fr; gap: var(--space-xl);">
    
    <!-- Recent Enquiries -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h2 class="admin-card-title">Recent Event Enquiries</h2>
        <a href="<?= View::url('/admin/enquiries/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
          View All Enquiries
        </a>
      </div>

      <?php if (!empty($recentEnquiries)): ?>
        <div class="table-responsive">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Event Type</th>
                <th>Preferred Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentEnquiries as $enq): ?>
                <tr>
                  <td>
                    <strong><?= View::e($enq['customer_name']) ?></strong><br>
                    <a href="tel:<?= preg_replace('/[^\d+]/', '', $enq['phone']) ?>" style="font-size: 0.75rem; color: #718096;"><?= View::e($enq['phone']) ?></a>
                  </td>
                  <td><?= View::e($enq['event_type']) ?></td>
                  <td><?= View::formatDate($enq['preferred_date']) ?></td>
                  <td>
                    <span class="status-pill status-<?= $enq['status'] ?>">
                      <?= ucfirst($enq['status']) ?>
                    </span>
                  </td>
                  <td>
                    <a href="<?= View::url('/admin/enquiries/view.php?id=' . $enq['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
                      Details
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p style="color: #718096; font-size: 0.875rem; padding: 1.5rem 0; text-align: center;">
          No enquiries received yet. Submissions from the customer contact form will appear here.
        </p>
      <?php endif; ?>
    </div>

    <!-- Recently Uploaded Media -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h2 class="admin-card-title">Recently Uploaded Media</h2>
        <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
          View All
        </a>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
        <?php foreach ($recentMedia as $med): ?>
          <div style="border: 1px solid var(--admin-border); border-radius: var(--radius-md); overflow: hidden;">
            <div style="aspect-ratio: 4/3; background: #000;">
              <img src="<?= View::asset($med['file_path']) ?>" alt="<?= View::e($med['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="padding: 0.5rem; font-size: 0.75rem;">
              <strong style="display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= View::e($med['title']) ?></strong>
              <span style="color: #718096;"><?= ucfirst($med['media_type']) ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
