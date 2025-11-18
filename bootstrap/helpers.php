<?php
function base_path(string $path = ''): string
{
    return __DIR__ . '/../' . ltrim($path, '/');
}

function resource_path(string $path = ''): string
{
    return base_path('resources/' . ltrim($path, '/'));
}

function view(string $view, array $data = []): string
{
    $viewFile = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');
    if (!file_exists($viewFile)) {
        throw new RuntimeException("View {$view} not found");
    }
    extract($data);
    ob_start();
    include $viewFile;
    return ob_get_clean();
}

function storage_path(string $path = ''): string
{
    return base_path('storage/' . ltrim($path, '/'));
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function old(string $key, $default = null)
{
    return $_SESSION['old'][$key] ?? $default;
}

function set_old(array $data): void
{
    $_SESSION['old'] = $data;
}

function flash(string $key, string $value): void
{
    $_SESSION['flash'][$key] = $value;
}

function get_flash(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }
    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $value;
}
