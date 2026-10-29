<?php
use App\Helpers\View;

$siteName = View::setting('business_name', 'Akash Event Management');
$phonePrimary = View::setting('phone_primary', '+91 93442 73433');
$phoneSecondary = View::setting('phone_secondary', '+91 96599 99856');
$emailPrimary = View::setting('email_primary', 'akasheventmanagement87@gmail.com');
$address = View::setting('address_line', '12, Gokul Nagar, Naligabetta Agraharam, Bhavani palace backside, Hosur Tamil Nadu 635109');
$instagram = View::setting('instagram_url', 'https://www.instagram.com/akasheventmanagement');
$googleBusiness = View::setting('google_business_url', 'https://share.google/nUP5Exqa3K1doUEfd');
$whatsapp = View::setting('whatsapp_number', '919344273433');
$pageJs = $pageJs ?? [];
?>

  <!-- Global Lightbox Modal with Multi-Photo Album Support -->
  <div class="lightbox-modal" id="global-lightbox" role="dialog" aria-modal="true" aria-label="Media Preview">
    <div class="lightbox-content">
      <button type="button" class="lightbox-btn-close" aria-label="Close Lightbox">&times;</button>
      
      <button type="button" class="lightbox-nav prev" aria-label="Previous Media">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
      </button>

      <div class="lightbox-stage" style="display: flex; align-items: center; justify-content: center;">
        <img src="" alt="" class="lightbox-image" style="display: none;">
        <video controls class="lightbox-video" style="display: none; max-width: 90vw; max-height: 75vh; border-radius: var(--radius-md);"></video>
      </div>

      <button type="button" class="lightbox-nav next" aria-label="Next Media">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </button>

      <div class="lightbox-meta">
        <div class="lightbox-counter" id="lightbox-counter"></div>
        <div class="lightbox-caption"></div>
        <div class="lightbox-album-strip" id="lightbox-album-strip" style="display: none;"></div>
      </div>
    </div>
  </div>

  <!-- Premium Redesigned Footer -->
  <footer class="site-footer" id="site-footer">
    <div class="site-container">
      <div class="footer-grid">
        
        <!-- 1. Brand Column -->
        <div class="footer-col footer-col-brand">
          <div class="footer-brand-header">
            <a href="<?= View::url('/index.php') ?>" class="footer-brand-link" aria-label="Akash Event Management Home">
              <img src="<?= View::asset('assets/images/brand/logo-footer.png') ?>" alt="Akash Event Management Emblem" class="footer-brand-logo" width="112" height="64" loading="lazy">
              <div class="footer-brand-text">
                <span class="footer-brand-name">Akash Event Management</span>
                <span class="footer-brand-founder">P. Manobalan &bull; 10+ Years</span>
              </div>
            </a>
          </div>

          <p class="footer-brand-desc">
            Bespoke wedding stages, sacred floral mandapams, and luxury celebrations across Hosur, Bangalore, and Krishnagiri. 10+ years of dedicated craftsmanship.
          </p>

          <div class="footer-social-links">
            <a href="<?= View::e($instagram) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm footer-social-btn" aria-label="Follow Akash Event Management on Instagram">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
              <span>Instagram</span>
            </a>
            <a href="<?= View::e($googleBusiness) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm footer-social-btn" aria-label="View Google Business Profile">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
              </svg>
              <span>Google Profile</span>
            </a>
          </div>
        </div>

        <!-- 2. Navigation Column -->
        <div class="footer-col footer-col-nav">
          <h3 class="footer-col-title">Navigation</h3>
          <ul class="footer-links">
            <li><a href="<?= View::url('/index.php') ?>" class="footer-link">Home</a></li>
            <li><a href="<?= View::url('/about.php') ?>" class="footer-link">About Us</a></li>
            <li><a href="<?= View::url('/services.php') ?>" class="footer-link">Services Catalog</a></li>
            <li><a href="<?= View::url('/gallery.php') ?>" class="footer-link">Event Gallery</a></li>
            <li><a href="<?= View::url('/contact.php') ?>" class="footer-link">Contact & Enquiries</a></li>
          </ul>
        </div>

        <!-- 3. Core Services Column -->
        <div class="footer-col footer-col-services">
          <h3 class="footer-col-title">Services</h3>
          <ul class="footer-links">
            <li><a href="<?= View::url('/service-details.php?slug=wedding-reception-decoration') ?>" class="footer-link">Wedding & Reception</a></li>
            <li><a href="<?= View::url('/service-details.php?slug=engagement-stage-design') ?>" class="footer-link">Engagement Stages</a></li>
            <li><a href="<?= View::url('/service-details.php?slug=traditional-mandapam-artistry') ?>" class="footer-link">Floral Mandapams</a></li>
            <li><a href="<?= View::url('/service-details.php?slug=baby-shower-seemantham') ?>" class="footer-link">Baby Shower & Seemantham</a></li>
            <li><a href="<?= View::url('/service-details.php?slug=naming-ceremony-cradle-decor') ?>" class="footer-link">Naming Ceremonies</a></li>
            <li><a href="<?= View::url('/service-details.php?slug=birthday-milestone-events') ?>" class="footer-link">Birthday Celebrations</a></li>
          </ul>
        </div>

        <!-- 4. Contact & Office Column -->
        <div class="footer-col footer-col-contact">
          <h3 class="footer-col-title">Office & Contact</h3>
          <ul class="footer-contact-list">
            <li class="footer-contact-item">
              <span class="footer-contact-label">Studio & Office:</span>
              <span class="footer-contact-val"><?= View::e($address) ?></span>
            </li>
            <li class="footer-contact-item">
              <span class="footer-contact-label">Direct Calling:</span>
              <div class="footer-phone-group">
                <a href="tel:<?= preg_replace('/[^\d+]/', '', $phonePrimary) ?>" class="footer-phone-link">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                  </svg>
                  <span><?= View::e($phonePrimary) ?></span>
                </a>
                <a href="tel:<?= preg_replace('/[^\d+]/', '', $phoneSecondary) ?>" class="footer-phone-link">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                  </svg>
                  <span><?= View::e($phoneSecondary) ?></span>
                </a>
              </div>
            </li>
            <li class="footer-contact-item">
              <span class="footer-contact-label">Official Email:</span>
              <a href="mailto:<?= View::e($emailPrimary) ?>" class="footer-email-link"><?= View::e($emailPrimary) ?></a>
            </li>
          </ul>
        </div>

      </div>

      <!-- Footer Bottom -->
      <div class="footer-bottom">
        <div class="footer-copyright">
          &copy; <?= date('Y') ?> Akash Event Management. All rights reserved. Registered in Hosur, Tamil Nadu.
        </div>
        <div class="footer-bottom-links">
          <a href="<?= View::url('/gallery.php') ?>" class="footer-bottom-link">Gallery</a>
          <span class="footer-bottom-divider" aria-hidden="true">&bull;</span>
          <a href="<?= View::url('/contact.php') ?>" class="footer-bottom-link">Enquiries</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Persistent Bottom Action Bar (Mobile only, <= 768px) -->
  <aside class="ios-mobile-bar" aria-label="Persistent Quick Actions">
    <a href="tel:<?= preg_replace('/[^\d+]/', '', $phonePrimary) ?>" class="ios-bar-btn ios-bar-call" aria-label="Call Akash Event Management">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
      </svg>
      <span>Call</span>
    </a>

    <a href="https://wa.me/<?= $whatsapp ?>?text=<?= urlencode('Hello P. Manobalan, I would like to enquire about event decoration.') ?>" target="_blank" rel="noopener noreferrer" class="ios-bar-btn ios-bar-whatsapp" aria-label="Chat on WhatsApp">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.79 14.1c-.24.68-1.2 1.28-1.83 1.34-.48.05-1.1.07-3.17-.79-2.65-1.1-4.36-3.79-4.49-3.96-.13-.18-1.07-1.42-1.07-2.72 0-1.29.67-1.93.92-2.19.24-.26.54-.33.72-.33.18 0 .36 0 .52.01.17.01.39-.06.61.47.23.55.78 1.9.85 2.04.07.15.11.32.02.5-.09.18-.14.29-.28.45-.14.16-.3.35-.43.48-.14.15-.29.31-.13.58.17.28.74 1.22 1.6 1.98 1.1.98 2.03 1.28 2.31 1.43.29.14.45.12.62-.07.17-.2.72-.84.91-1.13.2-.29.39-.24.66-.14.27.1 1.72.81 2.02.96.29.15.49.22.56.34.07.13.07.72-.17 1.4z"/>
      </svg>
      <span>WhatsApp</span>
    </a>

    <a href="<?= View::url('/contact.php') ?>" class="ios-bar-btn ios-bar-primary" aria-label="Book or Enquire About an Event">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6"></line>
        <line x1="8" y1="2" x2="8" y2="6"></line>
        <line x1="3" y1="10" x2="21" y2="10"></line>
      </svg>
      <span>Book Now</span>
    </a>
  </aside>

  <!-- Scripts -->
  <script src="<?= View::asset('assets/js/main.js') ?>"></script>
  <script src="<?= View::asset('assets/js/navigation.js') ?>"></script>
  <?php foreach ($pageJs as $js): ?>
    <script src="<?= View::asset($js) ?>"></script>
  <?php endforeach; ?>
</body>
</html>
