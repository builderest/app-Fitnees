<?php
declare(strict_types=1);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

function loadEnv(string $path): void
{
    if (!is_file($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($key !== '') {
            putenv("{$key}={$value}");
        }
    }
}

loadEnv(APP_ROOT . '/.env');

define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_DEBUG', filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN));
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'vidapro');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'UTC');

function jsonResponse(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

function getJsonInput(): array
{
    $raw = file_get_contents('php://input');
    return $raw ? (json_decode($raw, true) ?: []) : [];
}

function requireAuth(PDO $pdo): int
{
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
        jsonResponse(['error' => 'No autorizado'], 401);
    }
    $token = trim($matches[1]);
    $stmt = $pdo->prepare('SELECT user_id FROM sessions WHERE token = :token AND expires_at > NOW()');
    $stmt->execute(['token' => $token]);
    $userId = $stmt->fetchColumn();
    if (!$userId) {
        jsonResponse(['error' => 'Sesión inválida'], 401);
    }
    return (int)$userId;
}

function sanitize(array $data): array
{
    return array_map(static function ($value) {
        if (is_string($value)) {
            return trim(filter_var($value, FILTER_SANITIZE_STRING));
        }
        return $value;
    }, $data);
}
