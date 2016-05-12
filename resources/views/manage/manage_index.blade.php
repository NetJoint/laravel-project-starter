@extends('layouts.base')
@section('title')
<title>首页 - 管理中心</title>
@stop
@section('body')
@include('globals/nav')
<div class="container">
    <div class="layout">
        <div class="sidebar">
            <ul class="nav nav-xlarge nav-list">
                <li class="active"><a>首页</a></li>                
                <li><a href="/manage/category">栏目管理</a></li>
                <li><a href="/manage/document">文章管理</a></li>
                <li><a href="/manage/user">用户管理</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>管理中心首页</h3>
        </div>
    </div>
</div>
@stop