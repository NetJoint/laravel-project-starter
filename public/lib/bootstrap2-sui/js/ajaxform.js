!function ($) {

    "use strict";

    var Ajaxform = function (element, options) {
        this.init(element, options);
    }

    Ajaxform.prototype = {
        constructor: Ajaxform

        , init: function (element, options) {
            var that = this;
            this.el = element;
            this.$element = $(element);
            this.options = this.getOptions(options);
            this.$element.validate({
                success: function ($form) {
                    var $btn = $('button[type="submit"]', $form);
                    if (!$btn.attr('data-loading-text')) {
                        $btn.attr('data-loading-text', '正在提交...');
                    }
                    $btn.button('loading');
                    var field_data = $form.serializeArray();
                    var checkbox_data = that.options.checkbox_data;
                    if (checkbox_data.length) {
                        $.each(checkbox_data, function (i, data) {
                            var field = $.grep(field_data, function (obj) {
                                return obj.name === data.name;
                            });
                            if (!field.length) {
                                field_data.push(data);
                            }
                        });
                    }
                    $.ajax({
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        data: field_data,
                        success: function (data) {
                            $btn.button('reset');
                            $('.msg-ajaxform').remove();
                            var tip = $('<div class="msg msg-success msg-ajaxform"><div class="msg-con">提交成功</div><s class="msg-icon"></s></div>');
                            $btn.after(tip);
                            setTimeout("$('.msg-ajaxform').fadeOut()", 3000);
                            if (typeof ($form.attr('data-success')) !== "undefined") {
                                eval($form.attr('data-success') + '(data)');
                            }
                        },
                        error: function (rs) {
                            $btn.button('reset');
                            $('.msg-ajaxform').remove();
                            var tip = $('<div class="msg msg-error msg-ajaxform"><div class="msg-con">' + rs.responseJSON.message + '</div><s class="msg-icon"></s></div>');
                            $btn.after(tip);
                            setTimeout("$('.msg-ajaxform').fadeOut()", 3000);
                            if (typeof ($form.attr('data-fail')) !== "undefined") {
                                eval($form.attr('data-fail') + '(rs.responseJSON)');
                            }
                        },
                    });
                    return false;
                }
            });
        }
        , getOptions: function (options) {
            options = $.extend({}, $.fn.ajaxform.defaults, this.$element.data(), options);
            return options
        }
        , load: function (url) {
            var that = this;
            var $form = this.$element;
            $.ajax({
                type: 'GET',
                url: url,
                success: function (rs) {
                    //由于未选中项提交时不会被提交，记录下来提交时进行合并
                    that.options.checkbox_data = [];
                    $.each(rs.data, function (key, value) {
                        var $input = $('input[name="' + key + '"]', $form);
                        if ($input.length) {
                            var type = $input.attr('type');
                            switch (type) {
                                case 'radio':
                                    var $checked = $('input[name="' + key + '"]:checked', $form);
                                    if ($checked.val() != value) {
                                        $('input[name="' + key + '"][value="' + value + '"]', $form).prop('checked', true).trigger("change");
                                    }
                                    break;
                                case 'checkbox':
                                    //单项checkedbox
                                    if (!$input.is(':checked')) {
                                        if ($input.val() == value) {
                                            $input.prop('checked', true);
                                            $input.trigger("change");
                                        }
                                    } else {
                                        if ($input.val() != value) {
                                            $input.prop('checked', false).trigger("change");
                                        }
                                    }
                                    that.options.checkbox_data.push({name: key, value: ''});
                                    break;
                                case 'text':
                                default:
                                    if ($input.val() != value) {
                                        $input.val(value).trigger("change");
                                    }
                            }
                        } else {
                            //checkedbox
                            var $checkedbox = $('input[name="' + key + '[]"]', $form);
                            if ($checkedbox.length) {
                                //数组checkedbox
                                if (!$.isArray(value)) {
                                    value = [value];
                                }
                                key = key + '[]';
                                $('input[name="' + key + '"]', $form).prop('checked', false).trigger("change");
                                $.each(value, function (i, v) {
                                    $('input[name="' + key + '"][value="' + v + '"]', $form).prop('checked', true).trigger("change");
                                });
                                that.options.checkbox_data.push({name: key, value: ''});
                            } else {
                                //textarea
                                var $textarea = $('textarea[name="' + key + '"]', $form);
                                if ($textarea.length) {
                                    if($textarea.val() != value){
                                        $textarea.val(value).trigger("change");
                                    }
                                } else {
                                //ueditor    
                                    var $editor = $('.editor[name="' + key + '"]', $form);
                                    if ($editor.length) {
                                        var ue_id = $editor.parent().find('div.editor').attr('id');
                                        if (ue_id) {
                                            var ue = UE.getEditor(ue_id);
                                            ue.setContent(value);
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function (rs) {
                    alert(rs.responseJSON.message);
                },
            });
        }
        , reset: function () {
            this.el.reset();
            var $form = this.$element;
            $('input:radio', $form).trigger("change");
            $('input:checkbox', $form).trigger("change");
            $('[data-toggle="dropdown"] input', $form).trigger("change");
            $('input[data-toggle="cropupload"]', $form).trigger("change");
            if (typeof (UE) != 'undefined') {
                $('div.editor', $form).each(function () {
                    var ue_id = $(this).attr('id');
                    if (ue_id) {
                        var ue = UE.getEditor(ue_id);
                        ue.setContent('');
                    }
                });
            }
        }
    }

    $.fn.ajaxform = function (option, value) {
        return this.each(function () {
            var $this = $(this)
                    , data = $this.data('ajaxform')
                    , options = typeof option == 'object' && option
            if (!data)
                $this.data('ajaxform', (data = new Ajaxform(this, options)))
            if (typeof option == 'string')
                data[option](value)
        })
    }

    $.fn.ajaxform.Constructor = Ajaxform;

    $.fn.ajaxform.defaults = {
        checkbox_data: []
    }

    $(function () {
        $("[data-toggle='ajaxform']").ajaxform();
    });

}(window.jQuery); 