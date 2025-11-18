<?php

namespace App\Http\Controllers;

use App\Services\WorkoutGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GeneratorController extends Controller
{
    public function __construct(private WorkoutGeneratorService $generator)
    {
    }

    public function index(): View
    {
        return view('generator.index');
    }

    public function generate(Request $request): View
    {
        $data = $request->validate([
            'equipment' => ['required', 'array'],
            'muscles' => ['required', 'array'],
            'count' => ['required', 'integer', 'min:3', 'max:12'],
        ]);

        $result = $this->generator->generate($data['equipment'], $data['muscles'], $data['count']);

        return view('generator.index', [
            'generated' => $result,
        ]);
    }
}
