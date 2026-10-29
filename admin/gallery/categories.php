<?php
/**
 * Akash Event Management - Manage Event Categories
 * Clean Apple-Standard Administration Interface
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Gallery;
use App\Models\Event;

$pageTitle = 'Manage Event Categories | Akash Event Management Admin';
$pageHeaderTitle = 'Event Categories';
$currentAdminNav = 'categories';

$editId = (int)($_GET['edit'] ?? 0);
$editCategory = null;
if ($editId > 0) {
    $editCategory = Gallery::getCategoryById($editId);
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
        header("Location: " . View::url('/admin/gallery/categories.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!empty($name)) {
            $catId = Gallery::findOrCreateCategory($name, $description);
            if ($catId > 0) {
                flash('success', "Category &quot;{$name}&quot; added successfully.");
            } else {
                flash('error', 'Could not add category.');
            }
        } else {
            flash('error', 'Please enter a category name.');
        }
        header("Location: " . View::url('/admin/gallery/categories.php'));
        exit;
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($id > 0 && !empty($name)) {
            Gallery::updateCategory($id, [
                'name' => $name,
                'description' => $description,
            ]);
            flash('success', "Category &quot;{$name}&quot; updated successfully.");
        } else {
            flash('error', 'Category name cannot be empty.');
        }
        header("Location: " . View::url('/admin/gallery/categories.php'));
        exit;
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Gallery::deleteCategory($id);
            flash('success', 'Category removed successfully. Associated events and photos were preserved.');
        }
        header("Location: " . View::url('/admin/gallery/categories.php'));
        exit;
    }
}

$categories = Gallery::getCategories(false);
$events = Event::getAll(null, null, false);

// Calculate event counts per category
$categoryEventCounts = [];
foreach ($events as $ev) {
    $cId = (int)($ev['category_id'] ?? 0);
    if (!isset($categoryEventCounts[$cId])) {
        $categoryEventCounts[$cId] = 0;
    }
    $categoryEventCounts[$cId]++;
}

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 860px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h1 class="admin-card-title">Manage Event Categories</h1>
        <p class="admin-card-subtitle">
          Organize celebrations into distinct collections for website browsing.
        </p>
      </div>
      <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
        &larr; Back to Events
      </a>
    </div>

    <!-- Category Creation or Editing Form -->
    <div class="form-section">
      <h2 class="form-section-title">
        <?= $editCategory ? 'Edit Category: ' . View::e($editCategory['name']) : 'Add New Category' ?>
      </h2>
      <p class="form-section-desc">
        <?= $editCategory ? 'Update this category title and description.' : 'Enter a new category name to appear in event dropdowns and public filters.' ?>
      </p>

      <form action="<?= View::url('/admin/gallery/categories.php') ?>" method="POST" novalidate>
        <?= Csrf::field() ?>
        <input type="hidden" name="action" value="<?= $editCategory ? 'update' : 'create' ?>">
        <?php if ($editCategory): ?>
          <input type="hidden" name="id" value="<?= $editCategory['id'] ?>">
        <?php endif; ?>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="category_name">Category Name <span class="required">*</span></label>
            <input type="text" id="category_name" name="name" class="form-input" 
                   placeholder="e.g. Haldi Ceremony, Baby Shower, Corporate" 
                   value="<?= View::e($editCategory['name'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="category_desc">Description (Optional)</label>
            <input type="text" id="category_desc" name="description" class="form-input" 
                   placeholder="Brief overview of celebrations in this category..." 
                   value="<?= View::e($editCategory['description'] ?? '') ?>">
          </div>
        </div>

        <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.25rem;">
          <button type="submit" class="admin-btn admin-btn-gold">
            <?= $editCategory ? 'Update Category' : '+ Add Category' ?>
          </button>
          <?php if ($editCategory): ?>
            <a href="<?= View::url('/admin/gallery/categories.php') ?>" class="admin-btn admin-btn-secondary">
              Cancel Edit
            </a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- Existing Categories List -->
    <div>
      <h2 class="form-section-title" style="margin-bottom: 0.25rem;">Existing Categories (<?= count($categories) ?>)</h2>
      <p class="form-section-desc" style="margin-bottom: 1rem;">
        Categories actively available for event tagging and public gallery filtering.
      </p>

      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Category Name</th>
              <th>Public Slug</th>
              <th>Events</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $cat): ?>
              <?php $evCount = $categoryEventCounts[$cat['id']] ?? 0; ?>
              <tr>
                <td>
                  <strong style="color: var(--admin-primary); font-size: 0.9rem;">
                    <?= View::e($cat['name']) ?>
                  </strong>
                  <?php if (!empty($cat['description'])): ?>
                    <div style="font-size: 0.775rem; color: var(--admin-text-secondary); margin-top: 2px;">
                      <?= View::e($cat['description']) ?>
                    </div>
                  <?php endif; ?>
                </td>
                <td>
                  <code style="font-size: 0.775rem; background: #F2F2F7; padding: 2px 6px; border-radius: 4px; color: #5856D6;">
                    <?= View::e($cat['slug']) ?>
                  </code>
                </td>
                <td>
                  <span class="status-pill status-read">
                    <?= $evCount ?> <?= $evCount === 1 ? 'Event' : 'Events' ?>
                  </span>
                </td>
                <td style="text-align: right;">
                  <div style="display: inline-flex; gap: 0.4rem;">
                    <a href="<?= View::url('/admin/gallery/categories.php?edit=' . $cat['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
                      Edit
                    </a>

                    <form action="<?= View::url('/admin/gallery/categories.php') ?>" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Remove category &quot;<?= addslashes(View::e($cat['name'])) ?>&quot;? Events in this category will become Uncategorized.');">
                      <?= Csrf::field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                      <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">
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

  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
