<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'weight',
        'height',
        'age',
        'gender',
        'training_goal',
        'training_level',
        'plan',
        'premium_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'premium_until' => 'datetime',
        'password' => 'hashed',
    ];

    public function workoutPrograms(): HasMany
    {
        return $this->hasMany(WorkoutProgram::class);
    }

    public function workoutSessions(): HasMany
    {
        return $this->hasMany(WorkoutSession::class);
    }

    public function progressEntries(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    protected function isPremium(): Attribute
    {
        return Attribute::get(function () {
            return $this->plan === 'premium' && (! $this->premium_until || $this->premium_until->isFuture());
        });
    }
}
