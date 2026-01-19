<?php
/**
 * View: Data Detail
 * Description: Deep dive page for a specific intelligence source.
 */

// Basic Trend Analysis
$trendColor = 'text-gray-400';
$trendIcon = '→';
if ($trend === 'up') {
    $trendColor = 'text-green-500';
    $trendIcon = '↗';
} elseif ($trend === 'down') {
    $trendColor = 'text-red-500';
    $trendIcon = '↘';
}

// Prepare Chart Data
$chartLabels = array_column($signals, 'date');
$chartValues = array_column($signals, 'value');
?>

<div class="grid grid-cols-12 gap-6 h-full p-6 fade-in">

    <!-- HEADER -->
    <div class="col-span-12 flex justify-between items-center mb-4">
        <div>
            <a href="/" class="text-xs text-gray-500 hover:text-white uppercase tracking-wider mb-2 block">← Back to
                Dashboard</a>
            <h1 class="text-4xl font-light tracking-tight text-white">
                <?= htmlspecialchars($source['name']) ?>
                <span class="text-base ml-4 px-2 py-1 bg-gray-800 rounded text-gray-400 align-middle">
                    <?= htmlspecialchars($source['osint_id']) ?>
                </span>
            </h1>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 uppercase">Live Status</div>
            <div class="flex items-center justify-end gap-2 text-green-400">
                <span class="flex w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Running
            </div>
        </div>
    </div>

    <!-- LEFT COLUMN: CHART & STATS -->
    <div class="col-span-12 lg:col-span-8 space-y-6">

        <!-- MAIN CHART TILE -->
        <div class="tile p-6 h-96 relative group">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Historical Trend (30 Days)</h3>

            <?php if (empty($signals)): ?>
                <div class="flex items-center justify-center h-full text-gray-600">
                    No historical data available yet.
                </div>
            <?php else: ?>
                <!-- Simple Chart using Chart.js CDN -->
                <canvas id="mainChart"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('mainChart');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: <?= json_encode($chartLabels) ?>,
                            datasets: [{
                                label: '<?= htmlspecialchars($source['name']) ?>',
                                data: <?= json_encode($chartValues) ?>,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { grid: { color: '#333' } },
                                x: { display: false }
                            }
                        }
                    });
                </script>
            <?php endif; ?>
        </div>

        <!-- ANOMALIES FEED -->
        <div class="tile p-6">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Recent Anomalies</h3>
            <div class="space-y-3">
                <?php if (empty($anomalies)): ?>
                    <p class="text-xs text-gray-600 italic">No anomalies detected in recent scans.</p>
                <?php else: ?>
                    <?php foreach ($anomalies as $a): ?>
                        <div
                            class="border-l-2 border-<?= $a['severity'] === 'critical' ? 'red-500' : ($a['severity'] === 'warning' ? 'yellow-500' : 'blue-500') ?> pl-3 py-1">
                            <div class="text-xs text-gray-400 mb-1">
                                <?= $a['created_at'] ?>
                            </div>
                            <div class="text-sm text-gray-200">
                                <?= htmlspecialchars($a['message']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: SIDEBAR -->
    <div class="col-span-12 lg:col-span-4 space-y-6">

        <!-- AI INSIGHT TILE -->
        <div class="tile p-6 h-64 border border-blue-900/30 bg-blue-900/10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-bold text-blue-400 uppercase tracking-widest">AI Analyst</h3>
                <span class="text-xs bg-blue-900 text-blue-300 px-2 py-1 rounded">Beta</span>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed">
                <span class="animate-pulse">Analyzing...</span><br><br>
                Based on current projections,
                <?= htmlspecialchars($source['name']) ?> is showing a
                <span class="<?= $trendColor ?> font-bold">
                    <?= $trend ?>ward trend
                </span>.
                Volatility remains within expected parameters.
            </p>
        </div>

        <!-- METADATA TILE -->
        <div class="tile p-6">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Source Metadata</h3>
            <ul class="text-xs space-y-3">
                <li class="flex justify-between">
                    <span class="text-gray-500">Frequency</span>
                    <span class="text-gray-300">Daily</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-500">Method</span>
                    <span class="text-gray-300">
                        <?= ucfirst($source['type']) ?> Scraping
                    </span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-500">Last Checked</span>
                    <span class="text-gray-300">
                        <?= $source['last_checked_at'] ?? 'Never' ?>
                    </span>
                </li>
            </ul>
        </div>

        <!-- DATA DOWNLOAD CTA -->
        <div class="tile p-6 opacity-50 cursor-not-allowed text-center">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Export Data</h3>
            <p class="text-xs text-gray-600 mb-3">CSV / JSON access requires Enterprise Plan.</p>
            <button class="px-4 py-2 bg-gray-700 text-gray-400 text-xs rounded hover:bg-gray-600 transition">Download
                .CSV</button>
        </div>

    </div>
</div>