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
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brokers as $product)

                                    <tr>

                                        <td>{{$product->name}}</td>
                                        <td>{{$product->status}}</td>
                                        <td>{{$product->type}}</td>

                                        <td>
                                            <a href="{{route('admin.broker.destroy',[$product->id])}}" class="btn btn-danger">Delete</a>
                                            <a href="{{route('admin.broker.edit',[$product->id])}}" class="btn btn-info">Edit</a>
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
                @if ($brokers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($brokers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.broker.create')}}" class="float-right btn btn-primary">Create</a>
@endpush
