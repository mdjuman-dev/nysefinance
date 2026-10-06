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
            font-size: 14px;
            font-weight: 500;
            color: #fff;
        }
        .services-title{
            font-size: 11px;
            margin: 15px 0;
            color: #636060;
        }

        /* SVG Icon Styles */
        .main-sec-icon svg {
            width: 24px;
            height: 24px;
        }

        /* Default color for all icons */
        .main-sec-icon svg {
            stroke: #f99c26; /* Default orange color */
        }

        /* Hover effect */
        .main-sec-icon:hover svg {
            stroke: #fff; /* White on hover */
        }

        /* Active state */
        .main-sec-icon.active svg {
            stroke: #fff;
        }

        /* Different colors for different sections */
        .buy-crypto-section .main-sec-icon svg {
            stroke: #4CAF50; /* Green for buy crypto section */
        }

        .trade-section .main-sec-icon svg {
            stroke: #ffb100;
        }

        /* Border styles */
        .main-sec-icon {
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .main-sec-icon:hover {
            background: rgba(249, 156, 38, 0.1); /* Light orange background on hover */
        }

        @media (min-width: 992px) {
            .search-bar {
                width: 50% !important;
            }
            .find-input{
                display: flex;
                justify-content: center;
            }
            .find-icon {
                left: 26%;
            }
            .add-icon {
                margin: 0px 5px;
                color: #fff;
            }
            .add-icon i {
                font-size: 18px;
            }
            .my-favorites-icons {
                width: 50%;
            }
            .btn-edit{
                float: right;
            }
            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }
            .icon {
                font-size: 16px;
            }
            .services-text {
                font-size: 14px;
            }
            .services-title {
                font-size: 16px;
            }
            .header-title h6{
                font-size: 16px;
            }
        }
        @media (min-width: 768px) {
            .search-bar {
                width: 50% !important;
            }
            .find-input{
                display: flex;
                justify-content: center;
            }
            .find-icon {
                left: 26%;
            }
            .add-icon {
                margin: 0px 5px;
                color: #fff;
            }
            .add-icon i {
                font-size: 18px;
            }
            .my-favorites-icons {
                width: 50%;
            }
            .btn-edit{
                float: right;
            }
            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }
            .icon {
                font-size: 16px;
            }
            .services-text {
                font-size: 14px;
            }
            .services-title {
                font-size: 16px;
            }
            .my-favorites{
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .my-favorites h6{
                font-size: 16px;
            }
            .header-title h6{
                font-size: 16px;
            }
        }

        .add-icon{
            margin-left: 5px !important;
        }
        .add-icon img{
            height: 23px;
            width: 23px;
            margin: 0px 3px;
        }
        .btn-edit{
            padding: 7px 13px !important;
        }
        .main-sec-icon img{
            height: 100%;
        }
        .main-sec-icon{
            height: 50px;
        }

        .services-title{
            font-size: 16px !important;
            margin: 15px 0 !important;
            color: #7e7e7e !important;
        }

        .all-other-services svg{
            color: #f99c26;
            border: 1px solid #ffffff;
            border-radius: 50%;
            padding: 8px;
            height: 40px;
            width: 40px;
        }
        .header-section-icons svg{
            color: #f99c26;
            border: 1px solid #ffffff;
            border-radius: 50%;
            padding: 4px;
            height: 35px;
            width: 35px;
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
                        <h6 class="white">Services</h6>
                    </div>
                    <div class="other-data"></div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="find-input">
                        <i class="fa-solid fa-magnifying-glass find-icon"></i>
                        <input type="text" placeholder="Search" class="search-bar form-control">
                    </div>
                    <div class="my-favorites">
                        <h6 class="white">My Favorites</h6>
                        <div class="my-favorites-icons">
                            <div class="row">
                                <div class="col-9 header-section-icons">
                                    <div class="add-icons">
                                        <div class="add-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 3V21H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7 12L10 9L13 12L17 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="add-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 3L20 7V17L12 21L4 17V7L12 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 8V16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M8 10L12 8L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="add-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="4" y="4" width="12" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                                                <rect x="8" y="8" width="12" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                                                <path d="M8 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M12 8V20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <div class="add-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                <path d="M12 4V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M12 16V20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M4 12H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M16 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <div class="add-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M4 12H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M16 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <div class="add-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 8C9.79 8 8 9.79 8 12C8 14.21 9.79 16 12 16C14.21 16 16 14.21 16 12C16 9.79 14.21 8 12 8Z" stroke="currentColor" stroke-width="2"/>
                                                <path d="M12 4V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M12 16V20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M4 12H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M16 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-edit btn-sm">Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="all-other-services">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link owl-nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                        aria-selected="true">Recommended</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link owl-nav-link coming_soon" id="pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">Buy Crypto</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link owl-nav-link coming_soon" id="pills-contact-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">Trade</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <div class="services-data">
                                    <div class="row align-items-center main-sec-all-icons">

                                        <div data-url="{{route('user.stock.index')}}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 3V21H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 12L10 9L13 12L17 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Stock
                                            </div>
                                        </div>

                                        <div data-url="{{route('user.invite.friend')}}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 4V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M12 16V20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M4 12H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M16 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Invite Friends
                                            </div>
                                        </div>

                                        <div data-url="{{route('user.reward.hub')}}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8 10L12 7L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Rewards hub
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row align-items-center main-sec-all-icons buy-crypto-section">
                                        <div class="col-12">
                                            <div class="services-title">
                                                Buy Crypto
                                            </div>
                                        </div>
                                        <div data-url="{{route('user.wallet.overview',['sc'=>'tr'])}}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 3V21H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 12L10 9L13 12L17 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Deposit
                                            </div>
                                        </div>
                                        <div data-url="{{route('user.wallet.overview',['wh'=>'wi'])}}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 4V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M12 16V20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M4 12H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M16 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Withdraw
                                            </div>
                                        </div>



                                        @if(checkAgent())
                                            <div data-url="{{ route('user.p2p.dashboard') }}" class="click-icon col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                                <div class="icon main-sec-icon" style="    padding: 5px !important;">
                                                    <img src="{{asset('core/public/icon/p2p-trading.svg')}}" alt="" style="border: 1px solid #ffffff;  border-radius: 50px;  padding: 8px;">
                                                </div>
                                                <div class="services-text">
                                                    P2P Trading
                                                </div>
                                            </div>

                                        @else

                                            <div data-url="{{ route('p2p') }}" class="click-icon col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                                <div class="icon main-sec-icon" style="    padding: 5px !important;">
                                                    <img src="{{asset('core/public/icon/p2p-trading.svg')}}" alt="" style="border: 1px solid #ffffff;  border-radius: 50px;  padding: 8px;">
                                                </div>
                                                <div class="services-text">
                                                    P2P Trading
                                                </div>
                                            </div>

                                        @endif


                                        <div data-url="{{route('user.wallet.overview',['sc'=>'tr'])}}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 3V21H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 12L10 9L13 12L17 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Fiat Deposit
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center main-sec-all-icons trade-section">
                                        <div class="col-12">
                                            <div class="services-title">
                                                Trade
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.classic.trading') }}" class="col-3 click-icon col-lg-2  col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 3V21H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 12L10 9L13 12L17 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M4 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                 Classic Trade
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.gold.fx.trading') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 7L12 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8 12L16 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M15 9L9 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                 Gold-FX
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.otc.trading') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 4H20V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V4Z" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M4 8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8 12H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8 16H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                 OTC Trade
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.puzzle.hunt.trading') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="4" y="4" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                                                    <rect x="13" y="4" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                                                    <rect x="4" y="13" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                                                    <rect x="13" y="13" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Puzzle Hunt
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.token.splash.trading') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 7V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M12 15V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M15.5 8.5L14 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M10 14L8.5 15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Token Splash
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.by.votes.trading') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 4L14 8H10L12 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                    <rect x="6" y="10" width="12" height="10" rx="1" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M9 14H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M9 17H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                 By Votes
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.mt5') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 4H20V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V4Z" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M4 8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M7 4V8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 4V8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M17 4V8" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                 MT-5
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.spot.x') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M9 9L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M15 9L9 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                 Spot X
                                            </div>
                                        </div>
                                        <div data-url="{{route('user.convert')}}" class="col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2 click-icon">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 4L8 8L12 12L16 8L12 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                    <path d="M12 12L8 16L12 20L16 16L12 12Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                    <path d="M12 12L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M12 12L20 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Convert
                                            </div>
                                        </div>

                                        <div data-url="{{  route('user.twofactor') }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="2"/>
                                                    <path d="M12 11V7a3 3 0 0 1 6 0v4" stroke-width="2"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Security
                                            </div>
                                        </div>


                                        <div data-url="{{  route('user.wallet.overview',['sc'=>'tr']) }}" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="2"/>
                                                    <path d="M12 11V7a3 3 0 0 1 6 0v4" stroke-width="2"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Deposit
                                            </div>
                                        </div>

                                        <div data-url="https://www.livecoinwatch.com" class="col-3 click-icon col-lg-2 col-md-2 p-0 text-center mt-2 mb-2">
                                            <div class="icon main-sec-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <ellipse cx="12" cy="7" rx="8" ry="4" stroke-width="2"/>
                                                    <path d="M4 7v10c0 2.2 3.6 4 8 4s8-1.8 8-4V7" stroke-width="2"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Coin
                                            </div>
                                        </div>



                                        <div data-url="{{ route('user.trade.gpt') }}" class="col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2 click-icon">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                                                    <circle cx="12" cy="10" r="2" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M8 16C8 14.8954 9.79086 14 12 14C14.2091 14 16 14.8954 16 16" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M7 7L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Trade ZPT
                                            </div>
                                        </div>
                                        <div  data-url="{{ route('user.launch.pool') }}" class="col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2 click-icon">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M15 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6C4 4.89543 4.89543 4 6 4H9" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M9 9L12 12L15 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 16H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                LaunchPool
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.leader.board') }}" class="col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2 click-icon">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 4L17 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M7 20L17 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                               LeaderBoard
                                            </div>
                                        </div>
                                        <div data-url="{{ route('user.bonds') }}" class="col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2 click-icon">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7 4L17 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M7 20L17 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                               Explore
                                            </div>
                                        </div>

                                        <div class="col-3 col-lg-2 col-md-2 p-0 text-center mt-2 mb-2 coming_soon">
                                            <div class="icon main-sec-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M8 8H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8 12H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8 16H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <circle cx="18" cy="6" r="2" fill="none" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </div>
                                            <div class="services-text">
                                                Demo
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                 aria-labelledby="pills-profile-tab">...</div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                 aria-labelledby="pills-contact-tab">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('script')

    <script>

        $(document).on('click', '.click-icon', function (e){

            const url=$(this).attr('data-url');

            location.href=url;

        });


    </script>

@endpush
