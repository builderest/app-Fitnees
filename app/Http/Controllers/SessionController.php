<?php
namespace App\Http\Controllers;

use App\Core\AuthManager;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Exercise;
use App\Models\WorkoutSession;
use App\Services\SessionService;

class SessionController extends Controller
{
    public function index(): void
    {
        $user = AuthManager::user();
        $sessions = WorkoutSession::filter(fn ($session) => $session->user_id === $user->id);
        $this->view('sessions.index', compact('sessions'));
    }

    public function start(Request $request): void
    {
        $user = AuthManager::user();
        $service = new SessionService();
        $exerciseIds = array_map('intval', $request->post['exercise_ids'] ?? []);
        $exercises = array_map(fn ($id) => Exercise::find($id), $exerciseIds);
        $payload = [
            'date' => date('Y-m-d'),
            'exercises' => array_map(fn ($exercise) => ['id' => $exercise->id, 'name' => $exercise->name], $exercises),
        ];
        $service->createSession($user->id, $payload);
        redirect('/sessions');
    }

    public function complete(Request $request): void
    {
        $session = WorkoutSession::find((int) $request->input('id'));
        $service = new SessionService();
        $service->completeExercise($session);
        redirect('/sessions');
    }
}
