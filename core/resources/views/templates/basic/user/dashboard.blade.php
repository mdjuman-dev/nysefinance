@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">
        <div class=" col-xxl-9 col-lg-12">
            <div class="row gy-3">

{{--                <div class="col-12 sec-sliders d-none mb-3 mt-3">--}}
{{--                    <div id="owl-demo" class="owl-carousel owl-theme">--}}
{{--                        <div class="carousel-image item"><img src="{{asset('core/public/slider/1.jpg')}}"></div>--}}
{{--                        <div class="carousel-image item"><img src="{{asset('core/public/slider/2.jpg')}}"></div>--}}
{{--                        <div class="carousel-image item"><img src="{{asset('core/public/slider/3.jpg')}}"></div>--}}
{{--                        <div class="carousel-image item"><img src="{{asset('core/public/slider/4.jpg')}}"></div>--}}
{{--                        <div class="carousel-image item"><img src="{{asset('core/public/slider/5.jpg')}}"></div>--}}
{{--                        <div class="carousel-image item"><img src="{{asset('core/public/slider/6.jpg')}}"></div>--}}
{{--                        <div class="carousel-image item"><img src="{{asset('core/public/slider/7.jpg')}}"></div>--}}

{{--                    </div>--}}

{{--                </div>--}}

                <div class="col-12 ticker-tape-widget mb-3">
                    <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                            {
                                "symbols": [
                                {
                                    "proName": "FOREXCOM:SPXUSD",
                                    "title": "S&P 500 Index"
                                },
                                {
                                    "proName": "FOREXCOM:NSXUSD",
                                    "title": "US 100 Cash CFD"
                                },
                                {
                                    "proName": "FX_IDC:EURUSD",
                                    "title": "EUR to USD"
                                },
                                {
                                    "proName": "BITSTAMP:BTCUSD",
                                    "title": "Bitcoin"
                                },
                                {
                                    "proName": "BITSTAMP:ETHUSD",
                                    "title": "Ethereum"
                                }
                            ],
                                "showSymbolLogo": true,
                                "isTransparent": false,
                                "displayMode": "regular",
                                "colorTheme": "dark",
                                "locale": "en"
                            }
                        </script>
                    </div>
                    <!-- TradingView Widget END -->
                </div>


                @php
                    $kycContent = getContent('kyc_content.content', true);
                @endphp

                @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                    <div class="col-12">
                        <div class="alert alert--danger skeleton" role="alert">
                            <div class="flex-align justify-content-between">
                                <h5 class="alert-heading text--danger mb-2">@lang('KYC Documents Rejected')</h5>
                                <button data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                            </div>
                            <p class="mb-0">
                                {{ __(@$kycContent->data_values->rejection_content) }}
                                <a href="{{ route('user.kyc.data') }}" class="text--base">@lang('See KYC Data')</a>
                            </p>
                        </div>
                    </div>
                @endif
{{--                @if ($user->kv == Status::KYC_UNVERIFIED && !$user->kyc_rejection_reason)--}}
{{--                    <div class="col-12">--}}
{{--                        <div class="alert alert--danger skeleton" role="alert">--}}
{{--                            <h5 class="alert-heading text--danger mb-2">@lang('KYC Verification Required')</h5>--}}
{{--                            <p class="mb-0">--}}
{{--                                {{ __(@$kycContent->data_values->unverified_content) }}--}}
{{--                                <a href="{{ route('user.kyc.form') }}" class="text--base">@lang('Click here to verify')</a>--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
                @if ($user->kv == Status::KYC_PENDING)
                    <div class="col-12">
                        <div class="alert alert--warning flex-column justify-content-start align-items-start skeleton" role="alert">
                            <h5 class="alert-heading text--warning mb-2">@lang('KYC Verification Pending')</h5>
                            <p class="mb-0"> {{ __(@$kycContent->data_values->pending_content) }}
                                <a href="{{ route('user.kyc.data') }}" class="text--base">@lang('See KYC Data')</a>
                            </p>
                        </div>
                    </div>
                @endif
                @if (!$user->ts)
                    <div class="col-12 d-none">
                        <div class="alert-item 2fa-notice skeleton">
                            <span class="delete-icon skeleton" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                <i class="las la-times"></i></span>
                            <div class="alert flex-align alert--danger remove-2fa-notice" role="alert">
                                <span class="alert__icon">
                                    <i class="fas fa-exclamation"></i>
                                </span>
                                <div class="alert__content">
                                    <span class="alert__title">
                                        @lang('To secure your account add 2FA verification').
                                        <a href="{{ route('user.twofactor') }}" class="text--base text--small">@lang('Enable')</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-12">


                    <div class="dashboard-card-wrapper">
                        <div class="row">
                            <div class="col-md-12 mt-3-mb-3 d-flex">
                                <marquee style="color: #d7731d;font-size: 20px;font-weight: 600;">
                                    NyseFinance Exchange is a Best Crypto & Stock Pool. &nbsp;&nbsp;&nbsp; |&nbsp; &nbsp; &nbsp;
                                    Here you can buy the best company stocks in the world.&nbsp; &nbsp; &nbsp;| &nbsp; &nbsp; &nbsp;Favorite Stocks Buy Meta,Google,Apple, Microsoft Others     |    Buy the best Crypto and win volume for your account.</marquee>
                            </div>
                        </div>

                        <div class="row section-stock-trade-view">
                            <div class="col-md-12 sub-small-icon-section">

                                <div class="row">
                                    <div class="col-3 sm-icon-main-section">
                                        <div class="sec-sm-icons" data-url="{{ route('trade') }}">
                                            <i class="fa fa-line-chart"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Trade
                                        </small>
                                    </div>

                                    <div class="col-3 sm-icon-main-section">
                                        <div class="sec-sm-icons" data-url="{{ route('user.stock.index') }}">
                                            <i class="fa fa-bar-chart"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Stocks
                                        </small>
                                    </div>

                                    <div class="col-3 sm-icon-main-section">
                                        <div class="sec-sm-icons" data-url="{{ route('user.referrals') }}">
                                            <i class="fa fa-user-plus"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Referrals
                                        </small>
                                    </div>

                                    <div class="col-3 sm-icon-main-section">
                                        <div class="sec-sm-icons" data-url="{{ route('user.order.open') }}">
                                            <i class="fa fa-first-order"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Orders
                                        </small>
                                    </div>

                                    <div class="col-3 sm-icon-main-section">
                                        <div class="sec-sm-icons" data-url="{{ route('user.transactions') }}">
                                            <i class="fa fa-list"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Transaction
                                        </small>
                                    </div>

                                    <div class="col-3 sm-icon-main-section mt-4">
                                        <div class="sec-sm-icons" data-url="{{ route('ticket.index') }}">
                                            <i class="fa fa-question-circle"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Support
                                        </small>
                                    </div>

                                    @if(checkAgent())
                                        <div class="col-3 sm-icon-main-section mt-4">
                                            <div class="sec-sm-icons" data-url="{{ route('user.p2p.dashboard') }}">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <small class="icon-t-section">
                                                P2P
                                            </small>
                                        </div>
                                    @else
                                        <div class="col-3 sm-icon-main-section mt-4">
                                            <div class="sec-sm-icons" data-url="{{ route('p2p') }}">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <small class="icon-t-section">
                                                P2P
                                            </small>
                                        </div>
                                    @endif
                                    <div class="col-3 sm-icon-main-section mt-4">
                                        <div class="sec-sm-icons" data-url="{{ route('user.twofactor') }}">
                                            <i class="fa fa-lock"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Security
                                        </small>
                                    </div>
                                    <div class="col-3 sm-icon-main-section mt-4">
                                        <div class="sec-sm-icons toggle-dashboard-right">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Deposit
                                        </small>
                                    </div>

                                    <div class="col-3 sm-icon-main-section mt-4">
                                        <div class="sec-sm-icons" data-url="https://www.livecoinwatch.com">
                                            <i class="fa fa-coins"></i>
                                        </div>
                                        <small class="icon-t-section">
                                            Coin
                                        </small>
                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12 col-12 mb-3 single-weidgh-hart">
                                <div class="livecoinwatch-widget-6" lcw-coin="BTC" lcw-base="USD" lcw-period="d" lcw-color-tx="#ffffff" lcw-color-bg="#1f2434" lcw-border-w="1" ></div>
                                <div class="livecoinwatch-widget-6" lcw-coin="SOL" lcw-base="USD" lcw-period="d" lcw-color-tx="#ffffff" lcw-color-bg="#1f2434" lcw-border-w="1" ></div>
                                <div class="livecoinwatch-widget-6" lcw-coin="ETH" lcw-base="USD" lcw-period="d" lcw-color-tx="#ffffff" lcw-color-bg="#1f2434" lcw-border-w="1" ></div>
                                <div class="livecoinwatch-widget-6" lcw-coin="BNB" lcw-base="USD" lcw-period="d" lcw-color-tx="#ffffff" lcw-color-bg="#1f2434" lcw-border-w="1" ></div>
                                <div class="livecoinwatch-widget-6" lcw-coin="XRP" lcw-base="USD" lcw-period="d" lcw-color-tx="#ffffff" lcw-color-bg="#1f2434" lcw-border-w="1" ></div>
                                <div class="livecoinwatch-widget-6" lcw-coin="DOGE" lcw-base="USD" lcw-period="d" lcw-color-tx="#ffffff" lcw-color-bg="#1f2434" lcw-border-w="1" ></div>

                            </div>
                            <div class="col-md-12">
                                <div class="view-chart-section">
                                    <div class="tradingview-widget-container">
                                        <div class="tradingview-widget-container__widget"></div>
                                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
                                            {
                                                "colorTheme": "dark",
                                                "dateRange": "12M",
                                                "showChart": false,
                                                "locale": "en",
                                                "largeChartUrl": "",
                                                "isTransparent": false,
                                                "showSymbolLogo": true,
                                                "showFloatingTooltip": false,
                                                "width": "100%",
                                                "height": "420",
                                                "tabs": [
                                                {
                                                    "title": "Stocks",
                                                    "symbols": [
                                                        {
                                                            "s": "NASDAQ:META"
                                                        },
                                                        {
                                                            "s": "NASDAQ:GOOGL"
                                                        },
                                                        {
                                                            "s": "NASDAQ:AAPL"
                                                        },
                                                        {
                                                            "s": "NASDAQ:MSFT"
                                                        },
                                                        {
                                                            "s": "NASDAQ:AMZN"
                                                        },
                                                        {
                                                            "s": "NASDAQ:TSLA"
                                                        },
                                                        {
                                                            "s": "GPW:VISA"
                                                        },
                                                        {
                                                            "s": "NYSE:BABA"
                                                        },
                                                        {
                                                            "s": "NYSE:AGL"
                                                        },
                                                        {
                                                            "s": "NYSE:FVRR"
                                                        },
                                                        {
                                                            "s": "AMEX:BEEP"
                                                        },
                                                        {
                                                            "s": "FWB:JAN"
                                                        },
                                                        {
                                                            "s": "NASDAQ:INTC"
                                                        },
                                                        {
                                                            "s": "GETTEX:SUK"
                                                        },
                                                        {
                                                            "s": "NYSE:CAT"
                                                        },
                                                        {
                                                            "s": "MIL:1NKE"
                                                        }
                                                    ],
                                                    "originalTitle": "Indices"
                                                },
                                                {
                                                    "title": "Market",
                                                    "symbols": []
                                                }
                                            ]
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-mob-view">
                            <div class="row gy-4 mb-3 justify-content-center">
                                <div class="col-xxl-3 col-sm-6">
                                    <div class="dashboard-card skeleton">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <span class="dashboard-card__icon text--base">
                                            <i class="las la-spinner"></i>
                                        </span>
                                            <div class="dashboard-card__content">
                                                <a href="{{ route('user.order.open') }}" class="dashboard-card__coin-name mb-0 ">
                                                    @lang('Open Order') </a>
                                                <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['open_order']) }} </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6">
                                    <div class="dashboard-card skeleton">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <span class="dashboard-card__icon text--success">
                                            <i class="las la-check-circle"></i>
                                        </span>
                                            <div class="dashboard-card__content">
                                                <a href="{{ route('user.order.completed') }}" class="dashboard-card__coin-name mb-0">
                                                    @lang('Completed Order') </a>
                                                <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['completed_order']) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6">
                                    <div class="dashboard-card skeleton">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <span class="dashboard-card__icon text--danger">
                                            <i class="las la-times-circle"></i>
                                        </span>
                                            <div class="dashboard-card__content">
                                                <a href="{{ route('user.order.canceled') }}" class="dashboard-card__coin-name mb-0 ">
                                                    @lang('Canceled Order') </a>
                                                <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['canceled_order']) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6">
                                    <div class="dashboard-card skeleton">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <span class="dashboard-card__icon text--base">
                                            <span class="icon-trade fs-50"></span>
                                        </span>
                                            <div class="dashboard-card__content">
                                                <a href="{{ route('user.trade.history') }}" class="dashboard-card__coin-name mb-0">@lang('Total Trade') </a>
                                                <h6 class="dashboard-card__coin-title"> {{ getAmount($widget['total_trade']) }} </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row gy-4 mb-3 justify-content-center recent-tran-sec">
                            <div class="col-lg-6">
                                <div class="transection h-100">
                                    <h5 class="transection__title skeleton"> @lang('Recent Order') </h5>
                                    @forelse ($recentOrders as $recentOrder)
                                        <div class="transection__item skeleton">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="transection__date">
                                                    <h6 class="transection__date-number text-white">
                                                        {{ showDateTime($recentOrder->created_at, 'd') }}
                                                    </h6>
                                                    <span class="transection__date-text">
                                                        {{ __(strtoupper(showDateTime($recentOrder->created_at, 'M'))) }}
                                                    </span>
                                                </div>
                                                <div class="transection__content">
                                                    <h6 class="transection__content-title">
                                                        @php echo $recentOrder->orderSideBadge; @endphp
                                                    </h6>
                                                    <p class="transection__content-desc">
                                                        @lang('Placed an order in the ')
                                                        {{ @$recentOrder->pair->symbol }} @lang('pair to')
                                                        {{ __(strtolower(strip_tags($recentOrder->orderSideBadge))) }}
                                                        {{ showAmount($recentOrder->amount, currencyFormat: false) }}
                                                        {{ @$recentOrder->pair->coin->symbol }}
                                                    </p>
                                                </div>
                                            </div>
                                            @php echo $recentOrder->statusBadge; @endphp
                                        </div>
                                    @empty
                                        <div class="transection__item justify-content-center p-5 skeleton">
                                            <div class="empty-thumb text-center">
                                                <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                                                <p class="fs-14">@lang('No order found')</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="transection h-100">
                                    <h5 class="transection__title skeleton"> @lang('Recent Transactions') </h5>
                                    @forelse ($recentTransactions as $recentTransaction)
                                        <div class="transection__item skeleton">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="transection__date">
                                                    <h6 class="transection__date-number text-white">
                                                        {{ showDateTime($recentTransaction->created_at, 'd') }}
                                                    </h6>
                                                    <span class="transection__date-text">
                                                        {{ __(strtoupper(showDateTime($recentTransaction->created_at, 'M'))) }}
                                                    </span>
                                                </div>
                                                <div class="transection__content">
                                                    <h6 class="transection__content-title">
                                                        {{ __(ucwords(keyToTitle($recentTransaction->remark))) }}
                                                    </h6>
                                                    <p class="transection__content-desc">
                                                        {{ __($recentTransaction->details) }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if ($recentTransaction->trx_type == '+')
                                                <span class="badge badge--success">
                                                    @lang('Plus')
                                                </span>
                                            @else
                                                <span class="badge badge--danger">
                                                    @lang('Minus')
                                                </span>
                                            @endif

                                        </div>
                                    @empty
                                        <div class="transection__item justify-content-center p-5 skeleton">
                                            <div class="empty-thumb text-center">
                                                <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                                                <p class="fs-14">@lang('No transactions found')</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Blogs Section -->
            <div class="row gy-4 mb-3 justify-content-center">
                <div class="col-12">
                    <div class="transection h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="transection__title skeleton">@lang('Recent Posts')</h5>
                        </div>

                        <div class="blogs-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                            @forelse($recentBlogs as $blog)
                                <x-blog-card :blog="$blog" />
                            @empty
                                <div class="text-center p-5">
                                    <div class="empty-thumb">
                                        <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                                        <p class="fs-14">@lang('No blog posts yet')</p>
                                        <a href="{{ route('user.blog.create') }}" class="btn btn--base mt-2">@lang('Create First Post')</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        </div>
        <div class=" col-xxl-3">
            <div class="dashboard-right">
                <div class="right-sidebar">
                    <div class="right-sidebar__header mb-3 skeleton">
                        <div class="d-flex flex-between flex-wrap">
                            <div>
                                <h4 class="mb-0 fs-18">@lang('Wallet Overview')</h4>
                                <p class="mt-0 fs-12">@lang('Available wallet balance including the converted total balance')</p>
                            </div>
                            <span class="toggle-dashboard-right dashboard--popup-close"><i class="las la-times"></i></span>
                        </div>
                    </div>
                    <div class="text-center mb-3 skeleton">
                        <h3 class="right-sidebar__number mb-0 pb-0">
                            {{ showAmount($estimatedBalance) }}
                        </h3>
                        <span class="fs-14 mt-0">@lang('Estimated Total Balance')</span>
                    </div>
                    <div class="right-sidebar__menu ">
                        <div class="wallet-wrapper">
                            @forelse ($wallets as $wallet)
                                <div class="right-sidebar__item flex-wrap wallet-list skeleton">
                                    <div class="d-flex align-items-center">
                                        <span class="right-sidebar__item-icon">
                                            <img src="{{ @$wallet->currency->image_url }}">
                                        </span>
                                        <h6 class="right-sidebar__item-name">
                                            {{ strLimit(@$wallet->currency->name, 10) }}
                                            <span class="fs-11 d-block">
                                                {{ @$wallet->currency->symbol }}
                                            </span>
                                        </h6>
                                    </div>
                                    <h6 class="right-sidebar__item-number"> {{ showAmount($wallet->balance, currencyFormat: false) }} </h6>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <button type="button" class="w-100 show-more-wallet right-sidebar__button skeleton mt-2">
                            <span class="right-sidebar__button-icon">
                                <i class="las la-chevron-circle-down"></i>@lang('Show More')
                            </span>
                        </button>
                    </div>
                </div>
                <div class="right-sidebar mt-3">
                    <div class="right-sidebar__header mb-3 skeleton">
                        <h4 class="mb-0 fs-18">@lang('Deposit Money')</h4>
                        <p class="mt-0 fs-12">@lang('Make crypto & fiat deposits in a few steps')</p>
                    </div>
                    <div class="right-sidebar__deposit custom-select2">
                        <form class="skeletons deposit-forms" method="post" action="{{ route('user.deposit.insert') }}">
                            @csrf
                            <div class="form-group position-relative" id="currency_list_wrapper">
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form--control form-control"
                                        placeholder="@lang('Amount')">
                                    <div class="input-group-text skeleton">
                                       &nbsp; USDT &nbsp;
{{--                                        <x-currency-list :action="route('user.currency.all')" valueType="2" logCurrency="true" />--}}
                                    </div>
                                </div>
                            </div>
{{--                            <button class="deposit__button btn btn--base w-100 click-dps-btn" type="submit">--}}
{{--                                <span class="icon-deposit"></span> @lang('Deposit')--}}
{{--                            </button>--}}
                            <button class="btn btn--base w-100" type="submit">
                                <span class="icon-deposit"></span> @lang('Deposit')
                            </button>
                        </form>
                    </div>
                </div>
                <div class="right-sidebar mt-3">
                    <div class="right-sidebar__header mb-3 skeleton">
                        <h4 class="mb-0 fs-18">@lang('Withdraw Money')</h4>
                        <p class="mt-0 fs-12">@lang('Withdrawal your balance with our world-class withdrawal process')</p>
                    </div>
                    <div class="right-sidebar__deposit">
                        <form class="skeleton withdraw-form custom-select2">
                            <div class="form-group position-relative" id="withdraw_currency_list_wrapper">
                                <div class="input-group">
                                    <input type="number" name="amount" step="any" class="form--control form-control"
                                        placeholder="@lang('Amount')">
                                    <div class="input-group-text skeleton">
                                        <x-currency-list :action="route('user.currency.all')" id="withdraw_currency_list" parent="withdraw_currency_list_wrapper"
                                            valueType="2" logCurrency="true" />
                                    </div>
                                </div>
                            </div>
                            <button class="deposit__button btn btn--base w-100" type="submit">
                                <span class="icon-withdraw"></span> @lang('Withdraw')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-flexible-view :view="$activeTemplate . 'user.components.canvas.deposit'" :meta="['gateways' => $gateways]" />
    <x-flexible-view :view="$activeTemplate . 'user.components.canvas.withdraw'" :meta="['withdrawMethods' => $withdrawMethods]" />

    <!-- Recent Blog Posts Section -->
    <div class="col-12 mt-4">
        <div class="card custom--card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">@lang('Recent Blog Posts')</h5>
                    <a href="{{ route('user.blog.all') }}" class="btn btn-sm btn--primary">
                        <i class="fas fa-eye"></i> @lang('View All')
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentBlogs->count() > 0)
                    <div class="row">
                        @foreach($recentBlogs as $blog)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        @if($blog->image)
                                            <div class="blog-image mb-2">
                                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover;">
                                            </div>
                                        @endif
                                        <h6 class="card-title">
                                            <a href="{{ route('user.blog.show', $blog->slug) }}" class="text-decoration-none">
                                                {{ Str::limit($blog->title, 40) }}
                                            </a>
                                        </h6>
                                        <p class="card-text text-muted small">
                                            {{ $blog->excerpt }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> {{ $blog->user->fullname }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-heart"></i> {{ $blog->likes }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-blog fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">@lang('No blog posts available yet.')</p>
                        <a href="{{ route('user.blog.create') }}" class="btn btn-sm btn--primary mt-2">
                            <i class="fas fa-plus"></i> @lang('Create First Post')
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
        <div class="modal fade custom--modal" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <p>{{ auth()->user()->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script defer src="https://www.livecoinwatch.com/static/lcw-widget.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" ></script>
@endpush

@push('style-lib')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
@push('script')
    <script>
        "use strict";


        // $(document).on('click', '.small-menu-section', function (e){
        //     e.preventDefault();
        //
        //
        // })


        $(document).ready(function(){

            $("#owl-demo").owlCarousel({
                navigation : true,
                slideSpeed : 300,
                paginationSpeed : 400,
                items : 1,
                itemsDesktop : false,
                itemsDesktopSmall : false,
                itemsTablet: false,
                itemsMobile : false,
                autoplay: true,
                onInitialized: function() {
                    $("#owl-demo").css("visibility", "visible");
                }
            });


            setTimeout(
                $('.sec-sliders').removeClass('d-none')
            , 1000)
        });

        // Blog functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing blog features...');

            // Check if CSRF token is available
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                console.log('CSRF token found:', csrfToken.getAttribute('content'));
            } else {
                console.error('CSRF token not found!');
            }

            // Check if blog cards exist
            const blogCards = document.querySelectorAll('.blog-card');
            console.log('Found blog cards:', blogCards.length);

            // Blog functionality
            initializeBlogFeatures();
        });

        function initializeBlogFeatures() {
            console.log('Initializing blog features...');

            // Like functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.like-btn')) {
                    console.log('Like button clicked');
                    const likeBtn = e.target.closest('.like-btn');
                    const blogId = likeBtn.dataset.blogId;
                    const likeCount = likeBtn.querySelector('.like-count');
                    const heartIcon = likeBtn.querySelector('i');

                    if (heartIcon.classList.contains('text-danger')) {
                        // Unlike
                        fetch(`/user/blog/${blogId}/unlike`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                likeCount.textContent = data.likes;
                                heartIcon.classList.remove('text-danger');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    } else {
                        // Like
                        fetch(`/user/blog/${blogId}/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                likeCount.textContent = data.likes;
                                heartIcon.classList.add('text-danger');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                }
            });

            // Comment toggle functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.comment-btn')) {
                    console.log('Comment button clicked');
                    const commentBtn = e.target.closest('.comment-btn');
                    const blogId = commentBtn.dataset.blogId;
                    const commentsSection = document.getElementById(`comments-${blogId}`);

                    if (commentsSection.style.display === 'none') {
                        commentsSection.style.display = 'block';
                        commentBtn.style.color = '#f99c26';
                    } else {
                        commentsSection.style.display = 'none';
                        commentBtn.style.color = '#888';
                    }
                }
            });

            // Add comment functionality
            // document.addEventListener('click', function(e) {
            //     if (e.target.closest('.comment-submit-btn')) {
            //         console.log('Comment submit button clicked');
            //         const submitBtn = e.target.closest('.comment-submit-btn');
            //         const blogId = submitBtn.dataset.blogId;
            //         const commentInput = document.querySelector(`.comment-input[data-blog-id="${blogId}"]`);
            //         const commentText = commentInput.value.trim();

            //         console.log('Blog ID:', blogId, 'Comment text:', commentText);

            //         if (!commentText) return;

            //         fetch(`/user/blog/${blogId}/comment`, {
            //             method: 'POST',
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            //                 'Content-Type': 'application/json',
            //             },
            //             body: JSON.stringify({
            //                 comment: commentText
            //             })
            //         })
            //         .then(response => {
            //             console.log('Response status:', response.status);
            //             return response.json();
            //         })
            //         .then(data => {
            //             console.log('Response data:', data);
            //             if (data.success) {
            //                 // Add comment to the list
            //                 addCommentToDOM(blogId, data.comment);
            //                 commentInput.value = '';

            //                 // Update comment count
            //                 const commentCount = document.querySelector(`.comment-btn[data-blog-id="${blogId}"] .comment-count`);
            //                 commentCount.textContent = parseInt(commentCount.textContent) + 1;
            //             }
            //         })
            //         .catch(error => console.error('Error:', error));
            //     }
            // });

            // Delete comment functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.comment-delete-btn')) {
                    const deleteBtn = e.target.closest('.comment-delete-btn');
                    const commentId = deleteBtn.dataset.commentId;
                    const commentItem = deleteBtn.closest('.comment-item');
                    const blogId = commentItem.closest('.blog-card').dataset.blogId;

                    if (confirm('Are you sure you want to delete this comment?')) {
                        fetch(`/user/blog/comment/${commentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                commentItem.remove();

                                // Update comment count
                                const commentCount = document.querySelector(`.comment-btn[data-blog-id="${blogId}"] .comment-count`);
                                commentCount.textContent = parseInt(commentCount.textContent) - 1;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                }
            });
        }

        function addCommentToDOM(blogId, commentData) {
            const commentsList = document.getElementById(`comments-list-${blogId}`);
            const commentItem = document.createElement('div');
            commentItem.className = 'comment-item';
            commentItem.dataset.commentId = commentData.id;

            const avatar = commentData.user.image ?
                `<img src="${commentData.user.image}" alt="${commentData.user.username}">` :
                `<div class="comment-avatar-placeholder">${commentData.user.username.charAt(0).toUpperCase()}</div>`;

            commentItem.innerHTML = `
                <div class="comment-avatar">
                    ${avatar}
                </div>
                <div class="comment-content">
                    <div class="comment-header">
                        <span class="comment-username">${commentData.user.username}</span>
                        <span class="comment-timestamp">Just now</span>
                    </div>
                    <p class="comment-text">${commentData.comment}</p>
                    <button class="comment-delete-btn" data-comment-id="${commentData.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            commentsList.appendChild(commentItem);
        }


    </script>
@endpush


@push('topContent')

@endpush

@push('style')
    <style>
        .select2-image {
            max-width: 50px;
        }
        .slick-arrow{
            display: none !important;
        }
        .carousel-image img{
            width: 100%;
            height: 100%;
        }
        .carousel-image{
            height: 115px;
            width: 100%;
        }
        .currency-font{

        }

    </style>
@endpush
