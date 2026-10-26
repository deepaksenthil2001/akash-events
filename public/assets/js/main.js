/**
 * Akash Event Management - Main Application Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Intersection Observer for Subtle Section Reveal
  if ('IntersectionObserver' in window) {
    const revealElements = document.querySelectorAll('.card, .stat-item, .story-grid, .section-header');

    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    }, {
      rootMargin: '0px 0px -40px 0px',
      threshold: 0.1
    });

    revealElements.forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
      revealObserver.observe(el);
    });

    // Custom CSS style rule for revealed elements
    const style = document.createElement('style');
    style.innerHTML = `
      .is-revealed {
        opacity: 1 !important;
        transform: translateY(0) !important;
      }
      @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
  }
});
