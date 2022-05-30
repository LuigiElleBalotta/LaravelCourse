@extends('templates.layout')
@section('content')

    @if(session()->has('message'))
        <div class="alert alert-info">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-sm-8">
            <h1>Category List</h1>
            <table class="table table-striped table-dark">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Created</th>
                    <th scope="col">Updated</th>
                    <th scope="col">Albums</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td>{{ $cat->id }}</td>
                        <td>{{ $cat->category_name }}</td>
                        <td>{{ $cat->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $cat->updated_at->diffForHumans() }}</td>
                        <td>
                            @if($cat->albums->count() > 0)
                                <a class="btn btn-success" href="{{ route('albums.index') }}?category_id={{ $cat->id }}">
                                    {{ $cat->albums_count }}
                                </a>
                                @else
                                0
                            @endif
                        </td>
                        <td class="d-flex justify-content-center">
                            <a title="UPDATE" href="{{ route('categories.edit', $cat->id) }}" class="btn btn-primary m-1">
                                <i class="bi bi-pen"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="post"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button title="DELETE" type="submit" class="btn btn-danger m-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="6">
                            No categories
                        </th>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        {{ $categories->links('vendor.pagination.bootstrap-5') }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-sm-4">

            @include('categories.categoryform')
        </div>
    </div>

@endsection
