<?php
/**
 * Akash Event Management - Create Event & Multi-Photo Upload
 * Clean Apple-Standard Administration Interface
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Helpers\Uploader;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Event;
use App\Models\Gallery;

$pageTitle = 'Add New Event | Akash Event Management Admin';
$pageHeaderTitle = 'Add New Event';
$currentAdminNav = 'gallery';

$categories = Gallery::getCategories(false);
$errors = [];
$formData = [
    'title' => '',
    'category_id' => '',
    'event_type' => '',
    'event_date' => date('Y-m-d'),
    'location' => 'Hosur',
    'description' => '',
    'is_published' => '1',
    'is_featured' => '0',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        $errors['csrf'] = ['Security token expired. Please refresh the page and try again.'];
    } else {
        $catIdVal = !empty($_POST['category_id']) && $_POST['category_id'] !== 'custom' ? (int)$_POST['category_id'] : null;
        $customCatVal = trim($_POST['custom_category'] ?? '');

        if (!empty($customCatVal)) {
            $createdCatId = Gallery::findOrCreateCategory($customCatVal);
            if ($createdCatId > 0) {
                $catIdVal = $createdCatId;
            }
        }

        $formData = [
            'title' => trim($_POST['title'] ?? ''),
            'category_id' => $catIdVal,
            'event_type' => trim($_POST['event_type'] ?? ''),
            'event_date' => !empty($_POST['event_date']) ? trim($_POST['event_date']) : null,
            'location' => trim($_POST['location'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'is_published' => !empty($_POST['is_published']) ? 1 : 0,
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
        ];

        $validator = new Validator($formData);
        $isValid = $validator->validate([
            'title' => ['required', 'min:3', 'max:150'],
        ]);

        $hasFiles = false;
        if (isset($_FILES['photos']['name']) && is_array($_FILES['photos']['name'])) {
            foreach ($_FILES['photos']['error'] as $err) {
                if ($err !== UPLOAD_ERR_NO_FILE) {
                    $hasFiles = true;
                    break;
                }
            }
        }

        if (!$hasFiles) {
            $errors['photos'] = ['Please select 5 to 10 event photographs to upload.'];
            $isValid = false;
        }

        if ($isValid) {
            $eventId = Event::create($formData);

            if ($eventId > 0) {
                $uploadResult = Uploader::uploadMultipleImages($_FILES['photos'], 'gallery', true);

                $uploadedCount = 0;
                foreach ($uploadResult['uploaded'] as $photo) {
                    Event::addPhoto($eventId, [
                        'category_id' => $formData['category_id'],
                        'title' => $formData['title'],
                        'caption' => $formData['description'],
                        'media_type' => 'image',
                        'file_path' => $photo['file_path'],
                        'thumbnail_path' => $photo['thumbnail_path'],
                        'is_featured' => ($uploadedCount === 0) ? 1 : 0,
                        'display_order' => $uploadedCount + 1,
                    ]);
                    $uploadedCount++;
                }

                if ($uploadedCount > 0) {
                    flash('success', "Event &quot;{$formData['title']}&quot; created with {$uploadedCount} photographs successfully.");
                    header("Location: " . View::url('/admin/gallery/index.php'));
                    exit;
                } else {
                    $errors['photos'] = !empty($uploadResult['errors']) ? $uploadResult['errors'] : ['Could not upload the selected photographs.'];
                }
            } else {
                $errors['db'] = ['Failed to create event in database.'];
            }
        } else {
            $errors = array_merge($errors, $validator->getErrors());
        }
    }
}

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 820px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h1 class="admin-card-title">Add New Event</h1>
        <p class="admin-card-subtitle">
          Create an event portfolio entry and upload 5 to 10 photographs.
        </p>
      </div>
      <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
        &larr; Back to Events
      </a>
    </div>

    <?php if (!empty($errors['csrf']) || !empty($errors['db'])): ?>
      <div class="alert alert-error">
        <?= View::e($errors['csrf'][0] ?? $errors['db'][0] ?? 'An error occurred.') ?>
      </div>
    <?php endif; ?>

    <form action="<?= View::url('/admin/gallery/upload.php') ?>" method="POST" enctype="multipart/form-data" novalidate>
      <?= Csrf::field() ?>

      <!-- 1. Event Details -->
      <div class="form-section">
        <h2 class="form-section-title">Event Details</h2>
        <p class="form-section-desc">Basic information displayed on the public gallery.</p>

        <div class="form-group">
          <label class="form-label" for="title">Event Name / Stage Title <span class="required">*</span></label>
          <input type="text" id="title" name="title" class="form-input" 
                 placeholder="e.g. Royal Wedding &amp; Reception" 
                 value="<?= View::e($formData['title']) ?>" required>
          <?php if (!empty($errors['title'])): ?>
            <span class="form-error"><?= View::e($errors['title'][0]) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
              <label class="form-label" for="category_id" style="margin-bottom: 0;">Category <span class="required">*</span></label>
              <button type="button" id="btn-toggle-custom-cat" style="background: none; border: none; color: #0071E3; font-size: 0.775rem; font-weight: 500; cursor: pointer; padding: 0;">
                + Type New Category
              </button>
            </div>
            <select id="category_id" name="category_id" class="form-select">
              <option value="">Choose a Category</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $formData['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                  <?= View::e($cat['name']) ?>
                </option>
              <?php endforeach; ?>
              <option value="custom">+ Type New Category...</option>
            </select>

            <div id="custom-cat-box" style="display: none; margin-top: 0.5rem;">
              <input type="text" id="custom_category" name="custom_category" class="form-input" 
                     placeholder="Enter new category (e.g. Haldi Ceremony, Baby Shower)">
              <span class="form-help">Automatically creates and assigns this new category.</span>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="event_type">Subtitle / Theme</label>
            <input type="text" id="event_type" name="event_type" class="form-input" 
                   placeholder="e.g. Wedding Stage Decoration" 
                   value="<?= View::e($formData['event_type']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" class="form-input" 
                   value="<?= View::e($formData['event_date']) ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="location">Celebration Venue / Location</label>
            <input type="text" id="location" name="location" class="form-input" 
                   placeholder="e.g. Hosur, Tamil Nadu" 
                   value="<?= View::e($formData['location']) ?>">
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="description">Description (Optional)</label>
          <textarea id="description" name="description" class="form-textarea" 
                    placeholder="Brief description of floral arrangements, stage backdrops, lighting, etc..."><?= View::e($formData['description']) ?></textarea>
        </div>
      </div>

      <!-- 2. Photographs -->
      <div class="form-section">
        <h2 class="form-section-title">Photographs <span class="required">*</span></h2>
        <p class="form-section-desc">Select 5 to 10 photographs for the interactive lightbox album.</p>

        <div class="apple-dropzone" id="dropzone-box">
          <input type="file" id="photos-input" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" required>
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
          </svg>
          <div class="apple-dropzone-title" id="dropzone-title">Click to Select Photographs</div>
          <div class="apple-dropzone-subtitle">JPG, PNG or WEBP &bull; Select 5 to 10 images</div>
        </div>

        <?php if (!empty($errors['photos'])): ?>
          <div style="margin-top: 0.5rem;">
            <?php foreach ($errors['photos'] as $err): ?>
              <span class="form-error"><?= View::e($err) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div id="file-counter" style="display: none; margin-top: 0.85rem; font-size: 0.825rem; font-weight: 600; color: #1D1D1F;"></div>
        <div id="preview-grid" class="photos-preview-strip"></div>
      </div>

      <!-- 3. Publishing Settings -->
      <div class="form-section">
        <h2 class="form-section-title">Publishing</h2>
        <p class="form-section-desc">Control visibility on the public website.</p>

        <div class="setting-row">
          <div class="setting-info">
            <span class="setting-title">Publish on Website</span>
            <span class="setting-desc">Make this event and its photographs visible on the public Gallery page.</span>
          </div>
          <label class="apple-switch">
            <input type="checkbox" name="is_published" value="1" <?= !empty($formData['is_published']) ? 'checked' : '' ?>>
            <span class="switch-slider"></span>
          </label>
        </div>

        <div class="setting-row">
          <div class="setting-info">
            <span class="setting-title">Feature on Homepage</span>
            <span class="setting-desc">Showcase this celebration in the homepage gallery highlights.</span>
          </div>
          <label class="apple-switch">
            <input type="checkbox" name="is_featured" value="1" <?= !empty($formData['is_featured']) ? 'checked' : '' ?>>
            <span class="switch-slider"></span>
          </label>
        </div>
      </div>

      <div style="margin-top: 2rem; display: flex; gap: 0.75rem; align-items: center;">
        <button type="submit" class="admin-btn admin-btn-gold admin-btn-lg">
          Upload &amp; Save Event
        </button>
        <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-lg">
          Cancel
        </a>
      </div>
    </form>
  </div>

  <script>
    const photosInput = document.getElementById('photos-input');
    const previewGrid = document.getElementById('preview-grid');
    const fileCounter = document.getElementById('file-counter');
    const dropzoneTitle = document.getElementById('dropzone-title');

    if (photosInput) {
      photosInput.addEventListener('change', () => {
        previewGrid.innerHTML = '';
        const files = Array.from(photosInput.files);
        if (files.length > 0) {
          dropzoneTitle.textContent = `${files.length} Photo${files.length === 1 ? '' : 's'} Selected`;
          fileCounter.style.display = 'block';
          fileCounter.innerHTML = `Selected ${files.length} photographs (Photo 1 will be album cover)`;

          files.forEach((file, idx) => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
              const card = document.createElement('div');
              card.className = 'preview-thumb';
              card.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                ${idx === 0 ? '<span class="badge-cover">Cover</span>' : ''}
              `;
              previewGrid.appendChild(card);
            };
            reader.readAsDataURL(file);
          });
        } else {
          dropzoneTitle.textContent = 'Click to Select Photographs';
          fileCounter.style.display = 'none';
        }
      });
    }
    // Custom category toggle logic
    const catSelect = document.getElementById('category_id');
    const customBox = document.getElementById('custom-cat-box');
    const customInput = document.getElementById('custom_category');
    const toggleCatBtn = document.getElementById('btn-toggle-custom-cat');

    function openCustomCat() {
      customBox.style.display = 'block';
      catSelect.value = 'custom';
      customInput.focus();
    }

    if (catSelect) {
      catSelect.addEventListener('change', () => {
        if (catSelect.value === 'custom') {
          openCustomCat();
        } else if (catSelect.value !== '') {
          if (!customInput.value.trim()) {
            customBox.style.display = 'none';
          }
        }
      });
    }

    if (toggleCatBtn) {
      toggleCatBtn.addEventListener('click', openCustomCat);
    }
  </script>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
