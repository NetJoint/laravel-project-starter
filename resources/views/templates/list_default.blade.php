@extends('layouts.base')
@section('title')
<title>{{$document->title}}</title>
@stop
@section('body')
@include('globals/nav')
<div class="container">
    <h1>{{$document->title}}</h1>
    <p>
        {!! $document->content !!}
    </p>
</div>
@include('globals/footer')
@stop