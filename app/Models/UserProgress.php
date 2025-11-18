<?php
namespace App\Models;

class UserProgress extends BaseModel
{
    protected static string $file = 'progress.json';

    public int $user_id;
    public string $date;
    public float $weight;
    public ?float $body_fat = null;
    public ?float $chest = null;
    public ?float $waist = null;
    public ?float $arms = null;
    public ?float $legs = null;
    public ?string $notes = null;
}
