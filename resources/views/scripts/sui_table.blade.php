<script src="/lib/bootstrap-table/bootstrap-table-all.min.js"></script>
<script src="/lib/bootstrap2-sui/js/ajaxform.js"></script>
<script>
    function getIdSelections($table) {
        return $.map($table.bootstrapTable('getSelections'), function (row) {
            return row.id;
        });
    }
    var $curtable;
    $('body')
            .on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table load-success.bs.table load-error.bs.table','.box-table .table',function () {                
                var $table = $(this);
                var $box = $table.parents('.box-table');
                $('.btn-select-enable', $box).prop('disabled', !$table.bootstrapTable('getSelections').length);
            })
            .on('editable-save.bs.table','.box-table .table', function ($el, field, row, oldValue) {
                var $table = $(this);
                var $box = $table.parents('.box-table');
                var url = $box.attr('data-url');
                if (url) {
                    var params = {};
                    params[field] = row[field];
                    $.ajax({
                        type: 'PUT',
                        url: url + '/' + row.id,
                        data: params,
                        dataType: 'json',
                        error: function (rs) {
                            alert(rs.responseJSON.message);
                        },
                    });
                }
            })
        .on('click', '.box-table .btn-delete', function () {
        if (confirm('确定要删除所选项吗？')) {
            var $btn = $(this);
            var $box = $btn.parents('.box-table');
            var $table = $box.find('.table');
            var url = $box.attr('data-url');
            if (url) {
                var ids = getIdSelections($table);
                $.ajax({
                    type: 'DELETE',
                    url: url + '/' + ids.join(','),
                    dataType: 'json',
                    success: function (data) {
                        $table.bootstrapTable('refresh');
                    },
                    error: function (rs) {
                        alert(rs.responseJSON.message);
                    },
                });
            }
        }
    })
    .on('click', '.box-table .btn-create',function () {
        var $btn = $(this);
        var $box = $btn.parents('.box-table');
        var $table = $box.find('.table');
        $curtable = $table;
        var val = $btn.attr('data-val');
        var modal = $btn.attr('data-target'),$modal = $(modal);        
        if(val){            
            val = $.parseJSON(val);
            $.each( val, function( key, value ) {
                    $modal.find('[name="'+key+'"]').val(value);                    
                  });
        }
        $modal.modal('show');
    })
    .on('click', '.box-table .btn-edit',function () {
        var $btn = $(this);
        var $box = $btn.parents('.box-table');
        var $table = $box.find('.table');
        $curtable = $table;
        var id = $btn.attr('ref');
        var modal = $btn.attr('data-target'),$modal = $(modal);
        var $form = $('[data-toggle="ajaxform"]',$modal);
        var url = $form.attr('data-api')+'/'+id;
        $form.ajaxform('load', url);
        $form.attr('action', url);
        $modal.modal('show');
    })
    .on('click', '.box-table .btn-relation',function () {
        var $btn = $(this);
        var $box = $btn.parents('.box-table');
        $curtable = $box.find('.table');
        var id = $btn.attr('ref');        
        var modal = $btn.attr('data-target'),$modal = $(modal); 
        var master = $btn.attr('data-master'),slave = $btn.attr('data-slave'), master_id = $btn.attr('ref');
        $('input[name="'+master+'_id"]', $modal).val(master_id);
        $('input[name="'+slave+'_id"]', $modal).val('');
        $('#'+slave+'Typeahead', $modal).val('');
        var url = '/api/'+master+'/' + master_id + '/' + slave;        
        $('.box-table', $modal).attr('data-url', url);
        var $slaveTable = $('#'+slave+'Table', $modal);
        $slaveTable.bootstrapTable('removeAll');
        $slaveTable.bootstrapTable('refresh', {'url': url});
        $modal.off('hide').on('hide', function () {
                $curtable.bootstrapTable('refresh');
            }).modal('show');
    });
</script>