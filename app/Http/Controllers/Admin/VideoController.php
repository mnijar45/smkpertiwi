<?php

namespace App\Http\Controllers\Admin;
use App\Models\Video;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $videos = Video::latest()->paginate(9);
        $title = "Videos";
        return view('admin.videos.index', compact('title','videos'));
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
            'title' => 'required'
        ]);

        $video = Video::create([
            'title' => $request->input('title'),
            'embed' => $request->input('embed') 
        ]);

        if($video){
            //redirect dengan pesan sukses
            return redirect()->route('video.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('video.index')->with(['error' => 'Data Gagal Disimpan!']);
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
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, video $video)
    {
        $this->validate($request, [
            'title' => 'required',
            'embed' => 'required'
        ]);

        $video = Video::findOrFail($video->id);
        $video->update([
            'title' => $request->input('title'),
            'embed' => $request->input('embed') 
        ]);

        if($video){
            //redirect dengan pesan sukses
            return redirect()->route('video.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('video.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        if($video){
            //redirect dengan pesan sukses
            return redirect()->route('video.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('video.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
