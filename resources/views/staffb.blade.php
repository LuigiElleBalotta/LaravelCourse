@extends('templates.layout')

@section('title', $title)

@section('content')

<h1>
    With Blade
    {{$title}}
</h1>

@if($staff)
    <h2>Staff FOREACH</h2>
    <ul>
        @foreach($staff as $person)
        <li style="{{$loop->first ? 'color:red;' : ($loop->last ? "color:grey;" : "color:blue;")}}"> {{$loop->remaining}}
            {{$loop->last}} {{$person["name"]}} {{$person["lastname"]}}</li>
        @endforeach
    </ul>
@else
    <p>No Staff</p>
@endif


<h2>Staff FORELSE</h2>
<ul>
    @forelse($staff as $person)
    <li>{{$person["name"]}} {{$person["lastname"]}}</li>
    @empty
    <li>No Staff</li>
    @endforelse
</ul>

<h2>Staff FOR</h2>
<ul>
    @for($i = 0; $i < count($staff); $i++) 
        <li>{{$staff[$i]["name"]}} {{$staff[$i]["lastname"]}}</li>
    @endfor
</ul>

<h2>Staff WHILE</h2>
<ul>
    @while($person = array_pop($staff))
        <li>{{$person["name"]}} {{$person["lastname"]}}</li>
    @endwhile
</ul>

@endsection



@section('footer')
@parent
<script>
    alert('footer');

</script>
@stop
