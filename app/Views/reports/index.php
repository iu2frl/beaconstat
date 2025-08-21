<div class="container my-4">
    <h1 class="mb-4">Recent Reception Reports</h1>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="h5 mb-0">Recent Reports</h2>
        </div>
        
        <div class="card-body">
            <?php if (empty($reports)): ?>
                <div class="alert alert-info">No reception reports found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Beacon</th>
                                <th>Frequency</th>
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
                                <td>
                                    <a href="<?= site_url('beacons/view/' . $report['beacon_id']) ?>">
                                        <?= esc($report['beacon_callsign']) ?>
                                    </a>
                                </td>
                                <td><?= number_format($report['beacon_qrg'], 3) ?> MHz</td>
                                <td><?= esc($report['callsign']) ?></td>
                                <td><?= esc($report['locator']) ?></td>
                                <td>
                                    <?= $report['status'] ? 
                                        '<span class="badge bg-success">Received</span>' : 
                                        '<span class="badge bg-danger">Not received</span>' 
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
        <a href="<?= site_url() ?>" class="btn btn-primary">Back to Home</a>
    </div>
</div>