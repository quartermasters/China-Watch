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
