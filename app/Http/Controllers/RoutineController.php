<?php

namespace App\Http\Controllers;

use App\Models\WorkoutProgram;
use App\Services\RoutineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoutineController extends Controller
{
    public function __construct(private RoutineService $service)
    {
    }

    public function index(): View
    {
        $programs = WorkoutProgram::where('user_id', Auth::id())
            ->orWhere('is_global', true)
            ->with('days.exercises.exercise')
            ->paginate(10);

        return view('routines.index', compact('programs'));
    }

    public function create(): View
    {
        return view('routines.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'goal' => ['nullable', 'string'],
            'level' => ['nullable', 'string'],
            'type' => ['required', 'string'],
        ]);

        WorkoutProgram::create(array_merge($data, [
            'user_id' => Auth::id(),
            'is_global' => false,
        ]));

        return redirect()->route('routines.index')->with('status', 'Rutina creada');
    }

    public function duplicate(WorkoutProgram $program): RedirectResponse
    {
        $this->authorize('update', $program);
        $this->service->duplicate($program->load('days.exercises'), Auth::id());

        return back()->with('status', 'Rutina duplicada');
    }
}
