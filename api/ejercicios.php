<?php
require_once __DIR__ . '/db.php';

$pdo = getPDO();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    $search = $_GET['search'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM ejercicios WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$exercise) {
            jsonResponse(['error' => 'Ejercicio no encontrado'], 404);
        }
        jsonResponse(['item' => $exercise]);
    }
    $sql = 'SELECT * FROM ejercicios';
    $params = [];
    if ($search) {
        $sql .= ' WHERE name LIKE :search OR category LIKE :search';
        $params[':search'] = "%{$search}%";
    }
    $sql .= ' ORDER BY level, name';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    jsonResponse(['items' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
}

$user = requireUser($pdo);

switch ($method) {
    case 'POST':
        $data = getJsonInput();
        $stmt = $pdo->prepare('INSERT INTO ejercicios (name, category, level, muscles, description, tips, duration, image, animation_json, created_by) VALUES (:name,:category,:level,:muscles,:description,:tips,:duration,:image,:animation,:created_by)');
        $stmt->execute([
            ':name' => $data['name'] ?? 'Ejercicio',
            ':category' => $data['category'] ?? 'full body',
            ':level' => $data['level'] ?? 'basico',
            ':muscles' => $data['muscles'] ?? '',
            ':description' => $data['description'] ?? '',
            ':tips' => $data['tips'] ?? '',
            ':duration' => (int)($data['duration'] ?? 60),
            ':image' => $data['image'] ?? 'assets/images/ejercicios/jumping-jacks.svg',
            ':animation' => $data['animation'] ?? json_encode(['frames' => 40]),
            ':created_by' => $user['id']
        ]);
        jsonResponse(['message' => 'Ejercicio creado']);
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
        if (empty($query['id'])) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $data = getJsonInput();
        $data['id'] = (int)$query['id'];
        $stmt = $pdo->prepare('UPDATE ejercicios SET name=:name, category=:category, level=:level, muscles=:muscles, description=:description, tips=:tips, duration=:duration WHERE id=:id');
        $stmt->execute([
            ':name' => $data['name'],
            ':category' => $data['category'],
            ':level' => $data['level'],
            ':muscles' => $data['muscles'],
            ':description' => $data['description'],
            ':tips' => $data['tips'],
            ':duration' => (int)$data['duration'],
            ':id' => $data['id']
        ]);
        jsonResponse(['message' => 'Ejercicio actualizado']);
        break;
    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
        if (empty($query['id'])) {
            jsonResponse(['error' => 'ID requerido'], 422);
        }
        $stmt = $pdo->prepare('DELETE FROM ejercicios WHERE id = :id');
        $stmt->execute([':id' => (int)$query['id']]);
        jsonResponse(['message' => 'Ejercicio eliminado']);
        break;
    default:
        jsonResponse(['error' => 'Método no permitido'], 405);
}
