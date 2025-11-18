<?php
namespace App\Http\Controllers;

use App\Core\AuthManager;
use App\Core\Controller;
use App\Core\Request;
use App\Models\WorkoutProgram;
use App\Services\RoutineService;

class RoutineController extends Controller
{
    public function index(): void
    {
        $service = new RoutineService();
        $user = AuthManager::user();
        $programs = $service->userPrograms($user->id);
        $this->view('routines.index', compact('programs'));
    }

    public function create(): void
    {
        $this->view('routines.create');
    }

    public function store(Request $request): void
    {
        $user = AuthManager::user();
        $service = new RoutineService();
        $rawDays = $request->post['days'] ?? [];
        $parsedDays = [];
        foreach ($rawDays as $day) {
            $decoded = json_decode($day, true);
            if ($decoded) {
                $parsedDays[] = $decoded;
            }
        }
        $data = [
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'days' => $parsedDays,
        ];
        $service->createProgram($user->id, $data);
        redirect('/routines');
    }

    public function activate(Request $request): void
    {
        $program = WorkoutProgram::find((int) $request->input('id'));
        $service = new RoutineService();
        $service->activate(AuthManager::user()->id, $program);
        redirect('/routines');
    }
}
