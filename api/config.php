<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

const ROOT_PATH = __DIR__ . '/..';
$envFile = ROOT_PATH . '/.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'UTC');

function env(string $key, $default = null)
{
    $value = getenv($key);
    return $value === false ? $default : $value;
}

function jsonResponse(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function getJsonInput(): array
{
    $contents = file_get_contents('php://input');
    if (!$contents) {
        return [];
    }
    $decoded = json_decode($contents, true);
    if (!is_array($decoded)) {
        jsonResponse(['error' => 'JSON inválido'], 400);
    }
    return $decoded;
}

function getBearerToken(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
        return trim($matches[1]);
    }
    if (!empty($_SERVER['HTTP_X_AUTH_TOKEN'])) {
        return trim($_SERVER['HTTP_X_AUTH_TOKEN']);
    }
    return null;
}

function requireUser(PDO $pdo): array
{
    $token = getBearerToken();
    if (!$token) {
        jsonResponse(['error' => 'Token requerido'], 401);
    }
    $stmt = $pdo->prepare('SELECT u.* FROM sessions s JOIN users u ON u.id = s.user_id WHERE s.token = :token AND s.expires_at > NOW()');
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        jsonResponse(['error' => 'Sesión no válida'], 401);
    }
    return $user;
}

function hashPassword(string $plain): string
{
    return password_hash($plain, PASSWORD_BCRYPT);
}

function verifyPassword(string $plain, string $hash): bool
{
    return password_verify($plain, $hash);
}
