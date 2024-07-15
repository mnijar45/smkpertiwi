<?php

namespace App\Http\Controllers\Admin;
use App\Models\Relatedlink;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RelatedlinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = Relatedlink::latest()->paginate(10);
        $title = "Related Links";
        return view('admin.links.index', compact('title', 'links'));
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
        $this->validate($request, [
            'link' =>  'required',
            'position' =>  'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //upload image
        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('images/link'), $imageName);

        $link = Relatedlink::create([
            'image' => $imageName,
            'link' =>  $request->input('link'),
            'position' =>  $request->input('position')

        ]);

        return redirect()->route('link.index')->with('success', 'Data Referesi Link created successfully.');
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Relatedlink $link)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'required',
            'position' =>  'required'
        ]);

        if ($request->file('image') == "") {

            $link = Relatedlink::findOrFail($link->id);
            $link->update([
                'link' => $request->input('link'),
               'position' =>  $request->input('position')
            ]);
        } else {
            unlink(public_path('images/link' . $link->image));
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('images/link'), $imageName);

            $link = Relatedlink::findOrFail($link->id);
            $link->update([
                'image' => $imageName,
                'link' => $request->input('link'),
                'position' =>  $request->input('position')

            ]);

        }


        return redirect()->route('link.index')->with('success', 'slider updated successfully');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Relatedlink $link)
    {
        $image_path = public_path('images/link' . $link->image);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        $link->delete();

        return redirect()->route('link.index')->with('success', 'Image deleted successfully');
    }
}
