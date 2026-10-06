@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="row justify-content-between gy-3 align-items-center">
        <div class="col-lg-4">
            <h4 class="mb-0">{{ __($pageTitle) }}</h4>
        </div>
        <div class="col-lg-4">
            <form class="d-flex gap-2 flex-wrap" method="get" action="">
                <div class="row">
                    <div class="col-md-3">
                       <div class="form-group">
                           <select name="trade_type" class="form--control">
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
                    <div class="col-md-3">
                        <div class="form-group">
                        <select name="type" class="form--control">
                            <option  value="">--Type--</option>
                            <option {{request()->get('type') && request()->get('type')=='buy'?'selected':''}} value="buy">Buy</option>
                            <option {{request()->get('type') && request()->get('type')=='sell'?'selected':''}} value="sell">Sell</option>
                            <option {{request()->get('type') && request()->get('type')=='interest'?'selected':''}} value="interest">Interest</option>
                        </select>
                    </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <input type="text" name="filter_date" placeholder="Choose Date" autocomplete="off" class="form--control">
                    </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <button type="submit" class="input-group-text bg-primary justify-content-center text-white w-100">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                    <tr>
                        <th>@lang('Trade')</th>
                        <th>@lang('Type')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('TRXID')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                {{$transaction->trade->name}}({{$transaction->trade->type}})
                            </td>
                            <td>
                                @if($transaction->type=='buy')
                                    <span class="badge badge--danger">BUY</span>
                                @elseif($transaction->type=='sell')
                                    <span class="badge badge--success">SELL</span>
                                @else
                                    <span class="badge badge--danger">INTEREST</span>
                                @endif
                            </td>
                            <td>{{$transaction->amount}}</td>
                            <td>{{ showDateTime($transaction->created_at) }}</td>
                            <td>{{$transaction->transaction_id}}</td>


                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="5">
                                No Data Found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>


    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" ></script>

    <script>
        $(document).ready(function (e){

            $('#filter_date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('#filter_date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        })
    </script>


@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" />

@endpush


