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

        .contents-section {
            flex-direction: column;
            flex: 1;
            overflow-y: auto;
            padding: 0 10px;
            display: flex;
            height: calc(100vh - 48px);
            position: relative;
        }

        .contents-section-box {
            width: 100%;
            display: flex;
            flex-direction: column;
            padding-bottom: 40px;
            margin: 0 auto;
        }

        .contents-section-box-data {
            flex-direction: row;
            justify-content: space-between;
            align-items: start;
            width: 100%;
            margin-top: 15px;
            height: 388px;
            position: relative;
        }

        .contents-section-box-data-item {
            /* left: 50%;
            transform: translateX(-50%);
            position: absolute; */
            top: 40px;
            z-index: 10;
            background-image: url('{{asset('core/public/img/TopTraderCard-01.svg')}}');
            /* width: 290px; */
            padding-top: 48px;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            align-items: center;
            backdrop-filter: blur(8px);
            border-radius: 14px;
            margin-top: 55px;
        }

        .contents-section-box-data-link {
            left: 50%;
            transform: translate(-50%, -50%);
            position: absolute;
            top: 0;
            height: 84px;
            width: 84px;
        }

        .title-name {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px 16px;
            gap: 8px;
            width: 100%;
        }

        .title-name a {
            overflow: hidden;
            color: #fff;
            text-align: center;
            text-overflow: ellipsis;
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
            width: 100%;
            white-space: nowrap;
        }

        .title-name-des {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 8px;
            line-height: 16px;
        }

        .text-contect-all {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 4px;
        }

        .contect-word {
            margin-right: 4px;
            padding-right: 8px;
            position: relative;
        }

        .contect-word .one {
            background: linear-gradient(90deg, #33b88c .11%, #96fad9 31.51%, #3ba380 65.84%, #33b88c 99.79%);
            padding-right: 2px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, .2);
            font-size: 16px;
            font-style: italic;
            font-weight: 600;
            line-height: 16px;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            position: relative;
            z-index: 10;
        }

        .contect-word .two {
            right: -5px;
            color: #33b88c;
            font-size: 20px;
            line-height: 20px;
            width: 20px;
            height: 20px;
            text-align: center;
            font-weight: 600;
            position: absolute;
            bottom: -4px;
            transform: scale(.5);
            z-index: 10;
        }

        .contect-word .three-color {
            background: linear-gradient(90deg, #e5b08a .11%, #ffe1cc 33.34%, #ce8050 65.51%, #e5b08a 99.79%);
        }

        .contect-word .four-color {
            color: #e3aa83;
        }

        .contect-word .five-color {
            background: linear-gradient(90deg, #96b8da .11%, #e4f0fc 33.34%, #6e98c1 66.03%, #95b8db 99.79%);
        }

        .contect-word .six-color {
            color: #90b4d8;
        }

        .sub-data {
            display: flex;
            flex-direction: column;
            padding: 16px 16px;
            gap: 16px;
            width: 100%;
        }

        .date-title {
            color: #adb1b8;
            font-size: 12px;
            font-weight: 400;
            line-height: 18px;
        }

        .date-title-amount {
            font-size: 18px;
            font-weight: 600;
            color: #20b26c;
            line-height: 26px;
        }

        .footer-contents-data {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 10px;
        }

        .follow {
            color: #f7a600;
        }

        .likeIt {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 6px;
        }

        .likeIt-title {
            margin-right: 16px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            color: #adb1b8
        }
        .contents-section-box-header{
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            padding: 56px 0;
            text-align: center;
            gap: 12px;
        }
        .contents-section-box-header h6{
            background: linear-gradient(90deg, #33b88c .11%, #96fad9 31.51%, #3ba380 65.84%, #33b88c 99.79%);
            font-size: 20px;
            font-weight: 600;
            line-height: 48px;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
        }
        .contents-section-box-header p{
            color: #fff;
            font-size: 12px;
            font-weight: 400;
            line-height: 18px;
        }
        .contents-section-box-header span{
            color: rgb(255, 255, 255);
            font-size: 14px;
            line-height: 20px;
            margin-left: 10px;
        }
        @media (min-width: 992px) {
            .btn-edit {
                float: right;
            }

            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }

            .icon {
                font-size: 16px;
            }

            .header-title h6 {
                font-size: 16px;
            }
            .contents-section-box-header h6{
                font-size: 40px;
            }
        }
        @media (min-width: 768px) {
            .btn-edit {
                float: right;
            }

            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }

            .icon {
                font-size: 16px;
            }

            .header-title h6 {
                font-size: 16px;
            }
            .contents-section-box-header h6{
                font-size: 40px;
            }
        }
        .leaderboard-hero {
            position: relative;
            background: #181a20;
            border-radius: 20px;
            padding: 40px 0 0 0;
            text-align: center;
        }
        .leaderboard-avatars {
            position: relative;
            height: 120px;
            margin-bottom: -60px;
        }
        .avatar {
            position: absolute;
            top: 0;
            width: 80px;
            height: 80px;
            opacity: 0.5;
            z-index: 1;
        }
        .avatar-2nd { left: 20%; }
        .avatar-3rd { right: 20%; }
        .avatar-1st {
            left: 50%;
            transform: translateX(-50%) scale(1.2);
            z-index: 2;
            opacity: 1;
        }
        .crown {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: gold;
            color: #fff;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            line-height: 32px;
            font-weight: bold;
            font-size: 18px;
        }
        .crown-2nd { background: #bfc9d1; }
        .crown-3rd { background: #cfa77b; }
        .leaderboard-card {
            position: relative;
            background: #23262f;
            border-radius: 16px;
            margin: 0 auto;
            width: 340px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.4);
            padding: 32px 16px 16px 16px;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .leaderboard-card .arrow {
            background: #23262f;
            border: none;
            color: #fff;
            font-size: 24px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
            cursor: pointer;
        }
        .leaderboard-card .arrow.left { left: -20px; }
        .leaderboard-card .arrow.right { right: -20px; }
        .leaderboard-card .card-content {
            flex: 1;
        }
        .leaderboard-card .username {
            font-size: 22px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 8px;
        }
        .leaderboard-card .stats {
            color: #bfc9d1;
            margin-bottom: 8px;
        }
        .leaderboard-card .pnl-title {
            color: #bfc9d1;
            font-size: 14px;
        }
        .leaderboard-card .pnl-amount {
            color: #20b26c;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 12px;
        }
        .leaderboard-card .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #bfc9d1;
        }
        .leaderboard-card .follow-btn {
            background: #f7a600;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 4px 16px;
            font-weight: bold;
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
                        <h6 class="white">Leaderboard</h6>
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
                    <ul class="nav nav-pills mt-3 mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link owl-nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Derivatives</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link owl-nav-link coming_soon" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                    aria-selected="false">All Trading</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link owl-nav-link coming_soon" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                    aria-selected="false">Daily Top 500 Traders</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                             aria-labelledby="pills-home-tab">
                            <div class="contents-section mt-5">
                                <div class="contents-section-box">
                                    <div class="contents-section-box-header">
                                        <h6>2025 10th Weekly Edition </h6>
                                        <p>2025-03-24 00:00(UTC) to 2025-03-30 00:00(UTC)  <span>Past editions <i class="fa-solid fa-arrow-right"></i></span>                                      </p>
                                    </div>
                                    <div id="leaderboardCarousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">

                                            @foreach($rewards as $reward)
                                                <!-- First Card -->
                                            <div class="carousel-item active">
                                                <div class="row">
                                                    <div class="col-12 col-lg-4 col-md-6">
                                                        <div class="position-relative">
                                                            <div class="contents-section-box-data-item">
                                                                <a href="#" class="contents-section-box-data-link">
                                                                    <img src="{{asset('core/public/img/default_avatar.png')}}" alt="" class="img-fluid">
                                                                </a>

                                                                @php
                                                                    $fullname = $reward->user->fullname;
                                                                    $length = strlen($fullname);

                                                                    if ($length > 4) {
                                                                        $masked = substr($fullname, 0, 2) . str_repeat('*', $length - 4) . substr($fullname, -2);
                                                                    } else {
                                                                        $masked = str_repeat('*', $length); // if name too short, just mask everything
                                                                    }
                                                                @endphp



                                                                <div class="title-name">
                                                                    <a href="">{{$masked}}</a>
                                                                    <div class="title-name-des">
                                                                        <div class="text-contect-all">
                                                                            <div class="d-flex">
                                                                                <div class="contect-word">
                                                                                    <span class="one">8</span>
                                                                                    <span class="two">W</span>
                                                                                </div>
                                                                                <div class="contect-word">
                                                                                    <span class="one three-color">8</span>
                                                                                    <span class="two four-color">W</span>
                                                                                </div>
                                                                                <div class="contect-word">
                                                                                    <span class="one five-color">8</span>
                                                                                    <span class="two six-color">W</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex-column">
                                                                            <img src="{{asset('core/public/img/vip99.svg')}}" alt="" class="img-fluid">
                                                                        </div>
                                                                    </div>
                                                                    <div class="sub-data">
                                                                        <div class="row">
                                                                            <div class="col-9 mx-auto text-center">
                                                                                <div class="date-title">Weekly PnL (USD)</div>
                                                                                <div class="date-title-amount">{{$reward->coin}}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="footer-contents-data">
                                                                        <div class="likeIt">
                                                                            <div class="likeIt-title"><i
                                                                                    class="fa-solid fa-thumbs-up me-1"></i>555</div>
                                                                            <div class="likeIt-title"><i
                                                                                    class="fa-solid fa-check-to-slot me-1"></i></i>8
                                                                            </div>
                                                                        </div>
                                                                        <button class="btn btn-sm follow coming_soon">+ Follow</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach


                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#leaderboardCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#leaderboardCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                             aria-labelledby="pills-profile-tab">

                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                             aria-labelledby="pills-contact-tab">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection


@push('script')



@endpush
