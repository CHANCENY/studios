<!-- Footer -->
<footer class="inner-footer text-center text-white mt-lg-5">
    <!-- Grid container -->
    <div class="container p-4">
        <!-- Section: Social media -->
        <section class="mb-4">
            <!-- Facebook -->
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
            ><i class="fab fa-facebook-f"></i
                ></a>

            <!-- Twitter -->
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
            ><i class="fab fa-twitter"></i
                ></a>

            <!-- Google -->
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
            ><i class="fab fa-google"></i
                ></a>

            <!-- Instagram -->
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
            ><i class="fab fa-instagram"></i
                ></a>

            <!-- Linkedin -->
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
            ><i class="fab fa-linkedin-in"></i
                ></a>

            <!-- Github -->
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
            ><i class="fab fa-github"></i
                ></a>
        </section>
        <!-- Section: Social media -->

        <!-- Section: Form -->
        <section class="">
            <form action="">
                <!--Grid row-->
                <div class="row d-flex justify-content-center">
                    <!--Grid column-->
                    <div class="col-auto">
                        <p class="pt-2">
                            <strong>Sign up for our newsletter</strong>
                        </p>
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-5 col-12">
                        <!-- Email input -->
                        <div class="form-outline form-white mb-4">
                            <input type="email" id="subcription-email" class="form-control" />
                            <label class="form-label" for="form5Example21">Email address</label>
                        </div>
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-auto">
                        <!-- Submit button -->
                        <button type="submit" id="subcribe-button" class="btn btn-outline-light mb-4">
                            Subscribe
                        </button>
                    </div>
                    <!--Grid column-->
                </div>
                <!--Grid row-->
            </form>
        </section>
        <!-- Section: Form -->

        <!-- Section: Text -->
        <section class="mb-4">
            <p>
                Stream studios is platform where you can watch any tv show and movies available here for free.
            </p>
        </section>
        <!-- Section: Text -->

        <!-- Section: Links -->
        <section class="">
            <!--Grid row-->
            <div class="row">
                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Contact us</h5>

                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#!" class="text-white">mail us</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">phone us</a>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">About us</h5>

                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="terms-condition" class="text-white">Ts && Cs Policy</a>
                        </li>
                        <li>
                            <a href="copyright" class="text-white">Copyright Policy</a>
                        </li>
                        <li>
                            <a href="privacy" class="text-white">Privacy Policy</a>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Services</h5>

                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="movies" class="text-white">Movies</a>
                        </li>
                        <li>
                            <a href="tv-shows" class="text-white">Tv Shows</a>
                        </li>
                        <li>
                            <a href="request" class="text-white">Request</a>
                        </li><?php if(!empty(\GlobalsFunctions\Globals::user())): ?>
                        <li>
                            <a href="stream-ccpanel" class="text-white">DashBoard</a>
                        </li><?php endif; ?>
                        <li>
                            <a href="#!" class="text-white">API</a>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Source code</h5>

                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="https://fasts.tech" class="text-white">Builder</a>
                        </li>
                        <li>
                            <a href="https://quickapistorage.com" class="text-white">Data source</a>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->
            </div>
            <!--Grid row-->
        </section>
        <!-- Section: Links -->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2023 Copyright:
        <a class="text-white" href="https://streamstudios.online">Streamstudios.online</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->



<script src="assets/my-styles/js/main.js"></script>
<script src="assets/my-styles/js/searching.js"></script>
<script src="assets/my-styles/js/subscription.js"></script>
</body>
</html>