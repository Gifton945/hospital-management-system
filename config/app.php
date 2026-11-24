<?php
require_once __DIR__ . '/database.php';

function env_value(string $key, string $default = ''): string {
    static $values = null;
    if ($values === null) {
        $values = [];
        $path = dirname(__DIR__) . '/.env';
        if (is_readable($path)) {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
                [$name, $value] = array_map('trim', explode('=', $line, 2));
                $values[$name] = $value;
            }
        }
    }
    return $values[$key] ?? $default;
}

session_start();

if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

function base_url(string $path = ''): string {
    $script = str_replace('\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $base = rtrim($script, '/');
    return ($base === '' ? '' : $base) . '/' . ltrim($path, '/');
}

function redirect(string $path): never {
    header('Location: ' . base_url($path));
    exit;
}

function require_login(): array {
    if (empty($_SESSION['user'])) redirect('login.php');
    return $_SESSION['user'];
}

function require_role(array $roles): array {
    $user = require_login();
    if (!in_array($user['role'], $roles, true)) {
        http_response_code(403);
        exit('You do not have permission to access this page.');
    }
    return $user;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function verify_csrf(): void {
    if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(403); exit('Invalid security token. Refresh the page and try again.');
    }
}

function flash(string $message = null, string $type = 'success'): ?string {
    if ($message !== null) { $_SESSION['flash'] = [$message, $type]; return null; }
    $value = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
    return $value ? '<div class="alert alert-' . htmlspecialchars($value[1]) . '">' . htmlspecialchars($value[0]) . '</div>' : null;
}
