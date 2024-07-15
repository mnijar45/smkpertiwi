<?php

namespace App\Http\Controllers\Admin;
use App\Models\Photo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title ='Gallery';
        $photos =Photo::latest()->paginate(12);
        return view('admin.galleries.index',compact('title','photos'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'caption' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //upload image
        $imageName = time().'.'.$request->image->extension();  
         
        $request->image->move(public_path('images'), $imageName);
        
        $photo = Photo::create([
            'image'       => $imageName,
            'caption'       => $request->input('caption')
            
        ]);

       
    
    
        return redirect()->route('photo.index')
                        ->with('success','Data Gambar created successfully.');
    }
      
    
    public function update(Request $request, Photo $photo)
    {
        //
        $this->validate($request,[
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption' => 'required'
        ]);

        if ($request->file('image') == "") {
        
            $photo = Photo::findOrFail($photo->id);
            $photo->update([
                'caption'       => $request->input('caption'),
            ]);
        }
        else{
            unlink(public_path('images/galeri/'.$photo->image));
            $imageName = time().'.'.$request->image->extension();  
         
            $request->image->move(public_path('images'), $imageName);

            $photo = Photo::findOrFail($photo->id);
            $photo->update([
                'image'       => $imageName,
                'caption'       => $request->input('caption')
                
            ]);

        }
       
      
        return redirect()->route('photo.index')->with('success','Photo updated successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        //
      $image_path = public_path('images/galeri/'.$photo->image);
      if(file_exists($image_path)){
        unlink($image_path);
      }
        $photo->delete();
         
        return redirect()->route('photo.index')->with('success','Image deleted successfully');
    }
}
