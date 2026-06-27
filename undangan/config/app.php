<?php

/**
 * Override otomatis path undangan di hosting (bukan localhost).
 * Salin ke app.php hanya jika perlu override manual.
 */
$host = strtolower($_SERVER['HTTP_HOST'] ?? '');
$isLocal = $host === 'localhost'
    || str_starts_with($host, '127.0.0.1')
    || str_contains($host, 'localhost:');

if (!$isLocal) {
    $script = rawurldecode(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''));
    if (preg_match('#(/undangan)(?:/|$)#', $script, $m)) {
        return ['base_url' => $m[1]];
    }

    $uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '');
    if (preg_match('#(/undangan)(?:/|$)#', $uri, $m)) {
        return ['base_url' => $m[1]];
    }
}

return [];
