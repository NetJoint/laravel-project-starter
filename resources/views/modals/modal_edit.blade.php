<div id="{{$modal['name']}}EditModal" tabindex="-1" role="dialog" class="modal hide fade {{isset($modal['cls'])?$modal['cls']:''}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 class="modal-title">修改{{$modal['title']}}</h4>
            </div>
            <div class="modal-body">
                <form id="{{$modal['name']}}EditForm" class="form form-horizontal" data-api="{{$modal['api']}}" action="" method="PUT" data-toggle="ajaxform" data-success="{{$modal['name']}}EditSuccess">
                    @include($inputs)
                    <div class="control-group">
                        <label class="control-label"></label>
                        <div class="controls">
                            <button type="submit" class="btn btn-primary">保 存</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default btn-large">关 闭</button>
            </div>
        </div>        
    </div>
</div>
@section('script')
<script>    
    var {{$modal['name']}}EditSuccess = function () {
        $curtable.bootstrapTable("refresh");
    }
    if (typeof (UE) != 'undefined') {
            $(function(){
                $('#{{$modal['name']}}EditForm textarea.editor').editor();
            });
        }    
</script>
@append