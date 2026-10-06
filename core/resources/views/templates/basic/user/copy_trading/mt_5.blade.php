@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>
        .bybit-title {
            text-align: center;
        }
        .bybit-title .title{
            font-size: 11px;
            font-weight: 500;
            color: #fff;
        }
        .bybit-title .sub-title{
            margin: 5px 0px;
            font-size: 16px;
            font-weight: 600;
            background: linear-gradient(to right, #FFB200 0%, #EB5B00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bybit-title .footer-title{
            font-size: 10px;
            font-weight: 500;
            color: #a19f9fbd;
        }
        .deomTrading{
            margin-top: 20px;
            text-align: center;
            background: linear-gradient(to right, #FFB200 0%, #EB5B00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .deomTrading i{
            font-weight: 600;
            font-size: 15px;
        }
        .deomTrading span{
            font-weight: 700;
        }
        .deomTrading .img{
            background-image: url('/img/banner-mobile-bg.svg');
            background-size: cover;
            background-position: center;
        }
        .deomTrading .img img{
            width: 100%;
            height: 200px;
        }
        .other-deomTrading{
            padding: 0 0 60px;
            width: 100%;
            overflow: hidden;
        }
        .other-deomTrading-content{
            width: 100%;
            padding: 0 24px;
            margin: 0 auto;
        }
        .other-deomTrading-content0-box{
            margin-bottom: 24px;
        }
        .other-deomTrading-content0-box-data{
            width: 100%;
            max-width: 1200px;
            padding: 1px;
            border-radius: 16px;
            background-image: linear-gradient(rgb(247, 166, 0), transparent);
            position: relative;
            margin-bottom: 16px;
        }
        .other-deomTrading-content0-box-data-header{
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
            height: 100%;
            border-radius: 16px;
            background-color: rgb(0, 0, 0);
            padding: 15px;
        }
        .other-deomTrading-content0-box-data-header img{
            width: 100%;
            border-radius: 16px;
        }
        .other-deomTrading-content0-box-data-header .text .title {
            display: flex;
            align-items: start;
            gap: 6px;
        }
        .other-deomTrading-content0-box-data-header .text .title h6 {
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            flex: 1 1 0%;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .other-deomTrading-content0-box-data-header .text .sidetitle  {
            color: rgb(32, 178, 108);
            text-align: center;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: 24px;
            height: 24px;
            padding: 0px 6px;
            border-radius: 4px;
            background: rgba(32, 178, 108, 0.12);
            margin-top: 6px;
            white-space: nowrap;
        }
        .other-deomTrading-content0-box-data-header .des   {
            display: block;
            margin: 8px 0px;
            text-align: left;
            color: rgb(129, 133, 140);
            font-size: 11px;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .other-deomTrading-content0-box-data-header .footer {
            display: block;
            align-items: center;
        }
        .other-deomTrading-content0-box-data-header .footer p {
            text-wrap: wrap;
            text-align: left;
            margin: 15px 0px 0px;
            color: rgb(129, 133, 140);
            font-size: 10px;
            font-weight: 500;
        }
        .count-down{
            display: flex;
            align-items: center;
            gap: 4px;
            margin: 10px 0px 30px;
        }
        .count-down .number-countdown {
            width: fit-content;
            padding: 0px 2px;
            height: 32px;
            font-size: 24px;
            line-height: 32px;
            border-radius: 6px;
            border: 1px solid rgba(192, 210, 231, 0.12);
            background: rgb(22, 23, 26);
            color: rgb(255, 255, 255);
            text-align: center;
            font-weight: 600;
        }
        .count-down .countdown-time {
            color: rgb(173, 177, 184);
            font-size: 24px;
            font-weight: 500;
            line-height: 32px;
            margin-right: 6px;
        }
        @media (min-width: 992px) {
            .header-title h6{
                font-size: 16px;
            }
            .bybit-title .title {
                font-size: 16px;
            }
            .bybit-title .sub-title {
                font-size: 40px;
            }
            .bybit-title .footer-title {
                font-size: 16px;
            }
            .deomTrading .img img {
                height: 100%;
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
                        <h6 class="white">Copy Trading Classic</h6>
                    </div>
                    <div class="other-data">
                        <div class="other-data-war d-none">My Trades</div>
                    </div>
                </div>
            </div>
        </div>
    </header>



    <section class="main-section mt-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="bybit-title">
                        <div class="title">
                            Unleash your trading potential in financial markets
                        </div>
                        <div class="sub-title">
                            NyseFinance Gold&FX Trading
                        </div>
                        <div class="footer-title">
                            Cryptocurrency | forex | CFD (Metal, Oil and Indices)
                        </div>
                    </div>
                    <div class="deomTrading">
                        <h6> <i class="fa-regular fa-share-from-square me-1"></i> <span>Demo Trading</span> <i class="fa-solid fa-arrow-right ms-1"></i></h6>
                        <div class="img">
                            <img src="{{asset('core/public/img/pngtree-trading-chart-interface-on-laptop-png-image_14348826-removebg-preview.png')}}" alt="">
                        </div>
                    </div>
                    <div class="other-deomTrading mt-3">
                        <div class="other-deomTrading-content row">
                            <div class="other-deomTrading-content0-box col-lg-6">
                                <div class="other-deomTrading-content0-box-data">
                                    <div class="other-deomTrading-content0-box-data-header">
                                        <div class="img">
                                            <img src="{{asset('core/public/img/EN_2503-T50347_MT5_Indices_0-Fee_NoCTA_1600x900.png')}}" alt="">
                                        </div>
                                        <div class="text">
                                            <div class="title">
                                                <h6>
                                                    NyseFinance Indices Unleashed: Trade for Free With Zero Fees!
                                                </h6>
                                                <div class="sidetitle">Ongoing</div>
                                            </div>
                                            <div class="des">
                                                We're excited to announce the NyseFinance Indices Unleashed Trading Event, offering users the opportunity to enjoy zero trading fees on selected indices pairs!
                                            </div>
                                            <div class="footer">
                                                <p>This event ends in:</p>
                                                <div class="count-down">
                                                    <div class="number-countdown">06</div>
                                                    <div class="countdown-time">D</div>
                                                    <div class="number-countdown">07</div>
                                                    <div class="countdown-time">H</div>
                                                    <div class="number-countdown">16</div>
                                                    <div class="countdown-time">M</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="other-deomTrading-content0-box col-lg-6">
                                <div class="other-deomTrading-content0-box-data">
                                    <div class="other-deomTrading-content0-box-data-header">
                                        <div class="img">
                                            <img src="{{asset('core/public/img/EN_2503-T50347_MT5_Indices_0-Fee_NoCTA_1600x900.png')}}" alt="">
                                        </div>
                                        <div class="text">
                                            <div class="title">
                                                <h6>
                                                    NyseFinance Indices Unleashed: Trade for Free With Zero Fees!
                                                </h6>
                                                <div class="sidetitle">Ongoing</div>
                                            </div>
                                            <div class="des">
                                                We're excited to announce the NyseFinance Indices Unleashed Trading Event, offering users the opportunity to enjoy zero trading fees on selected indices pairs!
                                            </div>
                                            <div class="footer">
                                                <p>This event ends in:</p>
                                                <div class="count-down">
                                                    <div class="number-countdown">06</div>
                                                    <div class="countdown-time">D</div>
                                                    <div class="number-countdown">07</div>
                                                    <div class="countdown-time">H</div>
                                                    <div class="number-countdown">16</div>
                                                    <div class="countdown-time">M</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="footer-section">
        <div class="footer-section-content">
            <div class="container">
                <button class="btn btn-footer coming_soon ">Open MT5 Account</button>
                <a href="{{route('user.spot.x')}}" class="btn btn-footer btn-footer2 mt-2">Gold & FX Copy Trading</a>
            </div>
        </div>
    </footer>



    @include('templates.basic.user.copy_trading.includes.modal')
@endsection

@push('script')
    @include('templates.basic.user.copy_trading.includes.modal_js')
@endpush
