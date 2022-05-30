<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use App\Models\Category;
use Illuminate\Http\Request;

class GalleryController extends Controller
{


    public function index() {
        // Mettendo "with" si esegue l'eager loading, che carica preventivamente la relazione prima di restituire il risultato
        // Se NON si dovesse mettere "with" è comunque possibile chiamare $album->categories, ma verrebbe eseguita una query per ogni album
        $albums = Album::with('categories')->latest()->paginate(50);
        return view('gallery.albums')->with(['albums' => $albums, 'category_id' => null]);
    }

    public function showAlbumImages(Album $album) {
        return view('gallery.images', ['images' => Photo::whereAlbumId($album->id)->latest()->paginate(10),
                                            'album' => $album]);
               // ->with('images', Photo::whereAlbumId($album)->latest()->paginate(3));
    }

    public function showCategoryAlbums(Category $category) {
        $albums = $category->albums()->with('categories')->latest()->get();
        return view('gallery.albums')->with(['albums' => $albums, 'category_id' => $category->id]);
    }
}
