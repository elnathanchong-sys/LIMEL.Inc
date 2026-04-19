<?php
include '../base.php';
$pageTitle = 'Contact Us & Store Location';
include '../header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL - Contact Us</title>
    <link rel="stylesheet" href="/app.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>
<body>


    <main class="contact-main-container">
        <div class="contact-header">
            <h1 style="color: white;">Contact Us</h1>
            <p>We'd love to hear from you. Reach out to us below.</p>
        </div>

        <div class="contact-wrapper">
            <div class="contact-info-panel">
                <h3>Contact Information</h3>

                <div class="con-info">
                    <strong>Business Name</strong>
                    <span>LIMEL</span>
                </div>
                <div class="con-info">
                    <strong>Email</strong>
                    <span>limelinc@gmail.com</span>
                </div>
                <div class="con-info">
                    <strong>Phone</strong>
                    <span>+60 12 345 6722</span>
                </div>
                <div class="con-info">
                    <strong>Business Hours</strong>
                    <span>GMT +8 — Monday to Saturday, 10AM – 6PM</span>
                </div>
                <div class="con-info">
                    <strong>Response Time</strong>
                    <span>Within 24 hours</span>
                </div>
            </div>
        </div>
    </main>


    <hr class="section-divider">


    <div class="store-location-wrapper">
        <h1 style="color: white; margin-bottom: 10px;">Our Store Location</h1>
        <p style="color: #aaa; margin-bottom: 30px;">Visit us in person or use the map to get directions.</p>

        <div class="store-grid">
            <div class="map-container">
                <div id="map"></div>
            </div>

            <div class="store-info-card">
                <div class="store-info-header">
                    <h2>LIMEL Inc.</h2>
                </div>

                <div class="store-detail">
                    <div>
                        <div class="detail-label">Address</div>
                        <div class="detail-value">Jambatan Piko, Sinsuran Kompleks, 88000 Kota Kinabalu, Sabah</div>
                    </div>
                </div>

                <div class="store-detail">
                    <div>
                        <div class="detail-label">Opening Hours</div>
                        <div class="detail-value">
                            Mon – Sat: 10:00 AM – 6:00 PM<br>
                            <span style="color: #e74c3c;">Public Holidays: Closed</span>
                        </div>
                    </div>
                </div>

                <div class="store-detail">
                    <div>
                        <div class="detail-label">Phone</div>
                        <div class="detail-value">+60 12 345 6722</div>
                    </div>
                </div>

                <div class="store-detail">
                    <div>
                        <div class="detail-label">Email</div>
                        <div class="detail-value">limelinc@gmail.com</div>
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

</body>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
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

setTimeout(function() {
    map.invalidateSize();
}, 100);
</script>

<?php include '../footer.php'; ?>
</html>