<?php
/**
 * Akash Event Management - Admin Events & Photo Galleries Manager
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Event;
use App\Models\Gallery;

$pageTitle = 'Manage Events & Photos | Akash Event Management Admin';
$pageHeaderTitle = 'Events & Photo Galleries';
$currentAdminNav = 'gallery';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please refresh the page and try again.');
    } else {
        $action = $_POST['action'] ?? '';
        $id = (int) ($_POST['id'] ?? 0);

        if ($action === 'toggle_publish' && $id > 0) {
            Event::togglePublish($id);
            flash('success', 'Event visibility status updated.');
        } elseif ($action === 'delete' && $id > 0) {
            if (Event::delete($id)) {
                flash('success', 'Event and its associated photographs removed successfully.');
            } else {
                flash('error', 'Could not delete the event.');
            }
        }
    }
    header("Location: " . View::url('/admin/gallery/index.php'));
    exit;
}

$categories = Gallery::getCategories(false);
$filterCategory = trim($_GET['category'] ?? '');

$eventsList = Event::getAll(
    !empty($filterCategory) ? (int)$filterCategory : null,
    null,
    false
);

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card">
    <div class="admin-card-header">
      <div>
        <h1 class="admin-card-title">Events &amp; Galleries (<?= count($eventsList) ?>)</h1>
        <p class="admin-card-subtitle">
          Manage celebration portfolios and photographs displayed on the website gallery.
        </p>
      </div>

      <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
        <!-- Category Filter -->
        <form action="<?= View::url('/admin/gallery/index.php') ?>" method="GET" style="display: flex; gap: 0.5rem;">
          <select name="category" onchange="this.form.submit()" class="form-select" style="min-height: 38px; padding: 0.45rem 2rem 0.45rem 0.85rem; font-size: 0.825rem; width: auto;">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= $filterCategory == $cat['id'] ? 'selected' : '' ?>>
                <?= View::e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>

        <a href="<?= View::url('/admin/gallery/categories.php') ?>" class="admin-btn admin-btn-secondary">
          Manage Categories
        </a>
        <a href="<?= View::url('/admin/gallery/upload.php') ?>" class="admin-btn admin-btn-gold" style="font-weight: 600;">
          + Add New Event
        </a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 80px;">Cover</th>
            <th>Event Title &amp; Category</th>
            <th>Date &amp; Location</th>
            <th>Photos</th>
            <th>Status</th>
            <th style="text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($eventsList)): ?>
            <?php foreach ($eventsList as $ev): ?>
              <tr>
                <td>
                  <div style="width: 68px; height: 48px; border-radius: var(--radius-sm); overflow: hidden; background: #121214;">
                    <?php if (!empty($ev['cover_image'])): ?>
                      <img src="<?= View::asset($ev['cover_image']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                      <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; color: #718096;">No Cover</div>
                    <?php endif; ?>
                  </div>
                </td>

                <td>
                  <strong style="color: #1A202C; font-size: 0.95rem;"><?= View::e($ev['title']) ?></strong>
                  <div style="font-size: 0.78rem; color: #718096; margin-top: 2px;">
                    <?= View::e($ev['category_name'] ?? 'Uncategorized') ?>
                  </div>
                </td>

                <td>
                  <span style="font-size: 0.85rem; color: #2D3748;">
                    <?= !empty($ev['event_date']) ? View::formatDate($ev['event_date']) : 'Not Specified' ?>
                  </span>
                  <?php if (!empty($ev['location'])): ?>
                    <div style="font-size: 0.75rem; color: #718096; margin-top: 1px;">
                      <?= View::e($ev['location']) ?>
                    </div>
                  <?php endif; ?>
                </td>

                <td>
                  <span class="status-pill" style="background: #EDF2F7; color: #2D3748; font-weight: 600;">
                    <?= (int)$ev['photo_count'] ?> Photos
                  </span>
                </td>

                <td>
                  <form action="<?= View::url('/admin/gallery/index.php') ?>" method="POST" style="display: inline;">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="action" value="toggle_publish">
                    <input type="hidden" name="id" value="<?= $ev['id'] ?>">
                    <button type="submit" class="status-pill <?= !empty($ev['is_published']) ? 'status-active' : 'status-inactive' ?>" style="cursor: pointer; border: none;" title="Click to toggle visibility">
                      <?= !empty($ev['is_published']) ? 'Published' : 'Hidden / Draft' ?>
                    </button>
                  </form>
                </td>

                <td style="text-align: right; white-space: nowrap;">
                  <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                    <a href="<?= View::url('/admin/gallery/edit.php?id=' . $ev['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
                      Edit &amp; Photos
                    </a>

                    <form action="<?= View::url('/admin/gallery/index.php') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete &quot;<?= addslashes(View::e($ev['title'])) ?>&quot; and all its photographs? This action cannot be undone.');">
                      <?= Csrf::field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $ev['id'] ?>">
                      <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" title="Delete Event">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align: center; padding: 2.5rem 1rem; color: #718096;">
                No events found matching your filter. Click <strong>&quot;+ Add New Event &amp; Upload Photos&quot;</strong> above to create your first event!
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
