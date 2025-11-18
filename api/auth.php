<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getPDO();
$input = sanitize(array_merge($_POST, getJsonInput()));
$action = $_GET['action'] ?? '';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if ($action === 'register') {
            register($pdo, $input);
        } elseif ($action === 'login') {
            login($pdo, $input);
        } elseif ($action === 'logout') {
            logout($pdo);
        }
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}

function register(PDO $pdo, array $input): void
{
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $name = trim($input['name'] ?? '');
    $password = $input['password'] ?? '';
    if (!$email || !$name || strlen($password) < 6) {
        jsonResponse(['error' => 'Datos inválidos'], 422);
    }
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Usuario existente'], 409);
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :pass)');
    $stmt->execute(['name' => $name, 'email' => $email, 'pass' => $hash]);
    jsonResponse(['message' => 'Registro exitoso']);
}

function login(PDO $pdo, array $input): void
{
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $input['password'] ?? '';
    if (!$email || !$password) {
        jsonResponse(['error' => 'Credenciales inválidas'], 422);
    }
    $stmt = $pdo->prepare('SELECT id, password, name FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(['error' => 'Credenciales inválidas'], 401);
    }
    $token = bin2hex(random_bytes(32));
    $pdo->prepare('INSERT INTO sessions (user_id, token, expires_at) VALUES (:uid, :token, DATE_ADD(NOW(), INTERVAL 7 DAY))')
        ->execute(['uid' => $user['id'], 'token' => $token]);
    jsonResponse(['token' => $token, 'user' => ['id' => (int)$user['id'], 'name' => $user['name']]]);
}

function logout(PDO $pdo): void
{
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
        $token = trim($matches[1]);
        $pdo->prepare('DELETE FROM sessions WHERE token = :token')->execute(['token' => $token]);
    }
    jsonResponse(['message' => 'Sesión cerrada']);
}
