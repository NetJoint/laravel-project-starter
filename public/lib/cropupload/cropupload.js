/*!
 * Cropupload v0.1
 * https://github.com/netjoint/cropupload
 *
 * Copyright (c) 2015 NetJoint
 * Released under the MIT license
 *
 * Date: 2015-09-16
 */
!function ($) {
    'use strict';
    function Cropupload(el, options) {
        this.el = el;
        this.$el = $(el);
        this.$container = $(el).parent();
        this.setOptions(options);
        this.setLocale();
        this.$el.hide();
        var src = this.$el.val();
        if (src === '') {
            src = this.options.addimg;
        }
        var thumbView = '<div class="thumb"><img style="height:' + this.options.height + 'px" src="' + src + '" title="' + this.options.title + '" alt="' + this.lang.click_to_upload + '"></div>';
        this.$thumbView = $(thumbView).appendTo(this.$container);
        this.$thumb = this.$thumbView.find('img');
        var thumbModal = '<div class="modal hide fade modal-xlarge" id="thumb-modal" aria-hidden="true" role="dialog" tabindex="-1" style="z-index: 99999;">';
        thumbModal += '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><a class="close" data-dismiss="modal">&times;</a>';
        thumbModal += '<h4 class="modal-title">' + this.lang.upload_image + '</h4></div><div class="modal-body">';
        thumbModal += '<form class="form thumb-form" action="' + this.options.url + '" enctype="multipart/form-data" method="post">';        
        thumbModal += '<input type="hidden" class="thumb-data" name="crop">';
        thumbModal += '<div class="row"><div class="col-sm-6"><input type="file" class="thumb-input" name="' + this.options.field + '">';
        thumbModal += '<a href="javascript:void(0);" class="btn btn-xlarge btn-success">' + this.lang.choose_image + '</a> <span class="thumb-name text-large text-info">' + this.lang.choose_none + '</span></div>';
        thumbModal += '<div class="col-sm-6"><a href="javascript:void(0);" class="btn btn-xlarge btn-primary thumb-upload" disabled>' + this.lang.direct_upload + '</a></div></div>';
        thumbModal += '<div class="row"><div class="col-md-9"><div class="thumb-wrapper"></div></div>';
        thumbModal += '<div class="col-md-3"><div class="thumb-preview preview-lg"></div><div class="thumb-preview preview-md"></div><div class="thumb-preview preview-sm"></div></div></div>';
        thumbModal += '<div class="row"><div class="col-md-12 btn-toolbar thumb-btns">';
        thumbModal += '<div class="btn-group" style="font-size: 12px;vertical-align: bottom;margin-right:10px;">' + this.lang.aspect_ratio + ': <label class="radio-pretty inline"><input name="aspectRatio" type="radio" value="1"><span>1:1</span></label>';
        thumbModal += '<label class="radio-pretty inline"><input name="aspectRatio" type="radio" value="1.3333333333333333"><span>4:3</span></label>';
        thumbModal += '<label class="radio-pretty inline"><input name="aspectRatio" type="radio" value="1.7777777777777777"><span>16:9</span></label></div>';
        thumbModal += '<div class="btn-group"><a title="' + this.lang.zoom_in + '" data-option="0.1" data-method="zoom" class="btn"><i class="fa fa-search-plus"></i></a>';
        thumbModal += '<a title="' + this.lang.zoom_out + '" data-option="-0.1" data-method="zoom" class="btn"><i class="fa fa-search-minus"></i></a></div>';
        thumbModal += '<div class="btn-group"><a title="' + this.lang.move_left + '" data-second-option="0" data-option="-10" data-method="move" class="btn"><i class="fa fa-arrow-left"></i></a>';
        thumbModal += '<a title="' + this.lang.move_right + '" data-second-option="0" data-option="10" data-method="move" class="btn"><i class="fa fa-arrow-right"></i></a>';
        thumbModal += '<a title="' + this.lang.move_up + '" data-second-option="-10" data-option="0" data-method="move" class="btn"><i class="fa fa-arrow-up"></i></a>';
        thumbModal += '<a title="' + this.lang.move_down + '" data-second-option="10" data-option="0" data-method="move" class="btn"><i class="fa fa-arrow-down"></i></a></div>';
        thumbModal += '<div class="btn-group"><a class="btn btn-rotate" data-method="rotate" data-option="-90" title="' + this.lang.rotate_left + '"><i class="fa fa-rotate-left"></i></a>';
        thumbModal += '<a class="btn" data-method="rotate" data-option="90" title="' + this.lang.rotate_right + '"><i class="fa fa-rotate-right"></i></a></div>';
        thumbModal += '<div class="btn-group"><button type="submit" class="btn btn-primary thumb-save" disabled="disabled" autocomplate="off" data-loading-text="' + this.lang.uploading + '">' + this.lang.save + '</button></div>';
        thumbModal += '</div></div></form></div></div></div></div>';
        this.$thumbModal = $(thumbModal).appendTo('body');
        this.$thumbForm = this.$thumbModal.find('.thumb-form');
        this.$thumbData = this.$thumbForm.find('.thumb-data');
        this.$thumbInput = this.$thumbForm.find('.thumb-input');
        this.$thumbName = this.$thumbForm.find('.thumb-name');
        this.$thumbSave = this.$thumbForm.find('.thumb-save');
        this.$thumbUpload = this.$thumbForm.find('.thumb-upload');        
        this.$thumbWrapper = this.$thumbModal.find('.thumb-wrapper');
        this.$thumbPreview = this.$thumbModal.find('.thumb-preview');
        this.$thumbButtons = this.$thumbModal.find('.thumb-btns');
        this.initialize();
    }

    $.Cropupload = Cropupload;

    Cropupload.prototype = {
        constructor: Cropupload,
        support: {
            fileList: !!$('<input type="file">').prop('files'),
            blobURLs: !!window.URL && URL.createObjectURL,
            formData: !!window.FormData
        },
        setOptions: function (options) {
            return this.options = $.extend({}, $.fn.cropupload.defaults, this.$el.data(), options);
        },
        setLocale: function () {
            var locale = this.options.locale;
            return this.lang = $.extend({}, $.fn.cropupload.locales[locale]);
        },
        initialize: function () {
            this.support.datauri = this.support.fileList && this.support.blobURLs;
            if (!this.support.formData) {
                this.initIframe();
            }
            this.$thumbModal.modal('hide');
            this.$thumb.on('click', $.proxy(this.click, this));
            this.$thumbUpload.on('click', $.proxy(this.directUpload, this));
            this.$thumbInput.on('change', $.proxy(this.change, this));
            this.$thumbForm.on('submit', $.proxy(this.submit, this));
            this.$el.on('change', $.proxy(this.resetThumb, this));
            this.initButtons();
        },
        initIframe: function () {
            var target = 'upload-iframe-' + (new Date()).getTime();
            var $iframe = $('<iframe>').attr({
                name: target,
                src: ''
            });
            var that = this;

            // Ready ifrmae
            $iframe.one('load', function () {

                // respond response
                $iframe.on('load', function () {
                    var data;

                    try {
                        data = $(this).contents().find('body').text();
                    } catch (e) {
                        //console.log(e.message);
                    }

                    if (data) {
                        try {
                            data = $.parseJSON(data);
                        } catch (e) {
                            //console.log(e.message);
                        }
                        that.submitDone(data);
                    } else {
                        that.submitFail(that.lang.upload_error);
                    }

                    that.submitEnd();

                });
            });

            this.$iframe = $iframe;
            this.$thumbForm.attr('target', target).after($iframe.hide());
        },
        initButtons: function () {
            var that = this;
            this.$thumbButtons.on('click', 'a.btn', function (e) {
                e.preventDefault();
                if (that.active) {
                    var data = $(this).data();
                    if (data.method) {
                        that.$img.cropper(data.method, data.option, data.secondOption);
                    }
                }
                return false;
            }); 
            var ratio = this.options.aspectRatio;
            if(ratio <= 1.1){
                this.$thumbButtons.find('input[name="aspectRatio"][value="1"]').prop('checked', true).trigger('change');
                this.options.aspectRatio = 1;
            }else if(ratio < 1.6){
                this.$thumbButtons.find('input[name="aspectRatio"][value="1.3333333333333333"]').prop('checked', true).trigger('change');
                this.options.aspectRatio = 1.3333333333333333;
            }else{
                this.$thumbButtons.find('input[name="aspectRatio"][value="1.7777777777777777"]').prop('checked', true).trigger('change');
                this.options.aspectRatio = 1.7777777777777777;
            }
            this.$thumbButtons.on('change', 'input[name="aspectRatio"]', function (e) {
               var ratio = $(this).val();
               that.options.aspectRatio = ratio;
               if (that.active) {
                   that.$img.cropper('setAspectRatio', ratio);
               }               
            });            
        },
        resetThumb:function () {
            var src = this.$el.val();
            if (src == '') {
                src = this.options.addimg;
            }
            this.$thumb.attr('src',src);
        },
        click: function () {
            var src = this.$el.val();
            if (src == '') {
                src = this.options.blankimg;
            }
            this.$thumbPreview.html('<img src="' + src + '">');
            this.$thumbModal.modal('show');
        },
        change: function () {
            var files;
            var file;
            file = this.$thumbInput.val();
            this.$thumbName.text(file);
            if (this.support.datauri) {
                files = this.$thumbInput.prop('files');

                if (files.length > 0) {
                    file = files[0];

                    if (this.isImageFile(file)) {
                        if (this.url) {
                            URL.revokeObjectURL(this.url);
                        }

                        this.url = URL.createObjectURL(file);
                        this.startCropper();
                    }
                }
            } else {                
                if (this.isImageFile(file)) {
                    this.syncUpload();
                }
            }
        },
        submit: function (e) {
            e.preventDefault();
            if (!this.$thumbInput.val()) {
                return false;
            }
            if (this.support.formData) {
                this.ajaxUpload();
            }
            return false;
        },
        isImageFile: function (file) {
            if (file.type) {
                return /^image\/\w+$/.test(file.type);
            } else {
                return /\.(jpg|jpeg|png|gif|bmp)$/.test(file);
            }
        },
        startCropper: function () {
            var that = this;

            if (this.active) {
                this.$img.cropper('replace', this.url);
            } else {
                this.$img = $('<img src="' + this.url + '">');
                this.$thumbWrapper.empty().html(this.$img);
                this.$img.cropper({
                    aspectRatio: this.options.aspectRatio,
                    preview: this.$thumbPreview.selector,
                    strict: false,
                    crop: function (e) {
                        var json = [
                            '{"x":' + e.x,
                            '"y":' + e.y,
                            '"height":' + e.height,
                            '"width":' + e.width,
                            '"rotate":' + e.rotate + '}'
                        ].join();

                        that.$thumbData.val(json);
                    }
                });

                this.active = true;
                this.$thumbSave.button('enable');
                this.$thumbUpload.button('enable');
            }

            this.$thumbModal.one('hidden.bs.modal', function () {
                that.$thumbPreview.empty();
                that.stopCropper();
            });
        },
        stopCropper: function () {
            if (this.active) {
                this.$img.cropper('destroy');
                this.$img.remove();
                this.active = false;
                this.$thumbSave.button('disabled');
                this.$thumbUpload.button('disabled');
            }
        },
        ajaxUpload: function () {
            var url = this.$thumbForm.attr('action');
            var data = new FormData(this.$thumbForm[0]);
            var that = this;

            $.ajax(url, {
                type: 'post',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function () {
                    that.submitStart();
                },
                success: function (data) {
                    that.submitDone(data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    switch (XMLHttpRequest.status) {
                        case 403:
                            that.submitFail(that.lang.upload_forbidden);
                            break;
                        case 422:
                            if (typeof (XMLHttpRequest.responseJSON.file) != 'undefined') {
                                that.submitFail(XMLHttpRequest.responseJSON.file.join(','));
                            } else {
                                that.submitFail(that.lang.upload_error);
                            }
                            break;
                        default:
                            that.submitFail(that.lang.upload_error);
                    }
                },
                complete: function () {
                    that.submitEnd();
                }
            });
            return false;
        },
        directUpload: function (e) {            
            e.preventDefault();
            if (!this.$thumbInput.val()) {
                return false;
            }
            if (this.support.formData) {
                this.$thumbData.val('{}');
                this.ajaxUpload();
            }
            return false;
        },
        syncUpload: function () {
            this.$thumbSave.click();
        },
        submitStart: function () {
            this.$thumbSave.button('loading');
        },
        submitDone: function (rs) {
            if ($.isPlainObject(rs)) {
                this.url = rs.url + '?r=' + Math.random();
                if (this.support.datauri || this.uploaded) {
                    this.uploaded = false;
                    this.$el.val(rs.url);
                    this.cropDone();
                } else {
                    this.uploaded = true;
                    this.startCropper();
                }
                this.$thumbInput.val('');
            } else {
                alert(this.lang.upload_error);
            }
        },
        submitFail: function (msg) {
            alert(msg);
        },
        submitEnd: function () {
            this.$thumbSave.button('reset');
        },
        cropDone: function () {
            this.$thumbForm.get(0).reset();
            this.$thumbName.text('未选择');
            this.$thumb.attr('src', this.url);            
            this.stopCropper();
            this.$thumbSave.button('disabled');
            this.$thumbUpload.button('disabled');
            this.$thumbModal.modal('hide');
        }
    }

    // Create chainable jQuery plugin:
    $.fn.cropupload = function (option, args) {
        var dataKey = 'cropupload';
        return this.each(function () {
            var $this = $(this)
                    , data = $this.data(dataKey)
                    , options = typeof option == 'object' && option
            if (!data)
                $this.data(dataKey, (data = new Cropupload(this, options)))
            if (typeof option == 'string')
                data[option]()
        });
    };

    $.fn.cropupload.defaults = {
        url: null,
        zindex: 99999,
        height: 80,
        aspectRatio: 1.33,
        field: "file",
        title: "Upload Image",
        addimg: "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=",
        blankimg: "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=",
        locale: 'zh-CN'
    };

    $.fn.cropupload.locales = {
        'zh-CN': {
            click_to_upload: '点击上传',
            upload_image: '上传图片',
            choose_image: '选择图片',
            choose_none: '未选择',
            mouse_scale: '可以使用鼠标滚轮缩放',
            aspect_ratio: '宽高比',
            zoom_in: '放大',
            zoom_out: '缩小',
            move_left: '左移',
            move_right: '右移',
            move_up: '上移',
            move_down: '下移',
            rotate_left: '逆时针旋转',
            rotate_right: '顺时针旋转',
            uploading: '正在上传...',
            direct_upload: '上传原图',
            save: '保存裁剪结果',
            upload_error: '图片上传失败',
            upload_forbidden: '无权上传图片'
        }
    }

    $(function () {
        $("[data-toggle='cropupload']").cropupload();
    });

}(window.jQuery);