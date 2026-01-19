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

// HTMX Endpoints
$router->get('/api/ticker', [DashboardController::class, 'ticker']);
$router->post('/api/chat', [\RedPulse\Controllers\ChatController::class, 'ask']);

// 3. Dispatch
$router->dispatch();
