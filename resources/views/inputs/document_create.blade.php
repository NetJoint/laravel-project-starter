<link rel="stylesheet" href="/lib/cropper/cropper.min.css">
<link rel="stylesheet" href="/lib/cropupload/cropupload.css">
<script src="/lib/bootstrap2-sui/editor/editor-config.js"></script>
<script src="/lib/bootstrap2-sui/editor/editor-all.min.js"></script>
<script src="/lib/cropper/cropper.min.js"></script>
<script src="/lib/cropupload/cropupload.js"></script>
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>标题：</label>
    <div class="controls">
        <input class="input-xxlarge" name="title" placeholder="文档标题" data-rules="required" type="text">
    </div>
</div>
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>栏目：</label>
    <div class="controls">
        <input id="categoryTypeahead" type="text" placeholder="搜索栏目名称或id" value="" autocomplete="off">
        <input name="category_id" type="hidden" value="" data-rules="required">
    </div>
</div>
<!--<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>发布日期：</label>
    <div class="controls">
        <input name="publish_date" data-toggle="datepicker" value="{{date('Y-m-d')}}" type="text" data-rules="required"  class="input-medium">        
    </div>
</div>-->
<div class="control-group">
    <label class="control-label">链接地址：</label>
    <div class="controls">
        <input name="link"  value="" type="text" class="input-xxlarge" placeholder="留空则根据id自动生成">        
    </div>
</div>
<div class="control-group">
    <label class="control-label">内容：</label>
    <div class="controls">
        <textarea class="editor" name="content"></textarea>
    </div>
</div>
<div class="control-group">
    <label class="control-label">封面图/图标：</label>
    <div class="controls">
        <input type="text" name="thumb" value="" autocomplete="off" data-toggle="cropupload" data-url="/api/ueditor" data-field="file" data-title="点击上传封面图" data-addimg="/img/addimage.png" data-blankimg="/img/blankimg.png">
        <span class="label label-info">请使用对应宽高比裁剪图片：图标 - 1:1 ; 新闻图 - 4:3 ; 幻灯片 - 16:9</span>
    </div>
</div>
<div class="control-group">
    <label class="control-label">摘要：</label>
    <div class="controls">
        <textarea class="input-xxlarge" rows="2" name="description" placeholder="文档摘要，留空则自动截取内容"></textarea>        
    </div>
</div>
<div class="control-group">
    <label class="control-label">排序：</label>
    <div class="controls">
        <input name="rank" placeholder="数字越大越靠前，默认为0" type="text" class="input-large">
    </div>
</div>
@section('script')
<script>  
$('#documentCreateForm #categoryTypeahead').autocomplete({
        serviceUrl: '/api/category/typeahead',
        params: {
            limit: 5
        },
        onSelect: function (suggestion) {
            $('#documentCreateForm input[name="category_id"]').val(suggestion.data.id);
        }
    });
</script>
@append
