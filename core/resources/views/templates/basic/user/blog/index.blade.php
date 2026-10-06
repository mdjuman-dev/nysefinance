@extends($activeTemplate . 'layouts.master')


@push('ip-css')

    <style>
        .cs-sm-btn{
            padding: 5px !important;
            height: 30px !important;
            margin: 0px 7px !important;
        }
        .card.custom--card.blog-card{
            padding: 0px !important;
        }
    </style>

@endpush



@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">@lang('Posts')</h4>
            <a href="{{ route('user.blog.create') }}" class="btn btn--primary">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        @if($blogs->count() > 0)
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="col-lg-6 col-md-6 mb-4">
                                        <div class="card custom--card blog-card">
                    <div class="card-body">
                        @if($blog->image)
                            <div class="blog-image mb-3">
                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">
                                <a href="#" class="text-decoration-none text-white blog-title">
                                    {{ Str::limit($blog->title, 50) }}
                                </a>
                            </h5>
                            <span class="badge bg-{{ $blog->status == 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </div>

                        <p class="card-text text-muted blog-excerpt">
                            {{ $blog->excerpt }}
                        </p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <span class="ms-3">
                                            <i class="fas fa-heart"></i> {{ $blog->likes }} likes
                                        </span>
                                    </small>

                                    <div class="btn-group" role="group">
                                        <a href="{{ route('user.blog.edit', $blog->id) }}"
                                           class="btn btn-sm btn--info btn-sm cs-sm-btn">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn--danger btn-sm cs-sm-btn ml-2"
                                                onclick="deleteBlog({{ $blog->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $blogs->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">@lang('No blog posts yet')</h5>
                <p class="text-muted">@lang('Start sharing your thoughts by creating your first blog post!')</p>
                <a href="{{ route('user.blog.create') }}" class="btn btn--primary">
                    <i class="fas fa-plus"></i> @lang('Create Your First Post')
                </a>
            </div>
        @endif
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
    .blog-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: 1px solid #e9ecef;
    }

    .blog-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .blog-title {
        color: #333;
        font-weight: 600;
    }

    .blog-title:hover {
        color: #007bff;
    }

    .blog-excerpt {
        line-height: 1.6;
        color: #6c757d;
    }

    .btn-group .btn {
        margin-left: 2px;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
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
</script>
@endpush
