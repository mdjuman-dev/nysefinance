@php
    $user=auth()->user();
@endphp
<div class="dashboard-header">
    <div class="dashboard-header__inner">
        <div class="dashboard-header__left d-none">
            <div class="copy-link">
                <input type="text" class="copyText" value="{{ route('home') }}?reference={{ auth()->user()->username }}"
                       readonly>
                <button class="copy-link__button copyTextBtn" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="@lang('Copy URL')">
                    <span class="copy-link__icon"><i class="las la-copy"></i>
                    </span>
                </button>
            </div>
        </div>
        <div class="dashboard-header__right justify-content-between d-block">
            {{--            <a href="{{ route('trade') }}"   class="btn btn--base outline btn--sm trade-btn">--}}
            {{--                <span class="icon-trade"></span> @lang('TRADE')--}}
            {{--            </a>--}}


            <div class="row mob-hdr-section">
                <div class="col-md-2 col-2">
                    <div class="mobile-bar-tab-icon">
                        <div class="dashboard-body__bar d-xl-none d-inline-block">
                            <button class="dashboard-sidebar-filter__button m-auto">
                                <i class="las la-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-8 top-for-dashboard">
                    <div class="d-flex mt-2 justify-content-center">
                        <span class="header-option active">EXCHANGE</span>
                        <span class="header-option coming_soon">WEB3</span>
                    </div>
                </div>
                <div class="col-md-2 col-1 top-for-dashboard">
                    <div class="header-icon-sec mt-2">
                        <a style="margin-right: 5px" href="{{route('user.help.center')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-headphones">
                                <path d="M3 18v-6a9 9 0 0 1 18 0v6"/>
                                <path d="M21 18a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h3"/>
                                <path d="M3 18a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H3"/>
                            </svg>

                        </a>

{{--                        <a href="">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">--}}
{{--                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>--}}
{{--                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>--}}
{{--                                <circle cx="19" cy="5" r="5" fill="red"/>--}}
{{--                                <text x="19" y="9" text-anchor="middle" font-size="8" fill="white" font-family="Arial" dy="-2">17</text>--}}
{{--                            </svg>--}}

{{--                        </a>--}}
                    </div>
                </div>
            </div>


            <div class="user-info">
                <div class="user-info__right">
                    <div class="user-info__button">
                        <div class="user-info__profile">
                            <p class="user-info__name">
                                <img class="m-user-image"
                                     src="{{ getImage(getFilePath('userProfile').'/'. auth()->user()->image,getFileSize('userProfile'),true) }}">
                                <span class="username-for-m">{{auth()->user()->username}}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <ul class="user-info-dropdown">
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.profile.setting') }}">
                            <span class="icon"><i class="far fa-user-circle"></i></span>
                            <span class="text">@lang('My Profile')</span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.change.password') }}">
                            <span class="icon"><i class="fa fa-key"></i></span>
                            <span class="text">@lang('Change Password')</span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.logout') }}">
                            <span class="icon"><i class="far fa-user-circle"></i></span>
                            <span class="text">@lang('Logout')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
