@extends($activeTemplate . 'layouts.master')


@push('ip-css')


    <style>
        .section-trans-type .username.active{
            background: #ffffff;
            color: black !important;
        }
        .section-trans-type .uid.active{
            background: #ffffff;
            color: black !important;
        }
        .section-trans-type .username{
            padding: 2px 6px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .section-trans-type .uid{
            padding: 2px 6px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .section-trans-type{
            padding: 8px 6px;
            background: #3a4041;
            margin-bottom: 7px;
        }
    </style>
@endpush


@section('content')
    @php
        $walletBalance = showAmount($wallet->balance, currencyFormat: false);
        $general = gs();
        $transferCharge = getAmount($general->other_user_transfer_charge);
        $transferChargeForOtherWallet = getAmount($general->other_wallet_transfer_charge);
    @endphp
    <div class="row gy-3 justify-content-center mb-3">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap flex-between align-items-center">
                <h4 class="mb-0">{{ __($pageTitle) }}</h4>
                <a href="{{ route('user.wallet.list', $walletType) }}" class="btn btn--base btn--sm outline">
                    <i class="la la-undo"></i> @lang('Back')
                </a>
            </div>
        </div>
    </div>

    <div class="row gy-3 mb-3 justify-content-center">
        <div class="col-lg-12 col-xl-4">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <div class="wallet-currency text-center mb-3">
                        <img src="{{ @$wallet->currency->image_url }}">
                        <div class="">
                            <p class="mb-0 fs-16">{{ __(@$wallet->currency->name) }}</p>
                            <p class="mt-0 fs-12">{{ __(@$wallet->currency->symbol) }}</p>
                        </div>
                    </div>
                    <div class="wallet-ballance p-3 mb-3">
                        <p class="mb-0 fs-16">{{ __(@$wallet->currency->sign) }}{{ showAmount($wallet->balance, currencyFormat: false) }}
                        </p>
                        <p class="mt-0 fs-12">@lang('Available Balance')</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <div class="flex-fill wallet-ballance p-3 mt-3">
                            <p class="mb-0 fs-16">
                                {{ __(@$wallet->currency->sign) }}{{ showAmount($wallet->in_order, currencyFormat: false) }}</p>
                            <p class="mt-0 fs-12">@lang('In Order')</p>
                        </div>
                        <div class="flex-fill wallet-ballance p-3 mt-3 ">
                            <p class="mb-0 fs-16">
                                {{ __(@$wallet->currency->sign) }}{{ showAmount($wallet->total_balance, currencyFormat: false) }}</p>
                            <p class="mt-0 fs-12">@lang('Total Balance')</p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @if (checkWalletConfiguration($walletType, 'deposit'))
                            <button type="button" class="btn btn--success outline flex-fill btn--sm depositBtn">
                                <span class="icon-deposit"></span> @lang('Deposit')
                            </button>
                        @endif

                        @if (checkWalletConfiguration($walletType, 'withdraw'))
                            <button type="button" class="btn btn--danger outline flex-fill btn--sm withdrawBtn">
                                <span class="icon-withdraw"></span> @lang('Withdraw')
                            </button>
                        @endif

                        @if (checkWalletConfiguration($walletType, 'transfer_other_user') || checkWalletConfiguration($walletType, 'transfer_other_wallet'))
                            <button type="button" class="btn btn--base outline flex-fill btn--sm transferBtn">
                                <i class="las la-exchange-alt"></i> @lang('Transfer')
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row gy-4 mb-3 justify-content-center">
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card ">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base">
                        <i class="las la-spinner"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.open') }}?currency={{ $currency->symbol }}"
                           class="dashboard-card__coin-name mb-0 ">
                            @lang('Open Order')
                        </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount(@$widget['open_order']) }} </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card ">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--success">
                        <i class="las la-check-circle"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.completed') }}?currency={{ $currency->symbol }}"
                           class="dashboard-card__coin-name mb-0">
                            @lang('Completed Order')
                        </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount(@$widget['completed_order']) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card ">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--danger">
                        <i class="las la-times-circle"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.canceled') }}?currency={{ $currency->symbol }}"
                           class="dashboard-card__coin-name mb-0 ">
                            @lang('Canceled Order')
                        </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount(@$widget['canceled_order']) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base fs-50 icon-order"></span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.history') }}?search={{ @$currency->symbol }}"
                           class="dashboard-card__coin-name mb-0">
                            @lang('Total Order')
                        </a>
                        <h6 class="dashboard-card__coin-title">
                            {{ getAmount($widget['total_order']) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gy-3 mb-3 justify-content-center">
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base fs-50 icon-deposit"></span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.deposit.history') }}?search={{ @$currency->symbol }}"
                           class="dashboard-card__coin-name mb-0">
                            @lang('Total Deposit')
                        </a>
                        <h6 class="dashboard-card__coin-title">
                            {{ __(@$wallet->currency->sign) }}{{ showAmount($widget['total_deposit'], currencyFormat: false) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base fs-50 icon-withdraw">
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.withdraw.history') }}?search={{ @$currency->symbol }}"
                           class="dashboard-card__coin-name mb-0 ">
                            @lang('Total Withdraw')
                        </a>
                        <h6 class="dashboard-card__coin-title">
                            {{ __(@$wallet->currency->sign) }}{{ showAmount($widget['total_withdraw'], currencyFormat: false) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base fs-50 icon-transaction"></span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.transactions') }}?symbol={{ @$currency->symbol }}&wallet_type={{ $walletType }}"
                           class="dashboard-card__coin-name mb-0">
                            @lang('Total Transaction')
                        </a>
                        <h6 class="dashboard-card__coin-title">
                            {{ getAmount($widget['total_transaction']) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card ">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base">
                        <span class="icon-trade fs-50"></span>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.trade.history') }}"
                           class="dashboard-card__coin-name mb-0">@lang('Total Trade') </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount(@$widget['total_trade']) }} </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-3 mb-3 justify-content-center">
        <div class="col-lg-12 col-xl-8">
            <div class="card custom--card border-0">
                <div class="card-body p-0">
                    <h4 class="card-title">@lang('Transaction History')</h4>
                    <table class="table table--responsive--lg">
                        <thead>
                        <tr>
                            <th>@lang('Transacted')</th>
                            <th>@lang('Trx')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Detail')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td>
                                    <div>
                                        {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $trx->trx }}</strong>
                                </td>
                                <td class="budget">
                                        <span
                                            class="fw-bold @if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                            {{ $trx->trx_type }} {{ showAmount($trx->amount, currencyFormat: false) }}
                                            {{ __($trx->wallet->currency->symbol) }}
                                        </span>
                                </td>
                                <td class="budget"> {{ showAmount($trx->post_balance, currencyFormat: false) }}
                                    {{ __($trx->wallet->currency->symbol) }}
                                </td>
                                <td>{{ __($trx->details) }}</td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('transaction') @endphp
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($transactions->hasPages())
                {{ paginateLinks($transactions) }}
            @endif
        </div>
    </div>

    @if (checkWalletConfiguration($walletType, 'deposit'))
        <x-flexible-view :view="$activeTemplate . 'user.components.canvas.deposit'"
                         :meta="['gateways' => $gateways, 'single_currency' => $currency, 'wallet_type' => $walletType]"/>
    @endif

    @if (checkWalletConfiguration($walletType, 'withdraw'))
        <x-flexible-view :view="$activeTemplate . 'user.components.canvas.withdraw'" :meta="[
            'withdrawMethods' => $withdrawMethods,
            'single_currency' => $currency,
            'wallet_type' => $walletType,
        ]"/>
    @endif

    @if (checkWalletConfiguration($walletType, 'transfer_other_user') || checkWalletConfiguration($walletType, 'transfer_other_wallet'))
        <div class="offcanvas offcanvas-end" role="dialog" aria-hidden="true" id="transfer-offcanvas"
             aria-labelledby="offcanvasLabel">
            <div class="offcanvas-header">
                <h4 class="mb-0 fs-18 offcanvas-title">
                    @lang("Transfer $currency->symbol")
                </h4>
                {{--                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">--}}
                {{--                    <i class="fa fa-times-circle"></i>--}}
                {{--                </button>--}}
            </div>
            <div class="offcanvas-body">
                                <ul class="nav nav-pills custom--tab" id="pills-tab" role="tablist">
                                    @if (checkWalletConfiguration($walletType, 'transfer_other_user'))
                                        <li class="nav-item transfer-type" data-transfer-type="user">
                                            <button class="nav-link active" type="button">@lang('Other Users')</button>
                                        </li>
                                    @endif

                                    @if (checkWalletConfiguration($walletType, 'transfer_other_wallet'))
                                        <li class="nav-item transfer-type" data-transfer-type="wallet">
                                            <button class="nav-link" type="button">@lang('Other Wallet')</button>
                                        </li>
                                    @endif
                                </ul>
                @if (checkWalletConfiguration($walletType, 'transfer_other_user'))
                    <form action="{{ route('user.wallet.transfer') }}" id="transferFormN" method="post"
                          class="other-user-transfer transfer-wrapper ">
                        @csrf
                        <input type="hidden" name="currency" value="{{ $currency->id }}">
                        <input type="hidden" name="wallet_type" value="{{ $walletType }}">
                        <p class="border--base p-3 mb-3 rounded border d-none">
                            @lang("Fund transfer of $currency->symbol within the $general->site_name platform, allowing for the allocation of a maximum of $walletBalance $currency->symbol to another user, while bearing in mind a nominal $transferCharge% transaction fee.")
                        </p>



{{--                        <div class="form-group">--}}
{{--                            <label class="form-label">Send Mode</label>--}}
{{--                            <select name="" disabled class="form--control">--}}
{{--                                <option value="">Username/Email</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}



                        <div class="form-group mt-4">

                            <div class="section-trans-type">
                                <span class="username ch-trans-type active" data-type="username">
                                    @lang('Username/Email')
                                </span>

                                <span class="uid ch-trans-type" data-type="uid">
                                    UID
                                </span>
                            </div>



                            <input type="text" name="username" placeholder="Enter username/email"
                                   class="form-control form--control transfer-username">

                            <input type="text" name="uid" placeholder="Enter UID"
                                   class="form-control d-none form--control transfer-username" data-type="uid">

                            <div class="mt-2" id="section-transfer-user" style="color: #0dc30d;">
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label class="form-label">Transfer Amount</label>
                            <div class="input-group">
                                <span class="input-group-text transfer-sign">
                                    {{ isset($currency) && $currency->sign?$currency->sign:'USDT' }}
                                </span>
                                <input type="number" step="any" class="form-control form--control"
                                       name="transfer_amount" required/>
                                <span class="input-group-text max cursor-pointer other-user-transfer-max"
                                      data-max="{{ getAmount($wallet->balance) }}">@lang('MAX')</span>
                            </div>

                            <div class="available-bal-sec" style="font-size: 13px;color: #969696 !important;">
                                <span >Available</span>

                                <span style="float:right;">
                                    {{ getAmount($wallet->balance) }} {{ isset($currency) && $currency->sign?$currency->sign:'USDT' }}
                                </span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="form-label">Security Pin</label>
                            <input type="text" class="form-control form--control" name="security_pin"
                                   placeholder="Enter Security Pin">
                        </div>


                        <!--<div class="form-group">-->
                        <!--    <label for="">Enter OTP</label>-->
                        <!--    <div class="input-group mb-3 sec-send-otp">-->
                        <!--        <input type="text" class="form-control" name="transfer_otp" placeholder="Enter OTP">-->
                        <!--        <div class="input-group-append">-->
                        <!--    <span class="input-group-text sendOtp" id="sendOtpBtn">-->
                        <!--            Send Otp-->
                        <!--    </span>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->


                        <div class="form-group transfer-details d-none">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex flex-wrap justify-content-between">
                                    <span>@lang('Amount')</span>
                                    <span>
                                        <span class="transfer-amount"></span>
                                        <span>{{ $currency->symbol }}</span>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex flex-wrap justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span>
                                        <span class="transfer-charge"></span>
                                        <span>{{ $currency->symbol }}</span>
                                        <span class="fs-12">({{ $transferCharge }}%)</span>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex flex-wrap justify-content-between">
                                    <span>@lang('Amount with charge')</span>
                                    <span>
                                        <span class="transfer-total-amount"></span>
                                        <span>{{ $currency->symbol }}</span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <button class="btn btn--base w-100 transfer-amount-submit"
                                type="button"> @lang('Send') </button>
                    </form>
                @endif

                @if (checkWalletConfiguration($walletType, 'transfer_other_wallet'))
                    <form action="{{ route('user.wallet.transfer.to.other.wallet') }}" method="post"
                          class="@if (checkWalletConfiguration($walletType, 'transfer_other_user')) d-none @endif other-wallet-transfer transfer-wrapper">
                        @csrf
                        <input type="hidden" name="currency" value="{{ $currency->id }}">
                        <input type="hidden" name="from_wallet" value="{{ $walletType }}">

                        <p class="border--base p-3 mb-3 rounded border d-none">
                            @lang("Fund transfer of $currency->symbol within the $general->site_name platform, allowing for the allocation of a maximum of $walletBalance $currency->symbol to other wallet.")
                        </p>
                        <div class="form-group">
                            <label class="form--label">@lang('Amount')</label>
                            <div class="input-group">
                                <span class="input-group-text other-wallet">
                                    {{ isset($currency) && $currency->sign?$currency->sign:'USDT' }}
                                </span>
                                <input type="number" step="any" class="form-control form--control"
                                       name="transfer_amount" required/>
                                <span class="input-group-text max cursor-pointer other-user-transfer-max"
                                      data-max="{{ getAmount($wallet->balance) }}">@lang('MAX')</span>
                            </div>
                        </div>
                        <div class="form-group position-relative">
                            <label class="form--label">@lang('To Wallet')</label>
                            <select class="form--control form-select select2" name="to_wallet" required
                                    data-minimum-results-for-search="-1"
                                    data-width="100%">
                                <option selected disabled>@lang('Select One')</option>
                                @foreach (gs('wallet_types') as $wallet)
                                    @if ($wallet->name != $walletType)
                                        <option value="{{ $wallet->name }}">{{ __($wallet->title) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn--base w-100" type="submit"> @lang('Submit') </button>
                    </form>
                @endif
            </div>
        </div>
    @endif
@endsection

@push('script-lib')


@endpush
@push('style-lib')

    <style>

        #transferFormN{
            padding-bottom: 70px !important;
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

    </style>
@endpush

@push('script')
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>--}}

    <script>
        $(document).on('click', '.transfer-amount-submit', function (e){
            $('.transfer-amount-submit').attr('disabled', 'disabled');


            // let otp = $(`input[name=transfer_otp]`).val();

            // if (!otp) {
            //     notify('error', "@lang('Please enter a valid otp')");
            //     $('.transfer-amount-submit').removeAttr('disabled');
            //     return false;
            // }



            $('#transferFormN').submit();

        });


        $(document).on('click', '#sendOtpBtn', function (e){

            $.ajax({
                'type':'POST',
                url:'{{route('user.send.otp')}}',
                data:{
                    '_token':'{{csrf_token()}}','type':'transfer'
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


    @if(request()->get('fm') && request()->get('fm')=='transfer')

        <script>

            $(document).ready(function (e) {

                $('.transferBtn').trigger('click');

                $('.other-user-transfer-max').trigger('click');

            });
            // $('.trans-user-select').select2({
            //     multiple:false
            // })


        </script>

    @endif

    <script>

        $(document).on('click', '.ch-trans-type', function (e){
            e.preventDefault();

            const type=$(this).attr('data-type');

            $('.ch-trans-type').removeClass('active');

            $(this).addClass('active');

            if(type=='uid'){
                $('input[name=uid]').removeClass('d-none');
                $('input[name=username]').addClass('d-none');
            }else{
                $('input[name=uid]').addClass('d-none');
                $('input[name=username]').removeClass('d-none');
            }



        })


    </script>

    <script>
        "use strict";
        (function ($) {

            $('.depositBtn').on('click', function (e) {
                canvasShow("deposit-canvas");
            });

            $('.withdrawBtn').on('click', function (e) {
                canvasShow("withdraw-offcanvas");
            });

            $('.transferBtn').on('click', function (e) {
                canvasShow("transfer-offcanvas");
            });

            function canvasShow(id) {
                let myOffcanvas = document.getElementById(id);
                let offcanvasInstance = new bootstrap.Offcanvas(myOffcanvas);

                // Show the Offcanvas
                offcanvasInstance.show();

                // Initialize select2 after the Offcanvas is fully shown
                myOffcanvas.addEventListener('shown.bs.offcanvas', function () {
                    $('.trans-user-select').select2({
                        placeholder: "--Find--",
                        theme: 'bootstrap-5',
                        multiple: false,
                        dropdownParent: $(myOffcanvas), // Ensures proper rendering inside Offcanvas
                    });

                    // Optional: Trigger a change event if needed
                    $('.trans-user-select').trigger('change');
                });
            }


            $(".other-user-transfer input[name=transfer_amount]").on('input change', function () {

                const amount = parseFloat($(this).val());

                if (!amount || amount <= 0) {
                    $(".other-user-transfer").find('.transfer-details').addClass('d-none');
                    return;
                }

                const chargePercent = parseFloat("{{ $transferCharge }}");
                const chargeAmount = (amount / 100) * chargePercent;
                const totalAmount = amount + chargeAmount;

                $(".other-user-transfer").find('.transfer-amount').text(getAmount(amount));
                $(".other-user-transfer").find('.transfer-charge').text(getAmount(chargeAmount));
                $(".other-user-transfer").find('.transfer-total-amount').text(getAmount(totalAmount));
                $(".other-user-transfer").find('.transfer-details').removeClass('d-none');
            });

            $('.transfer-type').on('click', function (e) {
                let transferType = $(this).data('transfer-type');
                $('.transfer-type').find(`button`).removeClass('active');
                $(this).find(`button`).addClass('active');
                $(`.transfer-wrapper`).addClass('d-none');
                $(`.other-${transferType}-transfer`).removeClass('d-none');
            });

            $('.max').on('click', function (e) {
                const max = $(this).data('max');
                $(this).closest('div').find(`input`).val(max);
                if ($(this).hasClass('other-user-transfer-max')) {
                    $(".other-user-transfer input[name=transfer_amount]").trigger('change');
                }
            });


        })(jQuery);


        let debounceTimer;

        $(document).on('keyup paste', '.transfer-username', function (e) {
            clearTimeout(debounceTimer); // Clear the previous timer

            const username = $(this).val();
            const uid = $('input[name=uid]').val();
            const type = $(this).attr('data-type');

            $('.transfer-amount-submit').attr('disabled', 'disabled');
            $('#section-transfer-user').html('');

            // Set a new timer
            debounceTimer = setTimeout(function () {
                $.ajax({
                    type: 'GET',
                    url: '{{route('user.wallet.find.transfer.user')}}',
                    data: {
                        username: username,
                        uid: uid,
                        type: type
                    },
                    success: function (res) {
                        if (res.status === 'success') {
                            const name = `<p class="mb-0">Name: ${res.fullname}</p>`;
                            const email = `<p class="mb-0">Email: ${res.email}</p>`;
                            $('#section-transfer-user').html(name + email);
                        }else{
                            toastr.error(res.message, 'Error!')
                        }

                        $('.transfer-amount-submit').removeAttr('disabled');
                    }
                });
            }, 500); // Delay time in milliseconds (e.g., 500ms)
        });


    </script>
@endpush

@push('style')
    <style>
        .other-wallet {
            background: #3a3f40;
            border: 1px solid #3a3f40;
        }

        .form--control:focus{
            border-color: hsl(189.23deg 18.98% 49.1%) !important;
        }
        .offcanvas {
            background-color: #212c2e !important;
        }
        .transfer-details .list-group-item{
            font-size: 13px !important;
            line-height: 15px !important;
        }
        .transfer-sign,.other-user-transfer-max{
            border: hidden !important;
            background: #3a4041 !important;
        }
        .form--control {
            padding: 12px !important;
            background-color: #52525282 !important;
        }

        #transfer-offcanvas .select2-dropdown--below input {
            background: black !important;
            border: 1px solid #5b5151 !important;
            color: white !important;
        }

        #transfer-offcanvas .select2-dropdown--below {
            background: black !important;
            border: 1px solid #000000 !important;
        }

        .wallet-currency img {
            width: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .wallet-ballance {
            background-color: #09171a;
        }

        .offcanvas {
            padding: 30px;
        }

        .custom--tab {
            justify-content: flex-start;
            border-radius: 0;
            border-bottom: 2px solid hsl(var(--white) / 0.1);
            border-radius: 4px;
            padding: 10px;
            padding-bottom: 12px;
            margin-bottom: 0px !important;
            margin-bottom: 24px !important;
            background-color: #3a3f40;
        }
        .custom--tab .nav-item .nav-link.active:hover{
            color: white !important;
        }

        .custom--tab .nav-item {
            padding: 0;
            width: 50%;
            display: flex;
            justify-content: center;
            cursor: pointer;
        }

        .custom--tab .nav-item .nav-link {
            background-color: transparent !important;
            border-radius: 0;
            border: 0 !important;
            padding: 0 50px !important;
            position: relative;
            font-size: 1rem;
            font-weight: 600;
        }

        .custom--tab .nav-item .nav-link.active::before {
            position: absolute;
            content: "";
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 2px;
            background-color: hsl(var(--base));
            display: none;
            font-weight: normal;
        }

        .custom--tab .nav-item .nav-link::after {
            position: absolute;
            content: "";
            bottom: -13px;
            left: 0;
            width: 0;
            height: 3px;
            background-color: hsl(var(--base)) !important;
        }

        .custom--tab .nav-item .nav-link.active {
            color: hsl(var(--base)) !important;
            background-color: transparent !important;
        }

        .custom--tab .nav-item .nav-link.active.nav-link::after {
            width: 100%;
        }

        .custom--tab .nav-item .nav-link.active:hover {
            color: hsl(var(--base)) !important;
        }

        @media screen and (max-width: 991px) {
            .offcanvas {
                padding: 20px;
            }

            .custom--tab .nav-item .nav-link {
                padding: 0 20px !important;
            }
        }

        @media screen and (max-width: 991px) {
            .offcanvas {
                padding: 15px;
            }

            .custom--tab .nav-item .nav-link {
                padding: 0 10px !important;
                font-size: 15px;
            }
        }

        .select2-image {
            max-width: 50px;
        }
    </style>
@endpush
