@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card custom--card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="card-title mb-2">{{ $blog->title }}</h2>
                        <div class="d-flex align-items-center text-muted">
                            <span class="me-3">
                                <i class="fas fa-user"></i> {{ $blog->user->fullname }}
                            </span>
                            <span class="me-3">
                                <i class="fas fa-calendar"></i> {{ $blog->formatted_date }}
                            </span>
                            <span class="me-3">
                                <i class="fas fa-heart"></i> <span id="likes-count">{{ $blog->likes }}</span> likes
                            </span>
                            <span class="badge bg-{{ $blog->status == 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if(auth()->id() == $blog->user_id)
                        <div class="btn-group" role="group">
                            <a href="{{ route('user.blog.edit', $blog->id) }}" 
                               class="btn btn-sm btn--info">
                                <i class="fas fa-edit"></i> @lang('Edit')
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn--danger" 
                                    onclick="deleteBlog({{ $blog->id }})">
                                <i class="fas fa-trash"></i> @lang('Delete')
                            </button>
                        </div>
                    @endif
                </div>

                @if($blog->image)
                    <div class="blog-image mb-4">
                        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-fluid rounded">
                    </div>
                @endif

                <div class="blog-content">
                    <p>{{ $blog->content }}</p>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn--primary like-btn" data-id="{{ $blog->id }}">
                        <i class="fas fa-heart"></i> @lang('Like')
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('user.blog.index') }}" class="btn btn--secondary">
                        <i class="fas fa-arrow-left"></i> @lang('Back to My Posts')
                    </a>
                    
                    <a href="{{ route('user.blog.all') }}" class="btn btn--primary">
                        <i class="fas fa-list"></i> @lang('View All Posts')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">@lang('Confirm Delete')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @lang('Are you sure you want to delete this blog post? This action cannot be undone.')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">@lang('Delete')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .blog-content {
        line-height: 1.8;
        font-size: 16px;
    }
    
    .blog-content h1, .blog-content h2, .blog-content h3, 
    .blog-content h4, .blog-content h5, .blog-content h6 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .blog-content p {
        margin-bottom: 1rem;
    }
    
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }
    
    .blog-content blockquote {
        border-left: 4px solid #007bff;
        padding-left: 1rem;
        margin: 1rem 0;
        font-style: italic;
        color: #6c757d;
    }
</style>
@endpush

@push('script')
<script>
    function deleteBlog(blogId) {
        if (confirm('@lang("Are you sure you want to delete this blog post?")')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('user/blog') }}/${blogId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Like functionality
    $('.like-btn').on('click', function() {
        var btn = $(this);
        var blogId = btn.data('id');
        
        $.ajax({
            url: '{{ route("user.blog.like", "") }}/' + blogId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#likes-count').text(response.likes);
                    btn.html('<i class="fas fa-heart"></i> @lang("Liked")');
                    btn.removeClass('btn--primary').addClass('btn--success');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
</script>
@endpush 