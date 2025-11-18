<?php
namespace App\Http\Middleware;

use App\Core\AuthManager;
use App\Core\Request;

class CheckPremium
{
    public function __invoke(Request $request, callable $next)
    {
        $user = AuthManager::user();
        if (!$user || $user->plan !== 'premium') {
            flash('error', 'Necesitas Premium para acceder');
            redirect('/pricing');
        }
        return $next();
    }
}
