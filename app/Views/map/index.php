<?php
/**
 * Beacon Map page
 * Shows all active beacons on a map with different colors per band
 */
?>

<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0"><?= lang('App.beacon_map') ?></h1>
                </div>
                <div class="card-body">
                    <p><?= lang('App.map_description') ?></p>
                    
                    <!-- Map legend -->
                    <div class="map-legend mb-3">
                        <h5><?= lang('App.legend') ?></h5>
                        <div class="row">
                            <?php foreach($bands as $band): ?>
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="map-legend-item">
                                    <div class="map-legend-color" style="background-color: <?= $bandColors[$band] ?? '#000000' ?>;"></div>
                                    <span><?= $band ?> MHz</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Map container -->
                    <div id="beaconMap" style="height: 600px;"></div>
                    
                    <!-- Statistics -->
                    <div class="mt-3">
                        <p><strong><?= lang('App.total_beacons') ?>:</strong> <?= count($beacons) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<!-- Add Custom Map CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/beacon-map.css') ?>" />

<!-- Add Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
<!-- Add Custom Map JavaScript -->
<script src="<?= base_url('assets/js/beacon-map.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Convert PHP data to JavaScript
    const beacons = <?= json_encode($beacons) ?>;
    const bandColors = <?= json_encode($bandColors) ?>;
    
    // Initialize map with beacons
    initBeaconsMap('beaconMap', beacons, bandColors);
});
</script>