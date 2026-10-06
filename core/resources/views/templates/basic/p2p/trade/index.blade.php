@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @include($activeTemplate . 'sections.p2p_banner')
    @php $request=request(); @endphp
    <div class="p2p-header">
        <div class="container">
            <div class="p2p-header-wrapper">
                <div class="p2p-header__top">
                    <div class="p2p-header__top-left">
                        <div class="buy-sell-tab buy-sell-tab-desktop {{ $request->type ?? 'buy' }}">
                            <button type="button"
                                    class="buy-sell-tab__link buy btn buy--sell-btn @if (@$request->type != 'sell') active @endif"
                                    data-type="buy">
                                @lang('Buy')
                            </button>
                            <button type="button"
                                    class="buy-sell-tab__link sell btn buy--sell-btn @if (@$request->type == 'sell') active @endif"
                                    data-type="sell">
                                @lang('Sell')
                            </button>
                        </div>

                        <div class="buy-sell-tab buy-sell-tab-mobile">
                            <div class="row">
                                <div class="col-6">
                                    <button type="button"
                                            class="buy-sell-tab__link d-block buy btn buy--sell-btn @if (@$request->type != 'sell') active @endif"
                                            data-type="buy">
                                        @lang('Buy')
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button"
                                            class="buy-sell-tab__link d-block sell btn buy--sell-btn @if (@$request->type == 'sell') active @endif"
                                            data-type="sell">
                                        @lang('Sell')
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="coin-list d-none">
                            @foreach ($coins as $coin)
                                @php
                                    if ($request->coin) {
                                        $activeClass = $request->coin && strtoupper($request->coin) == strtoupper($coin->symbol) ? 'active' : '';
                                    } else {
                                        $activeClass = $loop->first ? 'active' : '';
                                    }
                                @endphp
                                <button type="button" class="coin-list__item coin-symbol {{ $activeClass }}"
                                        data-symbol="{{ @$coin->symbol }}">
                                    {{ __($coin->symbol) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-2">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-sm-4 d-none">
                    <input type="number" step="any" placeholder="@lang('Amount')"
                           class="form-control form--control filter-amount" value="{{ $request->amount ?? '' }}">
                </div>

                <div class="col-xl-3 col-sm-4 col-4 cs-select-row">

                    <div class="form-group choose-coin-section">
                        <select name="" id="" class="form-control select2 chooseCoin">
                            <option value="">--Coin--</option>
                            @foreach ($coins as $coin)

                                <option {{$request->coin && strtoupper($request->coin) == strtoupper($coin->symbol) ? 'selected' : ''}} value="{{ @$coin->symbol }}">{{ __($coin->symbol) }}</option>

                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="col-xl-3 col-sm-4 col-4 cs-select-row" id="currency-col">
                    <div class="p2p-custom--dropdown mb-4">
                        <div class="p2p-custom--dropdown-right dropdown currency-dropdown">
                            <div class="p2p-custom--dropdown-select" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="p2p-custom--dropdown-select-box justify-content-between">
                                    <div
                                        class="p2p-custom--dropdown-select-box justify-content-between dropdown-selcted-result">
                                        @if ($request->currency && $request->currency != 'all')
                                            @php
                                                $requestCurrency = $currencies->where('symbol', $request->currency)->first();
                                            @endphp
                                            <img src="{{ @$requestCurrency->image_url }}" />
                                            <span class="f-14 has-value" data-value="{{ @$requestCurrency->symbol }}">
                                                {{ __(@$requestCurrency->symbol) }}
                                            </span>
                                        @else
                                            <span class="f-14">@lang('Currency')</span>
                                        @endif
                                    </div>
                                    <i class="las la-angle-down"></i>
                                </div>
                            </div>
                            <ul class="p2p-custom--dropdown-menu dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <li class="p2p-custom--dropdown-search-item">
                                    <div class="search-inner">
                                        <button class="search-icon" type="search"> <i class="fas fa-search"></i></button>
                                        <input class="search-input form--control search-inside-drodown"
                                               placeholder="@lang('Search')">
                                    </div>
                                </li>
                                @foreach ($currencies as $currency)
                                    <li class="p2p-custom--dropdown-menu-item searchable-item"
                                        data-value="{{ $currency->symbol }}">
                                        <div slot="select-item" class="link">
                                            <img src="{{ $currency->image_url }}">
                                            <span class="text">{{ __($currency->symbol) }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-xl-3 col-4 cs-select-row" id="payment-method-col">
                    <div class="p2p-custom--dropdown mb-4 payment-method-dropdown">
                        <div class="p2p-custom--dropdown-right dropdown">
                            <div class="p2p-custom--dropdown-select" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="p2p-custom--dropdown-select-box justify-content-between">
                                    <div
                                        class="p2p-custom--dropdown-select-box justify-content-between dropdown-selcted-result search-result-payment-method">
                                        @if ($request->paymentMethod && $request->paymentMethod != 'all' && ($request->currency && $request->currency != 'all'))
                                            @php
                                                $selectPaymentMethod = $paymentMethods->where('slug', $request->paymentMethod)->first();
                                            @endphp
                                            <span class="color"
                                                  style="background-color:#{{ @$selectPaymentMethod->branding_color }}"></span>
                                            <span class="f-14 has-value" data-value="{{ @$selectPaymentMethod->name }}">
                                                {{ __(@$selectPaymentMethod->name) }}
                                            </span>
                                        @else
                                            <span class="f-14">@lang('Payments')</span>
                                        @endif
                                    </div>
                                    <i class="las la-angle-down"></i>
                                </div>
                            </div>
                            <ul class="p2p-custom--dropdown-menu dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                @if ($requestedCurrencyPaymentMethods->count())
                                    <li class="p2p-custom--dropdown-search-item">
                                        <div class="search-inner">
                                            <button class="search-icon" type="search"> <i
                                                    class="fas fa-search"></i></button>
                                            <input class="search-input form--control search-inside-drodown"
                                                   placeholder="@lang('Search')">
                                        </div>
                                    </li>
                                    @foreach ($requestedCurrencyPaymentMethods as $requestedCurrencyPaymentMethod)
                                        <li class="p2p-custom--dropdown-menu-item searchable-item"
                                            data-value="{{ __(@$requestedCurrencyPaymentMethod->slug) }}">
                                            <div slot="select-item" class="link">
                                                <span class="color"
                                                      style="background-color: #{{ @$requestedCurrencyPaymentMethod->branding_color }}"></span>
                                                <span class="text">
                                                    {{ __(@$requestedCurrencyPaymentMethod->name) }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="p2p-custom--dropdown-menu-item">
                                        <div slot="select-item" class="link ">
                                            <span class="text">@lang('Please Select Currency')</span>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-4 col-4 cs-select-row d-none">
                    <div class="p2p-custom--dropdown mb-4 country-dropdown">
                        <div class="p2p-custom--dropdown-right dropdown">
                            <div class="p2p-custom--dropdown-select" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="p2p-custom--dropdown-select-box justify-content-between">
                                    <div
                                        class="p2p-custom--dropdown-select-box justify-content-between dropdown-selcted-result search-result-region">
                                        @if (@$request->region && @$request->region != 'all')
                                            @php
                                                $countryCode = @$request->region;
                                                $requestedCoutry = $countries->$countryCode;
                                            @endphp
                                            <img
                                                src="{{ getImage(getFilePath('country') . '/' . strtolower(@$countryCode) . '.svg', getFileSize('country')) }}">
                                            <span class="f-14 has-value" data-value="{{ $requestedCoutry->country }}">
                                                {{ __($requestedCoutry->country) }}
                                            </span>
                                        @else
                                            <span class="f-14">{{ @$requestedCountry->country ?? 'Region' }}</span>
                                        @endif
                                    </div>
                                    <i class="las la-angle-down"></i>
                                </div>
                            </div>
                            <ul class="p2p-custom--dropdown-menu dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <li class="p2p-custom--dropdown-search-item">
                                    <div class="search-inner">
                                        <button class="search-icon" type="search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input class="search-input form--control search-inside-drodown"
                                               placeholder="@lang('Search')">
                                    </div>
                                </li>
                                @foreach ($countries as $k => $country)
                                    <li class="p2p-custom--dropdown-menu-item searchable-item"
                                        data-value="{{ $k }}">
                                        <div slot="select-item" class="link ">
                                            <img
                                                src="{{ getImage(getFilePath('country') . '/' . strtolower($k) . '.svg', getFileSize('country')) }}">
                                            <span class="text">{{ __(@$country->country) }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>
    <section class="p2p-table-section mb-5">
        <div class="container">
            <table class="table p2p-table table--responsive--lg">
                {{--                <thead>--}}
                {{--                    <tr>--}}
                {{--                        <th>@lang('Advertiser')</th>--}}
                {{--                        <th>@lang('Price')</th>--}}
                {{--                        <th>@lang('Available/Limit')</th>--}}
                {{--                        <th>@lang('Payment Window')</th>--}}
                {{--                        <th>@lang('Payment Method')</th>--}}
                {{--                        <th>@lang('Trade')<small class="fs-11 text--info">( {{getAmount(gs('p2p_trade_charge'))}}% @lang('Fee'))</small> </th>--}}
                {{--                    </tr>--}}
                {{--                </thead>--}}
                <tbody id="ad-list">
                @include($activeTemplate . 'p2p.ad.list')
                </tbody>
            </table>
        </div>

        {{--        <div class="container">--}}
        {{--            <div id="ad-list">--}}
        {{--                @include($activeTemplate . 'p2p.ad.list')--}}

        {{--            </div>--}}
        {{--        </div>--}}


        <div class="text-center mt-3">
            <button type="button" class="btn btn--base outline btn--sm @if ($totalAds <= 20) d-none @endif"
                    id="loadMore">
                <i class="fa fa-spinner"></i> @lang('Load More')
            </button>
        </div>
    </section>
    @include($activeTemplate . 'p2p.trade.buy_sell_script')
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){

            $('.chooseCoin').select2({
                multiple:false
            })
        })
    </script>

@endpush

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/p2p.css') }}">

    <style>

        .choose-coin-section .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: 18px !important;
        }
        .select2-dropdown{
            background: black !important;
        }
        .choose-coin-section .select2-selection.select2-selection--single{
            padding: 4px 5px 0px 5px;
            font-size: 13px;
            height: 39px !important;
            border: 2px solid #333333 !important;
        }

        @media(max-width: 750px){
            #header{
                display: none !important;
            }
            .p2p-header{
                top: 0px !important;
            }
            .cs-select-row{
                padding: 4px !important;
            }
            .p2p-custom--dropdown-right .f-14{
                font-size: 12px !important;
            }
            .p2p-custom--dropdown-right{
                padding: 0 7px !important;
                height: 40px !important;
            }
        }

        .section-trade-com{
            font-size: 12px;
        }
        .section-p-price .p-price{
            font-size: 12px;
        }
        .section-p-price .amount{
            font-size: 20px !important;
        }
        .section-p-price .currency{
            font-size: 12px !important;
        }
        .section-quantity .quantity,.limit{
            font-size: 12px;
            color: #979797;
        }
        .section-quantity{
            color: #c9c9c9 !important;
        }
        .section-bu-option button{
            padding: 6px 15px !important;
            font-size: 11px !important;
        }

        @media (max-width: 750px) {
            .section-bu-option{
                /*margin-top: 30px;*/
                text-align: right;
            }
            .timer-sec{
                font-size: 12px;
                padding-bottom: 10px;
            }
            .section-p-price{
                margin-top: 6px;
            }
            .payment-list{
                margin-top: 5px;
                display: flex !important;
                justify-content: flex-start !important;
                flex-direction: column-reverse !important;
                text-align: right !important;
            }
            .section-p-price .amount{
                color: white;
            }
            .section-p-price .currency{
                color: #9b9b9b;
            }
            .section-p-price .p-price{
                padding-bottom: 3px;
                color: #9b9b9b;
            }
            .section-quantity{
                margin-top: 10px;
            }
            .payment-list .payment-list__item{
                font-size: 9px !important;
            }
            #ad-list tr td::before{
                display: none;
            }
            #ad-list tr td{
                width: 100% !important;
                text-align: left;
            }

            .banner-section-two{
                display:none !important;
            }
        }

        .section-trade-com{
            font-size: 11px;
            margin-bottom: 10px;
        }


    </style>
@endpush
