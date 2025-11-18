<?php
require_once __DIR__ . '/db.php';

$pdo = getPDO();
$user = requireUser($pdo);
$action = $_GET['action'] ?? '';

if ($action === 'dashboard') {
    dashboard($pdo, $user);
}

jsonResponse([
    'id' => $user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'level' => $user['level'],
    'gender' => $user['gender'],
    'birthdate' => $user['birthdate']
]);

function dashboard(PDO $pdo, array $user): void
{
    $metaStmt = $pdo->prepare('SELECT * FROM metas WHERE user_id = :user ORDER BY updated_at DESC LIMIT 1');
    $metaStmt->execute([':user' => $user['id']]);
    $goal = $metaStmt->fetch(PDO::FETCH_ASSOC) ?: ['calorie_target' => 2200, 'water_goal_ml' => 2000, 'water_current_ml' => 0];

    $routineStmt = $pdo->prepare('SELECT r.id, r.name, COALESCE(er.total_sessions,0) as completions FROM rutinas r LEFT JOIN (
        SELECT rutina_id, COUNT(*) total_sessions FROM entrenos_realizados WHERE user_id = :user AND completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY rutina_id
    ) er ON er.rutina_id = r.id WHERE r.user_id = :user OR r.is_public = 1 ORDER BY r.created_at DESC LIMIT 1');
    $routineStmt->execute([':user' => $user['id']]);
    $routine = $routineStmt->fetch(PDO::FETCH_ASSOC) ?: ['name' => 'Empieza con FitLifePro', 'completions' => 0];

    $hydrationPercent = $goal['water_goal_ml'] > 0 ? round(($goal['water_current_ml'] / $goal['water_goal_ml']) * 100) : 0;

    $minutesStmt = $pdo->prepare('SELECT COALESCE(SUM(duration_min), 0) as minutos FROM entrenos_realizados WHERE user_id = :user AND completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
    $minutesStmt->execute([':user' => $user['id']]);
    $minutes = (int) $minutesStmt->fetchColumn();

    jsonResponse([
        'calorias' => (int) $goal['calorie_target'],
        'minutosActivos' => $minutes,
        'agua' => $hydrationPercent,
        'aguaActual' => (int) $goal['water_current_ml'],
        'metaAgua' => (int) $goal['water_goal_ml'],
        'rutina' => [
            'nombre' => $routine['name'],
            'progreso' => min(100, ($routine['completions'] ?? 0) * 10)
        ]
    ]);
}
