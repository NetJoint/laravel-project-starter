<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>名称：</label>
    <div class="controls">
        <input name="title" placeholder="栏目名称" data-rules="required" type="text">
    </div>
</div>
<div class="control-group">
    <label class="control-label">上级栏目：</label>
    <div class="controls">
        <input id="categoryTypeahead" name="parent_category" type="text" placeholder="搜索栏目名称或id" value="" autocomplete="off">
        <input name="parent_id" type="hidden" value="0">
    </div>
</div>
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>列表模板：</label>
    <div class="controls">
        <input name="list_template" placeholder="列表模板" type="text" data-rules="required" value="list_default">
    </div>
</div>
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>文章模板：</label>
    <div class="controls">
        <input name="detail_template" placeholder="详情模板" type="text" data-rules="required" value="detail_default">
    </div>
</div>
<div class="control-group">
    <label class="control-label">排序：</label>
    <div class="controls">
        <input name="rank" placeholder="数字越大越靠前，默认为0" type="text">
    </div>
</div>
@section('script')
<script>  
$('#categoryEditForm #categoryTypeahead').autocomplete({
        serviceUrl: '/api/category/typeahead',
        params: {
            limit: 5
        },
        onSelect: function (suggestion) {
            $('#categoryEditForm input[name="parent_id"]').val(suggestion.data.id);
        }
    });
</script>
@append