/**
 * @author: aperez <aperez@datadec.es>
 * @version: v2.0.0
 *
 * @update Dennis Hernández <http://djhvscf.github.io/Blog>
 */

!function ($) {
    'use strict';

    var firstLoad = false;

    var sprintf = $.fn.bootstrapTable.utils.sprintf;

    var searchClear = function (that) {
        var mid = that.options.searchModalId;
        var $form = $('#form_' + mid);        
        if ($form.length) {
            $form[0].reset();
            $('a[data-toggle="dropdown"]', $form).suidropdown("choose", 'like');
        }
        that.$toolbar.find('>.search input').val('');
        that.searchText = '';
        that.onColumnAdvancedSearch();
    }

    var showAvdSearch = function (pColumns, searchTitle, searchText, that) {
        if (!$("#avdSearchModal" + "_" + that.options.searchModalId).hasClass("modal")) {
            var vModal = sprintf("<div id=\"avdSearchModal%s\"  class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"mySmallModalLabel\" aria-hidden=\"true\">", "_" + that.options.searchModalId);
            vModal += "<div class=\"modal-dialog\">";
            vModal += " <div class=\"modal-content\">";
            vModal += "  <div class=\"modal-header\">";
            vModal += "   <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\" >&times;</button>";
            vModal += sprintf("   <h4 class=\"modal-title\">%s</h4>", searchTitle);
            vModal += "  </div>";
            vModal += "  <div class=\"modal-body modal-body-custom\">";
            vModal += sprintf("   <div class=\"container-fluid\" id=\"avdSearchModalContent%s\" style=\"padding-right: 0px;padding-left: 0px;\" >", "_" + that.options.searchModalId);
            vModal += "   </div>";
            vModal += "  </div>";
            vModal += "  </div>";
            vModal += " </div>";
            vModal += "</div>";

            $("body").append($(vModal));

            var vFormAvd = createFormAvd(pColumns, searchText, that),
                    timeoutId = 0;
            ;

            $('#avdSearchModalContent' + "_" + that.options.searchModalId).append(vFormAvd.join(''));

            $("#btnSearch" + "_" + that.options.searchModalId).click(function () {
                that.onColumnAdvancedSearch();
                $("#avdSearchModal" + "_" + that.options.searchModalId).modal('hide');
            });

            $("#btnReset" + "_" + that.options.searchModalId).click(function () {
                searchClear(that);
            });

            $("#btnCloseAvd" + "_" + that.options.searchModalId).click(function () {
                $("#avdSearchModal" + "_" + that.options.searchModalId).modal('hide');
            });

            $("#avdSearchModal" + "_" + that.options.searchModalId).modal();
        } else {
            $("#avdSearchModal" + "_" + that.options.searchModalId).modal();
        }
    };

    var createFormAvd = function (pColumns, searchText, that) {
        var htmlForm = [];
        htmlForm.push(sprintf('<form class="form form-horizontal" id="form_%s" action="%s" >', that.options.searchModalId, that.options.actionForm));
        for (var i in pColumns) {
            var vObjCol = pColumns[i];
            if (!vObjCol.checkbox && vObjCol.visible && vObjCol.searchable) {
                htmlForm.push('<div class="control-group">');
                htmlForm.push(sprintf('<label class="control-label">%s</label>', vObjCol.title));
                htmlForm.push('<div class="controls">');
                htmlForm.push('<span class="dropdown dropdown-bordered select"><span class="dropdown-inner">');
                htmlForm.push(sprintf('<a class="dropdown-toggle" href="#" data-toggle="dropdown" data-trigger="hover" role="button" id="drop_%s">', vObjCol.field));
                htmlForm.push(sprintf('<input type="hidden" name="operator_%s" value="like">', vObjCol.field));
                htmlForm.push('<i class="caret"></i><span>模糊匹配</span></a>');
                htmlForm.push(sprintf('<ul class="dropdown-menu" aria-labelledby="drop_%s" role="menu" id="menu_%s">', vObjCol.field, vObjCol.field));
                htmlForm.push('<li class="active" role="presentation"><a value="like" href="javascript:void(0);" tabindex="-1" role="menuitem">模糊匹配</a></li>');
                htmlForm.push('<li role="presentation"><a value="=" href="javascript:void(0);" tabindex="-1" role="menuitem">完全匹配</a></li>');
                htmlForm.push('<li role="presentation"><a value=">" href="javascript:void(0);" tabindex="-1" role="menuitem">&gt;</a></li>');
                htmlForm.push('<li role="presentation"><a value=">=" href="javascript:void(0);" tabindex="-1" role="menuitem">&gt;=</a></li>');
                htmlForm.push('<li role="presentation"><a value="<" href="javascript:void(0);" tabindex="-1" role="menuitem">&lt;</a></li>');
                htmlForm.push('<li role="presentation"><a value="<=" href="javascript:void(0);" tabindex="-1" role="menuitem">&lt;=</a></li>');
                htmlForm.push('<li role="presentation"><a value="!=" href="javascript:void(0);" tabindex="-1" role="menuitem">!=</a></li>');
                htmlForm.push('</ul></span></span>');
                htmlForm.push(sprintf('<input type="text" class="form-control" name="%s" placeholder="%s" id="%s">', vObjCol.field, vObjCol.title, vObjCol.field));
                htmlForm.push('</div>');
                htmlForm.push('</div>');
            }
        }

        htmlForm.push('<div class="control-group">');
        htmlForm.push('<label class="control-label"></label><div class="controls">');
        htmlForm.push(sprintf('<button type="button" id="btnSearch%s" class="btn btn-success" >%s</button> ', "_" + that.options.searchModalId, that.options.formatSearch()));
        htmlForm.push(sprintf('<button type="button" id="btnReset%s" class="btn btn-default" >%s</button> ', "_" + that.options.searchModalId, that.options.formatAdvancedResetButton()));
        htmlForm.push('</div>');
        htmlForm.push('</div>');
        htmlForm.push('</form>');

        return htmlForm;
    };

    $.extend($.fn.bootstrapTable.defaults, {
        advancedSearch: false,
        actionForm: '',
        searchModalId: (Math.random().toString(36)).slice(2, 6),
        onColumnAdvancedSearch: function () {
            return false;
        }
    });

    $.extend($.fn.bootstrapTable.defaults.icons, {
        advancedSearchIcon: 'icon-search',
        searchClearIcon: 'icon-remove-sign'
    });

    $.extend($.fn.bootstrapTable.Constructor.EVENTS, {
        'column-advanced-search.bs.table': 'onColumnAdvancedSearch'
    });

    $.extend($.fn.bootstrapTable.locales, {
        formatSearchClear: function () {
            return "清除搜索条件";
        },
        formatAdvancedSearch: function () {
            return "高级搜索";
        },
        formatAdvancedCloseButton: function () {
            return "关闭";
        },
        formatAdvancedResetButton: function () {
            return "清空";
        }
    });

    $.extend($.fn.bootstrapTable.defaults, $.fn.bootstrapTable.locales);

    var BootstrapTable = $.fn.bootstrapTable.Constructor,
            _initToolbar = BootstrapTable.prototype.initToolbar,
            _load = BootstrapTable.prototype.load,
            _initSearch = BootstrapTable.prototype.initSearch;

    BootstrapTable.prototype.initToolbar = function () {
        _initToolbar.apply(this, Array.prototype.slice.apply(arguments));

        if (!this.options.advancedSearch) {
            return;
        }

        var that = this,
                html = [];

        html.push(sprintf('<button class="btn btn-default%s' + '" type="button" name="advancedSearch" title="%s">', that.options.iconSize === undefined ? '' : ' btn-' + that.options.iconSize, that.options.formatAdvancedSearch()));
        html.push(sprintf('<i class="%s %s"></i>', that.options.iconsPrefix, that.options.icons.advancedSearchIcon))
        html.push('</button>');
        html.push(sprintf('<button class="btn btn-default%s' + '" type="button" name="searchClear" title="%s">', that.options.iconSize === undefined ? '' : ' btn-' + that.options.iconSize, that.options.formatSearchClear()));
        html.push(sprintf('<i class="%s %s"></i>', that.options.iconsPrefix, that.options.icons.searchClearIcon))
        html.push('</button>');

        that.$toolbar.find('>.btn-group').prepend(html.join(''));

        that.$toolbar.find('button[name="advancedSearch"]')
                .off('click').on('click', function () {
            showAvdSearch(that.columns, that.options.formatAdvancedSearch(), that.options.formatAdvancedCloseButton(), that);
        });
        that.$toolbar.find('button[name="searchClear"]')
                .off('click').on('click', function () {
            searchClear(that);
        });

    };

    BootstrapTable.prototype.load = function (data) {
        _load.apply(this, Array.prototype.slice.apply(arguments));

        if (!this.options.advancedSearch) {
            return;
        }

        if (typeof this.options.searchModalId === 'undefined') {
            return;
        } else {
            if (!firstLoad) {
                var height = parseInt($(".bootstrap-table").height());
                height += 10;
                $("#" + this.options.searchModalId).bootstrapTable("resetView", {height: height});
                firstLoad = true;
            }
        }
    };

    BootstrapTable.prototype.initSearch = function () {
        _initSearch.apply(this, Array.prototype.slice.apply(arguments));

        if (!this.options.advancedSearch) {
            return;
        }
    };

    BootstrapTable.prototype.onColumnAdvancedSearch = function () {
        var filter = {};
        var mid = this.options.searchModalId;
        var $form = $('#form_' + mid);
        if ($form.length) {
            var pColumns = this.columns;
            for (var i in pColumns) {
                var vObjCol = pColumns[i];
                if (!vObjCol.checkbox && vObjCol.visible && vObjCol.searchable) {
                    var field = vObjCol.field;
                    var text = $form.find('input[name="' + field + '"]').val();
                    if (text !== '') {
                        var operator = $form.find('input[name="operator_' + field + '"]').val();
                        filter[field] = [operator, text];
                    }
                }
            }
        }        
        this.filterColumnsPartial = filter;
        this.options.pageNumber = 1;        
        this.updatePagination();
        this.trigger('column-advanced-search', filter);
    };
}(jQuery);
