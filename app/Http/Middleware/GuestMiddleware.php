<?php
namespace App\Http\Middleware;

use App\Core\AuthManager;
use App\Core\Request;

class GuestMiddleware
{
    public function __invoke(Request $request, callable $next)
    {
        if (AuthManager::check()) {
            redirect('/dashboard');
        }
        return $next();
    }
}
