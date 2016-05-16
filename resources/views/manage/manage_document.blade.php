@extends('layouts.base')
@section('title')
<title>文章管理 - 管理中心</title>
@stop
@section('css')
<link href="/css/bootstrap3_grid.css" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="/lib/bootstrap-table/bootstrap-table.min.css">
<link type="text/css" rel="stylesheet" href="/lib/bootstrap-editable/css/bootstrap-editable.css">
@stop
@section('script')
@include('scripts.sui_table')
<script>
    function linkFormatter(value, row) {
        return '<a href="' + value + '" target="_blank">' + value + '</a>';
    }
    function docActions(value, row) {
        var actions = '<button data-target="#documentEditModal" ref="' + row.id + '" class="btn btn-success btn-edit">修改</button>';
        return actions;
    }    
</script>
@stop
@section('body')
@include('globals/nav')
<div class="container">

    <div class="layout">
        <div class="sidebar">
            <ul class="nav nav-xlarge nav-list">
                <li><a href="/manage">首页</a></li>                
                <li><a href="/manage/category">栏目管理</a></li>
                <li class="active"><a >文章管理</a></li>
                <li><a href="/manage/user">用户管理</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>文章管理</h3>
<div class="box-body box-table" data-url="/api/document">
            <div id="docToolbar">
                <button class="btn btn-success btn-large btn-create" type="button" data-target="#documentCreateModal" data-width="large" ><i class="fa fa-plus-circle"></i> 添加</button>
                <button class="btn btn-danger btn-large btn-delete btn-select-enable" type="button" data-target="#docTable" disabled><i class="fa fa-remove"></i> 删除</button>
            </div>
            <table id="docTable" class="table table-hover"
                   data-toggle="table"
                   data-cache="false" 
                   data-mobile-responsive="true"
                   data-sort-name="id"
                   data-sort-order="desc"
                   data-url="/api/document"
                   data-pagination="true"
                   data-side-pagination="server"
                   data-data-field="data"
                   data-search="true"
                   data-advanced-search="true"
                   data-search-modal-id="pubSearch"
                   data-show-refresh="true"
                   data-show-columns="true"
                   data-show-export="true"
                   data-pagination="true"
                   data-page-list="[10, 50, 100]"
                   data-toolbar="#docToolbar">
                <thead>
                    <tr>
                        <th data-field="checked" data-checkbox="true"></th>
                        <th data-field="rank" data-sortable="true" data-editable="true">排序</th>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="title" data-editable="true" >标题</th>
                        <th data-field="link" data-formatter="linkFormatter">页面地址</th>
                        <th data-field="category" data-searchable="false">栏目</th>
                        <th data-field="publisher" data-searchable="false">发布用户</th>
                        <th data-field="publish_date">发布日期</th>
                        <th data-field="created_at">添加日期</th>
                        <th data-align="center" data-formatter="docActions" data-searchable="false" data-width="100">操作</th>
                    </tr>
                </thead>                
            </table>
        </div>         
        </div>
    </div>
</div>
@include('modals.modal_create',['modal'=>['name'=>'document', 'title'=>'文档', 'api'=>'/api/document','cls'=>'modal-large'],'inputs'=>'inputs.document_create'])
@include('modals.modal_edit',['modal'=>['name'=>'document', 'title'=>'文档', 'api'=>'/api/document','cls'=>'modal-large'],'inputs'=>'inputs.document_edit'])
@include('modals.modal_relation',['modal'=>['name'=>'category', 'title'=>'栏目','api'=>'/api/document/category'],'master'=>'document','slave'=>'category','table'=>'tables.category'])
@stop
