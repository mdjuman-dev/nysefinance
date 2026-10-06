<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'slug',
        'status',
        'likes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the blog post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id')->approved();
    }

    /**
     * Get all comments including replies for the blog post.
     */
    public function allComments()
    {
        return $this->hasMany(BlogComment::class)->approved();
    }

    /**
     * Scope a query to only include published blogs.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include blogs from today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Check if user can create a blog post today.
     */
    public static function canUserCreateToday($userId)
    {
        return !static::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->exists();
    }

    /**
     * Generate slug from title.
     */
    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Increment like count.
     */
    public function incrementLikes()
    {
        $this->increment('likes');
    }

    /**
     * Decrement like count.
     */
    public function decrementLikes()
    {
        $this->decrement('likes');
    }

    /**
     * Get excerpt of content.
     */
    public function getExcerptAttribute()
    {
        return Str::limit($this->content, 150);
    }

    /**
     * Get image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/blog-images/' . $this->image);
        }
        return null;
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }
} 