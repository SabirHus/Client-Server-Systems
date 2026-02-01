class EcoMapManager {
    constructor(mapElementId = "ecoMap") {
        // Get the DOM element where the map should be displayed
        this.mapElement = document.getElementById(mapElementId);

        // Create a map to hold markers keyed by facility ID
        this.markerMap = new Map();

        // Exit early if the map element is not found
        if (!this.mapElement) return;

        // Initialize the map and load components
        this.initMap();         // Set up the base map
        this.loadTileLayer();   // Add OpenStreetMap tile layer
        this.centerOnUser();
        this.loadMarkers();     // Fetch and place facility markers
    }

    initMap() {
        // Initialize the Leaflet map centered over Manchester
        this.map = L.map(this.mapElement).setView([53.48, -2.24], 12);
    }

    loadTileLayer() {
        // Load OpenStreetMap tiles and add attribution
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(this.map);
    }

    centerOnUser() {
        // Check if the browser supports geolocation
        if (!navigator.geolocation) return;

        // Attempt to get the user's location
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const userLatLng = [pos.coords.latitude, pos.coords.longitude];

                // Center the map on user's location
                this.map.setView(userLatLng, 14);

                // Add a marker to show the user's location
                L.marker(userLatLng, {
                    icon: L.icon({
                        iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    })
                }).addTo(this.map).bindPopup("You are here").openPopup();
            },
            (err) => {
                // Handle cases where location permission is denied
                console.warn("User denied geolocation", err);
            }
        );
    }

    async loadMarkers() {
        try {
            // Fetch all facility data as JSON from the server
            const res = await fetch("mapController.php");
            const facilities = await res.json();

            // Loop through each facility and add a marker
            facilities.forEach(f => {
                const marker = L.marker([f.lat, f.lng]).addTo(this.map)
                    .bindPopup(`
                        <strong>${f.title}</strong><br>
                        ${f.description}<br>
                        ${f.town}<br>
                        <em>Status: ${f.status}</em>
                    `);

                // Store the marker with its ID for future reference
                this.markerMap.set(f.id, marker);
            });
        } catch (err) {
            // Log an error if markers fail to load
            console.error("Failed to load map markers:", err);
        }
    }
}

// Wait for the DOM to finish loading before running map logic
document.addEventListener("DOMContentLoaded", () => {
    // Create an instance of the map manager
    const ecoMap = new EcoMapManager();
    window.ecoMap = ecoMap; // Expose for access in other scripts

    // Add click event to each row that should focus the map
    document.querySelectorAll(".map-focus-row").forEach(row => {
        row.addEventListener("click", (e) => {
            // Don't trigger scroll if clicking inside a select or button
            if (e.target.closest('select') || e.target.closest('button')) return;

            const id = row.dataset.id;
            const marker = ecoMap.markerMap.get(parseInt(id));

            if (marker) {
                // Zoom into the clicked facility marker
                ecoMap.map.setView(marker.getLatLng(), 16);
                marker.openPopup();

                // Scroll the page to the map view smoothly
                document.getElementById("ecoMap").scrollIntoView({ behavior: "smooth", block: "center" });
            }
        });
    });
});
