<?php
/**
 * Pintu masuk Undangan Digital — redirect ke panel admin undangan.
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

header('Location: ' . public_url('undangan/admin/login.php'), true, 302);
exit;
