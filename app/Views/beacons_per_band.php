<?php
/**
 * View file for displaying beacons per band
 * Shows two tables: confirmed and unconfirmed beacons
 */
?>

<div class="container">
    <h1 class="my-4">Beacons on <?= $bandName ?? '144' ?> MHz band</h1>
    
    <!-- Band Selection Dropdown -->
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">Select Band</h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('home/showBand') ?>" method="get" class="d-flex">
                        <select name="band" class="form-select me-2">
                            <?php if (isset($listOfBands) && is_array($listOfBands)): ?>
                                <?php foreach ($listOfBands as $band): ?>
                                    <option value="<?= $band ?>" <?= ($bandName == $band) ? 'selected' : '' ?>>
                                        <?= $band ?> MHz
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="144" selected>144 MHz</option>
                            <?php endif; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Go</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirmed Beacons Section -->
    <div class="card mb-5">
        <div class="card-header bg-success text-white">
            <h2 class="h5 mb-0">Confirmed Beacons</h2>
        </div>
        
        <div class="card-body">
            <?php if (empty($confirmedBeacons)): ?>
                <div class="alert alert-info">No confirmed beacons found for this band.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Callsign</th>
                                <th>QRG</th>
                                <th>Locator</th>
                                <th>QTH</th>
                                <th>ASL</th>
                                <th>Antenna</th>
                                <th>Mode</th>
                                <th>Power</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($confirmedBeacons as $beacon): ?>
                                <tr>
                                    <td><strong><a href="<?= site_url('beacons/view/' . $beacon['id']) ?>"><?= esc($beacon['callsign']) ?></a></strong></td>
                                    <td><?= number_format($beacon['qrg'], 3) ?> MHz</td>
                                    <td><?= esc($beacon['locator']) ?></td>
                                    <td><?= esc($beacon['qth']) ?></td>
                                    <td><?= $beacon['asl'] ?> m</td>
                                    <td><?= esc($beacon['antenna']) ?></td>
                                    <td><?= esc($beacon['mode']) ?></td>
                                    <td><?= $beacon['power'] ?> W</td>
                                    <td>
                                        <?php if ($beacon['status'] == 1): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Unconfirmed Beacons Section -->
    <div class="card mb-4">
        <div class="card-header bg-warning">
            <h2 class="h5 mb-0">Unconfirmed Beacons</h2>
            <small class="text-muted">These beacons have been reported but not yet confirmed</small>
        </div>
        
        <div class="card-body">
            <?php if (empty($unconfirmedBeacons)): ?>
                <div class="alert alert-info">No unconfirmed beacons found for this band.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Callsign</th>
                                <th>QRG</th>
                                <th>Locator</th>
                                <th>QTH</th>
                                <th>ASL</th>
                                <th>Antenna</th>
                                <th>Mode</th>
                                <th>Power</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($unconfirmedBeacons as $beacon): ?>
                                <tr>
                                    <td><strong><a href="<?= site_url('beacons/view/' . $beacon['id']) ?>"><?= esc($beacon['callsign']) ?></a></strong></td>
                                    <td><?= number_format($beacon['qrg'], 3) ?> MHz</td>
                                    <td><?= esc($beacon['locator']) ?></td>
                                    <td><?= esc($beacon['qth']) ?></td>
                                    <td><?= $beacon['asl'] ?> m</td>
                                    <td><?= esc($beacon['antenna']) ?></td>
                                    <td><?= esc($beacon['mode']) ?></td>
                                    <td><?= $beacon['power'] ?> W</td>
                                    <td>
                                        <?php if ($beacon['status'] == 1): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="text-center mb-4">
        <a href="<?= base_url() ?>" class="btn btn-primary">Back to Home</a>
    </div>
</div>