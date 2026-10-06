@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>

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
            background: url('{{asset('core/public/img/banner-bg\ \(1\).jpeg')}}');
            background-position: 50%;
            background-size: cover;
            overflow: hidden;
            background-color: #161923;
            padding: 20px;
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

        .Launchpool-content-text p {
            font-size: 12px;
            color: #a9a8a8;
        }

        .launchpool-title {
            font-style: normal;
            color: #fff;
            font-size: 18px;
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
            background-color: #16171a;
            border: 1px solid #25282c;
            padding: 20px 0px;
        }

        .product-data-content .title {
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            position: relative;
            color: #fff;
        }

        .product-data-content .sub-title {
            font-size: 11px;
            margin-top: 7px;
            color: #717171;
        }

        .product-data-content-section {
            margin: 20px 0px;
            background-color: #3b3b3b;
            border-radius: 20px;
        }

        .product-data-content-section-box {
            padding: 20px 10px 10px 10px;
            margin: 0px 0px;
        }

        .product-data-content-section-box img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .product-data-content-section-box .header-title {
            font-size: 17px;
            color: #fff;
            font-weight: 600;
        }

        .product-data-content-section-box .header-subtitle {
            display: flex;
            justify-content: start;
        }

        .product-data-content-section-box .header-subtitle span {
            font-size: 9px;
            color: #fff;
            padding: 4px 8px;
            background-color: #fbbd00c7;
            font-weight: 700;
            border-radius: 50px;
        }

        .product-data-content-section-box-body-top-border {
            border-top: 1px solid #d3d3d370;
            border-bottom: 1px solid #d3d3d370;
            padding: 10px 10px 10px 10px;
        }

        .product-data-content-section-box-body-top-border .total-price {
            font-size: 13px;
            color: #afa8a8;
        }

        .product-data-content-section-box-body-top-border .total-price-amount {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
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
            color: #fff;
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

        .product-data-content-section-box-header {
            width: 100%;
            padding: 12px 16px;
            border-bottom: 1px solid #e9edf2;
            background-color: #3b3b3b;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .product-data-content-section-box-header-content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-data-content-section-box-header-content {
            color: #fff;
            font-family: IBM Plex Sans;
            font-size: 24px;
            font-style: normal;
            font-weight: 600;
        }

        .back-go {
            margin-left: 32px;
            display: flex;
            align-items: center;
            gap: 24px;
            height: 100%;
        }

        .back-go .btn {
            outline: 2px solid transparent;
            outline-offset: 2px;
            color: #fff;
        }

        @media (min-width: 992px) {
            .launchpool-title {
                font-size: 40px;
            }

            .Launchpool-content-text p {
                font-size: 16px;
            }

            .launchpool-des {
                font-size: 20px;
            }

            .Launchpool-content .font {
                font-size: 16px;
            }

            .referral-history {
                font-size: 16px;
            }

            .product-data-content .title {
                font-size: 30px;
            }

            .product-data-content .sub-title {
                font-size: 16px;
            }

            .product-data-content-section-box-header-content .title {
                font-size: 20px;

            }

            .product-data-content-section-box .header-title {
                font-size: 16px;

            }

            .product-data-content-section-box img {
                width: 50px;
                height: 50px;
                margin-right: 15px;
            }

            .product-data-content-section-box .header-subtitle span {
                font-size: 11px;
                padding: 3px 5px 3px 5px;
            }

            .product-data-content-section-box-body-top-border .total-price {
                font-size: 18px;
            }

            .product-data-content-section-box-body-top-border .total-price-amount {
                font-size: 16px;
            }

            .btn-Subscribe {
                font-size: 16px;
            }

            .ends-task-time {
                margin-top: 10px;
                gap: 5px;
            }

            .ends-task-time .number {
                padding: 0 5px;
                font-size: 16px;
            }

            .ends-task-time .word {
                font-size: 16px;
            }
        }

        @media (min-width: 768px) {
            .launchpool-title {
                font-size: 40px;
            }

            .Launchpool-content-text p {
                font-size: 16px;
            }

            .launchpool-des {
                font-size: 20px;
            }

            .Launchpool-content .font {
                font-size: 16px;
            }

            .referral-history {
                font-size: 16px;
            }

            .product-data-content .title {
                font-size: 30px;
            }

            .product-data-content .sub-title {
                font-size: 16px;
            }

            .product-data-content-section-box-header-content .title {
                font-size: 20px;

            }

            .product-data-content-section-box .header-title {
                font-size: 16px;

            }

            .product-data-content-section-box img {
                width: 50px;
                height: 50px;
                margin-right: 15px;
            }

            .product-data-content-section-box .header-subtitle span {
                font-size: 11px;
                padding: 3px 5px 3px 5px;
            }

            .product-data-content-section-box-body-top-border .total-price {
                font-size: 18px;
            }

            .product-data-content-section-box-body-top-border .total-price-amount {
                font-size: 16px;
            }

            .btn-Subscribe {
                font-size: 16px;
            }

            .ends-task-time {
                margin-top: 10px;
                gap: 5px;
            }

            .ends-task-time .number {
                padding: 0 5px;
                font-size: 16px;
            }

            .ends-task-time .word {
                font-size: 16px;
            }
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
                        <h6 class="white">Spot X</h6>
                    </div>
                    <div class="other-data">
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
                                        <div class="launchpool-title mb-2">Spot X</div>
                                        <p>Benefit from new listings on NyseFinance Spot in many ways</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="launchpool-img">
                                    <img src="{{asset('core/public/img/image-e8.webp')}}" alt="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between Launchpool-content-datas">
                                    <div class="left p-1">
                                        <div class="launchpool-des">4,258,435</div>
                                        <p class="font m-0">Total Participants</p>
                                    </div>
                                    <div class="right p-1">
                                        <div class="launchpool-des">$4,415,752,804</div>
                                        <p class="font m-0">Total Commitied(USD)</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between Launchpool-content-datas">
                                    <div class="left p-1">
                                        <div class="launchpool-des">258</div>
                                        <p class="font m-0">Total Prize Pool (USD)</p>
                                    </div>
                                    <div class="right p-1">
                                        <div class="launchpool-des">491%</div>
                                        <p class="font m-0">AVG Growth</p>
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
                <div class="referral-history">Referral History & Rules <i class="fa-solid fa-arrow-right"></i></div>
            </div>
            <div class="product-data-content">
                <div class="container">
                    <div class="title">Ongoing Projects</div>
                    <div class="product-data-content-section">
                        <div class="product-data-content-section-box-header">
                            <div class="product-data-content-section-box-header-content">
                                <div class="title">My Trade</div>
                                <div class="back-go">
                                    <a href="{{route('user.my.spot.x')}}" class="btn back-g"><i class="fa-solid fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
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
                                            <div class="">
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
                                            </div>

                                            <button class="btn btn-sm btn-Subscribe buyCopyTrade"
                                                    data-name="{{strtoupper($trade->name)}}" data-id="{{$trade->id}}">Copy</button>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center pt-4 pb-4 w-100">
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

