<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;
use RedPulse\Core\DB;

class EntitiesController
{
    public function index()
    {
        // Search Filter
        $search = $_GET['q'] ?? '';
        $whereClause = '';
        $params = [];

        if (!empty($search)) {
            $whereClause = "WHERE e.name LIKE ?";
            $params[] = "%$search%";
        }

        // Fetch top entities by frequency of appearance in reports
        // We join report_entities to count occurrences
        $sql = "
            SELECT e.*, COUNT(re.report_id) as report_count 
            FROM entities e
            LEFT JOIN report_entities re ON e.id = re.entity_id
            $whereClause
            GROUP BY e.id
            ORDER BY report_count DESC
            LIMIT 100
        ";

        $entities = DB::query($sql, $params);

        // Group by Type for the UI
        $grouped = [
            'ORG' => [],
            'PERSON' => [],
            'GPE' => [],
            'OTHER' => []
        ];

        foreach ($entities as $entity) {
            $type = strtoupper($entity['type']);
            if (isset($grouped[$type])) {
                $grouped[$type][] = $entity;
            } else {
                $grouped['OTHER'][] = $entity;
            }
        }

        View::render('entities', [
            'page_title' => 'Known Entities - China Watch',
            'grouped_entities' => $grouped,
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
