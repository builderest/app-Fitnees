<?php
namespace App\Core;

class StorageManager
{
    public static function read(string $filename): array
    {
        $path = storage_path('data/' . $filename);
        if (!file_exists($path)) {
            return [];
        }
        $content = file_get_contents($path);
        return $content ? json_decode($content, true) : [];
    }

    public static function write(string $filename, array $data): void
    {
        $path = storage_path('data/' . $filename);
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
    }
}
