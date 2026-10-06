<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    {{--    @include('partials.seo')--}}

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}"/>

    @stack('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.css"/>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>


    @if (session('app'))
        <style>
            .dashboard-header,
            .dashboardBodyNav {
                display: none !important;
            }
        </style>
    @endif

    <style>
        .dashboard__right {
            padding-bottom: 130px;
        }

        .btn {
            padding: 10px 20px !important;
        }

        .side-menu-refer-link {
            display: none;
        }

        .m-user-image {
            display: none;
        }

        .mobile-footer-menu {
            display: none;
        }

        .username-for-m {
            display: block;
        }

        .dashboard-mob-view {
            display: block;
        }

        .section-stock-trade-view {
            display: none;
        }

        .ticker-tape-widget {
            display: none;
        }

        .sub-small-icon-section {
            display: none;
        }

        @media (max-width: 700px) {
            .dashboard-header__right {
                display: flex;
                justify-content: space-between;
            }

            .username-for-m {
                display: none;
            }

            .m-user-image {
                display: block;
                width: 46px;
                height: 45px;
                float: right;
                border-radius: 50px;
            }

            .user-info__button::before {
                display: none;
            }

            .sidebar-logo__link {
                display: none !important;
            }

            .side-menu-refer-link {
                display: block !important;
                background: #3b3d3b;
                padding: 10px 0px;
                color: white !important;
                border-radius: 5px;
            }

            .sidebar-logo {
                margin: 50px 10px 10px 10px !important;
            }

            .side-menu-refer-link copyTextBtn {
                color: white !important;
                margin-left: 5px;
            }

            .side-menu-refer-link input {
                background: initial !important;
                border: hidden !important;
                color: white;
            }

            .dashboard-header__inner {
                padding-top: 0px !important;
            }

            .mobile-footer-menu {
                display: block !important;
                position: fixed;
                bottom: 0px;
                width: 100%;
                z-index: 99999999999;
                background: #212121;
                padding: 5px 9px 10px 9px;
            }

            .m-menu-icon-sec .footer-nav-item.active{
                background: #323232;
                border: 1px solid #6e6e6e;

            }
            .m-menu-icon-sec .footer-nav-item{
                border: 1px solid #323232;
                padding:3px 8px;
                border-radius: 5px;
            }

            .m-menu-icon-sec i {
                font-size: 20px !important;
                color: #777777;
            }

            .dashboard__inner {
                padding-bottom: 150px !important;
            }

            .dashboard-header {
                position: fixed;
                top: 0px;
                width: 100%;
                z-index: 9;
            }

            .dashboard-body {
                margin-top: 53px;
                /*padding-top: 15px !important;*/
            }

            .dashboard-fluid .user-info__right {
                margin-top: 11px !important;
            }

            .dashboard-header__inner {
                padding-bottom: 8px !important;
            }

            .m-user-image {
                width: 40px !important;
                height: 40px !important;
            }

            .dashboard-fluid .dashboard-sidebar-filter__button i {
                font-size: 24px !important;
            }

            .dashboard-fluid .dashboard-sidebar-filter__button {
                padding: 0px !important;
                background: transparent !important;
            }

            .n-view-balance {
                display: block !important;
            }

            .sidebar-menu__inner {
                padding-bottom: 150px;
            }

            .dashboard-mob-view {
                display: none;
            }

            .section-stock-trade-view {
                display: flex;
            }

            .transection__title {
                margin-top: 40px;
            }

            .ticker-tape-widget {
                display: block;
            }

            .small-sub-icon a {
                text-align: center;
            }

            .small-sub-icon a span {
                display: block !important;
            }

            .small-sub-icon a i {
                background: #ff5200;
                padding: 5px 7px;
                font-size: 20px;
                border-radius: 5px;
                color: #0c0c0c;
            }

            .pagination .page-item .page-link {
                width: 80px !important;
                border-radius: 5px !important;
            }

            .mobile-user-sidebar {
                display: block !important;
                width: 100% !important;
                padding-bottom: 100px !important;
            }

            .desktop-user-sidebar {
                display: none !important;
            }

            .account-section {
                background: #2C2D27;
                padding: 17px;
                border-radius: 14px;
                color: white;
            }

            .account-section .copyTextBtn {
                margin-left: 5px;
                color: white !important;
            }

            .account-section .m-user-image {
                width: 50px !important;
                height: 50px !important;
            }

            .sidebar-menu.show-sidebar {
                width: 100%;
            }

            .separate-section-sidebar h6 {
                margin-left: 20px;
                color: white;
            }

            .separate-section-sidebar {
                margin-top: 30px;
                background: #2c2d27;
                border-radius: 10px;
                padding: 17px 5px;
            }

            .separate-section-sidebar .sidebar-menu-list__link .fa-angle-right {
                float: right;
            }

            .separate-section-sidebar .sidebar-menu-list__link .text {
                width: 100%;
            }

            .separate-section-sidebar .sidebar-menu-list__link {
                display: flex !important;
                flex-wrap: nowrap !important;
            }

            .separate-dashboard .sidebar-menu-list__link .fa-angle-right {
                float: right;
            }

            .separate-dashboard .sidebar-menu-list__link .text {
                width: 100%;
            }

            .separate-dashboard .sidebar-menu-list__link {
                display: flex !important;
                flex-wrap: nowrap !important;
            }

            .separate-dashboard {
                background: #2c2d27;
                border-radius: 10px;
                margin-top: 28px;
            }

            .dashboard-fluid .sidebar-menu-list {
                margin-top: 50px !important;
            }

            .single-weidgh-hart .livecoinwatch-widget-6 {
                width: 98% !important;
            }

            .sm-icon-main-section .sec-sm-icons {
                width: 40px;
                text-align: center;
                padding: 10px 10px;
                border: 1px solid #a3a2a2;
                border-radius: 5px;
                color: white;
                background: #1f2434b0;
            }

            .sm-icon-main-section .icon-t-section {
                margin-top: 5px;
                font-size: 11px;
            }

            .sm-icon-main-section {
                justify-content: center;
                display: flex;
                align-items: center;
                flex-direction: column;
            }

            .sub-small-icon-section {
                margin-top: 20px;
                margin-bottom: 20px;
                display: block;
            }

            .sub-small-icon-section .sm-icon-main-section {
                width: 20% !important;
                padding-left: 6px !important;
                padding-right: 6px !important;
            }

            .deposit-con-sec-for-m {
                display: none !important;
            }

            .recent-tran-sec {
                display: none;
            }

            .user-info__right {
                display: none !important;
            }

            .dashboard-sidebar-filter__button.m-auto {
                margin-top: 11px !important;
            }

            .currency-font {
                font-size: 14px;
                color: white;
            }

            .n-view-balance {
                color: #e3e1e1 !important;
            }

            .scroll-top {
                display: none !important;
            }

            .bal-font {
                color: white !important;
            }

            #owl-demo {
                visibility: hidden;
            }

            .dashboard-right.show {
                width: 100% !important;
            }

        }

        .desktop-user-sidebar {
            background: #111e21 !important;
        }

        .desktop-user-sidebar {
            display: block;

        }

        .mobile-user-sidebar {
            display: none;
        }

        .m-menu-icon-sec {
            width: 16%;
            text-align: center;
        }

        .m-menu-icon-sec .icon-text-title {
            display: block;
            font-size: 9px;
            color: #999898 !important;
            font-weight: 700;
            font-family: monospace !important;
        }

        .mobile-menu-main-section {
            display: flex;
            justify-content: center;
        }

        .m-menu-icon-sec .active i {
            color: white !important;
        }

        .m-menu-icon-sec .active .icon-text-title {
            color: white !important;
        }
        .m-menu-icon-sec .active svg path,rect{
            /*stroke: rgb(249, 156, 38) !important;*/
            /*stroke-width: 2 !important;*/
            /*fill: rgb(249, 156, 38) !important;*/
        }
        .main-balance-hide {
            font-size: 20px;
            color: #a7a7a7;
            font-weight: 500;
            display: none;
        }

        .main-balance {
            font-size: 20px;
            color: #a7a7a7;
            font-weight: 500;
        }

        .hide-balance, .show-balance {
            cursor: pointer;
            margin-left: 7px;
            color: #9b9696;
        }

        .n-view-balance {
            display: none;
        }

        .badge-verified {
            color: #00db00;
            background: #0eb90e47;
            border-radius: 5px;
            padding: 2px 10px;
            font-size: 12px;
            margin-top: 5px;
        }

        .badge-pending {
            color: #fd0000;
            background: #bd0b0b47;
            border-radius: 5px;
            padding: 2px 10px;
            font-size: 12px;
            margin-top: 5px;
        }

        .select2-container .select2-selection--single {
            height: 50px !important;
        }

        .today-pnl {
            font-size: 12px;
            color: #12d712;
        }

        .offcanvas.show {
            width: 100% !important;
        }
        .icon .custom-css{
            border: 1px solid #ffffff;
            color: #f99c26;
            font-size: 13px !important;
            padding: 7px 8px;
            border-radius: 50%;
        }
        .section-blur-css{
            filter: blur(5px);
        }
        .dashboard-body{
            background: #000000 !important;
        }

        .mob-hdr-section{
            padding: 10px 0px 0px 0px !important;
        }
        .header-icon-sec{
            text-align: center;
        }
        .header-option.active{
            background:#545050 !important;
        }
        .header-option{
            background: #373737;
            padding: 4px 20px;
            font-size: 11px;
            font-weight: 900;
            border-radius: 4px;
            width: 120px;
            text-align: center;
        }

        .mob-hdr-section{
            display: none;
        }

        @media (max-width: 750px) {
            .mob-hdr-section{
                display: flex !important;
            }
        }

        .top-for-dashboard{
            display: none;
        }
        .copy-uid i,svg{
            margin-left: 4px;
        }
        .copy-uid{
            padding: 3px 0px 4px 0px;
            font-size: 11px;
            border-radius: 5px;
            margin-left: 5px;
            opacity: 0.6;
        }

    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


    <style>
        /*.countdown-box {*/
        /*    background: #f8f9fa;*/
        /*    border-radius: 10px;*/
        /*    padding: 10px 15px;*/
        /*    min-width: 70px;*/
        /*    box-shadow: 0 2px 6px rgba(0,0,0,0.1);*/
        /*}*/
        /*.countdown-time {*/
        /*    font-size: 1.5rem;*/
        /*    font-weight: bold;*/
        /*    color: #0d6efd;*/
        /*}*/
        /*.countdown-label {*/
        /*    font-size: 0.8rem;*/
        /*    text-transform: uppercase;*/
        /*    color: #6c757d;*/
        /*}*/


        .countdown-box {
            /*background: #f8f9fa;*/
            border-radius: 10px;
            padding: 10px 15px;
            min-width: 70px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .countdown-time {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fad300 !important;
        }
        .countdown-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #fad300 !important;
        }
        #comingSoonModal .modal-body h5{
            color: white !important;
        }
        #comingSoonModal .modal-title{
            color: #fad300 !important;
        }
        #comingSoonModal .modal-content{
            background: #333333;
        }


    </style>

    @stack('ip-css')

</head>
@php echo loadExtension('google-analytics') @endphp

<body>
@if (!request()->routeIs('user.home'))
    <div class="preloader">
        <div class="loader-p">
            <img src="{{asset('core/public/en/images/load.png')}}">
        </div>
    </div>
@endif

<div class="body-overlay"></div>
<div class="sidebar-overlay"></div>
<a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

<div class="dashboard-fluid position-relative">
    <div class="dashboard__inner">
        @include($activeTemplate . 'partials.user_sidebar')
        <div class="dashboard__right">
            @include($activeTemplate . 'partials.user_topbar')
            <div class="dashboard-body">
                <div class="d-flex justify-content-between mb-3 align-items-center dashboardBodyNav">



                    @if (request()->routeIs('user.home'))
                        <div class="dashboard-body__bar style deposit-con-sec-for-m">
                            <span class="dashboard-body__bar-two-icon toggle-dashboard-right"><i
                                    class="fas fa-bars"></i></span>
                        </div>
                    @endif

                    @if (request()->routeIs('user.p2p*'))
                        <div class="p2p-sidebar__menu">
                                <span class="p2p-sidebar__menu-icon">
                                    <i class="fas fa-bars"></i>
                                </span>
                        </div>
                    @endif

                </div>
                @stack('topContent')
                @yield('content')
            </div>
        </div>
    </div>
</div>

<div class="mobile-footer-menu">
    <div class="mobile-menu-main-section">
        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{ route('user.home') }}" class="footer-nav-item {{ menuActive('user.home') }}">
                <span class="footer-icon-svg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 10.5L12 4L21 10.5V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V10.5Z" stroke="{{ request()->routeIs('user.home') ? '#f99c26' : '#fff' }}" stroke-width="2" fill="{{ request()->routeIs('user.home') ? '#f99c26' : 'none' }}"/>
                        <rect x="8" y="14" width="8" height="7" rx="1" fill="#212121"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('user.home') ? '#f99c26' : '#fff' }}">Home</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{route('user.stock.index')}}" class="footer-nav-item {{ menuActive('user.stock.*') }}">
                <span class="footer-icon-svg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="10" width="3" height="7" rx="1" fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                        <rect x="10.5" y="7" width="3" height="10" rx="1" fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                        <rect x="17" y="4" width="3" height="13" rx="1" fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}">Stock</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{route('user.bonds')}}" class="footer-nav-item {{ menuActive('user.bonds') }}">
                <span class="footer-icon-svg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17 12H7C4.79086 12 3 13.7909 3 16V16C3 18.2091 4.79086 20 7 20H17C19.2091 20 21 18.2091 21 16V16C21 13.7909 19.2091 12 17 12ZM7 12C4.79086 12 3 10.2091 3 8V8C3 5.79086 4.79086 4 7 4H17C19.2091 4 21 5.79086 21 8V8C21 10.2091 19.2091 12 17 12Z"
                            stroke="{{ request()->routeIs('bond') ? '#f99c26' : '#fff' }}" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('bonds') ? '#f99c26' : '#fff' }}">Explore</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{ route('trade') }}" class="footer-nav-item {{ menuActive('trade') }}">
                <span class="footer-icon-svg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="17" width="18" height="2" rx="1" fill="{{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}"/>
                        <path d="M8 17V7L12 11L16 7V17" stroke="{{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}" stroke-width="2"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}">Trade</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{route('futures')}}" class="footer-nav-item {{ menuActive('futures') }}">
                <span class="footer-icon-svg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}" stroke-width="2"/>
                        <text x="12" y="16" text-anchor="middle" fill="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}" font-size="10" font-family="Arial" dy="-2">$</text>
                        <path d="M16 8L18 6" stroke="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('futures') ? '#f99c26' : '#fff' }}">Futures</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{route('user.wallet.overview')}}" class="footer-nav-item {{ menuActive('user.wallet.*') }}">
                <span class="footer-icon-svg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="7" width="16" height="10" rx="2" stroke="{{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}" stroke-width="2"/>
                        <rect x="8" y="11" width="8" height="2" rx="1" fill="{{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}">Assets</span>
            </a>
        </div>

    </div>
</div>

@if(gs('notice_file'))
    <!-- Modal -->
    <div class="modal fade" id="chmsModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header"
                     style="justify-content: right;padding-top: 0px !important; padding-bottom: 0px !important;">
                    <button type="button" class="closeChms" data-dismiss="modal" aria-label="Close">
                        <span style="font-size: 30px" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="section-image">
                        <img src="https://nysefinance.com/assets/images/currency/wh-notice.jpeg" alt="">
                         <!--<img src="{{ getImage(getFilePath('currency') .'/'.gs('notice_file'),getFileSize('currency')) }}" alt="">-->
                        
                    </div>
                </div>

            </div>
        </div>
    </div>

@endif




<!-- Modal -->
<div class="modal fade" id="comingSoonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-primary">ðŸš€ Coming Soon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h5 class="mb-4 text-muted">Something awesome is on the way!</h5>
                <div id="countdown" class="d-flex justify-content-center gap-3"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


{{--<div class="iziToast-wrapper iziToast-wrapper-topCenter">--}}
{{--    <div class="iziToast-capsule" style="height: auto;">--}}
{{--        <div data-izitoast-ref="1762024721458"--}}
{{--             class="iziToast fadeInUp iziToast-theme-light iziToast-color-green iziToast-animateInside iziToast-opened"--}}
{{--             id="U3VjY2Vzc1lvdSUyMGFscmVhZHklMjBzdWJtaXR0ZWQlMjBhJTIwYXBwbGljYXRpb24lMkMlMjB3ZSUyMHdpbGwlMjBub3RpZnklMjB5b3UlMjBzb29uLmdyZWVu"--}}
{{--             style="background: rgb(255, 255, 255);">--}}
{{--            <div class="iziToast-body" style="padding-left: 33px;">--}}
{{--                <svg class="svg-inline--fa fa-circle-check iziToast-icon" style="color: rgb(40, 199, 111);"--}}
{{--                     aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-check" role="img"--}}
{{--                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">--}}
{{--                    <path fill="currentColor"--}}
{{--                          d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"></path>--}}
{{--                </svg>--}}
{{--                <!-- <i class="iziToast-icon fas fa-check-circle" style="color: rgb(40, 199, 111);"></i> Font Awesome fontawesome.com -->--}}
{{--                <div class="iziToast-texts"><strong class="iziToast-title slideIn"--}}
{{--                                                    style="color: rgb(71, 71, 71); font-size: 1rem; margin-right: 10px;">Success</strong>--}}
{{--                    <p class="iziToast-message slideIn" style="color: rgb(162, 162, 162); font-size: 1rem;">You already--}}
{{--                        submitted a application, we will notify you soon.</p></div>--}}
{{--                <div></div>--}}
{{--            </div>--}}
{{--            <button type="button" class="iziToast-close"></button>--}}
{{--            <div class="iziToast-progressbar">--}}
{{--                <div style="background: rgb(40, 199, 111); transition: width 5000ms linear; width: 0%;"></div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


<script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if(gs('notice_status') && gs('notice_status')=='enable' && gs('notice_file'))

    <script>

        $(document).ready(function () {
            // Check if modal should be shown
            const modalKey = 'chmsModalClosed';
            const lastClosed = localStorage.getItem(modalKey);

            // Check the current time
            const now = new Date().getTime();

            // Show modal only if not closed in the last 6 hours
            if (!lastClosed || now - lastClosed > 6 * 60 * 60 * 1000) {
                $('#chmsModal').modal('show');
            }

            // Save the close event time
            $('#chmsModal').on('hidden.bs.modal', function () {
                localStorage.setItem(modalKey, new Date().getTime());
            });
        });

        $(document).on('click', '.closeChms', function(e){
            $('#chmsModal').modal('hide');
        });

    </script>

@endif



<script>
    // Get or set the countdown target date
    let targetDate = localStorage.getItem("comingSoonDate");
    if (!targetDate) {
        let now = new Date();
        now.setMonth(now.getMonth() + 3); // 3 months from now
        targetDate = now;
        localStorage.setItem("comingSoonDate", targetDate.toISOString());
    } else {
        targetDate = new Date(targetDate);
    }

    // Countdown function
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance <= 0) {
            document.getElementById("countdown").innerHTML = `<h5 class="text-success">ðŸŽ‰ We're live!</h5>`;
            clearInterval(timer);
            return;
        }

        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML = `
        <div class="countdown-box">
          <div class="countdown-time">${days}</div>
          <div class="countdown-label">Days</div>
        </div>
        <div class="countdown-box">
          <div class="countdown-time">${hours}</div>
          <div class="countdown-label">Hours</div>
        </div>
        <div class="countdown-box">
          <div class="countdown-time">${minutes}</div>
          <div class="countdown-label">Mins</div>
        </div>
        <div class="countdown-box">
          <div class="countdown-time">${seconds}</div>
          <div class="countdown-label">Secs</div>
        </div>
    `;
    }

    // Start countdown
    let timer = setInterval(updateCountdown, 1000);
    updateCountdown();

    // Auto-show the modal on page load
    // window.addEventListener("load", function() {
    //     var myModal = new bootstrap.Modal(document.getElementById('comingSoonModal'));
    //     myModal.show();
    // });
</script>


<script>

    jQuery('button[type="submit"]').on('click', function (e) {
        var form = $(this).parents('form:first');
        if (form) {
            $(this).attr('disabled', 'disabled').addClass('disabled')
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');
            form.submit();
        }
    });


    $(document).ready(function () {
        // Prevent anchor tags inside the tradingview widget container
        $('.tradingview-widget-container').on('click', 'a', function (event) {
            event.preventDefault(); // Prevent the default behavior of the anchor tags
        });
    });


    $(document).on('click', '.coming_soon', function (e){

        $('#comingSoonModal').modal('show');
    });

    $(document).on('click', '.sec-sm-icons', function (e) {
        const data_url = $(this).attr('data-url');
        if (!data_url) {
            return;
        }

        location.href = data_url;
    });
</script>


<script>
    // Create or modify the viewport meta tag
    function disableZoom() {
        let metaViewport = document.querySelector('meta[name="viewport"]');
        if (!metaViewport) {
            metaViewport = document.createElement('meta');
            metaViewport.name = "viewport";
            document.head.appendChild(metaViewport);
        }
        metaViewport.content = "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no";
    }

    // Call the function to apply the viewport settings
    disableZoom();
</script>


<script>
    (function ($) {

        $.each($('.canvas-select2'), function (index, element) {
            $('.canvas-select2').select2({
                dropdownParent: $(this).closest('.position-relative')
            });
        });

        $('.2fa-notice').on('click', '.delete-icon', function (e) {
            $(this).closest('.col-12').fadeOut('slow', function () {
                $(this).remove();
            });
        });

        let walletSkip = 3;

        $('.show-more-wallet').on('click', function (e) {
            let route = "{{ route('user.more.wallet', ':skip') }}";
            let $this = $(this);
            $.ajax({
                url: route.replace(':skip', walletSkip),
                type: "GET",
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    $this.html(`
                        <span class="right-sidebar__button-icon">
                            <i class="las la-spinner la-spin"></i>
                        </span>`).attr('disabled', true);
                },
                complete: function (e) {
                    setTimeout(() => {
                        $this.html(`
                        <span class="right-sidebar__button-icon">
                            <i class="las la-chevron-circle-down"></i>
                        </span>@lang('Show More')`).attr('disabled', false);
                        $('.wallet-list').removeClass('skeleton');
                    }, 500);
                },
                success: function (resp) {
                    if (resp.success && (resp.wallets && resp.wallets.length > 0)) {
                        let html = "";
                        $.each(resp.wallets, function (i, wallet) {
                            html += `
                            <div class="right-sidebar__item wallet-list skeleton">
                                <div class="d-flex align-items-center">
                                    <span class="right-sidebar__item-icon">
                                        <img src="${wallet.currency.image_url}">
                                    </span>
                                    <h6 class="right-sidebar__item-name">
                                        ${wallet.currency.name}
                                        <span class="fs-11 d-block">
                                            ${wallet.currency.symbol}
                                        </span>
                                    </h6>
                                </div>

                                <h6 class="right-sidebar__item-number">${getAmount(wallet.balance)}</h6>
                            </div>
                            `
                        });
                        walletSkip += 3;
                        $('.wallet-wrapper').append(html);
                    } else {
                        $this.remove();
                    }

                    $('.right-sidebar__menu').animate({
                        scrollTop: $('.right-sidebar__menu')[0].scrollHeight + 150
                    }, "slow");
                },
                error: function () {
                    notify('error', "@lang('Something went to wrong')");
                    $this.remove();
                }
            });
        });


    })(jQuery);
</script>

@if(request()->get('type') && request()->get('type')=='deposit')

    <script>
        $(document).ready(function () {

            $('.toggle-dashboard-right').trigger('click');
        })
    </script>

@endif


<script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
@stack('script-lib')

<script src="{{ asset($activeTemplateTrue . 'dashboard/js/main.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote.min.js"></script>


<script>
    window.allow_decimal = "{{ gs('allow_decimal_after_number') }}";
</script>

@include('partials.notify')

@php echo loadExtension('tawk-chat') @endphp

@if (gs('pn'))
    @include('partials.push_script')
@endif

@stack('script')

<script>

    $(document).ready(function () {
        let preViewBalance = localStorage.getItem('viewBalance');
        if (preViewBalance && preViewBalance == 'yes') {
            $('.main-balance').hide();
            $('.main-balance-hide').show();
        } else {
            $('.main-balance').show();
            $('.main-balance-hide').hide();
        }


        $('.bal-m-sec').removeClass('d-none');
    })

    $(document).on('click', '.hide-balance', function (e) {
        $('.main-balance').hide();
        $('.main-balance-hide').show();

        localStorage.setItem('viewBalance', 'yes');

    });

    $(document).on('click', '.show-balance', function (e) {
        $('.main-balance').show();
        $('.main-balance-hide').hide();

        localStorage.removeItem('viewBalance');
    });

    $(document).on('click', '.copyTextBtn', function (e) {

        const ref_link = '{{ route('user.register',['reference'=>auth()->user()->username]) }}';


        navigator.clipboard.writeText(ref_link);

    });

    $(document).on('click', '.copy-uid', function (e) {

        const uid = $(this).attr('data-uid');

        if(!uid){
            toastr.error('Contact With Administrator', 'Error!');
        }

        toastr.success('UID Successfully Copied', 'Success!')
        navigator.clipboard.writeText(uid);

    });

</script>

<script>
    (function ($) {
        "use strict";

        var inputElements = $('[type=text],[type=password],select,textarea');
        $.each(inputElements, function (index, element) {
            element = $(element);
            element.closest('.form-group').find('label').attr('for', element.attr('name'));
            element.attr('id', element.attr('name'))
        });

        $.each($('input, select, textarea'), function (i, element) {
            if (element.hasAttribute('required')) {
                $(element).closest('.form-group').find('label').addClass('required');
            }
        });

        $('.showFilterBtn').on('click', function () {
            $('.responsive-filter-card').slideToggle();
        });

        Array.from(document.querySelectorAll('table')).forEach(table => {
            let heading = table.querySelectorAll('thead tr th');
            Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                if (row.querySelectorAll('td').length > 1) {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                }
            });
        });

        @if (session('app'))
        $('.btn--base').each(function () {
            var isInForm = $(this).closest('form').length > 0;
            if (isInForm) {
                $(this).closest('form').on("submit", function () {
                    let html = `<span class="spinner-border spinner-border-sm" role="status"></span>`;
                    $(this).find('.btn--base').attr('disabled', true).html(html);
                });
            } else {
                $(this).on('click', function () {
                    let html = `<span class="spinner-border spinner-border-sm" role="status"></span>`;
                    $(this).attr('disabled', true).html(html);
                });
            }
        });
        @endif
    })(jQuery);
</script>

<script>
    $(document).ready(function () {

        $('.summernote').summernote();
    });

</script>
</body>
</html>
