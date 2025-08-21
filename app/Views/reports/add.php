<div class="container my-4">
    <h1 class="mb-4"><?= lang('App.add_reception_report') ?></h1>
    
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0"><?= lang('App.beacon_information') ?></h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><?= lang('App.callsign') ?>:</strong> <?= esc($beacon['callsign']) ?></p>
                            <p><strong><?= lang('App.qrg') ?>:</strong> <?= esc($beacon['qrg']) ?> <?= lang('App.mhz') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><?= lang('App.locator') ?>:</strong> <?= esc($beacon['locator']) ?></p>
                            <p><strong><?= lang('App.qth') ?>:</strong> <?= esc($beacon['qth'] ?? lang('App.na')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0"><?= lang('App.report_details') ?></h2>
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
                            <label for="callsign" class="form-label"><?= lang('App.your_callsign') ?></label>
                            <input type="text" class="form-control" id="callsign" name="callsign" maxlength="10" required
                                   value="<?= old('callsign') ?>">
                            <div class="form-text"><?= lang('App.enter_callsign') ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="locator" class="form-label"><?= lang('App.your_locator') ?></label>
                            <input type="text" class="form-control" id="locator" name="locator" maxlength="6" required
                                   value="<?= old('locator') ?>">
                            <div class="form-text"><?= lang('App.maidenhead_locator') ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.reception_status') ?></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_1" value="1" 
                                       <?= old('status') == '1' || old('status') === null ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_1">
                                    <?= lang('App.received') ?> <span class="badge bg-success"><?= lang('App.heard') ?></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_0" value="0"
                                       <?= old('status') == '0' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_0">
                                    <?= lang('App.not_received') ?> <span class="badge bg-danger"><?= lang('App.not_heard') ?></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="antenna" class="form-label"><?= lang('App.antenna_used') ?></label>
                            <input type="text" class="form-control" id="antenna" name="antenna" maxlength="15" required
                                   value="<?= old('antenna') ?>">
                            <div class="form-text"><?= lang('App.antenna_desc') ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label"><?= lang('App.additional_notes') ?></label>
                            <textarea class="form-control" id="note" name="note" rows="3" maxlength="100"><?= old('note') ?></textarea>
                            <div class="form-text"><?= lang('App.notes_desc') ?></div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><?= lang('App.submit_report') ?></button>
                            <a href="<?= site_url('beacons/view/' . $beacon['id']) ?>" class="btn btn-outline-secondary"><?= lang('App.cancel') ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>