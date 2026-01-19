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
            'page_title' => 'China Watch | Signal Intelligence'
        ]);
    }

    public function ticker(): void
    {
        // Mock Data with IDs for interaction
        $anomalies = [
            [
                'id' => 'ANOM-2026-884',
                'message' => 'CRITICAL: Unscheduled maintenance at key lithium processing hub.',
                'severity' => 'critical',
                'created_at' => '2026-01-19 20:54:02',
                'details' => [
                    'target' => 'Ganfeng Lithium - Mahong Factory (Unit 4)',
                    'location' => 'Xinyu, Jiangxi Province',
                    'impact' => 'Estimated output reduction of 450 MT/week. Spot prices likely to react +2-4%.',
                    'source' => 'Sentinet Satellite Thermal Imaging & Local Energy Consumption Data'
                ]
            ],
            [
                'id' => 'ANOM-2026-883',
                'message' => 'Shanghai municipal government releases guidance on AI infrastructure subsidies.',
                'severity' => 'info',
                'created_at' => '2026-01-19 19:54:02',
                'details' => [
                    'target' => 'Shanghai Municipal Commission of Economy and Informatization',
                    'location' => 'Shanghai',
                    'impact' => 'Fiscal allocation of 20B RMB for GPU clusters. Bullish for domestic chipmakers.',
                    'source' => 'Official Gov Portal (Crawled)'
                ]
            ],
            [
                'id' => 'ANOM-2026-882',
                'message' => 'PBOC signals potential reserve requirement ratio adjustment.',
                'severity' => 'warning',
                'created_at' => '2026-01-19 19:53:02',
                'details' => [
                    'target' => 'People\'s Bank of China',
                    'location' => 'Beijing',
                    'impact' => 'Liquidity injection imminent. Banking sector volatility expected.',
                    'source' => 'Financial News (State Media)'
                ]
            ]
        ];

        foreach ($anomalies as $a) {
            $color = match ($a['severity']) {
                'critical' => 'text-[var(--signal-red)]',
                'warning' => 'text-[var(--signal-amber)]',
                default => 'text-[var(--signal-blue)]'
            };

            // HTMX: Click to load details into modal
            echo "<li 
                hx-get='/api/anomaly/{$a['id']}' 
                hx-target='#anomaly-modal-container' 
                class='{$color} font-mono text-sm cursor-pointer hover:bg-white/5 p-1 transition-colors'
                style='cursor:pointer;'>
                [{$a['created_at']}] {$a['message']}
            </li>";
        }
        exit;
    }

    public function anomaly_detail(string $id): void
    {
        // In a real app, fetch from DB by ID. 
        // Here we reconstruct the specific mock data based on ID for the demo.

        $details = [];
        if ($id === 'ANOM-2026-884') {
            $details = [
                'id' => 'ANOM-2026-884',
                'title' => 'CRITICAL MAINTENANCE ALERT',
                'target' => 'Ganfeng Lithium - Mahong Factory (Unit 4)',
                'location' => 'Xinyu, Jiangxi Province',
                'impact' => 'Estimated output reduction of 450 MT/week. Spot prices likely to react +2-4%.',
                'source' => 'Sentinet Satellite Thermal Imaging & Local Energy Consumption Data',
                'severity' => 'critical'
            ];
        } elseif ($id === 'ANOM-2026-883') {
            $details = [
                'id' => 'ANOM-2026-883',
                'title' => 'POLICY SHIFT DETECTED',
                'target' => 'Shanghai Municipal Commission of Economy and Informatization',
                'location' => 'Shanghai',
                'impact' => 'Fiscal allocation of 20B RMB for GPU clusters. Bullish for domestic chipmakers.',
                'source' => 'Official Gov Portal (Crawled)',
                'severity' => 'info'
            ];
        } else {
            $details = [
                'id' => $id,
                'title' => 'MONETARY POLICY SIGNAL',
                'target' => 'People\'s Bank of China',
                'location' => 'Beijing',
                'impact' => 'Liquidity injection imminent. Banking sector volatility expected.',
                'source' => 'Financial News (State Media)',
                'severity' => 'warning'
            ];
        }

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
                <div class='text-xs text-gray-600 font-mono mb-6'>ID: {$details['id']} | DECLASSIFIED FOR CLIENT</div>

                <div class='space-y-4 font-mono text-sm'>
                    <div>
                        <span class='text-gray-500 block text-xs uppercase'>Target Entity</span>
                        <span class='text-white text-lg'>{$details['target']}</span>
                    </div>

                    <div>
                        <span class='text-gray-500 block text-xs uppercase'>Location</span>
                        <span class='text-gray-300'>{$details['location']}</span>
                    </div>

                    <div class='p-4 bg-white/5 border-l-2 {$borderColor}'>
                        <span class='text-gray-500 block text-xs uppercase mb-1'>Projected Impact</span>
                        <span class='text-white'>{$details['impact']}</span>
                    </div>

                    <div>
                        <span class='text-gray-500 block text-xs uppercase'>Intelligence Source</span>
                        <span class='text-gray-400'>{$details['source']}</span>
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
