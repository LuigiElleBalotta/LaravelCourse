@extends('templates.layout')

@section('content')

<h1>Images for {{ $album->album_name }}</h1>
@if(session()->has('message'))
    <div class="alert alert-info">
        {{ session()->get('message') }}
    </div>
@endif

<table class="table table-bordered">
    <thead>
        <th>ID</th>
        <th>Date Created</th>
        <th>Title</th>
        <th>Album</th>
        <th>Thumbail</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($images as $image)
        <tr>
            <td>
                {{ $image->id }}
            </td>
            <td>
                {{ $image->created_at }}
            </td>
            <td>
                {{ $image->name }}
            </td>
            <td>
                {{ $album->album_name }}
            </td>
            <td>
                <img width="100" src="{{ asset($image->img_path) }}" alt="{{ $image->title }}" title="{{ $image->title }}" />
            </td>
            <td>
                <a href="{{ route('photos.edit', $image->id) }}" class="btn btn-sm btn-primary">MODIFICA</a>
                <a href="{{ route('photos.destroy', $image->id) }}" class="btn btn-sm btn-danger delete-btn">DELETE</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">
                No images found.
            </td>
        </tr>
        @endforelse
        <tr>
            <td colspan="6">
                <div class="row">
                    <div class="col-md-8 offset-md-2 d-flex justify-content-center">
                        {{ $images->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

@endsection

@section('footer')
    @parent
    <script>
        $(document).ready(function() {

            // Togliamo l'alert se presente
            $('div.alert').fadeOut(5000);

            $('table').on('click', 'a.delete-btn', function(ele) {
                ele.preventDefault();
                // alert(ele.target.href);
                let urlImg = $(this).attr('href');
                let tr = ele.target.parentNode.parentNode;
                $.ajax(urlImg, {
                    method: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    complete: function(response) {
                        // alert(response.responseText);
                        if( response.responseText == 1 ) {
                            tr.parentNode.removeChild(tr);
                            // $(tr).remove();
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