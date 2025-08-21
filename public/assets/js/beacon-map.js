/**
 * Beacon Map Utility Functions
 * Used for displaying beacons on maps across the application
 */

/**
 * Converts a Maidenhead grid locator to latitude/longitude coordinates
 * @param {string} locator - The Maidenhead locator to convert (e.g. JN45ij)
 * @return {object|null} - Object with lat and lng properties or null if invalid
 */
function locatorToLatLng(locator) {
    if (!locator || locator.length < 4) return null;
    
    locator = locator.toUpperCase();
    
    // Basic conversion for 4 or 6 character locator
    let lng = (locator.charCodeAt(0) - 65) * 20 - 180;
    let lat = (locator.charCodeAt(1) - 65) * 10 - 90;
    
    lng += (locator.charCodeAt(2) - 48) * 2;
    lat += (locator.charCodeAt(3) - 48) * 1;
    
    // More precision with 6 character locator
    if (locator.length >= 6) {
        lng += (locator.charCodeAt(4) - 65) * (2/24);
        lat += (locator.charCodeAt(5) - 65) * (1/24);
        
        // Add offset to center of the square
        lng += (2/24) / 2;
        lat += (1/24) / 2;
    } else {
        // Add offset to center of the square
        lng += 1;
        lat += 0.5;
    }
    
    return { lat, lng };
}

/**
 * Initialize a map showing all beacons with different colors per band
 * @param {string} mapElementId - ID of the HTML element to render the map in
 * @param {Array} beacons - Array of beacon objects with locator, callsign, qrg, band and status properties
 * @param {object} bandColors - Object mapping band to color for markers
 */
function initBeaconsMap(mapElementId, beacons, bandColors) {
    // Find map bounds based on all beacons
    let bounds = L.latLngBounds([]);
    let markers = [];
    
    beacons.forEach(beacon => {
        const coordinates = locatorToLatLng(beacon.locator);
        if (coordinates) {
            bounds.extend([coordinates.lat, coordinates.lng]);
            markers.push({
                coordinates,
                beacon
            });
        }
    });
    
    // Create map
    const map = L.map(mapElementId);
    
    // If we have valid bounds, set the view based on them
    if (bounds.isValid()) {
        map.fitBounds(bounds);
    } else {
        // Default view if no valid beacons
        map.setView([45.0, 12.0], 5); // Centered approximately on Italy
    }
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Create layer groups for each band
    const layers = {};
    
    // Add markers to the map
    markers.forEach(({ coordinates, beacon }) => {
        const band = beacon.band || '0';
        const color = bandColors[band] || '#3388ff'; // Default blue if band color not specified
        const isActive = parseInt(beacon.status) === 1;
        const opacity = isActive ? 1.0 : 0.6; // Lower opacity for inactive beacons
        
        // Create icon with the band's color and appropriate opacity
        const icon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color:${color}; opacity:${opacity};" class="marker-pin"></div>`,
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });
        
        // Prepare status text for the popup
        const statusText = isActive ? '' : '<br><em>Inactive beacon</em>';
        
        // Create marker
        const marker = L.marker([coordinates.lat, coordinates.lng], { icon, opacity: opacity })
            .bindPopup(`<strong>${beacon.callsign}</strong><br>${beacon.qrg} MHz<br>Band: ${beacon.band} MHz${statusText}`);
        
        // Create layer group for this band if it doesn't exist
        if (!layers[band]) {
            layers[band] = L.layerGroup().addTo(map);
        }
        
        // Add marker to the layer group
        marker.addTo(layers[band]);
    });
    
    // Create layer control if we have multiple bands
    if (Object.keys(layers).length > 1) {
        const overlays = {};
        for (const band in layers) {
            overlays[`${band} MHz`] = layers[band];
        }
        L.control.layers(null, overlays).addTo(map);
    }
    
    return map;
}