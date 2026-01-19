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

        View::render('reports/index', [
            'reports' => $reports,
            'page_title' => 'China Watch | Intelligence Archive'
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

        // Increment Views (Fire and forget, no need to wait)
        DB::query("UPDATE reports SET views = views + 1 WHERE id = ?", [$report['id']]);

        View::render('reports/show', [
            'report' => $report,
            'page_title' => $report['title'] . ' | China Watch Intelligence'
        ]);
    }
}
