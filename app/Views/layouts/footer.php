</div><!-- Main Content End -->

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= lang('App.beaconstat') ?></h5>
                    <p class="small"><?= lang('App.beaconstat_desc') ?></p>
                </div>
                <div class="col-md-3">
                    <h5><?= lang('App.links') ?></h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>" class="text-white-50"><?= lang('App.home') ?></a></li>
                        <li><a href="<?= base_url('privacy') ?>" class="text-white-50"><?= lang('App.privacy_policy') ?></a></li>
                        <li><a href="https://github.com/iu2frl/beaconstat" class="text-white-50"><?= lang('App.github') ?></a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5><?= lang('App.contact') ?></h5>
                    <ul class="list-unstyled">
                        <li><a href="mailto:info@beaconstat.com" class="text-white-50">info@beaconstat.com</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center">
                <p class="small mb-0"><?= lang('App.copyright', [date('Y')]) ?></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>