<?php
/**
 * Akash Event Management - Add New Service
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Helpers\Uploader;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Service;

$pageTitle = 'Add New Service | Akash Event Management Admin';
$pageHeaderTitle = 'Add New Service';
$currentAdminNav = 'services';

$errors = [];
$formData = [
    'title' => '',
    'slug' => '',
    'short_description' => '',
    'full_description' => '',
    'features_list' => '',
    'display_order' => '0',
    'is_published' => '1',
    'is_featured' => '0',
    'meta_title' => '',
    'meta_description' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        $errors['csrf'] = ['Security token invalid. Please refresh and try again.'];
    } else {
        $formData = [
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'short_description' => trim($_POST['short_description'] ?? ''),
            'full_description' => trim($_POST['full_description'] ?? ''),
            'features_list' => trim($_POST['features_list'] ?? ''),
            'display_order' => (int) ($_POST['display_order'] ?? 0),
            'is_published' => !empty($_POST['is_published']) ? 1 : 0,
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
        ];

        $validator = new Validator($formData);
        $isValid = $validator->validate([
            'title' => ['required', 'min:3', 'max:120'],
            'short_description' => ['required', 'min:10', 'max:255'],
            'full_description' => ['required', 'min:20'],
        ]);

        if ($isValid) {
            $coverImagePath = null;
            
            // Handle image upload
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadRes = Uploader::uploadImage($_FILES['cover_image'], 'services');
                if ($uploadRes['success']) {
                    $coverImagePath = $uploadRes['file_path'];
                } else {
                    $errors['cover_image'] = [$uploadRes['error']];
                }
            } else {
                // If no custom upload, default to sample service image
                $coverImagePath = '/assets/images/services/wedding.jpg';
            }

            if (empty($errors)) {
                $formData['cover_image'] = $coverImagePath;
                $serviceId = Service::create($formData);

                if ($serviceId > 0) {
                    flash('success', 'New service created successfully!');
                    header("Location: " . View::url('/admin/services/index.php'));
                    exit;
                } else {
                    $errors['db'] = ['Failed to create service in database.'];
                }
            }
        } else {
            $errors = $validator->getErrors();
        }
    }
}

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 860px; margin: 0 auto;">
    <div class="admin-card-header">
      <h2 class="admin-card-title">Create New Service Offering</h2>
      <a href="<?= View::url('/admin/services/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
        &larr; Back to Services List
      </a>
    </div>

    <?php if (!empty($errors['csrf']) || !empty($errors['db'])): ?>
      <div class="alert alert-error">
        <?= View::e($errors['csrf'][0] ?? $errors['db'][0] ?? 'An error occurred.') ?>
      </div>
    <?php endif; ?>

    <form action="<?= View::url('/admin/services/create.php') ?>" method="POST" enctype="multipart/form-data" novalidate>
      <?= Csrf::field() ?>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="title">Service Title *</label>
          <input type="text" id="title" name="title" class="form-input" required value="<?= View::e($formData['title']) ?>" placeholder="e.g. Traditional Mandapam Floral Setup">
          <?php if (!empty($errors['title'])): ?>
            <div class="form-error"><?= View::e($errors['title'][0]) ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label" for="slug">URL Slug (Optional)</label>
          <input type="text" id="slug" name="slug" class="form-input" value="<?= View::e($formData['slug']) ?>" placeholder="Auto-generated if empty">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="short_description">Short Summary Description *</label>
        <textarea id="short_description" name="short_description" class="form-input" style="min-height: 80px;" required placeholder="Brief 1-2 sentence overview for cards and listings..."><?= View::e($formData['short_description']) ?></textarea>
        <?php if (!empty($errors['short_description'])): ?>
          <div class="form-error"><?= View::e($errors['short_description'][0]) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label class="form-label" for="full_description">Detailed Service Description *</label>
        <textarea id="full_description" name="full_description" class="form-textarea" required placeholder="Comprehensive description of materials, floral varieties, stage styling, and setup execution..."><?= View::e($formData['full_description']) ?></textarea>
        <?php if (!empty($errors['full_description'])): ?>
          <div class="form-error"><?= View::e($errors['full_description'][0]) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label class="form-label" for="features_list">Features Checklist (One per line)</label>
        <textarea id="features_list" name="features_list" class="form-textarea" style="min-height: 100px;" placeholder="Custom floral backdrops with fresh blooms&#10;Royal couple sofa&#10;Chandeliers and spotlights"><?= View::e($formData['features_list']) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label" for="cover_image">Cover Image Upload</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/*" class="form-input" data-preview-target="cover-preview">
        <p style="font-size: 0.75rem; color: #718096; margin-top: 4px;">Recommended: 16:9 or 4:3 high-resolution JPG or PNG (Max 15MB).</p>
        <?php if (!empty($errors['cover_image'])): ?>
          <div class="form-error"><?= View::e($errors['cover_image'][0]) ?></div>
        <?php endif; ?>
        <img id="cover-preview" src="" alt="Preview" style="display: none; max-width: 240px; margin-top: 10px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="display_order">Display Sort Order</label>
          <input type="number" id="display_order" name="display_order" class="form-input" value="<?= View::e($formData['display_order']) ?>">
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 2rem; margin-top: 2rem;">
          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer;">
            <input type="checkbox" name="is_published" value="1" <?= !empty($formData['is_published']) ? 'checked' : '' ?>>
            <strong>Publish Immediately</strong>
          </label>

          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer;">
            <input type="checkbox" name="is_featured" value="1" <?= !empty($formData['is_featured']) ? 'checked' : '' ?>>
            <span>Feature on Homepage</span>
          </label>
        </div>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="<?= View::url('/admin/services/index.php') ?>" class="admin-btn admin-btn-secondary">Cancel</a>
        <button type="submit" class="admin-btn admin-btn-primary">Create Service</button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
