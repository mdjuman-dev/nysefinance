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
                                    <th>@lang('User')</th>
                                    <th>@lang('Phone Number')</th>
                                    <th>@lang('Address')</th>
                                    <th>@lang('Card Type')</th>
                                    <th>@lang('Document Type')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)

                                    <tr>
                                        <td>
                                            {{$application->user->firstname.' '.$application->user->lastname}}
                                            <br>
                                            <a href="{{route('admin.users.detail',[$application->user_id])}}">{{$application->user->username}}</a>
                                        </td>
                                        <td>{{$application->phone}}</td>
                                        <td>{{$application->address}}</td>
                                        <td>{{ucwords($application->card_type)}}</td>
                                        <td>{{ucwords($application->doc_type)}}</td>
{{--                                            <img width="80" height="70" src="{{ getImage(getFilePath('currency') .'/'.$product->image,getFileSize('currency')) }}">--}}
                                        <td>
                                            <a href="{{route('admin.card.application.details',[$application->id])}}" class="btn btn-warning">Details</a>
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
                @if ($applications->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($applications) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')


@endpush
