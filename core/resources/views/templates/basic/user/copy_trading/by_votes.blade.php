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

        .by-votes-header {
            padding: 20px 5px;
            background-size: cover;
            background-position: center;
            color: #fff;
            position: relative;
            background-image: url('{{asset('core/public/img/image-718a46b056774024830325df048057f9.avif')}}');
        }

        .by-votes-header .title {
            font-size: 26px;
            font-weight: 700;
        }
        .by-votes-header div{
            width: 75%;
        }

        .by-votes-header .des {
            font-size: 12px;
            color: #c4c0c0;
            margin: 10px 0px;
        }

        .by-votes-header .sub-link {
            font-size: 15px;
        }

        .user-center {
            background: linear-gradient(to right, #67400d 0%, #000000 100%);
            border: 1px solid #323232;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .user-center .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-center .header .usertitle .rewards {
            font-size: 16px;
        }

        .user-center .header .usertitle .amounts {
            font-size: 18px;
        }

        .user-center .header .usercenter .links {
            color: #fff;
            font-size: 10px;
        }

        .user-center .body .body-title {
            font-size: 17px;
        }

        .user-center .body .body-content {
            font-size: 14px;
        }

        .user-center .body .body-content span {
            color: #adb1b8;
            font-weight: 500;
        }

        .user-center .body .body-content i {
            color: rgb(247, 166, 0);
            font-size: 12px;
            margin-top: 3px;
            margin-right: 3px;
        }

        .body-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-Earn, .btn-Subscribe {
            font-size: 15px;
        }

        .body-footer {
            margin-top: 20PX;
            border-top: 1PX solid #636060;
            padding: 10px 5px;
        }

        .content-text {
            line-height: 1.1;
        }

        .content-text .one {
            font-size: 15px;
        }

        .content-text .two {
            font-size: 13px;
        }

        .subxcribe-text {
            font-size: 13px;
        }

        .btn-Subscribe {
            color: #fff;
            border: 1px solid #323232;
            background: transparent;
            font-weight: 600;
        }
        .ex--sub{
            width: 170px !important;
            font-size: 14px !important;
            padding: 8px 0px !important;
        }

        .votes-contents-boxs {
            display: flex;
            gap: 24px;
            width: 100%;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .votes-contents-box {
            width: 100%;
            border-radius: 8px;
            background: #121b37;
            padding: 32px 15px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            position: relative;
            border: 1px solid transparent;
        }

        .votes-contents-box-complet {
            display: flex;
            position: absolute;
            width: 100%;
            top: 0;
            justify-content: flex-end;
            align-items: start;
            right: 0;
        }

        .votes-contents-box-complet-text {
            background-color: rgba(192, 210, 231, .12);
            color: #adb1b8;
            border-radius: 0px 8px;
            font-size: 11px;
            line-height: 18px;
            padding: 3px 10px;
            text-wrap: nowrap;
        }

        .votes-contents-box .main-content {
            display: flex;
            gap: 8px;
            align-items: center;
            position: relative;
        }

        .votes-contents-box .main-content .img {
            width: 60px;
            height: 60px;
            border-radius: 100px;
            border: .844px solid rgba(64, 67, 71, .6);
            background: rgba(192, 210, 231, .08);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .votes-contents-box .main-content .img img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
        }

        .votes-contents-box .main-content .text-contents {
            flex: 1;
            overflow: hidden;
        }

        .votes-contents-box .main-content .text-contents .one {
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            overflow: hidden;
        }

        .votes-contents-box .main-content .text-contents .one h6 {
            font-size: 19px;
        }

        .votes-contents-box .main-content .text-contents .one i {
            color: rgb(247, 166, 0);
            font-size: 13px;
            font-weight: 600;
            margin-left: 3px;
        }

        .votes-contents-box .main-content .text-contents .two {
            font-size: 13px;
        }

        .Progress_progressContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            line-height: 0.5;
        }

        .Progress_percentageText-text {
            font-size: 18px;
        }

        .inline-block {
            display: inline-block;
        }

        .progresschange {
            color: #71757a;
            font-size: 12px;
            font-weight: 400;
            line-height: 14px;
            transform: scale(.83);
            margin-top: 5px;
            padding-bottom: 2px;
            border-bottom: 1px dashed #71757a;
            white-space: nowrap;
        }

        .votes-contents-box-details {
            font-size: 13px;
            line-height: 18px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 6px 0 20px;
            min-height: 36px;
            position: relative;
        }

        .votes-contents-box-details-more {
            display: inline-block;
            bottom: 0;
            right: 0;
            background-color: #16171a;
            position: absolute;
        }

        .votes-contents-box-details-more span {
            transition-property: all;
            transition-timing-function: cubic-bezier(.4, 0, .2, 1);
            transition-duration: .15s;
            color: #f7a600;
        }

        .votes-contents-box-other {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 44px;
            margin-bottom: 16px;
            gap: 10px;
        }

        .votes-contents-box-other p {
            margin: 0;
            color: #fff;
            font-size: 13px;
        }

        .votes-contents-box-other .one {
            font-size: 15px;
        }

        @media (min-width: 992px) {
            .by-votes-header .title {
                font-size: 26px;
            }

            .by-votes-header .des {
                font-size: 13px;
            }

            .by-votes-header {
                padding: 100px 5px;
            }

            .by-votes-header .sub-link {
                margin-top: 15px;
                font-size: 16px;
            }

            .user-center .header .usertitle .rewards {
                font-size: 16px;
            }

            .user-center .header .usertitle .amounts {
                font-size: 16px;
            }

            .user-center .body .body-title {
                font-size: 18px;
            }

            .user-center .body .body-content {
                display: flex;
                font-size: 16px;
                justify-content: flex-start;
            }

            .user-center .body .body-content i {
                font-size: 16px;
                margin-top: 4px;
                margin-right: 10px;
            }

            .user-center .body .body-content span {
                margin-right: 20px;
            }

            .content-text .one {
                color: #fff;
                font-size: 16px;
            }

            .content-text .two {
                color: #b1a8a8;
                font-size: 13px;
            }

            .content-text {
                line-height: 1.5;
            }

            .btn-Earn {
                background: rgb(247, 166, 0);
                color: rgb(16, 16, 20);
                font-size: 15px;
                font-weight: 600;
            }

            .subxcribe-text {
                font-size: 16px;
            }

            .moblie-res {
                justify-content: space-between;
                align-items: center;
            }

            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }

            .votes-contents-box-details {
                font-size: 14px;
                line-height: 20px;
                margin: 15px 0 20px;
            }

            .votes-contents-box-other p {
                font-size: 16px;
            }
        }

        @media (min-width: 768px) {
            .by-votes-header .title {
                font-size: 26px;
            }

            .by-votes-header .des {
                font-size: 13px;
            }

            .by-votes-header {
                padding: 100px 5px;
            }

            .by-votes-header .sub-link {
                margin-top: 15px;
                font-size: 16px;
            }

            .user-center .header .usertitle .rewards {
                font-size: 16px;
            }

            .user-center .header .usertitle .amounts {
                font-size: 16px;
            }

            .user-center .body .body-title {
                font-size: 18px;
            }

            .user-center .body .body-content {
                display: flex;
                font-size: 16px;
                justify-content: flex-start;
            }

            .user-center .body .body-content i {
                font-size: 16px;
                margin-top: 4px;
                margin-right: 10px;
            }

            .user-center .body .body-content span {
                margin-right: 20px;
            }

            .content-text .one {
                color: #fff;
                font-size: 16px;
            }

            .content-text .two {
                color: #b1a8a8;
                font-size: 13px;
            }

            .content-text {
                line-height: 1.5;
            }

            .btn-Earn {
                background: rgb(247, 166, 0);
                color: rgb(16, 16, 20);
                font-size: 15px;
                font-weight: 600;
            }

            .subxcribe-text {
                font-size: 16px;
            }

            .moblie-res {
                justify-content: space-between;
                align-items: center;
            }

            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }

            .votes-contents-box-details {
                font-size: 14px;
                line-height: 20px;
                margin: 15px 0 20px;
            }

            .votes-contents-box-other p {
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
                        By-Votes
                    </div>
                    <div class="other-data">
                        <i class="fa-solid fa-ellipsis icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center all-other-services">
                <div class="col-12">
                    <div class="by-votes-header">
                        <div class="">
                            <div class="title">
                                ByVotes Spot
                            </div>
                            <div class="des">
                                Hold USDT, USDC, USDE, USDD, DAI, or CUSD to be able to vote for your favorite tokens
                                and stand to win airdrops.
                            </div>
                            <div class="sub-link">
                                Activity Rules <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="by-votes-body">
                        <div class="">
                            <div class="user-center">
                                <div class="header">
                                    <div class="usertitle">
                                        <div class="rewards">My Rewards</div>
                                        <div class="amounts">0.00 USD</div>
                                    </div>
                                    <div class="usercenter">
                                        <div class="links"><i class="fa-regular fa-circle-user me-1"></i> User Center <i
                                                class="fa-solid fa-angle-right ms-1 text-warning"></i></div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="body-title">
                                        How to Earn More Votes
                                    </div>
                                    <div class="body-content">
                                        <i class="fa-solid fa-circle-check"></i><span>Stablecoin Holdings ≥ $100 </span>
                                        <i class="fa-solid fa-circle-check"></i><span>Holding Period ≥ 1 Day</span>
                                    </div>
                                    <div class="body-footer">
                                        <div class="content-text">
                                            <div class="one">0 Votes</div>
                                            <div class="two">Availabe Votes?</div>
                                        </div>
                                        <button class="btn btn-sm btn-Earn">Earn Votes</button>
                                    </div>
                                </div>
                            </div>
                            <div class="user-center mt-3">
                                <div class="d-flex moblie-res">
                                    <div class="subxcribe-text">
                                        subscribe to receive updates on upcoming ByVotes events!
                                    </div>
                                    <button class="btn btn-sm btn-Earn btn-Subscribe ex--sub">Subscribe</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="votes-contents mt-3">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link owl-nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                        aria-selected="true">All
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link owl-nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">In Progress
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link owl-nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">Completed
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <div class="votes-contents-boxs">

                                    @if($trades->isNotEmpty())
                                        @foreach($trades as $trade)

                                            <div class="votes-contents-box">
                                                <div class="votes-contents-box-complet">
                                                    <div class="votes-contents-box-complet-text">Completed</div>
                                                </div>
                                                <div class="main-content">
                                                    <div class="img">
                                                        <img
                                                            src="{{ getImage(getFilePath('currency') .'/'.$trade->image,getFileSize('currency')) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="text-contents">
                                                        <div class="one">
                                                            <h6>{{$trade->name}}</h6>
                                                            <i class="fa-solid fa-up-right-from-square"></i>
                                                        </div>
                                                        <div class="two">AI</div>
                                                    </div>
                                                    <div class="Progress_progressContainer">
                                                        <div class="Progress_percentageText-text">100%</div>
                                                        <div class="inline-block">
                                                            <div class="progresschange">Listing Odds</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="votes-contents-box-details">
                                                    {{$trade->short_details}}
                                                    <div class="votes-contents-box-details-more">
                                                    </div>
                                                </div>
                                                <div class="votes-contents-box-other">
                                                    <div>
                                                        <p>Total Prize Pool</p>
                                                        <p class="one">${{$trade->total_prize}}</p>
                                                    </div>
                                                    <div>
                                                        <p>Total Votes</p>
                                                        <p class="one">1,000</p>
                                                    </div>
                                                    <div>
                                                        <p>Your Votes</p>
                                                        <p class="one">1,000</p>
                                                    </div>
                                                </div>
                                                <button class="btn btn-Earn trade-copy-btn btn-edit buyCopyTrade"
                                                        data-name="{{strtoupper($trade->name)}}"
                                                        data-id="{{$trade->id}}">Join Now
                                                </button>

                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center pt-4 pb-4 w-100">
                                            <h5 class="text-center">
                                                No Data Found
                                            </h5>
                                        </div>
                                    @endif


                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                 aria-labelledby="pills-profile-tab">

                                <div class="votes-contents-boxs">

                                    @if($by_votes_buy->isNotEmpty())
                                        @foreach($by_votes_buy as $votes_buy)

                                            <div class="votes-contents-box">
                                                <div class="votes-contents-box-complet">
                                                    <div class="votes-contents-box-complet-text">In-Progess</div>
                                                </div>
                                                <div class="main-content">
                                                    <div class="img">
                                                        <img
                                                            src="{{ getImage(getFilePath('currency') .'/'.$votes_buy->trade->image,getFileSize('currency')) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="text-contents">
                                                        <div class="one">
                                                            <h6>{{$votes_buy->trade->name}}</h6>
                                                            <i class="fa-solid fa-up-right-from-square"></i>
                                                        </div>
                                                        <div class="two">AI</div>
                                                    </div>
                                                    <div class="Progress_progressContainer">
                                                        <div class="Progress_percentageText-text">100%</div>
                                                        <div class="inline-block">
                                                            <div class="progresschange">Listing Odds</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="votes-contents-box-details">
                                                    {{$votes_buy->trade->short_details}}
                                                    <div class="votes-contents-box-details-more">
                                                    </div>
                                                </div>
                                                <div class="votes-contents-box-other">
                                                    <div>
                                                        <p>Total Prize Pool</p>
                                                        <p class="one">${{$votes_buy->trade->total_prize}}</p>
                                                    </div>
                                                    <div>
                                                        <p>Total Votes</p>
                                                        <p class="one">1,000</p>
                                                    </div>
                                                    <div>
                                                        <p>Your Votes</p>
                                                        <p class="one">1,000</p>
                                                    </div>
                                                </div>
                                                @php
                                                    $start_date=\Illuminate\Support\Carbon::parse($votes_buy->trade->start_date);
                                                @endphp
                                                @if($votes_buy->status=='buy' &&  $start_date < now())
                                                    <button class="btn btn-edit btn-sm trade-copy-btn withdrawCopyTrade"
                                                            data-name="{{strtoupper($votes_buy->trade->name)}}"
                                                            data-id="{{$votes_buy->id}}">Withdraw</button>
                                                @endif

                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center pt-4 pb-4 w-100">
                                            <h5 class="text-center">
                                                No Data Found
                                            </h5>
                                        </div>
                                    @endif


                                </div>


                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                 aria-labelledby="pills-contact-tab">

                                <div class="votes-contents-boxs">

                                    @if($by_votes_sell->isNotEmpty())
                                        @foreach($by_votes_sell as $votes_sell)

                                            <div class="votes-contents-box">
                                                <div class="votes-contents-box-complet">
                                                    <div class="votes-contents-box-complet-text">Completed</div>
                                                </div>
                                                <div class="main-content">
                                                    <div class="img">
                                                        <img
                                                            src="{{ getImage(getFilePath('currency') .'/'.$votes_selltrade->image,getFileSize('currency')) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="text-contents">
                                                        <div class="one">
                                                            <h6>{{$votes_sell->trade->name}}</h6>
                                                            <i class="fa-solid fa-up-right-from-square"></i>
                                                        </div>
                                                        <div class="two">AI</div>
                                                    </div>
                                                    <div class="Progress_progressContainer">
                                                        <div class="Progress_percentageText-text">100%</div>
                                                        <div class="inline-block">
                                                            <div class="progresschange">Listing Odds</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="votes-contents-box-details">
                                                    {{$votes_sell->trade->short_details}}
                                                    <div class="votes-contents-box-details-more">
                                                    </div>
                                                </div>
                                                <div class="votes-contents-box-other">
                                                    <div>
                                                        <p>Total Prize Pool</p>
                                                        <p class="one">${{$votes_sell->trade->total_prize}}</p>
                                                    </div>
                                                    <div>
                                                        <p>Total Votes</p>
                                                        <p class="one">1,000</p>
                                                    </div>
                                                    <div>
                                                        <p>Your Votes</p>
                                                        <p class="one">1,000</p>
                                                    </div>
                                                </div>
                                                <button disabled="disabled" class="btn btn-Earn trade-copy-btn disabled">Sold
                                                </button>

                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center pt-4 pb-4 w-100">
                                            <h5 class="text-center">
                                                No Data Found
                                            </h5>
                                        </div>
                                    @endif


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>






    @include('templates.basic.user.copy_trading.includes.modal')
@endsection

@push('script')
    @include('templates.basic.user.copy_trading.includes.modal_js')
@endpush
