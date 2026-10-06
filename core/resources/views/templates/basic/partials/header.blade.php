<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}">
                <img src="{{ siteLogo() }}">
            </a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu me-auto align-items-lg-center flex-wrap">
                    <li class="nav-item d-block d-lg-none">
                        @if (gs("multi_language"))
                            @php
                                $langDetails = $languages->where('code', config('app.locale'))->first();
                            @endphp
                            <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                                <div class="custom--dropdown">
                                    <div class="custom--dropdown__selected dropdown-list__item">
                                        <div class="thumb">
                                            <img
                                                src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                        </div>
                                        <span class="text">{{ __(@$langDetails->name) }}</span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item change-lang "
                                                data-code="{{ @$language->code }}">
                                                <div class="thumb">
                                                    <img
                                                        src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                                </div>
                                                <span class="text">{{ __(@$language->name) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <ul class="login-registration-list d-flex flex-wrap align-items-center">
                                    @guest
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.login') }}" class="sign-in ">@lang('Login')</a>
                                        </li>
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.register') }}"
                                                class="btn btn--base btn--sm ">@lang('Sign up') </a>
                                        </li>
                                    @else
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.home') }}"
                                                class="btn btn--base btn--sm">@lang('Dashboard')</a>
                                        </li>
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                        </li>
                                    @endguest
                                </ul>
                            </div>
                        @endif
                    </li>
                    <li class="nav-item hide-m-view-btn">
                        <a class="nav-link" href="{{ route('download.apk') }}">Download Apk</a>

                    </li>


                </ul>
            </div>
            <ul class="header-right d-lg-block d-none">
                <li class="nav-item">
                    <div class="top-button d-flex flex-wrap justify-content-between align-items-center">

                        <ul class="login-registration-list d-flex flex-wrap align-items-center">
                            @guest
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.login') }}" class="sign-in">@lang('Login')</a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.register') }}"
                                        class="btn btn--base btn--sm">@lang('Sign up') </a>

                                </li>
                            @else
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.home') }}"
                                        class="btn btn--base btn--sm">@lang('Dashboard')</a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </li>
            </ul>
            @if (!request()->routeIs('trade'))
                <div class="theme-switch-wrapper">
                    <label class="theme-switch" for="checkbox">
                        <input type="checkbox" class="d-none" id="checkbox">
                        <span class="slider">
                            <i class="las la-sun"></i>
                        </span>
                    </label>
                </div>
            @endif
        </nav>
    </div>
</header>
