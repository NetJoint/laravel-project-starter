<table id="{{$id}}Table" class="table table-hover"
       data-toggle="table"
       data-cache="false"
       data-mobile-responsive="true"
       data-sort-name="id"
       data-sort-order="desc"
       data-pagination="true"
       data-side-pagination="server"
       data-data-field="data"
       data-search="true"
       data-advanced-search="true"
       data-search-modal-id="{{$id}}Search"
       data-show-refresh="true"
       data-show-columns="true"
       data-show-export="true"
       data-pagination="true"
       data-page-list="[10, 50, 100, ALL]"
       data-toolbar="#{{$id}}Toolbar">
    <thead>
        <tr>
            <th data-field="checked" data-checkbox="true"></th>
            <th data-field="id" data-sortable="true">ID</th>
            <th data-field="title">标题</th>
            <th data-field="publisher" data-searchable="false">发布用户</th>
            <th data-field="created_at">添加日期</th>
        </tr>
    </thead>
</table>
