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
        .header-hunt-time span{
            font-size: 10px;
            font-weight: 600;
            color: #fff;
            background-color: #636060;
            padding: 5px 20px;
            border-radius: 15px;
        }
        .header-hunt{
            text-align: center;
            margin-top: 20px;
        }
        .main-title{
            font-size: 25px;
            color: #fff;
            margin: 5px  5px;
            font-weight: 700;
        }
        .sub-title{
            font-size: 12px;
            color: #fff;
        }
        .point span{
            font-size: 10px;
            color: #fff;
            border: 1px solid #fdc69a;
            background-color: #f38c38;
            padding: 5px 10px 5px 10px;
            border-radius: 50px;
        }
        .header-hunt .img {
            margin-top: 20px;
        }
        .header-hunt .img img{
            width: 100%;
            height: 200px;
        }
        .btn-earn{
            margin-top: 10px;
            background: linear-gradient(313deg, #fd9136 52.81%, #ea4620 106.5%);
            padding: .25rem .75rem .25rem .375rem;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            border-radius: .5rem;
        }
        .chance{
            margin-top: 10px;
            font-size: 11px;
            color: #ff8d2d;
            text-align: center;
            font-style: normal;
            font-weight: 600;
        }
        .chance-des{
            font-size: 11px;
            color: #fff;
        }
        .daily-claim{
            margin-top: 4.0625rem;
            width: 100%;
        }
        .daily-contents{
            background-image: linear-gradient(90deg, #000, #000), linear-gradient(0deg, #ea46201a 60%, #f48d38) !important;
            width: 100%;
            display: flex;
            padding: 1.5rem 0;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 1rem;
            border: .0625rem solid transparent;
            margin-bottom: 1rem;
            background-clip: padding-box, border-box;
            background-origin: padding-box, border-box;
        }
        .daily-contents-header{
            text-align: center;
            padding: 0 1rem;
        }
        .daily-contents-header .one{
            display: flex;
            flex-direction: row;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
        }
        .daily-contents-header .two{
            color: #adb1b8;
            font-size: 13px;
            font-weight: 400;
            margin-top: .5rem;
        }
        .daily-contents-header .one img{
            width: 1.25rem;
            height: 1.625rem;
            margin-top: .125rem;
        }
        .daily-contents-mine{
            margin-top: 1.5rem;
            overflow-x: hidden;
            width: 100%;
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: center;
        }
        .daily-contents-mine .scroll{
            overflow-x: scroll;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .daily-contents-mine .scroll::-webkit-scrollbar {
            display: none;
        }
        .daily-contents-mine .scroll .date{
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 1.34375rem;
        }
        .daily-contents-mine .scroll .date .number{
            color: #adb1b8;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .daily-contents-mine .scroll .date .number .count{
            width: 2rem;
            height: 2rem;
            border: .0625rem dashed #adb1b8;
            border-radius: 50%;
            text-align: center;
            font-size: .875rem;
            font-weight: 600;
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        .daily-contents-mine .scroll .date .number .amount{
            margin-top: .25rem;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.375rem;
        }
        .daily-contents-mine .scroll .date .number .amount.active{
            color: #ff8d2d;
        }
        .btncheck{
            margin-top: 20px;
            border: 1px solid #F7A600;
            color: #F7A600;
            font-size: 13px;
            background-color: transparent;
            border-radius: 6px;
        }
        @media (min-width: 992px) {
            .header-title h6{
                font-size: 16px;
            }
            .header-hunt .img img {
                width: 50%;
                height: 200px;
            }
            .header-hunt-time span {
                font-size: 16px;
            }
            .chance {
                font-size: 16px;
            }
            .chance-des {
                font-size: 16px;
            }
            .daily-contents-header .one img {
                width: 2.25rem;
                height: 2.625rem;
                margin-top: 0px;
                margin-right: 20px;
            }
            .daily-contents-header .one span{
                line-height: 50px;
                font-size: 25px;
            }
            .daily-contents-header .two {
                font-size: 16px;
                margin-top: 0;
            }
            .daily-contents-mine .scroll .date {
                gap: 3.34375rem;
            }
            .btncheck {
                font-size: 16px;
                margin-top: 30px;
            }
        }
        @media (min-width: 768px) {
            .header-title h6{
                font-size: 16px;
            }
            .header-hunt .img img {
                width: 50%;
                height: 200px;
            }
            .header-hunt-time span {
                font-size: 16px;
            }
            .chance {
                font-size: 16px;
            }
            .chance-des {
                font-size: 16px;
            }
            .daily-contents-header .one img {
                width: 2.25rem;
                height: 2.625rem;
                margin-top: 0px;
                margin-right: 20px;
            }
            .daily-contents-header .one span{
                line-height: 50px;
                font-size: 25px;
            }
            .daily-contents-header .two {
                font-size: 16px;
                margin-top: 0;
            }
            .daily-contents-mine .scroll .date {
                gap: 1.34375rem;
            }
            .btncheck {
                font-size: 16px;
                margin-top: 30px;
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
                        <h6 class="white">Daily Treasure Hunt <i class="fa-solid fa-up-right-from-square ms-2"></i></h6>
                    </div>
                    <div class="other-data">
                        <i class="fa-solid fa-circle-question icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="header-hunt">
                        <div class="header-hunt-time">
                            <span>Feb 10, 2025 ,10am UTC-May 10, 2025, 10AM UTC</span>
                        </div>
                        <div class="main-title">
                            Daily Treasure Hunt
                        </div>
                        <div class="sub-title">
                            Every scratch wins - 100% guaraned!
                        </div>
                        <div class="point"><span>My Points: {{$reward->coin}}</span></div>
                        <div class="img">
                            <img src="{{asset('core/public/img/hunt.jpeg')}}" alt="">
                            <img src="{{asset('core/public/img/10001.png')}}" alt="">
                        </div>
                        <button class="btn btn-earn coming_soon">Earn Points for Scratch cards</button>
                        <div class="chance">0 Chance(s)</div>
                        <div class="chance-des">
                            Each participant gets up to 3 chances per day, with each chance costing 3 points
                        </div>
                        <div class="daily-claim">
                            <div class="daily-contents">
                                <div class="daily-contents-header">
                                    <div class="one">
                                        <img src="{{asset('/core/public/img/timer-e53f368eda34d719ec603f10cc7ec2f3.webp')}}" alt="">
                                        <span>Earn Points With Daily Check-Ins</span>
                                    </div>
                                    <div class="two">Use your points to scratch for surprises!</div>
                                </div>
                                <div class="daily-contents-mine">
                                    <div class="scroll">
                                        <div class="date">
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.340</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount active">Today</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                            <div class="number">
                                                <div class="count">+1</div>
                                                <div class="amount">2.30</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $checkedDate=\App\Models\RewardHistory::where('user_id', auth()->user()->id)->whereDate('created_at', now())->first();
                                @endphp

                                @if($checkedDate)
                                <a href="#" class="btn btn-sm btncheck">
                                    Collected
                                </a>
                                @else
                                    <a href="{{route('user.collect.coin')}}" class="btn btn-sm btncheck">
                                        Check In
                                    </a>
                                @endif
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
