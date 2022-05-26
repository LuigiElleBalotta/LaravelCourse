<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use DB;

class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $queryBuilder = DB::table('albums')->orderBy('id', 'desc');

        if($request->has('id')) {
            $queryBuilder->where('id', '=', $request->input('id'));
        }

        if($request->has('album_name')) {
            $queryBuilder->where('album_name', 'like', "%".$request->input('album_name')."%");
        }

        $albums = $queryBuilder->get();

        return view('albums.albums', ['albums' => $albums]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('albums.createalbum');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['album_name', 'description']);
        $data['user_id'] = 1;
        $data['album_thumb'] = '/';

        
        $res = DB::table('albums')->insert($data);

        $message = "Album \"". $data["album_name"]."\" ";
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
        $sql = 'SELECT * FROM albums WHERE id = :id';
        return DB::select($sql, [ 'id' => $album->id ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        $sql = 'SELECT * FROM albums WHERE id = :id';
        $albumEdit = DB::select($sql, [ 'id' => $album->id ]);
        return view('albums.editalbum', [ 'album' => $albumEdit[0] ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $album)
    {
        $data = $request->only(['album_name', 'description']);
        $res = DB::table('albums')->where('id', '=', $album)->update($data);

        $message = "Album con id=$album ";
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
    public function destroy(/*Album $album => ritorna l'istanza di album, se non specifico il tipo $album è l'id*/int $album)
    {
        // $res = DB::table('albums')->delete($album);
        $res = DB::table('albums')->where('id', '=', $album)->delete();

        return $res;
    }

    public function delete(int $album)
    {
        $res = DB::table('albums')->where('id', '=', $album)->delete();

        return $res;
    }
}
