<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function getPDO(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
    $options = [
        PDO::ATTR_ERRMODE => APP_ENV === 'production' ? PDO::ERRMODE_SILENT : PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if (APP_DEBUG) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        jsonResponse(['error' => 'Database connection error'], 500);
    }
    return $pdo;
}
