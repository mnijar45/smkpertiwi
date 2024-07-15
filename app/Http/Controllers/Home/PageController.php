<?php
namespace App\Http\Controllers\Home;
use App\Models\Post;
use App\Models\Photo;
use App\Models\Video;
use App\Models\Event;
use App\Models\Category;
use App\Models\Relatedlink;
use App\Models\Tag;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PageController extends Controller {
public function sambutan($slug){

   $sambut= Post::where('slug', $slug)->first();
        return view('page.sambutan', compact('sambut'));
    }

public function ppdb(){
    $menuppdb = Post::where('category_id',6)->orderBy('id')->get();
    $ppdb= Post::where('category_id',6)->orderBy('id')->limit(1)->get();
    return view('page.ppdb.index', compact('ppdb','menuppdb'));
    }

public function pages($slug){
        
    $menuppdb = Post::where('category_id',6)->orderBy('id')->get();
        $ppdb= Post::where(['slug'=> $slug, 'category_id'=>6])->first();
             return view('page.ppdb.pages', compact('ppdb','menuppdb'));
         }

//berita
public function berita(){
$title="berita";
$berita = Post::where('category_id',1)->orderByDesc('id')->latest()->paginate(9);
 return view('page.berita.index', compact('berita','title'));
}
public function read($slug){
    $title="berita";
    $linkside = Relatedlink::where('position','side')->first();
    $morenews =  $berita = Post::where('slug','!=', $slug )
                                ->where('category_id','=',1)->limit(5)
                                ->orderByDesc('id')
                                ->get();
   
                $tags = Post::join('categories', 'categories.id', '=', 'posts.category_id')
                 ->join('post_tag', 'post_tag.post_id', '=', 'posts.id')
                 ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                 ->where(['posts.slug'=> $slug , 'category_id'=>1 ])
                ->get(['posts.*', 'tags.name']);
    $berita = Post::where('slug', $slug)->first();
     return view('page.berita.read', compact('berita','linkside','morenews','tags'));
    }

    public function read_profil($slug){
        $title="SMK PERTIWI INDONESIA";
        $linkside = Relatedlink::where('position','side')->first();
        $morenews  = Post::where('slug','!=', $slug )
                                    ->where('category_id','=',2)->limit(10)
                                    ->orderByDesc('id')
                                    ->get();
       
                    $tags = Post::join('categories', 'categories.id', '=', 'posts.category_id')
                     ->join('post_tag', 'post_tag.post_id', '=', 'posts.id')
                     ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                     ->where(['posts.slug'=> $slug , 'category_id'=>2 ])
                    ->get(['posts.*', 'tags.name']);
        $profil = Post::where('slug', $slug)->first();
         return view('page.profil.read', compact('profil','linkside','morenews','tags','title'));
        }


        // galeri

        public function galeri(){
            $title="GALERI SMK PERTIWI INDONESIA";
            $foto = Photo::latest()->paginate(9);
             return view('page.galeri.index', compact('foto','title'));
            }
            public function agenda(){
                $title="AGENDA KEGIATAN SMK PERTIWI INDONESIA";
                $agenda = Event::latest()->paginate(9);
                 return view('page.agenda.index', compact('agenda','title'));
                }

                public function video(){
                    $title="VIDEO KEGIATAN SMK PERTIWI INDONESIA";
                    $video = Video::latest()->paginate(9);
                     return view('page.video.index', compact('video','title'));
                    }
}