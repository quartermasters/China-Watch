<?php
// Hostinger Public Entry Point
// China Watch - Independent Research Institute

// 1. Bootstrap
require_once __DIR__ . '/../core/src/bootstrap.php';

use RedPulse\Core\Router;
use RedPulse\Controllers\DashboardController;

// 2. Routing
$router = new Router();

// ============================================
// THINK TANK NAVIGATION STRUCTURE
// RESEARCH | TOPICS | DATA | ABOUT
// ============================================

// Homepage
$router->get('/', [DashboardController::class, 'index']);

// RESEARCH (Publications)
$router->get('/research', [\RedPulse\Controllers\ReportController::class, 'index']);
// Note: /research/{slug} handled in Router::dispatch()

// TOPICS (Issue Areas)
$router->get('/topics', [\RedPulse\Controllers\EntitiesController::class, 'index']);
// Note: /topic/{id} handled in Router::dispatch()

// DATA CENTER
$router->get('/data', [DashboardController::class, 'dataCenter']);

// ABOUT Section
$router->get('/about', [\RedPulse\Controllers\StaticController::class, 'about']);
$router->get('/about/methodology', [\RedPulse\Controllers\StaticController::class, 'methodology']);
$router->get('/about/contact', [\RedPulse\Controllers\ContactController::class, 'index']);
$router->post('/about/contact', [\RedPulse\Controllers\ContactController::class, 'send']);

// Standalone URLs for footer links
$router->get('/methodology', [\RedPulse\Controllers\StaticController::class, 'methodology']);
$router->get('/privacy', [\RedPulse\Controllers\StaticController::class, 'privacy']);
$router->get('/terms', [\RedPulse\Controllers\StaticController::class, 'terms']);
$router->get('/contact', [\RedPulse\Controllers\ContactController::class, 'index']);
$router->post('/contact', [\RedPulse\Controllers\ContactController::class, 'send']);

// ============================================
// LEGACY URL REDIRECTS
// Handled in Router::dispatch() for 301 redirects:
// /reports -> /research
// /reports/{slug} -> /research/{slug}
// /entities -> /topics
// /entity/{id} -> /topic/{id}
// /tags -> /topics
// /tag/{slug} -> /topics?q={slug}
// ============================================

// ============================================
// SEO (Sitemaps)
// ============================================
$router->get('/sitemapindex.xml', [\RedPulse\Controllers\SitemapController::class, 'sitemapIndex']);
$router->get('/sitemap.xml', [\RedPulse\Controllers\SitemapController::class, 'index']);
$router->get('/sitemap-research.xml', [\RedPulse\Controllers\SitemapController::class, 'reports']);
$router->get('/sitemap-topics.xml', [\RedPulse\Controllers\SitemapController::class, 'entities']);
$router->get('/sitemap-news.xml', [\RedPulse\Controllers\SitemapController::class, 'news']);
// Legacy sitemap URLs
$router->get('/sitemap-reports.xml', [\RedPulse\Controllers\SitemapController::class, 'reports']);
$router->get('/sitemap-entities.xml', [\RedPulse\Controllers\SitemapController::class, 'entities']);
$router->get('/sitemap-tags.xml', [\RedPulse\Controllers\SitemapController::class, 'tags']);

// ============================================
// Auth Routes
// ============================================
$router->get('/auth/login', [\RedPulse\Controllers\AuthController::class, 'login']);
$router->get('/auth/google', [\RedPulse\Controllers\AuthController::class, 'google']);
$router->get('/auth/google/callback', [\RedPulse\Controllers\AuthController::class, 'cbGoogle']);
$router->get('/auth/logout', [\RedPulse\Controllers\AuthController::class, 'logout']);

// ============================================
// API Endpoints
// ============================================
$router->get('/api/ticker', [DashboardController::class, 'ticker']);
$router->post('/api/chat', [\RedPulse\Controllers\ChatController::class, 'ask']);

// 3. Dispatch
$router->dispatch();
