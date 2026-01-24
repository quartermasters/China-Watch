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

// RESEARCH (Publications) - New primary URL
$router->get('/research', [\RedPulse\Controllers\ReportController::class, 'index']);
$router->get('/research/{slug}', [\RedPulse\Controllers\ReportController::class, 'show']);

// TOPICS (Issue Areas) - Replaces Entities
$router->get('/topics', [\RedPulse\Controllers\EntitiesController::class, 'index']);
$router->get('/topic/{id}', [\RedPulse\Controllers\EntitiesController::class, 'show']);

// DATA CENTER - Economic indicators and metrics
$router->get('/data', [DashboardController::class, 'dataCenter']);

// ABOUT Section
$router->get('/about', [\RedPulse\Controllers\StaticController::class, 'about']);
$router->get('/about/methodology', [\RedPulse\Controllers\StaticController::class, 'methodology']);
$router->get('/about/contact', [\RedPulse\Controllers\ContactController::class, 'index']);
$router->post('/about/contact', [\RedPulse\Controllers\ContactController::class, 'send']);

// Keep standalone URLs for footer links
$router->get('/methodology', [\RedPulse\Controllers\StaticController::class, 'methodology']);
$router->get('/privacy', [\RedPulse\Controllers\StaticController::class, 'privacy']);
$router->get('/terms', [\RedPulse\Controllers\StaticController::class, 'terms']);
$router->get('/contact', [\RedPulse\Controllers\ContactController::class, 'index']);
$router->post('/contact', [\RedPulse\Controllers\ContactController::class, 'send']);

// ============================================
// LEGACY URL REDIRECTS (SEO - 301 Redirects)
// Keep old URLs working
// ============================================
$router->get('/reports', function() {
    header('Location: /research', true, 301);
    exit;
});
$router->get('/reports/{slug}', function($slug) {
    header('Location: /research/' . $slug, true, 301);
    exit;
});
$router->get('/entities', function() {
    header('Location: /topics', true, 301);
    exit;
});
$router->get('/entity/{id}', function($id) {
    header('Location: /topic/' . $id, true, 301);
    exit;
});
$router->get('/tags', function() {
    header('Location: /topics', true, 301);
    exit;
});
$router->get('/tag/{slug}', function($slug) {
    header('Location: /topic/' . $slug, true, 301);
    exit;
});

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
// API Endpoints
// ============================================
$router->get('/api/ticker', [DashboardController::class, 'ticker']);
$router->post('/api/chat', [\RedPulse\Controllers\ChatController::class, 'ask']);

// 3. Dispatch
$router->dispatch();
