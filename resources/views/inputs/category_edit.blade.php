<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>名称：</label>
    <div class="controls">
        <input name="title" placeholder="栏目名称" data-rules="required" type="text">
    </div>
</div>
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>列表模板：</label>
    <div class="controls">
        <span class="dropdown dropdown-bordered select">
            <span class="dropdown-inner"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button">
                    <input class="hide" type="text" data-rules="required" name="list_template" autocomplete="off" value="list_titledate"><i class="caret"></i><span>标题+日期</span></a>
                <ul class="dropdown-menu" aria-labelledby="drop1" role="menu" id="menu1">
                    <li role="presentation" class="active"><a value="list_titledate" href="javascript:void(0);" tabindex="-1" role="menuitem">标题+日期</a></li>                    
                    <li role="presentation"><a value="list_title" href="javascript:void(0);" tabindex="-1" role="menuitem">仅标题</a></li>
                    <li role="presentation"><a value="list_titlethumb" href="javascript:void(0);" tabindex="-1" role="menuitem">缩略图+标题</a></li>
                    <li role="presentation"><a value="list_link" href="javascript:void(0);" tabindex="-1" role="menuitem">平铺标题链接</a></li>
                    <li role="presentation"><a value="list_iconlink" href="javascript:void(0);" tabindex="-1" role="menuitem">平铺标题链接+小图标</a></li>
                    <li role="presentation"><a value="list_carousel" href="javascript:void(0);" tabindex="-1" role="menuitem">幻灯片</a></li>
                </ul>
            </span>
        </span>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>列表条数：</label>
    <div class="controls">
        <input name="limit" placeholder="设为0则不限制" type="text" value="5">
    </div>
</div>
<input name="detail_template" placeholder="详情模板" type="hidden" value="detail_default">
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>首页显示：</label>
    <div class="controls">
        <label class="radio-pretty inline">
            <input type="radio" data-rules="required" value="1" name="index_show" autocomplete="off"><span>是</span>
        </label>
        <label class="radio-pretty inline checked">
            <input type="radio" data-rules="required" value="0" name="index_show" checked="checked" autocomplete="off"><span>否</span>
        </label>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><b class="text-danger">*</b>可见身份：</label>
    <div class="controls">
        <label class="checkbox-pretty inline checked">
            <input type="checkbox" value="1" name="identity_student" checked="checked" autocomplete="off"><span>本科生</span>
        </label>
        <label class="checkbox-pretty inline checked">
            <input type="checkbox" value="1" name="identity_graduate" checked="checked" autocomplete="off"><span>研究生</span>
        </label>
        <label class="checkbox-pretty inline checked">
            <input type="checkbox" value="1" name="identity_teacher" checked="checked" autocomplete="off"><span>教工</span>
        </label>
        <label class="checkbox-pretty inline checked">
            <input type="checkbox" value="1" name="identity_alumni" checked="checked" autocomplete="off"><span>校友</span>
        </label>
        <label class="checkbox-pretty inline checked">
            <input type="checkbox" value="1" name="identity_other" checked="checked" autocomplete="off"><span>（含未登录）</span>
        </label>
    </div>
</div>
<!--<div class="control-group">
    <label class="control-label">测试：</label>
    <div class="controls">
        
    </div>
</div>-->
<div class="control-group">
    <label class="control-label">排序：</label>
    <div class="controls">
        <input name="rank" placeholder="数字越大越靠前，默认为0" type="text">
    </div>
</div>