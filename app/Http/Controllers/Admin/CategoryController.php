<?php

namespace App\Http\Controllers\Admin;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = 'Category';
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('title','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         //
         $this->validate($request, [
            'name' => 'required|unique:categories'
        ]);

        $category = Category::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-') 
        ]);

        if($category){
            //redirect dengan pesan sukses
            return redirect()->route('category.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('category.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function berita($category_id){    
        $posts = Post::where('category_id', $category_id)->latest()->paginate(10);
        $title = "Berita";
        return view('admin.categories.berita', compact('title', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name,'.$category->id
        ]);

        $category = Category::findOrFail($category->id);
        $category->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-') 
        ]);

        if($category){
            //redirect dengan pesan sukses
            return redirect()->route('category.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('category.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        if($category){
            //redirect dengan pesan sukses
            return redirect()->route('category.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('category.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
