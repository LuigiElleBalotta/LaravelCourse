<div class="form-group">
    <label for="album_thumb">Thumbnail</label>
    <input type="file" class="form-control" name="album_thumb" id="album_thumb" value="{{ $album->album_name }}" />
</div>
@if($album->album_thumb)
<div class="form-group">
    <img width="300" src="{{ asset($album->path) }}" alt="{{ $album->album_name }}" title="{{ $album->album_name }}" />
</div>
@endif