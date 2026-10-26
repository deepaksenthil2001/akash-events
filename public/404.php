<?php
/**
 * Akash Event Management - 404 Not Found Page
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;

$pageTitle = 'Page Not Found | Akash Event Management';
$pageDescription = 'The page you requested could not be found.';
$currentNav = '';

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <div class="site-container" style="padding: var(--space-5xl) 0; text-align: center;">
    <span class="section-eyebrow">404 Error</span>
    <h1 class="section-title" style="font-size: var(--font-size-4xl); margin-bottom: 1rem;">Page Not Found</h1>
    <p class="section-description" style="margin-bottom: 2rem;">
      The requested page or celebration service could not be located. It may have been moved or unpublished.
    </p>
    <div style="display: flex; justify-content: center; gap: 1rem;">
      <a href="<?= View::url('/index.php') ?>" class="btn btn-primary">Return Home</a>
      <a href="<?= View::url('/services.php') ?>" class="btn btn-secondary">Explore Services</a>
    </div>
  </div>

<?php require_once APP_ROOT . '/app/Views/layouts/footer.php'; ?>
