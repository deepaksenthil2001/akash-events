<?php
/**
 * Akash Event Management - Edit Event & Manage Photos
 * Clean Apple-Standard Administration Interface
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Helpers\Uploader;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Event;
use App\Models\Gallery;

$id = (int)($_GET['id'] ?? 0);
$event = Event::getById($id);

if (!$event) {
    flash('error', 'Event record not found.');
    header("Location: " . View::url('/admin/gallery/index.php'));
    exit;
}

$pageTitle = 'Edit Event | Akash Event Management Admin';
$pageHeaderTitle = 'Edit Event';
$currentAdminNav = 'gallery';

$categories = Gallery::getCategories(false);
$photos = Event::getPhotos($id);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please refresh the page and try again.');
        header("Location: " . View::url('/admin/gallery/edit.php?id=' . $id));
        exit;
    }

    $action = $_POST['action'] ?? 'update_event';

    if ($action === 'update_event') {
        $catIdVal = !empty($_POST['category_id']) && $_POST['category_id'] !== 'custom' ? (int)$_POST['category_id'] : null;
        $customCatVal = trim($_POST['custom_category'] ?? '');

        if (!empty($customCatVal)) {
            $createdCatId = Gallery::findOrCreateCategory($customCatVal);
            if ($createdCatId > 0) {
                $catIdVal = $createdCatId;
            }
        }

        $updateData = [
            'title' => trim($_POST['title'] ?? ''),
            'category_id' => $catIdVal,
            'event_type' => trim($_POST['event_type'] ?? ''),
            'event_date' => !empty($_POST['event_date']) ? trim($_POST['event_date']) : null,
            'location' => trim($_POST['location'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'is_published' => !empty($_POST['is_published']) ? 1 : 0,
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
        ];

        $validator = new Validator($updateData);
        if ($validator->validate(['title' => ['required', 'min:3', 'max:150']])) {
            Event::update($id, $updateData);
            flash('success', 'Event details saved successfully.');
            header("Location: " . View::url('/admin/gallery/edit.php?id=' . $id));
            exit;
        } else {
            $errors = $validator->getErrors();
        }
    } elseif ($action === 'set_cover') {
        $photoPath = trim($_POST['photo_path'] ?? '');
        if (!empty($photoPath)) {
            Event::setCoverImage($id, $photoPath);
            flash('success', 'Album cover photograph updated.');
        }
        header("Location: " . View::url('/admin/gallery/edit.php?id=' . $id));
        exit;
    } elseif ($action === 'delete_photo') {
        $photoId = (int)($_POST['photo_id'] ?? 0);
        if ($photoId > 0) {
            Event::removePhoto($id, $photoId);
            flash('success', 'Photograph removed from event.');
        }
        header("Location: " . View::url('/admin/gallery/edit.php?id=' . $id));
        exit;
    } elseif ($action === 'add_photos') {
        if (isset($_FILES['new_photos']) && !empty($_FILES['new_photos']['name'][0])) {
            $result = Uploader::uploadMultipleImages($_FILES['new_photos'], 'gallery', true);
            $added = 0;
            foreach ($result['uploaded'] as $p) {
                Event::addPhoto($id, [
                    'category_id' => $event['category_id'],
                    'title' => $event['title'],
                    'caption' => $event['description'],
                    'media_type' => 'image',
                    'file_path' => $p['file_path'],
                    'thumbnail_path' => $p['thumbnail_path'],
                    'is_featured' => 0,
                    'display_order' => count($photos) + $added + 1,
                ]);
                $added++;
            }
            flash('success', "{$added} new photographs added to this event.");
        }
        header("Location: " . View::url('/admin/gallery/edit.php?id=' . $id));
        exit;
    }
}

// Refresh event data
$event = Event::getById($id);
$photos = Event::getPhotos($id);

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card" style="max-width: 860px; margin: 0 auto;">
    <div class="admin-card-header">
      <div>
        <h1 class="admin-card-title">Edit Event</h1>
        <p class="admin-card-subtitle">
          Managing &quot;<?= View::e($event['title']) ?>&quot; &bull; <?= count($photos) ?> photographs uploaded
        </p>
      </div>
      <a href="<?= View::url('/admin/gallery/index.php') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
        &larr; Back to Events
      </a>
    </div>

    <!-- Form for Event Details -->
    <form action="<?= View::url('/admin/gallery/edit.php?id=' . $id) ?>" method="POST" novalidate>
      <?= Csrf::field() ?>
      <input type="hidden" name="action" value="update_event">

      <!-- 1. Event Details -->
      <div class="form-section">
        <h2 class="form-section-title">Event Details</h2>
        <p class="form-section-desc">Basic information displayed on the website gallery.</p>

        <div class="form-group">
          <label class="form-label" for="title">Event Name / Stage Title <span class="required">*</span></label>
          <input type="text" id="title" name="title" class="form-input" 
                 value="<?= View::e($event['title']) ?>" required>
          <?php if (!empty($errors['title'])): ?>
            <span class="form-error"><?= View::e($errors['title'][0]) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
              <label class="form-label" for="category_id" style="margin-bottom: 0;">Category</label>
              <button type="button" id="btn-toggle-custom-cat" style="background: none; border: none; color: #0071E3; font-size: 0.775rem; font-weight: 500; cursor: pointer; padding: 0;">
                + Type New Category
              </button>
            </div>
            <select id="category_id" name="category_id" class="form-select">
              <option value="">Choose a Category</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $event['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                  <?= View::e($cat['name']) ?>
                </option>
              <?php endforeach; ?>
              <option value="custom">+ Type New Category...</option>
            </select>

            <div id="custom-cat-box" style="display: none; margin-top: 0.5rem;">
              <input type="text" id="custom_category" name="custom_category" class="form-input" 
                     placeholder="Enter new category name (e.g. Haldi Ceremony, Baby Shower)">
              <span class="form-help">Automatically creates and assigns this new category.</span>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="event_type">Subtitle / Theme</label>
            <input type="text" id="event_type" name="event_type" class="form-input" 
                   value="<?= View::e($event['event_type']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" class="form-input" 
                   value="<?= View::e($event['event_date']) ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="location">Celebration Venue / Location</label>
            <input type="text" id="location" name="location" class="form-input" 
                   value="<?= View::e($event['location']) ?>">
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="description">Description (Optional)</label>
          <textarea id="description" name="description" class="form-textarea"><?= View::e($event['description']) ?></textarea>
        </div>
      </div>

      <!-- 2. Publishing Settings -->
      <div class="form-section">
        <h2 class="form-section-title">Publishing</h2>
        <p class="form-section-desc">Control visibility on the public website.</p>

        <div class="setting-row">
          <div class="setting-info">
            <span class="setting-title">Publish on Website</span>
            <span class="setting-desc">Visible to public visitors on the Gallery page.</span>
          </div>
          <label class="apple-switch">
            <input type="checkbox" name="is_published" value="1" <?= !empty($event['is_published']) ? 'checked' : '' ?>>
            <span class="switch-slider"></span>
          </label>
        </div>

        <div class="setting-row">
          <div class="setting-info">
            <span class="setting-title">Feature on Homepage</span>
            <span class="setting-desc">Showcase in the homepage gallery highlights.</span>
          </div>
          <label class="apple-switch">
            <input type="checkbox" name="is_featured" value="1" <?= !empty($event['is_featured']) ? 'checked' : '' ?>>
            <span class="switch-slider"></span>
          </label>
        </div>
      </div>

      <div style="margin-top: 1.5rem; margin-bottom: 2.5rem;">
        <button type="submit" class="admin-btn admin-btn-gold admin-btn-lg">
          Save Event Details
        </button>
      </div>
    </form>

    <!-- 3. Photographs Management -->
    <div class="form-section" style="border-top: 1px solid var(--admin-border-subtle); padding-top: 1.75rem;">
      <h2 class="form-section-title">Event Photographs (<?= count($photos) ?>)</h2>
      <p class="form-section-desc">Click &quot;Set Cover&quot; to change the main thumbnail seen by visitors.</p>

      <?php if (!empty($photos)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
          <?php foreach ($photos as $photo): ?>
            <?php $isCover = ($photo['file_path'] === $event['cover_image']); ?>
            <div style="border: 1px solid <?= $isCover ? '#C5A059' : 'var(--admin-border)' ?>; border-radius: var(--admin-radius-sm); overflow: hidden; background: #FFFFFF; display: flex; flex-direction: column;">
              <div style="position: relative; aspect-ratio: 16/11; background: #000000;">
                <img src="<?= View::asset($photo['file_path']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                <?php if ($isCover): ?>
                  <span style="position: absolute; top: 4px; left: 4px; background: #C5A059; color: #FFFFFF; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                    Cover
                  </span>
                <?php endif; ?>
              </div>

              <div style="padding: 0.5rem 0.65rem; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; background: #FAFAFC; border-top: 1px solid var(--admin-border-subtle);">
                <?php if (!$isCover): ?>
                  <form action="<?= View::url('/admin/gallery/edit.php?id=' . $id) ?>" method="POST" style="margin: 0;">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="action" value="set_cover">
                    <input type="hidden" name="photo_path" value="<?= View::e($photo['file_path']) ?>">
                    <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm" style="font-size: 0.72rem; height: 26px; padding: 0 0.5rem;">
                      Set Cover
                    </button>
                  </form>
                <?php else: ?>
                  <span style="font-size: 0.72rem; color: #9A7736; font-weight: 600;">Main Cover</span>
                <?php endif; ?>

                <form action="<?= View::url('/admin/gallery/edit.php?id=' . $id) ?>" method="POST" style="margin: 0;" onsubmit="return confirm('Remove this photograph from the event?');">
                  <?= Csrf::field() ?>
                  <input type="hidden" name="action" value="delete_photo">
                  <input type="hidden" name="photo_id" value="<?= $photo['id'] ?>">
                  <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" style="font-size: 0.72rem; height: 26px; padding: 0 0.5rem;">
                    Delete
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p style="padding: 1.5rem; text-align: center; background: #FAFAFC; border-radius: var(--admin-radius-sm); color: var(--admin-text-secondary); margin-bottom: 1.5rem;">
          No photographs in this event yet.
        </p>
      <?php endif; ?>

      <!-- 4. Add More Photos -->
      <div style="background: #FAFAFC; padding: 1.25rem 1.5rem; border-radius: var(--admin-radius-md); border: 1px dashed var(--admin-border);">
        <h3 style="font-size: 0.9rem; font-weight: 600; color: var(--admin-primary); margin: 0 0 0.25rem 0;">
          Add More Photographs
        </h3>
        <p style="font-size: 0.775rem; color: var(--admin-text-secondary); margin: 0 0 1rem 0;">
          Select one or more photographs to add to this event.
        </p>
        <form action="<?= View::url('/admin/gallery/edit.php?id=' . $id) ?>" method="POST" enctype="multipart/form-data">
          <?= Csrf::field() ?>
          <input type="hidden" name="action" value="add_photos">

          <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <input type="file" name="new_photos[]" multiple accept="image/jpeg,image/png,image/webp" class="form-input" style="flex: 1; min-width: 240px;" required>
            <button type="submit" class="admin-btn admin-btn-primary">
              Upload Photos
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
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
