<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Posts Management';
        $blogs = Blog::with('user')->latest()->paginate(20);
        
        return view('admin.blog.index', compact('pageTitle', 'blogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        $pageTitle = 'Edit Post';
        
        return view('admin.blog.edit', compact('pageTitle', 'blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:200',
            'likes' => 'required|integer|min:0',
            'status' => 'required|in:published,draft'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->likes = $request->likes;
        $blog->status = $request->status;
        $blog->save();

        $notify[] = ['success', 'Post updated successfully!'];
        return redirect()->route('admin.blog.index')->withNotify($notify);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        
        // Delete image file if exists
        if ($blog->image && file_exists(public_path('storage/blog-images/' . $blog->image))) {
            unlink(public_path('storage/blog-images/' . $blog->image));
        }
        
        $blog->delete();

        $notify[] = ['success', 'Post deleted successfully!'];
        return redirect()->route('admin.blog.index')->withNotify($notify);
    }

    /**
     * Update likes count only.
     */
    public function updateLikes(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'likes' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid likes count'
            ]);
        }

        $blog->likes = $request->likes;
        $blog->save();

        return response()->json([
            'success' => true,
            'likes' => $blog->likes,
            'message' => 'Likes updated successfully!'
        ]);
    }
} 