<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getPDO();
$userId = requireAuth($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare('SELECT * FROM routines WHERE user_id = :uid ORDER BY created_at DESC');
        $stmt->execute(['uid' => $userId]);
        jsonResponse(['items' => $stmt->fetchAll()]);
        break;
    case 'POST':
        $data = sanitize(getJsonInput());
        $stmt = $pdo->prepare('INSERT INTO routines (user_id, name, type, scheduled_at) VALUES (:uid, :name, :type, :scheduled)');
        $stmt->execute([
            'uid' => $userId,
            'name' => $data['name'] ?? 'Nueva rutina',
            'type' => $data['type'] ?? 'general',
            'scheduled' => $data['scheduled_at'] ?? date('Y-m-d H:i:s'),
        ]);
        jsonResponse(['message' => 'Rutina creada']);
        break;
    case 'PUT':
    case 'PATCH':
        $data = sanitize(getJsonInput());
        $id = (int)($data['id'] ?? 0);
        if (!$id) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $fields = ['name','type','scheduled_at','status'];
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
        $pdo->prepare('UPDATE routines SET ' . implode(', ', $set) . ' WHERE id = :id AND user_id = :uid')->execute($params);
        jsonResponse(['message' => 'Rutina actualizada']);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $id = (int)($data['id'] ?? ($_GET['id'] ?? 0));
        if (!$id) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $pdo->prepare('DELETE FROM routines WHERE id = :id AND user_id = :uid')->execute(['id' => $id, 'uid' => $userId]);
        jsonResponse(['message' => 'Rutina eliminada']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
