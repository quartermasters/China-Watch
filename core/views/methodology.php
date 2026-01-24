<?php
$page_title = 'Methodology // China Watch';
?>
<div class="col-span-1 fade-in">

    <div class="tile p-8">
        <h1 class="text-2xl font-mono uppercase tracking-widest text-blue-500 mb-6">// METHODOLOGY</h1>

        <div class="space-y-8">
            <div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--text-primary);">1. Autonomous Ingestion</h3>
                <p style="color: var(--text-secondary);">
                    Our engine runs on a strictly defined "Heartbeat" cycle (every 60 seconds).
                    It connects meaningfully with primary data sources (e.g., Shanghai Shipping Exchange, National
                    Bureau of Statistics).
                </p>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--text-primary);">2. Fail-Safe Verification</h3>
                <p style="color: var(--text-secondary);">
                    Raw data is often noisy or missing. Our system employs a <strong>Simulation Fallback Strategy</strong>.
                    If a live signal is undetectable (due to firewalling or downtime), the engine constructs a
                    high-fidelity projection based on historical trends to maintain continuity.
                </p>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--text-primary);">3. The "Anomaly" Filter</h3>
                <p style="color: var(--text-secondary);">
                    We do not report noise. A signal is only flagged as an "Anomaly" if it deviates > 2σ (standard
                    deviations) from the 30-day moving average, or if specific keywords (e.g., "Sanction", "Lockdown")
                    appear in regulatory feeds.
                </p>
            </div>
        </div>
    </div>

</div>