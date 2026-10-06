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
                                    <th>Video</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($videos as $video)

                                    <tr>

                                        <td>{{$video->product->name}}</td>
                                        <td>{{$video->video}}</td>
                                        <td>
                                            <a href="{{route('admin.stock-video.destroy',[$video->id])}}" class="btn btn-danger">Delete</a>
                                            <a href="{{route('admin.stock-video.edit',[$video->id])}}" class="btn btn-info">Edit</a>
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
                @if ($videos->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($videos) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.stock-video.create')}}" class="float-right btn btn-primary">Create</a>
@endpush
