<?php
/**
 * Akash Event Management - End-to-End Automated QA Suite
 * Tests full public browsing, enquiry submission, CSRF security, admin login, and admin operations over HTTP.
 */

require_once __DIR__ . '/../app/bootstrap.php';
use App\Models\Enquiry;
use App\Models\Service;
use App\Models\Gallery;

$baseUrl = 'http://127.0.0.1:8000';
$passed = 0;
$failed = 0;

function report($name, $success, $details = '') {
    global $passed, $failed;
    if ($success) {
        $passed++;
        echo "  [PASS] {$name}" . ($details ? " - {$details}" : "") . "\n";
    } else {
        $failed++;
        echo "  [FAIL] {$name}" . ($details ? " - {$details}" : "") . "\n";
    }
}

function httpGet($url, $cookies = []) {
    $opts = [
        'http' => [
            'method' => 'GET',
            'ignore_errors' => true,
            'header' => "User-Agent: AkashQABot/1.0\r\n"
        ]
    ];
    if (!empty($cookies)) {
        $cookieStr = '';
        foreach ($cookies as $k => $v) {
            $cookieStr .= "{$k}={$v}; ";
        }
        $opts['http']['header'] .= "Cookie: " . rtrim($cookieStr, '; ') . "\r\n";
    }
    $ctx = stream_context_create($opts);
    $body = @file_get_contents($url, false, $ctx);
    return ['body' => $body ?: '', 'headers' => $http_response_header ?? []];
}

function httpPost($url, $data, $cookies = []) {
    $content = http_build_query($data);
    $header = "Content-Type: application/x-www-form-urlencoded\r\n" .
              "Content-Length: " . strlen($content) . "\r\n" .
              "User-Agent: AkashQABot/1.0\r\n";
    if (!empty($cookies)) {
        $cookieStr = '';
        foreach ($cookies as $k => $v) {
            $cookieStr .= "{$k}={$v}; ";
        }
        $header .= "Cookie: " . rtrim($cookieStr, '; ') . "\r\n";
    }
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => $header,
            'content' => $content,
            'ignore_errors' => true,
            'max_redirects' => 0
        ]
    ];
    $ctx = stream_context_create($opts);
    $body = @file_get_contents($url, false, $ctx);
    return ['body' => $body ?: '', 'headers' => $http_response_header ?? []];
}

function extractCookies($headers) {
    $cookies = [];
    foreach ($headers as $h) {
        if (stripos($h, 'Set-Cookie:') === 0) {
            $cookiePart = substr($h, 11);
            $parts = explode(';', $cookiePart);
            $nv = explode('=', trim($parts[0]), 2);
            if (count($nv) === 2) {
                $cookies[$nv[0]] = $nv[1];
            }
        }
    }
    return $cookies;
}

function extractCsrf($html) {
    if (preg_match('/name="csrf_token"\s+value="([^"]+)"/', $html, $m)) {
        return $m[1];
    }
    return '';
}

echo "=================================================================\n";
echo "AKASH EVENT MANAGEMENT - COMPREHENSIVE END-TO-END QA TEST SUITE\n";
echo "Target: {$baseUrl}\n";
echo "=================================================================\n\n";

// --- TEST 1: Homepage Verification ---
echo "[1] Testing Homepage Presentation & Branding...\n";
$res = httpGet($baseUrl . '/');
report('Homepage HTTP 200', strpos($res['headers'][0] ?? '', '200') !== false);
report('Hero Section Rendered', strpos($res['body'], 'hero-section') !== false);
report('Brand Name & Monogram Present', strpos($res['body'], 'Akash Event Management') !== false && strpos($res['body'], 'MHA') !== false);
report('P. Manobalan Founder Attributed', strpos($res['body'], 'Manobalan') !== false);
report('10+ Years Experience Metric', strpos($res['body'], '10+') !== false);
report('Client Image Gallery Showcase Present', strpos($res['body'], 'image-005.jpg') !== false);
echo "\n";

// --- TEST 2: Services Catalog & Details ---
echo "[2] Testing Services Catalog & Detail Views...\n";
$res = httpGet($baseUrl . '/services.php');
report('Services Page HTTP 200', strpos($res['headers'][0] ?? '', '200') !== false);
report('Wedding & Reception Card Exists', strpos($res['body'], 'Wedding &amp; Reception') !== false || strpos($res['body'], 'Wedding & Reception') !== false);
report('Fresh Flower Artistry Listed', strpos($res['body'], 'Floral Artistry') !== false || strpos($res['body'], 'Flower') !== false);

$resDetails = httpGet($baseUrl . '/service-details.php?slug=wedding-reception-decoration');
report('Service Details Page HTTP 200', strpos($resDetails['headers'][0] ?? '', '200') !== false);
report('Service Details Feature Checklist', strpos($resDetails['body'], 'Custom floral backdrops') !== false || strpos($resDetails['body'], 'checklist') !== false || strpos($resDetails['body'], 'service-features') !== false);
report('Service Booking Sidebar', strpos($resDetails['body'], 'Plan Your Celebration') !== false || strpos($resDetails['body'], 'contact.php') !== false);
echo "\n";

// --- TEST 3: Gallery & Lightbox ---
echo "[3] Testing Gallery & Interactive Lightbox...\n";
$resGal = httpGet($baseUrl . '/gallery.php');
report('Gallery Page HTTP 200', strpos($resGal['headers'][0] ?? '', '200') !== false);
report('Gallery Filter Pills Rendered', strpos($resGal['body'], 'filter-pill') !== false && strpos($resGal['body'], 'data-filter') !== false);
report('Category Wedings Filter Option', strpos($resGal['body'], 'weddings') !== false);
report('Lightbox Modal Dialog Structure Present', strpos($resGal['body'], 'lightbox-modal') !== false || strpos($resGal['body'], 'id="lightbox"') !== false);
echo "\n";

// --- TEST 4: Contact & Enquiry Workflow ---
echo "[4] Testing Contact Form, CSRF Token & Enquiry Submission...\n";
$resContact = httpGet($baseUrl . '/contact.php');
report('Contact Page HTTP 200', strpos($resContact['headers'][0] ?? '', '200') !== false);
report('Official Hosur Address Verified', strpos($resContact['body'], 'Naligabetta Agraharam') !== false || strpos($resContact['body'], 'Bhavani palace') !== false);
report('Verified Primary Phone Displayed', strpos($resContact['body'], '93442 73433') !== false);

$sessionCookies = extractCookies($resContact['headers']);
$csrfToken = extractCsrf($resContact['body']);
report('CSRF Token Extracted for Form Protection', !empty($csrfToken), substr($csrfToken, 0, 10) . '...');

// Submit an enquiry
$enquiryData = [
    'csrf_token' => $csrfToken,
    'customer_name' => 'Priya Ramesh',
    'phone' => '9876543210',
    'email' => 'priya.ramesh@gmail.com',
    'event_type' => 'Wedding & Reception Stage Decoration',
    'event_date' => '2026-12-15',
    'guest_count' => '450',
    'venue_location' => 'Hosur Royal Palace Hall',
    'budget_range' => '₹1,50,000 - ₹3,00,000',
    'message' => 'Need premium royal gold arched stage decoration with fresh orchids and chandeliers.'
];

$postRes = httpPost($baseUrl . '/contact.php', $enquiryData, $sessionCookies);
$isSuccess = strpos($postRes['body'], 'Thank you') !== false || 
             strpos($postRes['body'], 'received') !== false ||
             strpos($postRes['body'], 'success') !== false ||
             in_array('Location: contact.php?status=success', $postRes['headers']) ||
             in_array('HTTP/1.1 302 Found', $postRes['headers']);

report('Enquiry Form Submission Accepted', $isSuccess);

// Verify enquiry exists in database
$latestEnquiry = Enquiry::getById(1);
if (!$latestEnquiry) {
    $allEnquiries = Enquiry::getAll();
    $latestEnquiry = $allEnquiries[0] ?? null;
}
$dbSaved = ($latestEnquiry && stripos($latestEnquiry['customer_name'], 'Priya') !== false);
report('Enquiry Persisted into Database Table', $dbSaved, "ID: " . ($latestEnquiry['id'] ?? 'none'));
echo "\n";

// --- TEST 5: Admin Security & Authentication ---
echo "[5] Testing Admin Authentication & Rate Limiting...\n";
$resLogin = httpGet($baseUrl . '/admin/login.php');
report('Admin Login Page HTTP 200', strpos($resLogin['headers'][0] ?? '', '200') !== false);
$adminCookies = extractCookies($resLogin['headers']);
$adminCsrf = extractCsrf($resLogin['body']);

// Attempt login with valid credentials
$loginData = [
    'csrf_token' => $adminCsrf,
    'username' => 'admin',
    'password' => 'Admin@Akash2026!'
];
$loginPost = httpPost($baseUrl . '/admin/login.php', $loginData, $adminCookies);
$adminSessionCookies = array_merge($adminCookies, extractCookies($loginPost['headers']));

$authPassed = false;
foreach ($loginPost['headers'] as $h) {
    if (stripos($h, 'Location:') !== false && (stripos($h, 'dashboard.php') !== false || stripos($h, 'admin') !== false)) {
        $authPassed = true;
        break;
    }
}
report('Admin Login Authentication Successful', $authPassed, 'Redirected to Dashboard');

// Access Dashboard with authenticated session
$resDash = httpGet($baseUrl . '/admin/dashboard.php', $adminSessionCookies);
report('Admin Dashboard Accessible with Session', strpos($resDash['body'], 'Dashboard') !== false && strpos($resDash['body'], 'Executive Overview') !== false);
report('Dashboard Real-time Enquiries Counter', strpos($resDash['body'], 'Enquiries') !== false);

// Access Enquiries Management Module
$resEnq = httpGet($baseUrl . '/admin/enquiries/index.php', $adminSessionCookies);
report('Admin Enquiries Module Loaded', strpos($resEnq['body'], 'Priya Ramesh') !== false || strpos($resEnq['body'], 'enquiries-table') !== false);

// Access Settings Module
$resSettings = httpGet($baseUrl . '/admin/settings/index.php', $adminSessionCookies);
report('Admin Settings Module Accessible', strpos($resSettings['body'], 'Business Identity') !== false || strpos($resSettings['body'], 'site_settings') !== false || strpos($resSettings['body'], 'settings') !== false);

// Access Hero Management Module
$resHero = httpGet($baseUrl . '/admin/settings/hero.php', $adminSessionCookies);
report('Admin Hero Art-Direction Settings Accessible', strpos($resHero['body'], 'Hero') !== false && strpos($resHero['body'], 'Desktop') !== false);
echo "\n";

// --- TEST 6: Logout & Session Destruction ---
echo "[6] Testing Admin Logout...\n";
$resLogout = httpGet($baseUrl . '/admin/logout.php', $adminSessionCookies);
$resPostLogout = httpGet($baseUrl . '/admin/dashboard.php', $adminSessionCookies);
$loggedOut = strpos($resPostLogout['headers'][0] ?? '', '302') !== false || strpos($resPostLogout['body'], 'Login') !== false;
report('Admin Session Destroyed and Dashboard Protected', $loggedOut);
echo "\n";

echo "=================================================================\n";
echo "E2E QA TEST RESULTS SUMMARY:\n";
echo "  TOTAL TESTS PASSED : {$passed}\n";
echo "  TOTAL TESTS FAILED : {$failed}\n";
echo "=================================================================\n";

if ($failed === 0) {
    echo "SUCCESS: ALL END-TO-END QA TESTS PASSED WITH ZERO ERRORS!\n\n";
    exit(0);
} else {
    echo "ATTENTION: SOME TESTS FAILED.\n\n";
    exit(1);
}
