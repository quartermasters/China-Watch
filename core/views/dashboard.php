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
    <div class="tile wide tall" style="min-height: 400px; padding:0; overflow:hidden;">
        <div id="map" style="width:100%; height:100%;"></div>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_KEY ?>&callback=initMap" async
        defer></script>

    <!-- Context Tile -->
    <div class="tile tall">
        <div class="label font-mono text-secondary">ANALYST BRIEFING</div>
        <p style="line-height: 1.6; color: var(--text-muted); margin-top: 1rem;">
            Shipping congestion in Shanghai is creating downstream delays for US West Coast logistics.
            Meanwhile, regulatory volume from the CAC suggests an imminent data security announcement.
        </p>
    </div>

</main>