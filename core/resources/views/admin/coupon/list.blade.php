@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Icon')</th>
                                    <th>@lang('Win date')</th>
                                    <th>@lang('Expire Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)

                                    <tr>
                                        <td>{{$coupon->name}}</td>
                                        <td>{{$coupon->code}}</td>
                                        <td>${{$coupon->price}}</td>
                                        <td>
                                            <img width="50" height="40" src="{{ getImage(getFilePath('currency') .'/'.$coupon->icon,getFileSize('currency')) }}">
                                        </td>
                                        <td>
                                            {{$coupon->wining_date}}
                                        </td>
                                        <td>
                                            {{$coupon->expire_date}}
                                        </td>
                                        <td>
                                            <a href="{{route('admin.coupon.edit',[$coupon->id])}}" class="btn btn-info">Edit</a>
                                            <a href="{{route('admin.coupon.delete',[$coupon->id])}}" class="btn btn-danger">Delete</a>
                                            <a href="{{route('admin.coupon.details',[$coupon->id])}}" class="btn btn-danger">Details</a>
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
                @if ($coupons->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($coupons) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.coupon.create')}}" class="float-right btn btn-primary">Create</a>
@endpush
