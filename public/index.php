<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Request;
use App\Core\Router;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\GeneratorController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\Api\StatsController;

$router = new Router();

$router->middleware('auth', function ($request, $next) {
    $middleware = new App\Http\Middleware\AuthMiddleware();
    return $middleware($request, $next);
});

$router->middleware('guest', function ($request, $next) {
    $middleware = new App\Http\Middleware\GuestMiddleware();
    return $middleware($request, $next);
});

$router->middleware('premium', function ($request, $next) {
    $middleware = new App\Http\Middleware\CheckPremium();
    return $middleware($request, $next);
});

$router->get('/', [DashboardController::class, 'index'], ['middleware' => ['auth']]);
$router->get('/dashboard', [DashboardController::class, 'index'], ['middleware' => ['auth']]);

$router->get('/login', [AuthController::class, 'showLogin'], ['middleware' => ['guest']]);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister'], ['middleware' => ['guest']]);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/exercises', [ExerciseController::class, 'index'], ['middleware' => ['auth']]);
$router->get('/exercise', [ExerciseController::class, 'show'], ['middleware' => ['auth']]);

$router->get('/generator', [GeneratorController::class, 'index'], ['middleware' => ['auth', 'premium']]);
$router->post('/generator', [GeneratorController::class, 'generate'], ['middleware' => ['auth', 'premium']]);

$router->get('/routines', [RoutineController::class, 'index'], ['middleware' => ['auth']]);
$router->get('/routines/create', [RoutineController::class, 'create'], ['middleware' => ['auth']]);
$router->post('/routines', [RoutineController::class, 'store'], ['middleware' => ['auth']]);
$router->post('/routines/activate', [RoutineController::class, 'activate'], ['middleware' => ['auth']]);

$router->get('/sessions', [SessionController::class, 'index'], ['middleware' => ['auth']]);
$router->post('/sessions/start', [SessionController::class, 'start'], ['middleware' => ['auth']]);
$router->post('/sessions/complete', [SessionController::class, 'complete'], ['middleware' => ['auth']]);

$router->get('/progress', [ProgressController::class, 'index'], ['middleware' => ['auth']]);
$router->post('/progress', [ProgressController::class, 'store'], ['middleware' => ['auth']]);

$router->get('/admin', [AdminController::class, 'dashboard'], ['middleware' => ['auth']]);
$router->post('/admin/exercises', [AdminController::class, 'storeExercise'], ['middleware' => ['auth']]);

$router->get('/pricing', [PricingController::class, 'index']);

$router->get('/api/weight', [StatsController::class, 'weight'], ['middleware' => ['auth']]);
$router->get('/api/contributions', [StatsController::class, 'contributions'], ['middleware' => ['auth']]);

$request = new Request();
$router->dispatch($request);
