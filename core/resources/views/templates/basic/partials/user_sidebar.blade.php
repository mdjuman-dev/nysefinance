<div class="sidebar-menu">
    <div class="sidebar-menu__inner desktop-user-sidebar">
        <span class="sidebar-menu__close d-xl-none d-block"><i class="fas fa-times custom-css"></i></span>
        <div class="sidebar-logo">
            <div class="copy-link side-menu-refer-link">
                <input type="text" class="copyText" value="{{ route('home') }}?reference={{ auth()->user()->username }}" readonly>
                <button class="copy-link__button text--white copyTextBtn" data-bs-toggle="tooltip"  data-bs-placement="right" title="@lang('Copy URL')">
                    <span class="copy-link__icon"><i class="las la-copy custom-css"></i>
                    </span>
                </button>
            </div>

            <a href="{{ route('user.home') }}" class="sidebar-logo__link">
                <img src="{{siteLogo()}}">
            </a>
        </div>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.home') }}" class="sidebar-menu-list__link {{ menuActive('user.home') }}">
                    <span class="icon"><i class="fas fa-tachometer-alt custom-css"></i></span>
                    <span class="text">@lang('Dashboard')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('user.order.open')}}" class="sidebar-menu-list__link {{ menuActive('user.order.*') }} ">
                    <span class="icon"><i class="fas fa-shopping-cart custom-css"></i></span>
                    <span class="text">@lang('Manage Order')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('user.stock.index')}}" class="sidebar-menu-list__link {{ menuActive('user.stock.index') }} ">
                    <span class="icon"><i class="fas fa-chart-line custom-css"></i></span>
                    <span class="text">@lang('Stock')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('user.stock.my')}}" class="sidebar-menu-list__link {{ menuActive('user.stock.my') }} ">
                    <span class="icon"><i class="fas fa-chart-line custom-css"></i></span>
                    <span class="text">@lang('My Stock')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('user.stock.transactions')}}" class="sidebar-menu-list__link {{ menuActive('user.stock.transactions') }} ">
                    <span class="icon"><i class="fas fa-history custom-css"></i></span>
                    <span class="text">@lang('Stock History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('trade')}}" class="sidebar-menu-list__link {{ menuActive('user.trade') }} ">
                    <span class="icon"><i class="fas fa-exchange-alt custom-css"></i></span>
                    <span class="text">@lang('Trade')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('futures')}}" class="sidebar-menu-list__link {{ menuActive('futures') }} ">
                    <span class="icon"><i class="fas fa-chart-line custom-css"></i></span>
                    <span class="text">@lang('Futures')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('user.trade.history')}}" class="sidebar-menu-list__link {{ menuActive('user.trade.history') }} ">
                    <span class="icon"><i class="fas fa-history custom-css"></i></span>
                    <span class="text">@lang('Trade History')</span>
                </a>
            </li>

            @if(checkAgent())
            <li class="sidebar-menu-list__item ">
                <a href="{{route('user.p2p.dashboard')}}" class="sidebar-menu-list__link {{ menuActive('user.p2p.dashboard') }} ">
                    <span class="icon"><i class="fas fa-exchange-alt custom-css"></i></span>
                    <span class="text">@lang('P2P Center')</span>
                </a>
            </li>

            @else
            <li class="sidebar-menu-list__item ">
                <a href="{{route('p2p')}}" class="sidebar-menu-list__link {{ menuActive('p2p') }} ">
                    <span class="icon"><i class="fas fa-exchange-alt custom-css"></i></span>
                    <span class="text">@lang('P2P')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{route('user.p2p.dashboard')}}" class="sidebar-menu-list__link {{ menuActive('user.p2p.dashboard') }} ">
                    <span class="icon"><i class="fas fa-exchange-alt custom-css"></i></span>
                    <span class="text">@lang('P2P Pending Order')</span>
                </a>
            </li>
            @endif

            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.wallet.overview') }}" class="sidebar-menu-list__link {{ menuActive('user.wallet.*') }}">
                    <span class="icon"><i class="fas fa-wallet custom-css"></i></span>
                    <span class="text">@lang('Manage Wallet')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.deposit.history') }}" class="sidebar-menu-list__link {{ menuActive('user.deposit.*') }}">
                    <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                    <span class="text">@lang('Deposit History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.deposit.requests') }}" class="sidebar-menu-list__link {{ menuActive('user.deposit.requests') }}">
                    <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                    <span class="text">@lang('Deposit Requests')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.withdraw.history') }}" class="sidebar-menu-list__link {{ menuActive('user.withdraw.history') }}">
                    <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                    <span class="text">@lang('Withdraw History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.referrals') }}" class="sidebar-menu-list__link {{ menuActive('user.referrals') }}">
                    <span class="icon"><i class="fas fa-users custom-css"></i></span>
                    <span class="text">@lang('My Affiliation')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.salary') }}" class="sidebar-menu-list__link {{ menuActive('user.salary') }}">
                    <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                    <span class="text">@lang('Salary')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.transactions') }}" class="sidebar-menu-list__link {{ menuActive('user.transactions') }}">
                    <span class="icon"><i class="fas fa-history custom-css"></i></span>
                    <span class="text">@lang('Transaction Histoy')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.blog.index') }}" class="sidebar-menu-list__link {{ menuActive('user.blog.*') }}">
                    <span class="icon"><i class="fas fa-blog custom-css"></i></span>
                    <span class="text">@lang('My Blog')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.blog.all') }}" class="sidebar-menu-list__link {{ menuActive('user.blog.all') }}">
                    <span class="icon"><i class="fas fa-newspaper custom-css"></i></span>
                    <span class="text">@lang('All Blogs')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('ticket.index') }}" class="sidebar-menu-list__link {{ menuActive('ticket.*') }}">
                    <span class="icon"><i class="fas fa-headset custom-css"></i></span>
                    <span class="text">@lang('Get Support')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.twofactor') }}" class="sidebar-menu-list__link {{ menuActive('user.twofactor') }}">
                    <span class="icon"><i class="fas fa-shield-alt custom-css"></i></span>
                    <span class="text">@lang('Security')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.logout') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-sign-out-alt custom-css"></i></span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-menu__inner mobile-user-sidebar">
        <span class="sidebar-menu__close d-xl-none d-block"><i class="fas fa-times custom-css"></i></span>
        <div class="sidebar-logo d-none">
            <div class="copy-link side-menu-refer-link">
                <input type="text" class="copyText" value="{{ route('home') }}?reference={{ auth()->user()->username }}" readonly>
                <button class="copy-link__button text--white copyTextBtn" data-bs-toggle="tooltip"  data-bs-placement="right" title="@lang('Copy URL')">
                    <span class="copy-link__icon"><i class="las la-copy custom-css"></i>
                    </span>
                </button>
            </div>

        </div>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item account-section">
                <div class="row">
                    <div class="col-3 d-flex justify-content-center">
                        @if(auth()->user()->image)
                        <img class="m-user-image" src="{{ getImage(getFilePath('userProfile').'/'. auth()->user()->image,getFileSize('userProfile'),true) }}">
                        @else
                        <img class="m-user-image" src="{{asset('core/public/default_profile.png')}}">
                        @endif
                    </div>
                    <div class="col-9">
                        <h4 class="mb-1">
                            {{auth()->user()->fullname}}

                            <button class="copy-link__button text--white copyTextBtn float-right" data-bs-toggle="tooltip"  data-bs-placement="right" title="@lang('Copy URL')">
                                <span class="copy-link__icon">
                                    <i class="las la-copy custom-css"></i>
                                </span>
                            </button>
                        </h4>

                        <div class="d-block">
                            <span class="copy-uid" data-uid="{{auth()->user()->uid}}">
                                   UID: {{auth()->user()->uid}} <i class="fa fa-copy click-copy-uid" ></i>
                            </span>
                        </div>



                        <p>
                            @if(auth()->user()->kv=='0')
                                <span class="badge-pending">Unverified <i class="fa fa-info-circle ml-1"></i></span>
                            @elseif(auth()->user()->kv=='2')
                                <span class="badge-pending">Pending <i class="fa fa-info-circle ml-1"></i></span>
                            @elseif(auth()->user()->kv=='1')
                                <span class="badge-verified">Verified <i class="fa fa-certificate ml-1"></i></span>
                            @endif

                                @if(auth()->user()->group_expert && auth()->user()->group_expert=='yes')
                                    <span class="group-expert"
                                              style="font-size: 11px;margin-left: 12px;margin-right: 9px; background: rgba(245,226,13,0.9);padding: 3px 5px;border-radius: 5px;">
                                        Group Expert
                                    </span>
                                @endif

                        </p>

                    </div>
                </div>
            </li>


                <li class="sidebar-menu-list__item separate-dashboard">
                    <a href="{{ route('user.home') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.home') }}">
                        <span class="icon"><i class="fas fa-tachometer-alt custom-css"></i></span>
                        <span class="text">@lang('Dashboard')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>



                <li class="sidebar-menu-list__item separate-dashboard">
                    <a href="{{ route('user.apply.card') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.apply.card') }}">
                        <span class="icon"><i class="fas fa-credit-card custom-css"></i></span>
                        <span class="text">@lang('Apply For Card')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>


{{--                <li class="sidebar-menu-list__item separate-dashboard">--}}
{{--                    <a href="{{ route('binary') }}"--}}
{{--                       class="sidebar-menu-list__link {{ menuActive('binary') }}">--}}
{{--                        <span class="icon"><i class="fas fa-tachometer-alt custom-css"></i></span>--}}
{{--                        <span class="text">@lang('Binary Trade')</span>--}}
{{--                        <i class="fa fa-angle-right float-right"></i>--}}
{{--                    </a>--}}
{{--                </li>--}}



            <!-- Coupons -->
            <div class="separate-section-sidebar">
                <h6>JackPlay</h6>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.coupons') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.coupons') }}">
                        <span class="icon"><i class="fas fa-shopping-cart custom-css"></i></span>
                        <span class="text">@lang('JackPlay')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

            </div>


            <!--Account Details -->
            <div class="separate-section-sidebar">
                <h6>Account Details</h6>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.profile.setting') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.profile.setting') }}">
                        <span class="icon"><i class="fas fa-user custom-css pf-icon"></i></span>
                        <span class="text">@lang('Profile')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.change.password') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.change.password') }}">
                        <span class="icon"><i class="fas fa-key custom-css"></i></span>
                        <span class="text">@lang('Change Password')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.twofactor') }}"
                       class="sidebar-menu-list__link flex-nowrap {{ menuActive('user.twofactor') }}">
                        <span class="icon"><i class="fas fa-shield-alt custom-css"></i></span>
                        <span class="text d-block">@lang('Security')</span>
                        <i class="fa fa-angle-right float-right"></i>

                    </a>
                </li>

                @if(auth()->user()->kv=='0')
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.kyc.form') }}"
                       class="sidebar-menu-list__link flex-nowrap {{ menuActive('user.kyc.form') }}">
                        <span class="icon"><i class="fas fa-id-card custom-css"></i></span>
                        <span class="text d-block">@lang('KYC Form')</span>
                        <i class="fa fa-angle-right float-right"></i>

                    </a>
                </li>
                @endif


            </div>


            <!-- Copy-Trade -->
            <div class="separate-section-sidebar">
                <h6>Copy Trade</h6>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.classic.trading') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.classic.trading') }}">
                        <span class="icon"><i class="fas fa-puzzle-piece custom-css"></i></span>
                        <span class="text">@lang('Classic Trade')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.gold.fx.trading') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.gold.fx.trading') }}">
                        <span class="icon"><i class="fas fa-gem custom-css"></i></span>
                        <span class="text">@lang('Gold-FX')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.otc.trading') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.otc.trading') }}">
                        <span class="icon"><i class="fas fa-coins custom-css"></i></span>
                        <span class="text">@lang('OTC Trade')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.puzzle.hunt.trading') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.puzzle.hunt.trading') }}">
                        <span class="icon"><i class="fas fa-puzzle-piece custom-css"></i></span>
                        <span class="text">@lang('Puzzle Hunt')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.token.splash.trading') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.token.splash.trading') }}">
                        <span class="icon"><i class="fas fa-coins custom-css"></i></span>
                        <span class="text">@lang('Token Splash')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.by.votes.trading') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.by.votes.trading') }}">
                        <span class="icon"><i class="fas fa-vote-yea custom-css"></i></span>
                        <span class="text">@lang('By Votes')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.mt5') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.mt5') }}">
                        <span class="icon"><i class="fas fa-chart-line custom-css"></i></span>
                        <span class="text">@lang('MT-5')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.spot.x') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.spot.x') }}">
                        <span class="icon"><i class="fas fa-chart-line custom-css"></i></span>
                        <span class="text">@lang('Spot X')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>



            </div>



            <!-- Rewardhub -->
            <div class="separate-section-sidebar">
                <h6>Reward-Hub</h6>


                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.reward.hub') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.reward.hub') }}">
                        <span class="icon"><i class="fas fa-gift custom-css"></i></span>
                        <span class="text">@lang('Reward Hub')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.treasure.hunt') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.treasure.hunt') }}">
                        <span class="icon"><i class="fas fa-treasure-chest custom-css"></i></span>
                        <span class="text">@lang('Treasure Hunt')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.leader.board') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.leader.board') }}">
                        <span class="icon"><i class="fas fa-trophy custom-css"></i></span>
                        <span class="text">@lang('Leader Board')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.launch.pool') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.launch.pool') }}">
                        <span class="icon"><i class="fas fa-rocket custom-css"></i></span>
                        <span class="text">@lang('LaunchPool')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.launchpad') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.launchpad') }}">
                        <span class="icon"><i class="fas fa-rocket custom-css"></i></span>
                        <span class="text">@lang('LaunchPad')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.reward.history') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.reward.history') }}">
                        <span class="icon"><i class="fas fa-rocket custom-css"></i></span>
                        <span class="text">@lang('Reward History')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
            </div>




            <!-- Orders -->
            <div class="separate-section-sidebar">
                <h6>Orders</h6>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.order.open') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.order.open') }}">
                        <span class="icon"><i class="fas fa-shopping-cart custom-css"></i></span>
                        <span class="text">@lang('Trade Order')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.stock.my') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.stock.my') }}">
                        <span class="icon"><i class="fas fa-shopping-cart custom-css"></i></span>
                        <span class="text">@lang('Market Order')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
            </div>

            <!-- History -->
            <div class="separate-section-sidebar">
                <h6>Histories</h6>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.stock.transactions') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.stock.transactions') }}">
                        <span class="icon"><i class="fas fa-history custom-css"></i></span>
                        <span class="text">@lang('Market History')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.bond.transactions') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.bond.transactions') }}">
                        <span class="icon"><i class="fas fa-history custom-css"></i></span>
                        <span class="text">@lang('Bond History')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.stock.daily.interest') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.stock.daily.interest') }}">
                        <span class="icon"><i class="fas fa-percentage custom-css"></i></span>
                        <span class="text">@lang('Daily Interest')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.trade.history') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.trade.history') }}">
                        <span class="icon"><i class="fas fa-history custom-css"></i></span>
                        <span class="text">@lang('Trade History')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.deposit.history') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.deposit.history') }}">
                        <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                        <span class="text">@lang('Deposit History')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.deposit.requests') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.deposit.requests') }}">
                        <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                        <span class="text">@lang('Deposit Requests')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.withdraw.history') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.withdraw.history') }}">
                        <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                        <span class="text">@lang('Withdraw History')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.transactions') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.transactions') }}">
                        <span class="icon"><i class="fas fa-history custom-css"></i></span>
                        <span class="text">@lang('Transactions')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.blog.index') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.blog.*') }}">
                        <span class="icon"><i class="fas fa-blog custom-css"></i></span>
                        <span class="text">@lang('Posts')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__itemd-none  d-none">
                    <a href="{{ route('user.blog.all') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.blog.all') }}">
                        <span class="icon"><i class="fas fa-newspaper custom-css"></i></span>
                        <span class="text">@lang('All Blogs')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.copy.trade.history') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.copy.trade.history') }}">
                        <span class="icon"><i class="fas fa-history custom-css"></i></span>
                        <span class="text">@lang('Copy Trade History')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
            </div>

            <!-- Wallets -->
            <div class="separate-section-sidebar">
                <h6>Wallets</h6>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.wallet.overview') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.wallet.overview') }}">
                        <span class="icon"><i class="fas fa-wallet custom-css"></i></span>
                        <span class="text">@lang('Manage Wallet')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                @if(checkAgent())
                    <li class="sidebar-menu-list__item ">
                        <a href="{{ route('user.p2p.dashboard') }}"
                           class="sidebar-menu-list__link {{ menuActive('user.p2p.dashboard') }}">
                            <span class="icon"><i class="fas fa-exchange-alt custom-css"></i></span>
                            <span class="text">@lang('P2P')</span>
                            <i class="fa fa-angle-right float-right"></i>
                        </a>
                    </li>
                @else
                    <li class="sidebar-menu-list__item ">
                        <a href="{{ route('user.p2p.dashboard') }}"
                           class="sidebar-menu-list__link {{ menuActive('user.p2p.dashboard') }}">
                            <span class="icon"><i class="fas fa-exchange-alt custom-css"></i></span>
                            <span class="text">@lang('P2P Pending Order')</span>
                            <i class="fa fa-angle-right float-right"></i>
                        </a>
                    </li>


                    <li class="sidebar-menu-list__item ">
                        <a href="{{ route('p2p') }}"
                           class="sidebar-menu-list__link {{ menuActive('user.p2p.dashboard') }}">
                            <span class="icon"><i class="fas fa-exchange-alt custom-css"></i></span>
                            <span class="text">@lang('P2P')</span>
                            <i class="fa fa-angle-right float-right"></i>
                        </a>
                    </li>
                @endif


            </div>



            <!-- Affiliate -->
            <div class="separate-section-sidebar">
                <h6>Affiliate</h6>


                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.affiliate') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.affiliate') }}">
                        <span class="icon"><i class="fas fa-handshake custom-css"></i></span>
                        <span class="text">@lang('Affiliate')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>


                <li class="sidebar-menu-list__item">
                    <a href="{{ route('user.invite.friend') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.invite.friend') }}">
                        <span class="icon"><i class="fas fa-users custom-css"></i></span>
                        <span class="text">@lang('Invite Friend')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>


                <li class="sidebar-menu-list__item">
                    <a href="{{ route('user.referrals') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.referrals') }}">
                        <span class="icon"><i class="fas fa-users custom-css"></i></span>
                        <span class="text">@lang('My Referral')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>


                <li class="sidebar-menu-list__item">
                    <a href="{{ route('user.salary') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.salary') }}">
                        <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                        <span class="text">@lang('Salary')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>


                <li class="sidebar-menu-list__item">
                    <a href="{{ route('user.photo.gallery') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.photo.gallery') }}">
                        <span class="icon"><i class="fas fa-money-bill-wave custom-css"></i></span>
                        <span class="text">@lang('Photo Gallery')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

            </div>




            <!-- Support -->
            <div class="separate-section-sidebar">
                <h6>Support</h6>
                <li class="sidebar-menu-list__item d-none">
                    <a href="{{ route('ticket.index') }}"
                       class="sidebar-menu-list__link {{ menuActive('ticket.index') }}">
                        <span class="icon"><i class="fas fa-headset custom-css"></i></span>
                        <span class="text">@lang('Support')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.help.center') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.help.center') }}">
                        <span class="icon"><i class="fas fa-question-circle custom-css"></i></span>
                        <span class="text">@lang('Help Center')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>

                <li class="sidebar-menu-list__item ">
                    <a href="{{ route('user.trade.gpt') }}"
                       class="sidebar-menu-list__link {{ menuActive('user.trade.gpt') }}">
                        <span class="icon"><i class="fas fa-robot custom-css"></i></span>
                        <span class="text">@lang('Trade ZPT')</span>
                        <i class="fa fa-angle-right float-right"></i>
                    </a>
                </li>
            </div>



            <!-- Affiliate -->
            <li class="sidebar-menu-list__item separate-dashboard">
                <a href="{{ route('user.logout') }}"
                   class="sidebar-menu-list__link {{ menuActive('user.logout') }}">
                    <span class="icon"><i class="fas fa-sign-out-alt custom-css"></i></span>
                    <span class="text">@lang('Logout')</span>
                    <i class="fa fa-angle-right float-right"></i>
                </a>
            </li>



            <!-- Official Website -->
            <div class="separate-section-sidebar">
                <h6>Official Website</h6>
{{--                <li class="sidebar-menu-list__item ">--}}
{{--                    <a href=""--}}
{{--                       class="sidebar-menu-list__link ">--}}
{{--                        <span class="icon"><i class="fas fa-globe custom-css"></i></span>--}}
{{--                        <span class="text">@lang('NyseFinance')</span>--}}
{{--                        <i class="fa fa-angle-right float-right"></i>--}}
{{--                    </a>--}}
{{--                </li>--}}

            </div>


        </ul>
    </div>

</div>



