<?php
require_once __DIR__ . '/db.php';

$pdo = getPDO();
$user = requireUser($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $day = $_GET['dia'] ?? date('Y-m-d');
        $stmt = $pdo->prepare('SELECT id, name, type, calories, proteins, carbs, fats, eaten_at FROM comidas_usuario WHERE user_id = :user AND DATE(eaten_at) = :day ORDER BY eaten_at');
        $stmt->execute([':user' => $user['id'], ':day' => $day]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $metaStmt = $pdo->prepare('SELECT calorie_target FROM metas WHERE user_id = :user ORDER BY updated_at DESC LIMIT 1');
        $metaStmt->execute([':user' => $user['id']]);
        $meta = $metaStmt->fetchColumn() ?: 2200;
        jsonResponse(['items' => $items, 'meta' => (int)$meta]);
        break;
    case 'POST':
        $data = getJsonInput();
        $stmt = $pdo->prepare('INSERT INTO comidas_usuario (user_id, name, type, calories, proteins, carbs, fats, eaten_at) VALUES (:user,:name,:type,:calories,:proteins,:carbs,:fats,:eaten_at)');
        $stmt->execute([
            ':user' => $user['id'],
            ':name' => $data['name'],
            ':type' => $data['type'],
            ':calories' => (int)$data['calories'],
            ':proteins' => (float)$data['proteins'],
            ':carbs' => (float)$data['carbs'],
            ':fats' => (float)$data['fats'],
            ':eaten_at' => $data['eaten_at'] ?? date('Y-m-d H:i:s')
        ]);
        jsonResponse(['message' => 'Comida registrada']);
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
        if (empty($query['id'])) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $data = getJsonInput();
        $stmt = $pdo->prepare('UPDATE comidas_usuario SET name=:name, type=:type, calories=:calories, proteins=:proteins, carbs=:carbs, fats=:fats WHERE id=:id AND user_id=:user');
        $stmt->execute([
            ':name' => $data['name'],
            ':type' => $data['type'],
            ':calories' => (int)$data['calories'],
            ':proteins' => (float)$data['proteins'],
            ':carbs' => (float)$data['carbs'],
            ':fats' => (float)$data['fats'],
            ':id' => (int)$query['id'],
            ':user' => $user['id']
        ]);
        jsonResponse(['message' => 'Comida actualizada']);
        break;
    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
        if (empty($query['id'])) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $stmt = $pdo->prepare('DELETE FROM comidas_usuario WHERE id = :id AND user_id = :user');
        $stmt->execute([':id' => (int)$query['id'], ':user' => $user['id']]);
        jsonResponse(['message' => 'Comida eliminada']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
