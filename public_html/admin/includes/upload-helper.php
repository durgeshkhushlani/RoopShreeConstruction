<?php
// Shared file-upload handling for admin project CRUD.
// Images are always re-encoded via GD (strips any payload hidden in the
// original file) and normalized to JPG. PDFs are validated by real MIME
// type, not just extension/client-provided content-type.

define('MAX_IMAGE_BYTES', 8 * 1024 * 1024);
define('MAX_PDF_BYTES', 10 * 1024 * 1024);
define('MAX_IMAGE_WIDTH', 1600);

function uploads_project_dir(int $projectId): string
{
    return __DIR__ . '/../../uploads/projects/' . $projectId;
}

/**
 * Re-encodes an uploaded image via GD, saves it as JPG, returns the
 * web-relative path, or null (with $error set) on failure.
 */
function process_uploaded_image(array $file, int $projectId, ?string &$error): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed.';
        return null;
    }
    if ($file['size'] > MAX_IMAGE_BYTES) {
        $error = 'Image is too large (max 8MB).';
        return null;
    }

    $mime = mime_content_type($file['tmp_name']);
    $allowed = ['image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/webp' => 'imagecreatefromwebp'];
    if (!isset($allowed[$mime])) {
        $error = 'Only JPG, PNG, or WEBP images are allowed.';
        return null;
    }

    $src = @$allowed[$mime]($file['tmp_name']);
    if (!$src) {
        $error = 'Could not read the uploaded image.';
        return null;
    }

    $width = imagesx($src);
    $height = imagesy($src);

    if ($width > MAX_IMAGE_WIDTH) {
        $newWidth = MAX_IMAGE_WIDTH;
        $newHeight = (int) round($height * ($newWidth / $width));
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagefill($resized, 0, 0, imagecolorallocate($resized, 255, 255, 255));
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);
        $src = $resized;
    } elseif ($mime === 'image/png') {
        // Flatten transparency onto white before JPG conversion.
        $flat = imagecreatetruecolor($width, $height);
        imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
        imagecopy($flat, $src, 0, 0, 0, 0, $width, $height);
        imagedestroy($src);
        $src = $flat;
    }

    $dir = uploads_project_dir($projectId) . '/images';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filename = bin2hex(random_bytes(8)) . '.jpg';
    $fullPath = $dir . '/' . $filename;
    imagejpeg($src, $fullPath, 80);
    imagedestroy($src);

    return '/uploads/projects/' . $projectId . '/images/' . $filename;
}

/**
 * Validates and stores an uploaded PDF brochure, returns the web-relative
 * path, or null (with $error set) on failure.
 */
function process_uploaded_pdf(array $file, int $projectId, ?string &$error): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed.';
        return null;
    }
    if ($file['size'] > MAX_PDF_BYTES) {
        $error = 'Brochure PDF is too large (max 10MB).';
        return null;
    }

    $mime = mime_content_type($file['tmp_name']);
    if ($mime !== 'application/pdf') {
        $error = 'Brochure must be a PDF file.';
        return null;
    }

    $dir = uploads_project_dir($projectId);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filename = 'brochure-' . bin2hex(random_bytes(8)) . '.pdf';
    $fullPath = $dir . '/' . $filename;
    move_uploaded_file($file['tmp_name'], $fullPath);

    return '/uploads/projects/' . $projectId . '/' . $filename;
}

function delete_project_files(int $projectId): void
{
    $dir = uploads_project_dir($projectId);
    if (!is_dir($dir)) {
        return;
    }
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($items as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }
    rmdir($dir);
}

function delete_single_file(?string $webPath): void
{
    if (!$webPath) {
        return;
    }
    $fullPath = __DIR__ . '/../../' . ltrim($webPath, '/');
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
}
