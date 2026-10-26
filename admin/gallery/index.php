<?php
/**
 * Akash Event Management - Admin Gallery Media List
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Gallery;

$pageTitle = 'Manage Gallery Media | Akash Event Management Admin';
$pageHeaderTitle = 'Gallery & Portfolio Media';
$currentAdminNav = 'gallery';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $action = $_POST['action'] ?? '';
        $id = (int) ($_POST['id'] ?? 0);

        if ($action === 'toggle_publish' && $id > 0) {
            Gallery::togglePublish($id);
            flash('success', 'Media publication status updated.');
        } elseif ($action === 'toggle_featured' && $id > 0) {
            Gallery::toggleFeatured($id);
            flash('success', 'Media featured status updated.');
        } elseif ($action === 'delete' && $id > 0) {
            $item = Gallery::getById($id);
            if ($item) {
                // Delete physical uploaded file if in uploads directory
                \App\Helpers\Uploader::deleteFile($item['file_path']);
                Gallery::delete($id);
                flash('success', 'Media item removed successfully.');
            }
        }
    }
    header("Location: " . View::url('/admin/gallery/index.php'));
    exit;
}

$categories = Gallery::getCategories(false);
$filterCategory = trim($_GET['category'] ?? '');

$mediaList = Gallery::getMedia(
    !empty($filterCategory) ? (int)$filterCategory : null,
    null,
    false
);

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card">
    <div class="admin-card-header">
      <div>
        <h2 class="admin-card-title">Gallery Media Items (<?= count($mediaList) ?>)</h2>
        <p style="font-size: 0.85rem; color: #718096; margin-top: 2px;">
          Manage photos, stage decoration captures, and video showcases displayed on the public gallery.
        </p>
      </div>

      <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
        <!-- Category Filter -->
        <form action="<?= View::url('/admin/gallery/index.php') ?>" method="GET" style="display: flex; gap: 0.5rem;">
          <select name="category" onchange="this.form.submit()" class="form-select" style="padding: 0.45rem 0.75rem; font-size: 0.85rem; width: auto;">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= $filterCategory == $cat['id'] ? 'selected' : '' ?>>
                <?= View::e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>

        <a href="<?= View::url('/admin/gallery/upload.php') ?>" class="admin-btn admin-btn-primary">
          + Upload New Media
        </a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 80px;">Thumbnail</th>
            <th>Media Title & Category</th>
            <th>Type</th>
            <th>Class</th>
            <th>Featured</th>
            <th>Status</th>
            <th style="text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($mediaList)): ?>
            <?php foreach ($mediaList as $item): ?>
              <tr>
                <td>
                  <?php if ($item['media_type'] === 'video'): ?>
                    <div style="width: 56px; height: 42px; background: #1D1D1F; border-radius: var(--radius-xs); display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 0.65rem;">
                      VIDEO
                    </div>
                  <?php else: ?>
                    <img src="<?= View::asset($item['file_path']) ?>" alt="" width="56" height="42" style="object-fit: cover; border-radius: var(--radius-xs);">
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?= View::e($item['title']) ?></strong>
                  <div style="font-size: 0.75rem; color: #718096;">
                    Category: <?= View::e($item['category_name'] ?? 'Uncategorized') ?>
                  </div>
                </td>
                <td>
                  <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #4A5568;">
                    <?= $item['media_type'] ?>
                  </span>
                </td>
                <td>
                  <?php if (!empty($item['is_concept'])): ?>
                    <span class="status-pill status-draft">Concept</span>
                  <?php else: ?>
                    <span class="status-pill status-completed">Authentic</span>
                  <?php endif; ?>
                </td>
                <td>
                  <form action="<?= View::url('/admin/gallery/index.php') ?>" method="POST" style="display: inline;">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="action" value="toggle_featured">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm" style="font-size: 0.72rem; padding: 0.2rem 0.5rem;">
                      <?= !empty($item['is_featured']) ? '★ Featured' : '☆ Normal' ?>
                    </button>
                  </form>
                </td>
                <td>
                  <span class="status-pill <?= !empty($item['is_published']) ? 'status-published' : 'status-draft' ?>">
                    <?= !empty($item['is_published']) ? 'Published' : 'Hidden' ?>
                  </span>
                </td>
                <td style="text-align: right;">
                  <div style="display: inline-flex; gap: 0.5rem;">
                    <!-- Toggle Publish -->
                    <form action="<?= View::url('/admin/gallery/index.php') ?>" method="POST" style="display: inline;">
                      <?= Csrf::field() ?>
                      <input type="hidden" name="action" value="toggle_publish">
                      <input type="hidden" name="id" value="<?= $item['id'] ?>">
                      <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm">
                        <?= !empty($item['is_published']) ? 'Unpublish' : 'Publish' ?>
                      </button>
                    </form>

                    <!-- Edit -->
                    <a href="<?= View::url('/admin/gallery/edit.php?id=' . $item['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
                      Edit
                    </a>

                    <!-- Delete -->
                    <form action="<?= View::url('/admin/gallery/index.php') ?>" method="POST" style="display: inline;">
                      <?= Csrf::field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $item['id'] ?>">
                      <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" data-confirm="Are you sure you want to permanently delete this media item?">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align: center; color: #718096; padding: 2rem;">
                No media records found in this category.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
