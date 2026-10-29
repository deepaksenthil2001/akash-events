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

  // 2. Single Image Upload Preview
  const imageInputs = document.querySelectorAll('input[type="file"][data-preview-target]');
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

  // 4. Mobile Sidebar Toggle & Overlay
  const sidebarToggle = document.querySelector('.admin-sidebar-toggle');
  const sidebar = document.querySelector('.admin-sidebar');
  let overlay = document.querySelector('.admin-sidebar-overlay');

  if (!overlay && sidebar) {
    overlay = document.createElement('div');
    overlay.className = 'admin-sidebar-overlay';
    document.body.appendChild(overlay);

    overlay.addEventListener('click', () => {
      sidebar.classList.remove('is-open');
      overlay.classList.remove('is-active');
    });
  }

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('is-open');
      if (overlay) {
        overlay.classList.toggle('is-active', sidebar.classList.contains('is-open'));
      }
    });
  }

  // 5. Drag and Drop Highlighting on Dropzones
  const dropzones = document.querySelectorAll('.admin-dropzone');
  dropzones.forEach(zone => {
    ['dragenter', 'dragover'].forEach(eventName => {
      zone.addEventListener(eventName, (e) => {
        e.preventDefault();
        zone.classList.add('dragover');
      }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      zone.addEventListener(eventName, (e) => {
        e.preventDefault();
        zone.classList.remove('dragover');
      }, false);
    });
  });
});
