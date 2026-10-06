@extends('admin.layouts.app')
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
                                    <th>@lang('Question')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faqs as $faq)
                                    <tr>
                                        <td>#{{ $loop->iteration }}</td>
                                        <td>{!! $faq->question !!}</td>
                                        <td>
                                            @if($faq->status=='active')
                                                <span class="badge badge--success">{{$faq->status}}</span>
                                            @else
                                                <span class="badge badge--danger">{{$faq->status}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline--danger deleteBtn"
                                                data-url="{{ route('admin.faq.del', $faq->id) }}">
                                                    <i class="la la-trash"></i> @lang('Delete')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($faqs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($faqs) }}
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
                <form  method="get" id="deleteForm">

                <div class="modal-body">
                        <p>@lang('Are you sure to delete this FAQ?')</p>
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
    <a href="{{ route('admin.faq.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="la la-plus"></i>@lang('Add New')
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
