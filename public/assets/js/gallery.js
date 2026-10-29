/**
 * Akash Event Management - Gallery & Lightbox Controller
 * Supports Curated Event Multi-Photo Albums & Individual Showcase Media
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Category Filtering for Events & Gallery Media
  const filterPills = document.querySelectorAll('.filter-pill');
  const eventCards = document.querySelectorAll('.event-card');
  const galleryCards = document.querySelectorAll('.gallery-card');

  filterPills.forEach(pill => {
    pill.addEventListener('click', (e) => {
      e.preventDefault();
      const category = pill.getAttribute('data-filter');

      filterPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');

      let visibleCount = 0;

      // Filter Event Cards
      eventCards.forEach(card => {
        const itemCategory = card.getAttribute('data-category');
        if (category === 'all' || itemCategory === category) {
          card.style.display = 'flex';
          card.style.opacity = '0';
          card.style.transform = 'translateY(12px)';
          setTimeout(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 30);
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      // Filter Standalone Media Cards (if present)
      galleryCards.forEach(card => {
        const itemCategory = card.getAttribute('data-category');
        const itemType = card.getAttribute('data-type');
        if (category === 'all' || itemCategory === category || (category === 'video' && itemType === 'video')) {
          card.style.display = 'block';
          card.style.opacity = '0';
          card.style.transform = 'translateY(12px)';
          setTimeout(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 30);
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      // Empty state indicator
      const emptyState = document.getElementById('gallery-empty-state');
      if (emptyState) {
        emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
      }
    });
  });

  // 2. Lightbox Modal
  const modal = document.querySelector('.lightbox-modal');
  if (!modal) return;

  const modalImg = modal.querySelector('.lightbox-image');
  const modalVideo = modal.querySelector('.lightbox-video');
  const modalCaption = modal.querySelector('.lightbox-caption');
  const modalCounter = modal.querySelector('#lightbox-counter');
  const modalAlbumStrip = modal.querySelector('#lightbox-album-strip');
  const btnClose = modal.querySelector('.lightbox-btn-close');
  const btnPrev = modal.querySelector('.lightbox-nav.prev');
  const btnNext = modal.querySelector('.lightbox-nav.next');

  // Album state
  let currentAlbum = [];
  let albumTitle = '';
  let activeIndex = -1;

  // Open Event Album
  function openAlbum(photos, title, startIndex = 0) {
    if (!photos || photos.length === 0) return;
    currentAlbum = photos;
    albumTitle = title;
    renderAlbumStrip();
    showAlbumItem(startIndex);
    modal.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  }

  function renderAlbumStrip() {
    if (!modalAlbumStrip) return;
    if (currentAlbum.length <= 1) {
      modalAlbumStrip.style.display = 'none';
      modalAlbumStrip.innerHTML = '';
      return;
    }

    modalAlbumStrip.style.display = 'flex';
    modalAlbumStrip.innerHTML = currentAlbum.map((photo, i) => `
      <img src="${photo.file_path || photo.src}" 
           alt="${photo.title || 'Thumbnail'}" 
           class="lightbox-album-thumb ${i === activeIndex ? 'active' : ''}" 
           data-index="${i}">
    `).join('');

    modalAlbumStrip.querySelectorAll('.lightbox-album-thumb').forEach(thumb => {
      thumb.addEventListener('click', (e) => {
        e.stopPropagation();
        const idx = parseInt(thumb.getAttribute('data-index'), 10);
        showAlbumItem(idx);
      });
    });
  }

  function showAlbumItem(index) {
    if (index < 0 || index >= currentAlbum.length) return;
    activeIndex = index;
    const item = currentAlbum[activeIndex];
    const src = item.file_path || item.src;
    const type = item.media_type || item.type || 'image';
    const title = item.title || albumTitle;
    const caption = item.caption || '';

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

    // Update Counter
    if (modalCounter) {
      modalCounter.textContent = `Photo ${activeIndex + 1} of ${currentAlbum.length} • ${albumTitle || 'Gallery'}`;
    }

    // Update Caption
    if (modalCaption) {
      modalCaption.innerHTML = `<strong>${title}</strong>${caption ? `<br><span style="opacity: 0.85; font-size: 0.85rem;">${caption}</span>` : ''}`;
    }

    // Update Thumbnail Active State
    if (modalAlbumStrip) {
      modalAlbumStrip.querySelectorAll('.lightbox-album-thumb').forEach((thumb, i) => {
        if (i === activeIndex) {
          thumb.classList.add('active');
          thumb.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        } else {
          thumb.classList.remove('active');
        }
      });
    }
  }

  // Bind Event Cards
  eventCards.forEach(card => {
    const photosJson = card.getAttribute('data-photos');
    const title = card.getAttribute('data-event-title') || 'Event Gallery';
    let photos = [];
    try {
      photos = JSON.parse(photosJson) || [];
    } catch (e) {
      photos = [];
    }

    // Main Card click
    card.addEventListener('click', (e) => {
      if (e.target.closest('.event-thumb-item')) return;
      openAlbum(photos, title, 0);
    });

    // Individual preview thumbnail click inside card
    card.querySelectorAll('.event-thumb-item').forEach(thumb => {
      thumb.addEventListener('click', (e) => {
        e.stopPropagation();
        const idx = parseInt(thumb.getAttribute('data-index') || '0', 10);
        openAlbum(photos, title, idx);
      });
    });
  });

  // Bind Standalone Gallery Cards (e.g. on Home page)
  galleryCards.forEach(card => {
    card.addEventListener('click', () => {
      const visible = Array.from(galleryCards).filter(c => c.style.display !== 'none');
      const photos = visible.map(c => ({
        src: c.getAttribute('data-src'),
        type: c.getAttribute('data-type') || 'image',
        title: c.getAttribute('data-title') || '',
        caption: c.getAttribute('data-caption') || '',
      }));
      const idx = visible.indexOf(card);
      openAlbum(photos, 'Portfolio Showcase', Math.max(0, idx));
    });
  });

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
    if (currentAlbum.length === 0) return;
    const nextIdx = (activeIndex + 1) % currentAlbum.length;
    showAlbumItem(nextIdx);
  }

  function prevItem() {
    if (currentAlbum.length === 0) return;
    const prevIdx = (activeIndex - 1 + currentAlbum.length) % currentAlbum.length;
    showAlbumItem(prevIdx);
  }

  if (btnClose) btnClose.addEventListener('click', closeModal);
  if (btnNext) btnNext.addEventListener('click', nextItem);
  if (btnPrev) btnPrev.addEventListener('click', prevItem);

  modal.addEventListener('click', (e) => {
    if (e.target === modal || e.target.classList.contains('lightbox-stage')) {
      closeModal();
    }
  });

  // Mobile touch swipe gesture navigation
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

    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 40) {
      if (diffX < 0) {
        nextItem(); // Swipe Left -> Next photo
      } else {
        prevItem(); // Swipe Right -> Prev photo
      }
    } else if (diffY > 90 && Math.abs(diffY) > Math.abs(diffX)) {
      // Swipe down -> dismiss modal
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
