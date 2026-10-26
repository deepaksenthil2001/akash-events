/**
 * Akash Event Management - Gallery & Lightbox Controller
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Category Filtering
  const filterPills = document.querySelectorAll('.filter-pill');
  const galleryCards = document.querySelectorAll('.gallery-card');

  filterPills.forEach(pill => {
    pill.addEventListener('click', (e) => {
      e.preventDefault();
      const category = pill.getAttribute('data-filter');

      filterPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');

      galleryCards.forEach(card => {
        const itemCategory = card.getAttribute('data-category');
        const itemType = card.getAttribute('data-type');
        if (category === 'all' || itemCategory === category || (category === 'video' && itemType === 'video')) {
          card.style.display = 'block';
          // Trigger subtle entrance
          card.style.opacity = '0';
          card.style.transform = 'translateY(12px)';
          setTimeout(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 30);
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // 2. Lightbox Modal
  const modal = document.querySelector('.lightbox-modal');
  if (!modal) return;

  const modalImg = modal.querySelector('.lightbox-image');
  const modalVideo = modal.querySelector('.lightbox-video');
  const modalCaption = modal.querySelector('.lightbox-caption');
  const btnClose = modal.querySelector('.lightbox-btn-close');
  const btnPrev = modal.querySelector('.lightbox-nav.prev');
  const btnNext = modal.querySelector('.lightbox-nav.next');

  let activeIndex = -1;
  let visibleCards = [];

  function updateVisibleCards() {
    visibleCards = Array.from(galleryCards).filter(c => c.style.display !== 'none');
  }

  galleryCards.forEach(card => {
    card.addEventListener('click', () => {
      updateVisibleCards();
      activeIndex = visibleCards.indexOf(card);
      if (activeIndex !== -1) {
        showItem(activeIndex);
      }
    });
  });

  function showItem(index) {
    if (index < 0 || index >= visibleCards.length) return;
    activeIndex = index;
    const card = visibleCards[activeIndex];
    const src = card.getAttribute('data-src');
    const type = card.getAttribute('data-type') || 'image';
    const title = card.getAttribute('data-title') || '';
    const caption = card.getAttribute('data-caption') || '';

    if (type === 'video') {
      if (modalImg) modalImg.style.display = 'none';
      if (modalVideo) {
        modalVideo.style.display = 'block';
        modalVideo.src = src;
        modalVideo.play();
      }
    } else {
      if (modalVideo) {
        modalVideo.pause();
        modalVideo.style.display = 'none';
      }
      if (modalImg) {
        modalImg.style.display = 'block';
        modalImg.src = src;
        modalImg.alt = title;
      }
    }

    if (modalCaption) {
      modalCaption.innerHTML = `<strong>${title}</strong>${caption ? `<br><span style="opacity: 0.8">${caption}</span>` : ''}`;
    }

    modal.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.classList.remove('is-active');
    document.body.style.overflow = '';
    if (modalVideo) {
      modalVideo.pause();
      modalVideo.src = '';
    }
    if (modalImg) {
      modalImg.src = '';
    }
  }

  function nextItem() {
    updateVisibleCards();
    if (visibleCards.length === 0) return;
    const nextIdx = (activeIndex + 1) % visibleCards.length;
    showItem(nextIdx);
  }

  function prevItem() {
    updateVisibleCards();
    if (visibleCards.length === 0) return;
    const prevIdx = (activeIndex - 1 + visibleCards.length) % visibleCards.length;
    showItem(prevIdx);
  }

  if (btnClose) btnClose.addEventListener('click', closeModal);
  if (btnNext) btnNext.addEventListener('click', nextItem);
  if (btnPrev) btnPrev.addEventListener('click', prevItem);

  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Mobile touch swipe gesture navigation (iOS feel)
  let touchStartX = 0;
  let touchStartY = 0;
  let touchEndX = 0;
  let touchEndY = 0;

  modal.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
    touchStartY = e.changedTouches[0].screenY;
  }, { passive: true });

  modal.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    touchEndY = e.changedTouches[0].screenY;
    handleSwipeGesture();
  }, { passive: true });

  function handleSwipeGesture() {
    const diffX = touchEndX - touchStartX;
    const diffY = touchEndY - touchStartY;

    // Detect horizontal swipe
    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 40) {
      if (diffX < 0) {
        nextItem(); // Swipe Left -> Next photo
      } else {
        prevItem(); // Swipe Right -> Prev photo
      }
    } else if (diffY > 80 && Math.abs(diffY) > Math.abs(diffX)) {
      // Swipe down -> dismiss modal (iOS pattern)
      closeModal();
    }
  }

  document.addEventListener('keydown', (e) => {
    if (!modal.classList.contains('is-active')) return;
    if (e.key === 'Escape') closeModal();
    if (e.key === 'ArrowRight') nextItem();
    if (e.key === 'ArrowLeft') prevItem();
  });
});
