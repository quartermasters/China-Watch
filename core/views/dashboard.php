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
    <div class="label font-mono text-secondary">SHANGHAI PORT (TEU)</div>
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
    <div class="label font-mono text-secondary">REGULATORY VELOCITY</div>
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
    <div id="map" style="width:100%; height:100%;"></div>
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
<script>
    function initMap() {
        const shanghai = { lat: 31.2304, lng: 121.4737 };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 11,
            center: shanghai,
            disableDefaultUI: true,
            styles: [
                { elementType: "geometry", stylers: [{ color: "#212121" }] },
                { elementType: "labels.icon", stylers: [{ visibility: "off" }] },
                { elementType: "labels.text.fill", stylers: [{ color: "#757575" }] },
                { elementType: "labels.text.stroke", stylers: [{ color: "#212121" }] },
                { featureType: "administrative", elementType: "geometry", stylers: [{ color: "#757575" }] },
                { featureType: "administrative.country", elementType: "labels.text.fill", stylers: [{ color: "#9e9e9e" }] },
                { featureType: "administrative.land_parcel", stylers: [{ visibility: "off" }] },
                { featureType: "administrative.locality", elementType: "labels.text.fill", stylers: [{ color: "#bdbdbd" }] },
                { featureType: "poi", elementType: "labels.text.fill", stylers: [{ color: "#757575" }] },
                { featureType: "poi.park", elementType: "geometry", stylers: [{ color: "#181818" }] },
                { featureType: "poi.park", elementType: "labels.text.fill", stylers: [{ color: "#616161" }] },
                { featureType: "road", elementType: "geometry.fill", stylers: [{ color: "#2c2c2c" }] },
                { featureType: "road", elementType: "labels.text.fill", stylers: [{ color: "#8a8a8a" }] },
                { featureType: "road.arterial", elementType: "geometry", stylers: [{ color: "#373737" }] },
                { featureType: "road.highway", elementType: "geometry", stylers: [{ color: "#3c3c3c" }] },
                { featureType: "road.highway.controlled_access", elementType: "geometry", stylers: [{ color: "#4e4e4e" }] },
                { featureType: "road.local", elementType: "labels.text.fill", stylers: [{ color: "#616161" }] },
                { featureType: "transit", elementType: "labels.text.fill", stylers: [{ color: "#757575" }] },
                { featureType: "water", elementType: "geometry", stylers: [{ color: "#000000" }] },
                { featureType: "water", elementType: "labels.text.fill", stylers: [{ color: "#3d3d3d" }] }
            ]
        });

        new google.maps.Marker({
            position: shanghai,
            map: map,
            title: "Shanghai Port High Activity"
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_KEY ?>&callback=initMap" async defer></script>

<!-- Context Tile -->
<div class="tile tall">
    <div class="label font-mono text-secondary">ANALYST BRIEFING</div>
    <p style="line-height: 1.6; color: var(--text-secondary); margin-top: 1rem; font-size:0.95rem;">
        Shipping congestion in Shanghai is creating downstream delays for US West Coast logistics.
        Meanwhile, regulatory volume from the CAC suggests an imminent data security announcement.
    </p>
</div>

<!-- Chart Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                    borderColor: '#30D158', // Green (Good for buyers, bad for sellers - sticking to signal green)
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
                    data: [120, 125, 130, 135, 140, 142, 145], // Upward trend (Congestion)
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