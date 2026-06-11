<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => false // Nastavitev na true, ce se uporablja HTTPS
    ]);

    session_start();
}

define('SESSION_TIMEOUT_SECONDS', 900); // 15 minut

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }

    if (
        isset($_SESSION['last_activity'])
        && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT_SECONDS
    ) {
        logout_user();
        header('Location: login.php?timeout=1');
        exit;
    }

    $_SESSION['last_activity'] = time();
}

function redirect_if_logged_in(): void
{
    if (is_logged_in()) {
        header('Location: dashboard.php');
        exit;
    }
}

function logout_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'] ?? '',
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function current_user_id(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function escape_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}