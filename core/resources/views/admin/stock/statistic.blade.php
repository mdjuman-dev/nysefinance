@extends('admin.layouts.app')
@section('panel')

<div class="row">
    <div class="col-lg-12">

        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <div class="row">


                    <div class="col-md-3 col-6">
                        <div class="widget-seven bg--primary ">
                            <div class="widget-seven__content">
                                <span class="widget-seven__content-icon">
                                    <span class="icon">
                                        <i class="fas fa-spinner"></i>
                                    </span>
                                </span>

                                <div class="widget-seven__description">
                                    <p class="widget-seven__content-title">Total Buy</p>
                                    <h3 class="widget-seven__content-amount">{{$totalBuy}} USD</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="widget-seven bg--primary ">
                            <div class="widget-seven__content">
                                <span class="widget-seven__content-icon">
                                    <span class="icon">
                                        <i class="las la-check-circle"></i>
                                    </span>
                                </span>

                                <div class="widget-seven__description">
                                    <p class="widget-seven__content-title">Total Sell</p>
                                    <h3 class="widget-seven__content-amount">{{$totalSell}} USD</h3>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3 col-6">
                        <div class="widget-seven bg--primary ">
                            <div class="widget-seven__content">
                                <span class="widget-seven__content-icon">
                                    <span class="icon">
                                        <i class="la la-list"></i>
                                    </span>
                                </span>

                                <div class="widget-seven__description">
                                    <p class="widget-seven__content-title">Total Exchange</p>
                                    <h3 class="widget-seven__content-amount">{{$totalExchange}} USD</h3>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3 col-6">
                        <div class="widget-seven bg--primary ">
                            <div class="widget-seven__content">
                                <span class="widget-seven__content-icon">
                                    <span class="icon">
                                        <i class="las la-coins"></i>
                                    </span>
                                </span>

                                <div class="widget-seven__description">
                                    <p class="widget-seven__content-title">Total Interest</p>
                                    <h3 class="widget-seven__content-amount">{{$totalInterest}} USD</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
