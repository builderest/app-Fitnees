<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'users' => User::count(),
            'premium' => User::where('plan', 'premium')->count(),
            'exercises' => Exercise::count(),
            'programs' => WorkoutProgram::count(),
        ]);
    }

    public function storeExercise(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'name_en' => 'required',
            'description' => 'required',
            'description_en' => 'required',
            'primary_muscle' => 'required',
            'equipment' => 'required',
            'difficulty' => 'required',
            'video_url' => 'nullable|url',
            'thumbnail_url' => 'nullable|url',
        ]);

        Exercise::create(array_merge($data, [
            'slug' => \Str::slug($data['name_en']),
        ]));

        return back()->with('status', 'Ejercicio creado');
    }
}
