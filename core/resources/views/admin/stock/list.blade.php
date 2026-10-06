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
                                    <th>@lang('Price')</th>
                                    <th>@lang('Fix Rate')</th>
                                    <th>@lang('Market Price')</th>
                                    <th>@lang('Logo')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)

                                    <tr>
                                        <td>{{$product->name}}</td>
                                        <td>${{$product->price}}</td>
                                        <td>{{$product->fix_rate}}</td>
                                        <td>{{$product->unfix_rate}}</td>
                                        <td>
                                            <img width="80" height="70" src="{{ getImage(getFilePath('currency') .'/'.$product->image,getFileSize('currency')) }}">
                                        </td>
                                        <td>
                                            <a href="{{route('admin.stock.delete',[$product->id])}}" class="btn btn-danger">Delete</a>
                                            <a href="{{route('admin.stock.edit',[$product->id])}}" class="btn btn-info">Edit</a>
                                            <a href="{{route('admin.stock.statistic',[$product->id])}}" class="btn btn-warning">Statistic</a>
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
                @if ($products->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($products) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.stock.sell.request')}}" class="float-right btn btn-success">Sell Request</a>
    <a href="{{route('admin.stock.create')}}" class="float-right btn btn-primary">New Stock</a>
@endpush
