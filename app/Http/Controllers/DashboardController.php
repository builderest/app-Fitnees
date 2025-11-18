<?php
namespace App\Http\Controllers;

use App\Core\AuthManager;
use App\Core\Controller;
use App\Core\Request;
use App\Models\WorkoutSession;
use App\Services\ProgressService;

class DashboardController extends Controller
{
    public function index(): void
    {
        $user = AuthManager::user();
        $sessions = WorkoutSession::filter(fn ($session) => $session->user_id === $user->id);
        $progressService = new ProgressService();
        $weightSeries = $progressService->weightSeries($user->id);

        $this->view('dashboard.index', [
            'user' => $user,
            'sessions' => $sessions,
            'weightSeries' => $weightSeries,
        ]);
    }
}
