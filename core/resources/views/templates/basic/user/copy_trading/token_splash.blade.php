@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>

        .icon {
            font-size: 14px;
            color: #fff;
        }

        .services-text {
            font-size: 11px;
            font-weight: 500;
            color: #fff;
        }

        .services-title {
            font-size: 13px;
            margin: 15px 0;
            color: #636060;
        }

        .other-data i {
            font-size: 16px;
            color: #fff;
        }

        .Launchpool {
            width: 100%;
            flex-shrink: 0;
            margin-bottom: 32px;
            background: url('{{asset('core/public/img/banner-bg (1).jpeg')}}');
            background-position: 50%;
            background-size: cover;
            overflow: hidden;
            background-color: #161923;
            border-radius: 5px;
            /*padding: 10px;*/
            padding-top: 30px !important;
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
            font-size: 24px;
            font-weight: 800;
            line-height: normal;
        }

        .launchpool-des {
            font-style: normal;
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            line-height: normal;
        }

        .Launchpool-content-text .text p {
            font-size: 13px;
            color: #717171;
        }

        .Launchpool-content .font {
            font-size: 13px;
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
            font-size: 15px;
            font-weight: 700;
            border-radius: 6px;
            padding: 6px 18px;
        }

        .btn-Share {
            color: rgb(247, 166, 0) !important;
            background: rgb(16, 16, 20) !important;
            border: 1px solid rgb(247, 166, 0) !important;
            font-size: 13px;
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
            font-size: 17px;
        }

        .launchpool-content-box .header .right i {
            color: #fff;
            font-size: 17px;
        }

        .launchpool-content-box .body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .body-title {
            font-size: 13px;
            color: #717171;
        }

        .body-des {
            font-size: 16px;
            font-weight: 700;
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
            font-size: 14px;
            color: #fff;
        }

        .contents-box .right .sub-title {
            font-size: 12px;
            color: #fff;
        }

        .referral-history {
            font-size: 14px;
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
            font-size: 18px;
            font-weight: 800;
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
            font-size: 13px;
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
            width: 44px;
            height: 44px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .product-data-content-section-box .header-title {
            font-size: 17px;
            font-weight: 700;
        }

        .product-data-content-section-box .header-subtitle {
            display: flex;
            justify-content: start;
        }

        .product-data-content-section-box .header-subtitle span {
            font-size: 11px;
            background-color: #f7f7005c;
            padding: 3px 8px 0px 8px;
            font-weight: 700;
            border-radius: 50px;
        }

        .product-data-content-section-box-body-top-border {
            border-top: 1px solid #d3d3d370;
            padding: 10px 5px;
        }

        .product-data-content-section-box-body-top-border .total-price {
            font-size: 12px;
            color: #717171;
        }

        .product-data-content-section-box-body-top-border .total-price-amount {
            font-size: 18px;
            font-weight: 700;
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
            font-weight: 700;
            line-height: 22px;
        }

        .ends-task-time .word {
            color: #adb1b8;
            font-size: 13px;
            font-weight: 600;
            margin-right: 2px;
        }

        .not-registered {
            color: #FFEB00;
            font-size: 12px;
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
                        <a href="{{route('user.token.splash.trading')}}">
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
                            <div class="col-8">
                                <div class="Launchpool-content-text">
                                    <div class="text">
                                        <div class="launchpool-title mb-2">Token Splash</div>
                                        <p>Deposit Trade and Earn Form Various Prize Pools</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="launchpool-img">
                                    <img src="{{asset('core/public/img/images-removebg-preview.png')}}" alt="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between Launchpool-content-datas">
                                    <div class="left p-1">
                                        <p class="font m-0">Total Prize Pool(USD)</p>
                                        <div class="launchpool-des">4,258,435</div>
                                    </div>
                                    <div class="right p-1">
                                        <p class="font m-0">Average Gains(USD)</p>
                                        <div class="launchpool-des">$47</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between Launchpool-content-datas">
                                    <div class="left p-1">
                                        <p class="font m-0">Total Products</p>
                                        <div class="launchpool-des">258</div>
                                    </div>
                                    <div class="right p-1">
                                        <p class="font m-0">Total Participants</p>
                                        <div class="launchpool-des">11,198,017</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-data">
            <div class="container">
                <div class="referral-history">
                    <a href="{{route('user.my.token.splash.trading')}}">
                        My Trade <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="product-data-content">
                <div class="container">
                    <div class="title">All Products</div>
                    <div class="sub-title">Deposit Trading and Earn Form Various Prize Pools</div>
                    <div class="product-data-content-section">

                        @if($trades->isNotEmpty())
                            @foreach($trades as $trade)
                                <div class="product-data-content-section-box">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ getImage(getFilePath('currency') .'/'.$trade->image,getFileSize('currency')) }}" alt="">
                                        <div class="text">
                                            <div class="header-title">
                                                {{$trade->name}}
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
                                                <div class="total-price-amount">{{$trade->total_prize}}</div>
                                            </div>
                                            <div class="right">
                                                <div class="total-price">Total Participants</div>
                                                <div class="total-price-amount">28,500</div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">

                                            @php
                                                $end_date=\Illuminate\Support\Carbon::parse($trade->end_date);
                                            @endphp
                                            <div class="">
                                                <div class="total-price text-left ">
                                                    Trading Task Ends in:
                                                </div>
                                                <div class="ends-task-time" id="countdown-{{$trade->id}}">
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

                                            <button class="btn btn-sm btn-Subscribe trade-copy-btn buyCopyTrade"
                                                    data-name="{{strtoupper($trade->name)}}" data-id="{{$trade->id}}">Join Now</button>

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
