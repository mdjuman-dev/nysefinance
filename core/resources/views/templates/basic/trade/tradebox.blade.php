@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="trading-section bg-color py-60">
        <div class="container custom--container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row trade-tab-row">
                        <div class="col-md-6 col-6 trade-tab active">
                            <a href="{{route('tradeBox')}}" class="">Trade</a>
                        </div>

                        <div class="col-md-6 col-6 trade-tab ">
                            <a href="{{route('trade')}}" class="">Charts</a>
                        </div>

                    </div>
                </div>


                <div class="col-xl-9">
                    <div class="row gy-2">
                        <div class="col-xl-4 pe-lg-1">
                            <x-flexible-view :view="$activeTemplate . 'trade.order_book'"
                                             :meta="['pair' => $pair, 'screen' => 'big']"/>
                        </div>
                        <div class="col-xl-8 col-md-7">
                            <x-flexible-view :view="$activeTemplate . 'trade.pair'" :meta="['pair' => $pair]"/>

{{--                            <x-flexible-view :view="$activeTemplate . 'trade.tab'"--}}
{{--                                             :meta="['screen' => 'small', 'markets' => $markets, 'pair' => $pair]"/>--}}

                            <div class="row" style="    padding: 0px 3px !important;">

                                <div class="com-md-6 col-6 p-0">
                                    @php
                                    $screen='small';
                                        @endphp
                                    <div class="section-coin-name">
                                        <select name="" class="form--control findCoin">
                                            @foreach($pairs as $pr)
                                                <option data-url="{{route('tradeBox',[$pr->symbol])}}" {{$pr->symbol==$pair->symbol?'selected':''}}
                                                value="{{ str_replace('_', '/', $pr->symbol) }}">

                                                    {{ str_replace('_', '/', $pr->symbol) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="section-buttons mt-3">

                                            <button class="btn--sm btnBuy"> @lang('BUY') {{ __(@$pair->coin->symbol) }} </button>

                                            <button class="btn--sm btnSell"> @lang('SELL') {{ __(@$pair->coin->symbol) }} </button>

                                    </div>


                                    <!-- Buy Form  -->
                                    <form class="trade_buy_form buy-sell d-none buy--form" method="POST">
                                        @csrf

                                        <input type="hidden" name="order_side" value="{{ Status::BUY_SIDE_ORDER }}">
                                        <input type="hidden" name="order_type" value="{{ Status::ORDER_TYPE_LIMIT }}">
                                        <div class="flex-between buy-sell__wrapper p-0">
                                            <h6 class="buy-sell__title">@lang('Available')</h6>
                                            <span class="fs-12">
                                                                    <span class="avl-market-cur-wallet">{{ showAmount(@$marketCurrencyWallet->balance,currencyFormat:false) }}</span>
                                                                     {{ @$pair->market->currency->symbol }}
                                                                     <span class="cursor-pointer new--deposit" data-currency="{{  @$pair->market->currency->symbol }}">
                                                                        <i class="las la-plus-circle"></i>
                                                                    </span>
                                                                </span>
                                        </div>

                                        <div class="mt-3">
                                            <select disabled class="form--control choose-m-type">
                                                <option value="limit">Limit</option>
                                                <option value="market">Market</option>
                                            </select>
                                        </div>

                                        <div class="buy-sell__price mt-3">
                                            <div class="input--group group-two">
                                                <span class="buy-sell__price-title fs-12">@lang('Price') </span>
                                                <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }} </span>
                                                <input type="number" step="any" class="form--control style-three buy-rate" name="rate" value="{{ getAmount($pair->marketData->price) }}">
                                            </div>
                                        </div>
                                        <div class="price-section">
                                            =
                                            <span class="current-coin-price">
                                                {{ getAmount($pair->marketData->price) }}
                                            </span>
                                            {{ @$pair->market->currency->symbol }}
                                        </div>

                                        <div class="buy-sell__price mt-3">
                                            <div class="input--group group-two">
                                                <span class="buy-sell__price-title fs-12">@lang('Amount') </span>
                                                <span class="buy-sell__price-btc fs-12"> {{ @$pair->coin->symbol }} </span>
                                                <input type="number" step="any" class="form--control style-three buy-amount"
                                                       placeholder="{{ @$pair->buyPlaceHolder }}" name="amount">
                                            </div>
                                        </div>
                                        <div class="custom--range mt-2">
                                            <div class="buy-amount-slider custom--range__range slider-range"></div>
                                            <ul class="range-list buy-amount-range">
                                                <li class="range-list__number" data-percent="0">0%<span></span></li>
                                                <li class="range-list__number" data-percent="25">@lang('25')%<span></span></li>
                                                <li class="range-list__number" data-percent="50">@lang('50')%<span></span></li>
                                                <li class="range-list__number" data-percent="75">@lang('75')%<span></span></li>
                                                <li class="range-list__number" data-percent="100">@lang('100')%<span></span></li>
                                            </ul>
                                        </div>
                                        <div class="buy-sell__price pt-0 mb-2">
                                            <div class="input--group group-two">
                                                <span class="buy-sell__price-title fs-12">@lang('Total') </span>
                                                <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }} </span>
                                                <input type="number" step="any" class="form--control style-three total-buy-amount"
                                                       placeholder="@lang('0.00')">
                                                <span class="fs-10 float-end mt-1 mb-2">
                                                                        @lang('Fee') {{ getAmount($pair->percent_charge_for_buy) }}%
                                                                        <span class="buy-charge d-none"></span>
                                                                    </span>
                                            </div>
                                        </div>


                                        <div class="form-group tp-sl-section">

                                            <div class="ech-sec">
                                                <input id="tpsl" type="checkbox" disabled>
                                                <label for="tpsl">TP/SL</label>
                                            </div>

                                            <div class="ech-sec">
                                                <input id="postOnly" type="checkbox" disabled>
                                                <label for="postOnly">Post-Only</label>
                                            </div>

                                        </div>

                                        <div class="trading-bottom__button">
                                            @auth
                                                <button class="btn btn--base-two w-100 btn--sm  buy-btn " type="submit">
                                                    @lang('BUY') {{ __(@$pair->coin->symbol) }}
                                                </button>
                                            @else
                                                <div class="btn login-btn w-100 btn--sm">
                                                    <a href="{{ route('user.login') }}">@lang('Login')</a>
                                                    <span>@lang('or')</span>
                                                    <a href="{{ route('user.register') }}">@lang('Register')</a>
                                                </div>
                                            @endauth
                                        </div>
                                    </form>


                                    <!-- Sell Form -->
                                    <form action="{{ route('user.order.save', @$pair->symbol) }}" class="trade_sell_form d-none"  method="POST">
                                        @csrf


                                        <input type="hidden" name="order_side" value="{{ Status::SELL_SIDE_ORDER }}">
                                        <input type="hidden" name="order_type" value="{{ Status::ORDER_TYPE_LIMIT }}">
                                        <div class="flex-between buy-sell__wrapper p-0">
                                            <h6 class="buy-sell__title"> @lang('Available')</h6>
                                            <span class="fs-12">
                                                                    <span class="avl-coin-wallet">
                                                                        {{ showAmount(@$coinWallet->balance,currencyFormat:false) }}
                                                                    </span>
                                                                    {{ @$pair->coin->symbol }}
                                                                    <span class="cursor-pointer new--deposit" data-currency="{{  @$pair->coin->symbol }}">
                                                                        <i class="las la-plus-circle"></i>
                                                                    </span>
                                                                </span>
                                        </div>

                                        <div class="mt-3">
                                            <select disabled class="form--control choose-m-type">
                                                <option value="limit">Limit</option>
                                                <option value="market">Market</option>
                                            </select>
                                        </div>



                                        <div class="buy-sell__price mt-3">
                                            <div class="input--group group-two">
                                                <span class="buy-sell__price-title fs-12"> @lang('Price') </span>
                                                <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }} </span>
                                                <input type="number" step="any" class="form--control style-three sell-rate"
                                                       name="rate" value="{{ getAmount(@$pair->marketData->price) }}">
                                            </div>
                                        </div>

                                        <div class="price-section">
                                            =
                                            <span class="current-coin-price">
                                                {{ getAmount($pair->marketData->price) }}
                                            </span>
                                            {{ @$pair->market->currency->symbol }}
                                        </div>


                                        <div class="buy-sell__price mt-3">
                                            <div class="input--group group-two">
                                                <span class="buy-sell__price-title fs-12"> @lang('Amount') </span>
                                                <span class="buy-sell__price-btc fs-12"> {{ @$pair->coin->symbol }} </span>
                                                <input type="text" class="form--control style-three sell-amount" name="amount"
                                                       placeholder="{{ $pair->sellPlaceHolder }}">
                                            </div>
                                        </div>
                                        <div class="custom--range">
                                            <div class="custom--range__range slider-range sell-amount-slider"></div>
                                            <ul class="range-list sell-amount-range">
                                                <li class="range-list__number" data-percent="0">0%<span></span></li>
                                                <li class="range-list__number" data-percent="25">@lang('25')%<span></span></li>
                                                <li class="range-list__number" data-percent="50">@lang('50')%<span></span></li>
                                                <li class="range-list__number" data-percent="75">@lang('75')%<span></span></li>
                                                <li class="range-list__number" data-percent="100">@lang('100')%<span></span>
                                                </li>
                                            </ul>
                                        </div>


                                        <div class="buy-sell__price pt-0 mb-2">
                                            <div class="input--group group-two">
                                                                    <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }}
                                                                    </span>
                                                <input type="number" step="any"
                                                       class="form--control style-three total-sell-amount" placeholder="0.00">
                                                <span class="fs-10 float-end mt-1 mb-2">
                                                                        @lang('Fee') {{ getAmount($pair->percent_charge_for_sell) }}%
                                                                        <span class="sell-charge d-none"></span>
                                                                    </span>
                                            </div>
                                        </div>

                                        <div class="form-group tp-sl-section">

                                            <div class="ech-sec">
                                                <input id="tpsl" type="checkbox" disabled>
                                                <label for="tpsl">TP/SL</label>
                                            </div>

                                            <div class="ech-sec">
                                                <input id="postOnly" type="checkbox" disabled>
                                                <label for="postOnly">Post-Only</label>
                                            </div>

                                        </div>


                                        <div class="trading-bottom__button">
                                            @auth
                                                <button type="submit" data-close-tag="yes" class="btn btn--danger w-100 btn--sm sell-btn">
                                                    @lang('SELL') {{ __(@$pair->coin->symbol) }}
                                                </button>
                                            @else
                                                <div class="btn  login-btn w-100 btn--sm">
                                                    <a href="{{ route('user.login') }}">@lang('Login')</a>
                                                    <span>@lang('or')</span>
                                                    <a href="{{ route('user.register') }}">@lang('Register')</a>
                                                </div>
                                            @endauth
                                        </div>
                                    </form>


                                </div>



                                <div class="col-md-6 col-6 p-0">


                                    <div class="w-full p-2">
                                        <div class="flex justify-between items-center mb-2 px-2 orderBook-header">
                                            <div class="text-sm text-gray-400">Price (USDT)</div>
                                            <div class="text-sm text-gray-400">Qty (GALAXIS)</div>
                                        </div>
                                        <div class="order-book-sell">
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007878</div>
                                                <div>567.6K</div>
                                                <div class="order-book-bg-bar" style="width: 100%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007877</div>
                                                <div>30.30K</div>
                                                <div class="order-book-bg-bar" style="width: 5%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007875</div>
                                                <div>7.406K</div>
                                                <div class="order-book-bg-bar" style="width: 1.5%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007870</div>
                                                <div>87.88K</div>
                                                <div class="order-book-bg-bar" style="width: 15%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007869</div>
                                                <div>103.5K</div>
                                                <div class="order-book-bg-bar" style="width: 18%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007855</div>
                                                <div>1.515K</div>
                                                <div class="order-book-bg-bar" style="width: 0.3%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007851</div>
                                                <div>4.266K</div>
                                                <div class="order-book-bg-bar" style="width: 0.8%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007824</div>
                                                <div>2.244K</div>
                                                <div class="order-book-bg-bar" style="width: 0.4%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-red-500">0.0007816</div>
                                                <div>24.89K</div>
                                                <div class="order-book-bg-bar" style="width: 4%;"></div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center p-1 px-2 my-1 order-book-crr-price">
                                            <div id="currentPrice" class="text-primary font-medium">0.0007816</div>
                                            <div id="usdPrice" class="text-xs text-gray-400">≈0.00078 USD</div>
                                        </div>
                                        <div class="order-book-buy">
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007741</div>
                                                <div>74.50K</div>
                                                <div class="order-book-bg-bar" style="width: 13%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007740</div>
                                                <div>35.89K</div>
                                                <div class="order-book-bg-bar" style="width: 6%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007738</div>
                                                <div>155.0K</div>
                                                <div class="order-book-bg-bar" style="width: 27%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007736</div>
                                                <div>155.1K</div>
                                                <div class="order-book-bg-bar" style="width: 27%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007730</div>
                                                <div>123.8K</div>
                                                <div class="order-book-bg-bar" style="width: 22%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007706</div>
                                                <div>27.37K</div>
                                                <div class="order-book-bg-bar" style="width: 5%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007705</div>
                                                <div>30.53K</div>
                                                <div class="order-book-bg-bar" style="width: 5.5%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007704</div>
                                                <div>19.47K</div>
                                                <div class="order-book-bg-bar" style="width: 3.5%;"></div>
                                            </div>
                                            <div class="order-book-row flex justify-between items-center p-1 px-2">
                                                <div class="text-primary">0.0007700</div>
                                                <div>16.36K</div>
                                                <div class="order-book-bg-bar" style="width: 3%;"></div>
                                            </div>
                                        </div>
                                        <div class="flex h-6 mt-2 process-bar">
                                            <div class="bg-primary sec first opacity-80 text-xs flex items-center justify-center" style="width: 21%">
{{--                                                <span class="text-white font-medium">B</span>--}}
                                                <span class="text-white ml-1">21%</span>
                                            </div>
                                            <div class="bg-secondary sec second opacity-80 text-xs flex items-center justify-center" style="width: 79%">
                                                <span class="text-white ml-1">79%</span>
{{--                                                <span class="text-white font-medium ml-1">S</span>--}}
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center mt-2 d-none">
                                            <div class="flex items-center p-1 px-2 bg-gray-800 rounded text-sm">
                                                <span>0.00001</span>
                                                <i class="ri-arrow-down-s-line ml-1"></i>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 flex items-center justify-center">
                                                    <i class="ri-layout-grid-line text-gray-400"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                </div>
                            </div>


                            <div class="d-none d-md-block d-xl-none">
                                <div class="trading-bottom__tab">
                                    <x-flexible-view :view="$activeTemplate . 'trade.tab'"
                                                     :meta="['screen' => 'medium', 'markets' => $markets, 'pair' => $pair]"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 d-xl-none d-block p-0">
                            <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                                'pair' => $pair,
                                'marketCurrencyWallet' => $marketCurrencyWallet,
                                'coinWallet' => $coinWallet,
                                'screen' => 'medium',
                            ]"/>
                        </div>
                        <div class="col-sm-12 mt-0">
                            <x-flexible-view :view="$activeTemplate . 'trade.my_order'" :meta="['pair' => $pair]"/>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 ps-lg-1">
                    <div class="trading-sidebar">
                        <x-flexible-view :view="$activeTemplate . 'trade.pair_list'" :meta="['markets' => $markets]"/>
                        <x-flexible-view :view="$activeTemplate . 'trade.history'" :meta="['pair' => $pair]"/>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end p-5" tabindex="-1" id="deposit-canvas" aria-labelledby="offcanvasLabel">
        <div class="offcanvas-header">
            <span class="fs-18">
                @lang('Deposit Money')
            </span>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-times-circle"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            @auth
                <form action="{{ route('user.deposit.insert') }}" method="post">
                    @csrf
                    <input type="hidden" name="currency" class="deposit-currency-symbol">
                    <input type="hidden" value="spot" name="wallet_type">
                    <div class="form-group">
                        <label class="form-label">@lang('Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form--control form-control" name="amount" required>
                            <span class="input-group-text deposit-currency-symbol"></span>
                        </div>
                    </div>
                    <div class="form-group position-relative">
                        <label class="form-label">@lang('Payment Gateway')</label>
                        <select class="form-control form--control form-select select2" name="gateway" required
                                data-minimum-results-for-search="-1">
                        </select>
                    </div>
                    <div class="form-group preview-details d-none">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('Limit')</span>
                                <span>
                                    <span class="min fw-bold">0</span>
                                    - <span class="max fw-bold">0</span>
                                    <span class="deposit-currency-symbol"></span>
                                </span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('Charge')</span>
                                <span>
                                    <span class="charge fw-bold">0</span>
                                    <span class="deposit-currency-symbol"></span>
                                </span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span> @lang('Payable')</span>
                                <span>
                                    <span class="payable fw-bold">0</span>
                                    <span class="deposit-currency-symbol"></span>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <button class="deposit__button btn btn--base w-100" type="submit"> @lang('Submit') </button>
                </form>
                <div class="p-5 text-center empty-gateway">
                    <img src="{{ asset('assets/images/extra_images/no_money.png') }}" alt="">
                    <span class="mt-3 fs-14">
                        @lang('No payment gateway available for ')
                        <span class="text--base deposit-currency-symbol"></span>
                        @lang('Currency')
                    </span>
                </div>
            @else
                <div class="p-5 text-center d-flex flex-column align-items-center justify-content-center h-100">
                    <img src="{{ asset('assets/images/extra_images/user.png') }}">
                    <span class="fs-12">@lang('Login required for deposit money')</span>
                    <div class="mt-3">
                        <a class="fs-12 text--base" href="{{ route('user.login') }}">@lang('Login')</a>
                        <span>@lang('or')</span>
                        <a class="fs-12 text--base" href="{{ route('user.register') }}">@lang('Register')</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>






    <div class="mobile-footer-menu">
        <div class="mobile-menu-main-section">
            <div class="d-flex justify-content-center m-menu-icon-sec">
                <a href="{{ route('user.home') }}" class="footer-nav-item {{ menuActive('user.home') }}">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="10" width="3" height="7" rx="1" fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                        <rect x="10.5" y="7" width="3" height="10" rx="1" fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                        <rect x="17" y="4" width="3" height="13" rx="1" fill="{{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}"/>
                    </svg>
                </span>
                    <span class="icon-text-title" style="color: {{ request()->routeIs('markets') ? '#f99c26' : '#fff' }}">Stock</span>
                </a>
            </div>

            <div class="d-flex justify-content-center m-menu-icon-sec">
                <a href="{{ route('trade') }}" class="footer-nav-item {{ menuActive('trade') }}">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="17" width="18" height="2" rx="1" fill="{{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}"/>
                        <path d="M8 17V7L12 11L16 7V17" stroke="{{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}" stroke-width="2"/>
                    </svg>
                </span>
                    <span class="icon-text-title" style="color: {{ request()->routeIs('trade') ? '#f99c26' : '#fff' }}">Trade</span>
                </a>
            </div>

            <div class="d-flex justify-content-center m-menu-icon-sec">
                <a href="#" class="footer-nav-item coming_soon">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}" stroke-width="2"/>
                        <text x="12" y="16" text-anchor="middle" fill="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}" font-size="10" font-family="Arial" dy="-2">$</text>
                        <path d="M16 8L18 6" stroke="{{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                    <span class="icon-text-title" style="color: {{ request()->routeIs('earn') ? '#f99c26' : '#fff' }}">Future</span>
                </a>
            </div>

            <div class="d-flex justify-content-center m-menu-icon-sec">
                <a href="{{route('user.wallet.overview')}}" class="footer-nav-item {{ menuActive('user.wallet.*') }}">
                <span class="footer-icon-svg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="7" width="16" height="10" rx="2" stroke="{{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}" stroke-width="2"/>
                        <rect x="8" y="11" width="8" height="2" rx="1" fill="{{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}"/>
                    </svg>
                </span>
                    <span class="icon-text-title" style="color: {{ request()->routeIs('assets') ? '#f99c26' : '#fff' }}">Assets</span>
                </a>
            </div>

        </div>
    </div>
@endsection


@push('script-lib')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.js') }}"></script>

@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/range-ui.css') }}">

@endpush


@push('script')

    @if(request()->get('df') && request()->get('df')=='sl')
        <script>
            $(document).ready(function (){

                $('.btnSell').trigger('click')
            })
        </script>

    @else
        <script>
            $(document).ready(function (){

                $('.btnBuy').trigger('click')
            })
        </script>
    @endif

    <script>
        "use strict";


        $(document).ready(function() {
            $('.findCoin').select2({
                placeholder: "Select an item",
                allowClear: true
            });
        });

        $(document).on('change', '.findCoin', function (e){
            const url=$(this).find(':selected').attr('data-url');

            location.href=url;
        })



        $(document).on('click','.each_order_book',function (e) {
            let rate=$(this).attr('data-rate');
            $('.buy-rate').val(getAmount(rate)).trigger('change');
            $('.sell-rate').val(getAmount(rate)).trigger('change');

            $('.current-coin-price').text(getAmount(rate))
        });


        $(document).on('click', '.btnBuy', function (e){

            $('.trade_buy_form').removeClass('d-none');
            $('.trade_sell_form').addClass('d-none');

            $('.btnBuy').addClass('active');
            $('.btnSell').removeClass('active');
        });

        $(document).on('click', '.btnSell', function (e){

            $('.trade_sell_form').removeClass('d-none');
            $('.trade_buy_form').addClass('d-none');

            $('.btnSell').addClass('active');
            $('.btnBuy').removeClass('active');
        });


        $.each($('.select2'), function (index, element) {
            $(element).select2({
                dropdownParent: $(this).closest('.position-relative')
            });
        });

        $('.new--deposit').on('click', function (e) {

            @auth
            let currency = $(this).data('currency');
            let gateways = @json($gateways);
            let currencyGateways = gateways.filter(ele => ele.currency == currency);


            if (currencyGateways && currencyGateways.length > 0) {
                let gatewaysOption = "";
                $.each(currencyGateways, function (i, currencyGateway) {
                    gatewaysOption += `<option value="${currencyGateway.method_code}"  data-gateway='${JSON.stringify(currencyGateway)}' >
                            ${currencyGateway.name}
                        </option>`;
                });
                $("#deposit-canvas").find('select[name=gateway]').html(gatewaysOption);
                $("#deposit-canvas").find('.deposit-currency-symbol').val(currency);

                $("#deposit-canvas").find(".empty-gateway").addClass('d-none');
                $("#deposit-canvas").find("form").removeClass('d-none');
            } else {
                $("#deposit-canvas").find(".empty-gateway").removeClass('d-none');
                $("#deposit-canvas").find("form").addClass('d-none');
            }
            $("#deposit-canvas").find('.deposit-currency-symbol').text(currency);
            @endauth
            var myOffcanvas = document.getElementById('deposit-canvas');
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas).show();
        });

        @auth
        $('#deposit-canvas').on('change', 'select[name=gateway]', function () {

            if (!$(this).val()) {
                $('#deposit-canvas .preview-details').addClass('d-none');
                return false;
            }

            var resource = $('select[name=gateway] option:selected').data('gateway');
            var fixed_charge = parseFloat(resource.fixed_charge);
            var percent_charge = parseFloat(resource.percent_charge);
            var rate = parseFloat(resource.rate);
            var amount = parseFloat($('#deposit-canvas input[name=amount]').val());

            $('#deposit-canvas .min').text(getAmount(resource.min_amount));
            $('#deposit-canvas .max').text(getAmount(resource.max_amount));


            if (!amount) {
                $('#deposit-canvas .preview-details').addClass('d-none');
                return false;
            }

            $('#deposit-canvas .preview-details').removeClass('d-none');

            var charge = parseFloat(fixed_charge + (amount * percent_charge / 100));
            var payable = parseFloat((parseFloat(amount) + parseFloat(charge)));

            $("#deposit-canvas").find(".empty-gateway").addClass('d-none');
            $("#deposit-canvas").find("form").removeClass('d-none');

            $('#deposit-canvas .charge').text(getAmount(charge));
            $('#deposit-canvas .payable').text(getAmount(payable));

            $('#deposit-canvas .method_currency').text(resource.currency);
            $('#deposit-canvas input[name=amount]').on('input');

        });

        $('#deposit-canvas').on('input', 'input[name=amount]', function () {
            var data = $('#deposit-canvas select[name=gateway]').change();
            $('#deposit-canvas .amount').text(parseFloat($(this).val()).toFixed(2));
        });
        @endauth

        // pusherConnection('market-data', marketChangeHtml);

        var swiper = new Swiper(".myswiper-two", {
            slidesPerView: 5,
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next-two",
                prevEl: ".swiper-button-prev-two",
            },
            breakpoints: {
                575: {
                    slidesPerView: 7,
                    spaceBetween: 0,
                },
                992: {
                    slidesPerView: 7,
                    spaceBetween: 0,
                },
            },
        });

        window.visit_pair = {
            selection: "{{ @$pair->marketData->id }}",
            symbol: "{{ @$pair->symbol }}",
            site_name: "{{ __(gs('site_name')) }}"
        };

        $('header').find(`.container`).addClass(`custom--container`);
    </script>

    <script>
        $(document).ready(function(){


            $('.trade_sell_form').on('submit', function(e) {
                e.preventDefault();
                let formData      = new FormData($(this)[0]);
                let action        = "{{ route('user.order.save', ':symbol') }}";
                let symbol        = "{{ @$pair->symbol }}";
                let token         = $(this).find('input[name=_token]');
                let orderSide     = $(this).find(`input[name=order_side]`).val();
                let cancelMessage = "@lang('Are you sure to cancel this order?')";
                let actionCancel  = "{{ route('user.order.cancel',':id') }}";

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: action.replace(':symbol', symbol),
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.buy-btn').attr('disabled', true);
                        $('.sell-btn').attr('disabled', true);
                        if (orderSide == 1) {
                            $('.buy-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                        } else {
                            $('.sell-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                        }
                    },
                    complete: function() {
                        $('.buy-btn').attr('disabled', false);
                        $('.sell-btn').attr('disabled', false);
                        if (orderSide == 1) {
                            $('.buy-btn').find(`.fa-spin`).remove();
                        } else {
                            $('.sell-btn').find(`.fa-spin`).remove();
                        }
                    },
                    success: function(resp) {
                        if (resp.success) {
                            if (orderSide == 1) {
                                $('.avl-market-cur-wallet').text(resp.data.wallet_balance);
                                $('.buy-charge').addClass('d-none');
                            } else {
                                $('.avl-coin-wallet').text(resp.data.coin_wallet_balance);
                                $('.sell-charge').addClass('d-none');
                            }
                            let order      = resp.data.order;
                            let updateData = {
                                id    : order.id,
                                amount: order.amount,
                                rate  : order.rate
                            }
                            let ordrHtml=`<tr class="skeleton">
                                    <td>${order.formatted_date}</td>
                                    <td>${resp.data.pair_symbol.replace('_','/')} </td>
                                    <td>${order.order_side_badge}</td>
                                    <td>
                                        <div class="order--amount-rate-wrapper">
                                            <span class="order-amount d-block">
                                                ${getAmount(order.amount)}
                                                <span class="amount-rate-update" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="amount">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                            <span class="order-amount d-block">
                                                ${getAmount(order.rate)}
                                                <span class="amount-rate-update" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="rate">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </td>
                                    <td> ${getAmount(order.total)}</td>
                                    <td>${getAmount(0)}</td>
                                    <td> ${order.status_badge.replaceAll('badge','text')} </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="delete-icon p-0 m-0 confirmationBtn" data-question="${cancelMessage}" data-action="${actionCancel.replace(':id',order.id)}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                            notify('success', resp.message);
                            $('.order-list-body').prepend(ordrHtml);
                            $('.order-list-body').find('.empty-thumb').closest('tr').remove();
                            setTimeout(() => {
                                $('.order-list-body tr').removeClass('skeleton');
                            }, 500);

                        } else {
                            notify('error', resp.message);
                        }
                    },
                    error: function(e) {
                        notify("@lang('Something went to wrong')")
                    }
                });
            });



        })
    </script>


    <script>
            "use strict";
            (function($) {

                let marketPrice          = parseFloat("{{ @$pair->marketData->price }}");
                let coinSymbol           = "{{ @$pair->coin->symbol }}";
                let marketCurrencySymbol = "{{ @$pair->market->currency->symbol }}";

                function buyCalculation() {
                    let amount = parseFloat($('.buy-amount').val());
                    if (!amount){
                        $('.buy-charge').addClass('d-none');
                        return false;
                    }
                    let rate           = buyRate();
                    let totalBuyAmount = amount * rate;
                    $('.total-buy-amount').val(getAmount(totalBuyAmount));
                    buyCharge()
                };

                $('.buy-amount, .buy-rate').on('input change', function(e) {
                    let allSameElement=$(this).attr('name');
                    $(`form.buy--form`).find(`input[name=${allSameElement}]`).not(this).val($(this).val())
                    buyCalculation();
                });

                function buyCharge() {
                    let buyPercentCharge = parseFloat("{{ $pair->percent_charge_for_buy }}");
                    let amount           = parseFloat($('.total-buy-amount').val());
                    if(amount && amount > 0){
                        let charge      = (amount / 100) * buyPercentCharge;
                        $('.buy-charge').text(', '+getAmount(charge) + ' ' + marketCurrencySymbol).removeClass('d-none')
                    }else{
                        $('.buy-charge').addClass('d-none');
                    }
                }

                function buyRate() {
                    return parseFloat($('.buy-rate').val() || marketPrice);
                }

                $('.total-buy-amount').on('keyup input change', function(e) {
                    let amount = parseFloat($(this).val());
                    if (!amount) return false;
                    let charge     = buyCharge(amount);
                    let rate       = buyRate();
                    let coinAmount = amount / rate;
                    $('.buy-amount').val(getAmount(coinAmount));
                    buyCharge();
                });

                $('.buy-amount-range').on('click', '.range-list__number', function(e) {
                    @guest return false; @endguest

                    let percent = parseInt($(this).data('percent'));
                    changeBuyAmountRange(percent);

                    $(".buy-amount-slider").find('.ui-widget-header').css({
                        'width': `${percent}%`
                    });

                    $(".buy-amount-slider").find('.ui-state-default').css({
                        'left': `${percent ==100 ? 97 : percent}%`
                    });
                });

                function changeBuyAmountRange(percent) {
                    @guest return false; @endguest

                        percent = parseFloat(percent);

                    if (percent > 100) {
                        notify('error', "@lang('Invalid amount range selected')");
                        return false;
                    }

                    let availableBalance = parseFloat("{{ @$marketCurrencyWallet->balance }}");
                    if (availableBalance <= 0) return false;

                    let percentAmount = (availableBalance / 100) * percent;
                    $('.total-buy-amount').val(getAmount(percentAmount)).trigger('change');
                }

                $(".buy-amount-slider").slider({
                    range: true,
                    min: 0,
                    max: 100,
                    values: [0, 0],
                    slide: function(event, ui) {
                        changeBuyAmountRange(ui.value);
                    },
                    change: function(event, ui) {
                        changeBuyAmountRange(ui.value);
                    }
                });

                $('.sell-rate, .sell-amount').on('input change', function(e) {
                    let allSameElement=$(this).attr('name');
                    $(`form.sel--form`).find(`input[name=${allSameElement}]`).not(this).val($(this).val())
                    sellCalculation();
                });

                // sell calculation
                function sellCharge() {
                    let sellPercentCharge = parseFloat("{{ $pair->percent_charge_for_sell }}");
                    let amount            = parseFloat($('.total-sell-amount').val());
                    if(amount && amount > 0){
                        let charge      = (amount / 100) * sellPercentCharge;
                        $('.sell-charge').text(', '+getAmount(charge) + ' ' + marketCurrencySymbol).removeClass('d-none')
                    }else{
                        $('.sell-charge').addClass('d-none');
                    }
                }

                function sellRate() {
                    return parseFloat($('.sell-rate').val() || marketPrice);
                }

                function sellCalculation() {
                    let amount = parseFloat($('.sell-amount').val());
                    if (!amount){
                        $('.sell-charge').addClass('d-none');
                        return false;
                    }
                    let rate            = sellRate();
                    let totalSellAmount = amount * rate;
                    $('.total-sell-amount').val(getAmount(totalSellAmount));
                    sellCharge();
                };

                $('.total-sell-amount').on('keyup input change', function(e) {
                    let amount = parseFloat($(this).val());
                    if (!amount) return false;
                    let charge = sellCharge(amount);
                    let rate   = sellRate();
                    let marketAmount = amount / rate;
                    $('.sell-amount').val(getAmount(marketAmount));
                    sellCharge();
                });

                function changeSellAmountRange(percent) {
                    @guest return false; @endguest

                        percent = parseFloat(percent);

                    if (percent > 100) {
                        notify('error', "@lang('Invalid amount range selected')");
                        return false;
                    }

                    let availableBalance = parseFloat("{{ @$coinWallet->balance }}");
                    if (availableBalance <= 0) return false;

                    let percentAmount = (availableBalance / 100) * percent;
                    $('.sell-amount').val(getAmount(percentAmount)).trigger('change');
                }

                $('.sell-amount-range').on('click', '.range-list__number', function(e) {

                    @guest return false; @endguest
                    let percent = parseInt($(this).data('percent'));
                    changeSellAmountRange(percent);

                    $(".sell-amount-slider").find('.ui-widget-header').css({
                        'width': `${percent}%`
                    });

                    $(".sell-amount-slider").find('.ui-state-default').css({
                        'left': `${percent == 100 ? 97 : percent}%`
                    });
                });

                $(".sell-amount-slider").slider({
                    range: true,
                    min: 0,
                    max: 100,
                    values: [0, 0],
                    slide: function(event, ui) {
                        changeSellAmountRange(ui.value);
                    },
                    change: function(event, ui) {
                        changeSellAmountRange(ui.value);
                    }
                });

                $('.trade_buy_form').on('submit', function(e) {
                    e.preventDefault();
                    let formData      = new FormData($(this)[0]);
                    let action        = "{{ route('user.order.save', ':symbol') }}";
                    let symbol        = "{{ @$pair->symbol }}";
                    let token         = $(this).find('input[name=_token]');
                    let orderSide     = $(this).find(`input[name=order_side]`).val();
                    let cancelMessage = "@lang('Are you sure to cancel this order?')";
                    let actionCancel  = "{{ route('user.order.cancel',':id') }}";

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        url: action.replace(':symbol', symbol),
                        method: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.buy-btn').attr('disabled', true);
                            $('.sell-btn').attr('disabled', true);
                            if (orderSide == 1) {
                                $('.buy-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                            } else {
                                $('.sell-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                            }
                        },
                        complete: function() {
                            $('.buy-btn').attr('disabled', false);
                            $('.sell-btn').attr('disabled', false);
                            if (orderSide == 1) {
                                $('.buy-btn').find(`.fa-spin`).remove();
                            } else {
                                $('.sell-btn').find(`.fa-spin`).remove();
                            }
                        },
                        success: function(resp) {
                            if (resp.success) {
                                if (orderSide == 1) {
                                    $('.avl-market-cur-wallet').text(resp.data.wallet_balance);
                                    $('.buy-charge').addClass('d-none');
                                } else {
                                    $('.avl-coin-wallet').text(resp.data.coin_wallet_balance);
                                    $('.sell-charge').addClass('d-none');
                                }
                                let order      = resp.data.order;
                                let updateData = {
                                    id    : order.id,
                                    amount: order.amount,
                                    rate  : order.rate
                                }
                                let ordrHtml=`<tr class="skeleton">
                                    <td>${order.formatted_date}</td>
                                    <td>${resp.data.pair_symbol.replace('_','/')} </td>
                                    <td>${order.order_side_badge}</td>
                                    <td>
                                        <div class="order--amount-rate-wrapper">
                                            <span class="order-amount d-block">
                                                ${getAmount(order.amount)}
                                                <span class="amount-rate-update" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="amount">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                            <span class="order-amount d-block">
                                                ${getAmount(order.rate)}
                                                <span class="amount-rate-update" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="rate">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </td>
                                    <td> ${getAmount(order.total)}</td>
                                    <td>${getAmount(0)}</td>
                                    <td> ${order.status_badge.replaceAll('badge','text')} </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="delete-icon p-0 m-0 confirmationBtn" data-question="${cancelMessage}" data-action="${actionCancel.replace(':id',order.id)}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                                notify('success', resp.message);
                                $('.order-list-body').prepend(ordrHtml);
                                $('.order-list-body').find('.empty-thumb').closest('tr').remove();
                                setTimeout(() => {
                                    $('.order-list-body tr').removeClass('skeleton');
                                }, 500);

                            } else {
                                notify('error', resp.message);
                            }
                        },
                        error: function(e) {
                            notify("@lang('Something went to wrong')")
                        }
                    });
                });

                $('.order-type').on('click', function(e) {
                    let orderType = $(this).data('order-type');

                    $('.order-type').find('button').removeClass('active');
                    $(this).find('button').addClass('active');
                    $(this).closest('.trading-bottom').find('.order-wrapper');

                    if (orderType == 'market') {
                        $('.buy-rate').attr('readonly', true);
                        $('.sell-rate').attr('readonly', true);
                        $(`input[name=order_type]`).val(`{{ Status::ORDER_TYPE_MARKET }}`);
                    } else {
                        $('.buy-rate').attr('readonly', false);
                        $('.sell-rate').attr('readonly', false);
                        $(`input[name=order_type]`).val(`{{ Status::ORDER_TYPE_LIMIT }}`);
                    }
                });

            })(jQuery);
        </script>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/decimal.js/10.4.3/decimal.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const coinName ='{{isset($pair)?strtoupper(str_replace('_','', $pair->symbol)):'SOLUSDT'}}'
            const symbol = coinName;
            let lastPrice = 0;
            let orderBook = {
                bids: [],
                asks: []
            };
            let ws = null;

            function connectWebSocket() {
                if (ws) {
                    ws.close();
                }
                const wsUrl = `wss://stream.binance.com:9443/ws/${symbol.toLowerCase()}@ticker/${symbol.toLowerCase()}@depth@100ms`;
                ws = new WebSocket(wsUrl);
                ws.onmessage = (event) => {
                    const data = JSON.parse(event.data);
                    if (data.e === '24hrTicker') {
                        updatePriceInfo(data);
                    } else if (data.e === 'depthUpdate') {
                        updateOrderBook(data);
                    }
                };
                ws.onclose = () => {
                    console.log('WebSocket connection closed. Reconnecting...');
                    setTimeout(connectWebSocket, 5000);
                };
            }

            // Set up interval to refresh data every 5 seconds
            setInterval(() => {
                connectWebSocket();
            }, 5000);

            // Initial connection
            connectWebSocket();

            function updatePriceInfo(data) {
                const currentPriceEl = document.getElementById('currentPrice');
                const usdPriceEl = document.getElementById('usdPrice');
                const priceChangeEl = document.getElementById('priceChange'); // This element is outside the provided snippet, might need adjustment
                const price = parseFloat(data.c);
                const priceChange = parseFloat(data.P);
                currentPriceEl.textContent = price.toFixed(2);
                usdPriceEl.textContent = `≈${price.toFixed(2)} USD`;
                if (price !== lastPrice) {
                    currentPriceEl.classList.remove('text-success', 'text-danger');
                    currentPriceEl.classList.add(price > lastPrice ? 'text-success' : 'text-danger');
                    lastPrice = price;
                }
                // The priceChangeEl logic assumes it exists in the main HTML, but not in this snippet
                // If you need it for this order book snippet, you would need to add it to the HTML above.
                if (priceChangeEl) {
                    priceChangeEl.textContent = `${priceChange >= 0 ? '+' : ''}${priceChange.toFixed(2)}%`;
                    priceChangeEl.classList.remove('text-primary', 'text-red-500');
                    priceChangeEl.classList.add(priceChange >= 0 ? 'text-primary' : 'text-red-500');
                }
            }

            function updateOrderBook(data) {
                // Update bids
                data.b.forEach(([price, quantity]) => {
                    updateOrderBookSide('bids', price, quantity);
                });
                // Update asks
                data.a.forEach(([price, quantity]) => {
                    updateOrderBookSide('asks', price, quantity);
                });
                renderOrderBook();
            }

            function updateOrderBookSide(side, price, quantity) {
                const decimal = new Decimal(quantity);
                const priceNum = parseFloat(price);
                const index = orderBook[side].findIndex(item => parseFloat(item[0]) === priceNum);
                if (decimal.isZero()) {
                    if (index !== -1) {
                        orderBook[side].splice(index, 1);
                    }
                } else {
                    if (index !== -1) {
                        orderBook[side][index] = [price, quantity];
                    } else {
                        orderBook[side].push([price, quantity]);
                        orderBook[side].sort((a, b) => {
                            return side === 'bids'
                                ? parseFloat(b[0]) - parseFloat(a[0])
                                : parseFloat(a[0]) - parseFloat(b[0]);
                        });
                    }
                }
                orderBook[side] = orderBook[side].slice(0, 9);
            }

            function renderOrderBook() {
                const sellOrdersContainer = document.querySelector('.order-book-sell');
                const buyOrdersContainer = document.querySelector('.order-book-buy');

                // Calculate total volumes
                let totalBuyVolume = orderBook.bids.reduce((sum, [_, quantity]) => sum + parseFloat(quantity), 0);
                let totalSellVolume = orderBook.asks.reduce((sum, [_, quantity]) => sum + parseFloat(quantity), 0);
                let totalVolume = totalBuyVolume + totalSellVolume;

                // Calculate percentages
                let buyPercentage = totalVolume > 0 ? Math.round((totalBuyVolume / totalVolume) * 100) : 0;
                let sellPercentage = 100 - buyPercentage;

                // Update process bar
                const processBar = document.querySelector('.process-bar');
                processBar.innerHTML = `
                    <div class="process-bar-container">
                        <div class="process-bar-wrapper">
                            <div class="bg-primary opacity-80 text-xs flex items-center justify-center process-bar-buy" style="width: ${buyPercentage}%">
                                <span class="text-white font-medium">B</span>
                                <span class="text-white ml-1">${buyPercentage}%</span>
                            </div>
                            <div class="bg-secondary opacity-80 text-xs flex items-center justify-center process-bar-sell" style="width: ${sellPercentage}%">
                                <span class="text-white ml-1">${sellPercentage}%</span>
                                <span class="text-white font-medium ml-1">S</span>
                            </div>
                        </div>
                        <div class="process-bar-stats">
                            <div class="buy-stats">
                                <span class="text-primary">Buy: ${formatQuantity(totalBuyVolume)}</span>
                                <span class="text-primary">(${buyPercentage}%)</span>
                            </div>
                            <div class="sell-stats">
                                <span class="text-secondary">Sell: ${formatQuantity(totalSellVolume)}</span>
                                <span class="text-secondary">(${sellPercentage}%)</span>
                            </div>
                        </div>
                    </div>
                `;

                // Render sell orders
                const sellOrdersHTML = orderBook.asks.map(([price, quantity]) => `
                    <div class="order-book-row flex justify-between items-center p-1 px-2 each_order_book" data-rate="${parseFloat(price).toFixed(2)}">
                        <div class="text-red-500">${parseFloat(price).toFixed(2)}</div>
                        <div>${formatQuantity(quantity)}</div>
                        <div class="order-book-bg-bar" style="width: ${(parseFloat(quantity) / totalSellVolume * 100)}%;"></div>
                    </div>
                `).join('');

                // Render buy orders
                const buyOrdersHTML = orderBook.bids.map(([price, quantity]) => `
                    <div class="order-book-row flex justify-between items-center p-1 px-2 each_order_book" data-rate="${parseFloat(price).toFixed(2)}">
                        <div class="text-primary">${parseFloat(price).toFixed(2)}</div>
                        <div>${formatQuantity(quantity)}</div>
                        <div class="order-book-bg-bar" style="width: ${(parseFloat(quantity) / totalBuyVolume * 100)}%;"></div>
                    </div>
                `).join('');

                sellOrdersContainer.innerHTML = sellOrdersHTML;
                buyOrdersContainer.innerHTML = buyOrdersHTML;
            }

            function formatQuantity(quantity) {
                const num = parseFloat(quantity);
                return num >= 1000 ? `${(num / 1000).toFixed(3)}K` : num.toFixed(3);
            }
        });
    </script>


@endpush


@push('style')

    <style>

        @media (max-width: 800px) {
            .trading-table{
                margin-bottom: 60px !important;
            }
        }


        .form--control.style-three{
            color: white !important;
        }
        .btn--base-two{
            color: hsl(0deg 0% 100%) !important;
            padding: 13px !important;
        }

        .order-book-crr-price{
            line-height: 18px;
        }
        .tp-sl-section label{
            font-size: 15px;
            color: white;
        }
        .tp-sl-section .ech-sec{
            padding-top: 4px;
            padding-left: 10px;
        }
        .tp-sl-section{
            margin-top: 55px;
        }
        .select2-selection__clear,.select2-selection__arrow{
            display: none !important;
        }
        .select2-container--default .select2-selection--single{
            background-color: #363d3f !important;
            border: hidden;
            height: 42px !important;
            padding: 6px 0px !important;
            color: white !important;
        }
        .choose-m-type{
            padding: 9px 7px !important;
            font-size: 14px !important;
        }
        .form--control.style-three:focus{
            border: hidden !important;
            color: white !important;
        }
        .form--control{
            background: #7c7c7c61 !important;
            border: hidden !important;
        }
        .input--group.group-two span{
            color: white !important;
        }



        .buy-sell__price{
            padding: 0px !important;
        }
        .trade_sell_form .buy-sell__wrapper{
            margin-top: 20px !important;
        }
        .trade_buy_form .buy-sell__wrapper{
            margin-top: 20px !important;
        }
        .section-buttons .btnBuy.active{
            background: #09a46d;
            border-radius: 5px;
        }
        .section-buttons .btnSell.active{
            background: #fb4646;
            border-radius: 5px;
        }
        .section-buttons .btnBuy,.btnSell{
            background: #4c4c4c;
            color: white;
            width: 100%;
            font-weight: 900;
            font-size: 12px !important;
            padding: 10px 5px;
        }
        .section-buttons{
            display: flex;
            background: #4c4c4c;
            width: 100%;
            text-align: center;
            justify-content: center;
        }
         .process-bar .sec.first{
            border-radius: 5px 0px 0px 5px;
         }
         .process-bar .sec.second{
            border-radius: 0px 5px 5px 0px;
         }
        .process-bar .sec{
            padding: 4px !important;
        }
        .process-bar .bg-primary{
            background-color: #0ec40e !important;
        }
        .process-bar .bg-secondary{
            background-color: #fb4646 !important;
        }
        .process-bar{
            display: flex;
            font-size: 11px !important;
        }
        .orderBook-header{
            display: flex;
            font-size: 11px !important;
        }
        .order-book-sell .order-book-row .text-red-500{
            color: red;
        }
        .order-book-sell .order-book-row{
            display: flex;
            font-size: 12px;
            width: 100%;
            justify-content: space-between;
        }


        .order-book-buy .order-book-row .text-primary{
            color: #08d108 !important;
        }
        .order-book-buy .order-book-row{
            display: flex;
            font-size: 12px;
            width: 100%;
            justify-content: space-between;
        }



        .order-book-row {
            position: relative; /* Needed for absolute positioning of the background bar */
            overflow: hidden; /* Ensures the bar doesn't overflow the row */
            cursor: pointer; /* To indicate interactivity on hover */
        }

        .order-book-row:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .order-book-bg-bar {
            position: absolute !important;
            top: 0;
            height: 100%;
            z-index: 0; /* Behind the text content */
            opacity: 0.3; /* Adjust opacity as needed */
        }

        .order-book-sell .order-book-bg-bar {
            background-color: #f6465d; /* Secondary color for sell orders */
            right: 0; /* Aligned to the right */
        }

        .order-book-buy .order-book-bg-bar {
            background-color: #2ebd85 !important; /* Primary color for buy orders */
            left: 0; /* Aligned to the left */
        }

        .order-book-row > div {
            position: relative; /* Brings text content above the background bar */
            z-index: 1;
        }

        .bottom-tab-active {
            border-bottom: 2px solid #f0b90b;
            color: #f0b90b;
        }

        .table.table-two tbody tr td{
            color: hsl(0deg 0% 100% / 70%) !important;
        }
        .nav-link{
            color: white !important;
        }
        .trade-tab-row .trade-tab{
            text-align: center;
            background: #2b2a2a;
            padding: 10px;
        }
        .trade-tab-row .trade-tab.active a{
            color: white !important;
        }
        .trade-tab-row .trade-tab.active{
            text-align: center;
            background: #615e5e;
            padding: 5px;
        }
        .trading-section.bg-color.py-60{
            padding-top: 0px !important;
        }

        #header {
            display: none;
        }
    </style>



    <style>
        .sync-page-for-sm-device {
            display: none;
        }

        @media (max-width: 700px) {
            .sync-page-for-sm-device {
                display: block;
            }
        }

        .sync-page-for-sm-device {
            float: right;
            position: absolute;
            right: 30px;
        }

        .cookies-card {
            background-color: #181d20 !important;
            color: #93988f !important;
        }

        .has-mega-menu .mega-menu {
            background: #181d20 !important;
        }

        .select2-image {
            max-width: 35px;
        }


        /* ////////////////// select 2 //////////////// */
        .select2-dropdown {
            background-color: #09171a;
            border-color: hsl(var(--white)/0.14);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-radius: 6px;
            font-weight: 400;
            outline: none;
            width: 100%;
            padding: 10px;
            background-color: transparent;
            border-color: hsl(var(--white) / 0.2) !important;
            color: #fff !important;
            line-height: 1;
            margin: 10px 0px;
        }

        .select2-container--default .select2-selection--single {
            background-color: transparent;
            border-color: hsl(var(--white)/0.14);
            height: 52px;
            padding: 10px 0px;

        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white !important;
        }

        .select2-container .selection {
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 11px !important;
            right: 5px !important;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: hsl(var(--base-d-400)) !important;
        }
    </style>

    <style>
        .process-bar-container {
            margin: 10px 0;
        }

        .process-bar-wrapper {
            display: flex;
            height: 24px;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .process-bar-buy {
            background-color: #0ec40e !important;
            transition: width 0.3s ease;
        }

        .process-bar-sell {
            background-color: #fb4646 !important;
            transition: width 0.3s ease;
        }

        .process-bar-stats {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 0 5px;
        }

        .buy-stats, .sell-stats {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .buy-stats span {
            color: #0ec40e;
        }

        .sell-stats span {
            color: #fb4646;
        }
    </style>
@endpush
