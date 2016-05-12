<div id="{{$modal['name']}}RelationModal" tabindex="-1" role="dialog" class="modal hide fade {{isset($modal['cls'])?$modal['cls']:''}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
                <h4 class="modal-title">关联{{$modal['title']}}</h4>
            </div>
            <div class="modal-body box-table" data-url="" style="height:400px;">
                <form id="{{$modal['name']}}RelationForm" class="form form-search" action="{{$modal['api']}}" method="POST" data-toggle="ajaxform" data-success="{{$slave}}AttachSuccess" >
                    <input name="{{$master}}_id" value="" type="hidden" data-rules="required">
                    <input name="{{$slave}}_id" value="" type="hidden" data-rules="required">

                    <div class="dropdown-like">
                        <input id="{{$slave}}Typeahead" class="input-xlarge input-fat" type="text" placeholder="搜索{{$modal['title']}}名称或id，选择添加" value="" autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-success btn-large">添加关联</button>
                </form>
                <div id="{{$slave}}Toolbar">
                    <button class="btn btn-danger btn-large btn-select-enable btn-delete" type="button" disabled><i class="fa fa-remove"></i> 删除关联</button>
                </div>
                @include($table,['id'=>$slave])
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default btn-large">关闭</button>
            </div>
        </div>
    </div>
</div>
@section('script')
<script>    
    var {{$slave}}AttachSuccess = function () {
        $('#{{$modal['name']}}RelationForm input[name="{{$slave}}_id"]').val('');
        $('#{{$modal['name']}}RelationForm #{{$slave}}Typeahead').val('');
        $('#{{$slave}}Table').bootstrapTable('refresh');
    }
    $('#{{$modal['name']}}RelationForm #{{$slave}}Typeahead').autocomplete({
        serviceUrl: '/api/{{$slave}}/typeahead',
        params: {
            limit: 5
        },
        onSelect: function (suggestion) {
            $('#{{$modal['name']}}RelationForm input[name="{{$slave}}_id"]').val(suggestion.data.id);
        }
    });
</script>
@append