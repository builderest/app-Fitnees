<?php
require_once __DIR__ . '/db.php';

$pdo = getPDO();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        register($pdo);
        break;
    case 'login':
        login($pdo);
        break;
    case 'logout':
        logout($pdo);
        break;
    default:
        jsonResponse(['error' => 'Acción no soportada'], 400);
}

function register(PDO $pdo): void
{
    $data = getJsonInput();
    $required = ['name','email','password','gender','birthdate','level'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            jsonResponse(['error' => "Campo {$field} es obligatorio"], 422);
        }
    }
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute([':email' => strtolower($data['email'])]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'El email ya está registrado'], 409);
    }
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, gender, birthdate, level, created_at) VALUES (:name, :email, :password, :gender, :birthdate, :level, NOW())');
    $stmt->execute([
        ':name' => trim($data['name']),
        ':email' => strtolower($data['email']),
        ':password' => hashPassword($data['password']),
        ':gender' => $data['gender'],
        ':birthdate' => $data['birthdate'],
        ':level' => $data['level']
    ]);
    jsonResponse(['message' => 'Cuenta creada con éxito'], 201);
}

function login(PDO $pdo): void
{
    $data = getJsonInput();
    if (empty($data['email']) || empty($data['password'])) {
        jsonResponse(['error' => 'Email y contraseña son obligatorios'], 422);
    }
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => strtolower($data['email'])]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user || !verifyPassword($data['password'], $user['password'])) {
        jsonResponse(['error' => 'Credenciales inválidas'], 401);
    }
    $token = bin2hex(random_bytes(32));
    $stmt = $pdo->prepare('INSERT INTO sessions (user_id, token, expires_at, created_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 1 DAY), NOW())');
    $stmt->execute([
        ':user_id' => $user['id'],
        ':token' => $token
    ]);
    jsonResponse(['token' => $token, 'user' => ['name' => $user['name'], 'email' => $user['email'], 'level' => $user['level']]]);
}

function logout(PDO $pdo): void
{
    $token = getBearerToken();
    if ($token) {
        $stmt = $pdo->prepare('DELETE FROM sessions WHERE token = :token');
        $stmt->execute([':token' => $token]);
    }
    jsonResponse(['message' => 'Sesión cerrada']);
}
