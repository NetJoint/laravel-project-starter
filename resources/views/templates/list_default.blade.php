@extends('layouts.base')
@section('title')
<title>{{$category->title}}</title>
@stop
@section('body')
@include('globals/nav')
<div class="container">
    <h1>{{$category->title}}</h1>
    <ul>
        @foreach($documents as $document)
        <li>
            @if($document->thumb)
            <img src="{{$document->thumb}}" width="100"/>
            @endif
            <a href="/document/{{$document->hash}}">{{$document->title}}</a>
        </li>
        @endforeach
    </ul>
</div>
@include('globals/footer')
@stop