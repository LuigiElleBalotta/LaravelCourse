@extends('templates.layout')
@section('title', 'Blog')
@section('content')
    <h1>blog</h1>

    <!-- METODO 1 -->
    @component('components.card', 
    [
        'img_title' => 'Image blog',
        'img_url' => 'https://picsum.photos/286/180'
    ])
    <p>This is a beautiful image I took in picsum</p>
    @endcomponent

    <!-- METODO 2 -->
    @component('components.card')
        @slot('img_url', 'https://picsum.photos/286/180')
        @slot('img_title', 'Second Image')
        <p>This is a beautiful image I took in picsum</p>
    @endcomponent

    <!-- METODO 3 -->
    <!-- Le variabili accessibili da questa pagina (fornite dal controller) saranno accessibili anche alla vista dell'include -->
    @include('components.card')

    <!-- METODO 4 -->
    @include('components.card', 
    [
        'img_title' => 'Quattro',
        'img_url' => 'https://picsum.photos/286/180'
    ])
@endsection



@section('footer')
    @parent

@endsection