@extends('layouts.base')
@section('title')
<title>栏目管理 - 管理中心</title>
@stop
@section('css')

<link type="text/css" rel="stylesheet" href="/lib/bootstrap-table/bootstrap-table.min.css">
<link type="text/css" rel="stylesheet" href="/lib/bootstrap-editable/css/bootstrap-editable.css">
@stop
@section('script')
@include('scripts.sui_table')
<script>
    function linkFormatter(value, row) {
        return '<a href="/category/' + row.id + '" target="_blank">/category/' + row.id + '</a>';
    }
    function limitFormatter(value, row) {
        return value?value:'不限';
    }
    function feedsFormatter(value, row) {
        if (value == '') {
            value = '未添加';
            var cls = 'text-danger';
        } else {
            var cls = 'text-success';
        }
        return '<a data-target="#feedRelationModal" data-master="category" data-slave="feed" class="btn-relation editable-click ' + cls + '" href="javascript:void(0);" ref="' + row.id + '">' + value + '</a>';
    }
    function documentFormatter(value, row) {
        return '<a data-target="#documentRelationModal" data-master="category" data-slave="document" class="btn-relation editable-click" href="javascript:void(0);" ref="' + row.id + '">' + value + '</a>';
    }
    function templateFormatter(value, row) {
        var temps = {
            'list_titledate': '标题+日期',
            'list_title': '仅标题',
            'list_titlethumb': '缩略图+标题',
            'list_link': '平铺标题链接',
            'list_iconlink': '平铺标题链接+小图标',
            'list_carousel': '幻灯片',
        }
        return temps[value];
    }
    function categoryActions(value, row) {
        var actions = '<button data-target="#categoryEditModal" ref="' + row.id + '" class="btn btn-success btn-edit">修改</button>';
        return actions;
    }    
</script>
@stop
@section('body')
@include('globals/nav')
<div class="container">
      
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">栏目管理</h3>
            <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body box-table" data-url="/api/category">
            <div id="catToolbar">
                <button class="btn btn-success btn-large btn-create" type="button" data-target="#categoryCreateModal" data-width="large" ><i class="fa fa-plus-circle"></i> 添加</button>
                <button class="btn btn-danger btn-large btn-delete btn-select-enable" type="button" disabled><i class="fa fa-remove"></i> 删除</button>
            </div>
            <table id="catTable" class="table table-hover"
                   data-toggle="table"
                   data-cache="false" 
                   data-mobile-responsive="true"
                   data-sort-name="id"
                   data-sort-order="desc"
                   data-url="/api/category"
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
                   data-toolbar="#catToolbar">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-field="rank" data-sortable="true" data-editable="true">排序</th>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="title" data-editable="true" >名称</th>
                        <th data-field="link" data-formatter="linkFormatter" data-searchable="false">页面地址</th>
                        <th data-field="list_template" data-formatter="templateFormatter" data-searchable="false">列表模板</th>
                        <th data-field="limit" data-searchable="false"  data-formatter="limitFormatter">列表条数</th>                     
                        <th data-field="doc_count" data-searchable="false"  data-formatter="documentFormatter">文档数</th>                     
                        <th data-field="created_at">添加日期</th>
                        <th data-align="center" data-formatter="categoryActions" data-searchable="false" data-width="100">操作</th>
                    </tr>
                </thead>                
            </table>
        </div>                
    </div>    
</div>
@include('modals.modal_create',['modal'=>['name'=>'category', 'title'=>'栏目', 'api'=>'/api/category'],'inputs'=>'inputs.category_create'])
@include('modals.modal_edit',['modal'=>['name'=>'category', 'title'=>'栏目', 'api'=>'/api/category'],'inputs'=>'inputs.category_edit'])
@include('modals.modal_relation',['modal'=>['name'=>'document', 'title'=>'文档','api'=>'/api/category/document','cls'=>'modal-large'],'master'=>'category','slave'=>'document','table'=>'tables.document'])
@stop
