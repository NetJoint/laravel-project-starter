@extends('layouts.base')
@section('title')
<title>用户管理 - 管理中心</title>
@stop
@section('css')
<link type="text/css" rel="stylesheet" href="/lib/bootstrap-table/bootstrap-table.min.css">
<link type="text/css" rel="stylesheet" href="/lib/bootstrap-editable/css/bootstrap-editable.css">
@stop
@section('script')
@include('scripts.sui_table')
<script>
    var $userTable = $('#userTable'), $roleTable = $('#roleTable');
    function rolesFormatter(value, row) {
        if (value == '') {
            value = '未添加';
            var cls = 'text-danger';
        } else {
            var cls = 'text-success';
        }
        return '<a class="btn-role editable-click ' + cls + '" href="javascript:void(0);" ref="' + row.id + '">' + value + '</a>';
    }
    $userTable.on('click', '.btn-role', function () {
        var $btn = $(this);
        var user_id = $btn.attr('ref');
        $('#userId').val(user_id);
        $('#roleId').val('');
        $('#roleTypeahead').val('');
        var url = '/api/user/' + user_id + '/role';
        $('#roleModal .box-table').attr('data-url', url);
        $roleTable.bootstrapTable('removeAll');
        $roleTable.bootstrapTable('refresh', {'url': url});
        $('#roleModal').on('hide', function () {
            $userTable.bootstrapTable('refresh');
        }).modal('show');
    });
    $('#roleTypeahead').autocomplete({
        serviceUrl: '/api/role/typeahead',
        params: {
            limit: 5
        },
        onSelect: function (suggestion) {
            $('#roleId').val(suggestion.data.id);
        }
    });
    var roleAddSuccess = function (data) {
        $('#roleId').val('');
        $('#roleTypeahead').val('');
        $roleTable.bootstrapTable('refresh');
    };
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
                <li><a href="/manage/document">文章管理</a></li>
                <li class="active"><a>用户管理</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>用户管理</h3>
            <div class="box-body box-table" data-url="/api/user">
                <div id="userToolbar">   
                    <button class="btn btn-danger btn-large btn-delete btn-select-enable" type="button" data-target="#docTable" disabled><i class="fa fa-remove"></i> 删除</button>
                </div>
                <table id="userTable" class="table table-hover"
                       data-toggle="table"
                       data-cache="false" 
                       data-mobile-responsive="true"
                       data-sort-name="id"
                       data-sort-order="desc"
                       data-url="/api/user"
                       data-pagination="true"
                       data-side-pagination="server"
                       data-data-field="data"
                       data-search="true"
                       data-advanced-search="true"
                       data-search-modal-id="userSearch"
                       data-show-refresh="true"
                       data-show-columns="true"
                       data-show-export="true"
                       data-pagination="true"
                       data-page-list="[10, 50, 100]"
                       data-toolbar="#userToolbar">
                    <thead>
                        <tr>
                            <th data-field="checked" data-checkbox="true"></th>
                            <th data-field="id">ID</th>
                            <th data-field="name" data-editable="true">姓名</th>
                            <th data-field="email" data-editable="true">邮件</th>
                            <th data-field="mobile" data-editable="true">电话</th>
                            <th data-field="roles" data-searchable="false" data-formatter="rolesFormatter">角色</th>
                            <th data-field="last_login">最后登录</th>
                        </tr>
                    </thead>                
                </table>
            </div> 
        </div>
    </div> 

</div>
<div id="roleModal" tabindex="-1" role="dialog" class="modal hide fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
                <h4 class="modal-title">关联角色</h4>
            </div>
            <div class="modal-body box-table" data-url="" style="height:400px;">                    
                <div id="roleToolbar">
                    <form id="roleForm" class="form form-search" action="/api/user/role" method="POST" data-toggle="ajaxform" data-success="roleAddSuccess" >
                        <input id="roleId" name="role_id" value="" type="hidden">
                        <input id="userId" name="user_id" value="" type="hidden" data-rules="required">

                        <div class="dropdown-like">
                            <input id="roleTypeahead" class="input-large" type="text" placeholder="选择角色添加" value="" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-success">添加</button>
                    </form>
                    <button class="btn btn-danger btn-large btn-select-enable btn-delete" type="button" disabled><i class="fa fa-remove"></i> 删除</button>
                </div>
                <table id="roleTable" data-toggle="table" data-cache="false" data-mobile-responsive="true" data-editable-mode="inline" data-toolbar="#roleToolbar">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="name">角色名称</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default btn-large">关闭</button>
            </div>
        </div>
    </div>
</div>
@stop
