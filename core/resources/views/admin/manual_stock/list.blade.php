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
                                    <th>@lang('CUSIP')</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Holder</th>
                                    <th>Buy Date</th>
                                    <th>Sell Date</th>
                                    <th>Status</th>
                                    <th>Buyer Email</th>
                                    <th>E. Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $product)

                                    <tr>

                                        <td>{{$product->cusip_id}}</td>
                                        <td>{{$product->name}}</td>
                                        <td>{{$product->amount}}</td>
                                        <td>{{$product->holder}}</td>
                                        <td>{{$product->buy_date}}</td>
                                        <td>{{$product->sell_date}}</td>
                                        <td>{{$product->status}}</td>
                                        <td>{{$product->buyer_email}}</td>
                                        <td>{{$product->buyer_number}}</td>

                                        <td>
                                            <a href="{{route('admin.manual-stock.destroy',[$product->id])}}" class="btn btn-danger">Delete</a>
                                            <a href="{{route('admin.manual-stock.edit',[$product->id])}}" class="btn btn-info">Edit</a>
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
                @if ($stocks->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($stocks) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.manual-stock.create')}}" class="float-right btn btn-primary">Create</a>
@endpush
