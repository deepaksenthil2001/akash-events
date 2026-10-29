<?php
/**
 * Akash Event Management - Admin & Staff Account Management
 * Clean Apple-Standard Administration Interface
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Admin;

$pageTitle = 'Admin Accounts & Staff | Akash Event Management Admin';
$pageHeaderTitle = 'Admin Accounts';
$currentAdminNav = 'users';

$currentUser = Auth::user();
$currentUserId = Auth::id();

$errors = [];
$successMessage = '';

// Handle Actions (Create, Change Password, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
        header("Location: " . View::url('/admin/users/index.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    // 1. CREATE NEW ADMIN
    if ($action === 'create_admin') {
        $username = strtolower(trim($_POST['username'] ?? ''));
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? 'admin');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($fullName)) {
            $errors['full_name'] = 'Full Name is required.';
        }

        if (empty($username) || strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
            $errors['username'] = 'Username must be at least 3 characters and contain only letters, numbers, underscores, or dots.';
        } elseif (Admin::existsByUsername($username)) {
            $errors['username'] = "The username &quot;{$username}&quot; is already taken.";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (empty($password) || strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (!in_array($role, ['superadmin', 'admin'])) {
            $role = 'admin';
        }

        if (empty($errors)) {
            $newId = Admin::create([
                'username' => $username,
                'email' => !empty($email) ? $email : null,
                'password' => $password,
                'full_name' => $fullName,
                'role' => $role,
            ]);

            if ($newId > 0) {
                flash('success', "Administrator account &quot;{$username}&quot; created successfully.");
                header("Location: " . View::url('/admin/users/index.php'));
                exit;
            } else {
                $errors['general'] = 'Failed to create admin account. Please try again.';
            }
        }
    }

    // 2. CHANGE PASSWORD
    if ($action === 'change_password') {
        $targetId = (int)($_POST['target_admin_id'] ?? 0);
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_new_password'] ?? '';

        $targetAdmin = Admin::getById($targetId);

        if (!$targetAdmin) {
            flash('error', 'Administrator account not found.');
        } elseif (empty($newPass) || strlen($newPass) < 8) {
            flash('error', 'Password must be at least 8 characters long.');
        } elseif ($newPass !== $confirmPass) {
            flash('error', 'Passwords do not match.');
        } else {
            if (Admin::updatePassword($targetId, $newPass)) {
                flash('success', "Password for &quot;{$targetAdmin['username']}&quot; updated successfully.");
            } else {
                flash('error', 'Failed to update password.');
            }
        }
        header("Location: " . View::url('/admin/users/index.php'));
        exit;
    }

    // 3. DELETE ADMIN
    if ($action === 'delete_admin') {
        $targetId = (int)($_POST['target_admin_id'] ?? 0);

        if ($targetId === $currentUserId) {
            flash('error', 'You cannot delete your own currently logged-in account.');
        } elseif (Admin::count() <= 1) {
            flash('error', 'Cannot delete the only remaining administrator account.');
        } else {
            $targetAdmin = Admin::getById($targetId);
            if ($targetAdmin) {
                Admin::delete($targetId);
                flash('success', "Administrator &quot;{$targetAdmin['username']}&quot; was removed.");
            } else {
                flash('error', 'Admin account not found.');
            }
        }
        header("Location: " . View::url('/admin/users/index.php'));
        exit;
    }
}

$admins = Admin::getAll();

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div style="max-width: 1040px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Header & Statistics -->
    <div class="admin-card" style="margin-bottom: 0;">
      <div class="admin-card-header" style="border-bottom: none; padding-bottom: 0;">
        <div>
          <h1 class="admin-card-title">Administrator &amp; Staff Accounts</h1>
          <p class="admin-card-subtitle">
            Manage who has secure login access to the Akash Event Management Portal.
          </p>
        </div>
      </div>

      <!-- Quick Metrics -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.25rem;">
        <div style="background: #F5F5F7; border: 1px solid #E5E5EA; border-radius: 8px; padding: 1rem 1.25rem;">
          <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #86868B;">Total Accounts</div>
          <div style="font-size: 1.75rem; font-weight: 700; color: #1D1D1F; margin-top: 0.25rem;"><?= count($admins) ?></div>
        </div>

        <div style="background: #F5F5F7; border: 1px solid #E5E5EA; border-radius: 8px; padding: 1rem 1.25rem;">
          <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #86868B;">Super Administrators</div>
          <div style="font-size: 1.75rem; font-weight: 700; color: #6A1B29; margin-top: 0.25rem;">
            <?= count(array_filter($admins, fn($a) => ($a['role'] ?? '') === 'superadmin')) ?>
          </div>
        </div>

        <div style="background: #F5F5F7; border: 1px solid #E5E5EA; border-radius: 8px; padding: 1rem 1.25rem;">
          <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #86868B;">Security Standard</div>
          <div style="font-size: 0.95rem; font-weight: 600; color: #34C759; margin-top: 0.5rem; display: flex; align-items: center; gap: 6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            Bcrypt Encrypted
          </div>
        </div>
      </div>
    </div>

    <!-- Active Administrators List -->
    <div class="admin-card" style="margin-bottom: 0;">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title">Active Administrators (<?= count($admins) ?>)</h2>
          <p class="admin-card-subtitle">All users who can log in to manage website events, services, and enquiries.</p>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Admin Profile</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Last Login</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($admins as $admin): ?>
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: #6A1B29; color: #DFB76C; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0;">
                      <?= strtoupper(substr($admin['full_name'] ?? $admin['username'], 0, 1)) ?>
                    </div>
                    <div>
                      <div style="font-weight: 600; color: #1D1D1F; font-size: 0.9rem;">
                        <?= View::e($admin['full_name'] ?? 'Admin User') ?>
                        <?php if ($admin['id'] === $currentUserId): ?>
                          <span style="font-size: 0.68rem; font-weight: 600; background: #E8F5E9; color: #2E7D32; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">You</span>
                        <?php endif; ?>
                      </div>
                      <div style="font-size: 0.75rem; color: #86868B;">ID: #<?= (int)$admin['id'] ?></div>
                    </div>
                  </div>
                </td>
                <td>
                  <code style="background: #F5F5F7; padding: 2px 6px; border-radius: 4px; font-size: 0.85rem; color: #1D1D1F;">
                    <?= View::e($admin['username']) ?>
                  </code>
                </td>
                <td style="color: #6E6E73; font-size: 0.85rem;">
                  <?= !empty($admin['email']) ? View::e($admin['email']) : '<span style="color: #AEAEB2;">None</span>' ?>
                </td>
                <td>
                  <?php if (($admin['role'] ?? '') === 'superadmin'): ?>
                    <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: #FFF4E5; color: #B76E00; border: 1px solid #FFE0B2;">
                      Super Admin
                    </span>
                  <?php else: ?>
                    <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: #F5F5F7; color: #555; border: 1px solid #E5E5EA;">
                      Staff Admin
                    </span>
                  <?php endif; ?>
                </td>
                <td style="color: #86868B; font-size: 0.825rem;">
                  <?= !empty($admin['last_login']) ? date('M d, Y h:i A', strtotime($admin['last_login'])) : 'Never' ?>
                </td>
                <td style="text-align: right;">
                  <div style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <!-- Change Password Trigger -->
                    <button type="button" 
                            class="admin-btn admin-btn-secondary admin-btn-sm btn-change-pass" 
                            data-admin-id="<?= (int)$admin['id'] ?>"
                            data-admin-name="<?= View::e($admin['full_name'] ?? $admin['username']) ?>"
                            style="padding: 0.4rem 0.65rem; font-size: 0.775rem;">
                      Reset Password
                    </button>

                    <!-- Delete Button -->
                    <?php if ($admin['id'] !== $currentUserId && count($admins) > 1): ?>
                      <form action="<?= View::url('/admin/users/index.php') ?>" method="POST" style="display: inline;" 
                            onsubmit="return confirm('Are you sure you want to permanently delete administrator &quot;<?= View::e($admin['username']) ?>&quot;?');">
                        <?= Csrf::field() ?>
                        <input type="hidden" name="action" value="delete_admin">
                        <input type="hidden" name="target_admin_id" value="<?= (int)$admin['id'] ?>">
                        <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" style="padding: 0.4rem 0.65rem; font-size: 0.775rem;">
                          Delete
                        </button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create New Administrator Form Card -->
    <div class="admin-card" style="margin-bottom: 0;">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title">+ Add New Administrator</h2>
          <p class="admin-card-subtitle">Create a dedicated login account for new team members or managers.</p>
        </div>
      </div>

      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error" style="margin-bottom: 1.25rem;">
          <?= View::e($errors['general']) ?>
        </div>
      <?php endif; ?>

      <form action="<?= View::url('/admin/users/index.php') ?>" method="POST" novalidate>
        <?= Csrf::field() ?>
        <input type="hidden" name="action" value="create_admin">

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="full_name">Full Name <span class="required">*</span></label>
            <input type="text" id="full_name" name="full_name" class="form-input" 
                   placeholder="e.g. Akash Manobalan" 
                   value="<?= View::e($_POST['full_name'] ?? '') ?>" required>
            <?php if (!empty($errors['full_name'])): ?>
              <span class="form-error"><?= View::e($errors['full_name']) ?></span>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label class="form-label" for="username">Username (Login ID) <span class="required">*</span></label>
            <input type="text" id="username" name="username" class="form-input" 
                   placeholder="e.g. akash" 
                   value="<?= View::e($_POST['username'] ?? '') ?>" required>
            <span class="form-help">Unique username used to log in. Letters, numbers, and dots only.</span>
            <?php if (!empty($errors['username'])): ?>
              <span class="form-error"><?= View::e($errors['username']) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="email">Email Address (Optional)</label>
            <input type="email" id="email" name="email" class="form-input" 
                   placeholder="e.g. akash@gmail.com" 
                   value="<?= View::e($_POST['email'] ?? '') ?>">
            <?php if (!empty($errors['email'])): ?>
              <span class="form-error"><?= View::e($errors['email']) ?></span>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label class="form-label" for="role">Administrative Role <span class="required">*</span></label>
            <select id="role" name="role" class="form-select">
              <option value="superadmin" <?= ($_POST['role'] ?? '') === 'superadmin' ? 'selected' : '' ?>>Super Administrator (Full Access)</option>
              <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Staff Admin (Content Manager)</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="password">Account Password <span class="required">*</span></label>
            <input type="password" id="password" name="password" class="form-input" 
                   placeholder="Minimum 8 characters" required>
            <?php if (!empty($errors['password'])): ?>
              <span class="form-error"><?= View::e($errors['password']) ?></span>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label class="form-label" for="confirm_password">Confirm Password <span class="required">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-input" 
                   placeholder="Re-type password" required>
            <?php if (!empty($errors['confirm_password'])): ?>
              <span class="form-error"><?= View::e($errors['confirm_password']) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #E5E5EA; display: flex; justify-content: flex-end;">
          <button type="submit" class="admin-btn admin-btn-gold admin-btn-lg">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create Administrator Account
          </button>
        </div>
      </form>
    </div>

  </div>

  <!-- Reset Password Modal -->
  <div id="modal-reset-password" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #FFFFFF; border-radius: 12px; max-width: 440px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden;">
      <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E5E5EA; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #1D1D1F; margin: 0;">Reset Password</h3>
        <button type="button" id="btn-close-modal" style="background: none; border: none; font-size: 1.25rem; color: #86868B; cursor: pointer; padding: 4px;">&times;</button>
      </div>

      <form action="<?= View::url('/admin/users/index.php') ?>" method="POST" style="padding: 1.5rem;">
        <?= Csrf::field() ?>
        <input type="hidden" name="action" value="change_password">
        <input type="hidden" name="target_admin_id" id="modal-target-admin-id" value="">

        <p style="font-size: 0.85rem; color: #6E6E73; margin-bottom: 1.25rem;">
          Setting a new password for: <strong id="modal-admin-name" style="color: #1D1D1F;"></strong>
        </p>

        <div class="form-group">
          <label class="form-label" for="new_password">New Password (Min 8 chars) <span class="required">*</span></label>
          <input type="password" id="new_password" name="new_password" class="form-input" required minlength="8" placeholder="Enter new secure password">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
          <label class="form-label" for="confirm_new_password">Confirm New Password <span class="required">*</span></label>
          <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-input" required minlength="8" placeholder="Re-type new password">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
          <button type="button" id="btn-cancel-modal" class="admin-btn admin-btn-secondary admin-btn-sm">Cancel</button>
          <button type="submit" class="admin-btn admin-btn-gold admin-btn-sm">Save New Password</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('modal-reset-password');
      const targetIdInput = document.getElementById('modal-target-admin-id');
      const adminNameSpan = document.getElementById('modal-admin-name');
      const btnClose = document.getElementById('btn-close-modal');
      const btnCancel = document.getElementById('btn-cancel-modal');

      document.querySelectorAll('.btn-change-pass').forEach(btn => {
        btn.addEventListener('click', function() {
          const id = this.getAttribute('data-admin-id');
          const name = this.getAttribute('data-admin-name');
          targetIdInput.value = id;
          adminNameSpan.textContent = name;
          modal.style.display = 'flex';
        });
      });

      function closeModal() {
        modal.style.display = 'none';
      }

      if (btnClose) btnClose.addEventListener('click', closeModal);
      if (btnCancel) btnCancel.addEventListener('click', closeModal);
      window.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
      });
    });
  </script>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>

