<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Exercise::query();

        if ($request->filled('muscle')) {
            $query->where('primary_muscle', $request->string('muscle'));
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->string('difficulty'));
        }

        if ($request->filled('equipment')) {
            $query->where('equipment', $request->string('equipment'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search').'%');
        }

        $exercises = $query->paginate(12)->withQueryString();

        return view('exercises.index', compact('exercises'));
    }

    public function show(Exercise $exercise): View
    {
        return view('exercises.show', compact('exercise'));
    }
}
