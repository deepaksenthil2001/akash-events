<?php
/**
 * Akash Event Management - Administrative Portal Login
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Auth;
use App\Core\Csrf;
use App\Helpers\View;

// If already logged in, redirect directly to dashboard
if (Auth::check()) {
    header("Location: " . View::url('/admin/dashboard.php'));
    exit;
}

$error = '';
$usernameVal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        $error = 'Security session expired. Please refresh the page and try again.';
    } else {
        $usernameVal = trim($_POST['username'] ?? '');
        $passwordVal = $_POST['password'] ?? '';

        if (empty($usernameVal) || empty($passwordVal)) {
            $error = 'Please enter both username/email and password.';
        } else {
            if (Auth::attempt($usernameVal, $passwordVal)) {
                header("Location: " . View::url('/admin/dashboard.php'));
                exit;
            } else {
                $error = 'Invalid credentials or account locked. Please check your username and password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrator Login | Akash Event Management</title>
  
  <link rel="stylesheet" href="<?= View::asset('assets/css/tokens.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/reset.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/components.css') ?>">
  <link rel="stylesheet" href="<?= View::asset('assets/css/admin/admin.css') ?>">
</head>
<body>

  <div class="admin-login-wrapper">
    <div class="admin-login-box">
      
      <div style="text-align: center; margin-bottom: 2rem;">
        <img src="<?= View::asset('assets/images/brand/logo.png') ?>" alt="Akash Event Management" style="height: 72px; width: auto; object-fit: contain; border-radius: 10px; margin-bottom: 1rem; border: 1px solid rgba(197, 160, 89, 0.4); box-shadow: 0 4px 16px rgba(71, 2, 16, 0.25);">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0F172A; margin-bottom: 0.25rem;">Akash Events</h1>
        <p style="font-size: 0.85rem; color: #64748B;">Client Management &amp; Control Portal</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error">
          <span><?= View::e($error) ?></span>
        </div>
      <?php endif; ?>

      <?php require APP_ROOT . '/app/Views/components/flash_messages.php'; ?>

      <form action="<?= View::url('/admin/login.php') ?>" method="POST" novalidate>
        <?= Csrf::field() ?>

        <div class="form-group">
          <label class="form-label" for="username">Username or Email</label>
          <input type="text" id="username" name="username" class="form-input" required autofocus value="<?= View::e($usernameVal) ?>" placeholder="e.g. admin or email address">
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" name="password" class="form-input" required placeholder="Enter administrative password">
        </div>

        <button type="submit" class="admin-btn admin-btn-gold admin-btn-lg" style="width: 100%; margin-top: 0.75rem;">
          Sign In to Dashboard
        </button>
      </form>

      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); text-align: center;">
        <a href="<?= View::url('/index.php') ?>" style="font-size: 0.8rem; color: #718096; text-decoration: none;">
          &larr; Back to Public Website
        </a>
      </div>

    </div>
  </div>

</body>
</html>
