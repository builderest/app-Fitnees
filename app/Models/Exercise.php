<?php
namespace App\Models;

class Exercise extends BaseModel
{
    protected static string $file = 'exercises.json';

    public string $name;
    public string $name_en;
    public string $slug;
    public string $description;
    public string $description_en;
    public string $primary_muscle;
    public array $secondary_muscles = [];
    public string $equipment;
    public string $difficulty;
    public string $video_url;
    public string $thumbnail_url;
    public array $tags = [];
}
