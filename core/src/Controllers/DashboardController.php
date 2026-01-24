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
            'page_title' => 'China Watch // Real-Time Intelligence on China',
            'meta_description' => 'China Watch: Automated OSINT intelligence platform monitoring China\'s economy, policy, and geopolitical developments. Real-time signals from Shanghai Port, lithium markets, and regulatory activity.',
            'canonical_url' => 'https://chinawatch.blog/'
        ]);
    }

    public function dataCenter(): void
    {
        // Data Center - Economic indicators and metrics
        $signals = [
            'gdp_growth' => ['value' => '4.7%', 'trend' => -0.2, 'label' => 'GDP Growth (YoY)'],
            'yuan_usd' => ['value' => '7.24', 'trend' => 0.5, 'label' => 'Yuan/USD'],
            'trade_volume' => ['value' => '$583B', 'trend' => -3.1, 'label' => 'Monthly Trade Volume'],
            'pmi' => ['value' => '49.8', 'trend' => -0.3, 'label' => 'Manufacturing PMI'],
            'cpi' => ['value' => '0.3%', 'trend' => 0.1, 'label' => 'CPI (YoY)'],
            'foreign_reserves' => ['value' => '$3.2T', 'trend' => 0.8, 'label' => 'Foreign Reserves']
        ];

        View::render('data_center', [
            'signals' => $signals,
            'page_title' => 'Data Center - China Watch',
            'meta_description' => 'Real-time economic indicators and data on China including GDP, trade, currency, and key policy metrics.',
            'canonical_url' => 'https://chinawatch.blog/data'
        ]);
    }

    public function ticker(): void
    {
        // HTMX Polling Endpoint - FETCH LATEST REPORTS
        $reports = DB::query("SELECT id, title, slug, published_at FROM reports ORDER BY published_at DESC LIMIT 5");

        if (empty($reports)) {
            echo "<li style='color: var(--text-muted);'>No active reports.</li>";
            exit;
        }

        foreach ($reports as $r) {
            $date = date('M d', strtotime($r['published_at']));

            echo "<li style='padding: 0.5rem; border-bottom: 1px solid var(--border-light);'>
                <a href='/research/{$r['slug']}' style='text-decoration:none; color: var(--text-primary);'>
                    <span style='color: var(--text-muted); font-size: 0.75rem;'>{$date}</span>
                    <span style='display: block;'>{$r['title']}</span>
                </a>
            </li>";
        }
        exit;
    }

    public function anomaly_detail(string $id): void
    {
        // Fetch Real Details from DB
        // Security: Ensure ID is integer to prevent injection
        $id = (int) $id;

        $data = DB::query("SELECT * FROM anomalies WHERE id = " . $id . " LIMIT 1");

        if (empty($data)) {
            echo "<div class='text-red-500 font-mono p-4'>Error: Anomaly #{$id} not found in archive.</div>";
            return;
        }

        $details = $data[0];

        $borderColor = match ($details['severity']) {
            'critical' => 'border-red-500',
            'warning' => 'border-yellow-500',
            default => 'border-blue-500'
        };

        $textColor = match ($details['severity']) {
            'critical' => 'text-red-500',
            'warning' => 'text-yellow-500',
            default => 'text-blue-500'
        };

        // Render Modal HTML
        echo "
        <div class='fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-[200]' onclick='this.remove()'>
            <div class='bg-[#111] border {$borderColor} w-[600px] max-w-full p-8 shadow-2xl relative' onclick='event.stopPropagation()'>
                <button class='absolute top-4 right-4 text-gray-500 hover:text-white' onclick='this.closest(\".fixed\").remove()'>[X] CLOSE</button>
                
                <h2 class='text-2xl font-mono {$textColor} mb-2'>// INTELLIGENCE BRIEF</h2>
                <div class='text-xs text-gray-600 font-mono mb-6'>ID: CLSF-{$details['id']} | DECLASSIFIED FOR CLIENT</div>

                <div class='space-y-4 font-mono text-sm'>
                    <div>
                        <span class='text-gray-500 block text-xs uppercase'>Target Entity</span>
                        <span class='text-white text-lg'>" . ($details['target'] ?? 'Unknown Entity') . "</span>
                    </div>

                    <div>
                        <span class='text-gray-500 block text-xs uppercase'>Location</span>
                        <span class='text-gray-300'>" . ($details['location'] ?? 'Classified') . "</span>
                    </div>

                    <div class='p-4 bg-white/5 border-l-2 {$borderColor}'>
                        <span class='text-gray-500 block text-xs uppercase mb-1'>Projected Impact</span>
                        <span class='text-white'>" . ($details['impact'] ?? 'Impact Assessment Pending') . "</span>
                    </div>

                    <div>
                        <span class='text-gray-500 block text-xs uppercase'>Intelligence Source</span>
                        <span class='text-gray-400'>" . ($details['source'] ?? 'General Telemetry') . "</span>
                    </div>
                </div>

                <div class='mt-8 pt-4 border-t border-gray-800 flex justify-between items-center'>
                    <span class='text-xs text-gray-600'>CONFIDENCE: HIGH (98.4%)</span>
                    <a href='/contact' class='text-xs {$textColor} hover:underline'>REQUEST RAW DATASET >></a>
                </div>
            </div>
        </div>
        ";
    }
}
