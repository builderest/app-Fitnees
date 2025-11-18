<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewareRegistry = [];

    public function get(string $path, array $action, array $options = []): void
    {
        $this->addRoute('GET', $path, $action, $options);
    }

    public function post(string $path, array $action, array $options = []): void
    {
        $this->addRoute('POST', $path, $action, $options);
    }

    public function addRoute(string $method, string $path, array $action, array $options = []): void
    {
        $this->routes[$method][$path] = [
            'action' => $action,
            'middleware' => $options['middleware'] ?? []
        ];
    }

    public function middleware(string $name, callable $handler): void
    {
        $this->middlewareRegistry[$name] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = $request->path();
        $route = $this->routes[$method][$path] ?? null;

        if (!$route) {
            http_response_code(404);
            echo view('errors.404');
            return;
        }

        $controller = new $route['action'][0];
        $methodName = $route['action'][1];

        $handler = function () use ($controller, $methodName, $request) {
            $reflection = new \ReflectionMethod($controller, $methodName);
            if ($reflection->getNumberOfParameters() === 0) {
                return $controller->$methodName();
            }
            return $controller->$methodName($request);
        };

        foreach (array_reverse($route['middleware']) as $middleware) {
            $middlewareHandler = $this->middlewareRegistry[$middleware] ?? null;
            if ($middlewareHandler) {
                $next = $handler;
                $handler = function () use ($middlewareHandler, $request, $next) {
                    return $middlewareHandler($request, $next);
                };
            }
        }

        $handler();
    }
}
