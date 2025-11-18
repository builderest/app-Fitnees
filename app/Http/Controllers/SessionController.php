<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSession;
use App\Services\SessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function __construct(private SessionService $service)
    {
    }

    public function index(): View
    {
        $sessions = WorkoutSession::where('user_id', Auth::id())
            ->with('exercises.exercise')
            ->latest('performed_at')
            ->paginate(10);

        return view('sessions.index', compact('sessions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'workout_program_id' => ['nullable', 'exists:workout_programs,id'],
            'workout_day_id' => ['nullable', 'exists:workout_days,id'],
            'intensity' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->service->start($data);

        return back()->with('status', 'Sesión guardada');
    }
}
