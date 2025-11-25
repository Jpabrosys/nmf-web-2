    
    
    <header class="--header-amp">
        <div class="cm-container">
            <div class="--header-container">
                <div class="--header-left">
                    <a href="/" class="--nmf-logo-amp">
                        {{-- <img src="{{ asset('/frontend/images/logo.png') }}" alt="Logo" class="--logo" /> --}}
                        <img src="https://www.newsnmf.com/frontend/images/logo.png" alt="Logo" class="--logo" />
                    </a>
                </div>
                <div class="--header-right">
                    <div class="--header-right-top">
                        <div class="--hdr-top">
                            <div class="--hdr-t-l">

                                <button class="--toggle-box" on="tap:ampModalMenu.open" id="toggle-btn">
                                    <label class="burger" for="burger">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </label>
                                </button>
                                <div>

                                </div>
                            </div>


                            <small class="Headertag m-0 htag" style="margin-left: 0px;white-space: nowrap;"> <span
                                    class="" style="color: #fff;">जिस पर देश</span><span
                                    class="HeadertagHalf">करता है
                                    भरोसा</span> </small>
                        </div>

                        <amp-lightbox id="ampModalMenu" layout="nodisplay">
                            <div class="modal-content">
                                <div class="modal_top">
                                    <button class="close_btn" on="tap:ampModalMenu.close" tabindex="0" role="button"
                                        aria-label="Close menu">
                                        ✕
                                    </button>
                                    <a href="/" class="modal_logo">
                                        <!-- CONVERSION TO AMP: Img tag replaced with amp-img, width and height are mandatory -->
                                        <amp-img src="https://www.newsnmf.com/frontend/images/logo.png"
                                            alt="NMF News Logo" width="50" height="48" layout="fixed"></amp-img>
                                    </a>
                                    <span class="Headertag" style="margin-left: 0px">
                                        <span style="color: #333;">जिस पर देश</span>
                                        <span class="HeadertagHalf">करता है भरोसा</span>
                                    </span>
                                </div>

                                <?php
                                // Define category-to-icon mapping
                                $categoryIcons = [
                                    'होम' => 'fa-solid fa-house',
                                    'न्यूज' => 'fa-solid fa-newspaper',
                                    'राज्य' => 'fa-solid fa-landmark',
                                    'एक्सक्लूसिव' => 'fa-solid fa-star',
                                    'खेल' => 'fa-solid fa-futbol',
                                    'मनोरंजन' => 'fa-solid fa-film',
                                    'धर्म ज्ञान' => 'fa-solid fa-om',
                                    'टेक्नोलॉजी' => 'fa-solid fa-microchip',
                                    'लाइफस्टाइल' => 'fa-solid fa-heart',
                                    'पॉडकास्ट' => 'fa-solid fa-podcast',
                                    'दुनिया' => 'fa-solid fa-globe',
                                    'विधान सभा चुनाव' => 'fa-solid fa-person-booth',
                                    'क्राइम' => 'fa-solid fa-user-secret',
                                    'वेब स्टोरी' => 'fa-solid fa-photo-film',
                                    'यूटीलिटी' => 'fa-solid fa-wrench',
                                    'करियर' => 'fa-solid fa-briefcase',
                                    'ट्रेंडिंग न्यूज़' => 'fa-solid fa-bolt',
                                    'ब्लॉग' => 'fa-solid fa-pen-nib',
                                ];
                                
                                $toggleMenus = App\Models\Menu::whereRelation('type', 'type', 'Header')
                                    ->whereRelation('category', 'category', 'User')
                                    ->where([['status', '1'], ['menu_id', 0]])
                                    ->whereNotNull('sequence_id')
                                    ->where('sequence_id', '!=', 0)
                                    ->orderBy('sequence_id', 'asc')
                                    ->get();
                                ?>

                                <ul class="modalmenu">
                                    @foreach ($toggleMenus as $menu)
                                        <?php
                                        // Submenu logic remains untouched, only used to determine structure
                                        $subMenus = App\Models\Menu::where('menu_id', $menu->id)->where('status', 1)->where('type_id', 1)->where('category_id', 2)->orderBy('sequence_id', 'asc')->get();
                                        $hasSubMenus = count($subMenus) > 0;
                                        ?>

                                        <li class="modal_item">
                                            @if ($hasSubMenus)
                                                <!-- AMP Accordion for the collapsible section -->
                                                <amp-accordion class="amp-menu-toggle" animate>
                                                    <!-- The <section> defines one accordion item -->
                                                    <section>
                                                        <!-- H4 is the required header element and acts as the toggle button for the content below -->
                                                        <h4 class="amp-menu-header">
                                                            <!-- The icon and menu name remain, but the H4 is NOT a link to prevent conflict with accordion toggle -->
                                                            <i
                                                                class="{{ $categoryIcons[$menu->menu_name] ?? 'fa-solid fa-link' }}"></i>
                                                            {{ $menu->menu_name }}
                                                        </h4>

                                                        <!-- The div is the collapsible content -->
                                                        <div class="amp-menu-submenu-content">
                                                            <!-- Since the H4 is the toggle, we add the main category link inside the dropdown content -->
                                                            <a href="{{ asset($menu->menu_link) }}"
                                                                class="amp-menu-parent-link-in-dropdown">
                                                                {{ $menu->menu_name }}
                                                            </a>

                                                            <ul class="modal_submenu">
                                                                @foreach ($subMenus as $subMenu)
                                                                    <li>
                                                                        <a href="{{ asset($subMenu->menu_link) }}">
                                                                            <!-- Simple unicode circle for maximum AMP compliance and icon consistency -->
                                                                            <span
                                                                                style="font-size: 0.5em; vertical-align: middle; margin-right: 8px;">&#9679;</span>
                                                                            {{ $subMenu->menu_name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </section>
                                                </amp-accordion>
                                            @else
                                                <!-- Simple link if no submenus -->
                                                <a href="{{ asset($menu->menu_link) }}">
                                                    <i
                                                        class=" {{ $categoryIcons[$menu->menu_name] ?? 'fa-solid fa-link' }}"></i>
                                                    {{ $menu->menu_name }}
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </amp-lightbox>
                    </div>
                </div>
            </div>
    </header>
        <nav class="main-navigation-mob" id="mainMenu">
        <div class="menu-container">
            <ul class="menu-list">
                @php
                    $baseUrl = config('global.base_url'); // your configured base URL

                @endphp

                @foreach ($menus as $item)
                    @php
                        if (substr($item['menu_link'], 0, 1) !== '/') {
                            $item['menu_link'] = '/' . $item['menu_link'];
                        }
                        $fullUrl =
                            (substr($baseUrl, -1) === '/' ? substr($baseUrl, 0, -1) : $baseUrl) . $item['menu_link'];
                    @endphp
                    <li
                        class="menu-item {{ request()->is('/') && $item['menu_link'] === '/' ? 'active' : '' }}{{ request()->is(ltrim($item['menu_link'], '/')) && $item['menu_link'] !== '/' ? 'active' : '' }}">
                        <a href="{{ $fullUrl }}"
                            class="menu-link {{ str_contains($item['menu_link'], 'state-legislative-assembly-election') ? 'mobile-new' : '' }}">{{ $item['menu_name'] }}
                        </a>
                    </li>
                @endforeach


            </ul>
        </div>
    </nav>