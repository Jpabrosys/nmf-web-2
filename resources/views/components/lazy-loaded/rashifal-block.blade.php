@if (!empty($rashifal))
<div class="rasifal-section">
    <div class="cm-container">
        <div class="rashifal-section">
            <div class="rashifal-container" role="region" aria-label="राशिफल दैनिक">
                <div class="rashifal-box">
                    <div class="rotating-bg" aria-hidden="true"></div>
                    <div class="rashifal-wrapper">
                        <button class="nav-btn prev" aria-label="पिछला राशिफल"><i class="fas fa-chevron-left"></i></button>
                        <div class="rashifal-slider" tabindex="0" role="listbox" aria-live="polite" aria-label="राशि चिन्ह">
                            <div class="rashifal-item spacer" aria-hidden="true"></div>
                            @foreach($rashifal as $index => $r)
                                @php
                                    $rashiImg = config('global.base_url_image') . $r->full_path . '/' . $r->file_name;
                                @endphp
                                <div class="rashifal-item" role="option" aria-selected="{{ $index == 0 ? 'true':'false' }}">
                                    <img src="{{ $rashiImg }}"
                                         alt="{{ $r->name }}"
                                         width="100" height="100"
                                         data-sign="{{ strtolower($r->name) }}"
                                         data-title="{{ $r->name }}"
                                         data-description="{{ $r->description }}"
                                         class="{{ $index == 0 ? 'active':'' }}"
                                         loading="lazy" tabindex="0" />
                                </div>
                            @endforeach
                            <div class="rashifal-item spacer" aria-hidden="true"></div>
                        </div>
                        <button class="nav-btn next" aria-label="अगला राशिफल"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <h2 id="rashifal-title">आपके तारे - दैनिक: {{ $rashifal[0]->name ?? '' }}</h2>
                    <p id="rashifal-text">{{ $rashifal[0]->description ?? '' }}</p>
                </div>
                <div class="adBgSidebar">
                    <div class="adtxt">Advertisement</div>
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-3986924419662120"
                         data-ad-slot="6911924096"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                </div>
            </div>
        </div>
    </div>
</div>
@endif