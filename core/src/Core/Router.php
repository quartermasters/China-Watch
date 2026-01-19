<?php
declare(strict_types=1);

namespace RedPulse\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    // Static Page Helper
    public function view(string $path, string $viewName): void
    {
        $this->get($path, [self::class, 'renderStatic']); // Use a generic handler
        $this->routes['STATIC'][$path] = $viewName; // Store view mapping
    }

    public static function renderStatic(): void
    {
        // Simple static renderer
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($uri !== '/' && substr($uri, -1) === '/')
            $uri = substr($uri, 0, -1);

        // This is a hacky way to access the router instance or just global map, 
        // but for this simple framework, we'll just check valid views or pass arguments.
        // Better: The dispatch logic needs to know WHICH view to render.
        // Let's refactor dispatch slightly to support this, OR just add manual routes in index.php for simplicity.
    }

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Simple strict match for now (no regex params for MVC MVP)
        // Stripping trailing slash
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = substr($uri, 0, -1);
        }

        // Dynamic Route: Data Pages (e.g., /data/SHANGHAI_PORT)
        if (preg_match('#^/data/([a-zA-Z0-9-_]+)$#', $uri, $matches)) {
            $controller = new \RedPulse\Controllers\DataController();
            $controller->show($matches[1]);
            return;
        }

        // Dynamic Route: Anomaly Details (e.g., /api/anomaly/ANOM-123)
        if (preg_match('#^/api/anomaly/([a-zA-Z0-9-_]+)$#', $uri, $matches)) {
            $controller = new \RedPulse\Controllers\DashboardController();
            $controller->anomaly_detail($matches[1]);
            return;
        }

        // Dynamic Route: Single Report (e.g., /reports/analysis-123)
        if (preg_match('#^/reports/([a-zA-Z0-9-_]+)$#', $uri, $matches)) {
            $controller = new \RedPulse\Controllers\ReportController();
            $controller->show($matches[1]);
            return;
        }

        // Static Route for Reports Archive
        if ($uri === '/reports') {
            $controller = new \RedPulse\Controllers\ReportController();
            $controller->index();
            return;
        }

        if (isset($this->routes[$method][$uri])) {
            [$class, $function] = $this->routes[$method][$uri];
            $controller = new $class();
            $controller->$function();
        } else {
            http_response_code(404);
            echo "404 - Signal Not Found";
        }
    }
}
