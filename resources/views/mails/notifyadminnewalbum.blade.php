@component('mail::message')
    Hello {{ $admin->name }}
# New album {{$album->album_name}} created!

Visit <a href="{{ route('albums.edit', $album->id) }}">{{$album->album_name}}</a>

The body of your message.

@component('mail::button', ['url' => route('albums.edit', $album->id)])
{{$album->album_name}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
