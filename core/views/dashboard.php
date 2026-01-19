<!-- Main Dashboard Area -->
<main class="bento-grid">

    <!-- Hero Metric 1: Lithium -->
    <div class="tile">
        <div class="label font-mono text-secondary">LITHIUM CARBONATE</div>
        <div class="value font-mono" style="font-size: 2rem; margin: 1rem 0;">
            <?= number_format($signals['lithium']['value']) ?> <span class="text-secondary"
                style="font-size:1rem">RMB/T</span>
        </div>
        <div class="trend <?= $signals['lithium']['trend'] < 0 ? 'text-green' : 'text-red' ?>">
            <?= $signals['lithium']['trend'] ?>% (24h)
        </div>
    </div>

    <!-- Hero Metric 2: Port Traffic -->
    <div class="tile">
        <div class="label font-mono text-secondary">SHANGHAI PORT (TEU)</div>
        <div class="value font-mono" style="font-size: 2rem; margin: 1rem 0;">
            <?= number_format($signals['shanghai_port']['value']) ?>
        </div>
        <div class="trend text-amber">
            +
            <?= $signals['shanghai_port']['trend'] ?>% (Congestion Rising)
        </div>
    </div>

    <!-- Hero Metric 3: Regulatory -->
    <div class="tile">
        <div class="label font-mono text-secondary">REGULATORY VELOCITY</div>
        <div class="value font-mono" style="font-size: 2rem; margin: 1rem 0;">
            <?= $signals['regulatory_velocity']['value'] ?> <span class="text-secondary"
                style="font-size:1rem">DOCS/HR</span>
        </div>
        <div class="trend text-red">
            HIGH ACTIVITY
        </div>
    </div>

    <!-- Map Container -->
    <div class="tile wide tall"
        style="min-height: 400px; display:flex; align-items:center; justify-content:center; background: #000;">
        <div class="placeholder-map font-mono text-secondary">
            [GLOBAL PROXY MAP RENDERER REQUESTED]
            <br>
            <span style="font-size:0.8rem">Google Maps API Integration Pending</span>
        </div>
    </div>

    <!-- Context Tile -->
    <div class="tile tall">
        <div class="label font-mono text-secondary">ANALYST BRIEFING</div>
        <p style="line-height: 1.6; color: var(--text-muted); margin-top: 1rem;">
            Shipping congestion in Shanghai is creating downstream delays for US West Coast logistics.
            Meanwhile, regulatory volume from the CAC suggests an imminent data security announcement.
        </p>
    </div>

</main>