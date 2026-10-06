@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 justify-content-center">
        <div class="col-12 mt-0">
            <div class="dashboard-header-menu justify-content-between align-items-center">
                <h4 class="mb-0 d-none">{{ __($pageTitle) }}</h4>
                <div class="div">
                    <a href="{{ route('user.wallet.overview') }}"
                       class="dashboard-header-menu__link  {{ menuActive('user.wallet.overview', null) }}">
                        @lang('Overview')
                    </a>
                    <a href="{{ route('user.wallet.list', 'spot') }}"
                       class="dashboard-header-menu__link  {{ menuActive('user.wallet.list', null, 'spot') }}">
                        @lang('Spot')
                    </a>
                    <a href="{{ route('user.wallet.list', 'funding') }}"
                       class="dashboard-header-menu__link  {{ menuActive('user.wallet.list', null, 'funding') }}">
                        @lang('Funding')
                    </a>
                    <a href="{{ route('user.wallet.stock') }}"
                       class="dashboard-header-menu__link  {{ menuActive('user.wallet.stock', null) }}">
                        @lang('Stock')
                    </a>

                    <a href="{{ route('user.wallet.bond') }}"
                       class="dashboard-header-menu__link  {{ menuActive('user.wallet.bond', null) }}">
                        @lang('Bond')
                    </a>

                    <a href="{{ route('user.wallet.copy') }}"
                       class="dashboard-header-menu__link  {{ menuActive('user.wallet.copy', null) }}">
                        @lang('Copy')
                    </a>
                </div>
            </div>
        </div>
        @if(isset($overview) && $overview)
            <div class="col-lg-12">
                <div class="table-wrapper pt-0">
                    @if(isset($estimatedBalances))
                        <div class="n-view-balance">
                            <h5 class="mb-0">Total Balance</h5>
                            <div class="bal-m-sec d-none">
                                <div class="main-balance">
                                    <span
                                        class="bal-font">{{ str_replace('USD', '', showAmount($estimatedBalances)) }}</span>
                                    <span
                                        class="currency-font">USD</span> <i class="fa fa-eye hide-balance"></i>
                                </div>
                                <div class="main-balance-hide">
                                    ********* <i class="fa fa-eye-slash show-balance"></i>
                                </div>
                            </div>
                            <small class="today-pnl">Today PNL $00000(-0.00%)</small>
                        </div>
                    @endif


                        <div class="row user-deposit-history coming_soon">
                            <div class="col-md-10 col-10">
                                <i class="fas fa-dollar"></i> <span style="font-size: 13px;" class="text">Hold USDT up to 9% ARP</span>
                            </div>
                            <div class="col-md-2 col-2 text-center">
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>



                    <div class="row" style="padding-top: 20px">
                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons deposit-cash-wallet">
                                <img src="{{asset('core/public/icon/deposit.svg')}}" alt="">
                            </div>
                            <small class="w-icon-t-section">
                                Deposit
                            </small>
                        </div>
                        @php $ttype=isset($typeValue)?$typeValue:'spot'; @endphp

                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons withdraw-cash">
                                <img src="{{asset('core/public/icon/invite-friend.svg')}}" alt="">
                            </div>
                            <small class="w-icon-t-section">
                                Withdraw
                            </small>
                        </div>

                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons" data-url="{{ route('user.convert') }}">
                                <img src="{{asset('core/public/icon/fait-deposit.svg')}}" alt="">

                            </div>
                            <small class="w-icon-t-section">
                                Convert
                            </small>
                        </div>
                        @php $w_type=$typeValue==1?'spot':'funding'; @endphp
                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons"
                                 data-url="{{ route('user.wallet.view',  [ 'type' => $w_type,'currencySymbol' => 'USDT','fm'=>'transfer']) }}">
                                <img src="{{asset('core/public/icon/p2p-trading.svg')}}" alt="">

                            </div>
                            <small class="w-icon-t-section">
                                Transfer
                            </small>
                        </div>

                    </div>


                </div>

                <table class="table">
                    <input type="text" style="background: #1a1a1a;color: white !important;border-color: #4b4949;"
                           id="currencySearch" class="form-control mb-3 mt-2" placeholder="Search by Symbol"
                           onkeyup="searchTable()">


                    <thead>
                    <tr>
                        <th>@lang('Currency')</th>
                        <th>@lang('Available Balance')</th>
                    </tr>
                    </thead>
                    <tbody id="walletTableBody">
                    @forelse($wallets as $wallet)
                        <tr>
                            <td class="d-flex">
                                <a class="d-flex"
                                   href="{{ route('user.wallet.view',  [ 'type' => $wallet->typeText,'currencySymbol' => @$wallet->currency->symbol]) }}">
                                    <div class="customer__thumb">
                                        <img src="{{ @$wallet->currency->image_url }}">
                                    </div>

                                    <small style="margin-top: 4px; margin-left: 13px"
                                           class="fs-12 currency-symbol currency-symbols">{{ @$wallet->currency->symbol }}</small>
                                </a>
                            </td>
                            <td>
                                <div>
                                    {{ __(@$wallet->currency->sign) }}{{ showAmount(@$wallet->balance,currencyFormat:false) }}
                                    <div class="each-coin-price" id="c_{{ @$wallet->currency->symbol }}" data-id="{{ @$wallet->currency->symbol }}">

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No wallets found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @if ($wallets->hasPages())
                    {{ paginateLinks($wallets) }}
                @endif
            </div>
        @elseif(isset($stock_wallet) && $stock_wallet)
            <div class="col-lg-12">
                <div class="table-wrapper">
                    <table class="table table--responsive--lg">
                        <thead>
                        <tr>
                            <th>Total Earned</th>
                            <th>@lang('Available Balance')</th>
                            <th>@lang('Total Assets')</th>
                            <th>@lang('Total Sell')</th>
                            <th>@lang('Mutual Fund')</th>
                            <th>@lang('Live Market')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{$total_earn}} USD
                            </td>
                            <td>{{$stock_wallet->amount}} USD</td>
                            <td>{{$total_buy}} USD</td>
                            <td>{{$total_sell}} USD</td>

                            <td>{{$total_mutual}} USD</td>
                            <td>{{$total_live_market}} USD</td>

                            <td>
                                <button
                                    {{$stock_wallet->amount <= 0?'disabled="disabled"':''}} class="btn btn-success stockTransfer"
                                    type="button">Transfer
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 mt-4">
                <table class="table">
                    <h4>
                        My Stocks

                        <a style="font-size: 12px;float: right;color: #95999a; margin-top: 8px;margin-right: 20px;"
                           href="{{route('user.stock.transfer.history')}}"><i class="fa fa-history"></i> History</a>
                    </h4>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($user_stocks as $user_stock)

                        <tr>
                            <td>
                                {{$user_stock->product->name}}
                            </td>
                            <td>
                                @if($user_stock->type=='fix')
                                    MF
                                @else
                                    LM
                                @endif
                            </td>
                            <td>
                                {{$user_stock->invest_amount}}

                                @php
                                    if($user_stock->type=='fix'){
                                        $totalInterest=\App\Models\StockTransaction::where('type', 'interest')->where('stock_id', $user_stock->id)->sum('amount');
                                }else{
                                  $totalInterest=null;
                                }
                                @endphp

                                @if($user_stock->type=='fix')
                                    @if($totalInterest)
                                        <div style="font-size: 13px !important;">
                                            <small> <span style="color: #09dc09;">{{$totalInterest}} USD</span></small>
                                        </div>
                                    @endif
                                @else
                                    <div style="font-size: 13px !important;">
                                        <span class="market-price-sec"><strong class="each-live-price"
                                                                               data-stack="{{$user_stock->stack_price}}"
                                                                               data-code="{{$user_stock->product->stock_code}}">---/---</strong></span>
                                    </div>
                                @endif

                            </td>
                            <td>
                                @if($user_stock->status=='sell')
                                    <span class="badge badge--danger">Sell</span>
                                @else
                                    <span class="badge badge--success">Buy</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No wallets found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @if ($user_stocks->hasPages())
                    {{ paginateLinks($user_stocks) }}
                @endif
            </div>

            <div class="modal fade" id="stockWithdraw" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style=" background: #38383885;">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="background: #000000;color: white">
                        <form action="{{route('user.stock.wallet.transfer')}}" method="post">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Transfer</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <h4>Available Balance: <span
                                            class="stock-current-balance">{{$stock_wallet->amount}}</span> USD</h4>
                                </div>
                                <div class="form-group">
                                    <label for="">Amount</label>
                                    <input type="text" name="amount" class="form-control transfer-stock-amnt-field"
                                           placeholder="Enter transfer amount....">
                                    <small style="font-size: 11px !important;" class="text--danger">Charge $0.00</small>
                                    <small style="font-size: 11px !important;" class="text--danger">5% VAT Will
                                        Applicable </small>
                                </div>
                                <div class="form-group">
                                    <p class="mb-0" style="color: #18cb18;">
                                        <span>Charge Amount: &nbsp;</span> <span class="t_charge_amount">0.00 USD</span>
                                    </p>
                                    <p class="mb-2" style="color: #18cb18;">
                                        <span>You'll Get: &nbsp;</span> <span class="t_final_amount">0.00 USD</span>
                                    </p>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary submit-transfer-stock-balance">Confirm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        @elseif(isset($copy_wallet) && $copy_wallet)

            <div class="col-lg-12">
                <div class="table-wrapper">
                    <table class="table table--responsive--lg">
                        <thead>
                        <tr>
                            <th>@lang('Total Buy')</th>
                            <th>@lang('Total Sell')</th>
                            <th>@lang('Total Interest')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{$total_buy}} USD
                            </td>
                            <td>{{$total_sell}} USD</td>
                            <td>{{$total_interest}} USD</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 mt-4">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($interests as $interest)

                        <tr>
                            <td>
                                {{$interest->trade->name}}
                            </td>
                            <td>
                                {{$interest->amount}} USD
                            </td>
                            <td>
                                {{$interest->created_at->format('d-m-Y')}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No wallets found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @if ($interests->hasPages())
                    {{ paginateLinks($interests) }}
                @endif
            </div>

        @else
            <div class="col-lg-12">
                <div class="table-wrapper pt-0">

                    @if(isset($estimatedBalances))
                        <div class="n-view-balance">
                            <h5 class="mb-0">Total Balance</h5>
                            <div class="bal-m-sec d-none">
                                <div class="main-balance">
                                    <span
                                        class="bal-font">{{ str_replace('USD', '', showAmount($estimatedBalances)) }}</span>
                                    <span
                                        class="currency-font">USD</span> <i class="fa fa-eye hide-balance"></i>
                                </div>
                                <div class="main-balance-hide">
                                    ********* <i class="fa fa-eye-slash show-balance"></i>
                                </div>
                            </div>
                            <small class="today-pnl">Today PNL $00000(-0.00%)</small>
                        </div>
                    @endif


                        <div class="row user-deposit-history coming_soon">
                            <div class="col-md-10 col-10">
                                <i class="fas fa-dollar"></i> <span style="font-size: 13px;" class="text">Hold USDT up to 9% ARP</span>
                            </div>
                            <div class="col-md-2 col-2 text-center">
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>


                    <div class="row" style="padding-top: 20px">
                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons deposit-cash-wallet">
                                <img src="{{asset('core/public/icon/deposit.svg')}}" alt="">
                            </div>
                            <small class="w-icon-t-section">
                                Deposit
                            </small>
                        </div>
                        @php $ttype=isset($typeValue)?$typeValue:'spot'; @endphp

                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons withdraw-cash">
                                <img src="{{asset('core/public/icon/invite-friend.svg')}}" alt="">
                            </div>
                            <small class="w-icon-t-section">
                                Withdraw
                            </small>
                        </div>

                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons" data-url="{{ route('user.convert') }}">
                                <img src="{{asset('core/public/icon/fait-deposit.svg')}}" alt="">

                            </div>
                            <small class="w-icon-t-section">
                                Convert
                            </small>
                        </div>

                        @php $w_type=$typeValue==1?'spot':'funding'; @endphp

                        <div class="col-3 w-sm-icon-main-section">
                            <div class="w-sec-sm-icons"
                                 data-url="{{ route('user.wallet.view',  [ 'type' => $w_type,'currencySymbol' => 'USDT','fm'=>'transfer']) }}">
                                <img src="{{asset('core/public/icon/p2p-trading.svg')}}" alt="">
                            </div>
                            <small class="w-icon-t-section">
                                Transfer
                            </small>
                        </div>

                    </div>


                    <table class="table">
                        <input type="text" style="background: #1a1a1a;color: white !important;border-color: #4b4949;"
                               id="currencySearchs" class="form-control mb-3 mt-2" placeholder="Search by Symbol"
                               onkeyup="searchTables()">


                        <thead>
                        <tr>
                            <th>@lang('Currency')</th>
                            <th>@lang('Available Balance')</th>
                        </tr>
                        </thead>
                        <tbody id="walletTableBodys">
                        @forelse($wallets as $wallet)
                            <tr>
                                <td class="d-flex">
                                    <a class="d-flex"
                                       href="{{ route('user.wallet.view',  [ 'type' => $wallet->typeText,'currencySymbol' => @$wallet->currency->symbol]) }}">
                                        <div class="customer__thumb">
                                            <img src="{{ @$wallet->currency->image_url }}">
                                        </div>

                                        <small style="margin-top: 4px; margin-left: 13px"
                                               class="fs-12 currency-symbol currency-symbols">{{ @$wallet->currency->symbol }}</small>
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        {{ __(@$wallet->currency->sign) }}{{ showAmount(@$wallet->balance,currencyFormat:false) }}
                                        <div class="each-coin-price" id="c_{{ @$wallet->currency->symbol }}" data-id="{{ @$wallet->currency->symbol }}">

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('wallet') @endphp
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($wallets->hasPages())
                    {{ paginateLinks($wallets) }}
                @endif
            </div>
        @endif
    </div>





    <!--  Convert modal !-->
    @php
        $singleCurrencys = @$meta->single_currency ?? null;
        $walletType = @$meta->wallet_type ?? null;
    @endphp





    <div class="dashboard-right">
        <div class="right-sidebar">
            <div class="right-sidebar__header mb-3 skeleton">
                <div class="d-flex">
                    <div class="p-2">

                    </div>
                    <span  class="toggle-dashboard-right text-white close-popup-wallet">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="right-sidebar deposit-from-wallet mt-3">
            <div class="right-sidebar__header mb-3 skeleton">
                <h4 class="mb-0 fs-18">@lang('Deposit Money')</h4>
                <p class="mt-0 fs-12">@lang('Make crypto & fiat deposits in a few steps')</p>
            </div>
            <div class="right-sidebar__deposit custom-select2">
                <form class="skeletons deposit-forms" method="post" action="{{ route('user.deposit.insert') }}">
                    @csrf

                    <input type="hidden" name="currency" class="gt_currency_name">
                    <div class="form-group position-relative mb-5">
                        <label for="">Choose Deposit Currency</label>
                        <select name="gateway" id="gtCoin" class="form-control form--control mt-3">
                            <option value="">--Select Currency--</option>
                            <option value="web3">USDT (web3)</option>
                            @foreach($gateways as $gateway)
                                <option value="{{$gateway->method_code}}">{{$gateway->currency}}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="form-group position-relative" id="currency_list_wrapper">
                        <div class="input-group">
                            <input type="number" step="any" name="amount" class="form--control form-control"
                                   placeholder="@lang('Amount')">
                            <div class="input-group-text skeleton dp-currency-symbol">
                                &nbsp; USD &nbsp;
                            </div>
                        </div>
                    </div>


                    <div class="form-group mt-3">
                        <label for="">Security Pin</label>
                        <input type="text" class="form-control form--control" name="security_pin"
                               placeholder="Enter Security Pin">
                    </div>


                    <button class="btn btn--base w-100" type="submit">
                        <span class="icon-deposit"></span> @lang('Next')
                    </button>
                </form>
            </div>
        </div>



        <div class="right-sidebar withdraw-from-wallet mt-3">
            <div class="right-sidebar__header mb-3 skeleton">
                <h4 class="mb-0 fs-18">@lang('Withdraw Money')</h4>
                <p class="mt-0 fs-12">@lang('Withdrawal your balance with our world-class withdrawal process')</p>
            </div>
            <div class="right-sidebar__deposit">
                <form class="skeleton custom-select2" action="{{ route('user.withdraw.submit') }}" method="post"
                      id="withdraw-form">
                    @csrf

                    <div class="form-group position-relative" id="withdraw_currency_list_wrapper">
                        <label for="">On Chain</label>
                        <x-currency-list :action="route('user.currency.all')" id="withdraw_currency_list"
                                         parent="withdraw_currency_list_wrapper" valueType="2" logCurrency="true"/>
                    </div>

                    <div class="form-group position-relative mt-4">
                        <label for="">Withdraw Amount</label>
                        <div class="input-group">
                            <input type="number" name="amount" step="any" class="form--control form-control"
                                   placeholder="@lang('Amount')">
                            <div class="input-group-text skeleton click-with-max">
                                <span class="mx-amount-click">
                                    MAX
                                </span>
                            </div>
                        </div>

                        <div class="available-bal-sec" style="font-size: 13px;color: #969696 !important;">
                            <span >Available</span>

                            <span style="float:right;">
                                 <span class="available_for_withdraw">0.00000</span>
                            </span>
                        </div>

                    </div>


                    <div class="form-group position-relative section-ch-method">
                        <label>@lang('Network')</label>
                        <select class="form-control form--control form-select select2" name="method_code" required
                                data-minimum-results-for-search="-1">
                            @if (@$singleCurrency)
                                @foreach ($withdrawMethods as $method)
                                    <option value="{{ $method->id }}" data-form-id="{{$method}}" data-resource='@json($method)'
                                            data-image-src="{{ getImage(getFilePath('withdrawMethod') . '/' . $method->image) }}">
                                        {{ __($method->name) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>



                    <div class="form-group section-address">
                        <div style=" text-align: center;background: #47494a;border-radius: 5px;padding: 7px;">
                            Choose Network To See....
                        </div>
                    </div>

                    <div class="section-qr-code d-none">

                        <div class="form-group">
                            <label for="qr-image-input">Upload QR Code</label>
                            <input type="file" id="qr-image-input" class="form--control" accept="image/*">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="form-label">Security Pin</label>
                        <input type="text" class="form-control form--control" name="security_pin"
                               placeholder="Enter Security Pin">
                    </div>


                   <div class="form-group">
                       <label for="">Enter OTP</label>
                       <div class="input-group mb-3 sec-send-otp">
                           <input type="text" class="form-control" name="withdraw_otp" placeholder="Enter OTP">
                           <div class="input-group-append">
                            <span class="input-group-text sendOtp" id="sendOtpBtn">
                                    Send Otp
                            </span>
                           </div>
                       </div>
                   </div>





                    <input type="hidden" name="wallet_type" value="spot">




                    <div class="preview-details d-none">
                        <ul class="list-group text-center list-group-flush pb-4 withdraw-details">
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('Limit')</span>
                                <span>
                            <span class="min fw-bold">0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span> -
                            <span class="max fw-bold">0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('Charge')</span>
                                <span>
                            <span class="charge fw-bold">0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('Receivable')</span>
                                <span>
                            <span class="receivable fw-bold"> 0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                            </li>
                        </ul>
                    </div>


                    <div class="form-group extra-description mb-5 d-none">
                        <ul>
                            <li>
                                *&nbsp; credit your account with tokens from that sale
                            </li>
                            <li>
                                *&nbsp; Do not transact with Sanctioned Entities. <span style="color: #c79508;">Learn more</span>
                            </li>
                            <li>
                                *&nbsp; Be cautious of scammers Check here to see how to licate common steams. <span
                                    style="color: #c79508;">Learn more</span>
                            </li>
                        </ul>


                    </div>

                    {{--                    <button class="deposit__button btn btn--base w-100 withdraw-btn-m" type="submit">--}}
                    {{--                        <span class="icon-withdraw"></span> @lang('Withdraw')--}}
                    {{--                    </button>--}}

                    <button class="deposit__button btn btn--base w-100 mt-3" type="button" id="submitWithdraw">
                        <span class="icon-withdraw"></span> @lang('Withdraw')
                    </button>
                </form>
            </div>
        </div>
    </div>
    <x-flexible-view :view="$activeTemplate . 'user.components.canvas.deposit'" :meta="['gateways' => $gateways]"/>
    <x-flexible-view :view="$activeTemplate . 'user.components.canvas.withdraw'"
                     :meta="['withdrawMethods' => $withdrawMethods]"/>
    <canvas id="qr-canvas" style="display:none;"></canvas>
@endsection

@push('style')

    <style>
        .each-coin-price{
            font-size: 12px !important;
        }
        .each-coin-price-red{
            color: #e00728 !important;
        }
        .each-coin-price-green{
            color: #04df04 !important;
        }
        .section-qr-code #qr-image-input{
            padding: 0px !important;
        }
        .section-qr-code{
            position: relative;
            top: -20px;
        }
        .sendOtp{
            height: 100%;
            background: #0366ff;
            border: hidden !important;
            border-radius: 0px 5px 5px 0px;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
        }
        .sec-send-otp .form-control{
            border: 1px solid #212c2e !important;
            background: #3a4041 !important;
            color: white !important;
        }
        .sec-send-otp .input-group-append{
            border: 1px solid #212c2e !important;
        }

        .withdraw-from-wallet{
            padding-top: 0px !important;
            margin-top: 0px !important;
        }
        .withdraw-details .list-group-item{
            font-size: 13px !important;
            line-height: 15px !important;
        }
        .click-with-max{
            background: #3a4041 !important;
            border: 1px solid #393939 !important;
            color: white !important;
        }
        .form--control:focus{
            border-color: hsl(188.57deg 5.69% 24.12%) !important;
        }
        .right-sidebar,.dashboard-right {
            background-color: #212c2e !important;
        }
        .form--control {
            padding: 12px !important;
            background-color: #52525282 !important;
        }

        #withdraw-form .select2-container .select2-selection--single{
            padding: 5px 0px !important;
            height: 41px !important;
            background: #3a4041 !important;
            color: white;
            border-radius: 5px !important;
        }
        #withdraw-form .form-control{
            padding: 10px !important;
        }
        .dashboard-header-menu__link.active{
            border-bottom: 2px solid hsl(37.6deg 95.74% 53.92%) !important;
            color: hsl(37.6deg 95.74% 53.92%) !important;
        }
        .dashboard-header-menu__link:hover{
            color: hsl(37.6deg 95.74% 53.92%) !important;
        }
        .user-deposit-history{
            margin-top: 20px;
            margin-bottom: 15px;
            background: #1a1a1a;
            padding: 7px 5px;
            border-radius: 5px;
            cursor: pointer;
        }
        .user-deposit-history .fa-dollar{
            background: #959595;
            margin: 0 auto;
            padding: 4px 7px;
            font-size: 13px;
            margin-right: 9px;
            color: white;
            border-radius: 10px;
        }

        .available-balance {
            padding: 14px 10px !important;
            background: #1f1f1f;
            border-radius: 5px;
            color: white;
        }

        .custom-select2 .select2-container--default .select2-selection--single {
            background: #1f1f1f !important;
            color: white;
        }

        .select2-selection.select2-selection--single {
            background: #1f1f1f !important;
            color: white;
        }

        .input-group, .form-control {
            background: #3a4041;
            color: white !important;
        }

        .dashboard-right.show {
            padding-bottom: 120px;
        }


        #transfer-offcanvas .offcanvas-body {
            padding-bottom: 100px;
        }

        .extra-description ul li {
            font-size: 14px !important;
            margin-top: 8px;
        }

        .extra-description ul {
            line-height: 22px;
        }

        .mx-amount-click {
            padding: 0px 10px;
            color: #ff9900;
            cursor: pointer;
        }

        #withdraw_currency_list_wrapper .select2, .select2-hidden-accessible {
            border: 1px solid #2d2d2d !important;
        }

        .w-sm-icon-main-section .w-sec-sm-icons {
            width: 48px;
            text-align: center;
            padding: 7px 10px;
            /*border: 1px solid #a3a2a2;*/
            border-radius: 5px;
            color: white;
            background: #3b3c3eb0;
            font-size: 21px;
        }

        .w-sm-icon-main-section .w-icon-t-section {
            margin-top: 5px;
            font-size: 11px;
        }

        .w-sm-icon-main-section {
            justify-content: center;
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        .bal-font {
            font-size: 23px;
            color: #ffffff;
            margin-right: 5px;
        }

        .currency-font {
            font-weight: 600;
            color: white;
            font-size: 15px;
        }

        .today-pnl {
            font-size: 10px;
            color: #12d712;
        }

        #withdraw_currency_list_wrapper .select2, .select2-hidden-accessible {
            border: 1px solid #2d2d2d !important;
        }

        .select2-selection--single {
            height: 45px !important;
        }

        .section-ch-method .select2-container {
            border: 1px solid #2d2d2d !important;
            height: 50px !important;
        }
        .dp-currency-symbol{
            background: #47494a;
            border: 1px solid #47494a;
        }
        .transfer-stock-amnt-field:focus{
            background-color: #3a4041 !important;
        }

        @media (max-width: 750px) {
            .withdraw-from-wallet{
                padding: 25px !important;
            }
        }
    </style>

@endpush
@push('script')
    @if(isset($stock_wallet) && $stock_wallet)
        <script>



            $(document).on('click', '.convertCoin', function (e){

                var myOffcanvas = document.getElementById('convertModal');
                var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas).show();

            });



            $(document).ready(function () {
                function fetchLivePrices() {
                    $('.each-live-price').each(function () {
                        const code = $(this).attr('data-code');
                        const stack_price = parseFloat($(this).attr('data-stack'));

                        if (stack_price && stack_price > 0) {
                            $.ajax({
                                type: 'GET',
                                url: '{{route('user.stock.live.price')}}',
                                data: {
                                    code: code
                                },
                                success: function (res) {
                                    if (res.status == 'success') {
                                        if (res.amount && res.amount > 0) {
                                            if (stack_price < res.amount) {
                                                $(this).html(`<span class="text-success">${res.amount}</span>`);
                                            } else {
                                                $(this).html(`<span class="text-danger">${res.amount}</span>`);
                                            }
                                        } else {
                                            $(this).html('<span class="text-danger">---/---</span>');
                                        }
                                    }
                                }.bind(this) // bind 'this' to the current element in the success callback
                            });
                        }
                    });
                }

                // Initial fetch
                fetchLivePrices();

                // Set interval to fetch prices every 20 minutes (20 * 60 * 1000 milliseconds)
                setInterval(fetchLivePrices, 20 * 60 * 1000);
            });
        </script>

    @endif


    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.1.0/dist/jsQR.min.js"></script>


    <script>


        $(document).ready(function () {

            function loadPrices() {

                $('.each-coin-price').each(function () {

                    const coinName = $(this).attr('data-id');   // e.g. BTC, ETH, DOGE
                    const target = $('#c_' + coinName);         // element where price will display

                    // Build Binance pair (ex: BTCUSDT)
                    const symbol = coinName + "USDT";

                    // Get previous price
                    const previous = parseFloat(target.attr("data-prev")) || null;

                    // Fetch price
                    fetch(`https://api.binance.com/api/v3/ticker/price?symbol=${symbol}`)
                        .then(response => {
                            if (!response.ok) throw new Error("Invalid pair");
                            return response.json();
                        })
                        .then(data => {

                            let price = parseFloat(data.price);
                            let formattedPrice = "$" + price.toFixed(4);

                            // Update UI
                            target.text(formattedPrice);

                            // Compare with previous price
                            if (previous !== null) {
                                if (price > previous) {
                                    $(this).addClass('each-coin-price-green');
                                    // flashColor(target, "green");
                                } else if (price < previous) {
                                    // flashColor(target, "red");
                                    $(this).addClass('each-coin-price-red');
                                }
                            }

                            // Save new price
                            target.attr("data-prev", price);
                        })
                        .catch(err => {
                            console.log("Price error:", coinName);
                        });

                });

            }

            // Flash function for color
            function flashColor(element, color) {
                const originalColor = element.css("color");

                element.css("color", color);

                setTimeout(() => {
                    element.css("color", originalColor);
                }, 300);
            }

            // First load
            loadPrices();

            // Update every second (your original timing)
            setInterval(loadPrices, 10000);

        });




        $(document).ready(function() {
            const $qrImageInput = $('#qr-image-input');
            const $outputAddress = $('input[name="address"]');
            const canvas = document.getElementById('qr-canvas');
            const ctx = canvas.getContext('2d');

            $qrImageInput.on('change', function(event) {
                const file = event.target.files[0];
                if (!file) {
                    $outputAddress.val('');
                    toastr.error('No file selected.', 'error');
                    return;
                }

                // toastr.error('Scanning QR code...', 'loading');
                $outputAddress.val('');

                const reader = new FileReader();
                reader.onload = function(e) {

                    // Create an image object to draw on canvas
                    const img = new Image();
                    img.onload = function() {
                        // Ensure image dimensions are available
                        canvas.width = img.width;
                        canvas.height = img.height;

                        // Draw image onto canvas
                        ctx.drawImage(img, 0, 0, img.width, img.height);

                        try {
                            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            // Use jsQR to find and decode the QR code
                            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                                inversionAttempts: "dontInvert",
                            });

                            if (code) {
                                $('input[name=address]').val(code.data)
                                toastr.success('QR code successfully scanned! Extracted content is below.', 'success');
                            } else {
                                toastr.error('No valid QR code found in the image. Please check the image quality.', 'error');
                                $('input[name=address]').val('')
                            }
                        } catch (error) {
                            toastr.error('Error processing image for QR code: ' + error.message, 'error');
                            $('input[name=address]').val('')
                        }
                    };

                    img.onerror = function() {
                        toastr.error('Could not load image. Please ensure it is a valid image file.', 'error');
                        $('input[name=address]').val('')
                    };

                    img.src = e.target.result; // Set image source to start loading
                };
                reader.readAsDataURL(file);
            });
        });

    </script>


    <script>
        $(document).on('click', '#sendOtpBtn', function (e){

            $.ajax({
                'type':'POST',
                url:'{{route('user.send.otp')}}',
                data:{
                    '_token':'{{csrf_token()}}','type':'withdraw'
                },

                success:function(res){
                    if(res.status=='success'){
                        notify('success', res.message);
                    }else{
                        notify('error', "@lang('Something went wrong try again after sometimes')");
                    }
                }
            })

        });

    </script>


    @if(request()->get('sc') && request()->get('sc')=='tr')
        <script>
            $(document).ready(function (e) {
                $('.deposit-cash-wallet').trigger('click');
            });
        </script>
    @endif

    @if(request()->get('wh') && request()->get('wh')=='wi')
        <script>
            $(document).ready(function (e) {
                $('.withdraw-cash').trigger('click');
            });
        </script>
    @endif

    <script>
        $(document).on('click', '.close-popup-wallet', function (e){
            location.href='{{route('user.wallet.overview')}}';
        })

        $(document).on('keyup or paste', '.transfer-stock-amnt-field', function (e) {

            let amount = parseFloat($(this).val());
            let charge_amount = (amount * 5) / 100
            let final_amount = amount - charge_amount;

            if (final_amount <= 0) {
                $('.submit-transfer-stock-balance').attr('disabled', 'disabled');
            } else {
                $('.submit-transfer-stock-balance').removeAttr('disabled');
            }

            $('.t_charge_amount').text(charge_amount + ' USD');
            $('.t_final_amount').text(final_amount);


        })


        $(document).on('click', '#submitWithdraw', function (e) {

            $('#submitWithdraw').attr('disabled', 'disabled');

            let amount = $(`#withdraw-form input[name=amount]`).val();

            if (!amount) {
                notify('error', "@lang('Please enter withdraw amount')");

                $('#submitWithdraw').removeAttr('disabled');

                return false;
            }



            let otp = $(`input[name=withdraw_otp]`).val();

            if (!otp) {
                notify('error', "@lang('Please enter a valid otp')");
                $('#submitWithdraw').removeAttr('disabled');
                return false;
            }




            $('#withdraw-form').submit();

        });





        $(document).on('change', '#currency', function (e) {
            e.preventDefault();
            let currency = $(`#withdraw-form select[name=currency]`).val();
            let amount = $(`#withdraw-form input[name=amount]`).val();

            if (!currency) {
                notify('error', "@lang('Currency field is required')");
                return false;
            }


            let withdrawMethods = @json($withdrawMethods);
            let currencyWithdrawMethods = withdrawMethods.filter(ele => ele.currency == currency);


            if (currencyWithdrawMethods && currencyWithdrawMethods.length > 0) {
                let methodsOptions = "<option selected disabled> @lang('Select Method')</option>";

                $.each(currencyWithdrawMethods, function (i, currencyWithdrawMethod) {
                    methodsOptions += `<option value="${currencyWithdrawMethod.id}" data-form-id="${currencyWithdrawMethod.form_id}" data-resource='${JSON.stringify(currencyWithdrawMethod)}'>
                                    ${currencyWithdrawMethod.name}
                                </option>
                            `;
                });

                $("select[name=method_code]").html(methodsOptions);
                $('.deposit__button').removeAttr('disabled');

            } else {
                $('.deposit__button').attr('disabled', 'disabled');
            }

            $('.withdraw-cur-sym').text(currency);
            $('.mx-amount-click').trigger('click');
        });


        $(document).on('change', 'select[name=method_code]', function () {

            if (!$(this).val()) {
                $('.preview-details').addClass('d-none');
                return false;
            }


            let w_amount = $(`#withdraw-form input[name=amount]`).val();
            if (!w_amount) {
                notify('error', "@lang('Please enter withdraw amount')");
                return false;
            }


            var resource = $('select[name=method_code] option:selected').data('resource');
            var fixed_charge = parseFloat(resource.fixed_charge);
            var percent_charge = parseFloat(resource.percent_charge);

            $('.min').text(getAmount(resource.min_limit));
            $('.max').text(getAmount(resource.max_limit));

            let amount = $(`#withdraw-form input[name=amount]`).val();
            if (!amount) {
                amount = 0.00;
            }

            $('.preview-details').removeClass('d-none');

            var charge = parseFloat(fixed_charge + (amount * percent_charge / 100));
            $('.charge').text(getAmount(charge));

            var receivable = parseFloat((parseFloat(amount) - parseFloat(charge)));

            console.log(receivable)

            $('.receivable').text(getAmount(receivable));

            $('.base-currency').text(resource.currency);
            $('.method_currency').text(resource.currency);
            $('input[name=amount]').on('input');





            // Load Form
            var formId = $('select[name=method_code] option:selected').attr('data-form-id');


            if (formId) {

                // Call backend route to get updated form HTML
                $.ajax({
                    url: "{{ route('user.withdraw.form.load') }}", // Adjust route
                    type: 'GET',
                    data: { id: formId },
                    success: function (response) {
                        $('.section-address').html(response); // Update form component
                        $('.section-qr-code').removeClass('d-none')
                    },
                    error: function () {
                        alert("Failed to load form.");
                    }
                });
            }



        });


    </script>

    <script>


        $(document).on('change', '#gateway', function (e) {

            const curr = $('option:selected', this).text();

            $('.gt_currency_name').val(curr);
            $('.dp-currency-symbol').text(curr);
        });

        function searchTable() {
            const searchValue = document.getElementById('currencySearch').value.toUpperCase();
            const rows = document.querySelectorAll('#walletTableBody tr');

            rows.forEach(row => {
                const symbol = row.querySelector('.currency-symbol').innerText.toUpperCase();
                row.style.display = symbol.includes(searchValue) ? '' : 'none';
            });
        }

        function searchTables() {
            const searchValue = document.getElementById('currencySearchs').value.toUpperCase();
            const rows = document.querySelectorAll('#walletTableBodys tr');

            rows.forEach(row => {
                const symbol = row.querySelector('.currency-symbols').innerText.toUpperCase();
                row.style.display = symbol.includes(searchValue) ? '' : 'none';
            });
        }

        $(document).on('keyup or paste', '.transfer-stock-amnt-field', function (e) {
            const amount = parseFloat($(this).val());
            const current_balance = parseFloat($('.stock-current-balance').text());


            if (amount > current_balance || amount <= 0) {
                $('.submit-transfer-stock-balance').attr('disabled', 'disabled');
            } else {
                $('.submit-transfer-stock-balance').removeAttr('disabled');
            }

        });


        $(document).on('click', '.stockTransfer', function (e) {

            $('#stockWithdraw').modal('show');
        });

        $(document).on('click', '.w-sec-sm-icons', function (e) {
            const have_report='{{$report}}';

            if(have_report && !$(this).hasClass('withdraw-cash')){
                toastr.error('Your all transaction has been frozen. Wait  until your report solved');

                return;
            }
            const data_url = $(this).attr('data-url');
            if (!data_url) {
                return;
            }

            location.href = data_url;
        });

        $(document).on('click', '.withdraw-cash', function (e) {

            const have_report='{{$report}}';

            if(have_report){
                toastr.error('Your all transaction has been frozen. Wait  until your report solved');

                return;
            }



            $('.withdraw-btn-m').html(`<span class="icon-withdraw"></span> Withdraw`);


            $('.withdraw-from-wallet').addClass('d-none');
            $('.deposit-from-wallet').addClass('d-none');

            $('.dashboard-right').addClass('show');
            $('.withdraw-from-wallet').removeClass('d-none');
        });

        $(document).on('click', '.deposit-cash-wallet', function (e) {
            $('.withdraw-from-wallet').addClass('d-none');
            $('.deposit-from-wallet').addClass('d-none');


            $('.dashboard-right').addClass('show');
            $('.deposit-from-wallet').removeClass('d-none');
        });

    </script>

    <script>

        $(document).on('click', '.mx-amount-click', function (e) {

            let amount = $('.available_for_withdraw').attr('data-balance');

            if (!amount) {
                amount = 0;
            }

            const currency = $('#currency').val();
            if (!currency) {
                alert('Select Withdraw Currency');
            }

            $('input[name="amount"]').val(amount)
        });

        $(document).on('change', '#currency', function (e) {

            const currency = $(this).val();

            if (currency) {
                $.ajax({
                    type: 'GET',
                    url: '{{route('user.wallet.get.coin.balance')}}',
                    data: {
                        currency: currency
                    },

                    success: function (res) {
                        if (res.status == 'success' && res.balance) {
                            $('.available_for_withdraw').attr('data-balance', res.balance).text(res.balance + ' ' + currency)
                        }
                    }
                })
            }

        });
    </script>

@endpush
