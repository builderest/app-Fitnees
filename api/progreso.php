<?php
require_once __DIR__ . '/db.php';

$pdo = getPDO();
$user = requireUser($pdo);
$type = $_GET['type'] ?? 'peso';

switch ($type) {
    case 'peso':
        $stmt = $pdo->prepare('SELECT DATE_FORMAT(logged_at, "%d/%m") as label, weight FROM progreso_peso WHERE user_id = :user ORDER BY logged_at ASC LIMIT 12');
        $stmt->execute([':user' => $user['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['labels' => array_column($rows, 'label'), 'data' => array_map('floatval', array_column($rows, 'weight'))]);
        break;
    case 'entrenos':
        $stmt = $pdo->prepare('SELECT DATE_FORMAT(completed_at, "%d/%m") as label, COUNT(*) total FROM entrenos_realizados WHERE user_id = :user GROUP BY DATE(completed_at) ORDER BY completed_at ASC LIMIT 10');
        $stmt->execute([':user' => $user['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['labels' => array_column($rows, 'label'), 'data' => array_map('intval', array_column($rows, 'total'))]);
        break;
    case 'calorias':
        $stmt = $pdo->prepare('SELECT DATE_FORMAT(logged_date, "%d/%m") as label, calories FROM progreso_calorias WHERE user_id = :user ORDER BY logged_date ASC LIMIT 12');
        $stmt->execute([':user' => $user['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['labels' => array_column($rows, 'label'), 'data' => array_map('intval', array_column($rows, 'calories'))]);
        break;
    default:
        jsonResponse(['error' => 'Tipo no soportado'], 400);
}
