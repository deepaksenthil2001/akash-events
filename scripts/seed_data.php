<?php
/**
 * Akash Event Management - Database Seeder
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

$db = Database::getInstance();
echo "Executing seed routine for Akash Event Management...\n";

// 1. Seed Site Settings
$settings = [
    ['business_name', 'Akash Event Management', 'general', 'Official legal business name'],
    ['brand_initials', 'MHA', 'general', 'Brand monogram / emblem initials'],
    ['founder_name', 'P. Manobalan', 'general', 'Founder & Lead Decorator'],
    ['founder_title', 'Master Floral Decorator & Event Architect', 'general', 'Founder official designation'],
    ['experience_statement', '10+ Years of Craftsmanship & Service', 'general', 'Verified experience milestone'],
    ['phone_primary', '+91 93442 73433', 'contact', 'Primary verified phone number'],
    ['phone_secondary', '+91 96599 99856', 'contact', 'Secondary verified phone number'],
    ['email_primary', 'akasheventmanagement87@gmail.com', 'contact', 'Official enquiries email address'],
    ['email_secondary', 'akasheventmanagement@gmail.com', 'contact', 'Alternative business email address'],
    ['address_line', 'Bhavani Palace, Back side, Gokul Nagar, Hosur, Tamil Nadu 635109', 'contact', 'Physical office / venue location'],
    ['service_areas', 'Hosur, Krishnagiri, Bangalore & Surrounding Regions', 'contact', 'Primary coverage regions'],
    ['instagram_url', 'https://www.instagram.com/akasheventmanagement', 'social', 'Official Instagram profile'],
    ['google_business_url', 'https://share.google/nUP5Exqa3K1doUEfd', 'social', 'Official Google Business Profile link'],
    ['whatsapp_number', '919344273433', 'social', 'WhatsApp enquiry number (with country code)'],
    ['hero_eyebrow', 'Hosur & Bangalore | 10+ Years of Celebrations', 'hero', 'Eyebrow label above hero title'],
    ['hero_headline', 'Creating Moments Worth Remembering.', 'hero', 'Primary homepage headline'],
    ['hero_subheadline', 'Thoughtfully designed event experiences, bespoke stage architecture, and breathtaking floral artistry tailored for weddings, receptions, and life milestones.', 'hero', 'Hero supporting paragraph'],
    ['hero_desktop_image', '/assets/images/hero/hero-desktop.jpg', 'hero', 'Primary desktop hero photograph'],
    ['hero_mobile_image', '/assets/images/hero/hero-mobile.jpg', 'hero', 'Art-directed portrait mobile hero image'],
    ['hero_cta_primary_text', 'Explore Our Work', 'hero', 'Primary hero button label'],
    ['hero_cta_primary_link', '/gallery.php', 'hero', 'Primary hero button URL'],
    ['hero_cta_secondary_text', 'Plan Your Celebration', 'hero', 'Secondary hero button label'],
    ['hero_cta_secondary_link', '/contact.php', 'hero', 'Secondary hero button URL'],
    ['about_intro_lead', 'Every celebration carries a heartbeat — a singular moment in time when family, friends, and lifelong memories unite.', 'about', 'About page lead statement'],
    ['about_philosophy', 'Founded by P. Manobalan, Akash Event Management has spent over a decade perfecting the delicate balance between timeless Indian ceremonial heritage and modern visual refinement. From intimate naming ceremonies to grand wedding mandapams, we treat every celebration as a bespoke work of art.', 'about', 'Core company philosophy'],
    ['meta_title_default', 'Akash Event Management | Premium Stage Decoration & Event Planning in Hosur & Bangalore', 'seo', 'Default SEO Page Title'],
    ['meta_description_default', 'Akash Event Management specializes in luxury wedding stages, floral decoration, reception mandapams, baby showers, and birthday setups across Hosur and Bangalore. 10+ years of excellence.', 'seo', 'Default SEO Meta Description']
];

foreach ($settings as $s) {
    try {
        $db->execute("INSERT INTO site_settings (setting_key, setting_value, setting_group, description) VALUES (?, ?, ?, ?)", $s);
    } catch (\Exception $e) {
        // already exists
    }
}
echo "✓ Settings seeded.\n";

// 2. Categories
$categories = [
    [1, 'weddings', 'Weddings & Receptions', 'Grand floral stages, luxury reception backdrops, and timeless mandapams', 1, 1],
    [2, 'engagements', 'Engagements', 'Romantic floral arches, crystal chandeliers, and refined couple seating', 2, 1],
    [3, 'traditional', 'Traditional Ceremonies', 'Seemantham, Half Saree, Puberty, and Kolam-inspired ritual backdrops', 3, 1],
    [4, 'birthdays', 'Birthdays & Milestones', 'Bespoke cartoon & fairy themes, balloon arches, and custom plinths', 4, 1],
    [5, 'naming-ceremonies', 'Naming Ceremonies', 'Lord Murugan, Lord Krishna, and floral cradling celebrations', 5, 1],
    [6, 'lighting-sound', 'Lighting & Ambient Production', 'Architectural building illumination, fairy light tunnels, DJ truss & sound', 6, 1],
];

foreach ($categories as $c) {
    try {
        $db->execute("INSERT INTO gallery_categories (id, slug, name, description, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)", $c);
    } catch (\Exception $e) {
        // already exists
    }
}
echo "✓ Gallery categories seeded.\n";

// 3. Services
$services = [
    [
        'wedding-reception-decoration', 'Wedding & Reception Stage Decoration',
        'Opulent floral backdrops, architectural curved arches, and royal throne seating tailored for unforgettable wedding celebrations.',
        'We create immersive wedding and reception environments that evoke grandeur and warmth. Every wedding design is custom-engineered to complement the venue dimensions, color themes, and family preferences. From cascading fresh flower arches to crystal chandeliers and delicate fairy light drapes, our team delivers seamless craftsmanship from concept to conclusion.',
        '/assets/images/services/wedding.jpg', '/assets/images/client/image-005.jpg',
        "Custom floral backdrops with exotic and fresh blossoms\nRoyal couple sofa and velvet diwan configurations\nCurated ambient spotlights and warm chandelier arrays\nMandapam coordination and entrance floral arches\nComplete on-site setup and teardown management",
        1, 1, 1, 'Wedding & Reception Decoration in Hosur | Akash Event Management', 'Transform your wedding with premium floral stage decoration, chandeliers, and royal seating in Hosur and Bangalore.'
    ],
    [
        'engagement-stage-decoration', 'Engagement Celebrations',
        'Contemporary romantic stages combining champagne gold frameworks, warm candlelight accents, and hand-selected floral blooms.',
        'An engagement marks the beginning of a lifelong journey. We design intimate yet striking stages featuring delicate geometric arches, soft pastel drapery, fluted pedestals, and bespoke couple monogram backdrops that photograph flawlessly from every angle.',
        '/assets/images/services/engagement.jpg', '/assets/images/client/image-013.jpg',
        "Bespoke monogram backdrop rings\nChampagne gold and white aesthetic palettes\nDelicate rose, peony, and orchid arrangements\nAmbient warm uplighting and candelabras\nCompact and expansive hall configurations",
        2, 1, 1, 'Engagement Stage Decoration Hosur | Akash Event Management', 'Bespoke engagement stage decorations with champagne gold accents, floral rings, and warm lighting.'
    ],
    [
        'traditional-ceremonies', 'Traditional Functions & Seemantham',
        'Authentic cultural setups for Seemantham (Baby Shower), Half Saree, Puberty functions, and auspicious family rituals.',
        'Honoring sacred customs requires reverence and artistry. Our traditional setups blend fragrant jasmine, marigold, and lotus motifs with authentic Kolam wall art, brass deepam oil lamps, banana trunk headers, and traditional Seer plate display pedestals.',
        '/assets/images/services/baby-shower.jpg', '/assets/images/client/image-023.jpg',
        "Traditional Kolam and Jali architectural panels\nFresh lotus, chrysanthemum, and jasmine hangings\nBrass deepam lamps and decorative brass urns\nSeer thattu and thamboolam gift display setups\nCustom green turf or traditional silk carpet flooring",
        3, 1, 1, 'Traditional Seemantham & Half Saree Decoration Hosur', 'Authentic South Indian traditional event decoration for Seemantham, Half Saree, and family celebrations.'
    ],
    [
        'naming-ceremonies', 'Naming Ceremony Celebrations',
        'Charming sacred stages celebrating new beginnings, featuring divine Murugan and Krishna themes with floral cradles.',
        'Welcoming a child is a sacred blessing. We craft heartwarming naming ceremony settings featuring divine temple backdrops, custom photo displays, suspended jasmine strings, and beautifully decorated cradles that create a serene atmosphere for elders and guests.',
        '/assets/images/services/naming-ceremony.jpg', '/assets/images/client/image-024.jpg',
        "Lord Murugan and Lord Krishna thematic backdrops\nFresh floral decorated cradles and swing setups\nWarm glowing candle stands and hanging floral drops\nCustom baby photo prints and personalized signage\nFamily-friendly seating and stage platforms",
        4, 1, 1, 'Naming Ceremony Decoration in Hosur | Akash Event Management', 'Traditional and modern naming ceremony decorations with divine themes and floral cradle setups.'
    ],
    [
        'birthday-celebrations', 'Birthday & Milestone Celebrations',
        'Playful, vibrant, and artistic balloon sculptures, fairy-tale arches, LED neon signs, and themed cake plinths.',
        'From 1st birthday milestone celebrations to grand jubilee anniversaries, our team designs captivating environments with organic balloon installations, custom 3D cutouts, illuminated age numbers, wooden picket fences, and dessert table styling.',
        '/assets/images/services/birthday.jpg', '/assets/images/client/image-018.jpg',
        "Organic pastel and chrome balloon arches\nIlluminated LED neon signs and marquee numbers\nFluted dessert plinths and thematic cake backdrops\nCustom cartoon, butterfly, jungle, or floral themes\nInteractive photo booths and entrance arches",
        5, 1, 1, 'Birthday Decoration Services Hosur | Akash Event Management', 'Creative birthday event decoration with organic balloon arches, neon signage, and custom theme backdrops.'
    ],
    [
        'flower-decoration', 'Bespoke Floral Artistry',
        'Master floral installations by P. Manobalan, sourcing premium fresh blooms for stages, entrance gates, and ceremonial cars.',
        'With over a decade of dedicated floral craftsmanship, P. Manobalan leads our floral division in creating show-stopping installations. We hand-select farm-fresh blooms, exotic Dutch roses, baby breath, orchids, and traditional South Indian flowers to create lush living art.',
        '/assets/images/services/flower-decoration.jpg', '/assets/images/client/image-006.jpg',
        "Dense natural floral walls and cascading ceilings\nEntrance archway floral wraps and walkway pillars\nBride & groom ceremonial garlands (Maalai)\nWedding car floral styling and VIP vehicle decor\nCenterpieces and dining table floral arrangements",
        6, 1, 1, 'Fresh Flower Decoration Hosur | P. Manobalan Akash Events', 'Specialist fresh flower decorations for weddings, receptions, and temples by master decorator P. Manobalan.'
    ],
    [
        'light-decoration-production', 'Architectural Light & Production',
        'Spectacular building facade illumination, glowing fairy-light walkways, professional stage lighting, and DJ sound systems.',
        'Lighting transforms ordinary architecture into magical wonderlands. We provide full building exterior fairy-light draping, outdoor tree canopies, moving head beams for dance floors, stage trusses, and crystal-clear sound reinforcement for music and ceremonies.',
        '/assets/images/services/light-decoration.jpg', '/assets/images/client/image-026.jpg',
        "Multi-story residential and commercial building facade lights\nFairy-light walkway tunnels and garden canopy lighting\nProfessional stage lighting truss with moving heads & beam effects\nHigh-fidelity sound reinforcement and DJ console setups\nEmergency backup power and certified safe electrical cabling",
        7, 1, 0, 'Light Decoration & DJ Sound Services Hosur | Akash Events', 'Full building fairy lighting, stage lighting trusses, and DJ sound production in Hosur and surrounding regions.'
    ],
    [
        'catering-tent-house', 'Catering & Tent House Infrastructure',
        'Complete event infrastructure including premium buffet dining setups, luxury shamianas, banquet seating, and guest logistics.',
        'Beyond stage decoration, we provide the backbone of high-capacity events. Our tent house inventory includes weatherproof German pagodas, decorative ceiling drapes, banquet tables, VIP chairs, and coordinated buffet presentation lines staffed by hospitality professionals.',
        '/assets/images/services/mandapam.jpg', '/assets/images/client/image-001.jpg',
        "Weatherproof German pagodas and shamiana canopies\nSilk ceiling drapery and chandeliers for dining halls\nBanquet chairs with satin bow ties and round tables\nBuffet line heating chaffing dishes and presentation decor\nCatering coordination and dining area sanitation",
        8, 1, 0, 'Tent House & Catering Setup Hosur | Akash Event Management', 'Complete tent house rentals, shamiana structures, banquet seating, and buffet catering presentation in Hosur.'
    ]
];

foreach ($services as $srv) {
    try {
        $db->execute("INSERT INTO services (slug, title, short_description, full_description, cover_image, banner_image, features_list, display_order, is_published, is_featured, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $srv);
    } catch (\Exception $e) {
        // already exists
    }
}
echo "✓ Services seeded.\n";

// 4. Media
$media = [
    [1, 'Champagne Gold & Ivory Arch Stage', 'Triple curved gold geometric arches with garden roses, chandeliers, and fluted love seat', 'image', '/assets/images/client/image-013.jpg', null, null, 1, 0, 1, 1],
    [1, 'Grand White & Peach Reception Stage', 'Circular floral arch with crystal chandeliers and carved teak royal throne', 'image', '/assets/images/client/image-005.jpg', null, null, 1, 0, 2, 1],
    [1, 'Emerald Glow Illuminated Stage', 'Modern shell-wave illuminated neon arches with rich green velvet backdrop and floral carpet', 'image', '/assets/images/client/image-003.jpg', null, null, 1, 0, 3, 1],
    [1, 'Traditional Mandapam Floral Setup', 'Draped pillars with jasmine tassels, Subha Mangalam kolam motif, and brass deepam lamps', 'image', '/assets/images/client/image-008.jpg', null, null, 1, 0, 4, 1],
    [2, 'Romantic Heart Floral Arch', 'Heart-shaped fresh floral arch with fairy lights and white amphora vases', 'image', '/assets/images/client/image-016.jpg', null, null, 1, 0, 5, 1],
    [2, 'Circular Floral Ring Reception', 'Full circle floral arch with hanging golden bells, carved gold sofa, and gerbera floor arrangements', 'image', '/assets/images/client/image-002.jpg', null, null, 0, 0, 6, 1],
    [3, 'Royal Kolam & Brass Deepam Stage', 'Traditional South Indian maroon & wood jali backdrop with seer plates and brass lamps', 'image', '/assets/images/client/image-015.jpg', null, null, 1, 0, 7, 1],
    [3, 'Traditional Seemantham Lotus Stage', 'Lotus centerpiece backdrop with parrots, silk pleated stage skirting, and fresh floral borders', 'image', '/assets/images/client/image-023.jpg', null, null, 1, 0, 8, 1],
    [4, 'Bespoke 1st Birthday Pastel Meadow', 'Rishi birthday setup with organic balloon garland, fluted plinths, and wooden garden fence', 'image', '/assets/images/client/image-018.jpg', null, null, 1, 0, 9, 1],
    [4, 'Golden Butterfly Fairytale Setup', 'Vertical circular backdrop with balloon garlands, illuminated LED number 1, and butterfly accents', 'image', '/assets/images/client/image-019.jpg', null, null, 0, 0, 10, 1],
    [5, 'Divine Bala Murugan Naming Ceremony', 'Temple-inspired Lord Murugan backdrop with candle posts and fresh floral header garland', 'image', '/assets/images/client/image-024.jpg', null, null, 1, 0, 11, 1],
    [1, 'Blossom Floral Wall & Gold Urns', 'Dense white chrysanthemum flower wall with gold monogram and antique urns', 'image', '/assets/images/client/image-006.jpg', null, null, 0, 0, 12, 1],
    [1, 'Heart Arch & Jali Lattice Stage', 'Geometric heart floral arch with gold lattice jali screens and white drape accents', 'image', '/assets/images/client/image-007.jpg', null, null, 0, 0, 13, 1],
    [1, 'Classic White & Jasmine Hall Stage', 'White curtain backdrop with fairy lights, jasmine tassels, and vintage diwan seating', 'image', '/assets/images/client/image-004.jpg', null, null, 0, 0, 14, 1],
    [6, 'Architectural Facade Fairy Lights & Stage Production', 'Illuminated building facade and professional stage light trusses with honeycomb LED displays', 'image', '/assets/images/client/image-026.jpg', null, null, 1, 0, 15, 1],
    [1, 'Editorial Concept: Luxury Royal Wedding Pavilion', 'Architectural warm ivory draping, delicate champagne gold curved arches, lavish floral cascades', 'image', '/assets/images/generated/hero_wedding_desktop.jpg', '/assets/images/generated/hero_wedding_mobile.jpg', null, 1, 1, 16, 1],
];

foreach ($media as $m) {
    try {
        $db->execute("INSERT INTO gallery_media (category_id, title, caption, media_type, file_path, mobile_file_path, thumbnail_path, is_featured, is_concept, display_order, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $m);
    } catch (\Exception $e) {
        // already exists
    }
}
echo "✓ Gallery media seeded.\n";
echo "Seed routine finished successfully!\n";
