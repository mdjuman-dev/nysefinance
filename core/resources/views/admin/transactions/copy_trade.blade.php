@extends('admin.layouts.app')
@section('panel')

    <div class="col-md-12 mb-5">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link custom-tab-btn" href="{{route('admin.trx',['user_id'=>request()->get('user_id')])}}">Transactions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link custom-tab-btn" href="{{route('admin.stock.trx',['user_id'=>request()->get('user_id')])}}">Stock Transaction</a>
            </li>
            <li class="nav-item">
                <a class="nav-link custom-tab-btn" href="{{route('admin.coin.stack.trx',['user_id'=>request()->get('user_id')])}}">Coin Transaction</a>
            </li>
            <li class="nav-item">
                <a class="nav-link custom-tab-btn active" href="{{route('admin.copy.trx',['user_id'=>request()->get('user_id')])}}">Copy Trade Transaction</a>
            </li>
        </ul>
    </div>

    <div class="row mb-none-30 mb-3 align-items-center gy-4">
        <div class="col-xxl-4 col-sm-4">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Sell"
                      value="${{ $total_sell }}" bg="primary" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-4">
            <x-widget style="6" link="#" icon="las la-coins f-size--56" title="Total Buy"
                      value="${{ $total_buy }}" bg="success" />
        </div>
        <div class="col-xxl-4 col-sm-4">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Interest"
                      value="${{ $total_interest }}" bg="danger" />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card responsive-filter-card mb-4">
                <div class="card-body">

                    <form class="d-block" method="get" action="">
                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="trade_type" class="form-control">
                                        <option value="">--Trade Type--</option>
                                        <option {{request()->get('trade_type') && request()->get('trade_type')=='classic'?'selected':''}} value="classic">Classic</option>
                                        <option {{request()->get('trade_type') && request()->get('trade_type')=='token_splash'?'selected':''}} value="token_splash">Token Splash</option>
                                        <option {{request()->get('trade_type') && request()->get('trade_type')=='gold_fx'?'selected':''}} value="gold_fx">Gold fx</option>
                                        <option {{request()->get('trade_type') && request()->get('trade_type')=='by_votes'?'selected':''}} value="by_votes">By Votes</option>
                                        <option {{request()->get('trade_type') && request()->get('trade_type')=='puzzle_hunt'?'selected':''}} value="puzzle_hunt">Puzzle Hunt</option>
                                        <option {{request()->get('trade_type') && request()->get('trade_type')=='spot_x'?'selected':''}} value="spot_x">Spot-x</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="type" class="form-control">
                                        <option  value="">--Type--</option>
                                        <option {{request()->get('type') && request()->get('type')=='buy'?'selected':''}} value="buy">Buy</option>
                                        <option {{request()->get('type') && request()->get('type')=='sell'?'selected':''}} value="sell">Sell</option>
                                        <option {{request()->get('type') && request()->get('type')=='interest'?'selected':''}} value="interest">Interest</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" id="daterangepicker" name="filter_date" placeholder="Choose Date" autocomplete="off" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="user_id" class="form-control select2">
                                        <option  value="">--Users--</option>
                                        @foreach($users as $user)
                                            <option {{request()->get('user_id') && request()->get('user_id')==$user->id?'selected':''}} value="{{$user->id}}">{{$user->email}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-primary justify-content-center text-white w-100">
                                        <i class="las la-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>


            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Trade')</th>
                                <th>@lang('Trade Type')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        {{$transaction->user->fullname}}
                                        <br>
                                        {{$transaction->user->email}}
                                    </td>
                                    <td>
                                        {{$transaction->trade->name}} (USDT)
                                    </td>
                                    <td>
                                        {{ucwords(str_replace($transaction->trade_type, '_', '-'))}}
                                    </td>
                                    <td>
                                        ${{$transaction->amount}}
                                    </td>
                                    <td>
                                        @if($transaction->type=='sell')
                                            <span class="badge badge--danger">Sell</span>
                                        @else
                                            <span class="badge badge--success">{{ucwords($transaction->type)}}</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{$transaction->created_at->format('d-m-Y h:i A')}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">No Data Available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($transactions->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($transactions) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')


@endpush


@push('style')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .custom-tab-btn.active{
            background: #071151 !important;
            color: white !important;
        }
    </style>
@endpush


@push('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>

        $(document).ready(function (e){


            $('#daterangepicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('#daterangepicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('.select2').select2();
        })
    </script>
@endpush
