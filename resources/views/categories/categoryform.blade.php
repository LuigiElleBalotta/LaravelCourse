
@include('partials.inputerrors')

@if($category->category_name)
    <h2>Modify category</h2>
    <form action="{{route('categories.update', $category->id)}}" method="POST" class="row">
        @method('PATCH')
@else
    <h2>Create new category</h2>
    <form action="{{route('categories.store')}}" method="POST" class="row">
@endif


    @csrf
    <div class="form-group">
        <input required minlength="4" value="{{ old('category_name', $category->category_name) }}" placeholder="Category name" name="category_name" id="category_name" class="form-control" type="text" />
    </div>
    <div class="form-group mt-4 d-flex justify-content-center">
        @if($category->category_name)
            <button class="btn btn-primary m-1">UPDATE</button>
    </form>
            <form action="{{route('categories.destroy', $category->id)}}" method="POST">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger m-1" type="submit">DELETE</button>
            </form>
        @else
            <button class="btn btn-primary">SAVE</button>
        @endif
    </div>
</form>
