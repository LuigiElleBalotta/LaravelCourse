@extends('templates.layout')

@section('content')
    <h1>Albums</h1>
    @if(session()->has('message'))
        <div class="alert alert-info">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12 ">
            <a href="/albums/create" class="btn btn-success float-right mb-4" title="Create new album">
                <i class="bi bi-plus"></i>
            </a>
        </div>
    </div>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
    <table class="table table-striped table-dark">
        <thead>
            <tr class="align-middle">
                <th>Album Name</th>
                <th>Thumb</th>
                <th>Author</th>
                <th>Date</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($albums as $album)
            <tr id="tr{{$album->id}}">
                <td>({{ $album->id }}) {{$album->album_name}}</td>
                <td>
                    @if($album->album_thumb)
                        <img width="120" src="{{ asset($album->path) }}" alt="{{ $album->album_name }}" title="{{ $album->album_name }}" />
                    @endif
                </td>
                <td>{{ $album->user->name }}</td>
                <td>{{ $album->created_at->diffForHumans() }}</td>
                <td>
                    <div class="row">
                        <div class="col-3">
                            <a title="Add new Image" href="{{ route('photos.create') }}?album_id={{ $album->id }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i>
                            </a>
                        </div>
                        <div class="col-3">
                            @if($album->photos_count)
                                <a title="View images" href="{{ route('albums.images', [ 'album' => $album->id ]) }}" class="btn btn-primary">
                                    <i class="bi bi-zoom-in"></i>({{ $album->photos_count }})
                                </a>
                            @else
                                <i class="bi bi-zoom-in"></i>
                            @endif
                        </div>
                        <div class="col-3">
                            <a href="{{ route('albums.edit', [ 'album' => $album->id ]) }}" class="btn btn-primary">
                                <i class="bi bi-pen"></i>
                            </a>
                        </div>
                        <div class="col-3">
                            <form id="form{{$album->id}}" method="POST" action="{{ route('albums.destroy', $album->id) }}">
                                @csrf
                                @method('DELETE')
                                <button id="{{$album->id}}" class="btn btn-danger delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    <div class="row">
                        <div class="col-md-8 offset-md-2 d-flex justify-content-center">
                            {{ $albums->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection

@section('footer')
    @parent
    <script>
        $(document).ready(function() {

            // Togliamo l'alert se presente
            $('div.alert').fadeOut(5000);

            $('table').on('click', 'button.delete-btn', function(evt) {
                evt.preventDefault();

                let id = evt.target.id;
                let form = $('#form' + id);

                let urlAlbum = form.attr('action');
                let tr = $('#tr' + id);
                $.ajax(urlAlbum, {
                    method: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    complete: function(response) {
                        // alert(response.responseText);
                        if( response.responseText == 1 ) {
                            tr.remove();
                        }
                        else {
                            alert('Problems contacting server.')
                        }
                    }
                })
            })
        });
    </script>
@endsection
