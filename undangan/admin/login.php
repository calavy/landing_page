<?php
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password && login($username, $password)) {
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Haflah Undangan</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #064E3B;
            --accent: #D4AF37;
            --muted: #6b7c77;
            --border: #e2ebe8;
            --danger: #dc3545;
            --radius: 10px;
        }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            line-height: 1.5;
        }
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #064E3B 0%, #0a6b52 100%);
        }
        .login-card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            margin: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
        }
        .login-header { text-align: center; margin-bottom: 1.5rem; }
        .login-icon { font-size: 2.5rem; }
        .login-header h1 { color: var(--primary); margin: .5rem 0 .25rem; font-size: 1.75rem; }
        .login-header p { color: var(--muted); font-size: .9rem; }
        .login-form label {
            display: block;
            margin-bottom: 1rem;
            font-size: .875rem;
            font-weight: 600;
        }
        .login-form input {
            display: block;
            width: 100%;
            margin-top: .35rem;
            padding: .65rem .85rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
        }
        .login-hint {
            text-align: center;
            margin-top: 1rem;
            font-size: .8rem;
            color: var(--muted);
        }
        .btn {
            display: inline-block;
            padding: .65rem 1.25rem;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: #053d2f; }
        .btn-block { width: 100%; }
        .alert {
            padding: .75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: .875rem;
        }
        .alert-error { background: #f8d7da; color: #842029; }
    </style>
</head>
<body class="login-page">
    <div class="login-card">
        <div class="login-header">
            <span class="login-icon">🕌</span>
            <h1>Back Office</h1>
            <p>Panel Admin Undangan Haflah</p>
        </div>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="POST" class="login-form">
            <label>
                Username
                <input type="text" name="username" required autofocus autocomplete="username">
            </label>
            <label>
                Password
                <input type="password" name="password" required autocomplete="current-password">
            </label>
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </form>
        <p class="login-hint">Default: <code>admin</code> / <code>admin123</code></p>
    </div>
</body>
</html>
