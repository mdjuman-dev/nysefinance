@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row stock-main-section">
        <div class="col-12">


            <div class="card-body mt-4">
                <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active bondType" data-type="Overview" id="overview-tab" data-bs-toggle="pill"
                           href="#below-overview"
                           role="tab" aria-controls="custom-content-above-home" aria-selected="true">Bond</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bondType" data-type="Stock" id="stock-tab" data-bs-toggle="pill" href="#below-stock"
                           role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Economy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bondType" data-type="Crypto" id="crypto-tab" data-bs-toggle="pill" href="#below-crypto"
                           role="tab" aria-controls="custom-content-above-messages" aria-selected="false">Indices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bondType" data-type="Futures" id="futures-tab" data-bs-toggle="pill" href="#below-futures"
                           role="tab" aria-controls="custom-content-above-settings" aria-selected="false">Options</a>
                    </li>
                </ul>


                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade active show" id="below-overview" role="tabpanel"
                         aria-labelledby="custom-content-above-home-tab">

                        <div class="row">
                            @if($overviews->isNotEmpty())
                                @foreach($overviews as $overview)
                                    <div class="col-md-6 col-12 mt-3">
                                        <div class="main-stock-section" data-url="{{route('user.bond.details',[$overview->slug])}}">


                                            <div class="tradingview-widget-container">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                                                    {
                                                        "symbol": "{{$overview->stock_code}}",
                                                        "chartOnly": false,
                                                        "dateRange": "1D",
                                                        "noTimeScale": false,
                                                        "colorTheme": "dark",
                                                        "isTransparent": false,
                                                        "locale": "en",
                                                        "width": "100%",
                                                        "autosize": true,
                                                        "height": "100%"
                                                    }
                                                </script>
                                            </div>


                                        </div>
                                    </div>
                                @endforeach

                            @else

                                <div class="text-danger text-center mt-4">
                                    No Data Available
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="tab-pane fade" id="below-stock" role="tabpanel"
                         aria-labelledby="custom-content-above-profile-tab">
                        <div class="row">
                            @if($stocks->isNotEmpty())
                                @foreach($stocks as $stock)
                                    <div class="col-md-6 col-12 mt-3">
                                        <div class="main-stock-section" data-url="{{route('user.bond.details',[$stock->slug])}}">


                                            <div class="tradingview-widget-container">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                                                    {
                                                        "symbol": "{{$stock->stock_code}}",
                                                        "chartOnly": false,
                                                        "dateRange": "1D",
                                                        "noTimeScale": false,
                                                        "colorTheme": "dark",
                                                        "isTransparent": false,
                                                        "locale": "en",
                                                        "width": "100%",
                                                        "autosize": true,
                                                        "height": "100%"
                                                    }
                                                </script>
                                            </div>


                                        </div>
                                    </div>
                                @endforeach

                            @else

                                <div class="text-danger text-center mt-4">
                                    No Data Available
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="below-crypto" role="tabpanel"
                         aria-labelledby="custom-content-above-messages-tab">
                        <div class="row">
                            @if($cryptos->isNotEmpty())
                                @foreach($cryptos as $crypto)
                                    <div class="col-md-6 col-12 mt-3">
                                        <div class="main-stock-section" data-url="{{route('user.bond.details',[$crypto->slug])}}">


                                            <div class="tradingview-widget-container">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                                                    {
                                                        "symbol": "{{$crypto->stock_code}}",
                                                        "chartOnly": false,
                                                        "dateRange": "1D",
                                                        "noTimeScale": false,
                                                        "colorTheme": "dark",
                                                        "isTransparent": false,
                                                        "locale": "en",
                                                        "width": "100%",
                                                        "autosize": true,
                                                        "height": "100%"
                                                    }
                                                </script>
                                            </div>


                                        </div>
                                    </div>
                                @endforeach

                            @else

                                <div class="text-danger text-center mt-4">
                                    No Data Available
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="below-futures" role="tabpanel"
                         aria-labelledby="custom-content-above-settings-tab">
                        <div class="row">
                            @if($futures->isNotEmpty())
                                @foreach($futures as $future)
                                    <div class="col-md-6 col-12 mt-3">
                                        <div class="main-stock-section" data-url="{{route('user.bond.details',[$future->slug])}}">


                                            <div class="tradingview-widget-container">
                                                <div class="tradingview-widget-container__widget"></div>
                                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                                                    {
                                                        "symbol": "{{$future->stock_code}}",
                                                        "chartOnly": false,
                                                        "dateRange": "1D",
                                                        "noTimeScale": false,
                                                        "colorTheme": "dark",
                                                        "isTransparent": false,
                                                        "locale": "en",
                                                        "width": "100%",
                                                        "autosize": true,
                                                        "height": "100%"
                                                    }
                                                </script>
                                            </div>


                                        </div>
                                    </div>
                                @endforeach

                            @else

                                <div class="text-danger text-center mt-4">
                                    No Data Available
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    @if(!$stock_member)
        <div class="modal fade" id="stockMember" tabindex="-1" role="dialog" data-bs-backdrop="static"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{route('user.stock.member')}}" method="post">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5 class="text--dark">Are you sure you want to buy <b>Global Stock Membership</b>. It's
                                cost 20 USDT?</h5>
                            <small class="text--danger">If you want to buy stock, at first you need to purchase <b>Global
                                    Stock Membership</b></small>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--success">Confirm</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif


    <x-confirmation-modal isCustom="true"/>
@endsection

@push('topContent')
    <h4 class="mb-4">
        <span class="main-tit">Overviews</span>

        <a href="{{route('user.my.bonds')}}" class="btn btn-success my-stocks">My Overview</a>
    </h4>
@endpush


@push('ip-css')

    <style>
        .nav-link.active {
            background-color: #e1890a !important;
            border: 1px solid #e1890a !important;
        }
        #custom-content-above-tab .nav-link {
            padding: 5px 14px !important;
            color: white !important;
        }
        .custom-content-above-tab{
            border-bottom: 1px solid #353535 !important;
        }
        .tv-mini-symbol-overview__ticker {
            padding: 5px 10px !important;
            font-size: 10px !important;
        }

        .tv-ticker-item-last__short-name {
            color: #ffffff !important;
            font-size: 12px !important;
        }

        .tv-ticker-item-last__last {
            font-size: 16px !important;
        }

        .main-stock-section {
            min-height: 150px !important;
        }

        .tradingview-widget-container {
            height: auto !important;
            pointer-events: none !important;
        }

        .tv-ticker-item-last__last {
            font-size: 18px !important;
        }

        .tv-ticker-item-last__change-wrapper {
            font-size: 9px !important;
        }

        .tv-mini-symbol-overview__chart {
            height: calc(100% - 139px) !important;
        }

        .my-stocks {
            float: right;
            padding: 10px 20px;
        }

        .main-stock-section {
            display: flex;
            background: #6c6c6c54;
            border-radius: 32px;
            padding: 5px;
            cursor: pointer;
        }

        .stock-image img {
            height: 100%;
            width: 100%;
            border-radius: 50px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        .stock-image {
            height: 55px;
            width: 55px;
        }

        .stock-name {
            font-size: 18px;
            margin: auto;
            text-align: left;
            width: 89%;
            padding-left: 15px;
            font-weight: 500;
            color: white;
        }

        .stock-description {
            display: block;
            font-size: 12px;
        }

        @media (max-width: 700px) {
            .stock-main-section .main-stock-section .stock-name {
                color: #ffffff !important;
            }

            .stock-main-section .main-stock-section {
                background: #0f1016 !important;
                border-radius: 7px !important;
            }

            .stock-main-section {
                background: #08090ab0;
                padding-bottom: 20px;
                border-radius: 5px;
            }

            .stock-image {
                height: 40px !important;
                width: 40px !important;
            }

            .stock-name {
                font-size: 14px !important;
            }
            .tv-ticker-item-last__body{
                height: auto !important;
            }
        }
    </style>

@endpush



@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

@endpush


@push('script')

    <script>
        $(document).on('click', '.bondType', function (e){
            const type=$(this).attr('data-type');

            $('.my-stocks').text('My '+type)
            $('.main-tit').text(type)
        })
    </script>


    @if(!$stock_member)
        <script>
            "use strict";
            $(document).on('click', '.main-stock-section', function (e) {
                $('#stockMember').modal('show');
            })

        </script>
    @else

        <script>
            "use strict";
            $(document).on('click', '.main-stock-section', function (e) {
                const url = $(this).attr('data-url');

                location.href = url;
            })

        </script>
    @endif
@endpush
