<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;
use RedPulse\Core\DB;

class ReportController
{
    // List all reports (Archive)
    public function index(): void
    {
        $reports = DB::query("SELECT id, title, slug, summary, published_at, tags FROM reports ORDER BY published_at DESC LIMIT 20");
        $total_results = DB::query("SELECT COUNT(*) as count FROM reports")[0]['count'];

        View::render('reports/index', [
            'reports' => $reports,
            'total_results' => $total_results,
            'page_title' => 'Intelligence Archive // China Watch',
            'meta_description' => 'Access the full archive of AI-generated intelligence reports on China\'s economy, industrial sectors, and geopolitical maneuvers.',
            'canonical_url' => 'https://chinawatch.blog/reports'
        ]);
    }

    // Show single report (Medium-style)
    public function show(string $slug): void
    {
        // Security: Slug is safe for string query usually, but parameterized is best
        $data = DB::query("SELECT * FROM reports WHERE slug = ? LIMIT 1", [$slug]);

        if (empty($data)) {
            http_response_code(404);
            echo "Report Declassified or Not Found."; // Thematic 404
            return;
        }

        $report = $data[0];
        $tags = json_decode($report['tags'] ?? '[]', true);

        // Increment Views (Fire and forget, no need to wait)
        DB::query("UPDATE reports SET views = views + 1 WHERE id = ?", [$report['id']]);

        // Construct SEO Data
        $meta_description = mb_substr(strip_tags($report['summary'] ?? $report['content']), 0, 160) . '...';

        // Fetch Related Reports (Simple Tag Matching)
        $related_reports = [];
        if (!empty($tags)) {
            // Create a LIKE query for the first tag found (simplest robust way without full-text search)
            // Ideally we'd loop through all, but for MVP one tag match is sufficient signal
            $primary_tag = $tags[0];
            $related_reports = DB::query("
                SELECT title, slug, published_at 
                FROM reports 
                WHERE tags LIKE ? 
                AND id != ? 
                ORDER BY published_at DESC 
                LIMIT 3
            ", ['%' . $primary_tag . '%', $report['id']]);
        }

        View::render('reports/show', [
            'report' => $report,
            'page_title' => $report['title'] . ' // China Watch Intel',

            // Critical SEO Data
            'meta_description' => $meta_description,
            'canonical_url' => 'https://chinawatch.blog/reports/' . $report['slug'],

            // Open Graph / Social
            'og_type' => 'article',
            'og_image' => 'https://chinawatch.blog/public/assets/og-default.jpg', // Logic to be improved later with featured images

            // Article Schema Data
            'article_published_time' => $report['published_at'],
            'article_tags' => $tags
        ]);
    }
}
