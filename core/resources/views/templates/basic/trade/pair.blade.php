@php
    $meta = (object) $meta;
    $pair = @$meta->pair;
@endphp
<div class=" @if (@$meta->screen == 'small') col-sm-12  d-xl-none d-block @else d-xl-block d-none @endif ">
    <div class="trading-header skeleton selected-pair">
        <h4 class="trading-header__title"> {{ str_replace('_', '/', $pair->symbol) }} </h4>
        <div>
            <span class="text--base fs-12">@lang('Price')</span>
            <p class="trading-header-number">
                <span
                    class="market-price-{{ @$pair->marketData->id }} {{ @$pair->marketData->html_classes->price_change }}">
                    {{ showAmount(@$pair->marketData->price,currencyFormat:false) }}
                </span>
            </p>
        </div>
        <div>
            <span class="text--base fs-12">@lang('Last Price')</span>
            <p class="trading-header-number market-last-price-{{ @$pair->marketData->id }} ">
                {{ showAmount(@$pair->marketData->last_price,currencyFormat:false) }}</p>
        </div>
        <div>
            <span class="text--base fs-12"> @lang('1H Change') </span>
            <p class="trading-header__number ">
                <span
                    class="market-percent-change-1h-{{ @$pair->marketData->id }} {{ @$pair->marketData->html_classes->percent_change_1h }}">
                    {{ getAmount(@$pair->marketData->percent_change_1h, 2) }}%
                </span>
            </p>
        </div>
        <div>
            <span class="text--base fs-12"> @lang('24H Change') </span>
            <p class="trading-header__number {{ @$pair->marketData->html_classes->percent_change_24h }}">
                {{ getAmount(@$pair->marketData->percent_change_24h, 2) }}%
            </p>
        </div>
        <div>
            <span class="text--base fs-12">@lang('Marketcap')</span>
            <p class="trading-header__number"> {{ showAmount(@$pair->marketData->market_cap,currencyFormat:false) }} </p>
        </div>
    </div>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {
            setTimeout(() => {
                $('.selected-pair').removeClass('skeleton');
            }, 1500);
        })(jQuery);


        $(document).ready(function() {
    const coin_name = $('.trading-header__title').text().trim().split(' ')[0];
    const formatted_coin_name = coin_name.replace('/', '').toLowerCase();

    // WebSocket connection to Binance order book data stream for the selected coin
    const wsss = new WebSocket(`wss://stream.binance.com:9443/ws/${formatted_coin_name}@ticker`);

    // Handle incoming WebSocket messages
    wsss.onmessage = function(event) {
        const data = JSON.parse(event.data);

        // Update the real-time data on the page
        updateMarketData(data);
    };

    // Function to update market data in real-time
    function updateMarketData(data) {
        // Update the price
        $('.market-price-' + data.s.toLowerCase()).text(showAmount(data.c, false));  // 'c' is current price

        // Update the last price
        $('.market-last-price-' + data.s.toLowerCase()).text(showAmount(data.x, false));  // 'x' is last price

        // Update the 1H change
        $('.market-percent-change-1h-' + data.s.toLowerCase()).text(getAmount(data.p, 2) + '%');  // Assuming 'p' is 1H change

        // Update the 24H change
        $('.market-percent-change-24h-' + data.s.toLowerCase()).text(getAmount(data.P, 2) + '%');  // Assuming 'P' is 24H change

        // Update the market cap (assuming you have a market cap field in WebSocket data)
        $('.marketcap-' + data.s.toLowerCase()).text(showAmount(data.m, false));  // 'm' for market cap (hypothetical)
    }

    // Helper function to format amounts
    function showAmount(value, currencyFormat) {
        return parseFloat(value).toFixed(2);  // Format number to 2 decimal places
    }

    // Helper function to get amounts (with a decimal precision)
    function getAmount(value, precision) {
        return parseFloat(value).toFixed(precision);
    }
});

    </script>
@endpush
