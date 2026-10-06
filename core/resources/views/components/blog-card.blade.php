@props(['blog'])

<div class="blog-card" data-blog-id="{{ $blog->id }}">
    <div class="blog-card__header">
        <div class="blog-card__user-info">
            <div class="blog-card__avatar">
                @if($blog->user->image)
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $blog->user->image) }}" alt="{{ $blog->user->username }}">
                @else
                    <div class="blog-card__avatar-placeholder">
                        {{ strtoupper(substr($blog->user->username, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="blog-card__user-details">
                <h6 class="blog-card__username">{{ $blog->user->username }}</h6>
                <span class="blog-card__timestamp">{{ $blog->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    <div class="blog-card__content">
        @if($blog->image)
            <div class="blog-card__image">
                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">
            </div>
        @endif
        
        <h5 class="blog-card__title">{{ Str::limit($blog->title, 50) }}</h5>
        <p class="blog-card__text">{{ Str::limit($blog->content, 200) }}</p>
    </div>

    <div class="blog-card__actions">
        <div class="blog-card__action-group">
            <button class="blog-card__action-btn like-btn" data-blog-id="{{ $blog->id }}">
                <i class="fas fa-heart {{ $blog->likes > 0 ? 'text-danger' : '' }}"></i>
                <span class="like-count">{{ $blog->likes }}</span>
            </button>
            
            <button class="blog-card__action-btn comment-btn" data-blog-id="{{ $blog->id }}">
                <i class="fas fa-comment"></i>
                <span class="comment-count">{{ $blog->comments->count() }}</span>
            </button>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="blog-card__comments" id="comments-{{ $blog->id }}">
        <!-- Debug: Blog ID {{ $blog->id }}, Comments Count: {{ $blog->comments->count() }}, Comments: {{ $blog->comments->pluck('comment')->join(', ') }}, Comments Loaded: {{ $blog->relationLoaded('comments') ? 'Yes' : 'No' }} -->
        <div class="comments-list" id="comments-list-{{ $blog->id }}">
            @if($blog->comments->count() > 0)
                @foreach($blog->comments as $comment)
                <div class="comment-item" data-comment-id="{{ $comment->id }}">
                    <div class="comment-avatar">
                        @if($comment->user->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $comment->user->image) }}" alt="{{ $comment->user->username }}">
                        @else
                            <div class="comment-avatar-placeholder">
                                {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-username">{{ $comment->user->username }}</span>
                            <span class="comment-timestamp">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="comment-text">{{ Str::limit($comment->comment, 150) }}</p>
                        @if($comment->user_id == auth()->id())
                            <button class="comment-delete-btn" data-comment-id="{{ $comment->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-comments" style="text-align: center; padding: 20px; color: #888;">
                    <p>No comments yet. Be the first to comment!</p>
                    <small style="color: #666;">(Debug: Comments count is {{ $blog->comments->count() }})</small>
                </div>
            @endif
        </div>
        
        <div class="comment-form">
            <div class="comment-input-group">
                <input type="text" class="comment-input" placeholder="Write a comment..." data-blog-id="{{ $blog->id }}" maxlength="200" onkeypress="if(event.keyCode==13) console.log('Enter pressed on comment input for blog {{ $blog->id }}')">
                <button class="comment-submit-btn" data-blog-id="{{ $blog->id }}" onclick="console.log('Comment submit button clicked for blog {{ $blog->id }}')">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>
