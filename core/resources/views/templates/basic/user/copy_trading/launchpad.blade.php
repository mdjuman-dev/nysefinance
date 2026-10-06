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
            background: url('{{asset('core/public//img/banner-bg\ \(1\).jpeg')}}');
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

        .Launchpool-content-text .text p {
            font-size: 15px;
            color: #d2d2d2;
        }

        .Launchpool-content .font {
            font-size: 11px;
            color: #d2d2d2;
            margin-top: 10px !important;
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
            background-color: #fbfbfb;
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

        .content-other-datas {
            margin-top: 20px;
        }

        .content-other-data {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: stretch;
            gap: 20px;
            border-radius: 16px;
            overflow: hidden;
            background-color: #fff;
            padding: 32px 10px;
            position: relative;
            margin-bottom: 10px;
        }

        .content-other-data .enable {
            background: rgba(56, 68, 82, .06);
            color: #81858c;
            right: 0;
            border-radius: 0 16px 0 8px;
            position: absolute;
            top: 0;
            display: inline-flex;
            padding: 2px 8px;
            justify-content: center;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-style: normal;
            font-weight: 600;
            line-height: 18px;
        }

        .content-other-data-section {
            display: flex;
            flex-wrap: wrap;
            background-color: #fff;
            width: 100%;
        }

        .content-other-data-section-info {
            flex-grow: 0;
            flex-shrink: 0;
            display: flex;
            width: 100%;
            flex-direction: column;
            justify-content: space-between;
        }

        .content-other-data-section-info .one {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .content-other-data-section-info .two {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .content-other-data-section-info .two .text {
            color: #81858c;
            font-size: 11px;
            font-style: normal;
            font-weight: 400;
            line-height: 18px;
        }

        .content-other-data-section-info .one .header-img {
            display: flex;
            width: 100%;
            flex-direction: row;
            align-items: center;
            gap: 16px;
            justify-content: space-between;
        }

        .content-other-data-section-info .one .text {
            color: #121214;
            font-size: 11px;
            font-style: normal;
            font-weight: 400;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-height: 88px;
        }

        .text-header {
            text-align: left;
            color: #000;
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-height: 88px;
        }

        .content-other-data-section-info .one .header-img .img img {
            display: flex;
            width: 48px;
            height: 48px;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .content-other-data-section-boxs {
            display: flex;
            width: 100%;
            flex-direction: row;
            gap: 16px;
        }

        .content-other-data-section-box-item {
            display: flex;
            flex-direction: column;
            padding: 10px 5px;
            background-color: #fff;
            border-radius: 16px;
            border: 1px solid #e9edf2;
            width: 100%;
            max-height: 296px;
            flex-shrink: 0;
        }

        .content-other-data-section-box-item-header {
            color: #121214;
            display: flex;
            flex-direction: row;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px dashed #e9edf2;
            gap: 8px;
            margin-bottom: 10px;
        }

        .content-other-data-section-box-item-header img {
            display: flex;
            width: 30px;
            height: 30px;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            border-radius: 100px;
            background-color: #fff;
        }

        .content-other-data-section-box-item-header .texts {
            color: #121214;
            font-size: 12px;
            font-style: normal;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: baseline;
            word-break: break-word;
            white-space: nowrap;
            width: 50%
        }

        .btn-Details {
            background-color: transparent;
            border-radius: 4px;
            border: 1px solid #d6850d;
            color: #d6850d;
            text-align: center;
            font-size: 11px;
            font-style: normal;
            font-weight: 600;
        }

        .text-contents {
            padding: 3px 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .text-contents .left {
            font-size: 12px;
            color: #81858c;
        }

        .text-contents .right {
            font-size: 11px;
            color: #000;
        }

        @media (min-width: 992px) {
            .header-title h6 {
                font-size: 16px;
            }

            .other-data i {
                font-size: 16px;
            }

            .launchpool-title {
                font-size: 30px;
            }

            .Launchpool-content-text .text p {
                font-size: 20px;
            }

            .Launchpool-content .font {
                font-size: 16px;
            }

            .launchpool-des {
                font-size: 16px;
            }

            .Launchpool-content-datas {
                margin-top: 10px;
            }

            .referral-history {
                font-size: 16px;
            }

            .referral-history span {
                margin-right: 30px !important;
            }

            .product-data-content .title {
                font-size: 20px;
            }

            .product-data-content .title::before {
                right: 57%;
            }

            .product-data-content .title::after {
                left: 57%;
            }

            .product-data-content .title::before, .product-data-content .title::after {
                width: 3%;
            }

            .product-data-content .sub-title {
                font-size: 14px;
            }

            .text-header {
                font-size: 30px;
            }

            .content-other-data-section-info .one .text {
                font-size: 16px;

            }

            .content-other-data .enable {
                padding: 5px 10px;
                font-size: 16px;
            }

            .content-other-data-section-info .one .header-img .img img {
                width: 68px;
                height: 68px;
            }

            .content-other-data-section-box-item-header img {
                width: 50px;
                height: 50px;
                margin-right: 20px;
            }

            .content-other-data-section-box-item-header .texts {
                font-size: 20px;
                text-align: left;
            }

            .text-contents .left {
                font-size: 16px;
            }

            .text-contents .right {
                font-size: 16px;
            }

            .btn-Details {
                width: 20%;
                font-size: 16px;
            }

            .content-other-data-section-box-item-header-content {
                display: flex;
                align-items: center;
                width: 100%;
            }
        }

        @media (min-width: 768px) {
            .header-title h6 {
                font-size: 16px;
            }

            .other-data i {
                font-size: 16px;
            }

            .launchpool-title {
                font-size: 30px;
            }

            .Launchpool-content-text .text p {
                font-size: 20px;
            }

            .Launchpool-content .font {
                font-size: 16px;
            }

            .launchpool-des {
                font-size: 16px;
            }

            .Launchpool-content-datas {
                margin-top: 10px;
            }

            .referral-history {
                font-size: 16px;
            }

            .referral-history span {
                margin-right: 30px !important;
            }

            .product-data-content .title {
                font-size: 20px;
            }

            .product-data-content .title::before {
                right: 57%;
            }

            .product-data-content .title::after {
                left: 57%;
            }

            .product-data-content .title::before, .product-data-content .title::after {
                width: 3%;
            }

            .product-data-content .sub-title {
                font-size: 14px;
            }

            .text-header {
                font-size: 30px;
            }

            .content-other-data-section-info .one .text {
                font-size: 16px;

            }

            .content-other-data .enable {
                padding: 5px 10px;
                font-size: 16px;
            }

            .content-other-data-section-info .one .header-img .img img {
                width: 68px;
                height: 68px;
            }

            .content-other-data-section-box-item-header img {
                width: 50px;
                height: 50px;
                margin-right: 20px;
            }

            .content-other-data-section-box-item-header .texts {
                font-size: 20px;
                text-align: left;
            }

            .text-contents .left {
                font-size: 16px;
            }

            .text-contents .right {
                font-size: 16px;
            }

            .btn-Details {
                width: 20%;
                font-size: 16px;
            }

            .content-other-data-section-box-item-header-content {
                display: flex;
                align-items: center;
                width: 100%;
            }
        }

        .content-other-data-section-box-item-header-content {
            display: flex;
            align-items: center;
            width: 65%;
        }

        .content-other-data-section-box-item-header .texts {
            width: 90%;
        }

        .content-other-data-section-box-item-header {
            justify-content: space-between;
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
                        <h6 class="white">Launch-Pad</h6>
                    </div>
                    <div class="other-data">
                        <i class="fa-solid fa-circle-question"></i>
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
                                        <div class="launchpool-title mb-2">Launchpad</div>
                                        <p>Gain early access to tokens from promising projects directly on NyseFinance</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="launchpool-img">
                                    <img src="{{asset('core/public/img/image-c3.png')}}" alt="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between Launchpool-content-datas">
                                    <div class="left p-1">
                                        <p class="font m-0">Total Committed Amount (USD)</p>
                                        <div class="launchpool-des">$4,258,435</div>
                                    </div>
                                    <div class="right p-1">
                                        <p class="font m-0">Avg. Growth</p>
                                        <div class="launchpool-des">457%</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between Launchpool-content-datas">
                                    <div class="left p-1">
                                        <p class="font m-0">Participants</p>
                                        <div class="launchpool-des">2,358,454</div>
                                    </div>
                                    <div class="right p-1">
                                        <p class="font m-0">Projects</p>
                                        <div class="launchpool-des">11</div>
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
                <div class="referral-history"><span class="me-2">Token Sale Records <i
                            class="fa-solid fa-arrow-right"></i></span> Referral History & Rules <i
                        class="fa-solid fa-arrow-right"></i></div>
            </div>
            <div class="product-data-content">
                <div class="container">
                    <div class="title">Past Projects</div>
                    <div class="sub-title">
                        Gain early access to tokens from promising projects on NyseFinance. Use MNT for token sale
                        subscriptions or participate in the allocation lottery with USDT. Your holdings during the
                        snapshot period determine both your subscription amount and your chances of winning in the token
                        allocation lottery.
                    </div>
                    <div class="content-other-datas">

                        @if($laundpads->isNotEmpty())
                            @foreach($laundpads as $laundpad)

                                <div class="content-other-data">
                                    <div class="enable">Enable</div>
                                    <div class="content-other-data-section">
                                        <div class="content-other-data-section-info">
                                            <div class="one">
                                                <div class="header-img">
                                                    <div class="">
                                                        <div class="text-header">{{$laundpad->title}}</div>
                                                        <div class="text">{{$laundpad->sub_title}}</div>
                                                    </div>
                                                    <div class="img">
                                                        <img src="{{asset('core/public/img/image-c3.png')}}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content-other-data-section-boxs mt-2">
                                            <div class="content-other-data-section-box-item">
                                                <div class="content-other-data-section-box-item-header">
                                                    <div class="content-other-data-section-box-item-header-content">

                                                        @if($laundpad->image)
                                                            <img src="{{ getImage(getFilePath('currency') .'/'.$laundpad->image,getFileSize('currency')) }}" alt="">
                                                        @else
                                                            <img src="{{asset('core/public/img/images (1).jpeg')}}" alt="">
                                                        @endif

                                                        <div class="texts">
                                                            Subscribe to Buy USDT with {{$laundpad->price_currency}}
                                                        </div>
                                                    </div>
                                                    <button class="btn-Details btn btn-sm d-none">View Details</button>
                                                </div>
                                                <div class="text-contents">
                                                    <div class="left">price</div>
                                                    <div class="right">1USDT={{$laundpad->price}}{{$laundpad->price_currency}}</div>
                                                </div>
                                                <div class="text-contents">
                                                    <div class="left">Total Allocation (XTER)</div>
                                                    <div class="right">{{$laundpad->total_allocation}}</div>
                                                </div>
                                                <div class="text-contents">
                                                    <div class="left">Cap per Subscriber</div>
                                                    <div class="right">{{$laundpad->cap_per_subscriber}}</div>
                                                </div>
                                                <div class="text-contents">
                                                    <div class="left">Total Commited Amount</div>
                                                    <div class="right">${{$laundpad->total_committed_amount}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    No Data Found
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection


@push('script')



@endpush
