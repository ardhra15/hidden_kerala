let map;
let markerLayer = L.layerGroup();

// 1. Icons
const destinationIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', iconSize: [35, 35], iconAnchor: [17, 35] });
const fuelIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/483/483497.png', iconSize: [32, 32] });
const mechanicIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/1037/1037974.png', iconSize: [32, 32] });
const hospitalIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/504/504244.png', iconSize: [32, 32] });
const hotelIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/2983/2983973.png', iconSize: [32, 32] });
const restoIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/3448/3448609.png', iconSize: [32, 32] });

// 2. Main Function (Must match the name used in PHP)
function showNearbyService(type, lat, lng) {
    console.log("Function triggered for:", type); // Check your console (F12) for this message!
    
    const titles = { 
        'fuel': 'Nearby Petrol Pumps', 
        'hospital': 'Nearby Hospitals', 
        'car_repair': 'Nearby Mechanics', 
        'hospitality': 'Nearby Hotels & Restaurants' 
    };

    const modal = document.getElementById('mapModal');
    if (modal) {
        modal.style.display = 'flex';
    } else {
        console.error("Modal element not found!");
        return;
    }

    document.getElementById('mapTitle').innerText = titles[type] || "Nearby Services";

    // Initialize map if it doesn't exist
    if (!map) {
        map = L.map('map-container').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        markerLayer.addTo(map);
    } else {
        map.setView([lat, lng], 13);
    }

    // Fix for the blank/grey map view
    setTimeout(() => { 
        map.invalidateSize(); 
    }, 300);

    markerLayer.clearLayers();
    L.marker([lat, lng], {icon: destinationIcon}).addTo(markerLayer).bindPopup("<b>Destination</b>").openPopup();

    // Fetch from Overpass API
    let queryFilter = `node["amenity"="${type}"](around:30000,${lat},${lng});`;
    if(type === 'hospitality') {
        queryFilter = `(node["amenity"="restaurant"](around:30000,${lat},${lng});node["tourism"~"hotel|guest_house|resort"](around:30000,${lat},${lng}););`;
    }

    fetch(`https://overpass-api.de/api/interpreter?data=[out:json];${queryFilter}out;`)
        .then(res => res.json())
        .then(data => {
            data.elements.forEach(item => {
                let iconToUse = (type === 'fuel') ? fuelIcon : (type === 'hospital') ? hospitalIcon : (type === 'car_repair') ? mechanicIcon : (item.tags.tourism ? hotelIcon : restoIcon);
                L.marker([item.lat, item.lon], {icon: iconToUse}).addTo(markerLayer)
                    .bindPopup(`<b>${item.tags.name || "Service"}</b>`);
            });
        })
        .catch(err => console.error("API Error:", err));
}

function closeMap() {
    document.getElementById('mapModal').style.display = 'none';
}