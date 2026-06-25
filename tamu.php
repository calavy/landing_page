<?php
/**
 * Pintu masuk Buku Tamu — redirect ke aplikasi buku tamu.
 */
declare(strict_types=1);

require_once __DIR__ . '/buku-tamu/config/paths.php';
header('Location: ' . buku_tamu_url(), true, 302);
exit;
