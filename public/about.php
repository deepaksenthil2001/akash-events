<?php
/**
 * Akash Event Management - About Us Page
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\View;
use App\Models\Service;

$pageTitle = 'About Us | Akash Event Management - 10+ Years of Craftsmanship';
$pageDescription = 'Learn about Akash Event Management, founded by P. Manobalan in Hosur. Over a decade of floral artistry, wedding stages, and sacred Indian celebrations.';
$currentNav = 'about';
$pageCss = ['assets/css/pages/about.css'];

$aboutIntro = View::setting('about_intro_lead', 'Every celebration carries a heartbeat — a singular moment in time when family, friends, and lifelong memories unite.');
$aboutPhilosophy = View::setting('about_philosophy', 'Founded by P. Manobalan, Akash Event Management has spent over a decade perfecting the delicate balance between timeless Indian ceremonial heritage and modern visual refinement. From intimate naming ceremonies to grand wedding mandapams, we treat every celebration as a bespoke work of art.');
$experienceYears = View::setting('experience_statement', '10+ Years of Craftsmanship & Service');
$founderName = View::setting('founder_name', 'P. Manobalan');
$founderTitle = View::setting('founder_title', 'Master Floral Decorator & Event Architect');
$founderQuote = View::setting('founder_quote', 'When a family entrusts us with their daughter\'s wedding or their grandchild\'s naming ceremony, they are entrusting us with one of the most sacred days of their lives. That trust demands nothing less than perfection in every flower, every light, and every detail.');
$phonePrimary = View::setting('phone_primary', '+91 93442 73433');
$address = View::setting('address_line', '12, Gokul Nagar, Naligabetta Agraharam, Bhavani palace backside, Hosur Tamil Nadu 635109');
$whatsapp = View::setting('whatsapp_number', '919344273433');
$aboutServices = Service::getAll(true);

require_once APP_ROOT . '/app/Views/layouts/header.php';
?>

  <div class="site-container">
    
    <!-- Hero / Header -->
    <section class="about-hero">
      <span class="section-eyebrow">Our Legacy & Philosophy</span>
      <h1 class="section-title">A Decade of Celebrations Crafted with Devotion.</h1>
      <p class="section-description">
        <?= View::e($aboutIntro) ?>
      </p>
    </section>

    <!-- Editorial Story Split -->
    <section class="about-story-grid">
      <div class="about-image-collage">
        <img src="<?= View::asset('assets/images/client/image-008.jpg') ?>" alt="Akash Event Management Mandapam Floral Setup" class="about-image-tall">
        <img src="<?= View::asset('assets/images/client/image-015.jpg') ?>" alt="Akash Event Management Traditional Kolam Stage">
        <img src="<?= View::asset('assets/images/client/image-006.jpg') ?>" alt="Akash Event Management Fresh Chrysanthemum Wall">
      </div>

      <div>
        <span class="section-eyebrow"><?= View::e($experienceYears) ?></span>
        <h2 style="font-size: var(--font-size-3xl); margin-bottom: 1.25rem;">Rooted in Hosur, Celebrated Across the Region.</h2>
        <p style="margin-bottom: 1.25rem;">
          Akash Event Management (MHA) was built from a fundamental passion for fresh floral artistry and celebratory design. Under the leadership of <strong><?= View::e($founderName) ?></strong>, what began as a dedicated floral decoration service has expanded over the past ten years into a premier full-service event styling company.
        </p>
        <p style="margin-bottom: 1.25rem;">
          Based in <strong>Hosur</strong> and catering to weddings, family rituals, and corporate functions throughout Tamil Nadu and Karnataka, we take immense pride in our hands-on craftsmanship. We don't rely on outsourced generic templates; our in-house decorators meticulously construct every backdrop, drape every silk panel, and arrange every flower bloom by hand.
        </p>
        <div class="about-hero-actions" style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 2rem;">
          <a href="<?= View::url('/gallery.php') ?>" class="btn btn-primary">View Our Portfolio</a>
          <a href="<?= View::url('/contact.php') ?>" class="btn btn-secondary">Get in Touch</a>
        </div>
      </div>
    </section>

    <!-- Philosophy & Founder Highlight -->
    <section class="about-philosophy-card">
      <blockquote class="philosophy-quote">
        &ldquo;<?= View::e($founderQuote) ?>&rdquo;
      </blockquote>

      <div class="founder-highlight">
        <img src="<?= View::asset('assets/images/brand/logo.png') ?>" alt="<?= View::e($founderName) ?>" class="founder-avatar" style="object-fit: contain; background: var(--color-maroon-dark); border: 2px solid var(--color-gold); padding: 4px;">
        <div class="founder-info">
          <h4><?= View::e($founderName) ?></h4>
          <p><?= View::e($founderTitle) ?></p>
        </div>
      </div>
    </section>

    <!-- Verified Business Credential & Visiting Card Showcase -->
    <section class="about-card-showcase">
      <div class="about-card-grid">
        <div>
          <span class="section-eyebrow">Verified Credentials</span>
          <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--space-md); color: var(--color-text-primary);">Official Business Identity & Direct Contact</h2>
          <p style="color: var(--color-text-secondary); line-height: var(--line-height-relaxed); margin-bottom: var(--space-lg);">
            Every celebration undertaken by Akash Event Management is backed by verified business credentials, on-time delivery guarantees, and personal oversight by founder P. Manobalan.
          </p>
          <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: var(--font-size-sm); margin-bottom: var(--space-lg);">
            <div><strong>Master Decorator:</strong> <?= View::e($founderName) ?></div>
            <div><strong>Office:</strong> <?= View::e($address) ?></div>
            <div><strong>Contact Phones:</strong> <a href="tel:+919344273433" style="color: var(--color-gold); font-weight: 600;">+91 93442 73433</a> &bull; <a href="tel:+919659999856">+91 96599 99856</a></div>
            <div><strong>Official Email:</strong> <a href="mailto:akasheventmanagement87@gmail.com">akasheventmanagement87@gmail.com</a></div>
          </div>
          <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <a href="https://wa.me/<?= $whatsapp ?>?text=<?= urlencode('Hello P. Manobalan, I would like to consult with you for my event.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-sm">
              WhatsApp P. Manobalan
            </a>
            <a href="tel:+919344273433" class="btn btn-secondary btn-sm">Direct Call</a>
          </div>
        </div>
        <div style="text-align: center;">
          <img src="<?= View::asset('assets/images/brand/visiting-card.jpg') ?>" alt="Akash Event Management Official Visiting Card - P. Manobalan" style="width: 100%; max-width: 480px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--color-border);" loading="lazy">
          <div style="font-size: 0.75rem; color: var(--color-text-tertiary); margin-top: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Official Registered Business Card</div>
        </div>
      </div>
    </section>

    <!-- Celebrations We Cater To -->
    <section style="margin: var(--space-5xl) 0;">
      <div class="section-header text-center">
        <span class="section-eyebrow">Our Breadth</span>
        <h2 class="section-title">Celebrations We Bring to Life</h2>
        <p class="section-description">
          From intimate auspicious poojas to grand five-hundred-guest receptions, we design spaces that honor tradition and captivate guests.
        </p>
      </div>

      <div class="services-grid about-cater-grid">
        <?php if (!empty($aboutServices)): ?>
          <?php foreach ($aboutServices as $srv): ?>
            <div class="card" style="padding: var(--space-xl);">
              <h3 style="font-size: var(--font-size-xl); margin-bottom: 0.5rem;"><?= View::e($srv['title']) ?></h3>
              <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); line-height: 1.6;">
                <?= View::e($srv['short_description']) ?>
              </p>
              <a href="<?= View::url('/service-details.php?slug=' . urlencode($srv['slug'])) ?>" class="link-gold" style="font-size: 0.825rem; font-weight: 600; margin-top: 0.75rem; display: inline-flex; align-items: center; gap: 4px;">
                View Details &rarr;
              </a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="card" style="padding: var(--space-xl);">
            <h3 style="font-size: var(--font-size-xl); margin-bottom: 0.5rem;">Weddings &amp; Receptions</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary);">
              Monumental stages with fresh flower arches, royal diwan seating, and custom monogram backdrops.
            </p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Call to action -->
    <section class="cta-banner">
      <h2 class="cta-headline">Plan Your Special Event with Us</h2>
      <p class="cta-subtext">
        Consult with P. Manobalan today for honest advice, customized themes, and transparent quotes.
      </p>
      <div class="cta-buttons">
        <a href="<?= View::url('/contact.php') ?>" class="btn btn-gold btn-lg">Send Enquiry</a>
        <a href="https://wa.me/<?= $whatsapp ?>?text=<?= urlencode('Hello P. Manobalan, I would like to consult with you for my event.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg">
          Chat on WhatsApp
        </a>
      </div>
    </section>

  </div>

<?php require_once APP_ROOT . '/app/Views/layouts/footer.php'; ?>
