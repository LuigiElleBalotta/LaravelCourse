@extends('templates.layout')
@section('title', 'Staff2')
@section('content')
    <h1><?=$title?></h1>
    <x-alert :name="$name" :info="'info'" :message="'Ciao'" />

    <!-- Questo caso non usa la classe!!! -->
    @component('components.alert', [
        'info' => 'success',
        'message' => 'Ciao',
        'name' => 'Mario'
    ])
    @endcomponent
@endsection



@section('footer')
    @parent

@endsection