    <footer class="footer_main">
        <div class="cm-container">
            <div class="footer-top">
                <div class="footer_left">
                    <div class="footer_logo_wrap">
                        <a href="{{ asset('/') }}" class="footer_logo">
                            <!-- NL1025:20Sept:2025:Added config path -->

                            <img src="{{ config('global.base_url_frontend') }}frontend/images/logo.png"
                                alt="" />
                        </a>
                        <div class="footer_logo">
                            <img src="{{ config('global.base_url_asset') }}asset/images/kmc_logo.png" alt="">
                        </div>
                    </div>
                    <p>NMF News is a Subsidary of Khetan Media Creation Pvt Ltd</p>
                    <div class="contact_wrap">
                        <div class="contact_block">
                            <div class="ct_left">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="ct_right">
                                <small>Give us a Call</small>
                                <a href="tel:+91-080767 27261">+91-080767 27261</a>
                            </div>
                        </div>
                        <div class="contact_block">
                            <div class="ct_left">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="ct_right">
                                <small>Visit Our Office</small>
                                <p>D-4 1st Floor, Sector 10, Noida, Uttar Pradesh 201301</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer_centre">
                    <div class="footer_col">
                        <h4>Company</h4>
                        <ul class="footer_menu">
                            <li class="footer_item"><a href="{{ asset('/about') }}">About us</a></li>
                            <li class="footer_item"><a href="{{ asset('/privacy') }}">Privacy Policy</a></li>
                            <li class="footer_item"><a href="{{ asset('/disclaimer') }}">Disclaimer</a></li>
                            <li class="footer_item"><a href="{{ asset('/contact') }}">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer_col">
                        <h4>Category</h4>
                        <?php
                        $footer_menus = App\Models\Menu::where('menu_id', 0)->where('status', 1)->where('type_id', '1')->where('category_id', '2')->limit(8)->get();
                        $chunks = $footer_menus->chunk(4);
                        ?>
                        <ul class="footer_menu">
                            @foreach ($chunks as $chunk)
                                <div class="footer_ct">
                                    @foreach ($chunk as $footer_menu)
                                        <li class="footer_item">
                                            <a href="{{ $footer_menu->menu_link }}">{{ $footer_menu->menu_name }}</a>
                                        </li>
                                    @endforeach
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="footer_right">
                    <h5>Download App</h5>
                    <div class="app_btn_wrap">
                        <a href="https://play.google.com/store/apps/details?id=com.kmcliv.nmfnews"
                            class="playstore-button">
                            <svg viewBox="0 0 512 512" class="_icon" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M99.617 8.057a50.191 50.191 0 00-38.815-6.713l230.932 230.933 74.846-74.846L99.617 8.057zM32.139 20.116c-6.441 8.563-10.148 19.077-10.148 30.199v411.358c0 11.123 3.708 21.636 10.148 30.199l235.877-235.877L32.139 20.116zM464.261 212.087l-67.266-37.637-81.544 81.544 81.548 81.548 67.273-37.64c16.117-9.03 25.738-25.442 25.738-43.908s-9.621-34.877-25.749-43.907zM291.733 279.711L60.815 510.629c3.786.891 7.639 1.371 11.492 1.371a50.275 50.275 0 0027.31-8.07l266.965-149.372-74.849-74.847z">
                                </path>
                            </svg>
                            <span class="texts">
                                <span class="text-1">GET IT ON</span>
                                <span class="text-2">Google Play</span>
                            </span>
                        </a>
                        <a href="https://apps.apple.com/us/app/nmf-news/id6745018964" class="playstore-button">
                            <svg viewBox="0 0 512 512" class="_icon" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg" style="margin-right: -7px;">
                                <path
                                    d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z" />
                            </svg>
                            <span class="texts">
                                <span class="text-1">GET IT ON</span>
                                <span class="text-2">App Store</span>
                            </span>
                        </a>
                    </div>

                    @if (session('subscribemessage'))
                        <div class="alert alert-success mt-1">
                            {{ session('subscribemessage') }}
                        </div>
                    @endif



                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="f-row">
                <div class="col-md-6 ps-0">
                    <div class="footer-site-info">Copyright © 2025 KMC PVT. LTD. All Rights Reserved.</div>
                </div>
                <div class="ftcol">
                    <div class="poweredby">
                        <span>Designed & Developed by</span>
                        <!-- NL1025:20Sept:2025:Added config path -->

                        <a href="https://www.abrosys.com/"> <img width="102" height="19"
                                src="{{ config('global.base_url_asset') }}asset/images/abrosys.png"
                                alt="Abrosys Technologies Private Limited"></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>