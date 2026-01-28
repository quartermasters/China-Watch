<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;
use RedPulse\Core\DB;

class EntitiesController
{
    private const FALLBACK_TOPICS = [
        'Economy' => 150,
        'Technology' => 120,
        'Semiconductors' => 95,
        'Real Estate' => 85,
        'Geopolitics' => 80,
        'Military' => 75,
        'Energy' => 70,
        'Supply Chain' => 65,
        'Belt & Road' => 60,
        'Demographics' => 55,
        'Taiwan' => 50,
        'Electric Vehicles' => 45,
        'Trade War' => 40,
        'PBOC' => 35,
        'Manufacturing' => 30,
        'AI' => 25,
        'Export Controls' => 20,
        'South China Sea' => 15
    ];

    public function index()
    {
        // Search Filter
        $search = $_GET['q'] ?? '';

        // Fetch all tags from actively published reports
        $rows = DB::query("SELECT tags FROM reports WHERE tags IS NOT NULL AND tags != ''");

        $tagCounts = [];
        foreach ($rows as $row) {
            $tags = json_decode($row['tags'], true);
            if (is_array($tags)) {
                foreach ($tags as $t) {
                    $t = trim($t);
                    if ($t !== '') {
                        // Filter by search query if present
                        if ($search && stripos($t, $search) === false) {
                            continue;
                        }
                        $tagCounts[$t] = ($tagCounts[$t] ?? 0) + 1;
                    }
                }
            }
        }

        // Use Fallback if DB is empty and no search is active
        if (empty($tagCounts) && empty($search)) {
            $tagCounts = self::FALLBACK_TOPICS;
        }

        // Sort by frequency (descending)
        arsort($tagCounts);

        View::render('entities', [
            'page_title' => 'Topic Galaxy - China Watch',
            'topics' => $tagCounts,
            'search_query' => $search
        ]);
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
