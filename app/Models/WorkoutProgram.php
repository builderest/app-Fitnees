<?php
namespace App\Models;

class WorkoutProgram extends BaseModel
{
    protected static string $file = 'programs.json';

    public string $title;
    public string $type;
    public string $owner_type = 'global';
    public int $user_id = 0;
    public array $days = [];
    public bool $is_active = false;
}
