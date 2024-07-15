<?php

namespace App\Http\Controllers\Home;
use App\Models\Post;
use App\Models\Photo;
use App\Models\Category;
use App\Models\Relatedlink;
use App\Models\Tag;
use App\Models\Slider;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class HomeController extends Controller
{
    //
    public function index(){
        $sambutan = Post::join('categories', 'categories.id', '=', 'posts.category_id')
                    ->where('categories.name','sambutan')
                    ->first(['posts.*', 'categories.name']);
        $pengumuman = Post::join('categories', 'categories.id', '=', 'posts.category_id')
                    ->where('categories.name','pengumuman')->orderBy('id')
                    ->first(['posts.*', 'categories.name']);
        $berita = Post::join('categories', 'categories.id', '=', 'posts.category_id')
                    ->where('categories.name','berita')->orderBy('posts.id')->limit(3)
                    ->get(['posts.*', 'categories.name']);
        $profil = Post::join('categories', 'categories.id', '=', 'posts.category_id')
                    ->where('categories.name','profil')->orderBy('posts.id')->limit(1)
                    ->get(['posts.*', 'categories.name']);
              		//->join('tags', 'tags.id', '=', 'posts.tag_id')
              		//->get(['post.*', 'categories.name']);
              		//->join('tags', 'tags.id', '=', 'posts.tag_id')
              		//->get(['post.*', 'categories.name']);
                    
       //$sambutan = Post::where('category_id',4)->first();
       //$pengumuman = Post::where('category_id',5)->orderBy('id')->limit(1)->get();
       $link = Relatedlink::where('position','modals')->orderBy('id')->limit(1)->get();
       $linktop = Relatedlink::where('position','top')->orderBy('id')->limit(4)->get();
       $linkbottom = Relatedlink::where('position','bottom')->orderBy('id')->limit(8)->get();
       $linkside = Relatedlink::where('position','side')->orderBy('id')->limit(1)->get();
       $foto = Photo::latest()->orderByDesc('id')->limit(3)->get();
       
       $slidera = Slider::latest()->orderByDesc('id')->first();
       $slider = Slider::where('id', '!=', $slidera->id)->orderBy('id')->limit(4)->get();
        return view('home.index',compact(
        'sambutan',
        'pengumuman',
        'berita',
        'link','linktop','linkbottom','linkside',
        'slider',
        'slidera',
        'foto',
        'profil'

    ));
    }
    public function kontak(){
        return view('home.kontak');
    }
}