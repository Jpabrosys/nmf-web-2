<?php
use App\Models\ElectionResult;
use App\Models\HomeSection;

//  Only fetch parties marked for display in list
$voteCounts = ElectionResult::where('show_in_list', -1)->get();

//  Top 4 parties (still based on seats won)
$topParties = ElectionResult::orderBy('sequence', 'asc')
    ->where('show_in_highlight', -1)
    ->take(4)
    ->get();

$status = HomeSection::where('title', 'ElectionLiveSection')->value('status') ?? 0;
?>
@php


    $showLive = HomeSection::where('title', 'ElectionLiveSection')->where('status', 1)->exists();
@endphp


@if ($showLive)
    <section class="election-section-live">
        <div class="cm-container">
            <div class="election-section-live-wrap">
                <div class="el-live-tag">
                    <span class="dot"></span>
                    <h3>Live</h3>
                </div>

                <div class="live-el-left">
                    <h3 class="live-el-title exit-tittle">बिहार चुनाव एग्जिट पोल 2025</h3>
                    <div class="live-el-img">
                        <img src="{{ asset('asset/images/bihar-map.png') }}" alt="Bihar exit poll">
                    </div>
                </div>

                <div class="live-el-mid">
                    <table class="live-party-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($voteCounts as $party)
                                <tr class="ext-table {{ strtolower($party->abbreviation) }}-">
                                    <td>
                                        <img src="{{ asset('asset/images/' . strtolower($party->abbreviation) . '-logo.png') }}"
                                            alt="{{ $party->party_name }} Logo">
                                        {{ $party->abbreviation }}
                                    </td>
                                    <td>{{ $party->exit_poll }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    $classMap = [
                        'NDA' => 'nda',
                        'RJD+' => 'rjd',
                        'JSP' => 'jsp',
                        'OTH' => 'oth',
                    ];
                @endphp

                <div class="live-el-right">
                    <div class="live-result-section">
                        @foreach ($topParties as $top)
                            @php
                                $boxClass = $classMap[$top->party_name] ?? 'oth';
                            @endphp
                            <div class="live-result-box {{ $boxClass }}">
                                <span class="title">{{ $top->abbreviation }}</span>
                                <span class="count" data-count="{{ $top->seats_won }}">{{ $top->seats_won }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="live-chart-container">
                        <div class="live-win-mark">122
                            <div class="live-win-t"></div>
                            <div class="win-l"></div>
                        </div>
                        <canvas id="semiCircleChart3"></canvas>
                        <div class="live-total-seats">
                            <p>Total Seats</p>
                            <h3>243/{{ $topParties->sum('seats_won') }} </h3>
                            {{-- <h3> 243 seats</h3> --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

@endif




    @php
        $results3 = $topParties->take(4)->map(function ($p) {
            return [
                'party_name' => $p->party_name,
                'abbreviation' => $p->abbreviation,
                'seats_won' => $p->seats_won,
            ];
        });
    @endphp

    <script>
        const results3 = @json($results3);
    </script>
    {{-- added end====== --}}

    {{-- NL1031: 19.09.2025 : added chart js --}}
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        // maha mukabla swiper
        const swiper = new Swiper('.mh-carousel', {
            loop: true,
            navigation: {
                nextEl: '.mh-button-next',
                prevEl: '.mh-button-prev',
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });
        document.addEventListener("DOMContentLoaded", function() {
            // ---------------- COUNTER ----------------
            const counters = document.querySelectorAll(".count");
            counters.forEach(counter => {
                let target = +counter.getAttribute("data-count");
                let current = 0;
                let increment = Math.ceil(target / 50);

                let interval = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(interval);
                    }
                    counter.textContent = current.toString().padStart(2, '0');
                }, 30);
            });

            // ---------------- SEMI-CIRCLE CHART FUNCTION ----------------
            if (window.Chart && window.ChartDataLabels && !Chart._nmfDataLabelsRegistered) {
                Chart.register(ChartDataLabels);
                Chart._nmfDataLabelsRegistered = true;
            }

            function createSemiCircleChart(canvasId, results, options = {}) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return null;

                // destroy old instance
                if (canvas._chartInstance) {
                    try {
                        canvas._chartInstance.destroy();
                    } catch (e) {}
                    canvas._chartInstance = null;
                }

                // Filter out LJP
                const filteredResults = results.filter(r => r.abbreviation.toLowerCase() !== 'ljp');

                // Prepare labels, values, and colors
                const labels = filteredResults.map(r => r.party_name);
                const values = filteredResults.map(r => r.seats_won);
                const colorMap = {
                    'nda': '#fd6101',
                    'rjd': '#13B605',
                    'jsp': '#FABB00',
                    'oth': '#D13A37'
                };
                const colors = filteredResults.map(r => colorMap[r.abbreviation.toLowerCase()] || '#13B605');
                const aspectRatio = (typeof options.aspectRatio !== 'undefined') ?
                    options.aspectRatio :
                    (window.innerWidth < 768 ? 1 : 1.5);

                const config = {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors,
                            borderWidth: 2,
                            borderColor: 'white',
                            hoverOffset: 15,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: aspectRatio,
                        rotation: -90,
                        circumference: 180,
                        cutout: options.cutout || '60%',
                        animation: {
                            duration: options.duration || 600
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            // NL1031:16Sep2025: Disable tooltip
                            tooltip: {
                                enabled: false,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total ? Math.round((value / total) * 100) : 0;
                                        return `${label}: ${percentage}%`;
                                    }
                                }
                            },
                            datalabels: {
                                color: 'black',
                                font: {
                                    weight: 'bold',
                                    size: options.datalabelSize || 14
                                },
                                formatter: () => ''
                            }
                        }
                    },
                    plugins: []
                };

                if (window.ChartDataLabels) config.plugins.push(ChartDataLabels);

                const chartInstance = new Chart(canvas.getContext('2d'), config);
                canvas._chartInstance = chartInstance;
                return chartInstance;
            }

            // Example datasets
            const results1 = [{
                    party_name: "NDA",
                    seats_won: 87
                },
                {
                    party_name: "RJD+",
                    seats_won: 53
                },
                {
                    party_name: "LJP",
                    seats_won: 5
                },
                {
                    party_name: "OTH",
                    seats_won: 9
                }
            ];

            const results2 = [{
                    party_name: "NDA",
                    seats_won: 87
                },
                {
                    party_name: "RJD+",
                    seats_won: 53
                },
                {
                    party_name: "LJP",
                    seats_won: 5
                },
                {
                    party_name: "OTH",
                    seats_won: 9
                }
            ];

            createSemiCircleChart('semiCircleChart3', results3, {
                duration: 500
            });

            // init charts
            createSemiCircleChart('semiCircleChart', results1, {
                duration: 500
            });
            createSemiCircleChart('semiCircleChart2', results2, {
                duration: 500
            });
            createSemiCircleChart('semiCircleChart3', results3, {
                duration: 500
            });


            // re-render on resize (debounced)
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    createSemiCircleChart('semiCircleChart', results1);
                    createSemiCircleChart('semiCircleChart2', results2);
                    createSemiCircleChart('semiCircleChart3', results3);
                }, 200);
            });
        });
    </script>

    <script>
        const swipernew = new Swiper('.swiper2', {
            direction: 'horizontal',
            loop: true,
            slidesPerView: 5,
            spaceBetween: 10,


            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },


            scrollbar: {
                el: '.swiper-scrollbar',
            },


            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 10,
                },
            },
        });
    </script>