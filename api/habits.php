<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getPDO();
$userId = requireAuth($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare('SELECT * FROM habits WHERE user_id = :uid ORDER BY created_at DESC');
        $stmt->execute(['uid' => $userId]);
        jsonResponse(['items' => $stmt->fetchAll()]);
        break;
    case 'POST':
        $data = sanitize(getJsonInput());
        $pdo->prepare('INSERT INTO habits (user_id, name, frequency, reminder_time, icon) VALUES (:uid,:name,:freq,:time,:icon)')
            ->execute([
                'uid' => $userId,
                'name' => $data['name'] ?? 'Nuevo hábito',
                'freq' => $data['frequency'] ?? 'diario',
                'time' => $data['reminder_time'] ?? '08:00:00',
                'icon' => $data['icon'] ?? '🔥',
            ]);
        jsonResponse(['message' => 'Hábito creado']);
        break;
    case 'PATCH':
        $data = sanitize(getJsonInput());
        $id = (int)($data['id'] ?? 0);
        if (!$id) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        if (isset($data['complete'])) {
            $pdo->prepare('INSERT INTO habits_logs (habit_id, user_id, completed_at) VALUES (:hid,:uid,NOW())')
                ->execute(['hid' => $id, 'uid' => $userId]);
            jsonResponse(['message' => 'Hábito completado']);
        }
        $fields = ['name','frequency','reminder_time','icon'];
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
        $pdo->prepare('UPDATE habits SET ' . implode(', ', $set) . ' WHERE id = :id AND user_id = :uid')->execute($params);
        jsonResponse(['message' => 'Hábito actualizado']);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $id = (int)($data['id'] ?? ($_GET['id'] ?? 0));
        if (!$id) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $pdo->prepare('DELETE FROM habits WHERE id = :id AND user_id = :uid')->execute(['id' => $id, 'uid' => $userId]);
        jsonResponse(['message' => 'Hábito eliminado']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
