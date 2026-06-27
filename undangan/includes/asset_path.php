<?php

/**
 * Path undangan — independen dari config DB, aman di hosting.
 */
function undangan_base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }

    $overrideFile = dirname(__DIR__) . '/config/app.php';
    if (is_file($overrideFile)) {
        $cfg = require $overrideFile;
        if (!empty($cfg['base_url'])) {
            $base = rtrim(str_replace('\\', '/', (string) $cfg['base_url']), '/');
            return $base;
        }
    }

    $script = rawurldecode(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''));
    if (preg_match('#(/undangan)(?:/|$)#', $script, $m)) {
        $base = $m[1];
        return $base;
    }

    $uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '');
    if (preg_match('#(/undangan)(?:/|$)#', $uri, $m)) {
        $base = $m[1];
        return $base;
    }

    if (preg_match('#(/landing page/undangan)(?:/|$)#', $script, $m)) {
        $base = $m[1];
        return $base;
    }

    $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $moduleRoot = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');

    if ($docRoot !== '' && str_starts_with($moduleRoot, $docRoot)) {
        $relative = substr($moduleRoot, strlen($docRoot));
        $relative = $relative === false ? '' : str_replace('\\', '/', $relative);
        $base = rtrim($relative, '/');
        return $base;
    }

    $dir = dirname($script);
    if (in_array(basename($dir), ['admin', 'api'], true)) {
        $dir = dirname($dir);
    }

    $base = ($dir === '/' || $dir === '.' || $dir === '\\') ? '' : rtrim($dir, '/');
    return $base;
}

function undangan_asset_url(string $path): string
{
    $path = ltrim(str_replace('\\', '/', $path), '/');
    $script = rawurldecode(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = trim(dirname($script), '/');

    if ($dir === '' || $dir === '.') {
        return $path;
    }

    $parts = explode('/', $dir);
    $uIdx = array_search('undangan', $parts, true);

    if ($uIdx !== false) {
        $depth = count($parts) - $uIdx - 1;

        return ($depth > 0 ? str_repeat('../', $depth) : '') . $path;
    }

    if (isset($parts[0]) && in_array($parts[0], ['admin', 'api'], true)) {
        return str_repeat('../', count($parts)) . $path;
    }

    return undangan_url($path);
}

function undangan_url(string $path = ''): string
{
    $path = ltrim(str_replace('\\', '/', $path), '/');
    $base = rtrim(undangan_base_path(), '/');

    if ($base === '') {
        $url = $path === '' ? '/' : '/' . $path;
    } else {
        $url = $path === '' ? $base : $base . '/' . $path;
    }

    return str_replace(' ', '%20', $url);
}

if (!function_exists('app_url')) {
    function app_url(string $path = ''): string
    {
        return undangan_url($path);
    }
}

if (!defined('APP_BASE')) {
    define('APP_BASE', undangan_base_path());
}
