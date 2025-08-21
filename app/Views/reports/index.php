<div class="container my-4">
    <h1 class="mb-4"><?= lang('App.recent_reports') ?></h1>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="h5 mb-0"><?= lang('App.recent_reports') ?></h2>
        </div>
        
        <div class="card-body">
            <?php if (empty($reports)): ?>
                <div class="alert alert-info"><?= lang('App.no_reception_reports') ?></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><?= lang('App.date') ?></th>
                                <th><?= lang('App.beacon') ?></th>
                                <th><?= lang('App.frequency') ?></th>
                                <th><?= lang('App.reporter') ?></th>
                                <th><?= lang('App.location') ?></th>
                                <th><?= lang('App.status') ?></th>
                                <th><?= lang('App.antenna') ?></th>
                                <th><?= lang('App.note') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i', strtotime($report['date'])) ?></td>
                                <td>
                                    <a href="<?= site_url('beacons/view/' . $report['beacon_id']) ?>">
                                        <?= esc($report['beacon_callsign']) ?>
                                    </a>
                                </td>
                                <td><?= number_format($report['beacon_qrg'], 3) ?> <?= lang('App.mhz') ?></td>
                                <td><?= esc($report['callsign']) ?></td>
                                <td><?= esc($report['locator']) ?></td>
                                <td>
                                    <?= $report['status'] ? 
                                        '<span class="badge bg-success">'.lang('App.received').'</span>' : 
                                        '<span class="badge bg-danger">'.lang('App.not_received').'</span>' 
                                    ?>
                                </td>
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
    
    <div class="mt-4">
        <a href="<?= site_url() ?>" class="btn btn-primary"><?= lang('App.back_to_home') ?></a>
    </div>
</div>