<?php
namespace App\Models;

use App\Core\StorageManager;

abstract class BaseModel
{
    public int $id;
    protected static string $file;

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public static function all(): array
    {
        $records = StorageManager::read(static::$file);
        return array_map(fn ($item) => new static($item), $records);
    }

    public static function find(int $id): ?static
    {
        $records = StorageManager::read(static::$file);
        foreach ($records as $record) {
            if ((int) $record['id'] === $id) {
                return new static($record);
            }
        }
        return null;
    }

    public static function where(string $field, $value): ?static
    {
        $records = StorageManager::read(static::$file);
        foreach ($records as $record) {
            if (($record[$field] ?? null) == $value) {
                return new static($record);
            }
        }
        return null;
    }

    public static function filter(callable $callback): array
    {
        return array_values(array_filter(static::all(), $callback));
    }

    public function save(): void
    {
        $records = StorageManager::read(static::$file);
        if (!isset($this->id)) {
            $this->id = count($records) ? max(array_column($records, 'id')) + 1 : 1;
            $records[] = get_object_vars($this);
        } else {
            foreach ($records as &$record) {
                if ((int) $record['id'] === $this->id) {
                    $record = get_object_vars($this);
                    break;
                }
            }
        }
        StorageManager::write(static::$file, $records);
    }
}
