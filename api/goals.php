<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getPDO();
$userId = requireAuth($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare('SELECT * FROM goals WHERE user_id = :uid ORDER BY updated_at DESC LIMIT 1');
        $stmt->execute(['uid' => $userId]);
        jsonResponse($stmt->fetch() ?: []);
        break;
    case 'POST':
    case 'PUT':
    case 'PATCH':
        $data = sanitize(getJsonInput());
        $pdo->prepare('INSERT INTO goals (user_id, current_weight, target_weight, objective, calories, level, updated_at)
            VALUES (:uid,:current,:target,:objective,:calories,:level,NOW())')
            ->execute([
                'uid' => $userId,
                'current' => (float)($data['current_weight'] ?? 0),
                'target' => (float)($data['target_weight'] ?? 0),
                'objective' => $data['objective'] ?? 'mantener',
                'calories' => (int)($data['calories'] ?? 2000),
                'level' => $data['level'] ?? 'intermedio',
            ]);
        jsonResponse(['message' => 'Meta guardada']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
