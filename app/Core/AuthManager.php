<?php
namespace App\Core;

use App\Models\User;

class AuthManager
{
    public static function user(): ?User
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return User::find((int) $_SESSION['user_id']);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(array $credentials): bool
    {
        $user = User::where('email', $credentials['email'] ?? '');
        if (!$user) {
            return false;
        }
        if (!password_verify($credentials['password'] ?? '', $user->password)) {
            return false;
        }
        $_SESSION['user_id'] = $user->id;
        return true;
    }

    public static function login(User $user): void
    {
        $_SESSION['user_id'] = $user->id;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
    }
}
