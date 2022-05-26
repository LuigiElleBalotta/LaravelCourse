<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\{Request, Response};
use DB;
use App\Http\Requests\AlbumRequest;


class AlbumsController extends Controller
{

    public function __construct() {
        // $this->authorizeResource(Album::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // dd($request->user());
        $queryBuilder = Album::orderBy('id', 'desc')
                        ->withCount('photos');
                        // ->with('photos');

        $queryBuilder->where('user_id', \Auth::id());

        if($request->has('id')) {
            $queryBuilder->where('id', '=', $request->input('id'));
        }

        if($request->has('album_name')) {
            $queryBuilder->where('album_name', 'like', "%".$request->input('album_name')."%");
        }

        $albums = $queryBuilder->paginate(10);

        return view('albums.albums', ['albums' => $albums]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $album = new Album();
        return view('albums.createalbum', [ 'album' => $album ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlbumRequest $request)
    {
        $album = new Album();
        $album->album_name = $request->input('album_name');
        $album->album_thumb = '';
        $album->description = $request->input('description');
        $album->user_id = \Auth::id();

        $res = $album->save();

        if($res) {
            if($this->processFile($album->id, $request, $album )) {
                $album->save();
            }
        }


        $message = "Album \"". $album->album_name."\" ";
        $message .= $res ? 'created' : 'not created';
        session()->flash('message', $message);
        return redirect()->route('albums.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        return $album;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        return view('albums.editalbum', [ 'album' => $album ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(AlbumRequest $request, Album $album)
    {
        // $data = $request->only(['album_name', 'description']);

        // Test 1 con parametro int $album
        // $res = Album::where('id', '=', $album)->update($data);
        // Test 2 con parametro Album $album
        // $res = $album->update($data);
        // Test 3 per evitare l'errore di mass assignment:
        // $album->album_name = $data['album_name'];
        // $album->description = $data['description'];

        $album->album_name = $request->input('album_name');
        $album->description = $request->input('description');
        $album->user_id = \Auth::id();
        $fileProcessed = $this->processFile($album->id, $request, $album);

        $res = $album->save();

        $message = "Album con nome=$album->album_name ";
        $message .= $res ? 'aggiornato' : 'non aggiornato';
        session()->flash('message', $message);
        return redirect()->route('albums.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(/*Album $album => ritorna l'istanza di album, se non specifico il tipo $album è l'id*/Album $album)
    {
        $thumbNail = $album->album_thumb;

        // $res = Album::where('id', '=', $album)->delete();
        // Album::findOrFail($album)->delete();
        // return Album::destroy($album);
        // Per il prossimo esempio per cancellare togliamo INT dal parametro e mettiamo la sua classe (Album)
        $res = +$album->delete(); // Dato che delete ritorna bool o null, castiamo il risultato a un integer per far funzionare lo script ajax con +.

        if( $res && $thumbNail && \Storage::exists($thumbNail)) {
            \Storage::delete($thumbNail);
        }

        return $res;
    }

    public function delete(Album $album)
    {
        $thumbNail = $album->album_thumb;

        // $res = Album::where('id', '=', $album)->delete();
        // Album::findOrFail($album)->delete();
        // return Album::destroy($album);
        // Per il prossimo esempio per cancellare togliamo INT dal parametro e mettiamo la sua classe (Album)
        $res = +$album->delete(); // Dato che delete ritorna bool o null, castiamo il risultato a un integer per far funzionare lo script ajax con +.

        if( $res && $thumbNail && \Storage::exists($thumbNail)) {
            \Storage::delete($thumbNail);
        }

        return $res;
    }


    // La variabile album è passata per riferimento
    public function processFile($id, Request $request, &$album ): bool
    {
        if( !$request->hasFile('album_thumb')) {
            return false;
        }

        $file = $request->file('album_thumb');

        if($file->isValid()) {
            // Salva il file con un nome random usando un disco specifico (che in questo caso settiamo dall'env)
            // $file->store(env('IMG_DIR') /*, [ 'disk' => 'public' ] */);

            // Salva il file col nome che abbiamo scelto noi
            $filename = $id.'.'.$file->extension();
            $filename = $file->storeAs(env('ALBUM_THUMB_DIR'), $filename);

            $album->album_thumb = $filename;

            return true;
        }
        else {
            return false;
        }
    }

    public function getImages(Album $album) {
        $recordsPerPage = env('IMG_PER_PAGE', 20);

        // Latest() ordina per data creazione DESC
        $images = Photo::where([ 'album_id' => $album->id ])->latest()->paginate($recordsPerPage);

        return view('images.albumimages', compact('album', 'images'));
        // return $images;
    }


}
