<?php
/**
 * Akash Event Management - Edit Gallery Media
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Helpers\Uploader;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Gallery;

$id = (int)($_GET['id'] ?? 0);
$media = Gallery::getById($id);

if (!$media) {
    flash('error', 'Media record not found.');
    header("Location: " . View::url('/admin/gallery/index.php'));
    exit;
}

$pageTitle = 'Edit Media: ' . $media['title'] . ' | Admin';
$pageHeaderTitle = 'Edit Gallery Media';
$currentAdminNav = 'gallery';

$categories = Gallery::getCategories(false);
$errors = [];
$formData = $media;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        $errors['csrf'] = ['Security token invalid. Please refresh and try again.'];
    } else {
        $formData = [
            'title' => trim($_POST['title'] ?? ''),
            'caption' => trim($_POST['caption'] ?? ''),
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'media_type' => $_POST['media_type'] ?? 'image',
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
            'is_concept' => !empty($_POST['is_concept']) ? 1 : 0,
            'is_published' => !empty($_POST['is_published']) ? 1 : 0,
        ];

        $validator = new Validator($formData);
        $isValid = $validator->validate([
            'title' => ['required', 'min:3', 'max:120'],
        ]);

        if ($isValid) {
            // Optional file replacement
            if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadResult = null;
                if ($formData['media_type'] === 'video') {
                    $uploadResult = Uploader::uploadVideo($_FILES['media_file'], 'videos');
                } else {
                    $uploadResult = Uploader::uploadImage($_FILES['media_file'], 'gallery');
                }

                if ($uploadResult['success']) {
                    // Delete old upload
                    Uploader::deleteFile($media['file_path']);
                    $formData['file_path'] = $uploadResult['file_path'];
                    $formData['thumbnail_path'] = $uploadResult['thumbnail_path'] ?? null;
                } else {
                    $errors['media_file'] = [$uploadResult['error']];
                }
            }

            if (empty($errors)) {
                if (Gallery::update($id, $formData)) {
                    flash('success', 'Media details updated successfully!');
                    header("Location: " . View::url('/admin/gallery/index.php'));
                    exit;
                } else {
                    $errors['db'] = ['Failed to update media record.'];
                }
            }
        } else {
            $errors = array_merge($errors, $validator->getErrors());
        }
    }
}

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 800px; margin: 0 auto;">
    <div class="admin-card-header">
      <h2 class="admin-card-title">Edit Media: <?= View::e($media['title']) ?></h2>
      <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
        &larr; Back to Gallery
      </a>
    </div>

    <?php if (!empty($errors['csrf']) || !empty($errors['db'])): ?>
      <div class="alert alert-error">
        <?= View::e($errors['csrf'][0] ?? $errors['db'][0] ?? 'An error occurred.') ?>
      </div>
    <?php endif; ?>

    <form action="<?= View::url('/admin/gallery/edit.php?id=' . $id) ?>" method="POST" enctype="multipart/form-data" novalidate>
      <?= Csrf::field() ?>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="title">Media Title *</label>
          <input type="text" id="title" name="title" class="form-input" required value="<?= View::e($formData['title']) ?>">
          <?php if (!empty($errors['title'])): ?>
            <div class="form-error"><?= View::e($errors['title'][0]) ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label" for="category_id">Event Category</label>
          <select id="category_id" name="category_id" class="form-select">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= $formData['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                <?= View::e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="caption">Caption / Decoration Details</label>
        <textarea id="caption" name="caption" class="form-input" style="min-height: 80px;"><?= View::e($formData['caption']) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Current Media</label>
        <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1rem;">
          <?php if ($media['media_type'] === 'video'): ?>
            <video src="<?= View::asset($media['file_path']) ?>" controls width="160" style="border-radius: var(--radius-md);"></video>
          <?php else: ?>
            <img src="<?= View::asset($media['file_path']) ?>" alt="" width="140" style="border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
          <?php endif; ?>
          <div>
            <label class="form-label" for="media_file">Replace Media File</label>
            <input type="file" id="media_file" name="media_file" class="form-input" data-preview-target="media-preview" accept="image/*,video/mp4,video/quicktime">
          </div>
        </div>
        <img id="media-preview" src="" alt="New Preview" style="display: none; max-width: 200px; border-radius: var(--radius-md);">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="display_order">Sort Order Number</label>
          <input type="number" id="display_order" name="display_order" class="form-input" value="<?= View::e($formData['display_order']) ?>">
        </div>

        <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1.5rem;">
          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
            <input type="checkbox" name="is_published" value="1" <?= !empty($formData['is_published']) ? 'checked' : '' ?>>
            <strong>Published in Public Gallery</strong>
          </label>

          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
            <input type="checkbox" name="is_featured" value="1" <?= !empty($formData['is_featured']) ? 'checked' : '' ?>>
            <span>Featured on Homepage Showcase</span>
          </label>

          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
            <input type="checkbox" name="is_concept" value="1" <?= !empty($formData['is_concept']) ? 'checked' : '' ?>>
            <span style="color: #718096;">Mark as Design Concept / Editorial Preview</span>
          </label>
        </div>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary">Cancel</a>
        <button type="submit" class="admin-btn admin-btn-primary">Save Changes</button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
