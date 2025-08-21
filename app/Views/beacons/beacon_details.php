<div class="container my-4">
    <h1><?= esc($beacon['callsign']) ?> Beacon Details</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h4 mb-0">Beacon Information</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Callsign:</strong> <?= esc($beacon['callsign']) ?></p>
                    <p><strong>QRG:</strong> <?= esc($beacon['qrg']) ?> MHz</p>
                    <p><strong>QTH:</strong> <?= esc($beacon['qth'] ?? 'N/A') ?></p>
                    <p><strong>Locator:</strong> <?= esc($beacon['locator']) ?></p>
                    <p><strong>Mode:</strong> <?= esc($beacon['mode'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>QTF:</strong> <?= esc($beacon['qtf'] ?? 'N/A') ?></p>
                    <p><strong>Power:</strong> <?= esc($beacon['power'] ?? 'N/A') ?> W</p>
                    <p><strong>Antenna:</strong> <?= esc($beacon['antenna'] ?? 'N/A') ?></p>
                    <p><strong>Status:</strong> <span class="badge <?= $beacon['status'] ? 'bg-success' : 'bg-danger' ?>"><?= $beacon['status'] ? 'Active' : 'Inactive' ?></span></p>
                    <p><strong>Height ASL:</strong> <?= esc($beacon['asl'] ?? 'N/A') ?> m</p>
                </div>
            </div>
            
            <?php
            // Convert locator to latitude/longitude for map display
            // This is a placeholder - in a real implementation, you would use a proper function
            // to convert Maidenhead locator to lat/long coordinates
            if (!empty($beacon['locator'])): 
                // Assuming we have lat/long data calculated from locator
                // In a real implementation, this would need to be properly calculated
            ?>
            <div class="mt-4">
                <h3 class="h5">Map Location</h3>
                <p>Please note: the map is based on the provided locator (<?= esc($beacon['locator']) ?>), a 6-digits locator produces an approximation up to ~200 meters, then coordinates calculation might introduce additional tolerance.</p>
                <div id="beaconMap" style="height: 300px;"></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Reception Reports</h2>
            <a href="<?= site_url('reports/add/'.$beacon['id']) ?>" class="btn btn-primary btn-sm">Add Report</a>
        </div>
        <div class="card-body">
            <?php if (empty($reports)): ?>
                <div class="alert alert-info">No reports have been submitted for this beacon yet.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reporter</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Antenna</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i', strtotime($report['date'])) ?></td>
                                <td><?= esc($report['callsign']) ?></td>
                                <td><?= esc($report['locator']) ?></td>
                                <td><?= $report['status'] ? '<span class="badge bg-success">Received</span>' : '<span class="badge bg-danger">Not received</span>' ?></td>
                                <td><?= esc($report['antenna']) ?></td>
                                <td><?= esc($report['note'] ?? '') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($beacon['locator'])): ?>
<!-- Add Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<!-- Add Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // This function converts Maidenhead grid locator to lat/long
    // Implementation based on common algorithms
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
    
    // Get coordinates from locator
    const coordinates = locatorToLatLng('<?= $beacon['locator'] ?>');
    
    if (coordinates) {
        // Initialize map
        const map = L.map('beaconMap').setView([coordinates.lat, coordinates.lng], 8);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add beacon marker
        L.marker([coordinates.lat, coordinates.lng])
            .addTo(map)
            .bindPopup('<?= esc($beacon['callsign']) ?> - <?= esc($beacon['qrg']) ?> MHz');
    }
});
</script>
<?php endif; ?>
