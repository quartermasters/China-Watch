<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;
use RedPulse\Core\DB;

class DashboardController
{

    public function index(): void
    {
        // Fetch 'Hard Signals' (Mock Database Logic for now)
        // In reality, this would query the 'daily_snapshots' or 'signals' table

        // Mock Data for MVP Visualization
        $signals = [
            'lithium' => ['value' => 98500, 'trend' => -1.2, 'status' => 'stable'],
            'shanghai_port' => ['value' => 145000, 'trend' => 4.5, 'status' => 'warning'], // TEU
            'regulatory_velocity' => ['value' => 12, 'trend' => 300, 'status' => 'critical'] // Docs/hr
        ];

        View::render('dashboard', [
            'signals' => $signals,
            'page_title' => 'Red Pulse | Signal Intelligence'
        ]);
    }

    public function ticker(): void
    {
        // HTMX Polling Endpoint
        // Returns just the <li> elements for the ticker

        $anomalies = DB::query("SELECT * FROM anomalies ORDER BY created_at DESC LIMIT 5");

        if (empty($anomalies)) {
            // Fallback mock
            $anomalies = [
                ['message' => 'System Stable. No anomalies detected.', 'severity' => 'info', 'created_at' => date('H:i')]
            ];
        }

        foreach ($anomalies as $a) {
            $color = match ($a['severity']) {
                'critical' => 'text-[var(--signal-red)]',
                'warning' => 'text-[var(--signal-amber)]',
                default => 'text-[var(--signal-blue)]'
            };
            echo "<li class='{$color} font-mono text-sm'>[{$a['created_at']}] {$a['message']}</li>";
        }
        exit;
    }
}
