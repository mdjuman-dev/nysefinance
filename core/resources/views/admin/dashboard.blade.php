@extends('admin.layouts.app')
@section('panel')
    @php
        $cur = gs('cur_sym');
        $money = fn($v) => $cur . number_format((float) $v, 2);
        $short = function ($v) use ($cur) {
            $v = (float) $v;
            foreach ([1e9 => 'B', 1e6 => 'M', 1e3 => 'K'] as $n => $s) {
                if (abs($v) >= $n) return $cur . number_format($v / $n, 2) . $s;
            }
            return $cur . number_format($v, 2);
        };
        $admin = auth('admin')->user();
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

        $attention = [
            ['Pending deposits', $widget['pending_deposit'], 'las la-wallet', route('admin.deposit.pending'), 'green'],
            ['Pending withdrawals', $widget['pending_withdraw'], 'las la-hand-holding-usd', route('admin.withdraw.data.pending'), 'orange'],
            ['Pending KYC', $widget['pending_kyc'], 'las la-id-card', route('admin.users.kyc.pending'), 'blue'],
            ['Stock sell requests', $widget['pending_sell_request'], 'las la-chart-line', route('admin.stock.sell.request'), 'purple'],
            ['Running P2P trades', $widget['p2p']['running_trade'], 'las la-people-carry', route('admin.p2p.trade.index', 'running'), 'teal'],
        ];

        $groups = [
            [
                'title' => 'Users', 'icon' => 'las la-users', 'tone' => 'blue', 'link' => route('admin.users.all'),
                'items' => [
                    ['Total users', number_format($widget['total_users']), route('admin.users.all')],
                    ['Active users', number_format($widget['verified_users']), route('admin.users.active')],
                    ['Email unverified', number_format($widget['email_unverified_users']), route('admin.users.email.unverified')],
                    ['Mobile unverified', number_format($widget['mobile_unverified_users']), route('admin.users.mobile.unverified')],
                ],
            ],
            [
                'title' => 'Spot trading', 'icon' => 'las la-exchange-alt', 'tone' => 'green', 'link' => route('admin.order.history'),
                'items' => [
                    ['Total trades', number_format($widget['total_trade']), route('admin.trade.history')],
                    ['Open orders', number_format($widget['order_count']['open']), route('admin.order.open')],
                    ['Completed orders', number_format($widget['order_count']['completed']), route('admin.order.history') . '?status=' . Status::ORDER_COMPLETED],
                    ['Canceled orders', number_format($widget['order_count']['canceled']), route('admin.order.history') . '?status=' . Status::ORDER_CANCELED],
                ],
            ],
            [
                'title' => 'Futures', 'icon' => 'las la-rocket', 'tone' => 'orange', 'link' => route('admin.futures.positions'),
                'items' => [
                    ['Open positions', number_format($widget['futures']['open']), route('admin.futures.positions')],
                    ['Margin in open positions', $money($widget['futures']['open_margin']), route('admin.futures.positions')],
                    ['Liquidated', number_format($widget['futures']['liquidated']), route('admin.futures.positions')],
                    ['Fees collected', $money($widget['futures']['fees']), route('admin.futures.positions')],
                ],
            ],
            [
                'title' => 'Stocks', 'icon' => 'las la-chart-bar', 'tone' => 'purple', 'link' => route('admin.stock.index'),
                'items' => [
                    ['Listed stocks', number_format($widget['total_stock']), route('admin.stock.index')],
                    ['Stocks bought', number_format($widget['total_stock_buyed']), route('admin.stock.transactions')],
                    ['Buy amount', $money($widget['stock_buy_amount']), route('admin.stock.transactions')],
                    ['Sell amount', $money($widget['stock_sell_amount']), route('admin.stock.transactions')],
                    ['Interest paid (total)', $money($widget['stock_interest_amount']), route('admin.stock.interests')],
                    ['Interest paid (today)', $money($widget['daily_interest_amount']), route('admin.stock.interests')],
                    ['Stock transfers', $money($widget['stock_transfer']), null],
                ],
            ],
            [
                'title' => 'P2P', 'icon' => 'las la-handshake', 'tone' => 'teal', 'link' => route('admin.p2p.trade.index', 'running'),
                'items' => [
                    ['Total trades', number_format($widget['p2p']['total_trade']), route('admin.p2p.trade.index', 'completed')],
                    ['Completed trades', number_format($widget['p2p']['completed_trade']), route('admin.p2p.trade.index', 'completed')],
                    ['Total ads', number_format($widget['p2p']['total_ad']), route('admin.p2p.ad.index')],
                    ['Total sell volume', number_format($widget['p2p_sell_order'], 2), route('admin.report.transaction')],
                    ['Total buy volume', number_format($widget['p2p_buy_order'], 2), route('admin.report.transaction')],
                ],
            ],
            [
                'title' => 'Currencies', 'icon' => 'las la-coins', 'tone' => 'pink', 'link' => route('admin.currency.crypto'),
                'items' => [
                    ['Total currencies', number_format($widget['total_currency']), null],
                    ['Crypto', number_format($widget['total_crypto_currency']), route('admin.currency.crypto')],
                    ['Fiat', number_format($widget['total_fiat_currency']), route('admin.currency.fiat')],
                    ['Initiated deposits (unpaid)', $money($widget['initiated']), route('admin.deposit.initiated')],
                ],
            ],
        ];
    @endphp

    <div class="ndb">
        {{-- hero --}}
        <section class="ndb-hero">
            <div class="ndb-hero__glow"></div>
            <div class="ndb-hero__top">
                <div>
                    <p class="ndb-hero__date">{{ now()->format('l, d F Y') }}</p>
                    <h2 class="ndb-hero__title">{{ $greeting }}, {{ $admin->name ?? 'Admin' }} 👋</h2>
                    <p class="ndb-hero__sub">Here's what's happening on {{ gs('site_name') }} today.</p>
                </div>
                <div class="ndb-hero__actions">
                    <a href="{{ route('admin.users.all') }}" class="ndb-btn ndb-btn--light"><i class="las la-users"></i> Users</a>
                    <a href="{{ route('admin.report.transaction') }}" class="ndb-btn ndb-btn--brand"><i class="las la-file-invoice-dollar"></i> Reports</a>
                </div>
            </div>
            <div class="ndb-hero__stats">
                <div class="ndb-hs">
                    <span class="ndb-hs__label">Total deposited</span>
                    <span class="ndb-hs__value" title="{{ $money($widget['total_deposit']) }}">{{ $short($widget['total_deposit']) }}</span>
                </div>
                <div class="ndb-hs">
                    <span class="ndb-hs__label">Total withdrawn</span>
                    <span class="ndb-hs__value" title="{{ $money($widget['total_withdraw_base']) }}">{{ $short($widget['total_withdraw_base']) }}</span>
                </div>
                <div class="ndb-hs">
                    <span class="ndb-hs__label">Net inflow</span>
                    @php $net = $widget['total_deposit'] - $widget['total_withdraw_base']; @endphp
                    <span class="ndb-hs__value {{ $net >= 0 ? 'is-up' : 'is-down' }}">{{ $net >= 0 ? '+' : '−' }}{{ $short(abs($net)) }}</span>
                </div>
                <div class="ndb-hs">
                    <span class="ndb-hs__label">New users</span>
                    <span class="ndb-hs__value">{{ number_format($widget['users_today']) }} <small>today · {{ number_format($widget['users_month']) }} this month</small></span>
                </div>
            </div>
        </section>

        {{-- needs attention --}}
        <div class="ndb-section-head">
            <h5><i class="las la-bell"></i> Needs attention</h5>
        </div>
        <div class="ndb-attn">
            @foreach ($attention as [$label, $count, $icon, $link, $tone])
                <a href="{{ $link }}" class="ndb-attn__item tone-{{ $tone }} {{ $count > 0 ? 'is-hot' : '' }}">
                    <span class="ndb-ico"><i class="{{ $icon }}"></i></span>
                    <span class="ndb-attn__body">
                        <span class="ndb-attn__count">{{ number_format($count) }}</span>
                        <span class="ndb-attn__label">{{ $label }}</span>
                    </span>
                    @if ($count > 0)
                        <span class="ndb-pulse"></span>
                    @endif
                    <i class="las la-arrow-right ndb-attn__go"></i>
                </a>
            @endforeach
        </div>

        {{-- money flow --}}
        <div class="row gy-4 mb-4">
            <div class="col-xl-8">
                <div class="ndb-card h-100">
                    <div class="ndb-card__head">
                        <div>
                            <h5>Money flow</h5>
                            <p>Successful deposits vs withdrawals · last 12 months ({{ __(gs('cur_text')) }})</p>
                        </div>
                        <div class="ndb-legend">
                            <span><i style="background:#22b455"></i>Deposits</span>
                            <span><i style="background:#ff6b6b"></i>Withdrawals</span>
                        </div>
                    </div>
                    <div id="flowChart" class="ndb-flow"></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="ndb-card h-100">
                    <div class="ndb-card__head">
                        <div>
                            <h5>Order book</h5>
                            <p>All spot orders</p>
                        </div>
                        <a href="{{ route('admin.order.history') }}" class="ndb-link">View all</a>
                    </div>
                    @php
                        $oc = $widget['order_count'];
                        $ot = max(1, $oc['total']);
                    @endphp
                    <div class="ndb-big">{{ number_format($oc['total']) }} <small>orders</small></div>
                    <div class="ndb-stack">
                        <span style="width: {{ $oc['completed'] / $ot * 100 }}%; background:#22b455"></span>
                        <span style="width: {{ $oc['open'] / $ot * 100 }}%; background:#3b82f6"></span>
                        <span style="width: {{ $oc['canceled'] / $ot * 100 }}%; background:#ff6b6b"></span>
                    </div>
                    <ul class="ndb-kv">
                        <li><span><i class="dot" style="background:#22b455"></i>Completed</span><b>{{ number_format($oc['completed']) }}</b></li>
                        <li><span><i class="dot" style="background:#3b82f6"></i>Open</span><b>{{ number_format($oc['open']) }}</b></li>
                        <li><span><i class="dot" style="background:#ff6b6b"></i>Canceled</span><b>{{ number_format($oc['canceled']) }}</b></li>
                    </ul>
                    <div class="ndb-split">
                        <div>
                            <span>Futures user P&amp;L</span>
                            <b class="{{ $widget['futures']['user_pnl'] >= 0 ? 'is-up' : 'is-down' }}">{{ $widget['futures']['user_pnl'] >= 0 ? '+' : '−' }}{{ $money(abs($widget['futures']['user_pnl'])) }}</b>
                        </div>
                        <div>
                            <span>Open futures</span>
                            <b>{{ number_format($widget['futures']['open']) }}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI groups --}}
        <div class="row gy-4 mb-4">
            @foreach ($groups as $g)
                <div class="col-xxl-4 col-md-6">
                    <div class="ndb-card ndb-group h-100">
                        <div class="ndb-card__head">
                            <div class="d-flex align-items-center gap-2">
                                <span class="ndb-ico tone-{{ $g['tone'] }}"><i class="{{ $g['icon'] }}"></i></span>
                                <h5 class="mb-0">{{ $g['title'] }}</h5>
                            </div>
                            <a href="{{ $g['link'] }}" class="ndb-link">Open <i class="las la-angle-right"></i></a>
                        </div>
                        <ul class="ndb-kv">
                            @foreach ($g['items'] as [$label, $value, $link])
                                <li>
                                    @if ($link)
                                        <a href="{{ $link }}">{{ $label }}</a>
                                    @else
                                        <span>{{ $label }}</span>
                                    @endif
                                    <b>{{ $value }}</b>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- summaries --}}
        <div class="row gy-4 mb-4">
            <div class="col-xl-4 col-lg-6">
                <div class="ndb-card h-100">
                    <div class="ndb-card__head">
                        <div>
                            <h5>Orders by pair</h5>
                            <p>Excluding canceled · scroll for more</p>
                        </div>
                    </div>
                    <div class="ndb-donut"><canvas id="pair-chart"></canvas></div>
                    <div class="order-list ndb-list">
                        <ul>
                            @forelse ($widget['order']['list'] as $order)
                                <li>
                                    <span class="ndb-sym">{{ @$order->pair->symbol }}</span>
                                    <b>{{ showAmount($order->total_amount, currencyFormat: false) }} {{ @$order->pair->coin->symbol }}</b>
                                </li>
                            @empty
                                <li class="ndb-empty">{{ __($emptyMessage) }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="ndb-card h-100">
                    <div class="ndb-card__head">
                        <div>
                            <h5>Deposits by currency</h5>
                            <p>Excluding rejected · scroll for more</p>
                        </div>
                        <a href="{{ route('admin.deposit.list') }}" class="ndb-link">All</a>
                    </div>
                    <div class="ndb-donut"><canvas id="deposit-chart"></canvas></div>
                    <div class="deposit-wrapper">
                        <ul class="deposit-list ndb-list">
                            @forelse ($widget['deposit']['list'] as $deposit)
                                <li>
                                    <span class="ndb-coin">
                                        <img src="{{ @$deposit->currency->image_url }}" alt="">
                                        <span>{{ @$deposit->currency->symbol }}<small>{{ strLimit(@$deposit->currency->name, 12) }}</small></span>
                                    </span>
                                    <span class="text-end">
                                        <b>{{ @$deposit->currency->sign }}{{ showAmount($deposit->total_amount, currencyFormat: false) }}</b>
                                        <small>{{ showAmount($deposit->total_amount * @$deposit->currency->rate) }}</small>
                                    </span>
                                </li>
                            @empty
                                <li class="ndb-empty">{{ __($emptyMessage) }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="ndb-card h-100">
                    <div class="ndb-card__head">
                        <div>
                            <h5>Withdrawals by currency</h5>
                            <p>Excluding rejected · scroll for more</p>
                        </div>
                        <a href="{{ route('admin.withdraw.data.all') }}" class="ndb-link">All</a>
                    </div>
                    <div class="ndb-donut"><canvas id="withdraw"></canvas></div>
                    <div class="withdraw-wrapper">
                        <ul class="withdraw-list ndb-list">
                            @forelse ($widget['withdraw']['list'] as $withdraw)
                                <li>
                                    <span class="ndb-coin">
                                        <img src="{{ @$withdraw->withdrawCurrency->image_url }}" alt="">
                                        <span>{{ @$withdraw->withdrawCurrency->symbol }}<small>{{ __(strLimit(@$withdraw->withdrawCurrency->name, 12)) }}</small></span>
                                    </span>
                                    <span class="text-end">
                                        <b>{{ @$withdraw->withdrawCurrency->sign }}{{ showAmount($withdraw->total_amount, currencyFormat: false) }}</b>
                                        <small>{{ showAmount($withdraw->total_amount * @$withdraw->withdrawCurrency->rate) }}</small>
                                    </span>
                                </li>
                            @empty
                                <li class="ndb-empty">{{ __($emptyMessage) }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- latest users + logins --}}
        <div class="row gy-4">
            <div class="col-xl-6">
                <div class="ndb-card h-100">
                    <div class="ndb-card__head">
                        <div>
                            <h5>Newest users</h5>
                            <p>Latest sign-ups</p>
                        </div>
                        <a href="{{ route('admin.users.all') }}" class="ndb-link">All users</a>
                    </div>
                    <ul class="ndb-users">
                        @forelse ($widget['latest_users'] as $u)
                            <li>
                                <a href="{{ route('admin.users.detail', $u->id) }}">
                                    <span class="ndb-avatar">{{ strtoupper(substr($u->firstname ?: $u->username ?: $u->email, 0, 1)) }}</span>
                                    <span class="ndb-users__who">
                                        <b>{{ trim($u->firstname . ' ' . $u->lastname) ?: $u->username }}</b>
                                        <small>{{ $u->email }}</small>
                                    </span>
                                    <span class="ndb-users__meta">
                                        <small>UID {{ $u->uid }}</small>
                                        <small>{{ diffForHumans($u->created_at) }}</small>
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="ndb-empty">{{ __($emptyMessage) }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="ndb-card h-100">
                    <div class="ndb-card__head">
                        <div>
                            <h5>Logins · last 30 days</h5>
                            <p>By browser, operating system and country</p>
                        </div>
                        <a href="{{ route('admin.report.login.history') }}" class="ndb-link">History</a>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <p class="ndb-mini">Browser</p>
                            <canvas id="userBrowserChart"></canvas>
                        </div>
                        <div class="col-sm-4">
                            <p class="ndb-mini">OS</p>
                            <canvas id="userOsChart"></canvas>
                        </div>
                        <div class="col-sm-4">
                            <p class="ndb-mini">Country</p>
                            <canvas id="userCountryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.cron_modal')
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        // Money flow
        (function() {
            const flow = @json($chart['flow']);
            const sym = @json(gs('cur_sym'));
            const fmt = (v) => sym + Number(v).toLocaleString(undefined, { maximumFractionDigits: 2 });
            new ApexCharts(document.querySelector('#flowChart'), {
                chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'inherit', zoom: { enabled: false } },
                series: [{ name: 'Deposits', data: flow.deposit }, { name: 'Withdrawals', data: flow.withdraw }],
                xaxis: { categories: flow.labels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: '#8a94a6' } } },
                yaxis: { labels: { formatter: (v) => sym + (Math.abs(v) >= 1000 ? (v / 1000).toFixed(1) + 'K' : v.toFixed(0)), style: { colors: '#8a94a6' } } },
                colors: ['#22b455', '#ff6b6b'],
                stroke: { curve: 'smooth', width: 2.5 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 95] } },
                dataLabels: { enabled: false },
                grid: { borderColor: 'rgba(138,148,166,.15)', strokeDashArray: 4 },
                legend: { show: false },
                tooltip: { y: { formatter: fmt } },
            }).render();
        })();

        // Infinite lists (load more on scroll)
        let take = 20;
        function infinite(selector, model, start, render) {
            let skip = start, busy = false, done = false;
            $(selector).on('scroll', function() {
                if (busy || done || this.scrollHeight - this.clientHeight - this.scrollTop > 4) return;
                busy = true;
                $.getJSON("{{ route('admin.load.data') }}", { model_name: model, skip: skip, take: take }, function(resp) {
                    busy = false;
                    if (!resp.success || !resp.data.length) { done = true; return; }
                    skip += take;
                    $(selector).find('ul').addBack('ul').first().append(resp.data.map(render).join(''));
                });
            });
        }
        const money = (v) => getAmount(v);
        infinite('.order-list', 'Order', 6, (o) =>
            `<li><span class="ndb-sym">${o.pair.symbol}</span><b>${money(o.total_amount)} ${o.pair.coin.symbol}</b></li>`);
        infinite('.deposit-list', 'Deposit', 6, (d) =>
            `<li><span class="ndb-coin"><img src="${d.currency.image_url}" alt=""><span>${d.currency.symbol}<small>${d.currency.name}</small></span></span>
             <span class="text-end"><b>${d.currency.sign}${money(d.total_amount)}</b><small>{{ gs('cur_sym') }}${money(parseFloat(d.total_amount) * parseFloat(d.currency.rate))}</small></span></li>`);
        infinite('.withdraw-list', 'Withdrawal', 6, (w) =>
            `<li><span class="ndb-coin"><img src="${w.withdraw_currency.image_url}" alt=""><span>${w.withdraw_currency.symbol}<small>${w.withdraw_currency.name}</small></span></span>
             <span class="text-end"><b>${w.withdraw_currency.symbol} ${money(w.total_amount)}</b><small>{{ gs('cur_sym') }}${money(parseFloat(w.total_amount) * parseFloat(w.withdraw_currency.rate))}</small></span></li>`);

        if (window.Chart) {
            Chart.defaults.global.legend.labels.boxWidth = 10;
            Chart.defaults.global.legend.position = 'bottom';
        }
        piChart(document.getElementById('deposit-chart'), @json(@$widget['deposit']['currency_symbol']), @json(@$widget['deposit']['currency_count']));
        piChart(document.getElementById('withdraw'), @json(@$widget['withdraw']['currency_symbol']), @json(@$widget['withdraw']['currency_count']));
        piChart(document.getElementById('pair-chart'), @json($widget['order']['symbol']), @json($widget['order']['count']));
        piChart(document.getElementById('userBrowserChart'), @json(@$chart['user_browser_counter']->keys()), @json(@$chart['user_browser_counter']->flatten()));
        piChart(document.getElementById('userOsChart'), @json(@$chart['user_os_counter']->keys()), @json(@$chart['user_os_counter']->flatten()));
        piChart(document.getElementById('userCountryChart'), @json(@$chart['user_country_counter']->keys()), @json(@$chart['user_country_counter']->flatten()));
    </script>
@endpush

@push('style')
    <style>
        .ndb { --ndb-card: #fff; --ndb-line: rgba(15, 23, 42, .07); --ndb-text: #0f172a; --ndb-muted: #64748b; --ndb-brand: #22b455; --ndb-radius: 18px; }
        .ndb h5 { font-size: 1rem; font-weight: 600; color: var(--ndb-text); margin: 0; }
        .ndb .is-up { color: #16a34a !important; }
        .ndb .is-down { color: #ef4444 !important; }

        /* hero */
        .ndb-hero { position: relative; overflow: hidden; border-radius: 24px; padding: 28px; margin-bottom: 28px; color: #e9f5ec;
            background: radial-gradient(120% 140% at 0% 0%, #12301f 0%, #0b1410 55%, #070b09 100%); box-shadow: 0 20px 50px -24px rgba(7, 30, 17, .55); }
        .ndb-hero__glow { position: absolute; right: -120px; top: -160px; width: 420px; height: 420px; border-radius: 50%;
            background: radial-gradient(circle, rgba(63, 212, 110, .35), transparent 65%); pointer-events: none; }
        .ndb-hero__top { position: relative; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 16px; align-items: flex-start; }
        .ndb-hero__date { margin: 0 0 6px; font-size: .75rem; letter-spacing: .08em; text-transform: uppercase; color: #8fe35c; font-weight: 600; }
        .ndb-hero__title { margin: 0; font-size: 1.6rem; font-weight: 700; color: #fff; }
        .ndb-hero__sub { margin: 6px 0 0; color: rgba(233, 245, 236, .65); font-size: .9rem; }
        .ndb-hero__actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .ndb-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 12px; font-size: .85rem; font-weight: 600; transition: .2s; }
        .ndb-btn--light { background: rgba(255, 255, 255, .08); color: #fff; border: 1px solid rgba(255, 255, 255, .12); }
        .ndb-btn--light:hover { background: rgba(255, 255, 255, .14); color: #fff; }
        .ndb-btn--brand { background: #3fd46e; color: #06210f; }
        .ndb-btn--brand:hover { background: #8fe35c; color: #06210f; }
        .ndb-hero__stats { position: relative; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-top: 24px; }
        .ndb-hs { background: rgba(255, 255, 255, .05); border: 1px solid rgba(255, 255, 255, .08); border-radius: 16px; padding: 14px 16px; backdrop-filter: blur(6px); min-width: 0; }
        .ndb-hs__label { display: block; font-size: .72rem; color: rgba(233, 245, 236, .6); text-transform: uppercase; letter-spacing: .06em; }
        .ndb-hs__value { display: block; margin-top: 4px; font-size: 1.35rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ndb-hs__value small { font-size: .7rem; font-weight: 500; color: rgba(233, 245, 236, .6); }
        .ndb-hs__value.is-up { color: #8fe35c !important; }
        .ndb-hs__value.is-down { color: #ff8a8a !important; }

        /* attention */
        .ndb-section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .ndb-section-head h5 i { color: var(--ndb-brand); }
        .ndb-attn { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 12px; margin-bottom: 28px; }
        .ndb-attn__item { position: relative; display: flex; align-items: center; gap: 12px; padding: 16px; border-radius: var(--ndb-radius); background: var(--ndb-card);
            border: 1px solid var(--ndb-line); transition: .2s; min-width: 0; }
        .ndb-attn__item:hover { transform: translateY(-2px); box-shadow: 0 12px 30px -18px rgba(15, 23, 42, .35); }
        .ndb-attn__item.is-hot { border-color: color-mix(in srgb, var(--tone) 35%, transparent); background: color-mix(in srgb, var(--tone) 5%, #fff); }
        .ndb-attn__body { display: flex; flex-direction: column; min-width: 0; }
        .ndb-attn__count { font-size: 1.4rem; font-weight: 700; color: var(--ndb-text); line-height: 1.1; }
        .ndb-attn__label { font-size: .78rem; line-height: 1.25; color: var(--ndb-muted); }
        .ndb-attn__go { margin-left: auto; color: var(--ndb-muted); opacity: 0; transition: .2s; }
        .ndb-attn__item:hover .ndb-attn__go { opacity: 1; }
        .ndb-pulse { position: absolute; top: 12px; right: 12px; width: 8px; height: 8px; border-radius: 50%; background: var(--tone); box-shadow: 0 0 0 0 var(--tone); animation: ndbPulse 1.8s infinite; }
        @keyframes ndbPulse { 0% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--tone) 60%, transparent); } 70% { box-shadow: 0 0 0 8px transparent; } 100% { box-shadow: 0 0 0 0 transparent; } }

        /* tones */
        .tone-green { --tone: #22b455; } .tone-orange { --tone: #f59e0b; } .tone-blue { --tone: #3b82f6; }
        .tone-purple { --tone: #8b5cf6; } .tone-teal { --tone: #14b8a6; } .tone-pink { --tone: #ec4899; }
        .ndb-ico { display: grid; place-items: center; flex-shrink: 0; width: 42px; height: 42px; border-radius: 13px; font-size: 1.35rem;
            color: var(--tone, #22b455); background: color-mix(in srgb, var(--tone, #22b455) 12%, transparent); }

        /* cards */
        .ndb-card { background: var(--ndb-card); border: 1px solid var(--ndb-line); border-radius: var(--ndb-radius); padding: 20px; box-shadow: 0 1px 2px rgba(15, 23, 42, .03); }
        .ndb-card__head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
        .ndb-card__head p { margin: 2px 0 0; font-size: .78rem; color: var(--ndb-muted); }
        .ndb-link { font-size: .8rem; font-weight: 600; color: var(--ndb-brand); white-space: nowrap; }
        .ndb-link:hover { color: #15803d; }
        .ndb-legend { display: flex; gap: 14px; font-size: .78rem; color: var(--ndb-muted); }
        .ndb-legend i, .ndb-kv .dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; }
        .ndb-flow { margin: 0 -8px -12px; min-height: 300px; }

        .ndb-big { font-size: 2rem; font-weight: 700; color: var(--ndb-text); line-height: 1; }
        .ndb-big small { font-size: .85rem; font-weight: 500; color: var(--ndb-muted); }
        .ndb-stack { display: flex; height: 10px; border-radius: 99px; overflow: hidden; background: #eef2f6; margin: 16px 0 6px; gap: 2px; }
        .ndb-split { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; }
        .ndb-split > div { background: #f6f8fa; border-radius: 12px; padding: 12px; }
        .ndb-split span { display: block; font-size: .72rem; color: var(--ndb-muted); }
        .ndb-split b { font-size: 1rem; color: var(--ndb-text); }

        .ndb-kv { list-style: none; margin: 0; padding: 0; }
        .ndb-kv li { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px dashed var(--ndb-line); font-size: .87rem; }
        .ndb-kv li:last-child { border-bottom: 0; }
        .ndb-kv li > a, .ndb-kv li > span { color: var(--ndb-muted); }
        .ndb-kv li > a:hover { color: var(--ndb-brand); }
        .ndb-kv b { color: var(--ndb-text); font-weight: 600; text-align: right; }

        /* lists */
        .ndb-donut { max-width: 240px; margin: 0 auto 12px; }
        .ndb-list, .ndb-list ul, .order-list ul { list-style: none; margin: 0; padding: 0; }
        .order-list, .deposit-list, .withdraw-list { max-height: 260px; overflow-y: auto; }
        .ndb-list li, .order-list li { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 10px 4px; border-bottom: 1px solid var(--ndb-line); font-size: .85rem; }
        .ndb-list li:last-child { border-bottom: 0; }
        .ndb-list b { color: var(--ndb-text); font-weight: 600; display: block; }
        .ndb-list small { display: block; color: var(--ndb-muted); font-size: .72rem; }
        .ndb-sym { font-weight: 600; color: var(--ndb-text); }
        .ndb-coin { display: flex; align-items: center; gap: 10px; font-weight: 600; color: var(--ndb-text); }
        .ndb-coin img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
        .ndb-empty { justify-content: center !important; color: var(--ndb-muted); }

        .ndb-users { list-style: none; margin: 0; padding: 0; }
        .ndb-users a { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 12px; transition: .15s; }
        .ndb-users a:hover { background: #f6f8fa; }
        .ndb-avatar { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0; font-weight: 700; color: #06210f; background: linear-gradient(135deg, #8fe35c, #22b455); }
        .ndb-users__who { min-width: 0; flex: 1; }
        .ndb-users__who b { display: block; color: var(--ndb-text); font-size: .88rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ndb-users__who small, .ndb-users__meta small { display: block; color: var(--ndb-muted); font-size: .74rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ndb-users__meta { text-align: right; flex-shrink: 0; }
        .ndb-mini { margin: 0 0 8px; font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--ndb-muted); text-align: center; }

        @media (max-width: 1399px) { .ndb-attn { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 991px) { .ndb-hero__stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 767px) {
            .ndb-attn { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .ndb-hero { padding: 20px; border-radius: 20px; }
            .ndb-hero__title { font-size: 1.3rem; }
            .ndb-hs__value { font-size: 1.1rem; }
        }
        @media (max-width: 420px) { .ndb-attn { grid-template-columns: 1fr; } }
    </style>
@endpush

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary btn-sm" data-bs-toggle="modal" data-bs-target="#cronModal">
        <i class="las la-server"></i>@lang('Cron Setup')
    </button>
@endpush
