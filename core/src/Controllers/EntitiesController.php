<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;
use RedPulse\Core\DB;

class EntitiesController
{
    private const FALLBACK_TOPICS = [
        'China' => 116,
        'Semiconductors' => 32,
        'Tariffs' => 30,
        'Export Controls' => 28,
        'Real Estate' => 26,
        'Macroeconomy' => 24,
        'United States' => 22,
        'Taiwan' => 20,
        'Supply Chains' => 17,
        'PLA' => 17,
        'Geopolitics' => 16,
        'Policy' => 16,
        'Technology' => 14,
        'Trade War' => 12,
        'Electric Vehicles' => 10,
        'Belt & Road' => 8,
        'PBOC' => 6,
        'Demographics' => 5
    ];

    /**
     * Fetch tag counts from reports table with error handling.
     */
    private function getTagCounts(?string $search = null): array
    {
        $tagCounts = [];

        try {
            $rows = DB::query("SELECT tags FROM reports WHERE tags IS NOT NULL AND tags != ''");

            if ($rows && is_array($rows)) {
                foreach ($rows as $row) {
                    $tags = json_decode($row['tags'] ?? '[]', true);
                    if (is_array($tags)) {
                        foreach ($tags as $t) {
                            $t = trim($t);
                            if ($t === '') continue;

                            // Filter by search if provided
                            if ($search && stripos($t, $search) === false) {
                                continue;
                            }

                            $tagCounts[$t] = ($tagCounts[$t] ?? 0) + 1;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error but continue with fallback
            error_log('EntitiesController: Failed to fetch tags - ' . $e->getMessage());
        }

        // Use fallback if no tags found and no search filter
        if (empty($tagCounts) && empty($search)) {
            $tagCounts = self::FALLBACK_TOPICS;
        }

        // Sort by frequency (descending)
        arsort($tagCounts);

        return $tagCounts;
    }

    public function index()
    {
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $tagCounts = $this->getTagCounts($search ?: null);

        // Calculate stats
        $totalTopics = count($tagCounts);
        $totalReferences = array_sum($tagCounts);
        $topTopic = $totalTopics > 0 ? array_key_first($tagCounts) : 'N/A';

        View::render('entities', [
            'page_title' => 'Topic Explorer // China Watch',
            'meta_description' => 'Explore analysis topics and research categories covering China\'s economy, technology, geopolitics, and policy.',
            'topics' => $tagCounts,
            'search_query' => $search,
            'total_topics' => $totalTopics,
            'total_references' => $totalReferences,
            'top_topic' => $topTopic
        ]);
    }

    /**
     * JSON API endpoint for dynamic topic loading.
     */
    public function apiIndex(): void
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache');

        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $tagCounts = $this->getTagCounts($search ?: null);

        $totalTopics = count($tagCounts);
        $totalReferences = array_sum($tagCounts);
        $topTopic = $totalTopics > 0 ? array_key_first($tagCounts) : 'N/A';

        echo json_encode([
            'topics' => $tagCounts,
            'total_topics' => $totalTopics,
            'total_references' => $totalReferences,
            'top_topic' => $topTopic,
            'search' => $search
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function show(int $id)
    {
        // 1. Fetch Entity Info
        $entity = DB::query("SELECT * FROM entities WHERE id = ?", [$id]);

        if (empty($entity)) {
            header("HTTP/1.0 404 Not Found");
            echo "Entity not found.";
            return;
        }
        $entity = $entity[0];

        // 2. Fetch Related Reports
        $sql = "
            SELECT r.*
            FROM reports r
            JOIN report_entities re ON r.id = re.report_id
            WHERE re.entity_id = ?
            ORDER BY r.published_at DESC
        ";
        $reports = DB::query($sql, [$id]);

        View::render('entity_detail', [
            'page_title' => $entity['name'] . ' - Dossier',
            'entity' => $entity,
            'reports' => $reports
        ]);
    }
}
