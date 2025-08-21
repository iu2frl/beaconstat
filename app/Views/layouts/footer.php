
    </div><!-- Main Content End -->

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>BeaconStat</h5>
                    <p class="small">A database of amateur radio beacons around the world.</p>
                </div>
                <div class="col-md-3">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>" class="text-white-50">Home</a></li>
                        <li><a href="<?= base_url('privacy') ?>" class="text-white-50">Privacy Policy</a></li>
                        <li><a href="https://github.com/iu2frl/beaconstat" class="text-white-50">GitHub</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><a href="mailto:info@beaconstat.com" class="text-white-50">info@beaconstat.com</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center">
                <p class="small mb-0">&copy; <?= date('Y') ?> IU2FRL & IU3GNB. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>