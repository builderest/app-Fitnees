<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getPDO();
$userId = requireAuth($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $date = $_GET['date'] ?? date('Y-m-d');
        $stmt = $pdo->prepare('SELECT * FROM meals WHERE user_id = :uid AND date = :date ORDER BY created_at DESC');
        $stmt->execute(['uid' => $userId, 'date' => $date]);
        jsonResponse(['items' => $stmt->fetchAll()]);
        break;
    case 'POST':
        $data = sanitize(getJsonInput());
        $stmt = $pdo->prepare('INSERT INTO meals (user_id, name, calories, proteins, carbs, fats, type, date, image)
            VALUES (:uid, :name, :calories, :proteins, :carbs, :fats, :type, :date, :image)');
        $stmt->execute([
            'uid' => $userId,
            'name' => $data['name'] ?? 'Comida',
            'calories' => (int)($data['calories'] ?? 0),
            'proteins' => (int)($data['proteins'] ?? 0),
            'carbs' => (int)($data['carbs'] ?? 0),
            'fats' => (int)($data['fats'] ?? 0),
            'type' => $data['type'] ?? 'desconocido',
            'date' => $data['date'] ?? date('Y-m-d'),
            'image' => $data['image'] ?? null,
        ]);
        jsonResponse(['message' => 'Comida registrada']);
        break;
    case 'PUT':
    case 'PATCH':
        $data = sanitize(getJsonInput());
        $id = (int)($data['id'] ?? 0);
        if (!$id) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $fields = ['name','calories','proteins','carbs','fats','type','date','image'];
        $set = [];
        $params = ['id' => $id, 'uid' => $userId];
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $set[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }
        if (!$set) {
            jsonResponse(['error' => 'Sin cambios'], 422);
        }
        $pdo->prepare('UPDATE meals SET ' . implode(', ', $set) . ' WHERE id = :id AND user_id = :uid')->execute($params);
        jsonResponse(['message' => 'Comida actualizada']);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $id = (int)($data['id'] ?? ($_GET['id'] ?? 0));
        if (!$id) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $pdo->prepare('DELETE FROM meals WHERE id = :id AND user_id = :uid')->execute(['id' => $id, 'uid' => $userId]);
        jsonResponse(['message' => 'Comida eliminada']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
