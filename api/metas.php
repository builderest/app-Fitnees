<?php
require_once __DIR__ . '/db.php';

$pdo = getPDO();
$user = requireUser($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare('SELECT * FROM metas WHERE user_id = :user ORDER BY updated_at DESC LIMIT 1');
        $stmt->execute([':user' => $user['id']]);
        $goal = $stmt->fetch(PDO::FETCH_ASSOC);
        jsonResponse(['goal' => $goal]);
        break;
    case 'POST':
        $data = getJsonInput();
        $stmt = $pdo->prepare('INSERT INTO metas (user_id, main_goal, current_weight, target_weight, weeks_target, daily_minutes, daily_steps, water_goal_ml, water_current_ml, workouts_per_week, progress_percent, calorie_target, updated_at) VALUES (:user,:main_goal,:current_weight,:target_weight,:weeks_target,:daily_minutes,:daily_steps,:water_goal,:water_current,:workouts,:progress,:calories,NOW())');
        $stmt->execute([
            ':user' => $user['id'],
            ':main_goal' => $data['main_goal'],
            ':current_weight' => $data['current_weight'],
            ':target_weight' => $data['target_weight'],
            ':weeks_target' => $data['weeks_target'],
            ':daily_minutes' => $data['daily_minutes'],
            ':daily_steps' => $data['daily_steps'],
            ':water_goal' => $data['water_goal_ml'],
            ':water_current' => $data['water_current_ml'] ?? 0,
            ':workouts' => $data['workouts_per_week'],
            ':progress' => $data['progress_percent'] ?? 0,
            ':calories' => $data['calorie_target']
        ]);
        jsonResponse(['message' => 'Meta creada']);
        break;
    case 'PUT':
        $data = getJsonInput();
        $stmt = $pdo->prepare('UPDATE metas SET main_goal=:main_goal, current_weight=:current_weight, target_weight=:target_weight, weeks_target=:weeks_target, daily_minutes=:daily_minutes, daily_steps=:daily_steps, water_goal_ml=:water_goal, water_current_ml=:water_current, workouts_per_week=:workouts, progress_percent=:progress, calorie_target=:calories, updated_at=NOW() WHERE user_id=:user');
        $stmt->execute([
            ':main_goal' => $data['main_goal'],
            ':current_weight' => $data['current_weight'],
            ':target_weight' => $data['target_weight'],
            ':weeks_target' => $data['weeks_target'],
            ':daily_minutes' => $data['daily_minutes'],
            ':daily_steps' => $data['daily_steps'],
            ':water_goal' => $data['water_goal_ml'],
            ':water_current' => $data['water_current_ml'],
            ':workouts' => $data['workouts_per_week'],
            ':progress' => $data['progress_percent'],
            ':calories' => $data['calorie_target'],
            ':user' => $user['id']
        ]);
        jsonResponse(['message' => 'Meta actualizada']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
