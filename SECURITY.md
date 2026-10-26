# Security Architecture & Safeguards — Akash Event Management

This document details the multi-layered security measures implemented across the **Akash Event Management** application.

---

## 1. Authentication & Password Security

- **Hashing Algorithm**: Passwords are cryptographically hashed using PHP's native `password_hash()` utilizing the **Bcrypt** algorithm (`PASSWORD_DEFAULT`), providing adaptive work factors resistant to GPU-accelerated cracking.
- **Constant-Time Verification**: All authentication verifications use `password_verify()` to prevent side-channel timing attacks.
- **Session Fixation Defense**: Following every successful login or privilege change, `Session::regenerate()` is executed, invoking `session_regenerate_id(true)` to discard old identifiers and prevent session fixation hijacking.
- **Session Guarding**: All administrative endpoints verify `Auth::check()`. Unauthenticated requests are immediately terminated and redirected to the login view with HTTP 302.

---

## 2. Brute-Force & Rate Limiting (`login_attempts`)

- **Table Tracking**: The database maintains a persistent `login_attempts` audit table tracking IP addresses, target usernames, and timestamps.
- **Thresholds**:
  - Maximum failed attempts: **5 attempts** within a 15-minute sliding window.
  - Temporary lockout duration: **15 minutes** (900 seconds).
- **Graceful Reset**: Upon any valid administrative authentication, previous failure counters for that specific IP and username pair are purged.

---

## 3. Cross-Site Request Forgery (CSRF) Protection

- **Cryptographic Token Generation**: Powered by `random_bytes(32)` converted into a 64-character hexadecimal token.
- **Timing-Attack Resilient Validation**: Tokens are verified using `hash_equals()`, ensuring constant-time evaluation.
- **Universal Form Enforcement**: All state-altering POST requests across both the public enquiry form and the administrative portal require valid CSRF tokens. Invalid or expired tokens trigger an HTTP 403 Forbidden response.

---

## 4. SQL Injection Prevention (PDO Prepared Statements)

- **Prepared Statements**: Zero user-supplied parameters are concatenated into raw SQL strings. All SQL operations across `Database.php` and Model classes strictly use PDO prepared statements with bound parameters (`:param` or `?`).
- **Emulated Prepares Disabled**: `PDO::ATTR_EMULATE_PREPARES => false` is enforced in the PDO configuration, ensuring database-level parameter separation.
- **Strict Typing**: Numeric identifiers (`id`, `display_order`, `limit`) are cast to strict PHP integers prior to binding.

---

## 5. Cross-Site Scripting (XSS) Mitigation

- **Universal Escaping Helper**: All dynamic output rendered in HTML templates passes through `View::e()` which applies `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')`.
- **Double-Encoding Protection**: Single and double quotes are escaped (`ENT_QUOTES`), preventing attribute break-outs.
- **Input Sanitization**: Public contact form fields pass through `Sanitizer::cleanString()`, stripping illegal control characters and null bytes (`\0`).

---

## 6. Secure File Upload Architecture (`Uploader.php`)

To prevent Remote Code Execution (RCE), arbitrary file upload attacks, and web-root traversal:

1. **MIME-Type Inspection via `finfo`**:
   The uploaded file's binary stream is inspected using PHP's `finfo_open(FILEINFO_MIME_TYPE)`. Client-supplied `$_FILES['type']` headers are completely disregarded.
2. **Strict Extension Whitelist**:
   Only explicit safe formats are permitted:
   - Images: `.jpg`, `.jpeg`, `.png`, `.webp`
   - Videos: `.mp4`, `.mov`
   Executable extensions (`.php`, `.phtml`, `.phar`, `.pl`, `.cgi`, `.exe`, `.sh`) are unconditionally rejected.
3. **Randomized File Renaming**:
   Original client filenames are discarded. All saved files are renamed using a cryptographically random prefix + timestamp (`uniqid('media_', true)`), preventing name collision and directory traversal exploits.
4. **GD Image Verification & Normalization**:
   Image files are parsed with GD (`imagecreatefromjpeg`, `imagecreatefrompng`, `imagecreatefromwebp`). This strips potential malicious EXIF payloads and verifies that the file is indeed a valid image matrix.
5. **Path Traversal Shield**:
   Upload destinations are resolved via absolute paths (`APP_ROOT`), and filenames are validated with `basename()` to prevent `../` directory traversal attacks.

---

## 7. Web Server Hardening (`.htaccess`)

- **Directory Browsing Disabled**: `Options -Indexes` prevents visitors from listing directory contents when an index file is absent.
- **Sensitive Directory Shielding**: Apache rewrite rules deny direct HTTP access to core folders:
  - `app/`
  - `storage/`
  - `database/`
  - `scripts/`
  - `tests/`
  - Hidden files (`.env`, `.git`)
- **Default Encoding**: `AddDefaultCharset UTF-8` prevents character-set confusion attacks.
- **Cookie Security**: Session cookies are configured with `httponly = true` (preventing JavaScript access) and `SameSite = Lax` (preventing cross-site request inclusion).
