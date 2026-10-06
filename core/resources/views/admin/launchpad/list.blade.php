@extends('admin.layouts.app')
@section('title', 'LaunchPad List')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('SL')#</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Total Allocation')</th>
                                    <th>@lang('Cap Per Subscriber')</th>
                                    <th>@lang('Committed Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>Z
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($launchPads as $launchPad)
                                    <tr>
                                        <td>#{{ $loop->iteration }}</td>
                                        <td>{{ $launchPad->title }}</td>
                                        <td>
                                            <img width="40" height="40" src="{{ getImage(getFilePath('currency') .'/'.$launchPad->image,getFileSize('currency')) }}" alt="">
                                        </td>
                                        <td>{{ number_format($launchPad->price, 2) }} {{ $launchPad->price_currency }}</td>
                                        <td>{{ number_format($launchPad->total_allocation) }}</td>
                                        <td>{{ number_format($launchPad->cap_per_subscriber) }}</td>
                                        <td>{{ number_format($launchPad->total_committed_amount) }}</td>
                                        <td>
                                            @if($launchPad->status == 'active')
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @elseif($launchPad->status == 'upcoming')
                                                <span class="badge badge--warning">@lang('Upcoming')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Closed')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.launchpad.edit', $launchPad->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline--danger deleteBtn"
                                                data-url="{{ route('admin.launchpad.del',  $launchPad->id) }}">
                                                    <i class="la la-trash"></i> @lang('Delete')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __('No LaunchPad Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($launchPads->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($launchPads) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">@lang('Delete Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="get" id="deleteForm">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete this LaunchPad?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.launchpad.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="la la-plus"></i>@lang('Add New LaunchPad')
    </a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.deleteBtn').on('click', function() {
                var modal = $('#deleteModal');
                var url = $(this).attr('data-url');
                $('#deleteForm').attr('action', url);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
