<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    {{--    @include('partials.seo')--}}

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}"/>

    @stack('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    <link rel="manifest" href="{{ route('pwa.configuration') }}">


    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}"
          rel="stylesheet">

    <style>
        .skeleton::after {
            background: initial !important;
        }

        .buy-sell-tab-desktop {
            display: block;
        }

        .buy-sell-tab-mobile {
            display: none;
            visibility: hidden;
        }

        @media (max-width: 750px) {
            .footer-area {
                display: none;
            }

            .buy-sell-tab-desktop {
                display: none !important;
            }

            .buy-sell-tab-mobile {
                display: block;
                width: 100% !important;
                visibility: visible;
            }

            .buy-sell-tab-mobile .row button {
                width: 100%;
            }

            .buy-sell-tab-mobile .row {
                width: 100%;
            }

            .p2p-header {

            }

            .custom--dropdown {
                display: none;
            }

            .hide-m-view-btn {
                display: none !important;
            }

            .navbar-brand.logo img {
                width: 100px !important;
            }

            .trading-header.selected-pair {
                display: flex;
                align-content: space-around;
                justify-content: space-between;
                align-items: flex-start;
                flex-direction: row;
            }
        }

        .mobile-footer-menu {
            display: none;
        }


        .m-menu-icon-sec i {
            font-size: 20px !important;
            color: #777777;
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

        .hide-row-p {
            visibility: hidden;
        }

        @media (max-width: 750px) {
            .scroll-top {
                display: none;
            }

            .mobile-footer-menu {
                display: block !important;
                position: fixed;
                bottom: 0px;
                width: 100%;
                z-index: 99999999999;
                background: #151818;
                padding: 10px 9px;
            }

            .p2p-table-section {
                padding-bottom: 45px;
            }
        }

        .nav-link {
            color: white !important;
        }

        .preloader-wrapper {
            background-color: hsl(0deg 0% 0%) !important;
        }

        .preloader-wrapper .preloader img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            font-family: cursive;
            padding: 13px;
            border: 1px solid #373737;
        }
    </style>


    @stack('style')

    @yield('new_css')

</head>
@php echo loadExtension('google-analytics') @endphp

<body>
<div class="progress-cricle">
    <h4 class="progress-cricle__number"></h4>
</div>
@stack('fbComment')
<div class="preloader-wrapper">
    <div class="preloader">
        <img src="{{asset('core/public/en/images/load.png')}}">
    </div>
</div>

<div class="body-overlay"></div>
<div class="sidebar-overlay"></div>
<a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

@yield('main-content')


<div class="mobile-footer-menu">
    <div class="mobile-menu-main-section">
        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{ route('user.home') }}" class="footer-nav-item {{ menuActive('user.home') }}">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 10.5L12 4L21 10.5V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V10.5Z"
                              stroke="{{ request()->routeIs('user.home') ? '#f99c26' : '#fff' }}" stroke-width="2"
                              fill="{{ request()->routeIs('user.home') ? '#f99c26' : 'none' }}"/>
                        <rect x="8" y="14" width="8" height="7" rx="1" fill="#212121"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('user.home') ? '#f99c26' : '#fff' }}">Home</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{route('user.stock.index')}}" class="footer-nav-item {{ menuActive('user.stock.*') }}">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="10" width="3" height="7" rx="1"
                              fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                        <rect x="10.5" y="7" width="3" height="10" rx="1"
                              fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                        <rect x="17" y="4" width="3" height="13" rx="1"
                              fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
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
                            stroke="{{ request()->routeIs('bond') ? '#f99c26' : '#fff' }}" stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('bonds') ? '#f99c26' : '#fff' }}">Explore</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{ route('trade') }}" class="footer-nav-item {{ menuActive('trade') }}">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="17" width="18" height="2" rx="1"
                              fill="{{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}"/>
                        <path d="M8 17V7L12 11L16 7V17" stroke="{{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}"
                              stroke-width="2"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}">Trade</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="#" class="footer-nav-item coming_soon">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}"
                                stroke-width="2"/>
                        <text x="12" y="16" text-anchor="middle"
                              fill="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}" font-size="10"
                              font-family="Arial" dy="-2">$</text>
                        <path d="M16 8L18 6" stroke="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}"
                              stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}">Future</span>
            </a>
        </div>

        <div class="d-flex justify-content-center m-menu-icon-sec">
            <a href="{{route('user.wallet.overview')}}" class="footer-nav-item {{ menuActive('user.wallet.*') }}">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="7" width="16" height="10" rx="2"
                              stroke="{{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}" stroke-width="2"/>
                        <rect x="8" y="11" width="8" height="2" rx="1"
                              fill="{{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}"/>
                    </svg>
                </span>
                <span class="icon-text-title" style="color: {{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}">Assets</span>
            </a>
        </div>

    </div>
</div>


@php
    $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
@endphp

{{--@if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))--}}
{{--    <div class="cookies-card text-center hide">--}}
{{--        <div class="cookies-card__icon bg--base">--}}
{{--            <i class="las la-cookie-bite"></i>--}}
{{--        </div>--}}
{{--        <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a href="{{ route('cookie.policy') }}" class="text--base"--}}
{{--                                                                                        target="_blank">@lang('learn more')</a></p>--}}
{{--        <div class="cookies-card__btn mt-4">--}}
{{--            <a href="javascript:void(0)" class="btn btn--base w-100 policy">@lang('Allow')</a>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}

<script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>

<script>
    jQuery('button[type="submit"]').on('click', function (e) {
        if ($(this).hasClass('sell-btn') || $(this).hasClass('buy-btn') || $(this).hasClass('chat-send-btn')) {
            return;
        }

        var form = $(this).parents('form:first');
        if (form) {
            $(this).attr('disabled', 'disabled').addClass('disabled')
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');
            form.submit();
        }
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

<script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>


{{--@php--}}
{{--    $pusherConfig = gs('pusher_config');--}}
{{--@endphp--}}


<script>
    window.my_pusher = {
        'app_key': "{{ base64_encode(@$pusherConfig->pusher_app_key) }}",
        'app_cluster': "{{ base64_encode(@$pusherConfig->pusher_app_cluster) }}",
        'base_url': "{{ route('home') }}"
    }
    window.allow_decimal = "{{ gs('allow_decimal_after_number') }}";
</script>

@stack('script-lib')

@php echo loadExtension('tawk-chat') @endphp

@include('partials.notify')

@if (gs('pn'))
    @include('partials.push_script')
@endif

@stack('script')


<script>
    (function ($) {
        "use strict";

        $('.change-lang').on('click', function (e) {
            let langCode = $(this).data('code');
            window.location.href = "{{ route('home') }}/change/" + langCode;
        });

        @if (!request()->routeIs('trade'))

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            let windowsIsDarkTheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (windowsIsDarkTheme) {
                setTheme('light');
            } else {
                setTheme('dark');
            }
        });

        const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            setTheme(currentTheme);
        } else {
            let defaultTheme = `{{ gs('default_theme') }}`;

            if (defaultTheme == 'dark') {
                setTheme('dark');
            } else {
                setTheme('light');
            }
        }

        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            if (toggleSwitch) {
                theme == 'dark' ? toggleSwitch.checked = true : toggleSwitch.checked = false;
            }
        }

        if (toggleSwitch) {
            toggleSwitch.addEventListener('change', function (e) {
                setTheme(e.target.checked ? 'dark' : 'light');
            });
        }
        @endif

        var inputElements = $('input,select');
        $.each(inputElements, function (index, element) {
            element = $(element);
            element.closest('.form-group').find('label').attr('for', element.attr('name'));
            element.attr('id', element.attr('name'))
        });

        $('.policy').on('click', function () {
            $.get('{{ route('cookie.accept') }}', function (response) {
                $('.cookies-card').addClass('d-none');
            });
        });

        setTimeout(function () {
            $('.cookies-card').removeClass('hide')
        }, 2000);

        var inputElements = $('[type=text],select,textarea');
        $.each(inputElements, function (index, element) {
            element = $(element);
            element.closest('.form-group').find('label').attr('for', element.attr('name'));
            element.attr('id', element.attr('name'))
        });

        $.each($('input, select, textarea'), function (i, element) {
            var elementType = $(element);
            if (elementType.attr('type') != 'checkbox') {
                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }
            }
        });

        let disableSubmission = false;
        $('.disableSubmission').on('submit', function (e) {
            if (disableSubmission) {
                e.preventDefault()
            } else {
                disableSubmission = true;
            }
        });

    })(jQuery);


    async function registerSW() {
        if ('serviceWorker' in navigator) {
            try {
                await navigator.serviceWorker.register("{{ asset($activeTemplateTrue . 'js/pwa/serviceworker.js') }}");
            } catch (e) {
                console.warn('SW registration failed');
            }
        }
    }

    window.addEventListener('load', () => {
        registerSW();
    });
</script>


</body>

</html>
