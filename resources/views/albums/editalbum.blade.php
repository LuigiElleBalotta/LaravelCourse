
@extends('templates.layout')
@section('content')

@php
/**
* @var $album App\Models\Album;
*/
@endphp
<h1>EDIT ALBUM {{ $album->album_name }}</h1>
@include('partials.inputerrors')
<form method="post" action="{{ route('albums.update', [ 'album' => $album->id ]) }}" enctype="multipart/form-data">
    @method('PATCH')
    <!--
    {{ method_field('PATCH') }}
    <input type="hidden" value="PATCH" name="_method" />
    -->
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="user_id" id="user_id" value="{{ $album->user_id }}" />
    <div class="form-group">
        <label for="album_name">Name</label>
        <input class="form-control" name="album_name" id="album_name" value="{{ $album->album_name }}" />
    </div>

    @include('albums.partials.fileupload')

    @include('albums.partials.category_combo')

    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" id="description">{{ $album->description }}</textarea>
    </div>
    <div class="form-group">
        <div class="d-flex justify-content-end editbuttons">
            <button class="btn btn-primary mx-1">INVIA</button>
            <a class="btn btn-outline-info mx-1" href="{{ route('albums.index') }}">Indietro</a>
            <a class="btn btn-outline-success mx-1" href="{{ route('albums.images', $album->id) }}">Immagini</a>
        </div>
    </div>
</form>
@endsection
