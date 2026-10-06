@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>

        .icon{
            font-size: 13px;
            color: #fff;
        }
        .services-text{
            font-size: 10px;
            font-weight: 500;
            color: #fff;
        }
        .services-title{
            font-size: 11px;
            margin: 15px 0;
            color: #636060;
        }
        .header{
            text-align: center;
            background-color: #141414;
            padding: 30px 10px;
        }
        .header .text-content{
            font-size: 16px;
            font-weight: 500;
            color: #fff;
        }
        .header .total span{
            cursor: pointer;
            font-size: 50px;
            font-weight: Bold;
            color: rgb(247 166 0 );

        }
        .tasks{
            margin-top: 30px;
            margin-bottom: 10px;
            color: #fff;
            font-size: 16px;
        }
        .events-details{
            background-color: #141414;
            padding: 20px 10px;
        }
        .events-details h6{
            margin-bottom: 10px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .box-content{
            background-color: #2f2d2d;
            border-radius: 10px;
            padding: 10px 15px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .box-content .one{
            color: #9f9f9f;
            font-size: 13px;
        }
        .box-content .one img{
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .box-content .two{
            color: #ffb11a;
            font-size: 13px;
        }
        .luck{
            font-size: 11px;
        }
        .change{
            font-size: 16px;
            color: #ffb11a;
        }
        @media (min-width: 1200px) {
            .header-title h6{
                font-size: 16px;
            }
            .header {
                background-image: url('/img/banner-bg\ \(1\).jpeg');
                background-size: cover;
                background-color: transparent;
                background-position: center;
            }
            .icon {
                font-size: 16px;
                color: #fff;
            }
            .tasks {
                margin-top: 30px;
                margin-bottom: 20px;
                font-size: 25px;
            }
            .events-details h6 {
                font-size: 18px;
            }
            .luck {
                text-align: center;
            }
            .events-details {
                padding: 30px 30px;
            }
        }
        @media (min-width: 768px) {
            .header-title h6{
                font-size: 16px;
            }
            .header {
                background-image: url('/img/banner-bg\ \(1\).jpeg');
                background-size: cover;
                background-color: transparent;
                background-position: center;
            }
            .icon {
                font-size: 16px;
                color: #fff;
            }
            .tasks {
                margin-top: 30px;
                margin-bottom: 20px;
                font-size: 25px;
            }
            .events-details h6 {
                font-size: 18px;
            }
            .luck {
                text-align: center;
            }
            .events-details {
                padding: 30px 30px;
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
                        <h6 class="white">My Rewards Hub</h6>
                    </div>
                    <div class="other-data">
                        <i class="fa-regular fa-circle-question icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="header">
                        <div class="total">
                            <span>${{$reward->coin}}</span>
                        </div>
                        <div class="text-content">My Rewards <i class="fa-solid fa-arrow-right ms-1"></i></div>
                    </div>
                </div>
                <div class="col-12">
                    <h6 class="tasks">My Tasks and Events</h6>
                    <div class="events-details">
                        <h6>Deposit to win SOL</h6>

                        <div class="row align-items-end">
                            <div class="col-lg-6">
                                <div class="box-content">
                                    <div class="one">0 Chance(s)</div>
                                    <div class="two coming_soon">Take a Chance <i class="fa-solid fa-arrow-right ms-1"></i></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="box-content">
                                    <div class="one">
                                        <img src="{{asset('core/public/img/hunt.jpeg')}}" alt="">
                                        <div class="change">1 Chance(s)</div>
                                        <div class="luck">Luck Draw</div>
                                    </div>
                                    <div class="two">Take Deposit > $100</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="events-details">
                        <h6>PLUME GRAND Spin</h6>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="box-content">
                                    <div class="one">0 Chance(s)</div>
                                    <div class="two coming_soon">Take a Chance <i class="fa-solid fa-arrow-right ms-1"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection


@push('script')



@endpush
