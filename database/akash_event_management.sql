-- ==============================================================
-- AKASH EVENT MANAGEMENT
-- Official Production Database Schema & Seed Data
-- Compatible with Hostinger hPanel, MySQL 8.x, 5.7, MariaDB, and phpMyAdmin
-- Character Set: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ==============================================================

-- NOTE FOR HOSTINGER USERS:
-- 1. In Hostinger hPanel -> Databases, create a new MySQL Database (e.g. u123456789_akash).
-- 2. Open phpMyAdmin for that database.
-- 3. Click the Import tab, select this file, and click 'Go' / 'Import'.
-- 4. All tables and initial data will be created automatically.

SET FOREIGN_KEY_CHECKS=0;

-- --------------------------------------------------------------
-- Table: `admins`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'superadmin',
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'akasheventmanagement87@gmail.com', '$2y$10$GAbP9UVh8dQNXf5EBoi0iuWdo.eJ4ivU58ZPoKuKEtMegIHLYQXla', 'P. Manobalan', 'superadmin', NULL, '2026-10-13 18:08:00', '2026-10-13 18:08:00');

-- --------------------------------------------------------------
-- Table: `site_settings`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(60) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `setting_group` VARCHAR(30) NOT NULL DEFAULT 'general',
  `description` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_settings_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `description`, `updated_at`) VALUES
(1, 'business_name', 'Akash Event Management', 'general', 'Official legal business name', '2026-10-13 18:08:00'),
(2, 'brand_initials', 'MHA', 'general', 'Brand monogram / emblem initials', '2026-10-13 18:08:00'),
(3, 'founder_name', 'P. Manobalan', 'general', 'Founder & Lead Decorator', '2026-10-13 18:08:00'),
(4, 'founder_title', 'Master Floral Decorator & Event Architect', 'general', 'Founder official designation', '2026-10-13 18:08:00'),
(5, 'experience_statement', '10+ Years of Craftsmanship & Service', 'general', 'Verified experience milestone', '2026-10-13 18:08:00'),
(6, 'phone_primary', '+91 93442 73433', 'contact', 'Primary verified phone number', '2026-10-13 18:08:00'),
(7, 'phone_secondary', '+91 96599 99856', 'contact', 'Secondary verified phone number', '2026-10-13 18:08:00'),
(8, 'email_primary', 'akasheventmanagement87@gmail.com', 'contact', 'Official enquiries email address', '2026-10-13 18:08:00'),
(9, 'email_secondary', 'akasheventmanagement@gmail.com', 'contact', 'Alternative business email address', '2026-10-13 18:08:00'),
(10, 'address_line', '12, Gokul Nagar, Naligabetta Agraharam, Bhavani palace backside, Hosur Tamil Nadu 635109', 'contact', 'Physical office / venue location', '2026-10-13 18:08:00'),
(11, 'service_areas', 'Hosur, Krishnagiri, Bangalore & Surrounding Regions', 'contact', 'Primary coverage regions', '2026-10-13 18:08:00'),
(12, 'instagram_url', 'https://www.instagram.com/akasheventmanagement', 'social', 'Official Instagram profile', '2026-10-13 18:08:00'),
(13, 'google_business_url', 'https://share.google/nUP5Exqa3K1doUEfd', 'social', 'Official Google Business Profile link', '2026-10-13 18:08:00'),
(14, 'whatsapp_number', 919344273433, 'social', 'WhatsApp enquiry number (with country code)', '2026-10-13 18:08:00'),
(15, 'hero_eyebrow', 'Hosur & Bangalore | 10+ Years of Celebrations', 'hero', 'Eyebrow label above hero title', '2026-10-13 18:08:00'),
(16, 'hero_headline', 'Creating Moments Worth Remembering.', 'hero', 'Primary homepage headline', '2026-10-13 18:08:00'),
(17, 'hero_subheadline', 'Thoughtfully designed event experiences, bespoke stage architecture, and breathtaking floral artistry tailored for weddings, receptions, and life milestones.', 'hero', 'Hero supporting paragraph', '2026-10-13 18:08:00'),
(18, 'hero_desktop_image', '/assets/images/hero/hero-desktop.jpg', 'hero', 'Primary desktop hero photograph', '2026-10-13 18:08:00'),
(19, 'hero_mobile_image', '/assets/images/hero/hero-mobile.jpg', 'hero', 'Art-directed portrait mobile hero image', '2026-10-13 18:08:00'),
(20, 'hero_cta_primary_text', 'Explore Our Work', 'hero', 'Primary hero button label', '2026-10-13 18:08:00'),
(21, 'hero_cta_primary_link', '/gallery.php', 'hero', 'Primary hero button URL', '2026-10-13 18:08:00'),
(22, 'hero_cta_secondary_text', 'Plan Your Celebration', 'hero', 'Secondary hero button label', '2026-10-13 18:08:00'),
(23, 'hero_cta_secondary_link', '/contact.php', 'hero', 'Secondary hero button URL', '2026-10-13 18:08:00'),
(24, 'about_intro_lead', 'Every celebration carries a heartbeat — a singular moment in time when family, friends, and lifelong memories unite.', 'about', 'About page lead statement', '2026-10-13 18:08:00'),
(25, 'about_philosophy', 'Founded by P. Manobalan, Akash Event Management has spent over a decade perfecting the delicate balance between timeless Indian ceremonial heritage and modern visual refinement. From intimate naming ceremonies to grand wedding mandapams, we treat every celebration as a bespoke work of art.', 'about', 'Core company philosophy', '2026-10-13 18:08:00'),
(26, 'meta_title_default', 'Akash Event Management | Premium Stage Decoration & Event Planning in Hosur & Bangalore', 'seo', 'Default SEO Page Title', '2026-10-13 18:08:00'),
(27, 'meta_description_default', 'Akash Event Management specializes in luxury wedding stages, floral decoration, reception mandapams, baby showers, and birthday setups across Hosur and Bangalore. 10+ years of excellence.', 'seo', 'Default SEO Meta Description', '2026-10-13 18:08:00'),
(28, 'stat_1_val', '10+', 'editorial', NULL, '2026-10-29 14:51:12'),
(29, 'stat_1_label', 'Years of Craftsmanship', 'editorial', NULL, '2026-10-29 14:51:12'),
(30, 'stat_2_val', '1,200+', 'editorial', NULL, '2026-10-29 14:51:12'),
(31, 'stat_2_label', 'Celebrations Crafted', 'editorial', NULL, '2026-10-29 14:51:12'),
(32, 'stat_3_val', '100%', 'editorial', NULL, '2026-10-29 14:51:12'),
(33, 'stat_3_label', 'Bespoke Artistry', 'editorial', NULL, '2026-10-29 14:51:12'),
(34, 'stat_4_val', '8+', 'editorial', NULL, '2026-10-29 14:51:12'),
(35, 'stat_4_label', 'Core Event Domains', 'editorial', NULL, '2026-10-29 14:51:12'),
(36, 'home_intro_eyebrow', 'The Craft of Celebration', 'editorial', NULL, '2026-10-29 14:51:12'),
(37, 'home_intro_title', 'Elevating Rituals into Living Works of Art.', 'editorial', NULL, '2026-10-29 14:51:12'),
(38, 'home_intro_p1', 'Founded by master decorator P. Manobalan, Akash Event Management has spent over a decade curating extraordinary celebration spaces across Hosur, Krishnagiri, and Bangalore.', 'editorial', NULL, '2026-10-29 14:51:12'),
(39, 'home_intro_p2', 'Whether designing a monumental floral mandapam, an intimate champagne-gold engagement stage, or a heartwarming naming ceremony, we harmonize fresh floral fragrances, architectural silhouettes, and warm ambient lighting.', 'editorial', NULL, '2026-10-29 14:51:12'),
(40, 'home_story_eyebrow', 'Precision & Execution', 'editorial', NULL, '2026-10-29 14:51:12'),
(41, 'home_story_title', 'From Empty Hall to Cinematic Wonder.', 'editorial', NULL, '2026-10-29 14:51:12'),
(42, 'home_story_p1', 'An empty convention hall or outdoor lawn is a blank canvas. Our process begins with understanding the lighting angles, sightlines, and ceremonial flow.', 'editorial', NULL, '2026-10-29 14:51:12'),
(43, 'home_story_p2', 'Our master floral teams arrive hours ahead to hand-wire fresh roses, jasmines, and lilies. Spotlights and chandeliers are meticulously positioned to eliminate shadows, creating the dream backdrop for your wedding photography.', 'editorial', NULL, '2026-10-29 14:51:12'),
(44, 'founder_quote', 'When a family entrusts us with their daughter\'s wedding or their grandchild\'s naming ceremony, they are entrusting us with one of the most sacred days of their lives. That trust demands nothing less than perfection in every flower, every light, and every detail.', 'editorial', NULL, '2026-10-29 14:51:12'),
(45, 'about_card_1_title', 'Fresh Floral Mandapams & Stage Architecture', 'editorial', NULL, '2026-10-29 14:51:12'),
(46, 'about_card_1_desc', 'Authentic marigold cascades, Bangalore rose canopies, and sculpted wooden backdrops designed for timeless rituals.', 'editorial', NULL, '2026-10-29 14:51:12'),
(47, 'about_card_2_title', 'Bespoke Themes & Milestone Setups', 'editorial', NULL, '2026-10-29 14:51:12'),
(48, 'about_card_2_desc', 'Custom pastel themes, floral cradle rings, and playful backdrops for naming ceremonies and 1st birthdays.', 'editorial', NULL, '2026-10-29 14:51:12'),
(49, 'about_card_3_title', 'Light Production & DJ Sound', 'editorial', NULL, '2026-10-29 14:51:12'),
(50, 'about_card_3_desc', 'Building facade fairy lighting, garden light tunnels, professional moving head beam trusses, and acoustic sound engineering.', 'editorial', NULL, '2026-10-29 14:51:12'),
(51, 'services_header_title', 'Crafting Unforgettable Experiences', 'editorial', NULL, '2026-10-29 14:51:12'),
(52, 'services_header_subtitle', 'From sacred mandapams to contemporary illuminated stages, explore our full spectrum of event styling and production services across Hosur and Bangalore.', 'editorial', NULL, '2026-10-29 14:51:12'),
(53, 'gallery_header_title', 'The Artistry of Every Celebration', 'editorial', NULL, '2026-10-29 14:51:12'),
(54, 'gallery_header_subtitle', 'Explore our curated portfolio of authentic celebrations. Each event showcases a bespoke collection of photographs capturing stage architecture, fresh flower arrangements, and ceremonial ambiance.', 'editorial', NULL, '2026-10-29 14:51:12');

-- --------------------------------------------------------------
-- Table: `services`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(80) NOT NULL UNIQUE,
  `title` VARCHAR(120) NOT NULL,
  `short_description` VARCHAR(255) NOT NULL,
  `full_description` TEXT NOT NULL,
  `cover_image` VARCHAR(255) NOT NULL,
  `banner_image` VARCHAR(255) NULL,
  `features_list` TEXT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `meta_title` VARCHAR(160) NULL,
  `meta_description` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_services_published_order` (`is_published`, `display_order`),
  INDEX `idx_services_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`id`, `slug`, `title`, `short_description`, `full_description`, `cover_image`, `banner_image`, `features_list`, `display_order`, `is_published`, `is_featured`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'wedding-reception-decoration', 'Wedding & Reception Stage Decoration', 'Opulent floral backdrops, architectural curved arches, and royal throne seating tailored for unforgettable wedding celebrations.', 'We create immersive wedding and reception environments that evoke grandeur and warmth. Every wedding design is custom-engineered to complement the venue dimensions, color themes, and family preferences. From cascading fresh flower arches to crystal chandeliers and delicate fairy light drapes, our team delivers seamless craftsmanship from concept to conclusion.', '/assets/images/services/wedding.jpg', '/assets/images/client/image-005.jpg', 'Custom floral backdrops with exotic and fresh blossoms\nRoyal couple sofa and velvet diwan configurations\nCurated ambient spotlights and warm chandelier arrays\nMandapam coordination and entrance floral arches\nComplete on-site setup and teardown management', 1, 1, 1, 'Wedding & Reception Decoration in Hosur | Akash Event Management', 'Transform your wedding with premium floral stage decoration, chandeliers, and royal seating in Hosur and Bangalore.', '2026-10-13 18:08:00', '2026-10-13 18:08:00'),
(2, 'engagement-stage-decoration', 'Engagement Celebrations', 'Contemporary romantic stages combining champagne gold frameworks, warm candlelight accents, and hand-selected floral blooms.', 'An engagement marks the beginning of a lifelong journey. We design intimate yet striking stages featuring delicate geometric arches, soft pastel drapery, fluted pedestals, and bespoke couple monogram backdrops that photograph flawlessly from every angle.', '/assets/images/services/engagement.jpg', '/assets/images/client/image-013.jpg', 'Bespoke monogram backdrop rings\nChampagne gold and white aesthetic palettes\nDelicate rose, peony, and orchid arrangements\nAmbient warm uplighting and candelabras\nCompact and expansive hall configurations', 2, 1, 1, 'Engagement Stage Decoration Hosur | Akash Event Management', 'Bespoke engagement stage decorations with champagne gold accents, floral rings, and warm lighting.', '2026-10-13 18:08:00', '2026-10-13 18:08:00'),
(3, 'traditional-ceremonies', 'Traditional Functions & Seemantham', 'Authentic cultural setups for Seemantham (Baby Shower), Half Saree, Puberty functions, and auspicious family rituals.', 'Honoring sacred customs requires reverence and artistry. Our traditional setups blend fragrant jasmine, marigold, and lotus motifs with authentic Kolam wall art, brass deepam oil lamps, banana trunk headers, and traditional Seer plate display pedestals.', '/assets/images/services/baby-shower.jpg', '/assets/images/client/image-023.jpg', 'Traditional Kolam and Jali architectural panels\nFresh lotus, chrysanthemum, and jasmine hangings\nBrass deepam lamps and decorative brass urns\nSeer thattu and thamboolam gift display setups\nCustom green turf or traditional silk carpet flooring', 3, 1, 1, 'Traditional Seemantham & Half Saree Decoration Hosur', 'Authentic South Indian traditional event decoration for Seemantham, Half Saree, and family celebrations.', '2026-10-13 18:08:00', '2026-10-13 18:08:00'),
(4, 'naming-ceremonies', 'Naming Ceremony Celebrations', 'Charming sacred stages celebrating new beginnings, featuring divine Murugan and Krishna themes with floral cradles.', 'Welcoming a child is a sacred blessing. We craft heartwarming naming ceremony settings featuring divine temple backdrops, custom photo displays, suspended jasmine strings, and beautifully decorated cradles that create a serene atmosphere for elders and guests.', '/assets/images/services/naming-ceremony.jpg', '/assets/images/client/image-024.jpg', 'Lord Murugan and Lord Krishna thematic backdrops\nFresh floral decorated cradles and swing setups\nWarm glowing candle stands and hanging floral drops\nCustom baby photo prints and personalized signage\nFamily-friendly seating and stage platforms', 4, 1, 1, 'Naming Ceremony Decoration in Hosur | Akash Event Management', 'Traditional and modern naming ceremony decorations with divine themes and floral cradle setups.', '2026-10-13 18:08:00', '2026-10-13 18:08:00'),
(5, 'birthday-celebrations', 'Birthday & Milestone Celebrations', 'Playful, vibrant, and artistic balloon sculptures, fairy-tale arches, LED neon signs, and themed cake plinths.', 'From 1st birthday milestone celebrations to grand jubilee anniversaries, our team designs captivating environments with organic balloon installations, custom 3D cutouts, illuminated age numbers, wooden picket fences, and dessert table styling.', '/assets/images/services/birthday.jpg', '/assets/images/client/image-018.jpg', 'Organic pastel and chrome balloon arches\nIlluminated LED neon signs and marquee numbers\nFluted dessert plinths and thematic cake backdrops\nCustom cartoon, butterfly, jungle, or floral themes\nInteractive photo booths and entrance arches', 5, 1, 1, 'Birthday Decoration Services Hosur | Akash Event Management', 'Creative birthday event decoration with organic balloon arches, neon signage, and custom theme backdrops.', '2026-10-13 18:08:00', '2026-10-13 18:08:00'),
(6, 'flower-decoration', 'Bespoke Floral Artistry', 'Master floral installations by P. Manobalan, sourcing premium fresh blooms for stages, entrance gates, and ceremonial cars.', 'With over a decade of dedicated floral craftsmanship, P. Manobalan leads our floral division in creating show-stopping installations. We hand-select farm-fresh blooms, exotic Dutch roses, baby breath, orchids, and traditional South Indian flowers to create lush living art.', '/assets/images/services/flower-decoration.jpg', '/assets/images/client/image-006.jpg', 'Dense natural floral walls and cascading ceilings\nEntrance archway floral wraps and walkway pillars\nBride & groom ceremonial garlands (Maalai)\nWedding car floral styling and VIP vehicle decor\nCenterpieces and dining table floral arrangements', 6, 1, 1, 'Fresh Flower Decoration Hosur | P. Manobalan Akash Events', 'Specialist fresh flower decorations for weddings, receptions, and temples by master decorator P. Manobalan.', '2026-10-13 18:08:00', '2026-10-13 18:08:00'),
(7, 'light-decoration-production', 'Architectural Light & Production', 'Spectacular building facade illumination, glowing fairy-light walkways, professional stage lighting, and DJ sound systems.', 'Lighting transforms ordinary architecture into magical wonderlands. We provide full building exterior fairy-light draping, outdoor tree canopies, moving head beams for dance floors, stage trusses, and crystal-clear sound reinforcement for music and ceremonies.', '/assets/images/services/light-decoration.jpg', '/assets/images/client/image-026.jpg', 'Multi-story residential and commercial building facade lights\nFairy-light walkway tunnels and garden canopy lighting\nProfessional stage lighting truss with moving heads & beam effects\nHigh-fidelity sound reinforcement and DJ console setups\nEmergency backup power and certified safe electrical cabling', 7, '0', '0', 'Light Decoration & DJ Sound Services Hosur | Akash Events', 'Full building fairy lighting, stage lighting trusses, and DJ sound production in Hosur and surrounding regions.', '2026-10-13 18:08:00', '2026-10-13 18:08:00'),
(8, 'catering-tent-house', 'Catering & Tent House Infrastructure', 'Complete event infrastructure including premium buffet dining setups, luxury shamianas, banquet seating, and guest logistics.', 'Beyond stage decoration, we provide the backbone of high-capacity events. Our tent house inventory includes weatherproof German pagodas, decorative ceiling drapes, banquet tables, VIP chairs, and coordinated buffet presentation lines staffed by hospitality professionals.', '/assets/images/services/mandapam.jpg', '/assets/images/client/image-001.jpg', 'Weatherproof German pagodas and shamiana canopies\nSilk ceiling drapery and chandeliers for dining halls\nBanquet chairs with satin bow ties and round tables\nBuffet line heating chaffing dishes and presentation decor\nCatering coordination and dining area sanitation', 8, '0', '0', 'Tent House & Catering Setup Hosur | Akash Event Management', 'Complete tent house rentals, shamiana structures, banquet seating, and buffet catering presentation in Hosur.', '2026-10-13 18:08:00', '2026-10-13 18:08:00');

-- --------------------------------------------------------------
-- Table: `gallery_categories`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `gallery_categories`;
CREATE TABLE `gallery_categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(60) NOT NULL UNIQUE,
  `name` VARCHAR(80) NOT NULL,
  `description` VARCHAR(255) NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_categories_active_order` (`is_active`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gallery_categories` (`id`, `slug`, `name`, `description`, `display_order`, `is_active`, `created_at`) VALUES
(1, 'weddings', 'Weddings & Receptions', 'Grand floral stages, luxury reception backdrops, and timeless mandapams', 1, 1, '2026-10-13 18:08:00'),
(2, 'engagements', 'Engagements', 'Romantic floral arches, crystal chandeliers, and refined couple seating', 2, 1, '2026-10-13 18:08:00'),
(3, 'traditional', 'Traditional Ceremonies', 'Seemantham, Half Saree, Puberty, and Kolam-inspired ritual backdrops', 3, 1, '2026-10-13 18:08:00'),
(4, 'birthdays', 'Birthdays & Milestones', 'Bespoke cartoon & fairy themes, balloon arches, and custom plinths', 4, 1, '2026-10-13 18:08:00'),
(5, 'naming-ceremonies', 'Naming Ceremonies', 'Lord Murugan, Lord Krishna, and floral cradling celebrations', 5, 1, '2026-10-13 18:08:00'),
(6, 'lighting-sound', 'Lighting & Ambient Production', 'Architectural building illumination, fairy light tunnels, DJ truss & sound', 6, 1, '2026-10-13 18:08:00');

-- --------------------------------------------------------------
-- Table: `gallery_events`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `gallery_events`;
CREATE TABLE `gallery_events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT UNSIGNED NULL,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `event_type` VARCHAR(80) NULL,
  `event_date` DATE NULL,
  `location` VARCHAR(150) NULL,
  `description` TEXT NULL,
  `cover_image` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_events_published` (`is_published`, `display_order`),
  INDEX `idx_events_category` (`category_id`),
  CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`)
    REFERENCES `gallery_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gallery_events` (`id`, `category_id`, `title`, `slug`, `event_type`, `event_date`, `location`, `description`, `cover_image`, `is_featured`, `is_published`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Grand Royal Wedding & Reception', 'grand-royal-wedding-reception', 'Weddings & Receptions', '2026-09-18', 'Hosur Convention Center, Hosur', 'A monumental reception stage featuring handcrafted triple curved champagne gold arches, crystal chandeliers, royal velvet diwan seating, and cascading exotic fresh floral borders.', '/assets/images/client/image-005.jpg', 1, 1, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30'),
(2, 2, 'Champagne Gold & Rose Floral Engagement', 'champagne-gold-rose-engagement', 'Engagements', '2026-08-22', 'Bhavani Palace Hall, Hosur', 'Contemporary romantic engagement celebration with curved floral arches, illuminated couple monogram, fluted love seat, and ambient warm spotlights.', '/assets/images/client/image-016.jpg', 1, 1, 2, '2026-10-29 13:15:30', '2026-10-29 13:15:30'),
(3, 3, 'Traditional Seemantham & Sacred Rituals', 'traditional-seemantham-sacred-rituals', 'Traditional Ceremonies', '2026-07-14', 'Gokul Nagar, Hosur', 'Culturally authentic South Indian baby shower celebration featuring hand-carved lotus motifs, brass deepam oil lamps, fresh marigold hangings, and traditional seer plate display.', '/assets/images/client/image-023.jpg', 1, 1, 3, '2026-10-29 13:15:30', '2026-10-29 13:15:30'),
(4, 4, 'Fairytale 1st Birthday Milestone Meadow', 'fairytale-1st-birthday-milestone', 'Birthdays & Milestones', '2026-06-30', 'Electronic City, Bangalore', 'A magical first birthday kingdom featuring organic pastel balloon arches, illuminated marquee light numbers, wooden garden fences, and fluted dessert plinths.', '/assets/images/client/image-018.jpg', 1, 1, 4, '2026-10-29 13:15:30', '2026-10-29 13:15:30'),
(5, 5, 'Divine Bala Murugan Naming Ceremony', 'divine-bala-murugan-naming-ceremony', 'Naming Ceremonies', '2026-05-19', 'Krishnagiri Grand Hall', 'A sacred temple-inspired celebration welcoming a newborn with Lord Murugan artwork, fresh floral cradling swing, suspended lotus drops, and traditional oil lamps.', '/assets/images/client/image-024.jpg', 1, 1, 5, '2026-10-29 13:15:30', '2026-10-29 13:15:30'),
(6, 6, 'Architectural Facade Lighting & Sound Production', 'architectural-facade-lighting-production', 'Lighting & Ambient Production', '2026-04-12', 'Hosur Residential & Commercial Venues', 'Full building exterior fairy-light draping, garden walkway tunnels, moving head beam trusses, and crystal-clear sound reinforcement for celebrations.', '/assets/images/client/image-026.jpg', 1, 1, 6, '2026-10-29 13:15:30', '2026-10-29 13:15:30');

-- --------------------------------------------------------------
-- Table: `gallery_media`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `gallery_media`;
CREATE TABLE `gallery_media` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT UNSIGNED NULL,
  `event_id` INT UNSIGNED NULL,
  `title` VARCHAR(120) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `media_type` ENUM('image', 'video') NOT NULL DEFAULT 'image',
  `file_path` VARCHAR(255) NOT NULL,
  `mobile_file_path` VARCHAR(255) NULL,
  `thumbnail_path` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_concept` TINYINT(1) NOT NULL DEFAULT 0,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_gallery_published_order` (`is_published`, `display_order`),
  INDEX `idx_gallery_featured` (`is_featured`),
  INDEX `idx_gallery_category` (`category_id`),
  INDEX `idx_gallery_event` (`event_id`),
  CONSTRAINT `fk_gallery_category` FOREIGN KEY (`category_id`)
    REFERENCES `gallery_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_gallery_event` FOREIGN KEY (`event_id`)
    REFERENCES `gallery_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gallery_media` (`id`, `category_id`, `title`, `caption`, `media_type`, `file_path`, `mobile_file_path`, `thumbnail_path`, `is_featured`, `is_concept`, `display_order`, `is_published`, `created_at`, `updated_at`, `event_id`) VALUES
(1, 1, 'Champagne Gold & Ivory Arch Stage', 'Triple curved gold geometric arches with garden roses, chandeliers, and fluted love seat', 'image', '/assets/images/client/image-013.jpg', NULL, NULL, 1, '0', 1, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(2, 1, 'Grand White & Peach Reception Stage', 'Circular floral arch with crystal chandeliers and carved teak royal throne', 'image', '/assets/images/client/image-005.jpg', NULL, NULL, 1, '0', 2, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(3, 1, 'Emerald Glow Illuminated Stage', 'Modern shell-wave illuminated neon arches with rich green velvet backdrop and floral carpet', 'image', '/assets/images/client/image-003.jpg', NULL, NULL, 1, '0', 3, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(4, 1, 'Traditional Mandapam Floral Setup', 'Draped pillars with jasmine tassels, Subha Mangalam kolam motif, and brass deepam lamps', 'image', '/assets/images/client/image-008.jpg', NULL, NULL, 1, '0', 4, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(5, 2, 'Romantic Heart Floral Arch', 'Heart-shaped fresh floral arch with fairy lights and white amphora vases', 'image', '/assets/images/client/image-016.jpg', NULL, NULL, 1, '0', 5, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(6, 2, 'Circular Floral Ring Reception', 'Full circle floral arch with hanging golden bells, carved gold sofa, and gerbera floor arrangements', 'image', '/assets/images/client/image-002.jpg', NULL, NULL, '0', '0', 6, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(7, 3, 'Royal Kolam & Brass Deepam Stage', 'Traditional South Indian maroon & wood jali backdrop with seer plates and brass lamps', 'image', '/assets/images/client/image-015.jpg', NULL, NULL, 1, '0', 7, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(8, 3, 'Traditional Seemantham Lotus Stage', 'Lotus centerpiece backdrop with parrots, silk pleated stage skirting, and fresh floral borders', 'image', '/assets/images/client/image-023.jpg', NULL, NULL, 1, '0', 8, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(9, 4, 'Bespoke 1st Birthday Pastel Meadow', 'Rishi birthday setup with organic balloon garland, fluted plinths, and wooden garden fence', 'image', '/assets/images/client/image-018.jpg', NULL, NULL, 1, '0', 9, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(10, 4, 'Golden Butterfly Fairytale Setup', 'Vertical circular backdrop with balloon garlands, illuminated LED number 1, and butterfly accents', 'image', '/assets/images/client/image-019.jpg', NULL, NULL, '0', '0', 10, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(11, 5, 'Divine Bala Murugan Naming Ceremony', 'Temple-inspired Lord Murugan backdrop with candle posts and fresh floral header garland', 'image', '/assets/images/client/image-024.jpg', NULL, NULL, 1, '0', 11, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(12, 1, 'Blossom Floral Wall & Gold Urns', 'Dense white chrysanthemum flower wall with gold monogram and antique urns', 'image', '/assets/images/client/image-006.jpg', NULL, NULL, '0', '0', 12, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(13, 1, 'Heart Arch & Jali Lattice Stage', 'Geometric heart floral arch with gold lattice jali screens and white drape accents', 'image', '/assets/images/client/image-007.jpg', NULL, NULL, '0', '0', 13, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(14, 1, 'Classic White & Jasmine Hall Stage', 'White curtain backdrop with fairy lights, jasmine tassels, and vintage diwan seating', 'image', '/assets/images/client/image-004.jpg', NULL, NULL, '0', '0', 14, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(15, 6, 'Architectural Facade Fairy Lights & Stage Production', 'Illuminated building facade and professional stage light trusses with honeycomb LED displays', 'image', '/assets/images/client/image-026.jpg', NULL, NULL, 1, '0', 15, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(16, 1, 'Editorial Concept: Luxury Royal Wedding Pavilion', 'Architectural warm ivory draping, delicate champagne gold curved arches, lavish floral cascades', 'image', '/assets/images/generated/hero_wedding_desktop.jpg', '/assets/images/generated/hero_wedding_mobile.jpg', NULL, 1, 1, 16, 1, '2026-10-13 18:08:00', '2026-10-13 18:08:00', NULL),
(18, 1, 'Grand White & Peach Reception Stage', 'Circular floral arch with crystal chandeliers and carved teak royal throne', 'image', '/assets/images/client/image-005.jpg', NULL, NULL, 1, '0', 1, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(19, 1, 'Champagne Gold & Ivory Arch Stage', 'Triple curved gold geometric arches with garden roses, chandeliers, and fluted love seat', 'image', '/assets/images/client/image-013.jpg', NULL, NULL, '0', '0', 2, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(20, 1, 'Emerald Glow Illuminated Stage', 'Modern shell-wave illuminated neon arches with rich green velvet backdrop', 'image', '/assets/images/client/image-003.jpg', NULL, NULL, '0', '0', 3, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(21, 1, 'Traditional Mandapam Floral Setup', 'Draped pillars with jasmine tassels, Subha Mangalam kolam motif, and brass deepam lamps', 'image', '/assets/images/client/image-008.jpg', NULL, NULL, '0', '0', 4, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(22, 1, 'Blossom Floral Wall & Gold Urns', 'Dense white chrysanthemum flower wall with gold monogram and antique urns', 'image', '/assets/images/client/image-006.jpg', NULL, NULL, '0', '0', 5, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(23, 1, 'Heart Arch & Jali Lattice Stage', 'Geometric heart floral arch with gold lattice jali screens and white drape accents', 'image', '/assets/images/client/image-007.jpg', NULL, NULL, '0', '0', 6, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(24, 1, 'Classic White & Jasmine Hall Stage', 'White curtain backdrop with fairy lights, jasmine tassels, and vintage diwan seating', 'image', '/assets/images/client/image-004.jpg', NULL, NULL, '0', '0', 7, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(25, 1, 'Circular Floral Ring Reception', 'Full circle floral arch with hanging golden bells, carved gold sofa, and gerbera floor arrangements', 'image', '/assets/images/client/image-002.jpg', NULL, NULL, '0', '0', 8, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 1),
(26, 2, 'Romantic Heart Floral Arch', 'Heart-shaped fresh floral arch with fairy lights and white amphora vases', 'image', '/assets/images/client/image-016.jpg', NULL, NULL, 1, '0', 1, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 2),
(27, 2, 'Champagne Gold Stage Architecture', 'Curved gold geometric arches with garden roses and chandeliers', 'image', '/assets/images/client/image-013.jpg', NULL, NULL, '0', '0', 2, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 2),
(28, 2, 'Full Circle Floral Couple Ring', 'Round backdrop ring with floral wrap and couple seating', 'image', '/assets/images/client/image-002.jpg', NULL, NULL, '0', '0', 3, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 2),
(29, 2, 'Rose Garland Entrance Arch', 'Welcoming entrance archway with fresh Dutch roses and greenery', 'image', '/assets/images/client/image-012.jpg', NULL, NULL, '0', '0', 4, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 2),
(30, 2, 'Ambient Candle & Floral Pedestals', 'Warm candelabra arrays and floral arrangements along stage walkway', 'image', '/assets/images/client/image-014.jpg', NULL, NULL, '0', '0', 5, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 2),
(31, 2, 'Monogram Jali Couple Stage', 'Geometric heart arch with gold lattice screens and couple monogram', 'image', '/assets/images/client/image-007.jpg', NULL, NULL, '0', '0', 6, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 2),
(32, 3, 'Traditional Seemantham Lotus Stage', 'Lotus centerpiece backdrop with parrots, silk pleated stage skirting, and fresh floral borders', 'image', '/assets/images/client/image-023.jpg', NULL, NULL, 1, '0', 1, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 3),
(33, 3, 'Royal Kolam & Brass Deepam Stage', 'Traditional South Indian maroon & wood jali backdrop with seer plates and brass lamps', 'image', '/assets/images/client/image-015.jpg', NULL, NULL, '0', '0', 2, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 3),
(34, 3, 'Jasmine Tassels & Kolam Header', 'Fresh jasmine garlands hanging above auspicious ceremonial stage', 'image', '/assets/images/client/image-008.jpg', NULL, NULL, '0', '0', 3, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 3),
(35, 3, 'Seer Thattu Gift Presentation Setup', 'Bespoke stepped display pedestals for ceremonial fruits and wedding gifts', 'image', '/assets/images/client/image-020.jpg', NULL, NULL, '0', '0', 4, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 3),
(36, 3, 'Traditional Silk Pleated Stage Skirting', 'Vibrant gold and maroon silk pleated drapery along stage perimeter', 'image', '/assets/images/client/image-021.jpg', NULL, NULL, '0', '0', 5, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 3),
(37, 4, 'Rishi 1st Birthday Pastel Meadow', 'Bespoke pastel balloon arch with wooden picket fence and custom signage', 'image', '/assets/images/client/image-018.jpg', NULL, NULL, 1, '0', 1, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 4),
(38, 4, 'Golden Butterfly Fairytale Setup', 'Vertical circular backdrop with balloon garlands, illuminated LED number 1, and butterfly accents', 'image', '/assets/images/client/image-019.jpg', NULL, NULL, '0', '0', 2, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 4),
(39, 4, 'Organic Chrome Balloon Sculptures', 'Metallic gold and soft ivory organic balloon clusters', 'image', '/assets/images/client/image-017.jpg', NULL, NULL, '0', '0', 3, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 4),
(40, 4, 'Marquee Light Number One', 'Warm glowing marquee number 1 centerpiece with balloon clouds', 'image', '/assets/images/client/image-022.jpg', NULL, NULL, '0', '0', 4, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 4),
(41, 4, 'Fluted Dessert Plinths & Themed Cake Table', 'Cylindrical cake plinths with floral toppers and themed props', 'image', '/assets/images/client/image-025.jpg', NULL, NULL, '0', '0', 5, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 4),
(42, 5, 'Divine Bala Murugan Thematic Stage', 'Temple-inspired Lord Murugan backdrop with candle posts and fresh floral header garland', 'image', '/assets/images/client/image-024.jpg', NULL, NULL, 1, '0', 1, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 5),
(43, 5, 'Fresh Floral Decorated Cradle Swing', 'Traditional wooden cradle adorned with fresh jasmine, roses, and silk drapes', 'image', '/assets/images/client/image-011.jpg', NULL, NULL, '0', '0', 2, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 5),
(44, 5, 'Jasmine Hanging Strings & Candle Posts', 'Auspicious suspended floral drops and warm deepam oil lamps', 'image', '/assets/images/client/image-010.jpg', NULL, NULL, '0', '0', 3, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 5),
(45, 5, 'Floral Welcome Entrance Arch', 'Grand hall entrance archway with fresh blossoms and auspicious signs', 'image', '/assets/images/client/image-009.jpg', NULL, NULL, '0', '0', 4, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 5),
(46, 5, 'Lord Krishna & Peacock Motif Backdrop', 'Sacred blue and gold peacock motif backdrop with marigold garlands', 'image', '/assets/images/client/image-027.jpg', NULL, NULL, '0', '0', 5, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 5),
(47, 6, 'Illuminated Building Facade Fairy Lights', 'Multi-story residential and commercial building facade lights and stage light trusses', 'image', '/assets/images/client/image-026.jpg', NULL, NULL, 1, '0', 1, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 6),
(48, 6, 'Luxury Shamiana Ceiling Drapes & Chandelier', 'Silk pleated dining hall ceiling drapery with glowing crystal chandeliers', 'image', '/assets/images/client/image-001.jpg', NULL, NULL, '0', '0', 2, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 6),
(49, 6, 'Professional Moving Head Stage Truss', 'Concert-grade aluminum lighting truss with intelligent beam lights', 'image', '/assets/images/client/image-003.jpg', NULL, NULL, '0', '0', 3, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 6),
(50, 6, 'Garden Fairy Light Canopy Tunnel', 'Glowing walkway illuminated by warm fairy lights for guest entry', 'image', '/assets/images/client/image-014.jpg', NULL, NULL, '0', '0', 4, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 6),
(51, 6, 'LED Ambient Uplighting & Sound Setup', 'Color-coordinated wireless LED uplights highlighting stage architecture', 'image', '/assets/images/client/image-022.jpg', NULL, NULL, '0', '0', 5, 1, '2026-10-29 13:15:30', '2026-10-29 13:15:30', 6);

-- --------------------------------------------------------------
-- Table: `enquiries`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `enquiries`;
CREATE TABLE `enquiries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(100) NULL,
  `event_type` VARCHAR(80) NOT NULL,
  `preferred_date` DATE NULL,
  `event_location` VARCHAR(150) NULL,
  `guest_count` VARCHAR(50) NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('new', 'read', 'contacted', 'completed') NOT NULL DEFAULT 'new',
  `admin_notes` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_enquiries_status` (`status`),
  INDEX `idx_enquiries_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `enquiries` (`id`, `customer_name`, `phone`, `email`, `event_type`, `preferred_date`, `event_location`, `guest_count`, `message`, `status`, `admin_notes`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-13 18:42:07', '2026-10-13 18:42:07'),
(2, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-13 18:43:14', '2026-10-13 18:43:14'),
(3, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-13 18:52:55', '2026-10-13 18:52:55'),
(4, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-26 16:39:44', '2026-10-26 16:39:44'),
(5, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-26 17:09:36', '2026-10-26 17:09:36'),
(6, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-26 17:29:30', '2026-10-26 17:29:30'),
(7, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-26 17:32:03', '2026-10-26 17:32:03'),
(8, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-29 13:36:03', '2026-10-29 13:36:03'),
(9, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-29 14:12:20', '2026-10-29 14:12:20'),
(10, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-29 14:56:50', '2026-10-29 14:56:50'),
(12, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-29 15:08:52', '2026-10-29 15:08:52'),
(13, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-30 05:39:52', '2026-10-30 05:39:52'),
(14, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-30 05:40:48', '2026-10-30 05:40:48'),
(15, 'Priya Ramesh', '9876543210', 'priya.ramesh@gmail.com', 'Wedding & Reception Stage Decoration', NULL, NULL, 450, 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.', 'new', NULL, '127.0.0.1', '2026-10-30 06:08:37', '2026-10-30 06:08:37');

-- --------------------------------------------------------------
-- Table: `login_attempts`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `attempted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_attempts_ip_time` (`ip_address`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
