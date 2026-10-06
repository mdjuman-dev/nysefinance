@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card custom--card">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Create New Blog Post')</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="title" class="form-label">@lang('Title') <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               placeholder="@lang('Enter blog post title')"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="content" class="form-label">@lang('Content') <span class="text-danger">*</span> <small class="text-muted">(@lang('Max 200 characters'))</small></label>
                        <textarea class="form-control @error('content') is-invalid @enderror"
                                  id="content"
                                  name="content"
                                  rows="4"
                                  maxlength="200"
                                  placeholder="@lang('Write your blog post content here... (max 200 characters)')"
                                  required>{{ old('content') }}</textarea>
                        <div class="form-text">
                            <span id="charCount">0</span>/200 @lang('characters')
                        </div>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="image" class="form-label">@lang('Blog Image') <small class="text-muted">(@lang('Optional'))</small></label>
                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image"
                               name="image"
                               accept="image/*">
                        <div class="form-text">@lang('Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB')</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status" class="form-label">@lang('Status') <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="">@lang('Select status')</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>@lang('Published')</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>@lang('Draft')</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>@lang('Note:')</strong> @lang('You can only create one blog post per day. Content is limited to 200 characters.')
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('user.blog.index') }}" class="btn btn--secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> @lang('Back')
                        </a>
                        <button type="submit" class="btn btn--primary btn-sm">
                            <i class="fas fa-save"></i> @lang('Save')
                        </button>
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
        $('#content').on('input', function() {
            var length = $(this).val().length;
            $('#charCount').text(length);

            if (length > 200) {
                $('#charCount').addClass('text-danger');
            } else {
                $('#charCount').removeClass('text-danger');
            }
        });

        // Initialize character count
        $('#charCount').text($('#content').val().length);
    });
</script>
@endpush
