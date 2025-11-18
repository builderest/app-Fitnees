<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function getPDO(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', env('DB_HOST', 'localhost'), env('DB_NAME', 'fitlifepro'));
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, env('DB_USER'), env('DB_PASS'), $options);
    } catch (PDOException $exception) {
        jsonResponse(['error' => 'No se pudo conectar a la base de datos'], 500);
    }
    return $pdo;
}
