@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 mb-4">
        <div class="col-xxl-2 col-sm-4">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <div class="widget-two__content">
                    <h3>{{ $stats['open_count'] }}</h3>
                    <p>@lang('Open Positions')</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-sm-4">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <div class="widget-two__content">
                    <h3>{{ showAmount($stats['open_margin'], currencyFormat: false) }}</h3>
                    <p>@lang('Margin Locked') (USDT)</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-sm-4">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <div class="widget-two__content">
                    <h3>{{ showAmount($stats['open_notional'], currencyFormat: false) }}</h3>
                    <p>@lang('Open Interest') (USDT)</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-sm-4">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <div class="widget-two__content">
                    <h3>{{ showAmount($stats['fees'], currencyFormat: false) }}</h3>
                    <p>@lang('Fees Collected') (USDT)</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-sm-4">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <div class="widget-two__content">
                    <h3 class="{{ $stats['house_pnl'] >= 0 ? 'text--success' : 'text--danger' }}">{{ showAmount($stats['house_pnl'], currencyFormat: false) }}</h3>
                    <p title="@lang('Margin + fees received minus payouts, on settled positions')">@lang('Platform Net') (USDT)</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-sm-4">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <div class="widget-two__content">
                    <h3>{{ $stats['liquidations'] }}</h3>
                    <p>@lang('Liquidations')</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach ($scopes as $key => $label)
                    <a href="{{ route('admin.futures.positions', $key) }}" class="btn btn-sm {{ $scope == $key ? 'btn--primary' : 'btn-outline--primary' }}">{{ __($label) }}</a>
                @endforeach
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two highlighted-table">
                            <thead>
                                <tr>
                                    <th>@lang('User | TRX')</th>
                                    <th>@lang('Pair | Side')</th>
                                    <th>@lang('Margin | Size')</th>
                                    <th>@lang('Entry | Liq.')</th>
                                    <th>@lang('TP | SL')</th>
                                    <th>@lang('Close | PnL')</th>
                                    <th>@lang('Payout')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($positions as $p)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$p->user->fullname }}</span><br>
                                            <a href="{{ route('admin.users.detail', $p->user_id) }}"><span>@</span>{{ @$p->user->username }}</a><br>
                                            <small class="text-muted">{{ $p->trx }}</small>
                                        </td>
                                        <td>
                                            {{ str_replace('_', '/', @$p->pair->symbol) }}<br>
                                            <span class="badge {{ $p->side == 'long' ? 'badge--success' : 'badge--danger' }}">{{ ucfirst($p->side) }} {{ $p->leverage }}x</span>
                                        </td>
                                        <td>{{ showAmount($p->margin, currencyFormat: false) }} USDT<br><small>{{ showAmount($p->size, 8, currencyFormat: false) }}</small></td>
                                        <td>{{ showAmount($p->entry_price, currencyFormat: false) }}<br><small class="text--warning">{{ showAmount($p->liquidation_price, currencyFormat: false) }}</small></td>
                                        <td>
                                            <span class="text--success">{{ $p->take_profit ? showAmount($p->take_profit, currencyFormat: false) : '—' }}</span><br>
                                            <span class="text--danger">{{ $p->stop_loss ? showAmount($p->stop_loss, currencyFormat: false) : '—' }}</span>
                                        </td>
                                        <td>
                                            {{ $p->close_price ? showAmount($p->close_price, currencyFormat: false) : '—' }}<br>
                                            @if ($p->realized_pnl !== null)
                                                <span class="{{ $p->realized_pnl >= 0 ? 'text--success' : 'text--danger' }}">{{ $p->realized_pnl >= 0 ? '+' : '' }}{{ showAmount($p->realized_pnl, currencyFormat: false) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->payout !== null ? showAmount($p->payout, currencyFormat: false) : '—' }}</td>
                                        <td>
                                            @if ($p->status == 'open')
                                                <span class="badge badge--primary">@lang('Open')</span>
                                            @elseif ($p->status == 'liquidated')
                                                <span class="badge badge--danger">@lang('Liquidated')</span>
                                            @else
                                                <span class="badge badge--dark">{{ __(ucwords(str_replace('_', ' ', $p->close_reason ?? 'closed'))) }}</span>
                                            @endif
                                            <br><small class="text-muted">{{ showDateTime($p->closed_at ?? $p->created_at) }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($positions->hasPages())
                    <div class="card-footer py-4">{{ paginateLinks($positions) }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="TRX / Username / Pair" />
    <form action="{{ route('admin.futures.sweep') }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-sm btn-outline--primary" type="submit"><i class="las la-sync"></i> @lang('Settle now')</button>
    </form>
@endpush
