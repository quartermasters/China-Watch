<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\DB;
use RedPulse\Core\View;

class DataController
{
    /**
     * Show the detailed data page for a specific source
     */
    public function show(string $slug): void
    {
        // 1. Fetch Source Logic
        // Support searching by OSINT ID (SCREAMING_SNAKE_CASE) or slug (kebab-case)
        $osint_id = str_replace('-', '_', strtoupper($slug));

        $sources = DB::query("SELECT * FROM sources WHERE osint_id = ? OR id = ?", [$osint_id, $slug]);

        if (empty($sources)) {
            // 404 behavior - for now just redirect home or show error
            header("HTTP/1.0 404 Not Found");
            echo "Data source not found.";
            return;
        }

        $source = $sources[0];

        // 2. Fetch History (Last 30 Days)
        // Groups by day to prevent messy charts if multiple scrapes happen
        $signals = DB::query("
            SELECT DATE(captured_at) as date, AVG(value) as value 
            FROM signals 
            WHERE source_id = ? 
            GROUP BY DATE(captured_at) 
            ORDER BY date ASC 
            LIMIT 30
        ", [$source['id']]);

        // 3. Fetch Recent Anomalies
        $anomalies = DB::query("
            SELECT * FROM anomalies 
            WHERE signal_id = ? 
            ORDER BY created_at DESC 
            LIMIT 10
        ", [$source['id']]);

        // 4. Calculate Trends (Simple Logic)
        $trend = 'neutral';
        if (count($signals) >= 2) {
            $last = end($signals)['value'];
            $prev = prev($signals)['value'];
            if ($last > $prev)
                $trend = 'up';
            if ($last < $prev)
                $trend = 'down';
        }

        // 5. Render View
        View::render('data_detail', [
            'source' => $source,
            'signals' => $signals,
            'anomalies' => $anomalies,
            'trend' => $trend
        ]);
    }
}
