<div class="form-group">
    <label for="img_path">Thumbnail</label>
    <input type="file" class="form-control" name="img_path" id="img_path" value="{{ $photo->name }}" />
</div>
@if($photo->img_path)
<div class="form-group">
    <img width="300" src="{{ asset($photo->img_path) }}" alt="{{ $photo->name }}" title="{{ $photo->name }}" />
</div>
@endif