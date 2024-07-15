<?php

namespace App\Http\Controllers\Admin;
use App\Models\Event;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title ="Agenda";
        $events = Event::latest()->paginate(10);
        return view('admin.events.index',compact('title','events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title ="Agenda";
        return view('admin.events.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required',
            'content' => 'required',
            'location' => 'required',
            'date' => 'required'
            ]);
            $event = Event::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-'),
            'content' => $request->input('content'),
            'location' => $request->input('location'),
            'date' => $request->input('date')
            ]);
            if($event){
                //redirect dengan pesan sukses
                return redirect()->route('event.index')->with(['success' => 'Data Berhasil Disimpan!']);
            }else{
                //redirect dengan pesan error
                return redirect()->route('event.index')->with(['error' => 'Data Gagal Disimpan!']);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
        $events =Event::all();
        $title ="Agenda";
        return view('admin.events.show', compact('event','title','events'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
        $title ="Agenda";
        return view('admin.events.edit', compact('event','title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
 {
 $this->validate($request, [
 'name' => 'required',
 'content' => 'required',
 'location' => 'required',
 'date' => 'required'
 ]);
 $event = Event::findOrFail($event->id);
 $event->update([
 'name' => $request->input('name'),
 'slug' => Str::slug($request->input('name'), '-'),
 'content' => $request->input('content'),
 'location' => $request->input('location'),
 'date' => $request->input('date')
 ]);
 if($event){
    //redirect dengan pesan sukses
    return redirect()->route('event.index')->with(['success' => 'Data Berhasil Diupdate!']);
}else{
    //redirect dengan pesan error
    return redirect()->route('event.index')->with(['error' => 'Data Gagal Diupdate!']);
}
 }
 public function destroy($id)
 {
     $event = Event::findOrFail($id);
     $event->delete();

     if($event){
         //redirect dengan pesan sukses
         return redirect()->route('event.index')->with(['success' => 'Data Berhasil Dihapus!']);
     }else{
         //redirect dengan pesan error
         return redirect()->route('event.index')->with(['error' => 'Data Gagal Dihapus!']);
     }
 }
}
