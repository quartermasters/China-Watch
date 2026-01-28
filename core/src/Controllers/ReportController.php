<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;
use RedPulse\Core\DB;

class ReportController
{
    private const ALLOWED_LIMITS = [25, 50, 100, 250, 500];
    private const ALLOWED_SORTS = [
        'newest' => ['published_at', 'DESC'],
        'oldest' => ['published_at', 'ASC'],
        'views'  => ['views', 'DESC'],
        'title_az' => ['title', 'ASC'],
        'title_za' => ['title', 'DESC'],
    ];

    /**
     * Parse and validate common request parameters for reports queries.
     */
    private function parseParams(): array
    {
        // Page
        $page = max(1, (int) ($_GET['page'] ?? 1));

        // Limit
        $requestedLimit = isset($_GET['limit']) ? (int) $_GET['limit'] : 25;
        $limit = in_array($requestedLimit, self::ALLOWED_LIMITS) ? $requestedLimit : 25;

        // Sort
        $sortKey = $_GET['sort'] ?? 'newest';
        $sort = self::ALLOWED_SORTS[$sortKey] ?? self::ALLOWED_SORTS['newest'];

        // Search
        $search = isset($_GET['q']) && trim($_GET['q']) !== '' ? trim($_GET['q']) : null;

        // Tag filter
        $tag = isset($_GET['tag']) && trim($_GET['tag']) !== '' ? trim($_GET['tag']) : null;

        // Date range
        $dateFrom = isset($_GET['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from']) ? $_GET['from'] : null;
        $dateTo = isset($_GET['to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to']) ? $_GET['to'] : null;

        $offset = ($page - 1) * $limit;

        return compact('page', 'limit', 'sort', 'sortKey', 'search', 'tag', 'dateFrom', 'dateTo', 'offset');
    }

    /**
     * Build WHERE clauses and params from parsed parameters.
     */
    private function buildWhereClause(array $params): array
    {
        $conditions = [];
        $bindings = [];

        if ($params['search']) {
            $searchTerm = '%' . $params['search'] . '%';
            $conditions[] = '(title LIKE ? OR summary LIKE ?)';
            $bindings[] = $searchTerm;
            $bindings[] = $searchTerm;
        }

        if ($params['tag']) {
            $conditions[] = 'tags LIKE ?';
            $bindings[] = '%' . $params['tag'] . '%';
        }

        if ($params['dateFrom']) {
            $conditions[] = 'published_at >= ?';
            $bindings[] = $params['dateFrom'] . ' 00:00:00';
        }

        if ($params['dateTo']) {
            $conditions[] = 'published_at <= ?';
            $bindings[] = $params['dateTo'] . ' 23:59:59';
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        return [$where, $bindings];
    }

    /**
     * Fetch all unique tags from the database for filter chips.
     */
    private function getAllTags(): array
    {
        $rows = DB::query("SELECT tags FROM reports WHERE tags IS NOT NULL AND tags != ''");
        $tagMap = [];
        foreach ($rows as $row) {
            $decoded = json_decode($row['tags'], true);
            if (is_array($decoded)) {
                foreach ($decoded as $t) {
                    $t = trim($t);
                    if ($t !== '') {
                        $tagMap[$t] = ($tagMap[$t] ?? 0) + 1;
                    }
                }
            }
        }
        arsort($tagMap);
        return $tagMap;
    }

    // List all reports (Archive)
    public function index(): void
    {
        $params = $this->parseParams();
        [$where, $bindings] = $this->buildWhereClause($params);

        [$sortCol, $sortDir] = $params['sort'];
        $limit = $params['limit'];
        $offset = $params['offset'];

        // Fetch reports
        $reports = DB::query(
            "SELECT id, title, slug, summary, published_at, tags, views FROM reports $where ORDER BY $sortCol $sortDir LIMIT $limit OFFSET $offset",
            $bindings
        );

        // Total count
        $total_results = (int) DB::query("SELECT COUNT(*) as count FROM reports $where", $bindings)[0]['count'];
        $total_pages = (int) ceil($total_results / $limit);

        // Get all tags for filter chips
        $allTags = $this->getAllTags();

        View::render('reports/index', [
            'reports' => $reports,
            'total_results' => $total_results,
            'current_page' => $params['page'],
            'total_pages' => $total_pages,
            'search_query' => $params['search'] ?? '',
            'current_limit' => $limit,
            'current_sort' => $params['sortKey'],
            'current_tag' => $params['tag'] ?? '',
            'date_from' => $params['dateFrom'] ?? '',
            'date_to' => $params['dateTo'] ?? '',
            'all_tags' => $allTags,
            'page_title' => 'Intelligence Archive // China Watch',
            'meta_description' => 'Access the full archive of AI-generated intelligence reports on China\'s economy, industrial sectors, and geopolitical maneuvers.',
            'canonical_url' => 'https://chinawatch.blog/reports'
        ]);
    }

    // JSON API endpoint for AJAX fetching
    public function apiIndex(): void
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache');

        $params = $this->parseParams();
        [$where, $bindings] = $this->buildWhereClause($params);

        [$sortCol, $sortDir] = $params['sort'];
        $limit = $params['limit'];
        $offset = $params['offset'];

        $reports = DB::query(
            "SELECT id, title, slug, summary, published_at, tags, views FROM reports $where ORDER BY $sortCol $sortDir LIMIT $limit OFFSET $offset",
            $bindings
        );

        $total_results = (int) DB::query("SELECT COUNT(*) as count FROM reports $where", $bindings)[0]['count'];
        $total_pages = (int) ceil($total_results / $limit);

        // Decode tags JSON for each report
        foreach ($reports as &$r) {
            $r['tags_array'] = json_decode($r['tags'] ?? '[]', true) ?? [];
        }
        unset($r);

        // Get all tags
        $allTags = $this->getAllTags();

        echo json_encode([
            'reports' => $reports,
            'total' => $total_results,
            'page' => $params['page'],
            'limit' => $limit,
            'total_pages' => $total_pages,
            'sort' => $params['sortKey'],
            'search' => $params['search'] ?? '',
            'tag' => $params['tag'] ?? '',
            'all_tags' => $allTags,
        ], JSON_UNESCAPED_UNICODE);
        exit;
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
