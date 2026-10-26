<?php
namespace App\Helpers;

use finfo;

class Uploader
{
    private const ALLOWED_IMAGE_MIMES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    private const ALLOWED_VIDEO_MIMES = [
        'video/mp4' => 'mp4',
        'video/quicktime' => 'mov',
    ];

    private const MAX_IMAGE_SIZE = 15728640; // 15MB
    private const MAX_VIDEO_SIZE = 125829120; // 120MB

    public static function uploadImage(array $file, string $subfolder = 'gallery', bool $createThumbnail = true): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Invalid file parameter.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => self::uploadErrorMessage($file['error'])];
        }

        if ($file['size'] > self::MAX_IMAGE_SIZE) {
            return ['success' => false, 'error' => 'File size exceeds maximum limit of 15MB.'];
        }

        // Validate actual MIME type using finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!array_key_exists($mime, self::ALLOWED_IMAGE_MIMES)) {
            return ['success' => false, 'error' => 'Invalid image format. Allowed: JPG, PNG, WEBP.'];
        }

        $extension = self::ALLOWED_IMAGE_MIMES[$mime];

        // Verify it is a valid decodable image and get dimensions
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return ['success' => false, 'error' => 'Corrupt or unreadable image file.'];
        }

        // Generate safe unique filename
        $safeName = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetDir = dirname(__DIR__, 2) . '/public/uploads/' . trim($subfolder, '/');
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
            // Place .htaccess to prevent script execution in upload directory
            @file_put_contents($targetDir . '/.htaccess', "RemoveHandler .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .pl\nphp_flag engine off\n");
        }

        $targetPath = $targetDir . '/' . $safeName;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'Failed to save uploaded file.'];
        }

        $publicPath = '/uploads/' . trim($subfolder, '/') . '/' . $safeName;
        $thumbPublicPath = null;

        if ($createThumbnail) {
            $thumbDir = dirname(__DIR__, 2) . '/public/uploads/thumbnails';
            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }
            $thumbName = 'thumb_' . $safeName;
            $thumbTargetPath = $thumbDir . '/' . $thumbName;
            
            if (self::generateThumbnail($targetPath, $thumbTargetPath, 600, 400, $mime)) {
                $thumbPublicPath = '/uploads/thumbnails/' . $thumbName;
            }
        }

        return [
            'success' => true,
            'file_path' => $publicPath,
            'thumbnail_path' => $thumbPublicPath,
            'filename' => $safeName,
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime' => $mime,
            'size' => $file['size']
        ];
    }

    public static function uploadVideo(array $file, string $subfolder = 'videos'): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Invalid file parameter.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => self::uploadErrorMessage($file['error'])];
        }

        if ($file['size'] > self::MAX_VIDEO_SIZE) {
            return ['success' => false, 'error' => 'Video size exceeds 120MB limit.'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!array_key_exists($mime, self::ALLOWED_VIDEO_MIMES)) {
            return ['success' => false, 'error' => 'Invalid video format. Allowed: MP4, MOV.'];
        }

        $extension = self::ALLOWED_VIDEO_MIMES[$mime];
        $safeName = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetDir = dirname(__DIR__, 2) . '/public/uploads/' . trim($subfolder, '/');
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
            @file_put_contents($targetDir . '/.htaccess', "RemoveHandler .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .pl\nphp_flag engine off\n");
        }

        $targetPath = $targetDir . '/' . $safeName;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'Failed to move uploaded video file.'];
        }

        return [
            'success' => true,
            'file_path' => '/uploads/' . trim($subfolder, '/') . '/' . $safeName,
            'filename' => $safeName,
            'mime' => $mime,
            'size' => $file['size']
        ];
    }

    public static function deleteFile(?string $publicRelativePath): bool
    {
        if (empty($publicRelativePath)) {
            return false;
        }

        // Avoid deleting default assets from /assets/
        if (str_starts_with($publicRelativePath, '/assets/')) {
            return false; // protect core system assets
        }

        $fullPath = dirname(__DIR__, 2) . '/public' . '/' . ltrim($publicRelativePath, '/');
        $realPublic = realpath(dirname(__DIR__, 2) . '/public');
        $realTarget = realpath($fullPath);

        // Security check against directory traversal
        if ($realTarget && str_starts_with($realTarget, $realPublic) && is_file($realTarget)) {
            return @unlink($realTarget);
        }

        return false;
    }

    private static function generateThumbnail(string $sourcePath, string $destPath, int $targetW, int $targetH, string $mime): bool
    {
        list($origW, $origH) = @getimagesize($sourcePath);
        if (!$origW || !$origH) return false;

        $sourceImg = null;
        switch ($mime) {
            case 'image/jpeg':
                $sourceImg = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImg = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $sourceImg = @imagecreatefromwebp($sourcePath);
                break;
        }

        if (!$sourceImg) return false;

        $scale = max($targetW / $origW, $targetH / $origH);
        $newW = (int) round($origW * $scale);
        $newH = (int) round($origH * $scale);
        $cropX = (int) round(($newW - $targetW) / 2);
        $cropY = (int) round(($newH - $targetH) / 2);

        $tempImg = imagecreatetruecolor($newW, $newH);
        imagealphablending($tempImg, false);
        imagesavealpha($tempImg, true);
        imagecopyresampled($tempImg, $sourceImg, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        $thumb = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        imagecopy($thumb, $tempImg, 0, 0, $cropX, $cropY, $targetW, $targetH);

        $saved = false;
        if ($mime === 'image/jpeg') {
            $saved = imagejpeg($thumb, $destPath, 85);
        } elseif ($mime === 'image/png') {
            $saved = imagepng($thumb, $destPath, 8);
        } elseif ($mime === 'image/webp') {
            $saved = imagewebp($thumb, $destPath, 85);
        }

        imagedestroy($sourceImg);
        imagedestroy($tempImg);
        imagedestroy($thumb);

        return $saved;
    }

    private static function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File exceeds maximum upload size.',
            UPLOAD_ERR_PARTIAL => 'File upload was aborted midway.',
            UPLOAD_ERR_NO_FILE => 'No file was selected for upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'File upload blocked by server extension.',
            default => 'Unknown upload error occurred.'
        };
    }
}
