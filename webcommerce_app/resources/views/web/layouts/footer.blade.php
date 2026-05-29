<footer id="footer" class="footer dark-background">
    <div class="container">
        <div class="row gy-3">
            <div class="col-lg-3 col-md-6 d-flex">
                <i class="bi bi-geo-alt icon"></i>
                <div class="address">
                    <h4>{{ $globalSetting->address }}</h4>
                    <p></p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 d-flex">
                <i class="bi bi-telephone icon"></i>
                <div>
                    <h4>Contact</h4>
                    <p>
                        <strong>Phone:</strong> <span>{{ $globalSetting->whatsapp }}</span><br>
                        <strong>Email:</strong> <span>{{ $globalSetting->email }}</span><br>
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 d-flex">
                <i class="bi bi-clock icon"></i>
                <div>
                    <h4>Opening Hours</h4>
                    <p>
                        <strong>Mon-Sat:</strong> <span>{{ $globalSetting->open_time }} -
                            {{ $globalSetting->close_time }}</span><br>
                        <strong>Sunday</strong>: <span>Closed</span>
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h4>Follow Us</h4>
                <div class="social-links d-flex">
                    {{-- <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a> --}}
                    <a href="{{ $globalSetting->facebook }}" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="{{ $globalSetting->instagram }}" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="{{ $globalSetting->tiktok }}" class="tiktok"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">{{ $globalSetting->store_name }}</strong>
            <span>All Rights Reserved</span>
        </p>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you've purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a
                href="https://themewagon.com">ThemeWagon</a>
        </div>
    </div>

</footer>
