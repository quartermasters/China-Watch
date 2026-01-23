<!-- SEO: Screen Reader Only H1 for Dashboard -->
<h1 class="sr-only">China Watch Intelligence Dashboard - Real-Time China Economic Data</h1>

<!-- Hero Metric 1: Lithium -->
<div class="tile">
    <div class="label font-mono text-secondary">LITHIUM CARBONATE</div>
    <div class="value font-mono" style="font-size: 2rem; margin: 0.5rem 0;">
        <?= number_format($signals['lithium']['value']) ?> <span class="text-secondary"
            style="font-size:1rem">RMB/T</span>
    </div>
    <div class="trend <?= $signals['lithium']['trend'] < 0 ? 'text-green' : 'text-red' ?> font-mono text-sm">
        <?= $signals['lithium']['trend'] ?>% (24h)
    </div>
    <!-- Sparkline Canvas -->
    <div style="height: 60px; margin-top: 1rem; width:100%;">
        <canvas id="chart-lithium"></canvas>
    </div>
</div>

<!-- Hero Metric 2: Port Traffic -->
<div class="tile">
    <h2 class="label font-mono text-secondary" style="font-size:1rem; margin:0; font-weight:normal;">SHANGHAI PORT (TEU)
    </h2>
    <div class="value font-mono" style="font-size: 2rem; margin: 0.5rem 0;">
        <?= number_format($signals['shanghai_port']['value']) ?>
    </div>
    <div class="trend text-amber font-mono text-sm">
        +<?= $signals['shanghai_port']['trend'] ?>% (Congestion Rising)
    </div>
    <!-- Sparkline Canvas -->
    <div style="height: 60px; margin-top: 1rem; width:100%;">
        <canvas id="chart-port"></canvas>
    </div>
</div>

<!-- Hero Metric 3: Regulatory -->
<div class="tile">
    <h2 class="label font-mono text-secondary" style="font-size:1rem; margin:0; font-weight:normal;">REGULATORY VELOCITY
    </h2>
    <div class="value font-mono" style="font-size: 2rem; margin: 0.5rem 0;">
        <?= $signals['regulatory_velocity']['value'] ?> <span class="text-secondary"
            style="font-size:1rem">DOCS/HR</span>
    </div>
    <div class="trend text-red font-mono text-sm">
        HIGH ACTIVITY
    </div>
    <div style="height: 60px; margin-top: 1rem; width:100%; display:flex; align-items:end; gap:4px;">
        <!-- CSS Bar Chart for Regulatory (Simpler than JS) -->
        <div style="height:40%; width:10px; background:var(--signal-red); opacity:0.3"></div>
        <div style="height:60%; width:10px; background:var(--signal-red); opacity:0.5"></div>
        <div style="height:30%; width:10px; background:var(--signal-red); opacity:0.3"></div>
        <div style="height:90%; width:10px; background:var(--signal-red); opacity:0.8"></div>
        <div style="height:100%; width:10px; background:var(--signal-red);"></div>
    </div>
</div>

<!-- Map Container -->
<div class="tile wide tall" style="min-height: 400px; padding:0; overflow:hidden;">
    <div id="map" style="width:100%; height:100%; background:#111;"></div>
</div>

<!-- Ad Slot B: Context Tile -->
<div class="tile ad-unit">
    <!-- Placeholder for Google Ad Unit -->
    <div style="text-align:center; color:var(--text-muted); font-family:var(--font-mono); font-size:0.8rem;">
        [AD SPACE RESERVED]
        <br>
        250x250
    </div>
</div>

<!-- Map Script -->
<!-- Leaflet Map Script (Deferred for Performance) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>

<script>
    // Defer execution until window load to prioritize Paint
    window.addEventListener('load', function () {
        // Initialize Leaflet
        var map = L.map('map', {
            center: [31.2304, 121.4737], // Shanghai
            zoom: 11,
            zoomControl: false, // Keep UI clean
            attributionControl: false
        });

        // CartoDB Dark Matter Tiles (Premium Dark Look)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 20
        }).addTo(map);

        // Add Custom Marker
        var icon = L.divIcon({
            className: 'custom-pin',
            html: '<div style="width:12px; height:12px; background:#0A84FF; border-radius:50%; box-shadow:0 0 15px #0A84FF; border:2px solid white;"></div>',
            iconSize: [12, 12],
            iconAnchor: [6, 6]
        });

        L.marker([31.2304, 121.4737], { icon: icon }).addTo(map)
            .bindPopup('<div style="color:black"><b>Shanghai Port</b><br>High Activity Detected</div>');

        // Force map invalidation to ensure correct render/resize
        setTimeout(() => { map.invalidateSize(); }, 500);
    });
</script>

<!-- Context Tile -->
<div class="tile tall">
    <h2 class="label font-mono text-secondary" style="font-size:1rem; margin:0; font-weight:normal;">ANALYST BRIEFING
    </h2>
    <p style="line-height: 1.6; color: var(--text-secondary); margin-top: 1rem; font-size:0.95rem;">
        Shipping congestion in Shanghai is creating downstream delays for US West Coast logistics.
        Meanwhile, regulatory volume from the CAC suggests an imminent data security announcement.
    </p>
</div>

<!-- Chart Logic -->
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script defer>
    window.addEventListener('load', function () {
        // Common Options
        const sparkOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false } },
            elements: { point: { radius: 0 }, line: { borderJoinStyle: 'round' } }
        };

        // Lithium Chart
        new Chart(document.getElementById('chart-lithium'), {
            type: 'line',
            data: {
                labels: [1, 2, 3, 4, 5, 6, 7],
                datasets: [{
                    data: [102000, 101000, 99500, 99000, 98800, 98500, 98500], // Downward trend
                    borderColor: '#30D158', // Green
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: sparkOptions
        });

        // Port Chart
        new Chart(document.getElementById('chart-port'), {
            type: 'line',
            data: {
                labels: [1, 2, 3, 4, 5, 6, 7],
                datasets: [{
                    data: [120, 125, 130, 135, 140, 142, 145], // Upward trend
                    borderColor: '#FFD60A', // Amber
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: sparkOptions
        });
    });
</script>

</main>