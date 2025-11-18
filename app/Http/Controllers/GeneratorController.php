<?php
namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Services\WorkoutGeneratorService;

class GeneratorController extends Controller
{
    public function index(): void
    {
        $this->view('generator.index');
    }

    public function generate(Request $request): void
    {
        $service = new WorkoutGeneratorService();
        $equipment = $request->post['equipment'] ?? [];
        $muscles = $request->post['muscles'] ?? [];
        $count = (int) ($request->post['count'] ?? 6);
        $result = $service->generate($equipment, $muscles, $count);
        $this->view('generator.index', ['result' => $result]);
    }
}
