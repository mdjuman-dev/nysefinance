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
        .other-data i{
            font-size: 13px;
            color: #fff;
        }
        .invite-header{
            padding: 20px 10px;
            position: relative;
            /* background-color: #101014; */
        }
        .invite-header-title{
            background: linear-gradient(163deg, #ffca5f -7.79%, #f79501 88.15%);
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            -webkit-text-fill-color: transparent;
            -webkit-background-clip: text;
            margin-bottom: 5px;
        }

        .invite-header-title-bg{
            background-image: url('{{asset('core/public/img/bgfull-a254eea0ab106ee16fc274a790bb0c72.png')}}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .invite-header p{
            font-size: 11px;
            color: #636060;
        }
        .invite-data{
            background-color: #101014;
            padding: 25px 10px;
        }
        .invite-data .header{
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .invite-data-item{
            border-radius: 16px;
            border: 1px solid rgba(247, 166, 0, .15);
            background: #16171a;
            height: 120px;
            padding: 14px 10px 10px;
            position: relative;
            display: flex;
            flex-direction: column;
            margin: 5px 0px;
            overflow: hidden;
        }
        .invite-data-item .bgimg{
            left: 0;
            position: absolute;
            top: 0;
            z-index: 1;
            display: block;
        }
        .get-up{
            color: #adb1b8;
            font-size: 10px;
            font-style: normal;
            font-weight: 400;
            margin-bottom: 4px;
            white-space: nowrap;
        }
        .amounts{
            background: linear-gradient(163deg, #ffca5f -7.79%, #f79501 88.15%);
            font-size: 16px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
            -webkit-text-fill-color: transparent;
            margin: 0 4px;
            -webkit-background-clip: text;
        }
        .usdt{
            color: #adb1b8 !important;
            font-size: 10px;
            font-style: normal;
            font-weight: 400;
            white-space: nowrap;
        }
        .btn-Invite{
            margin-top: auto;
            position: relative;
            z-index: 2;
            background-color: #f7a600;
            font-size: 11px;
            padding-left: 24px;
            font-weight: 600;
            padding-right: 24px;
            border-radius: .5rem;
        }
        .Trading-img{
            width: 30px;
            height: 30px;
            position: relative;
            margin-right: 5px;
            border-radius: 50%;
        }
        .friends{
            font-size: 11px;
            color: #fff;
        }
        @media (min-width: 992px) {
            .header-title h6{
                font-size: 16px;
            }
            .invite-header-title-bg {
                background-size: cover;
                padding: 50px;
            }
            .invite-header-title {
                font-size: 30px;
            }
            .invite-header p {
                font-size: 16px;
            }
            .invite-data .header {
                font-size: 16px;
                margin-bottom: 20px;
            }
            .invite-data {
                background-color: #101014;
                padding: 35px 30px;
            }
            .get-up {
                font-size: 16px;
            }
            .amounts {
                font-size: 25px;
            }
            .usdt {
                color: #adb1b8 !important;
                font-size: 13px;
                font-style: normal;
                font-weight: 400;
                white-space: nowrap;
            }
            .invite-data-item {
                height: 150px;
            }
            .Trading-img {
                width: 50px;
                height: 50px;
            }
            .friends {
                font-size: 16px;
            }
            .btn-Invite {
                font-size: 16px;
            }
        }
        @media (min-width: 768px) {
            .header-title h6{
                font-size: 16px;
            }
            .invite-header-title-bg {
                background-size: cover;
                padding: 0px;
            }
            .invite-header-title {
                font-size: 30px;
            }
            .invite-header p {
                font-size: 16px;
            }
            .invite-data .header {
                font-size: 16px;
                margin-bottom: 20px;
            }
            .invite-data {
                background-color: #101014;
                padding: 25px 15px;
            }
            .get-up {
                font-size: 16px;
            }
            .amounts {
                font-size: 25px;
            }
            .usdt {
                color: #adb1b8 !important;
                font-size: 13px;
                font-style: normal;
                font-weight: 400;
                white-space: nowrap;
            }
            .invite-data-item {
                height: 150px;
            }
            .Trading-img {
                width: 50px;
                height: 50px;
            }
            .friends {
                font-size: 16px;
            }
            .btn-Invite {
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
                        <h6 class="white">Invite Friends</h6>
                    </div>
                    <div class="other-data">
                        <i class="fa-regular fa-circle-question"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="invite-header">
                        <div class="row invite-header-title-bg">
                            <div class="col-8">
                                <div class="invite-header-title">
                                    <div>Invite Friends to Earn Over 1,717 USDT and 30% Commission</div>
                                </div>
                                <p>Earn rewards together with your friends via Spot, Derivatives, Copy Trading and NyseFinance Card</p>
                            </div>
                        </div>
                    </div>
                    <div class="invite-data">
                        <div class="header">One Invitation for Multiple Rewards </div>
                        <div class="row mt-2">
                            <div class="col-12 col-lg-6 col-md-6">
                                <div class="invite-data-item">
                                    <img src="{{asset('core/public/img/invite-card-color-009eaf1dc243c0d0506845a0d26971c5.png')}}" alt="" class="bgimg">
                                    <div class="row align-items-end">
                                        <div class="col-6">
                                            <div class="get-up">Get Up to</div>
                                            <span class="amounts"> <span>1,000</span> </span><span class="usdt">USDT</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="amounts"> <span>30%</span> </span><span class="usdt">Commission</span></div>
                                    </div>
                                    <div class="d-flex mt-3 justify-content-between align-items-center">
                                        <div class="one d-flex align-items-center">
                                            <img src="{{asset('core/public/img/hunt.jpeg')}}" alt="" class="Trading-img">
                                            <div class="friends">From inviting new friends to Trading</div>
                                        </div>
                                        <div class="two">
                                            <button class="btn btn-sm btn-Invite copyTextBtn">Copy Url</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-lg-6 col-md-6">
                                <div class="invite-data-item">
                                    <img src="{{asset('core/public/img/invite-card-color-009eaf1dc243c0d0506845a0d26971c5.png')}}" alt="" class="bgimg">
                                    <div class="row align-items-end">
                                        <div class="col-6">
                                            <div class="get-up">Get Up to</div>
                                            <span class="amounts"> <span>1,000</span> </span><span class="usdt">USDT</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="amounts"> <span>30%</span> </span><span class="usdt">Commission</span></div>
                                    </div>
                                    <div class="d-flex mt-3 justify-content-between align-items-center">
                                        <div class="one d-flex align-items-center">
                                            <img src="{{asset('core/public/img/hunt.jpeg')}}" alt="" class="Trading-img">
                                            <div class="friends">From inviting new friends to Trading</div>
                                        </div>
                                        <div class="two">
                                            <button class="btn btn-sm btn-Invite">Invite</button>
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


@endsection


@push('script')



@endpush
