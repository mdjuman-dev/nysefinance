@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Post')</th>
                                    <th>@lang('Author')</th>
                                    <th>@lang('Likes')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $blog)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($blog->image)
                                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ Str::limit($blog->title, 40) }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $blog->user->username }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge--success me-2">{{ $blog->likes }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($blog->status == 'published')
                                                <span class="badge badge--success">@lang('Published')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Draft')</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $blog->created_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-pen"></i> @lang('Edit')
                                                </a>
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
                @if ($blogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($blogs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Likes Modal -->
    <div class="modal fade" id="editLikesModal" tabindex="-1" role="dialog" aria-labelledby="editLikesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLikesModalLabel">@lang('Edit Likes Count')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editLikesForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="likes">@lang('Likes Count')</label>
                            <input type="number" class="form-control" id="likes" name="likes" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    (function($) {
        "use strict";

        $('.edit-likes').on('click', function() {
            var id = $(this).data('id');
            var likes = $(this).data('likes');

            $('#likes').val(likes);
            $('#editLikesForm').attr('action', '{{ route("admin.blog.update.likes", "") }}/' + id);
            $('#editLikesModal').modal('show');
        });

        $('#editLikesForm').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editLikesModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    })(jQuery);
</script>
@endpush
