@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">@lang('All Blog Posts')</h4>
            <a href="{{ route('user.blog.create') }}" class="btn btn--primary">
                <i class="fas fa-plus"></i> @lang('Create New Post')
            </a>
        </div>

        @if($blogs->count() > 0)
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card custom--card h-100">
                            <div class="card-body d-flex flex-column">
                                @if($blog->image)
                                    <div class="blog-image mb-3">
                                        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-fluid rounded" style="max-height: 120px; width: 100%; object-fit: cover;">
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">
                                        <a href="{{ route('user.blog.show', $blog->slug) }}" class="text-decoration-none">
                                            {{ Str::limit($blog->title, 40) }}
                                        </a>
                                    </h5>
                                    <span class="badge bg-success">
                                        {{ ucfirst($blog->status) }}
                                    </span>
                                </div>
                                
                                <p class="card-text text-muted flex-grow-1">
                                    {{ $blog->excerpt }}
                                </p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> {{ $blog->user->fullname }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-heart"></i> {{ $blog->likes }}
                                        </small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> {{ $blog->formatted_date }}
                                        </small>
                                        
                                        <a href="{{ route('user.blog.show', $blog->slug) }}" 
                                           class="btn btn-sm btn--primary">
                                            <i class="fas fa-eye"></i> @lang('Read More')
                                        </a>
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
                <h5 class="text-muted">@lang('No blog posts available')</h5>
                <p class="text-muted">@lang('Be the first to share your thoughts by creating a blog post!')</p>
                <a href="{{ route('user.blog.create') }}" class="btn btn--primary">
                    <i class="fas fa-plus"></i> @lang('Create Your First Post')
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 