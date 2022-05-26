@extends('templates.layout')
@section('title', 'Blog')
@section('content')
    <h1>About</h1>
    <x-alert :info="'success'" :message="'Ciao'" />
@endsection



@section('footer')
    @parent

@endsection