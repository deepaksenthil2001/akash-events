/**
 * Akash Event Management - Navigation & Mobile Drawer
 */

document.addEventListener('DOMContentLoaded', () => {
  const menuBtn = document.querySelector('.mobile-menu-btn');
  const drawer = document.querySelector('.mobile-drawer');
  const header = document.querySelector('.site-header');

  if (menuBtn && drawer) {
    menuBtn.addEventListener('click', () => {
      const isOpen = drawer.classList.contains('is-open');
      if (isOpen) {
        closeDrawer();
      } else {
        openDrawer();
      }
    });

    // Close when clicking outside or pressing Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
        closeDrawer();
      }
    });

    // Close when clicking a nav link in the drawer
    drawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        closeDrawer();
      });
    });
  }

  function openDrawer() {
    drawer.classList.add('is-open');
    menuBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer.classList.remove('is-open');
    menuBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  // Header blur / elevation on scroll
  window.addEventListener('scroll', () => {
    if (header) {
      if (window.scrollY > 30) {
        header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.05)';
      } else {
        header.style.boxShadow = 'none';
      }
    }
  }, { passive: true });
});
