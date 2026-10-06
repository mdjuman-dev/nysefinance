@extends('admin.layouts.app')
@section('panel')

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
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Trade Type')</th>
                                    <th>@lang('Price')</th>

                                    <th>@lang('Total Buy')</th>
                                    <th>@lang('Total Sell')</th>
                                    <th>@lang('Total Interest')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trades as $trade)

                                    <tr>
                                        <td>{{$trade->name}}</td>
                                        <td>{{ucwords(str_replace('_', '-', $trade->trade_type))}}</td>
                                        <td>${{$trade->amount}}</td>

                                        @php

                                        $totalSell=\App\Models\CopyTransaction::where('trade_id', $trade->id)->where('type', 'sell')->sum('amount');
                                        $totalBuy=\App\Models\CopyTransaction::where('trade_id', $trade->id)->where('type', 'buy')->sum('amount');
                                        $totalInterest=\App\Models\CopyTransaction::where('trade_id', $trade->id)->where('type', 'interest')->sum('amount');

                                            @endphp

                                        <td>
                                            <div class="text-success" style="font-size: 18px !important;font-weight: 800;">
                                                ${{$totalBuy}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-danger" style="font-size: 18px !important;font-weight: 800;">
                                                ${{$totalSell}}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="text-danger" style="font-size: 18px !important;font-weight: 800;">
                                                ${{$totalInterest}}
                                            </div>
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
                @if ($trades->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($trades) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')

@endpush
