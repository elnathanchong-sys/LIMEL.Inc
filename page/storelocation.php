<?php
include '../base.php';
$pageTitle = 'Store Location';
include '../header.php';
?>

<link rel="stylesheet" href="/app.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="store-location-wrapper">
    <h1 style="color: white; margin-bottom: 10px;">Our Store Location</h1>
    <p style="color: #aaa; margin-bottom: 30px;">Visit us in person or use the map to get directions.</p>

    <div class="store-grid">

        <!-- LEFT: MAP -->
        <div class="map-container">
            <div id="map"></div>
        </div>

        <!-- RIGHT: STORE INFO -->
        <div class="store-info-card">
            <div class="store-info-header">

                <h2>LIMEL Inc.</h2>
            </div>

            <div class="store-detail">

                <div>
                    <div class="detail-label">Address</div>
                    <div class="detail-value">No. 1, Jalan Sulaman, 88450 Kota Kinabalu, Sabah, Malaysia</div>
                </div>
            </div>

            <div class="store-detail">

                <div>
                    <div class="detail-label">Opening Hours</div>
                    <div class="detail-value">
                        Mon – Fri: 10:00 AM – 8:00 PM<br>
                        Sat – Sun: 11:00 AM – 6:00 PM<br>
                        <span style="color: #e74c3c;">Public Holidays: Closed</span>
                    </div>
                </div>
            </div>

            <div class="store-detail">

                <div>
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">+60 3-1234 5678</div>
                </div>
            </div>

            <div class="store-detail">

                <div>
                    <div class="detail-label">Email</div>
                    <div class="detail-value">support@limel.com</div>
                </div>
            </div>

            <a href="https://www.google.com/maps/dir/?api=1&destination=5.9804,116.0735" 
               target="_blank" 
               class="directions-btn">
                Get Directions
            </a>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Replace your entire script with this
const map = L.map('map', {
    center: [5.9804, 116.0735],
    zoom: 16
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19
}).addTo(map);

L.marker([5.9804, 116.0735]).addTo(map)
    .bindPopup('<strong>LIMEL Inc.</strong><br>Kota Kinabalu, Sabah')
    .openPopup();

// Force map to recalculate size after load
setTimeout(function() {
    map.invalidateSize();
}, 100);
</script>

<?php include '../footer.php'; ?>

