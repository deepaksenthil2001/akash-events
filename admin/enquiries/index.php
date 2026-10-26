<?php
/**
 * Akash Event Management - Admin Customer Enquiries
 */

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Helpers\View;
use App\Core\Csrf;
use App\Models\Enquiry;

$pageTitle = 'Customer Enquiries | Akash Event Management Admin';
$pageHeaderTitle = 'Customer Enquiries & Leads';
$currentAdminNav = 'enquiries';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
        flash('error', 'Security token invalid. Please try again.');
    } else {
        $action = $_POST['action'] ?? '';
        $id = (int) ($_POST['id'] ?? 0);

        if ($action === 'delete' && $id > 0) {
            Enquiry::delete($id);
            flash('success', 'Enquiry deleted successfully.');
        } elseif ($action === 'update_status' && $id > 0) {
            $newStatus = $_POST['status'] ?? 'read';
            Enquiry::updateStatus($id, $newStatus);
            flash('success', 'Enquiry status updated to ' . ucfirst($newStatus) . '.');
        }
    }
    header("Location: " . View::url('/admin/enquiries/index.php'));
    exit;
}

$statusFilter = trim($_GET['status'] ?? 'all');
$keyword = trim($_GET['q'] ?? '');

if (!empty($keyword)) {
    $enquiries = Enquiry::search($keyword);
} else {
    $enquiries = Enquiry::getAll($statusFilter);
}

$countAll = Enquiry::count('all');
$countNew = Enquiry::count('new');
$countRead = Enquiry::count('read');
$countContacted = Enquiry::count('contacted');
$countCompleted = Enquiry::count('completed');

require APP_ROOT . '/app/Views/layouts/admin_header.php';
?>

  <div class="admin-card">
    <div class="admin-card-header">
      <div>
        <h2 class="admin-card-title">Enquiry Submissions (<?= count($enquiries) ?>)</h2>
        <p style="font-size: 0.85rem; color: #718096; margin-top: 2px;">
          Track incoming celebration requests, customer contact phone numbers, and workflow statuses.
        </p>
      </div>

      <!-- Search & Filters -->
      <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
        <form action="<?= View::url('/admin/enquiries/index.php') ?>" method="GET" style="display: flex; gap: 0.5rem;">
          <input type="text" name="q" value="<?= View::e($keyword) ?>" placeholder="Search name, phone, city..." class="form-input" style="padding: 0.45rem 0.75rem; font-size: 0.85rem; width: 220px;">
          <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm">Search</button>
        </form>
      </div>
    </div>

    <!-- Status Tabs -->
    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; border-bottom: 1px solid var(--admin-border); padding-bottom: 0.75rem;">
      <a href="<?= View::url('/admin/enquiries/index.php?status=all') ?>" class="admin-btn <?= $statusFilter === 'all' && empty($keyword) ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm">
        All (<?= $countAll ?>)
      </a>
      <a href="<?= View::url('/admin/enquiries/index.php?status=new') ?>" class="admin-btn <?= $statusFilter === 'new' ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm">
        New / Unread (<?= $countNew ?>)
      </a>
      <a href="<?= View::url('/admin/enquiries/index.php?status=read') ?>" class="admin-btn <?= $statusFilter === 'read' ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm">
        Read (<?= $countRead ?>)
      </a>
      <a href="<?= View::url('/admin/enquiries/index.php?status=contacted') ?>" class="admin-btn <?= $statusFilter === 'contacted' ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm">
        Contacted (<?= $countContacted ?>)
      </a>
      <a href="<?= View::url('/admin/enquiries/index.php?status=completed') ?>" class="admin-btn <?= $statusFilter === 'completed' ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm">
        Completed (<?= $countCompleted ?>)
      </a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Customer</th>
            <th>Phone / Direct Contact</th>
            <th>Event Details</th>
            <th>Preferred Date</th>
            <th>Received On</th>
            <th>Status</th>
            <th style="text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($enquiries)): ?>
            <?php foreach ($enquiries as $enq): ?>
              <tr>
                <td>
                  <strong><?= View::e($enq['customer_name']) ?></strong>
                  <?php if (!empty($enq['email'])): ?>
                    <div style="font-size: 0.75rem; color: #718096;"><?= View::e($enq['email']) ?></div>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="tel:<?= preg_replace('/[^\d+]/', '', $enq['phone']) ?>" style="font-weight: 600; color: var(--color-gold-deep);">
                    <?= View::e($enq['phone']) ?>
                  </a>
                  <div style="margin-top: 2px;">
                    <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $enq['phone']) ?>?text=<?= urlencode('Hello ' . $enq['customer_name'] . ', this is P. Manobalan from Akash Event Management regarding your event enquiry.') ?>" target="_blank" rel="noopener noreferrer" style="font-size: 0.72rem; color: #2E7D32; font-weight: 500;">
                      WhatsApp &rarr;
                    </a>
                  </div>
                </td>
                <td>
                  <strong><?= View::e($enq['event_type']) ?></strong>
                  <?php if (!empty($enq['event_location'])): ?>
                    <div style="font-size: 0.75rem; color: #718096;"><?= View::e($enq['event_location']) ?></div>
                  <?php endif; ?>
                </td>
                <td><?= View::formatDate($enq['preferred_date']) ?></td>
                <td>
                  <span style="font-size: 0.75rem; color: #718096;">
                    <?= date('M d, Y h:i A', strtotime($enq['created_at'])) ?>
                  </span>
                </td>
                <td>
                  <span class="status-pill status-<?= $enq['status'] ?>">
                    <?= ucfirst($enq['status']) ?>
                  </span>
                </td>
                <td style="text-align: right;">
                  <div style="display: inline-flex; gap: 0.5rem;">
                    <a href="<?= View::url('/admin/enquiries/view.php?id=' . $enq['id']) ?>" class="admin-btn admin-btn-primary admin-btn-sm">
                      Open
                    </a>

                    <form action="<?= View::url('/admin/enquiries/index.php') ?>" method="POST" style="display: inline;">
                      <?= Csrf::field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $enq['id'] ?>">
                      <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" data-confirm="Delete this customer enquiry record?">
                        &times;
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align: center; color: #718096; padding: 2.5rem;">
                No enquiry records found matching current filter.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require APP_ROOT . '/app/Views/layouts/admin_footer.php'; ?>
