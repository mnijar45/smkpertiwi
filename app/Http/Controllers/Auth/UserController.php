<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Users";
        $roles = Role::all();
        $users = User::latest()->paginate(10);
        return view('auth.user.index', compact('title','users','roles'));
        //
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
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role_id' => 'required'
            ]);
            $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role_id')
            ]);
            if($user){
                //redirect dengan pesan sukses
                return redirect()->route('user.index')->with(['success' => 'Data Berhasil Disimpan!']);
            }else{
                //redirect dengan pesan error
                return redirect()->route('user.index')->with(['error' => 'Data Gagal Disimpan!']);
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

    public function update(Request $request, User $user)
 {
 $this->validate($request, [
 'name' => 'required',
 'email' => 'required|unique:users,email,' . $user->id,
 'password' => 'required',
 'role_id' => 'required'
 ]);
 $user = User::findOrFail($user->id);
 $user->update([
 'name' => $request->input('name'),
 'email' => $request->input('email'),
 'password' => bcrypt($request->input('password')),
 'role_id' => $request->input('role_id')
 ]);
 if($user){
    //redirect dengan pesan sukses
    return redirect()->route('user.index')->with(['success' => 'Data Berhasil Diupdate!']);
}else{
    //redirect dengan pesan error
    return redirect()->route('user.index')->with(['error' => 'Data Gagal Diupdate!']);
}
 }
 public function destroy($id)
 {
     $user = User::findOrFail($id);
     $user->delete();

     if($user){
         //redirect dengan pesan sukses
         return redirect()->route('user.index')->with(['success' => 'Data Berhasil Dihapus!']);
     }else{
         //redirect dengan pesan error
         return redirect()->route('user.index')->with(['error' => 'Data Gagal Dihapus!']);
     }
 }
}
