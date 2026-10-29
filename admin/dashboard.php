<?php
/**
 * Akash Event Management - Administrative Dashboard Overview
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Service;
use App\Models\Gallery;
use App\Models\Event;
use App\Models\Enquiry;

$pageTitle = 'Dashboard Executive Overview | Akash Event Management Admin';
$pageHeaderTitle = 'Control Center Dashboard';
$currentAdminNav = 'dashboard';

// Fetch genuine real-time database metric statistics
$totalEvents = Event::count(false);
$totalServices = Service::count(false);
$publishedServices = Service::count(true);
$totalImages = Gallery::count(false, 'image');
$totalEnquiries = Enquiry::count('all');
$newEnquiries = Enquiry::count('new');

$recentEnquiries = Enquiry::getRecent(6);
$recentEvents = Event::getAll(null, null, false, 4, 0);

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <!-- Metric Cards -->
  <div class="metrics-grid">
    <div class="metric-card">
      <div class="metric-label">Events &amp; Albums</div>
      <div class="metric-value"><?= $totalEvents ?></div>
      <div class="metric-sub"><?= $totalImages ?> photographs uploaded</div>
    </div>

    <div class="metric-card">
      <div class="metric-label">Published Services</div>
      <div class="metric-value"><?= $publishedServices ?></div>
      <div class="metric-sub"><?= $totalServices ?> total in catalog</div>
    </div>

    <div class="metric-card">
      <div class="metric-label">Total Inquiries</div>
      <div class="metric-value"><?= $totalEnquiries ?></div>
      <div class="metric-sub">Leads received online</div>
    </div>

    <div class="metric-card">
      <div class="metric-label">New Leads</div>
      <div class="metric-value" style="color: #0071E3;"><?= $newEnquiries ?></div>
      <div class="metric-sub">Awaiting follow-up</div>
    </div>
  </div>

  <!-- Quick Action Banner -->
  <div class="admin-card" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h2 style="font-size: 1.05rem; font-weight: 600; color: var(--admin-primary); margin: 0 0 0.2rem 0;">Quick Management Actions</h2>
      <p style="font-size: 0.8125rem; color: var(--admin-text-secondary); margin: 0;">Add new completed events, upload stage photos, or adjust business contact information.</p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
      <a href="<?= View::url('/admin/gallery/upload.php') ?>" class="admin-btn admin-btn-gold">
        + Add New Event
      </a>
      <a href="<?= View::url('/admin/services/create.php') ?>" class="admin-btn admin-btn-secondary">
        + Add Service
      </a>
      <a href="<?= View::url('/admin/settings/index.php') ?>" class="admin-btn admin-btn-secondary">
        Edit Phone &amp; Address
      </a>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 1.6fr 1.4fr; gap: var(--space-xl);">
    
    <!-- Recent Enquiries -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title">Recent Customer Inquiries</h2>
          <p style="font-size: 0.8rem; color: #718096; margin-top: 2px;">Submissions from the public contact form.</p>
        </div>
        <a href="<?= View::url('/admin/enquiries/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
          View All Inquiries
        </a>
      </div>

      <?php if (!empty($recentEnquiries)): ?>
        <div class="table-responsive">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Event Type</th>
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
                  <td>
                    <span style="font-size: 0.825rem; color: #2D3748;"><?= View::e($enq['event_type']) ?></span>
                  </td>
                  <td>
                    <span class="status-pill status-<?= $enq['status'] ?>">
                      <?= ucfirst($enq['status']) ?>
                    </span>
                  </td>
                  <td>
                    <a href="<?= View::url('/admin/enquiries/view.php?id=' . $enq['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
                      View
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p style="color: #718096; font-size: 0.875rem; padding: 2rem 0; text-align: center;">
          No customer inquiries received yet. New inquiries submitted through your website will appear here.
        </p>
      <?php endif; ?>
    </div>

    <!-- Recent Events -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title">Recent Event Albums</h2>
          <p style="font-size: 0.8rem; color: #718096; margin-top: 2px;">Client celebrations published in gallery.</p>
        </div>
        <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
          Manage All Events
        </a>
      </div>

      <div style="display: flex; flex-direction: column; gap: 0.75rem;">
        <?php foreach ($recentEvents as $ev): ?>
          <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.65rem 0.75rem; border: 1px solid var(--admin-border); border-radius: var(--radius-md); background: #FFFFFF;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
              <div style="width: 56px; height: 42px; border-radius: var(--radius-sm); overflow: hidden; background: #121214; flex-shrink: 0;">
                <?php if (!empty($ev['cover_image'])): ?>
                  <img src="<?= View::asset($ev['cover_image']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
              </div>
              <div>
                <strong style="display: block; font-size: 0.85rem; color: #1A202C;"><?= View::e($ev['title']) ?></strong>
                <span style="font-size: 0.72rem; color: #718096;"><?= (int)$ev['photo_count'] ?> photographs</span>
              </div>
            </div>

            <a href="<?= View::url('/admin/gallery/edit.php?id=' . $ev['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm" style="flex-shrink: 0;">
              Edit Photos
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
