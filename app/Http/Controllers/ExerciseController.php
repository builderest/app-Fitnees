<?php
namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Exercise;
use App\Services\ExerciseService;

class ExerciseController extends Controller
{
    public function index(Request $request): void
    {
        $service = new ExerciseService();
        $filters = [
            'muscle' => $request->input('muscle'),
            'equipment' => $request->input('equipment'),
            'difficulty' => $request->input('difficulty'),
            'search' => $request->input('search'),
        ];
        $exercises = $service->filter($filters);
        $this->view('exercises.index', compact('exercises', 'filters'));
    }

    public function show(Request $request): void
    {
        $slug = $request->input('slug');
        $exercise = Exercise::where('slug', $slug);
        $this->view('exercises.show', compact('exercise'));
    }
}
