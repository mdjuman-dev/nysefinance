<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        
        abort('404');
        
        $pageTitle = 'My Blog Posts';
        $blogs = Blog::where('user_id', Auth::id())->latest()->paginate(10);
        return view('Template::user.blog.index', compact('pageTitle', 'blogs'));
    }

    public function create()
    {
         abort('404');
         
        $pageTitle = 'Create Blog Post';
        return view('Template::user.blog.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        
         abort('404');
         
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!Blog::canUserCreateToday(Auth::id())) {
            $notify[] = ['error', 'You can only create one blog post per day'];
            return back()->withNotify($notify);
        }

        $blog = new Blog();
        $blog->user_id = Auth::id();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->slug = Blog::generateSlug($request->title);
        $blog->status = 'published';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/blog-images', $filename);
            $blog->image = $filename;
        }

        $blog->save();

        $notify[] = ['success', 'Blog post created successfully'];
        return redirect()->route('user.blog.index')->withNotify($notify);
    }

    public function show($slug)
    {
        
         abort('404');
         
         
        $blog = Blog::where('slug', $slug)->with(['user', 'comments.user', 'comments.replies.user'])->firstOrFail();
        $pageTitle = $blog->title;
        return view('Template::user.blog.show', compact('pageTitle', 'blog'));
    }

    public function edit($id)
    {
         abort('404');
         
         
        $blog = Blog::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pageTitle = 'Edit Blog Post';
        return view('Template::user.blog.edit', compact('pageTitle', 'blog'));
    }

    public function update(Request $request, $id)
    {
        
         abort('404');
         
        $blog = Blog::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog->title = $request->title;
        $blog->content = $request->content;

        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($blog->image) {
                \Storage::disk('public')->delete('blog-images/' . $blog->image);
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/blog-images', $filename);
            $blog->image = $filename;
        }

        $blog->save();

        $notify[] = ['success', 'Blog post updated successfully'];
        return redirect()->route('user.blog.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $blog = Blog::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($blog->image) {
            \Storage::disk('public')->delete('blog-images/' . $blog->image);
        }
        
        $blog->delete();

        $notify[] = ['success', 'Blog post deleted successfully'];
        return redirect()->route('user.blog.index')->withNotify($notify);
    }

    public function all()
    {
        $pageTitle = 'All Blog Posts';
        $blogs = Blog::published()->with('user')->latest()->paginate(12);
        return \Inertia\Inertia::render('User/Blogs', [
            'blogs' => \App\Support\InertiaData::paginate($blogs, fn ($b) => [
                'id'      => $b->id,
                'title'   => $b->title,
                'content' => $b->content,
                'image'   => $b->image ? $b->image_url : null,
                'author'  => $b->user->fullname ?? '',
                'likes'   => (int) $b->likes,
                'date'    => \App\Support\InertiaData::date($b->created_at),
            ]),
            'urls' => ['like' => route('user.blog.like', '__ID__'), 'unlike' => route('user.blog.unlike', '__ID__')],
        ]);
    }

    public function like($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->incrementLikes();
        
        return response()->json([
            'success' => true,
            'likes' => $blog->likes,
            'message' => 'Blog post liked successfully'
        ]);
    }

    public function unlike($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->decrementLikes();
        
        return response()->json([
            'success' => true,
            'likes' => $blog->likes,
            'message' => 'Blog post unliked successfully'
        ]);
    }

    public function addComment(Request $request, $id)
    {

 abort('404');
 
 
 
        $request->validate([
            'comment' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:blog_comments,id'
        ]);

        $blog = Blog::findOrFail($id);

        $comment = new BlogComment();
        $comment->blog_id = $blog->id;
        $comment->user_id = Auth::id();
        $comment->comment = $request->comment;
        $comment->parent_id = $request->parent_id;
        $comment->status = 'approved';
        $comment->save();

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' => 'Comment added successfully'
        ]);
    }

    public function deleteComment($id)
    {
         abort('404');
         
        $comment = BlogComment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
