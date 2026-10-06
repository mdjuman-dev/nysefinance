@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>
        /* Main Title */
        .launchpool-title {
            font-style: normal;
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            line-height: normal;
        }
        /* Subtitle */
        .launchpool-des {
            font-style: normal;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            line-height: normal;
        }
        /* Stats Numbers */
        .product-data-content-section-box-body-top-border .total-price-amount {
            font-size: 18px;
            font-weight: 700;
            color: #000;
        }
        /* Stats Labels */
        .product-data-content-section-box-body-top-border .total-price {
            font-size: 13px;
            color: #717171;
        }
        /* Section Titles */
        .product-data-content .title {
            font-size: 17px;
            font-weight: 800;
            text-align: center;
            position: relative;
        }
        /* Section Subtitles */
        .product-data-content .sub-title {
            font-size: 13px;
            margin-top: 7px;
            color: #717171;
        }
        /* Card/Project Titles */
        .product-data-content-section-box .header-title {
            font-size: 15px;
            font-weight: 700;
        }
        /* Card/Project Sub-labels */
        .product-data-content-section-box .header-subtitle span {
            font-size: 11px;
            background-color: #f7f7005c;
            padding: 3px 5px 0px 5px;
            font-weight: 700;
            border-radius: 50px;
        }
        /* Button Font Sizes */
        .btn-Subscribe, .btn-Share, .btn-edit, .btn-sm {
            font-size: 13px !important;
            font-weight: 600;
        }
        /* Small Text, Labels, and Misc */
        .services-text, .services-title, .body-title, .contents-box .right .sub-title, .not-registered, .referral-history {
            font-size: 11px;
        }
        .contents-box .right .title {
            font-size: 13px;
            color: #fff;
        }
        .ends-task-time .number {
            font-size: 15px;
            font-weight: 700;
        }
        .ends-task-time .word {
            color: #adb1b8;
            font-size: 13px;
            font-weight: 500;
            margin-right: 2px;
        }
        /* Adjust header title */
        .header-title h6.white {
            font-size: 22px;
            font-weight: 800;
        }
        /* Adjust referral link */
        .referral-history {
            font-size: 13px;
            font-weight: bold;
            color: rgb(247, 166, 0);
        }
        /* Adjust for mobile spacing if needed */
        @media (max-width: 600px) {
            .launchpool-title, .header-title h6.white {
                font-size: 20px;
            }
            .product-data-content .title {
                font-size: 15px;
            }
            .product-data-content-section-box .header-title {
                font-size: 13px;
            }
        }

        .icon {
            font-size: 13px;
            color: #fff;
        }

        .services-text {
            font-size: 10px;
            font-weight: 500;
            color: #fff;
        }

        .services-title {
            font-size: 11px;
            margin: 15px 0;
            color: #636060;
        }

        .other-data i {
            font-size: 13px;
            color: #fff;
        }

        .Launchpool {
            width: 100%;
            /* display: flex; */
            flex-shrink: 0;
            margin-bottom: 32px;
            padding-top: 30px;
            background: url('/img/banner-bg\ \(1\).jpeg');
            background-position: 50%;
            background-size: cover;
            overflow: hidden;
            background-color: #161923;
        }

        .Launchpool-content {
            height: 100%;
            justify-content: space-between;
            align-items: center;
        }

        .Launchpool-content-text {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 24px;
        }

        .Launchpool-content-text .text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }

        .launchpool-title {
            font-style: normal;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            line-height: normal;
        }

        .launchpool-des {
            font-style: normal;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            line-height: normal;
        }

        .Launchpool-content-text .text p {
            font-size: 11px;
            color: #717171;
        }

        .Launchpool-content .font {
            font-size: 10px;
            color: #717171;

        }

        .launchpool-img img {
            width: 100%;
            height: 100%;
        }

        .btn-Subscribe {
            background: rgb(247, 166, 0) !important;
            color: rgb(16, 16, 20) !important;
            border: none !important;
            font-size: 11px;
            font-weight: 600;
        }

        .btn-Share {
            color: rgb(247, 166, 0) !important;
            background: rgb(16, 16, 20) !important;
            border: 1px solid rgb(247, 166, 0) !important;
            font-size: 11px;
        }

        .launchpool-content-box {
            border: 1px solid #636060;
            border-radius: 7px;
            padding: 20px 10px 10px 10px;
            background-color: #31313d61;
        }

        .launchpool-content-box .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .launchpool-content-box .header .left img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .launchpool-content-box .header .left span {
            color: #fff;
            font-size: 15px;
        }

        .launchpool-content-box .header .right i {
            color: #fff;
            font-size: 15px;
        }

        .launchpool-content-box .body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .body-title {
            font-size: 11px;
            color: #717171;
        }

        .body-des {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .contents-box {
            border-bottom: 1px solid #161923;
            padding: 10px 5px;
        }

        .contents-box:last-child {
            border-bottom: none;
        }

        .contents-box .left img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .contents-box {
            display: flex;
            align-items: center;
        }

        .contents-box .right .title {
            font-size: 12px;
            color: #fff;
        }

        .contents-box .right .sub-title {
            font-size: 10px;
            color: #fff;
        }

        .referral-history {
            font-size: 11px;
            font-weight: bold;
            color: rgb(247, 166, 0);
        }

        .Launchpool-content-datas .right .launchpool-des {
            display: flex;
            justify-content: end;
        }

        .product-data-content {
            text-align: center;
            margin-top: 10px;
            background-color: #fff;
            padding: 20px 0px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .product-data-content .title {
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            position: relative;
        }

        .product-data-content .title::before,
        .product-data-content .title::after {
            content: '';
            position: absolute;
            top: 55%;
            width: 8%;
            height: 1px;
            background-color: #000;
        }

        .product-data-content .title::before {
            right: 65%;
            transform: translateY(-50%);
        }

        .product-data-content .title::after {
            left: 65%;
            transform: translateY(-50%);
        }

        .product-data-content .sub-title {
            font-size: 11px;
            margin-top: 7px;
            color: #717171;
        }

        .product-data-content-section {
            margin: 20px 0px;
        }

        .product-data-content-section-box {
            border-top: 4px solid rgb(247, 166, 0);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
            padding: 20px 5px 0px 5px;
            background-color: #f3f5f940;
            margin: 14px 0px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-data-content-section-box img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .product-data-content-section-box .header-title {
            font-size: 15px;
            font-weight: 600;
        }

        .product-data-content-section-box .header-subtitle {
            display: flex;
            justify-content: start;
        }

        .product-data-content-section-box .header-subtitle span {
            font-size: 9px;
            background-color: #f7f7005c;
            padding: 3px 5px 0px 5px;
            font-weight: 700;
            border-radius: 50px;
        }

        .product-data-content-section-box-body-top-border {
            border-top: 1px solid #d3d3d370;
            padding: 10px 5px;
        }

        .product-data-content-section-box-body-top-border .total-price {
            font-size: 11px;
            color: #717171;
        }

        .product-data-content-section-box-body-top-border .total-price-amount {
            font-size: 14px;
            font-weight: 600;
            color: #000;
        }

        .product-data-content-section-box-body-top-border .left .total-price-amount {
            text-align: left;
        }

        .product-data-content-section-box-body-top-border .right .total-price-amount {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .ends-task-time {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .ends-task-time .number {
            border-radius: 2px;
            background: rgba(192, 210, 231, .12);
            padding: 0 4px;
            height: 20px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            line-height: 22px;
        }

        .ends-task-time .word {
            color: #adb1b8;
            font-size: 14px;
            font-weight: 500;
            margin-right: 2px;
        }

        .not-registered {
            color: #FFEB00;
            font-size: 11px;
        }

        .main-section {
            margin-bottom: 0;
        }
    </style>

@endpush

@section('content')

    <header class="header-section">
        <div class="container">
            <div class="header-section-content">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="right-back-btn">
                        <a href="{{route('user.home')}}" class="right-back-action">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="header-title">
                        <h6 class="white">Token Splash</h6>
                    </div>
                    <div class="other-data">
                        <a href="/">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="Launchpool">
                        <div class="Launchpool-content row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-data">
            <div class="container">
                <div class="referral-history">
                    <a href="{{route('user.token.splash.trading')}}">
                        All Token Splash <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="product-data-content">
                <div class="container">
                    <div class="title">All Products</div>
                    <div class="sub-title">Deposit Trading and Earn Form Various Prize Pools</div>
                    <div class="product-data-content-section">

                        @if($token_splash_trades->isNotEmpty())
                            @foreach($token_splash_trades as $token_splash_trade)
                                <div class="product-data-content-section-box">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ getImage(getFilePath('currency') .'/'.$token_splash_trade->trade->image,getFileSize('currency')) }}" alt="">
                                        <div class="text">
                                            <div class="header-title">
                                                {{$token_splash_trade->trade->name}}
                                            </div>
                                            <div class="header-subtitle">
                                                <span> <i class="fa-solid fa-coins"></i> Refer & Earn </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-data-content-section-box-body-top-border mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="left">
                                                <div class="total-price">Total Prize Pool (ELX)</div>
                                                <div class="total-price-amount">{{$token_splash_trade->trade->total_prize}}</div>
                                            </div>
                                            <div class="right">
                                                <div class="total-price">Total Participants</div>
                                                <div class="total-price-amount">28,500</div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">

                                            @php
                                                $end_date=\Illuminate\Support\Carbon::parse($token_splash_trade->trade->end_date);
                                            @endphp
                                            <div class="">
                                                <div class="total-price text-left ">
                                                    Trading Task Ends in:
                                                </div>
                                                <div class="ends-task-time" id="countdown-{{$token_splash_trade->id}}">
                                                    <div class="number">0</div>
                                                    <div class="word">D</div>
                                                    <div class="number">0</div>
                                                    <div class="word">H</div>
                                                    <div class="number">0</div>
                                                    <div class="word">M</div>
                                                    <div class="number">0</div>
                                                    <div class="word">S</div>
                                                </div>
                                            </div>

                                            @php
                                                $start_date=\Illuminate\Support\Carbon::parse($token_splash_trade->trade->start_date);
                                            @endphp
                                            @if($token_splash_trade->status=='buy' &&  $start_date < now())
                                                <button class="btn btn-edit btn-sm trade-copy-btn withdrawCopyTrade"
                                                        data-name="{{strtoupper($token_splash_trade->trade->name)}}"
                                                        data-id="{{$token_splash_trade->id}}">Withdraw</button>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="product-data-content-section-box-body-top-border mt-2">
                                        <div class="d-flex justify-content-end align-items-center">
                                            <div class="not-registered"><i class="fa-regular fa-lightbulb me-2"></i>Not
                                                Registered
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        @else
                            <div class="text-center pt-4 pb-4">
                                <h5 class="text-center">No Trade Found</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>


    @include('templates.basic.user.copy_trading.includes.modal')
@endsection

@push('script')
    @include('templates.basic.user.copy_trading.includes.modal_js')

    <script>
        function updateCountdown(endDate, elementId) {
            const countdownElement = document.getElementById(elementId);
            const numbers = countdownElement.getElementsByClassName('number');

            function update() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    clearInterval(interval);
                    numbers[0].textContent = '0';
                    numbers[1].textContent = '0';
                    numbers[2].textContent = '0';
                    numbers[3].textContent = '0';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                numbers[0].textContent = days;
                numbers[1].textContent = hours;
                numbers[2].textContent = minutes;
                numbers[3].textContent = seconds;
            }

            const interval = setInterval(update, 1000);
            update(); // Initial call
        }

        // Initialize countdowns for all trades
        @foreach($trades as $trade)
        const endDate{{$trade->id}} = new Date('{{$trade->end_date}}').getTime();
        updateCountdown(endDate{{$trade->id}}, 'countdown-{{$trade->id}}');
        @endforeach
    </script>
@endpush
