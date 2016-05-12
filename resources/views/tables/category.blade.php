<table id="{{$id}}Table" 
       data-toggle="table"
       data-cache="false" 
       data-mobile-responsive="true"
       data-sort-name="id"
       data-sort-order="desc"
       data-show-refresh="true"
       data-show-columns="true"
       data-show-export="true"
       data-toolbar="#{{$id}}Toolbar">
    <thead>
        <tr>
            <th data-field="checked" data-checkbox="true"></th>
            <th data-field="id" data-sortable="true">ID</th>
            <th data-field="title">名称</th>
            <th data-field="doc_count" data-searchable="false">文档数</th>
        </tr>
    </thead>
</table>