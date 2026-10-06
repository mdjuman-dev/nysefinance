@extends('admin.layouts.app')
@section('panel')

    <div class="row">
        <div class="col-lg-12">

            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="d-flex flex-wrap gap-4">

                            <div class="flex-grow-1">
                                <label>@lang('Type')</label>
                                <select name="trx_type" class="form-control select2" data-minimum-results-for-search="-1">
                                    <option value="">--Search By Type--</option>
                                    <option {{request()->get('trx_type') && request()->get('trx_type')=='unfix'?'selected':''}} value="unfix">Live Market</option>
                                    <option {{request()->get('trx_type') && request()->get('trx_type')=='fix'?'selected':''}} value="fix">Mutual</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Search By Email')</label>
                                <select name="email" class="form-control select2">
                                    <option value="">--Email--</option>
                                    @foreach($users as $user)
                                        <option {{request()->get('email')==$user->id?'selected':''}} value="{{$user->id}}">{{$user->email}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" id="daterangepicker" type="search" class="datepicker-here form-control bg--white pe-2 date-range"
                                       placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filter')</button>
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
                                <th>@lang('Amount')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('For')</th>
                                <th>@lang('Remark')</th>
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
                                        {{$transaction->amount}} (USDT)
                                    </td>
                                    <td>
                                        @if($transaction->type=='sell')
                                            <span class="badge badge--danger">Sell</span>
                                        @else
                                            <span class="badge badge--success">Buy</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if($transaction->stock_type=='fix')
                                            <span class="badge badge--danger">Mutual Fund</span>
                                        @else
                                            <span class="badge badge--success">Live Market</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->user_stock && isset($transaction->user_stock->product))
                                        {{$transaction->user_stock->product->name}}
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
    <a href="{{route('admin.stock.index')}}" class="float-right btn btn-primary">Back</a>
@endpush


@push('style')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


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
        })
    </script>
@endpush
