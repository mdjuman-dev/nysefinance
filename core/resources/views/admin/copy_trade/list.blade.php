@extends('admin.layouts.app')
@section('panel')

    <div class="row mb-none-30 mb-3 align-items-center gy-4">
        <div class="col-xxl-4 col-sm-4">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Sell"
                      value="{{ $total_sell }}" bg="primary" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-4">
            <x-widget style="6" link="#" icon="las la-coins f-size--56" title="Total Buy"
                      value="{{ $total_buy }}" bg="success" />
        </div>
        <div class="col-xxl-4 col-sm-4">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Interest"
                      value="{{ $total_interest }}" bg="danger" />
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
                                    <th>@lang('Type')</th>
                                    <th>@lang('Start Date')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Logo')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trades as $trade)

                                    <tr>
                                        <td>{{$trade->name}}</td>
                                        <td>{{ucwords(str_replace('_', '-', $trade->trade_type))}}</td>
                                        <td>{{ucwords($trade->type)}}</td>
                                        <td>{{$trade->start_date}}</td>
                                        <td>${{$trade->amount}}</td>
                                        <td>
                                            <img style="border-radius: 50px" width="40" height="40"
                                                 src="{{ getImage(getFilePath('currency') .'/'.$trade->image,getFileSize('currency')) }}">
                                        </td>
                                        <td>
                                            @if($trade->status=='active')
                                                <span class="badge badge--success">Active</span>
                                            @else
                                                <span class="badge badge--danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin.copy.delete',[$trade->id])}}" class="btn btn-danger">Delete</a>
                                            <a href="{{route('admin.copy.edit',[$trade->id])}}" class="btn btn-info">Edit</a>
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
    <a href="{{route('admin.copy.create')}}" class="float-right btn btn-primary">New Stock</a>
@endpush
