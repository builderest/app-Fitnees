<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getPDO();
$userId = requireAuth($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $today = date('Y-m-d');
        $goalStmt = $pdo->prepare('SELECT daily_goal FROM water WHERE user_id = :uid ORDER BY updated_at DESC LIMIT 1');
        $goalStmt->execute(['uid' => $userId]);
        $goal = (int)($goalStmt->fetchColumn() ?: 2000);
        $consumedStmt = $pdo->prepare('SELECT SUM(amount) FROM water_logs WHERE user_id = :uid AND date = :date');
        $consumedStmt->execute(['uid' => $userId, 'date' => $today]);
        $consumed = (int)($consumedStmt->fetchColumn() ?: 0);
        $history = $pdo->prepare('SELECT date, SUM(amount) as total FROM water_logs WHERE user_id = :uid GROUP BY date ORDER BY date DESC LIMIT 7');
        $history->execute(['uid' => $userId]);
        jsonResponse(['goal' => $goal, 'consumed' => $consumed, 'history' => $history->fetchAll()]);
        break;
    case 'POST':
        $data = sanitize(getJsonInput());
        $amount = (int)($data['amount'] ?? 0);
        if ($amount <= 0) {
            jsonResponse(['error' => 'Cantidad inválida'], 422);
        }
        $stmt = $pdo->prepare('INSERT INTO water_logs (user_id, amount, date) VALUES (:uid, :amount, :date)');
        $stmt->execute(['uid' => $userId, 'amount' => $amount, 'date' => date('Y-m-d')]);
        jsonResponse(['message' => 'Registro agregado']);
        break;
    case 'PUT':
    case 'PATCH':
        $data = sanitize(getJsonInput());
        $goal = (int)($data['goal'] ?? 0);
        if ($goal < 500) {
            jsonResponse(['error' => 'Meta inválida'], 422);
        }
        $pdo->prepare('INSERT INTO water (user_id, daily_goal, updated_at) VALUES (:uid, :goal, NOW())')->execute(['uid' => $userId, 'goal' => $goal]);
        jsonResponse(['message' => 'Meta actualizada']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
