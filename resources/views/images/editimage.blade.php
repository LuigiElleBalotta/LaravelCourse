
@extends('templates.layout')
@section('content')



<h1>
    @if($photo->id)
        Edit Photo "{{ $photo->name }}"
    @else
        New Photo
    @endif
</h1>

@include('partials.inputerrors')

@if($photo->id)
<form method="post" action="{{ route('photos.update', $photo->id) }}" enctype="multipart/form-data">
    
    {{ method_field('PATCH') }}
@else
<form method="post" action="{{ route('photos.store') }}" enctype="multipart/form-data">
@endif
    @csrf

    <div class="form-group">
        
        <select name="album_id" id="album_id" class="form-control">
            <option value="">Select album</option>
            @foreach($albums as $item)
            <option value="{{ $item->id }}" {{ $item->id == $album->id ? 'selected' : '' }}>{{ $item->album_name }}</option>
            @endforeach
        </select>
        
    </div>
    {{-- <input type="hidden" name="album_id" value="{{ $photo->album_id ?: $album->id }}" /> --}}

    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" name="name" id="name" value="{{ $photo->name }}" />
    </div>
    @include('images.partials.fileupload')
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" id="description">{{ $photo->description }}</textarea>
    </div>
    <div class="form-group">
        <button class="btn btn-primary">INVIA</button>
    </div>
</form>
@endsection