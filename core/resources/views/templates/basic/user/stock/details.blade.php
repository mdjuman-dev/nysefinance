@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="row">
                <div class="col-md-12">
                    <div class="row stock-sec">

                        <div class="col-md-12">
                            <button type="button" class="btn btn-success buyStock d-s-buy-btn btn-custom-class btn-sm">
                                Buy Stock
                            </button>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 mt-3">

                    <!-- TradingView Widget BEGIN -->
{{--                    <div class="tradingview-widget-container">--}}
{{--                        <div class="tradingview-widget-container__widget"></div>--}}

{{--                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>--}}
{{--                            {--}}
{{--                                "symbols": [--}}
{{--                                [--}}
{{--                                    "{{$stock->stock_code}}"--}}
{{--                                ]--}}
{{--                            ],--}}
{{--                                "chartOnly": false,--}}
{{--                                "width": "100%",--}}
{{--                                "height": "100%",--}}
{{--                                "locale": "en",--}}
{{--                                "colorTheme": "dark",--}}
{{--                                "autosize": true,--}}
{{--                                "showVolume": false,--}}
{{--                                "showMA": false,--}}
{{--                                "hideDateRanges": false,--}}
{{--                                "hideMarketStatus": false,--}}
{{--                                "hideSymbolLogo": false,--}}
{{--                                "scalePosition": "right",--}}
{{--                                "scaleMode": "Normal",--}}
{{--                                "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",--}}
{{--                                "fontSize": "10",--}}
{{--                                "noTimeScale": false,--}}
{{--                                "valuesTracking": "1",--}}
{{--                                "changeMode": "price-and-percent",--}}
{{--                                "chartType": "area",--}}
{{--                                "maLineColor": "#2962FF",--}}
{{--                                "maLineWidth": 1,--}}
{{--                                "maLength": 9,--}}
{{--                                "headerFontSize": "medium",--}}
{{--                                "lineWidth": 2,--}}
{{--                                "lineType": 0,--}}
{{--                                "dateRanges": [--}}
{{--                                "1d|1",--}}
{{--                                "1m|30",--}}
{{--                                "3m|60",--}}
{{--                                "12m|1D",--}}
{{--                                "60m|1W",--}}
{{--                                "all|1M"--}}
{{--                            ]--}}
{{--                            }--}}
{{--                        </script>--}}
{{--                    </div>--}}
                    <!-- TradingView Widget END -->






                    <div class="row">
                        <div class="col-md-12 sec-main-chart">
{{--                            <div class="tradingview-widget-container" >--}}
{{--                                <div class="tradingview-widget-container__widget" ></div>--}}
{{--                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>--}}
{{--                                    {--}}
{{--                                        "autosize": true,--}}
{{--                                        "width": "100%",--}}
{{--                                        "height": "100%",--}}
{{--                                        "symbol": "{{$stock->stock_code}}",--}}
{{--                                        "interval": "D",--}}
{{--                                        "timezone": "exchange",--}}
{{--                                        "theme": "dark",--}}
{{--                                        "backgroundColor": "rgba(255, 255, 255, 1)",--}}
{{--                                        "style": "1",--}}
{{--                                        "withdateranges": true,--}}
{{--                                        "hide_side_toolbar": false,--}}
{{--                                        "allow_symbol_change": true,--}}
{{--                                        "save_image": false,--}}
{{--                                        "studies": [--}}
{{--                                        "ROC@tv-basicstudies",--}}
{{--                                        "StochasticRSI@tv-basicstudies",--}}
{{--                                        "MASimple@tv-basicstudies"--}}
{{--                                    ],--}}
{{--                                        "locale": "en",--}}
{{--                                        "show_popup_button": true,--}}
{{--                                        "popup_width": "1000",--}}
{{--                                        "popup_height": "650",--}}
{{--                                        "calendar": false,--}}
{{--                                        "support_host": "https://www.tradingview.com"--}}
{{--                                    }--}}
{{--                                </script>--}}
{{--                            </div>--}}




                            <!-- TradingView Widget BEGIN -->
                            <div class="tradingview-widget-container" style="height:100%;width:100%">
                                <div class="tradingview-widget-container__widget"></div>
                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                                    {
                                        "allow_symbol_change": true,
                                        "calendar": false,
                                        "details": false,
                                        "hide_side_toolbar": true,
                                        "hide_top_toolbar": false,
                                        "hide_legend": false,
                                        "hide_volume": false,
                                        "hotlist": false,
                                        "interval": "1",
                                        "locale": "en",
                                        "save_image": true,
                                        "style": "1",
                                        "symbol": "{{$stock->stock_code}}",
                                        "theme": "dark",
                                        "timezone": "Etc/UTC",
                                        "backgroundColor": "#0F0F0F",
                                        "gridColor": "rgba(242, 242, 242, 0.06)",
                                        "watchlist": [],
                                        "withdateranges": false,
                                        "compareSymbols": [],
                                        "studies": [],
                                        "autosize": true
                                    }
                                </script>
                            </div>
                            <!-- TradingView Widget END -->


                        </div>
                    </div>



                    <div class="row mt-3 mobile-view-buy align-items-center">
                        <div class="col-md-6 col-6">
                            <button type="button" style="width: 100%;" class="btn btn-success d-block buyStock btn-custom-class btn-sm">
                                Buy Stock
                            </button>
                        </div>
                        <div class="col-md-6 col-6">
                            @if($stock->use_for=='bond')
                                <a href="{{route('user.my.bonds')}}" style="width: 100%;" class="btn btn-danger d-block buyStock btn-custom-class btn-sm">
                                    Sell Stock
                                </a>
                            @else
                            <a href="{{route('user.stock.my')}}" style="width: 100%;" class="btn btn-danger d-block buyStock btn-custom-class btn-sm">
                                Sell Stock
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="row desc-sec-stock">
                        <div class="col-md-12">
                            <p>{!! $stock->short_description !!}</p>
                            <div class="mt-2">
                                {!! $stock->description !!}
                            </div>
                        </div>
                    </div>



                    <div class="row mt-4">
                        <div class="col-md-12">


                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">

                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-three-home-tab"
                                               data-bs-toggle="pill" href="#stock-analytics" role="tab" aria-controls="custom-tabs-three-home"
                                               aria-selected="true">Analysis</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-three-profile-tab"
                                               data-bs-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile"
                                               aria-selected="false">Financials</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-three-messages-tab"
                                               data-bs-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages"
                                               aria-selected="false">Profile</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-three-settings-tab"
                                               data-bs-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings"
                                               aria-selected="false">Economic</a>
                                        </li>

                                    </ul>

                                    <div class="tab-content" id="custom-tabs-three-tabContent">

                                        <div class="tab-pane fade active show" id="stock-analytics" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                            <div class="tradingview-widget-containers">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-technical-analysis.js" async>
                                                    {
                                                        "colorTheme": "dark",
                                                        "displayMode": "multiple",
                                                        "isTransparent": true,
                                                        "locale": "en",
                                                        "interval": "1m",
                                                        "disableInterval": false,
                                                        "width": "100%",
                                                        "height": "100%",
                                                        "symbol": "{{$stock->stock_code}}",
                                                        "showIntervalTabs": true
                                                    }
                                                </script>
                                            </div>


                                        </div>

                                        <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                            <!-- TradingView Widget BEGIN -->
                                            <div class="tradingview-widget-containers">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-financials.js" async>
                                                    {
                                                        "symbol": "{{$stock->stock_code}}",
                                                        "colorTheme": "dark",
                                                        "displayMode": "regular",
                                                        "isTransparent": false,
                                                        "locale": "en",
                                                        "width": "100%",
                                                        "height": "100%"
                                                    }
                                                </script>
                                            </div>
                                            <!-- TradingView Widget END -->

                                        </div>

                                        <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                                            <!-- TradingView Widget BEGIN -->
                                            <div class="tradingview-widget-containers">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-profile.js" async>
                                                    {
                                                        "symbol": "{{$stock->stock_code}}",
                                                        "colorTheme": "dark",
                                                        "isTransparent": false,
                                                        "locale": "en",
                                                        "width": "100%",
                                                        "height": "100%"
                                                    }
                                                </script>
                                            </div>
                                            <!-- TradingView Widget END -->
                                        </div>

                                        <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                                            <!-- TradingView Widget BEGIN -->
                                            <div class="tradingview-widget-containers">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-events.js" async>
                                                    {
                                                        "colorTheme": "dark",
                                                        "isTransparent": false,
                                                        "locale": "en",
                                                        "countryFilter": "ar,au,br,ca,cn,fr,de,in,id,it,jp,kr,mx,ru,sa,za,tr,gb,us,eu",
                                                        "importanceFilter": "-1,0,1",
                                                        "width": "100%",
                                                        "height": "100%"
                                                    }
                                                </script>
                                            </div>
                                            <!-- TradingView Widget END -->
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>


                    <div class="row mt-5">
                        @if($videos)
                            @foreach($videos as $key=>$video)
                                <div class="col-md-3 col-12">
                                    <div class="video-section">
                                        <video controls>
                                            <source src="{{ getImage(getFilePath('currency') .'/'.$video->video,getFileSize('currency')) }}" type="video/mp4">
                                        </video>
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade modal-lg" id="butStockModal" style="background: rgb(35, 35, 35);" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered "  role="document">
            <div class="modal-content" style="background: #000000;">
                <form action="{{route('user.stock.buy')}}" method="post">
                    @csrf

                    <input type="hidden" name="id" value="{{$stock->id}}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Confirm Buy</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div>
                                    <strong>Stock:</strong> {{$stock->name}}
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="mt-2">
                                    <label for="">Buy In</label>
                                    <div class="form-group mb-3 d-flex mt-3 justify-content-between divide-for-bond">
                                        <div>
                                            <input type="radio" name="type" class="choose-stock-type" checked id="fix" value="fix">
                                            <label for="fix"><b>Mutual Fund</b></label>
                                        </div>

                                        <div>
                                            <input type="radio" name="type" class="choose-stock-type ml-3" id="unfix" value="unfix">
                                            <label for="unfix"><b>Live Market</b></label>
                                        </div>
                                    </div>

                                    <div class="stock-fix-section">
                                        <div class="row">
                                            <div class="col-md-6 col-6">
                                                <label for="">Enter Amount</label>
                                                <input type="text" class="form-control iv-amnt" placeholder="Enter Amount" name="invest_amount">
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <label class="d-block" for="">Holding time</label>
                                                <select name="invest_time" class="form-control">
                                                    <option value="month">1 Month</option>
                                                    <option value="half_year">6 Month</option>
                                                    <option value="year">1 Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group custom-stock-section" style="display: none">

                                        <div class="form-group section-unfix-invest d-none">
                                            <label for="">Invest Amount</label>
                                            <input type="text" name="unfix_invest_amount" class="form-control" placeholder="Enter Invest Amount">
                                        </div>

                                        <button type="button" class="btn custom-stock-btn" data-name="25">25%</button>
                                        <button type="button" class="btn custom-stock-btn ml-2" data-name="50">50%</button>
                                        <button type="button" class="btn custom-stock-btn ml-2 active" data-name="100">100%</button>
                                        <button type="button" class="btn custom-stock-btn ml-2" data-name="custom">Custom</button>
                                    </div>

                                </div>

                            </div>
                        </div>


                        <input type="hidden" name="unfix_amount" class="unfix_amount" value="100">

                        <div class="alert alert-info mt-3 st-desc">
                            <small>
                                <strong>Info! </strong> {{$stock->short_description}}
                            </small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-custom-class btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-custom-class confirmBuy btn-sm">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="sec-extra d-none">

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#overview">Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#news">News</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#minds">Minds</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#ideas">Ideas</a>
            </li>
        </ul>

        <!-- Earnings Section -->
        <div class="content-section">
            <h2 class="section-title">Earnings</h2>

            <div class="earnings-controls">
                <button class="control-btn">Annual</button>
                <button class="control-btn active">Quarterly</button>
            </div>

            <div class="chart-container" style="height: 150px !important;">
                <canvas id="earningsChart"></canvas>
            </div>

            <div class="earnings-info">
                <div class="info-row">
                    <span class="info-label">Next earnings report</span>
                    <span class="info-value">≈ Jul 24, 2025</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Report period</span>
                    <span class="info-value">Q3 2025</span>
                </div>
                <div class="info-row">
                    <span class="info-label">EPS estimate</span>
                    <span class="info-value">1.42 <small>USD</small></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Revenue estimate</span>
                    <span class="info-value">88.809 B <small>USD</small></span>
                </div>
            </div>

            <a href="#" class="more-link">
                More earnings <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Key Stats Section -->
        <div class="content-section">
            <h2 class="section-title">Key stats</h2>

            <div class="key-stats">
                <div class="info-row">
                    <span class="info-label">Volume</span>
                    <span class="info-value">25.70 M</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Market capitalization</span>
                    <span class="info-value">2.92 T <small>USD</small></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dividend yield (indicated)</span>
                    <span class="info-value">0.52%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Price to earnings Ratio (TTM)</span>
                    <span class="info-value">30.66</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Basic EPS (TTM)</span>
                    <span class="info-value">6.44 <small>USD</small></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Net income (FY)</span>
                    <span class="info-value">93.74 B <small>USD</small></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Revenue (FY)</span>
                    <span class="info-value">391.04 B <small>USD</small></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Shares float</span>
                    <span class="info-value">14.92 B</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Beta (1Y)</span>
                    <span class="info-value">1.06</span>
                </div>
            </div>

            <a href="#" class="more-link">
                More financials <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Dividends Section -->
        <div class="content-section">
            <h2 class="section-title">Dividends</h2>

            <div class="dividend-chart">
                <div class="donut-chart" style="height: 150px !important;">
                    <canvas id="dividendChart" width="150" height="150"></canvas>
                </div>
            </div>

            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--text-secondary);"></div>
                    <span>Earnings retained</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--accent-green);"></div>
                    <span>Payout ratio (TTM)</span>
                </div>
            </div>

            <div class="earnings-info">
                <div class="info-row">
                    <span class="info-label">Dividends yield TTM</span>
                    <span class="info-value">0.51%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dividend amount</span>
                    <span class="info-value">0.26 <small>USD</small></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last pay date</span>
                    <span class="info-value">May 15, 2025</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last ex-date</span>
                    <span class="info-value">May 12, 2025</span>
                </div>
            </div>

            <a href="#" class="more-link">
                Dividend history <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Income Statement Section -->
        <div class="content-section">
            <h2 class="section-title">Income statement</h2>

            <div class="earnings-controls">
                <button class="control-btn">Annual</button>
                <button class="control-btn active">Quarterly</button>
            </div>

            <div class="chart-container" style="height: 150px !important;">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <!-- Bottom Navigation (Mobile Only) -->
        <div class="bottom-nav d-md-none">
            <div class="nav-icon" style="border-radius: 4px;"></div>
            <div class="nav-icon" style="border-radius: 50%;"></div>
            <div class="nav-icon" style="border-radius: 0; transform: rotate(45deg);"></div>
        </div>
    </div>



@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <style>
        .tradingview-widget-containers iframe::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        .tradingview-widget-containers iframe::-webkit-scrollbar
        {
            width: 10px;
            background-color: #F5F5F5;
        }

        .tradingview-widget-containers iframe::-webkit-scrollbar-thumb
        {
            background-color: #000000;
            border: 2px solid #555555;
        }

        .tradingview-widget-containers{
            height: 650px !important;
        }

        @media (max-width: 750px) {

            .sec-main-chart{
                height: 450px;
            }
            .tradingview-widget-containers{
                height: 450px !important;
            }
        }

    </style>

    <style>
        :root {
            --bg-primary: #1c1c1e;
            --bg-secondary: #2c2c2e;
            --bg-tertiary: #3a3a3c;
            --text-primary: #ffffff;
            --text-secondary: #8e8e93;
            --accent-green: #30d158;
            --accent-red: #ff453a;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .status-bar {
            background-color: var(--bg-primary);
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--bg-tertiary);
        }

        .status-left {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-right {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stock-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--bg-tertiary);
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .apple-logo {
            width: 40px;
            height: 40px;
            background-color: var(--text-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--bg-primary);
        }

        .stock-price {
            font-size: 32px;
            font-weight: 300;
        }

        .stock-change {
            color: var(--accent-red);
            font-size: 18px;
            margin-left: 10px;
        }

        .stock-symbol {
            color: var(--text-secondary);
            font-size: 14px;
            margin-top: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .action-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
        }

        .nav-tabs {
            background-color: var(--bg-primary);
            border: none;
            /*padding: 0 20px;*/
        }

        .nav-tabs .nav-link {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            padding: 11px 13px;
            border-radius: 20px;
            margin-right: 10px;
        }

        .nav-tabs .nav-link.active {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .content-section {
            padding: 20px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .earnings-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .control-btn {
            background-color: var(--bg-tertiary);
            border: none;
            color: var(--text-secondary);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
        }

        .control-btn.active {
            background-color: var(--text-primary);
            color: var(--bg-primary);
        }

        .chart-container {
            height: 300px;
            margin-bottom: 30px;
            position: relative;
        }

        .earnings-info {
            background-color: var(--bg-secondary);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--bg-tertiary);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--text-secondary);
            font-size: 16px;
        }

        .info-value {
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 500;
        }

        .key-stats {
            background-color: var(--bg-secondary);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .more-link {
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            font-size: 16px;
        }

        .more-link:hover {
            color: var(--text-primary);
        }

        .dividend-chart {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }

        .donut-chart {
            position: relative;
            width: 150px;
            height: 150px;
        }

        .chart-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .chart-percentage {
            font-size: 24px;
            font-weight: 600;
            color: var(--accent-green);
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            font-size: 14px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: var(--bg-primary);
            border-top: 1px solid var(--bg-tertiary);
            padding: 10px 0;
            display: flex;
            justify-content: center;
            gap: 60px;
        }

        .nav-icon {
            width: 30px;
            height: 30px;
            background-color: var(--text-secondary);
            border-radius: 4px;
        }

        @media (min-width: 768px) {
            .container-fluid {
                max-width: 800px;
                margin: 0 auto;
            }

            .status-bar {
                display: none;
            }

            .bottom-nav {
                display: none;
            }

            .stock-header {
                padding: 30px;
            }

            .content-section {
                padding: 30px;
            }
        }

        @media (min-width: 1200px) {
            .container-fluid {
                max-width: 1000px;
            }

            .earnings-info, .key-stats {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .section-title {
                grid-column: 1 / -1;
            }
        }
    </style>



    <style>
        .tradingview-widget-container{
            min-height: 400px;
        }
        .btn-custom-class{
            padding: 10px 20px !important;
        }
        .custom-stock-btn.active,.custom-stock-btn.active:hover{
            background: #1329b8 !important;
            border: 1px solid #0022ff;
        }
        .custom-stock-btn:hover {
            background: #a3a3a3;
        }
        .custom-stock-btn{
            background: #a3a3a3;
            padding: 10px 20px !important;
        }
        .custom-stock-section{
            transition: visibility 0s linear 0.33s, opacity 0.33s linear;
        }
        .mobile-view-buy{
            display: none;
        }
        .desc-sec-stock{
            margin-top: 50px;
        }
        .iv-amnt{
            color: black !important;
        }
        .video-section {
            text-align: center;
            }
        .video-section video{
            height: 160px;
            width: 95%;
            margin: 0 auto;
        }

        @media(max-width: 700px) {
            .mobile-view-buy{
                display: flex !important;
            }
            .d-s-buy-btn{
                display: none;
            }

            .video-section video{
                height: 185px;
                width: 90%;
                margin: 0 auto;
            }
            .st-desc{
                display: none;
            }
            .tradingview-widget-container{
                height: 350px !important;
            }

        }

        .dashboard__right{
            min-height: 1800px;
        }

        /* Stock Details Styles */
        .stock-details-container {
            background: #1a1a1a;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .section-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        /* Key Stats Section */
        .stats-list {
            margin-bottom: 16px;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #333;
        }

        .stat-row:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: #cccccc;
            font-size: 14px;
            font-weight: 400;
        }

        .stat-value {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }

        /* Toggle Buttons */
        .earnings-toggle {
            display: flex;
            background: #2a2a2a;
            border-radius: 8px;
            padding: 4px;
            gap: 4px;
            width: fit-content;
        }

        .toggle-btn {
            background: transparent;
            border: none;
            color: #cccccc;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn.active {
            background: #4a4a4a;
            color: #ffffff;
        }

        /* Earnings Chart */
        .earnings-chart {
            margin: 20px 0;
        }

        .horizontal-chart {
            margin-bottom: 16px;
        }

        .chart-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            height: 30px;
        }

        .chart-bar {
            height: 20px;
            border-radius: 4px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding-left: 8px;
        }

        .chart-bar.actual {
            background: #00d4aa;
        }

        .chart-bar.estimate {
            background: transparent;
            border: 2px solid #666;
        }

        .chart-bar:hover {
            opacity: 0.8;
        }

        .bar-label {
            color: #ffffff;
            font-size: 12px;
            font-weight: 500;
        }

        .chart-legend {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #cccccc;
            font-size: 14px;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .legend-dot.actual {
            background: #00d4aa;
        }

        .legend-dot.estimate {
            background: transparent;
            border: 2px solid #666;
        }

        /* Earnings Details */
        .earnings-details, .dividend-details {
            background: #2a2a2a;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #333;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #cccccc;
            font-size: 14px;
        }

        .detail-value {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }

        /* Dividends Section */
        .dividend-chart-container {
            display: flex;
            align-items: center;
            gap: 24px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .donut-chart {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .donut-ring {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: relative;
            background: conic-gradient(
                #666 0deg calc(var(--percentage) * 3.6deg),
                #00d4aa calc(var(--percentage) * 3.6deg) 360deg
            );
        }

        .donut-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: #1a1a1a;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .donut-value {
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            line-height: 1;
        }

        .donut-label {
            color: #cccccc;
            font-size: 10px;
            margin-top: 2px;
        }

        .donut-legend {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .legend-dot.retained {
            background: #666;
        }

        .legend-dot.payout {
            background: #00d4aa;
        }

        /* More Links */
        .more-link {
            text-align: center;
            margin-top: 16px;
        }

        .more-link a {
            color: #00d4aa;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s ease;
        }

        .more-link a:hover {
            color: #00b894;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stock-details-container {
                padding: 16px;
                margin-top: 16px;
            }

            .stat-row {
                padding: 10px 0;
            }

            .chart-row {
                height: 25px;
            }

            .chart-bar {
                height: 16px;
            }

            .dividend-chart-container {
                flex-direction: column;
                align-items: center;
                gap: 16px;
            }

            .donut-chart {
                width: 100px;
                height: 100px;
            }

            .donut-center {
                width: 70px;
                height: 70px;
            }

            .donut-value {
                font-size: 14px;
            }

            .donut-label {
                font-size: 9px;
            }

            .earnings-details, .dividend-details {
                padding: 12px;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .detail-value {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .section-title {
                font-size: 16px;
            }

            .chart-row {
                height: 20px;
            }

            .chart-bar {
                height: 14px;
            }

            .bar-label {
                font-size: 11px;
            }

            .toggle-btn {
                padding: 6px 12px;
                font-size: 13px;
            }
        }
    </style>
@endpush


@push('script')

    <script>
        // Earnings Chart
        const earningsCtx = document.getElementById('earningsChart').getContext('2d');
        new Chart(earningsCtx, {
            type: 'line',
            data: {
                labels: ['Q3 \'24', 'Q4 \'24', 'Q1 \'25', 'Q2 \'25', 'Q3 \'25'],
                datasets: [{
                    label: 'Actual',
                    data: [1.40, 1.60, 2.80, 2.10, null],
                    borderColor: '#30d158',
                    backgroundColor: '#30d158',
                    pointBackgroundColor: '#30d158',
                    pointBorderColor: '#30d158',
                    pointRadius: 8,
                    pointHoverRadius: 10,
                    fill: false,
                    tension: 0
                }, {
                    label: 'Estimate',
                    data: [null, null, null, null, 1.40],
                    borderColor: '#8e8e93',
                    backgroundColor: 'transparent',
                    pointBackgroundColor: 'transparent',
                    pointBorderColor: '#8e8e93',
                    pointRadius: 8,
                    pointHoverRadius: 10,
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#8e8e93',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#3a3a3c',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#8e8e93'
                        }
                    },
                    y: {
                        grid: {
                            color: '#3a3a3c',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#8e8e93',
                            callback: function(value) {
                                return value.toFixed(2);
                            }
                        },
                        min: 0,
                        max: 3
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#30d158'
                    }
                }
            }
        });

        // Dividend Donut Chart
        const dividendCtx = document.getElementById('dividendChart').getContext('2d');
        new Chart(dividendCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [15.61, 84.39],
                    backgroundColor: ['#30d158', '#8e8e93'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });

        // Income Statement Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'bar',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    label: 'Revenue',
                    data: [31, 85, 120, 140],
                    backgroundColor: '#30d158',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#8e8e93'
                        }
                    },
                    y: {
                        grid: {
                            color: '#3a3a3c',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#8e8e93',
                            callback: function(value) {
                                return value + 'B';
                            }
                        }
                    }
                }
            }
        });

        // Tab functionality
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Control button functionality
        document.querySelectorAll('.control-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.querySelectorAll('.control-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>



    <script>
        "use strict";

        $(document).on('click', '.custom-stock-btn', function (e) {
            e.preventDefault();

            const name = $(this).attr('data-name');
            $('.unfix_amount').val(name);

            if(name=='custom'){
                $('.section-unfix-invest').removeClass('d-none');
            }else{
                $('.section-unfix-invest').addClass('d-none');
            }

            $('.custom-stock-btn').removeClass('active');
            $(this).addClass('active');
        });


        $(document).on('click', '.buyStock', function (e){

            const stock_type='{{$stock->use_for}}';

            if(stock_type=='bond'){
                // $('#unfix').trigger('click');
                // $('button[data-name="custom"]').trigger('click');
                $('.divide-for-bond').addClass('d-none');
                // $('.custom-stock-btn').addClass('d-none');
            }else{
                $('.divide-for-bond').removeClass('d-none');
                // $('.custom-stock-btn').removeClass('d-none');
            }

            $('#butStockModal').modal('show');
        });

        $(document).on('click', '.choose-stock-type', function (e){

            const type=$(this).val();
            if(type=='unfix'){
                $('.custom-stock-section').show();
                $('.stock-fix-section').hide();
            }else{
                $('.custom-stock-section').hide();
                $('.stock-fix-section').show();
            }
        });

        // Stock Details Toggle Functionality
        $(document).on('click', '.toggle-btn', function(e) {
            e.preventDefault();
            const $parent = $(this).closest('.earnings-toggle, .income-toggle');
            $parent.find('.toggle-btn').removeClass('active');
            $(this).addClass('active');

            // Here you can add logic to switch between annual and quarterly data
            const period = $(this).data('period');
            console.log('Switched to:', period);
        });

        // Chart bar hover effects
        $(document).on('mouseenter', '.chart-bar', function() {
            const value = $(this).data('value');
            const quarter = $(this).data('quarter');
            $(this).attr('title', `${quarter}: ${value}`);
        });

    </script>
@endpush
