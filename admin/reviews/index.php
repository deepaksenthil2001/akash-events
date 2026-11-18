<?php
/**
 * Akash Event Management - Admin Google Reviews Manager
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Helpers\View;
use App\Models\Review;

Auth::requireLogin();

$user = Auth::user();
$currentAdminNav = 'reviews';
$pageTitle = 'Google Reviews Management';

$errors = [];
$editReview = null;

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        Session::flash('error', 'Security session expired. Please refresh and try again.');
        header('Location: ' . View::url('/admin/reviews/index.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    // ADD REVIEW
    if ($action === 'add') {
        $customerName = trim($_POST['customer_name'] ?? '');
        $reviewText = trim($_POST['review_text'] ?? '');
        $eventType = trim($_POST['event_type'] ?? '');
        $rating = (int) ($_POST['rating'] ?? 5);
        $reviewDate = trim($_POST['review_date'] ?? date('Y-m-d'));
        $displayOrder = (int) ($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if (empty($customerName)) {
            $errors[] = 'Customer name is required.';
        }
        if (empty($reviewText)) {
            $errors[] = 'Review text is required.';
        }

        if (empty($errors)) {
            Review::create([
                'customer_name' => $customerName,
                'rating' => $rating,
                'event_type' => $eventType,
                'review_text' => $reviewText,
                'review_date' => $reviewDate,
                'source' => 'google',
                'is_verified' => 1,
                'is_published' => $isPublished,
                'display_order' => $displayOrder
            ]);

            Session::flash('success', 'Google review added successfully and updated on website.');
            header('Location: ' . View::url('/admin/reviews/index.php'));
            exit;
        }
    }

    // UPDATE REVIEW
    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $customerName = trim($_POST['customer_name'] ?? '');
        $reviewText = trim($_POST['review_text'] ?? '');
        $eventType = trim($_POST['event_type'] ?? '');
        $rating = (int) ($_POST['rating'] ?? 5);
        $reviewDate = trim($_POST['review_date'] ?? null);
        $displayOrder = (int) ($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if (empty($customerName)) {
            $errors[] = 'Customer name is required.';
        }
        if (empty($reviewText)) {
            $errors[] = 'Review text is required.';
        }

        if (empty($errors) && $id > 0) {
            Review::update($id, [
                'customer_name' => $customerName,
                'rating' => $rating,
                'event_type' => $eventType,
                'review_text' => $reviewText,
                'review_date' => $reviewDate,
                'source' => 'google',
                'is_verified' => 1,
                'is_published' => $isPublished,
                'display_order' => $displayOrder
            ]);

            Session::flash('success', 'Review updated successfully.');
            header('Location: ' . View::url('/admin/reviews/index.php'));
            exit;
        }
    }

    // TOGGLE STATUS
    if ($action === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Review::togglePublish($id);
            Session::flash('success', 'Review publication status toggled.');
        }
        header('Location: ' . View::url('/admin/reviews/index.php'));
        exit;
    }

    // DELETE REVIEW
    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Review::delete($id);
            Session::flash('success', 'Review deleted successfully.');
        }
        header('Location: ' . View::url('/admin/reviews/index.php'));
        exit;
    }
}

// Edit Mode
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editReview = Review::getById($editId);
}

$allReviews = Review::getAll();
$stats = Review::getStats();
$googleBusinessUrl = View::setting('google_business_url', 'https://share.google/nUP5Exqa3K1doUEfd');

require_once APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

<div class="admin-topbar">
  <div class="admin-topbar-left">
    <button type="button" class="admin-mobile-toggle" id="adminSidebarToggle" aria-label="Toggle Navigation">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
      </svg>
    </button>
    <div class="admin-page-title">
      <h1>Google Reviews &amp; Testimonials</h1>
      <p>Manage customer reviews shown on the website and direct Google rating link.</p>
    </div>
  </div>

  <div class="admin-topbar-actions">
    <a href="<?= View::e($googleBusinessUrl) ?>" target="_blank" rel="noopener noreferrer" class="admin-btn admin-btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/>
      </svg>
      View Google Business Profile
    </a>
  </div>
</div>

<div class="admin-content-inner">
  <?php require_once APP_ROOT . '/app/Views/components/flash_messages.php'; ?>

  <?php if (!empty($errors)): ?>
    <div style="background: #FEE2E2; border: 1px solid #F87171; color: #991B1B; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem;">
      <strong style="display: block; margin-bottom: 0.25rem;">Please review the following errors:</strong>
      <ul style="margin: 0; padding-left: 1.25rem;">
        <?php foreach ($errors as $err): ?>
          <li><?= View::e($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Stats Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem;">
      <div style="width: 48px; height: 48px; border-radius: 10px; background: #FFF8E7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
        ★
      </div>
      <div>
        <div style="font-size: 1.5rem; font-weight: 700; color: #1E293B;">
          <?= number_format($stats['average'], 1) ?> / 5.0
        </div>
        <div style="font-size: 0.8rem; color: #64748B;">Average Rating Score</div>
      </div>
    </div>

    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem;">
      <div style="width: 48px; height: 48px; border-radius: 10px; background: #ECFDF5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
        ✓
      </div>
      <div>
        <div style="font-size: 1.5rem; font-weight: 700; color: #1E293B;">
          <?= count($allReviews) ?>
        </div>
        <div style="font-size: 0.8rem; color: #64748B;">Total Reviews in Database</div>
      </div>
    </div>

    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem;">
      <div style="width: 48px; height: 48px; border-radius: 10px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
        🌐
      </div>
      <div>
        <div style="font-size: 1.5rem; font-weight: 700; color: #1E293B;">
          <?= count(array_filter($allReviews, fn($r) => !empty($r['is_published']))) ?>
        </div>
        <div style="font-size: 0.8rem; color: #64748B;">Live on Website</div>
      </div>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Reviews Table -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
      <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #1E293B;">Client Reviews List</h3>
        <span style="font-size: 0.8rem; color: #64748B;"><?= count($allReviews) ?> records</span>
      </div>

      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
          <thead>
            <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #475569; font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em;">
              <th style="padding: 0.85rem 1.25rem;">Customer</th>
              <th style="padding: 0.85rem 1rem;">Rating</th>
              <th style="padding: 0.85rem 1rem;">Review Excerpt</th>
              <th style="padding: 0.85rem 1rem;">Status</th>
              <th style="padding: 0.85rem 1.25rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($allReviews)): ?>
              <tr>
                <td colspan="5" style="padding: 2.5rem; text-align: center; color: #94A3B8;">
                  No reviews recorded yet. Use the form on the right to add your first client review.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($allReviews as $rev): ?>
                <tr style="border-bottom: 1px solid #F1F5F9;">
                  <td style="padding: 1rem 1.25rem; vertical-align: top;">
                    <div style="font-weight: 600; color: #1E293B;"><?= View::e($rev['customer_name']) ?></div>
                    <?php if (!empty($rev['event_type'])): ?>
                      <div style="font-size: 0.75rem; color: #D97706; margin-top: 2px;">
                        <?= View::e($rev['event_type']) ?>
                      </div>
                    <?php endif; ?>
                    <div style="font-size: 0.72rem; color: #94A3B8; margin-top: 2px;">
                      <?= !empty($rev['review_date']) ? date('d M Y', strtotime($rev['review_date'])) : 'No date' ?>
                    </div>
                  </td>

                  <td style="padding: 1rem; vertical-align: top; white-space: nowrap;">
                    <div style="color: #F59E0B; font-size: 0.95rem; letter-spacing: 1px;">
                      <?= str_repeat('★', (int)$rev['rating']) ?>
                    </div>
                    <div style="font-size: 0.72rem; color: #64748B;">
                      <?= (int)$rev['rating'] ?>.0 / 5.0
                    </div>
                  </td>

                  <td style="padding: 1rem; vertical-align: top; max-width: 280px; color: #475569; font-size: 0.82rem; line-height: 1.5;">
                    <?= View::e(mb_strimwidth($rev['review_text'], 0, 120, '...')) ?>
                  </td>

                  <td style="padding: 1rem; vertical-align: top; white-space: nowrap;">
                    <?php if (!empty($rev['is_published'])): ?>
                      <span style="display: inline-block; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 600; background: #DCFCE7; color: #166534;">
                        Published
                      </span>
                    <?php else: ?>
                      <span style="display: inline-block; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 600; background: #F1F5F9; color: #64748B;">
                        Hidden
                      </span>
                    <?php endif; ?>
                  </td>

                  <td style="padding: 1rem 1.25rem; vertical-align: top; text-align: right; white-space: nowrap;">
                    <!-- Toggle Publish -->
                    <form method="POST" action="<?= View::url('/admin/reviews/index.php') ?>" style="display: inline-block; margin-right: 0.35rem;">
                      <?= Csrf::field() ?>
                      <input type="hidden" name="action" value="toggle">
                      <input type="hidden" name="id" value="<?= (int) $rev['id'] ?>">
                      <button type="submit" class="admin-btn admin-btn-sm" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; background: #F8FAFC; border: 1px solid #CBD5E1; color: #334155;" title="<?= $rev['is_published'] ? 'Hide from website' : 'Publish on website' ?>">
                        <?= $rev['is_published'] ? 'Hide' : 'Publish' ?>
                      </button>
                    </form>

                    <!-- Edit -->
                    <a href="<?= View::url('/admin/reviews/index.php?edit=' . (int)$rev['id']) ?>" class="admin-btn admin-btn-sm admin-btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; margin-right: 0.35rem;">
                      Edit
                    </a>

                    <!-- Delete -->
                    <form method="POST" action="<?= View::url('/admin/reviews/index.php') ?>" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this review?');">
                      <?= Csrf::field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= (int) $rev['id'] ?>">
                      <button type="submit" class="admin-btn admin-btn-sm admin-btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">
                        Delete
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Form: Add or Edit Review -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
      <h3 style="font-size: 1.05rem; font-weight: 700; margin-top: 0; margin-bottom: 1.25rem; color: #1E293B;">
        <?= $editReview ? 'Edit Review #' . (int)$editReview['id'] : 'Add New Google Review' ?>
      </h3>

      <form method="POST" action="<?= View::url('/admin/reviews/index.php') ?>">
        <?= Csrf::field() ?>
        <input type="hidden" name="action" value="<?= $editReview ? 'update' : 'add' ?>">
        <?php if ($editReview): ?>
          <input type="hidden" name="id" value="<?= (int)$editReview['id'] ?>">
        <?php endif; ?>

        <div style="margin-bottom: 1.1rem;">
          <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem;">
            Customer Name <span style="color: #DC2626;">*</span>
          </label>
          <input type="text" 
                 name="customer_name" 
                 required 
                 value="<?= View::e($editReview['customer_name'] ?? '') ?>" 
                 placeholder="e.g. Karthik &amp; Divya" 
                 style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.88rem; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1.1rem;">
          <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem;">
            Star Rating <span style="color: #DC2626;">*</span>
          </label>
          <select name="rating" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.88rem; box-sizing: border-box;">
            <?php 
              $curRating = (int) ($editReview['rating'] ?? 5);
              for ($s = 5; $s >= 1; $s--): 
            ?>
              <option value="<?= $s ?>" <?= $curRating === $s ? 'selected' : '' ?>>
                <?= str_repeat('★', $s) ?> (<?= $s ?> Stars)
              </option>
            <?php endfor; ?>
          </select>
        </div>

        <div style="margin-bottom: 1.1rem;">
          <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem;">
            Event Type / Setup Description
          </label>
          <input type="text" 
                 name="event_type" 
                 value="<?= View::e($editReview['event_type'] ?? '') ?>" 
                 placeholder="e.g. Grand Wedding Stage &amp; Floral Arch" 
                 style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.88rem; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1.1rem;">
          <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem;">
            Review Date
          </label>
          <input type="date" 
                 name="review_date" 
                 value="<?= View::e($editReview['review_date'] ?? date('Y-m-d')) ?>" 
                 style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.88rem; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1.1rem;">
          <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem;">
            Customer Feedback Text <span style="color: #DC2626;">*</span>
          </label>
          <textarea name="review_text" 
                    rows="4" 
                    required 
                    placeholder="Enter the customer review as posted on Google..." 
                    style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.88rem; line-height: 1.5; box-sizing: border-box;"><?= View::e($editReview['review_text'] ?? '') ?></textarea>
        </div>

        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem;">
            Display Order
          </label>
          <input type="number" 
                 name="display_order" 
                 value="<?= (int)($editReview['display_order'] ?? 0) ?>" 
                 style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.88rem; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1.5rem;">
          <label style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; color: #334155; cursor: pointer;">
            <input type="checkbox" name="is_published" value="1" <?= (!isset($editReview) || !empty($editReview['is_published'])) ? 'checked' : '' ?>>
            <span><strong>Publish immediately on website</strong></span>
          </label>
        </div>

        <div style="display: flex; gap: 0.75rem;">
          <button type="submit" class="admin-btn admin-btn-primary" style="flex: 1;">
            <?= $editReview ? 'Update Review' : 'Save &amp; Publish Review' ?>
          </button>

          <?php if ($editReview): ?>
            <a href="<?= View::url('/admin/reviews/index.php') ?>" class="admin-btn admin-btn-secondary">
              Cancel
            </a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
