<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Gateway | Hidden Kerala</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --primary: #2d5a27; /* Kerala Forest Green */
            --secondary: #f4f7f6;
            --accent: #e67e22; /* Sunset Orange */
            --dark: #2c3e50;
        }

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--secondary); margin: 0; padding: 0; }
        
        /* Header Styling */
        .header { background: var(--primary); color: white; padding: 20px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 1px; }
        .back-btn { position: absolute; left: 20px; top: 25px; color: white; text-decoration: none; font-weight: bold; }

        .main-container { max-width: 1100px; margin: 30px auto; padding: 20px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        /* Controls Layout */
        .search-area { display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        
        select { padding: 12px 20px; border-radius: 30px; border: 2px solid #ddd; outline: none; font-size: 16px; transition: 0.3s; width: 250px; }
        select:focus { border-color: var(--primary); }

        .btn-group { display: flex; gap: 10px; }
        .nav-btn { 
            padding: 12px 25px; border: none; border-radius: 30px; cursor: pointer; 
            font-weight: 600; transition: 0.3s transform, 0.3s background; background: #eee; color: var(--dark);
        }
        .nav-btn:hover { transform: translateY(-3px); background: #e0e0e0; }
        .nav-btn.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3); }

        /* Map Styling */
        #map { height: 550px; width: 100%; border-radius: 12px; margin-top: 20px; z-index: 1; }
        
        /* Marker Popup Styling */
        .leaflet-popup-content-wrapper { border-radius: 10px; padding: 5px; }
        .leaflet-popup-content b { color: var(--primary); font-size: 16px; }
    </style>
</head>
<body>

<div class="header">
    <a href="index.php" class="back-btn">← Home</a>
    <h1>Transport Gateway</h1>
</div>

<div class="main-container">
    <div class="search-area">
        <select id="districtSelect">
            <option value="Thiruvananthapuram">Thiruvananthapuram</option>
            <option value="Kollam">Kollam</option>
            <option value="Pathanamthitta">Pathanamthitta</option>
            <option value="Alappuzha">Alappuzha</option>
            <option value="Kottayam">Kottayam</option>
            <option value="Idukki">Idukki</option>
            <option value="Ernakulam">Ernakulam</option>
            <option value="Thrissur">Thrissur</option>
            <option value="Palakkad">Palakkad</option>
            <option value="Malappuram">Malappuram</option>
            <option value="Kozhikode">Kozhikode</option>
            <option value="Wayanad">Wayanad</option>
            <option value="Kannur">Kannur</option>
            <option value="Kasaragod">Kasaragod</option>
        </select>

        <div class="btn-group">
            <button class="nav-btn" onclick="loadTransport('bus', this)">🚌 Buses</button>
            <button class="nav-btn" onclick="loadTransport('auto')">🛺 Autos</button>
            <button class="nav-btn" onclick="loadTransport('train')">🚆 Trains</button>
        </div>
    </div>

    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([10.8505, 76.2711], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    var transportLayer = L.layerGroup().addTo(map);

    function loadTransport(type, btnElement) {
        var district = document.getElementById('districtSelect').value;
        
        // UI: Change active button style
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
        if(btnElement) btnElement.classList.add('active');

        transportLayer.clearLayers();

        fetch(`get_transport.php?district=${district}&type=${type}`)
            .then(res => res.json())
            .then(data => {
                if(data.length === 0) {
                    alert("No " + type + " data found for " + district);
                    return;
                }
                data.forEach(item => {
                    L.marker([item.latitude, item.longitude])
                     .addTo(transportLayer)
                     .bindPopup(`<b>${item.station_name}</b><br>📞 ${item.contact_info}`);
                });
                var group = new L.featureGroup(transportLayer.getLayers());
                map.fitBounds(group.getBounds(), {padding: [50, 50]});
            });
    }
</script>

</body>
</html>