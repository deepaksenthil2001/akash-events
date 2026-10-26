/**
 * Akash Event Management - Administrative Dashboard Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Delete Confirmations
  const deleteButtons = document.querySelectorAll('[data-confirm]');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      const msg = btn.getAttribute('data-confirm') || 'Are you sure you want to delete this record? This action cannot be undone.';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  // 2. Image Upload Preview
  const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
  imageInputs.forEach(input => {
    input.addEventListener('change', () => {
      const file = input.files[0];
      if (!file) return;

      const previewTargetId = input.getAttribute('data-preview-target');
      const previewEl = previewTargetId ? document.getElementById(previewTargetId) : null;

      if (previewEl && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          previewEl.src = e.target.result;
          previewEl.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  });

  // 3. Admin Table Search Filter
  const tableSearch = document.querySelector('.table-search-input');
  if (tableSearch) {
    tableSearch.addEventListener('input', (e) => {
      const query = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('.admin-table tbody tr');

      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }

  // 4. Mobile Sidebar Toggle
  const sidebarToggle = document.querySelector('.admin-sidebar-toggle');
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('is-open');
    });
  }
});
