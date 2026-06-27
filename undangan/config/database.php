<?php

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'u700125577_santri');
define('DB_USER', 'u700125577_santri');
define('DB_PASS', 'Landingpage@1990');
define('DB_CHARSET', 'utf8mb4');

/**
 * Deteksi path web ke folder undangan.
 * Prioritas: config/app.php → DOCUMENT_ROOT + folder modul → SCRIPT_NAME.
 */
function detectUndanganBase(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }

    $overrideFile = __DIR__ . '/app.php';
    if (is_file($overrideFile)) {
        $cfg = require $overrideFile;
        if (!empty($cfg['base_url'])) {
            $base = rtrim(str_replace('\\', '/', (string) $cfg['base_url']), '/');
            return $base;
        }
    }

    $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $moduleRoot = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');

    if ($docRoot !== '' && str_starts_with($moduleRoot, $docRoot)) {
        $relative = substr($moduleRoot, strlen($docRoot));
        $relative = $relative === false ? '' : str_replace('\\', '/', $relative);
        $base = rtrim($relative, '/');
        return $base;
    }

    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $dir = dirname($script);
    $leaf = basename($dir);

    if ($leaf === 'admin' || $leaf === 'api') {
        $dir = dirname($dir);
    }

    $base = ($dir === '/' || $dir === '.' || $dir === '\\') ? '' : rtrim($dir, '/');
    return $base;
}

if (!defined('APP_BASE')) {
    define('APP_BASE', detectUndanganBase());
}

function app_url(string $path = ''): string
{
    $path = ltrim(str_replace('\\', '/', $path), '/');
    $base = rtrim(APP_BASE, '/');

    if ($base === '') {
        $url = $path === '' ? '/' : '/' . $path;
    } else {
        $url = $path === '' ? $base : $base . '/' . $path;
    }

    return str_replace(' ', '%20', $url);
}

define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_DIR', BASE_PATH . '/assets/uploads/logos/');
define('UPLOAD_URL', 'assets/uploads/logos/');
define('ORNAMENT_UPLOAD_DIR', BASE_PATH . '/assets/uploads/ornaments/');
define('ORNAMENT_UPLOAD_URL', 'assets/uploads/ornaments/');
define('FONT_UPLOAD_DIR', BASE_PATH . '/assets/uploads/fonts/');
define('FONT_UPLOAD_URL', 'assets/uploads/fonts/');
define('MAX_LOGO_SIZE', 1048576);
define('MAX_ORNAMENT_SIZE', 2097152);
define('MAX_FONT_SIZE', 3145728);
define('ALLOWED_LOGO_TYPES', ['image/png', 'image/jpeg', 'image/jpg']);
define('ALLOWED_LOGO_EXT', ['png', 'jpg', 'jpeg']);
define('ALLOWED_FONT_EXT', ['woff2', 'woff', 'ttf']);
define('ALLOWED_FONT_TYPES', [
    'font/woff2', 'font/woff', 'application/font-woff2', 'application/font-woff',
    'application/x-font-woff', 'application/x-font-ttf', 'font/ttf', 'application/octet-stream',
]);

function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}
