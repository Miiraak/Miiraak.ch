<?php
/**
 * php/download.php - Secure file download with tracking
 * Usage: php/download.php?id=product-id&file=filename.ext
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/download_errors.log');

session_start();

// ============================================
// CONFIGURATION
// ============================================

$allowedFiles = [
    'AccountTester_v0.8.2.exe' => 'AccountTester',
];

$allowedExtensions = ['exe', 'ps1', 'xml', 'bat', 'cmd', 'zip', '7z', 'msi', 'txt', 'pdf'];
$maxDownloadsPerMinute = 10;
$statsFile = __DIR__ . '/../data/stats.json';  
$filesDir = __DIR__ . '/../files/';         

// ============================================
// RATE LIMITING
// ============================================

function checkRateLimit($maxRequests) {
    $now = time();
    
    if (! isset($_SESSION['download_timestamps'])) {
        $_SESSION['download_timestamps'] = [];
    }
    
    $_SESSION['download_timestamps'] = array_filter(
        $_SESSION['download_timestamps'],
        function($timestamp) use ($now) {
            return ($now - $timestamp) < 60;
        }
    );
    
    if (count($_SESSION['download_timestamps']) >= $maxRequests) {
        return false;
    }
    
    $_SESSION['download_timestamps'][] = $now;
    return true;
}

// ============================================
// INPUT VALIDATION
// ============================================

$productId = isset($_GET['id']) ? $_GET['id'] : null;
$file = isset($_GET['file']) ?  $_GET['file'] : null;

if (!$productId || ! $file) {
    http_response_code(400);
    error_log("Download attempt with missing parameters");
    die('Bad Request: Missing parameters');
}

$productId = preg_replace('/[^a-z0-9\-_]/i', '', $productId);
if (empty($productId)) {
    http_response_code(400);
    error_log("Download attempt with invalid product ID");
    die('Bad Request: Invalid product ID');
}

$file = basename($file);
$file = preg_replace('/[^a-z0-9\-_\. ]/i', '', $file);

if (empty($file)) {
    http_response_code(400);
    error_log("Download attempt with invalid filename");
    die('Bad Request: Invalid filename');
}

// ============================================
// WHITELIST CHECK
// ============================================

if (! array_key_exists($file, $allowedFiles)) {
    http_response_code(403);
    error_log("Download attempt for non-whitelisted file: $file");
    die('Forbidden: File not available for download');
}

if ($allowedFiles[$file] !== $productId) {
    http_response_code(403);
    error_log("Product ID mismatch: $productId for file $file");
    die('Forbidden: Invalid product ID for this file');
}

// ============================================
// FILE EXTENSION CHECK
// ============================================

$fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
if (!in_array($fileExtension, $allowedExtensions)) {
    http_response_code(403);
    error_log("Download attempt for file with forbidden extension: $fileExtension");
    die('Forbidden: File type not allowed');
}

// ============================================
// RATE LIMITING CHECK
// ============================================

if (!checkRateLimit($maxDownloadsPerMinute)) {
    http_response_code(429);
    error_log("Rate limit exceeded for IP: " . $_SERVER['REMOTE_ADDR']);
    die('Too Many Requests: Rate limit exceeded.  Please wait and try again.');
}

// ============================================
// FILE EXISTENCE CHECK
// ============================================

$filePath = $filesDir . $file;
$realFilePath = realpath($filePath);
$realFilesDir = realpath($filesDir);

if ($realFilePath === false || strpos($realFilePath, $realFilesDir) !== 0) {
    http_response_code(403);
    error_log("Path traversal attempt detected: $file");
    die('Forbidden: Invalid file path');
}

if (!file_exists($realFilePath)) {
    http_response_code(404);
    error_log("Download attempt for non-existent file: $file");
    die('Not Found: File does not exist');
}

if (!is_readable($realFilePath)) {
    http_response_code(500);
    error_log("File not readable: $file");
    die('Internal Server Error: Unable to read file');
}

// ============================================
// STATS UPDATE (with locking)
// ============================================

if (file_exists($statsFile) && is_writable($statsFile)) {
    $fp = fopen($statsFile, 'r+');
    
    if ($fp && flock($fp, LOCK_EX)) {
        $statsContent = fread($fp, filesize($statsFile));
        $stats = json_decode($statsContent, true);
        
        if ($stats !== null && isset($stats[$productId])) {
            $stats[$productId]['downloads'] = (int)$stats[$productId]['downloads'] + 1;
            $stats[$productId]['last_updated'] = date('Y-m-d');
            
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            error_log("Download tracked: $productId (Total: " . $stats[$productId]['downloads'] .  ")");
        }
        
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

// ============================================
// SERVE FILE
// ============================================

$fileSize = filesize($realFilePath);
$fileName = basename($realFilePath);

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $realFilePath);
finfo_close($finfo);

if (! $mimeType) {
    $mimeType = 'application/octet-stream';
}

while (ob_get_level()) {
    ob_end_clean();
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Security-Policy: default-src \'none\'');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' .  addslashes($fileName) . '"');
header('Content-Length: ' . $fileSize);
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Description: File Transfer');

error_log("File served successfully: $file to IP: " . $_SERVER['REMOTE_ADDR']);

$chunkSize = 8192;
$handle = fopen($realFilePath, 'rb');

if ($handle) {
    while (!feof($handle)) {
        echo fread($handle, $chunkSize);
        flush();
    }
    fclose($handle);
}

exit;
?>