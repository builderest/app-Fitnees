<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getPDO();
$userId = requireAuth($pdo);

$weightStmt = $pdo->prepare('SELECT weight, recorded_at FROM progress WHERE user_id = :uid ORDER BY recorded_at DESC LIMIT 1');
$weightStmt->execute(['uid' => $userId]);
$weight = $weightStmt->fetch();

$caloriesStmt = $pdo->prepare('SELECT AVG(calories) FROM meals WHERE user_id = :uid AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
$caloriesStmt->execute(['uid' => $userId]);
$avgCalories = (int)($caloriesStmt->fetchColumn() ?: 0);

$routinesStmt = $pdo->prepare("SELECT COUNT(*) FROM routines WHERE user_id = :uid AND status = 'completed' AND updated_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$routinesStmt->execute(['uid' => $userId]);
$routinesCompleted = (int)$routinesStmt->fetchColumn();

$habitsStmt = $pdo->prepare('SELECT COUNT(*) FROM habits_logs WHERE user_id = :uid AND completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
$habitsStmt->execute(['uid' => $userId]);
$habitsCompleted = (int)$habitsStmt->fetchColumn();

jsonResponse([
    'weight' => $weight['weight'] ?? null,
    'weight_date' => $weight['recorded_at'] ?? null,
    'avgCalories' => $avgCalories,
    'routinesCompleted' => $routinesCompleted,
    'habitsCompleted' => $habitsCompleted,
    'goal' => 'Mantener',
    'level' => 'Intermedio'
]);
