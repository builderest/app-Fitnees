<?php
require_once __DIR__ . '/db.php';

$pdo = getPDO();
$user = requireUser($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST' && $action === 'completar') {
    $data = getJsonInput();
    $stmt = $pdo->prepare('INSERT INTO entrenos_realizados (rutina_id, user_id, duration_min, completed_at) VALUES (:rutina,:user,:duracion,NOW())');
    $stmt->execute([
        ':rutina' => $data['rutina_id'],
        ':user' => $user['id'],
        ':duracion' => (int)($data['duration_min'] ?? 30)
    ]);
    jsonResponse(['message' => 'Sesión registrada']);
}

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare('SELECT * FROM rutinas WHERE user_id = :user OR is_public = 1 ORDER BY created_at DESC');
        $stmt->execute([':user' => $user['id']]);
        $rutinas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rutinas as &$rutina) {
            $exStmt = $pdo->prepare('SELECT re.*, e.name, e.duration as base_duration FROM rutina_ejercicios re JOIN ejercicios e ON e.id = re.ejercicio_id WHERE re.rutina_id = :rutina ORDER BY re.position');
            $exStmt->execute([':rutina' => $rutina['id']]);
            $rutina['exercises'] = array_map(function ($exercise) {
                return [
                    'name' => $exercise['name'],
                    'series' => $exercise['series'],
                    'reps' => $exercise['repetitions'],
                    'duration' => $exercise['duration'] ?: $exercise['base_duration'],
                    'rest' => $exercise['rest_seconds']
                ];
            }, $exStmt->fetchAll(PDO::FETCH_ASSOC));
            $rutina['progress'] = rand(10, 70); // fallback visual
        }
        jsonResponse(['items' => $rutinas]);
        break;
    case 'POST':
        $data = getJsonInput();
        $stmt = $pdo->prepare('INSERT INTO rutinas (user_id, name, goal, level, days_per_week, description, is_public, created_at) VALUES (:user,:name,:goal,:level,:days,:description,0,NOW())');
        $stmt->execute([
            ':user' => $user['id'],
            ':name' => $data['name'],
            ':goal' => $data['goal'],
            ':level' => $data['level'],
            ':days' => (int)$data['days_per_week'],
            ':description' => $data['description'] ?? ''
        ]);
        jsonResponse(['message' => 'Rutina creada']);
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
        if (empty($query['id'])) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $data = getJsonInput();
        $stmt = $pdo->prepare('UPDATE rutinas SET name=:name, goal=:goal, level=:level, days_per_week=:days, description=:description WHERE id=:id AND user_id=:user');
        $stmt->execute([
            ':name' => $data['name'],
            ':goal' => $data['goal'],
            ':level' => $data['level'],
            ':days' => (int)$data['days_per_week'],
            ':description' => $data['description'] ?? '',
            ':id' => (int)$query['id'],
            ':user' => $user['id']
        ]);
        jsonResponse(['message' => 'Rutina actualizada']);
        break;
    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
        if (empty($query['id'])) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $stmt = $pdo->prepare('DELETE FROM rutinas WHERE id = :id AND user_id = :user');
        $stmt->execute([':id' => (int)$query['id'], ':user' => $user['id']]);
        jsonResponse(['message' => 'Rutina eliminada']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
