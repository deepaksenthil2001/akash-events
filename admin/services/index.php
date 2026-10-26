<?php
/**
 * Akash Event Management - Admin Services List
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Service;

$pageTitle = 'Manage Services | Akash Event Management Admin';
$pageHeaderTitle = 'Services Management';
$currentAdminNav = 'services';

// Handle POST actions (Toggle publish or Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $action = $_POST['action'] ?? '';
        $id = (int) ($_POST['id'] ?? 0);

        if ($action === 'toggle_publish' && $id > 0) {
            Service::togglePublish($id);
            flash('success', 'Service status updated successfully.');
        } elseif ($action === 'delete' && $id > 0) {
            Service::delete($id);
            flash('success', 'Service deleted successfully.');
        }
    }
    header("Location: " . View::url('/admin/services/index.php'));
    exit;
}

$services = Service::getAll(false);

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card">
    <div class="admin-card-header">
      <div>
        <h2 class="admin-card-title">All Event Services (<?= count($services) ?>)</h2>
        <p style="font-size: 0.85rem; color: #718096; margin-top: 2px;">Manage published celebration disciplines, stage offerings, and service descriptions.</p>
      </div>

      <div style="display: flex; gap: 0.75rem; align-items: center;">
        <input type="text" class="table-search-input form-input" placeholder="Search services..." style="width: 200px; padding: 0.45rem 0.75rem; font-size: 0.85rem;">
        <a href="<?= View::url('/admin/services/create.php') ?>" class="admin-btn admin-btn-primary">
          + Add New Service
        </a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 80px;">Cover</th>
            <th>Service Title</th>
            <th>Order</th>
            <th>Featured</th>
            <th>Status</th>
            <th style="text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($services as $srv): ?>
            <tr>
              <td>
                <img src="<?= View::asset($srv['cover_image']) ?>" alt="" width="56" height="40" style="object-fit: cover; border-radius: var(--radius-xs);">
              </td>
              <td>
                <strong><?= View::e($srv['title']) ?></strong>
                <div style="font-size: 0.75rem; color: #718096;">slug: /<?= View::e($srv['slug']) ?></div>
              </td>
              <td><?= $srv['display_order'] ?></td>
              <td>
                <?= !empty($srv['is_featured']) ? '<span class="status-pill status-completed">Featured</span>' : '<span style="color:#A0AEC0;">No</span>' ?>
              </td>
              <td>
                <span class="status-pill <?= !empty($srv['is_published']) ? 'status-published' : 'status-draft' ?>">
                  <?= !empty($srv['is_published']) ? 'Published' : 'Draft' ?>
                </span>
              </td>
              <td style="text-align: right;">
                <div style="display: inline-flex; gap: 0.5rem;">
                  <!-- Toggle Publish Button -->
                  <form action="<?= View::url('/admin/services/index.php') ?>" method="POST" style="display: inline;">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="action" value="toggle_publish">
                    <input type="hidden" name="id" value="<?= $srv['id'] ?>">
                    <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm">
                      <?= !empty($srv['is_published']) ? 'Unpublish' : 'Publish' ?>
                    </button>
                  </form>

                  <!-- Edit Button -->
                  <a href="<?= View::url('/admin/services/edit.php?id=' . $srv['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
                    Edit
                  </a>

                  <!-- Delete Button -->
                  <form action="<?= View::url('/admin/services/index.php') ?>" method="POST" style="display: inline;">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $srv['id'] ?>">
                    <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" data-confirm="Are you sure you want to permanently delete this service?">
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
