<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $posts = Post::latest()->paginate(10);
        $title = "Berita";
        return view('admin.posts.index', compact('title', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::latest()->get();
        $tags = Tag::latest()->get();
        $title = "Create Post";
        return view('admin.posts.create', compact('title', 'tags', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required'
        ]);

        //upload image
        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('images/post'), $imageName);

        $post = Post::create([
            'image' => $imageName,
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title'), '-'),
            'category_id' => $request->input('category_id'),
            'content' => $request->input('content')

        ]);
        //assign tags
        $post->tags()->attach($request->input('tags'));
        $post->save();
        if ($post) {
            //redirect dengan pesan sukses
            return
                redirect()->route('post.index')->with([
                    'success' => 'Data Berhasil Disimpan!'
                ]);
        } else {
            //redirect dengan pesan error
            return redirect()->route('post.index')->with([
                'error'
                => 'Data Gagal Disimpan!'
            ]);
        }

    }
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $title = "detail Post";
        $tags = DB::table('post_tag')
                ->join('tags','tags.id','=','post_tag.tag_id')
                ->where('post_tag.post_id', $post->id)
                ->select('post_tag.*','tags.name')
                ->get();
        
        return view('admin.posts.show', compact('post','title','tags'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $title = "Edit";
        $tags = Post::join('categories', 'categories.id', '=', 'posts.category_id')
        ->join('post_tag', 'post_tag.post_id', '=', 'posts.id')
        ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
        ->where('posts.id',$post->id)
       ->get(['posts.*', 'tags.name']);
       $tag2 = Post::join('categories', 'categories.id', '=', 'posts.category_id')
                 ->join('post_tag', 'post_tag.post_id', '=', 'posts.id')
                 ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                 ->where('posts.category_id',2)
                 ->get(['posts.*', 'tags.name']);
        $categories = Category::latest()->get();
        return view('admin.posts.edit', compact(
            'post',
            'tags',
            'tag2',
            'categories',
            'title'
        )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required|unique:posts,title,' . $post->id,
            'category_id' => 'required',
            'content' => 'required',
        ]);
        if ($request->file('image') == "") {
            $post = Post::findOrFail($post->id);
            $post->update([
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('title'), '-'),
                'category_id' => $request->input('category_id'),
                'content' => $request->input('content')
            ]);
        } else {
            $image_path = public_path('images/post/' . $post->image);
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/post'), $imageName);
            $post = Post::findOrFail($post->id);
            $post->update([
                'image' => $imageName,
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('title'), '-'),
                'category_id' => $request->input('category_id'),
                'content' => $request->input('content')
            ]);
        }
        return redirect()->route('post.index')->with('success', 'Posted Udated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $image_path = public_path('images/post/' . $post->image);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        DB::table('post_tag')->where('post_id', $post->id)->delete();
        $post->delete();

        return redirect()->route('post.index')->with('success', 'Image deleted successfully');
    }

}