<?php
namespace App\Http\Controllers;

use App\Core\AuthManager;
use App\Core\Controller;
use App\Core\Request;
use App\Models\UserProgress;
use App\Services\ProgressService;

class ProgressController extends Controller
{
    public function index(): void
    {
        $user = AuthManager::user();
        $service = new ProgressService();
        $entries = UserProgress::filter(fn ($progress) => $progress->user_id === $user->id);
        $weightSeries = $service->weightSeries($user->id);
        $matrix = $service->contributionMatrix($user->id);
        $this->view('progress.index', compact('entries', 'weightSeries', 'matrix'));
    }

    public function store(Request $request): void
    {
        $user = AuthManager::user();
        $progress = new UserProgress($request->all());
        $progress->user_id = $user->id;
        $progress->save();
        redirect('/progress');
    }
}
