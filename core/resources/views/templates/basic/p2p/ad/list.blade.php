@forelse ($ads as $ad)
    <tr class="universal-class">
        <td>
            <div class="row" style="border-bottom: 1px solid #e1d7d7; background: #0e0e0e; padding: 12px 3px 10px 3px;">
                <div class="col-md-4 col-12">
                    <div class="row">
                        <div class="col-md-8 col-8">
                            <div class="section-seller d-flex">
                                <h6 class="customer__name">
                                    <a class="fw-normal" target="_blank" href="{{ route('p2p.advertiser', encrypt($ad->user->id)) }}">
                                        {{ __(@$ad->user->fullname) }}
                                    </a>
                                    @if ($ad->user->kv)
                                        <span class="verified-profile">
                                            <img data-bs-toggle="tooltip" title="@lang('KYC Verified')" src="{{ asset('assets/images/extra_images/kyc.png') }}">
                                        </span>
                                    @endif
                                </h6>
                            </div>

                            <div class="section-trade-com">
                                <span>Trade(s) {{ __($ad->total_trade) }}</span>
                                <span>|</span>
                                @if ($ad->total_trade > 0 && $ad->trades_count > 0)
                                    @php $successRate=( @$ad->trades_count / $ad->total_trade) *100;  @endphp
                                    <span>{{ $successRate?number_format($successRate, 2):'0.00' }}@lang('%')</span>
                                @else
                                    @lang('0%')
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 col-4">

                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="row">
                        <div class="col-md-9 col-8">
                            <div class="section-p-price">
{{--                                <h6 class="p-price mb-0">--}}
{{--                                    Price--}}
{{--                                </h6>--}}
                                <h6 class="mb-0">
                                    <span class="currency">{{ __($ad->fiat->symbol) }}</span>
                                    <span class="amount">{{ showAmount($ad->price,currencyFormat:false) }}</span>
                                </h6>
                            </div>
                            <div class="section-quantity">
                                <div class="price-limit skeleton">
                                    <p class="fs-10">
                                        <span class="limit">Limit:</span> {{ showAmount($ad->minimum_amount,currencyFormat:false) }} - {{ showAmount($ad->maximum_amount,currencyFormat:false) }}
                                        {{ __($ad->fiat->symbol) }}
                                    </p>
                                    <p class="fs-10">
                                        <span class="quantity">Available:</span>   {{ showAmount($ad->balance,currencyFormat:false) }}
                                        <span>{{ __(@$ad->asset->symbol) }}</span>
                                    </p>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-4 p-lg-0">
                            <div class="section-bu-option">

                                <ul class="payment-list fs-14 skeleton d-flex">
                                    @foreach ($ad->paymentMethods as $paymentMethod)
                                        <li class="payment-list__item danger">
                                            <span class="color"
                                                        style="background-color: #{{ @$paymentMethod->paymentMethod->branding_color?$paymentMethod->paymentMethod->branding_color:'72d914' }}"></span>
                                            {{ __(@$paymentMethod->paymentMethod->name) }},&nbsp;
                                        </li>
                                    @endforeach
                                </ul>



                                <div class="timer-sec">
                                    <i class="far fa-clock" style="margin-right:3px;"></i>
                                    {{ __(@$ad->paymentWindow->minute) }} @lang('Min')
                                </div>
                                @if ($type == 'buy')
                                    <button type="button" class="btn btn--success btn--sm trade-request" data-type="buy"
                                            data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}"
                                            data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">
                                        @lang('Buy')
                                    </button>
                                @else
                                    <button type="button" class="btn btn--danger btn--sm trade-request" data-type="sell"
                                            data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}"
                                            data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">
                                        @lang('Sell')
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12 text-left">

                </div>
            </div>
        </td>
    </tr>
@empty
    @php echo userTableEmptyMessage('ad') @endphp
@endforelse


{{--@forelse ($ads as $ad)--}}

{{--    <div class="row" style="border-bottom: 1px solid #e1d7d7; background: #0e0e0e; padding: 12px 3px 10px 3px;">--}}
{{--        <div class="col-md-4 col-12">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-8 col-8">--}}
{{--                    <div class="section-seller d-flex">--}}
{{--                        <h6 class="customer__name">--}}
{{--                            <a class="fw-normal" target="_blank" href="{{ route('p2p.advertiser', encrypt($ad->user->id)) }}">--}}
{{--                                {{ __(@$ad->user->fullname) }}--}}
{{--                            </a>--}}
{{--                            @if ($ad->user->kv)--}}
{{--                                <span class="verified-profile">--}}
{{--                                <img data-bs-toggle="tooltip" title="@lang('KYC Verified')" src="{{ asset('assets/images/extra_images/kyc.png') }}">--}}
{{--                            </span>--}}
{{--                            @endif--}}
{{--                        </h6>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-md-4 col-4">--}}
{{--                    <div class="section-trade-com" style="font-size: 12px;">--}}
{{--                        <span>Trade(s) {{ __($ad->total_trade) }}</span>--}}
{{--                        <span>|</span>--}}
{{--                        @if ($ad->total_trade > 0 && $ad->trades_count > 0)--}}
{{--                            <span>{{ ( @$ad->trades_count / $ad->total_trade) *100 }}@lang('%')</span>--}}
{{--                        @else--}}
{{--                            @lang('0%')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-4 col-12">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-9 col-8">--}}
{{--                    <div class="section-p-price">--}}
{{--                        <h6 class="p-price mb-0">--}}
{{--                            Price--}}
{{--                        </h6>--}}
{{--                        <h6 class="mb-0">--}}
{{--                            <span class="currency">{{ __($ad->fiat->symbol) }}</span>--}}
{{--                            <span class="amount">{{ showAmount($ad->price,currencyFormat:false) }}</span>--}}
{{--                        </h6>--}}
{{--                    </div>--}}
{{--                    <div class="section-quantity">--}}
{{--                        <div class="price-limit skeleton">--}}
{{--                            <p class="fs-10">--}}
{{--                             <span class="quantity">Quantity:</span>   {{ showAmount($ad->balance,currencyFormat:false) }}--}}
{{--                                <span>{{ __(@$ad->asset->symbol) }}</span>--}}
{{--                            </p>--}}
{{--                            <p class="fs-10">--}}
{{--                               <span class="limit">Limit:</span> {{ showAmount($ad->minimum_amount,currencyFormat:false) }} - {{ showAmount($ad->maximum_amount,currencyFormat:false) }}--}}
{{--                                {{ __($ad->fiat->symbol) }}--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-md-3 col-4 p-lg-0">--}}
{{--                    <div class="section-bu-option">--}}
{{--                        <div class="timer-sec">--}}
{{--                            <i class="fa fa-times-circle" style="margin-right:3px;"></i>--}}
{{--                            {{ __(@$ad->paymentWindow->minute) }} @lang('Minute')--}}
{{--                        </div>--}}
{{--                        @if ($type == 'buy')--}}
{{--                            <button type="button" class="btn btn--success btn--sm trade-request" data-type="buy"--}}
{{--                                    data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}"--}}
{{--                                    data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">--}}
{{--                                @lang('Buy')--}}
{{--                            </button>--}}
{{--                        @else--}}
{{--                            <button type="button" class="btn btn--danger btn--sm trade-request" data-type="sell"--}}
{{--                                    data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}"--}}
{{--                                    data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">--}}
{{--                                @lang('Sell')--}}
{{--                            </button>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-4 col-12 text-left">--}}
{{--            <ul class="payment-list fs-14 skeleton d-flex">--}}
{{--                @foreach ($ad->paymentMethods as $paymentMethod)--}}
{{--                    <li class="payment-list__item danger">--}}
{{--                        <span class="color"--}}
{{--                              style="background-color: #{{ @$paymentMethod->paymentMethod->branding_color?$paymentMethod->paymentMethod->branding_color:'72d914' }}"></span>--}}
{{--                        {{ __(@$paymentMethod->paymentMethod->name) }},&nbsp;--}}
{{--                    </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <tr>--}}
{{--        <td>--}}
{{--            <div class="advertiser d-flex gap-2 align-items-center skeleton">--}}
{{--                <div class="advertiser__thumb">--}}
{{--                    @if (@$ad->user->image)--}}
{{--                        <img class="fit-image" src="{{ getImage(getFilePath('userProfile') . '/' . @$ad->user->image, getFileSize('userProfile'), true) }}">--}}
{{--                    @else--}}
{{--                        <span class="user-short-name">{{ firstTwoCharacter(@$ad->user->fullname) }}</span>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--                <div class="advertiser__content">--}}
{{--                    <h6 class="customer__name">--}}
{{--                        <a class="fw-normal" target="_blank" href="{{ route('p2p.advertiser', encrypt($ad->user->id)) }}">--}}
{{--                            {{ __(@$ad->user->fullname) }}--}}
{{--                        </a>--}}
{{--                        @if ($ad->user->kv)--}}
{{--                            <span class="verified-profile">--}}
{{--                                <img data-bs-toggle="tooltip" title="@lang('KYC Verified')" src="{{ asset('assets/images/extra_images/kyc.png') }}">--}}
{{--                            </span>--}}
{{--                        @endif--}}
{{--                    </h6>--}}
{{--                    <div class="advertiser__content-order-details d-flex align-items-center gap-1">--}}
{{--                        <span>{{ __($ad->total_trade) }}</span>--}}
{{--                        <span>|</span>--}}
{{--                        @if ($ad->total_trade > 0 && $ad->trades_count > 0)--}}
{{--                            <span>{{ ( @$ad->trades_count / $ad->total_trade) *100 }}@lang('% Completed')</span>--}}
{{--                        @else--}}
{{--                            @lang('0% Completed')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <div class="price fs-14 skeleton text--base">--}}
{{--                <span class="amount">{{ showAmount($ad->price,currencyFormat:false) }}</span>--}}
{{--                <span class="currency">{{ __($ad->fiat->symbol) }}</span>--}}
{{--            </div>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <div class="price-limit skeleton">--}}
{{--                <p class="fs-14">--}}
{{--                    {{ showAmount($ad->balance,currencyFormat:false) }}--}}
{{--                    <span>{{ __(@$ad->asset->symbol) }}</span>--}}
{{--                </p>--}}
{{--                <p class="fs-14">--}}
{{--                    {{ showAmount($ad->minimum_amount,currencyFormat:false) }} - {{ showAmount($ad->maximum_amount,currencyFormat:false) }}--}}
{{--                    {{ __($ad->fiat->symbol) }}--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <span class="skeleton">--}}
{{--                {{ __(@$ad->paymentWindow->minute) }} @lang('Minute')--}}
{{--            </span>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <ul class="payment-list fs-14 skeleton">--}}
{{--                @foreach ($ad->paymentMethods as $paymentMethod)--}}
{{--                    <li class="payment-list__item danger">--}}
{{--                        <span class="color"--}}
{{--                              style="background-color: #{{ @$paymentMethod->paymentMethod->branding_color }}"></span>--}}
{{--                        {{ __(@$paymentMethod->paymentMethod->name) }}--}}
{{--                    </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            @if ($type == 'buy')--}}
{{--                <button type="button" class="btn btn--success btn--sm trade-request" data-type="buy"--}}
{{--                        data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}"--}}
{{--                        data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">--}}
{{--                    @lang('Buy') {{ __(@$ad->asset->symbol) }}--}}
{{--                </button>--}}
{{--            @else--}}
{{--                <button type="button" class="btn btn--danger btn--sm trade-request" data-type="sell"--}}
{{--                        data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}"--}}
{{--                        data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">--}}
{{--                    @lang('Sell') {{ __(@$ad->asset->symbol) }}--}}
{{--                </button>--}}
{{--            @endif--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--@empty--}}
{{--    @php echo userTableEmptyMessage('ad') @endphp--}}
{{--@endforelse--}}

