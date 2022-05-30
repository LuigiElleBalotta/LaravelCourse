<div class="form-group">
    <label for="categories">Category</label>
    @json($selectedCategories)
    <select name="categories[]" id="categories" class="form-control" multiple>
        @foreach($categories as $category)
            <option {{ in_array($category->id, $selectedCategories, true) ? "selected" : "" }} value="{{ $category->id }}">{{ $category->category_name }}</option>
        @endforeach
    </select>
</div>
