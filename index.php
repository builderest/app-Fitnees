<?php
// VidaPro+ WebApp bootstrap front controller

declare(strict_types=1);

define('APP_ROOT', __DIR__);

// Simple .env loader
$envFile = APP_ROOT . DIRECTORY_SEPARATOR . '.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($key !== '') {
            putenv("{$key}={$value}");
        }
    }
}

$env = getenv('APP_ENV') ?: 'production';
if ($env === 'production') {
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'UTC');

header('Location: app/index.html');
exit;
