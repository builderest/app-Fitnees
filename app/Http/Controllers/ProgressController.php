<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function __construct(private ProgressService $service)
    {
    }

    public function index(): View
    {
        $entries = UserProgress::where('user_id', Auth::id())->orderByDesc('date')->paginate(12);
        $series = $this->service->weightSeries(Auth::user());

        return view('progress.index', compact('entries', 'series'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'weight' => ['nullable', 'numeric'],
            'body_fat' => ['nullable', 'numeric'],
            'chest' => ['nullable', 'integer'],
            'waist' => ['nullable', 'integer'],
            'arms' => ['nullable', 'integer'],
            'legs' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);

        UserProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'date' => $data['date']],
            array_merge($data, ['user_id' => Auth::id()])
        );

        return back()->with('status', 'Progreso actualizado');
    }
}
