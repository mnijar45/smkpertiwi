<?php

namespace App\Http\Controllers\Admin;
use App\Models\Tag;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title='Tags';
        $tags = Tag::latest()->Paginate(10);
        return view('admin.tags.index', compact(['title','tags']));
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
        $this->validate($request, [
            'name' => 'required|unique:tags'
        ]);

        $tag = Tag::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-') 
        ]);

        if($tag){
            //redirect dengan pesan sukses
            return redirect()->route('tag.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('tag.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $this->validate($request, [
            'name' => 'required|unique:tags,name,'.$tag->id
        ]);

        $tag = Tag::findOrFail($tag->id);
        $tag->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-') 
        ]);

        if($tag){
            //redirect dengan pesan sukses
            return redirect()->route('tag.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('tag.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        if($tag){
            //redirect dengan pesan sukses
            return redirect()->route('tag.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('tag.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
