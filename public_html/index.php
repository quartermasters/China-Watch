<?php
// Hostinger Public Entry Point

// 1. Bootstrap
require_once __DIR__ . '/../core/src/bootstrap.php';

use RedPulse\Core\Router;
use RedPulse\Controllers\DashboardController;

// 2. Routing
$router = new Router();

// Dashboard
$router->get('/', [DashboardController::class, 'index']);

// Contact (Pages + Actions)
$router->get('/contact', [\RedPulse\Controllers\ContactController::class, 'index']);
$router->post('/contact', [\RedPulse\Controllers\ContactController::class, 'send']);

// Static Pages
$router->get('/about', [\RedPulse\Controllers\StaticController::class, 'about']);
$router->get('/methodology', [\RedPulse\Controllers\StaticController::class, 'methodology']);
$router->get('/privacy', [\RedPulse\Controllers\StaticController::class, 'privacy']);
$router->get('/terms', [\RedPulse\Controllers\StaticController::class, 'terms']);

// Knowledge Graph (Entities)
$router->get('/entities', [\RedPulse\Controllers\EntitiesController::class, 'index']);
$router->get('/entity/{id}', [\RedPulse\Controllers\EntitiesController::class, 'show']);

// Reports
$router->get('/reports', [\RedPulse\Controllers\ReportController::class, 'index']);
$router->get('/reports/{slug}', [\RedPulse\Controllers\ReportController::class, 'show']);

// SEO (Sitemaps)
$router->get('/sitemap.xml', [\RedPulse\Controllers\SitemapController::class, 'index']);
$router->get('/sitemap-news.xml', [\RedPulse\Controllers\SitemapController::class, 'news']);

// HTMX Endpoints
$router->get('/api/ticker', [DashboardController::class, 'ticker']);
$router->post('/api/chat', [\RedPulse\Controllers\ChatController::class, 'ask']);

// 3. Dispatch
$router->dispatch();
