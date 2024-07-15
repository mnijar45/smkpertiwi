<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sliders = Slider::latest()->paginate(10);
        $title = "Slider";
        return view('admin.sliders.index', compact('title', 'sliders'));
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
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //upload image
        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('images/slider'), $imageName);

        $slider = Slider::create([
            'image' => $imageName,
            'title' => $request->input('title')

        ]);

        return redirect()->route('slider.index')->with('success', 'Data Gambar created successfully.');
    }

    public function update(Request $request, Slider $slider)
    {
        //
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required'
        ]);

        if ($request->file('image') == "") {

            $slider = slider::findOrFail($slider->id);
            $slider->update([
                'title' => $request->input('title'),
            ]);
        } else {
            unlink(public_path('images/slider' . $slider->image));
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('images/slider'), $imageName);

            $slider = slider::findOrFail($slider->id);
            $slider->update([
                'image' => $imageName,
                'title' => $request->input('title')

            ]);

        }


        return redirect()->route('slider.index')->with('success', 'slider updated successfully');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        //
        $image_path = public_path('images/slider' . $slider->image);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        $slider->delete();

        return redirect()->route('slider.index')->with('success', 'Image deleted successfully');
    }
}