<div class="container my-4">
    <h1 class="mb-4">Add Reception Report</h1>
    
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Beacon Information</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Callsign:</strong> <?= esc($beacon['callsign']) ?></p>
                            <p><strong>QRG:</strong> <?= esc($beacon['qrg']) ?> MHz</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Locator:</strong> <?= esc($beacon['locator']) ?></p>
                            <p><strong>QTH:</strong> <?= esc($beacon['qth'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Report Details</h2>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('reports/add/' . $beacon['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Display validation errors if any -->
                        <?php if (session()->has('validation')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session('validation') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="callsign" class="form-label">Your Callsign</label>
                            <input type="text" class="form-control" id="callsign" name="callsign" maxlength="10" required
                                   value="<?= old('callsign') ?>">
                            <div class="form-text">Enter your amateur radio callsign</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="locator" class="form-label">Your Locator</label>
                            <input type="text" class="form-control" id="locator" name="locator" maxlength="6" required
                                   value="<?= old('locator') ?>">
                            <div class="form-text">Maidenhead grid locator (e.g., JO21qf)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Reception Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_1" value="1" 
                                       <?= old('status') == '1' || old('status') === null ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_1">
                                    Received <span class="badge bg-success">Heard</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_0" value="0"
                                       <?= old('status') == '0' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_0">
                                    Not Received <span class="badge bg-danger">Not Heard</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="antenna" class="form-label">Antenna Used</label>
                            <input type="text" class="form-control" id="antenna" name="antenna" maxlength="15" required
                                   value="<?= old('antenna') ?>">
                            <div class="form-text">Type of antenna used (e.g., 9el Yagi)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="note" name="note" rows="3" maxlength="100"><?= old('note') ?></textarea>
                            <div class="form-text">Optional additional information (max 100 characters)</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit Report</button>
                            <a href="<?= site_url('beacons/view/' . $beacon['id']) ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>