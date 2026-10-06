@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Edit Post')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>@lang('Title') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $blog->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Content') <span class="text-danger">*</span> <small class="text-muted">(@lang('Max 200 characters'))</small></label>
                            <textarea class="form-control" name="content" rows="4" maxlength="200" required>{{ old('content', $blog->content) }}</textarea>
                            <div class="form-text">
                                <span id="charCount">{{ strlen($blog->content) }}</span>/200 @lang('characters')
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Likes Count') <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="likes" value="{{ old('likes', $blog->likes) }}" min="0" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Status') <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" required>
                                <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>@lang('Published')</option>
                                <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>@lang('Draft')</option>
                            </select>
                        </div>

                        @if($blog->image)
                            <div class="form-group">
                                <label>@lang('Current Image')</label>
                                <div class="mb-3">
                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>@lang('Author')</label>
                            <input type="text" class="form-control" value="{{ $blog->user->fullname }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('Created Date')</label>
                            <input type="text" class="form-control" value="{{ $blog->created_at->format('M d, Y H:i:s') }}" readonly>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100">@lang('Update Post')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        // Character counter
        $('textarea[name="content"]').on('input', function() {
            var length = $(this).val().length;
            $('#charCount').text(length);
            
            if (length > 200) {
                $('#charCount').addClass('text-danger');
            } else {
                $('#charCount').removeClass('text-danger');
            }
        });
    });
</script>
@endpush 