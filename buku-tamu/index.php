<?php
/**
 * Pintu masuk Buku Tamu — redirect ke form tamu (public).
 */
declare(strict_types=1);

require_once __DIR__ . '/config/paths.php';
header('Location: ' . buku_tamu_url());
exit;
