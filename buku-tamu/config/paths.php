<?php

/**
 * URL publik aplikasi buku tamu (folder public/).
 */
function buku_tamu_url(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $config = require __DIR__ . '/app.php';
        $base = rtrim(rawurldecode($config['base_url'] ?? ''), '/');
    }

    $path = ltrim(str_replace('\\', '/', $path), '/');
    $url = $path === '' ? $base . '/' : $base . '/' . $path;

    return str_replace(' ', '%20', $url);
}
