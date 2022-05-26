<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;
use Validator;

class PhotosController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
            // ->except(['index', 'show']);
            // ->only('index', 'show');

        $this->authorizeResource(Photo::class);
    }

    protected $rules = [
        'album_id' => 'required|integer|exists:albums,id',
        'name' => 'required',
        'description' => 'required',
        'img_path' => 'required|image'
    ];

    protected $messages = [
        'album_id.required' => 'Il campo album è obbligatorio',
        'name.required' => 'Il campo Nome è obbligatorio.',
        'description.required' => 'Il campo Descrizione è obbligatorio.',
        'img_path.required' => 'Il campo Immagine è obbligatorio.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Photo::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $album = $req->album_id ? Album::findOrFail($req->album_id) : new Album();

        $photo = new Photo();
        $albums = $this->getAlbums();

        return view('images.editimage', compact('album', 'photo', 'albums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);

        $photo = new Photo();
        $photo->album_id =    $request->input('album_id');
        $photo->name =        $request->input('name');
        $photo->description = $request->input('description');

        $this->processFile($photo);

        $photo->save();

        return redirect(route('albums.images', $photo->album_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        return $photo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        $albums = $this->getAlbums();
        $album = $photo->album;
        return view('images.editimage', compact('album', 'albums', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $rules = $this->rules;
        unset($rules['img_path']);
        $this->validate($request, $rules, $this->messages);

        $this->processFile($photo);
        $photo->name = $request->input('name');
        $photo->description = $request->input('description');

        $res = $photo->save();

        $message = "Foto con id=$photo->id ";
        $message .= $res ? 'aggiornata' : 'non aggiornata';
        session()->flash('message', $message);

        return redirect()->route('albums.images', $photo->album_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        // $res = Photo::findOrFail($id)->delete();

        $res = $photo->delete();
        if($res) {
            $this->deleteFile($photo);
        }

        return $res;
    }

    public function processFile(Photo $photo, Request $request = null ): bool
    {
        if(!$request) {
            $request = request();
        }

        if( !$request->hasFile('img_path')) {
            return false;
        }

        $file = $request->file('img_path');

        if($file->isValid()) {
            $imgName = preg_replace('@[a-z0-9]@', '_', $photo->name);
            $filename = $imgName.'.'.$file->extension();
            $filename = $file->storeAs(env('IMG_DIR').$photo->album_id, $filename);

            $photo->img_path = $filename;

            return true;
        }
        else {
            return false;
        }
    }

    public function deleteFile(Photo $photo) {
        $disk = config('filesystems.default');

        if($photo->img_path && Storage::disk($disk)->has($photo->img_path)) {
            return Storage::disk($disk)->delete($photo->img_path);
        }

        return false;
    }

    public function getAlbums() {
        return Album::whereUserId(Auth::id())->orderBy('id', 'DESC')->get();
    }
}
