<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use DB;

class AlbumsControllerOLD extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // return Album::all();
        $sql = 'SELECT * FROM albums WHERE 1=1';
        $where = [];

        if($request->has('id')) {
            // $where .= 'AND id = '.(int)$request->get('id');
            $where['id'] = (int)$request->get('id');
            // $sql .= " AND id = ?";
            $sql .= " AND id = :id";
        }

        if($request->has('album_name')) {
            $where['album_name'] = $request->get('album_name');
            // $sql .= " AND album_name = ?";
            $sql .= " AND album_name = :album_name";
        }

        $sql .= " ORDER BY id DESC";
        // Dial Dump (debug)
        // dd($sql);

        // return DB::select($sql, array_values($where));
        // RITORNO JSON
        // return DB::select($sql, $where);

        // RITORNO VISTA
        return view('albums.albums', [ 'albums' => DB::select($sql, $where) ]);
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
        $data['album_thumb'] = '';

        $query = 'INSERT INTO albums (album_name, description, user_id, album_thumb) VALUES ( :album_name, :description, :user_id, :album_thumb );';

        $res = DB::insert($query, $data);

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
        $data['id'] = $album;
        // dd($request->all());

        $query = 'UPDATE albums SET album_name = :album_name, description = :description WHERE id = :id';
        $res = DB::update($query, $data);

        // dd($res);
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
        $sql = "DELETE FROM albums WHERE id = :id";
        return DB::delete($sql, ['id' => $album]);
    }

    public function delete(int $album)
    {
        $sql = "DELETE FROM albums WHERE id = :id";
        $deletedRecords = DB::delete($sql, ['id' => $album]);
        
        // return redirect()->back();
        // Dato che stiamo facendo una chiamata AJAX non facciamo un redirect
        return $deletedRecords;
    }
}
