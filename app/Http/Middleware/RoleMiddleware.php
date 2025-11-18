<?php
namespace App\Http\Middleware;

use App\Core\AuthManager;
use App\Core\Request;

class RoleMiddleware
{
    private string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function __invoke(Request $request, callable $next)
    {
        $user = AuthManager::user();
        if (!$user || !in_array($user->role, explode('|', $this->role), true)) {
            flash('error', 'No autorizado');
            redirect('/dashboard');
        }
        return $next();
    }
}
