<?php
/**
 * View file for displaying beacons per band
 * Shows two tables: confirmed and unconfirmed beacons
 */
?>

<br>

<div class="container">
    <!-- Band Selection Dropdown -->
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0"><?= lang('App.select_band') ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('home/showBand') ?>" method="get" class="d-flex">
                        <select name="band" class="form-select me-2">
                            <?php if (isset($listOfBands) && is_array($listOfBands)): ?>
                                <?php foreach ($listOfBands as $band): ?>
                                    <option value="<?= $band ?>" <?= ($bandName == $band) ? 'selected' : '' ?>>
                                        <?= $band ?> <?= lang('App.mhz') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="144" selected>144 <?= lang('App.mhz') ?></option>
                            <?php endif; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Go</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h2 class="my-4"><?= lang('App.displaying_beacons', [$bandName ?? '144']) ?></h2>
    
    <!-- Confirmed Beacons Section -->
    <div class="card mb-5">
        <div class="card-header bg-success text-white">
            <h2 class="h5 mb-0"><?= lang('App.confirmed_beacons') ?></h2>
        </div>
        
        <div class="card-body">
            <?php if (empty($confirmedBeacons)): ?>
                <div class="alert alert-info"><?= lang('App.no_confirmed_beacons') ?></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th><?= lang('App.callsign') ?></th>
                                <th><?= lang('App.qrg') ?></th>
                                <th><?= lang('App.locator') ?></th>
                                <th><?= lang('App.qth') ?></th>
                                <th><?= lang('App.asl') ?></th>
                                <th><?= lang('App.antenna') ?></th>
                                <th><?= lang('App.mode') ?></th>
                                <th><?= lang('App.power') ?></th>
                                <th><?= lang('App.status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($confirmedBeacons as $beacon): ?>
                                <tr>
                                    <td><strong><a href="<?= site_url('beacons/view/' . $beacon['id']) ?>"><?= esc($beacon['callsign']) ?></a></strong></td>
                                    <td><?= number_format($beacon['qrg'], 3) ?> <?= lang('App.mhz') ?></td>
                                    <td><?= esc($beacon['locator']) ?></td>
                                    <td><?= esc($beacon['qth']) ?></td>
                                    <td><?= $beacon['asl'] ?> <?= lang('App.meters') ?></td>
                                    <td><?= esc($beacon['antenna']) ?></td>
                                    <td><?= esc($beacon['mode']) ?></td>
                                    <td><?= $beacon['power'] ?> <?= lang('App.watts') ?></td>
                                    <td>
                                        <?php if ($beacon['status'] == 1): ?>
                                            <span class="badge bg-success"><?= lang('App.active') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?= lang('App.inactive') ?></span>
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
            <h2 class="h5 mb-0"><?= lang('App.unconfirmed_beacons') ?></h2>
            <small class="text-muted"><?= lang('App.unconfirmed_desc') ?></small>
        </div>
        
        <div class="card-body">
            <?php if (empty($unconfirmedBeacons)): ?>
                <div class="alert alert-info"><?= lang('App.no_unconfirmed_beacons') ?></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th><?= lang('App.callsign') ?></th>
                                <th><?= lang('App.qrg') ?></th>
                                <th><?= lang('App.locator') ?></th>
                                <th><?= lang('App.qth') ?></th>
                                <th><?= lang('App.asl') ?></th>
                                <th><?= lang('App.antenna') ?></th>
                                <th><?= lang('App.mode') ?></th>
                                <th><?= lang('App.power') ?></th>
                                <th><?= lang('App.status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($unconfirmedBeacons as $beacon): ?>
                                <tr>
                                    <td><strong><a href="<?= site_url('beacons/view/' . $beacon['id']) ?>"><?= esc($beacon['callsign']) ?></a></strong></td>
                                    <td><?= number_format($beacon['qrg'], 3) ?> <?= lang('App.mhz') ?></td>
                                    <td><?= esc($beacon['locator']) ?></td>
                                    <td><?= esc($beacon['qth']) ?></td>
                                    <td><?= $beacon['asl'] ?> <?= lang('App.meters') ?></td>
                                    <td><?= esc($beacon['antenna']) ?></td>
                                    <td><?= esc($beacon['mode']) ?></td>
                                    <td><?= $beacon['power'] ?> <?= lang('App.watts') ?></td>
                                    <td>
                                        <?php if ($beacon['status'] == 1): ?>
                                            <span class="badge bg-success"><?= lang('App.active') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?= lang('App.inactive') ?></span>
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
        <a href="<?= base_url() ?>" class="btn btn-primary"><?= lang('App.back_to_home') ?></a>
    </div>
</div>