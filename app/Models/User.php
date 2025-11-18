<?php
namespace App\Models;

class User extends BaseModel
{
    protected static string $file = 'users.json';

    public string $name;
    public string $email;
    public string $password;
    public string $role = 'user';
    public ?string $weight = null;
    public ?string $height = null;
    public ?string $age = null;
    public ?string $gender = null;
    public ?string $training_goal = null;
    public ?string $training_level = null;
    public string $plan = 'free';
    public ?string $premium_until = null;
}
