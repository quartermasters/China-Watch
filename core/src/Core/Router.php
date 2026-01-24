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

        // Strip trailing slash
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = substr($uri, 0, -1);
        }

        // ============================================
        // 301 REDIRECTS (Legacy URLs -> New URLs)
        // ============================================

        // /reports -> /research
        if ($uri === '/reports') {
            header('Location: /research', true, 301);
            exit;
        }

        // /reports/{slug} -> /research/{slug}
        if (preg_match('#^/reports/([a-zA-Z0-9-_]+)$#', $uri, $matches)) {
            header('Location: /research/' . $matches[1], true, 301);
            exit;
        }

        // /entities -> /topics
        if ($uri === '/entities') {
            header('Location: /topics', true, 301);
            exit;
        }

        // /entity/{id} -> /topic/{id}
        if (preg_match('#^/entity/([a-zA-Z0-9-]+)$#', $uri, $matches)) {
            header('Location: /topic/' . $matches[1], true, 301);
            exit;
        }

        // /tags -> /topics
        if ($uri === '/tags') {
            header('Location: /topics', true, 301);
            exit;
        }

        // /tag/{slug} -> /topics?q={slug}
        if (preg_match('#^/tag/([a-zA-Z0-9-_%+]+)$#', $uri, $matches)) {
            header('Location: /topics?q=' . $matches[1], true, 301);
            exit;
        }

        // ============================================
        // DYNAMIC ROUTES
        // ============================================

        // /research/{slug} - Single Research Article
        if (preg_match('#^/research/([a-zA-Z0-9-_]+)$#', $uri, $matches)) {
            $controller = new \RedPulse\Controllers\ReportController();
            $controller->show($matches[1]);
            return;
        }

        // /topic/{id} - Topic Detail (by ID or slug)
        if (preg_match('#^/topic/([a-zA-Z0-9-]+)$#', $uri, $matches)) {
            $controller = new \RedPulse\Controllers\EntitiesController();
            $identifier = $matches[1];
            if (ctype_digit($identifier)) {
                $controller->showById((int) $identifier);
            } else {
                $controller->showBySlug($identifier);
            }
            return;
        }

        // /data/{source} - Data Detail Page
        if (preg_match('#^/data/([a-zA-Z0-9-_]+)$#', $uri, $matches)) {
            $controller = new \RedPulse\Controllers\DataController();
            $controller->show($matches[1]);
            return;
        }

        // /api/anomaly/{id} - Anomaly Details
        if (preg_match('#^/api/anomaly/([a-zA-Z0-9-_]+)$#', $uri, $matches)) {
            $controller = new \RedPulse\Controllers\DashboardController();
            $controller->anomaly_detail($matches[1]);
            return;
        }

        // ============================================
        // STATIC ROUTES (from registered routes)
        // ============================================

        if (isset($this->routes[$method][$uri])) {
            [$class, $function] = $this->routes[$method][$uri];
            $controller = new $class();
            $controller->$function();
        } else {
            http_response_code(404);
            echo "404 - Page Not Found";
        }
    }
}
