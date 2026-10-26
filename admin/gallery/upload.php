<?php
/**
 * Akash Event Management - Upload New Gallery Media
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Helpers\Uploader;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Gallery;

$pageTitle = 'Upload Gallery Media | Akash Event Management Admin';
$pageHeaderTitle = 'Upload New Media';
$currentAdminNav = 'gallery';

$categories = Gallery::getCategories(false);
$errors = [];
$formData = [
    'title' => '',
    'caption' => '',
    'category_id' => '',
    'media_type' => 'image',
    'display_order' => '0',
    'is_featured' => '0',
    'is_concept' => '0',
    'is_published' => '1',
];

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

        if (!isset($_FILES['media_file']) || $_FILES['media_file']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['media_file'] = ['Please select a media file to upload.'];
            $isValid = false;
        }

        if ($isValid) {
            $uploadResult = null;
            if ($formData['media_type'] === 'video') {
                $uploadResult = Uploader::uploadVideo($_FILES['media_file'], 'videos');
            } else {
                $uploadResult = Uploader::uploadImage($_FILES['media_file'], 'gallery');
            }

            if ($uploadResult['success']) {
                $formData['file_path'] = $uploadResult['file_path'];
                $formData['thumbnail_path'] = $uploadResult['thumbnail_path'] ?? null;

                $mediaId = Gallery::create($formData);
                if ($mediaId > 0) {
                    flash('success', 'Media uploaded and published successfully!');
                    header("Location: " . View::url('/admin/gallery/index.php'));
                    exit;
                } else {
                    $errors['db'] = ['Failed to store media record in database.'];
                }
            } else {
                $errors['media_file'] = [$uploadResult['error']];
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
      <h2 class="admin-card-title">Upload Stage Photograph or Video</h2>
      <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
        &larr; Back to Gallery
      </a>
    </div>

    <?php if (!empty($errors['csrf']) || !empty($errors['db'])): ?>
      <div class="alert alert-error">
        <?= View::e($errors['csrf'][0] ?? $errors['db'][0] ?? 'An error occurred.') ?>
      </div>
    <?php endif; ?>

    <form action="<?= View::url('/admin/gallery/upload.php') ?>" method="POST" enctype="multipart/form-data" novalidate>
      <?= Csrf::field() ?>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="title">Media Title *</label>
          <input type="text" id="title" name="title" class="form-input" required value="<?= View::e($formData['title']) ?>" placeholder="e.g. Royal Gold Monogram Wedding Stage">
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
        <textarea id="caption" name="caption" class="form-input" style="min-height: 80px;" placeholder="e.g. Triple curved arches with garden roses, chandeliers, and fluted love seat..."><?= View::e($formData['caption']) ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="media_type">Media Format</label>
          <select id="media_type" name="media_type" class="form-select">
            <option value="image" <?= $formData['media_type'] === 'image' ? 'selected' : '' ?>>Photograph / Image (JPG, PNG, WebP)</option>
            <option value="video" <?= $formData['media_type'] === 'video' ? 'selected' : '' ?>>Event Video Clip (MP4, MOV)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="media_file">Select File *</label>
          <input type="file" id="media_file" name="media_file" required class="form-input" data-preview-target="media-preview" accept="image/*,video/mp4,video/quicktime">
          <?php if (!empty($errors['media_file'])): ?>
            <div class="form-error"><?= View::e($errors['media_file'][0]) ?></div>
          <?php endif; ?>
        </div>
      </div>

      <img id="media-preview" src="" alt="Preview" style="display: none; max-width: 240px; margin-bottom: 1.5rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="display_order">Sort Order Number</label>
          <input type="number" id="display_order" name="display_order" class="form-input" value="<?= View::e($formData['display_order']) ?>">
        </div>

        <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1.5rem;">
          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
            <input type="checkbox" name="is_published" value="1" <?= !empty($formData['is_published']) ? 'checked' : '' ?>>
            <strong>Publish in Public Gallery</strong>
          </label>

          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
            <input type="checkbox" name="is_featured" value="1" <?= !empty($formData['is_featured']) ? 'checked' : '' ?>>
            <span>Feature on Homepage Showcase</span>
          </label>

          <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
            <input type="checkbox" name="is_concept" value="1" <?= !empty($formData['is_concept']) ? 'checked' : '' ?>>
            <span style="color: #718096;">Mark as Design Concept / Editorial Preview</span>
          </label>
        </div>
      </div>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary">Cancel</a>
        <button type="submit" class="admin-btn admin-btn-primary">Upload & Publish</button>
      </div>
    </form>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
