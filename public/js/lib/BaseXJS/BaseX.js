/*
 * 
 * Coded by: Carlos Sing Ramos
 * @returns {undefined}
 */


var BaseX = function () {

};
BaseX.formatDate = 'YYYY-MM-DD';

BaseX.ajaxConfig = function (options) {
    var options = BaseX.xval(options, {});
    jQuery.ajaxSetup({
        cache: BaseX.xval(options.cache, false), // options.cache === undefined ? false : options.cache,
        async: BaseX.xval(options.async, false), //options.async === undefined ? false : options.async,
    });
};

BaseX.xval = function (variable, value) {
    return (variable === undefined ? value : variable);
};

BaseX.xset = function (variable, value) {
    return (variable === undefined ? value : variable);
};


BaseX.getJson = function (options) {
    BaseX.ajaxConfig();

};

BaseX.post = function (options) {
    BaseX.ajaxConfig();
    jQuery.ajax({
        url: options.url,
        method: "POST",
        type: 'json',
        data: options.data,
        success: options.success
    });

};

BaseX.get = function (options) {
    BaseX.ajaxConfig();
    jQuery.ajax({
        url: options.url,
        method: BaseX.xval(options.method, 'GET'),
        type: 'json',
        data: options.data,
        success: options.success
    });
};

BaseX.load_html = function (url, options) {

    if (options.data === undefined) {
        options.data = {};
    }
    jQuery.ajaxSetup({
        async: false,
        cache: false,
    });

    jQuery.ajax({
        url: url, //root + '/dashboard/index/presentacion',
        data: options.data,
        method: 'GET',
        success: options.success
    });
};

BaseX.ArraySortBy = function () {
    var _toString = Object.prototype.toString,
            _parser = function (x) {
                return x;
            }
    _getItem = function (x) {
        return this.parser((_toString.call(x) === "[object Object]" && x[this.prop]) || x)
    };

    return function (array, o) {
        if (!(array instanceof Array) || !array.length)
            return [];
        if (_toString.call(o) !== "[object Object]")
            o = {};

        if (typeof o.parser !== "function")
            o.parser = _parser;

        o.desc = [1, -1][+!!o.desc];
        return array.sort(function (a, b) {
            a = _getItem.call(o, a);
            b = _getItem.call(o, b);
            return ((a > b) - (b > a)) * o.desc;
        });
    }
};

/* 
 * GRID
 */

BaseX.Grid = function (id, options) {


    var _options = {};
    var _sw_options = false;
//    console.debug(options);

    if (options === undefined) {
        _options = {};
        options = {};
//        console.debug(options.url !== undefined);
//        console.debug(options.url == undefined);

        _sw_options = false;
    } else {
        _sw_options = true;
    }
    var _options = BaseX.xval(options, {});
    _options.url = options.url !== undefined ? options.url : '';
    _options.datatype = options.datatype !== undefined ? options.datatype : 'json';
    _options.styleUI = options.styleUI !== undefined ? options.styleUI : 'Bootstrap';
    _options.mtype = options.mtype !== undefined ? options.mtype : 'GET';
    _options.colModel = options.colModel !== undefined ? options.colModel : [];
    _options.rowNum = options.rowNum !== undefined ? options.rowNum : 10;
    _options.pager = options.pager !== undefined ? options.pager : id + '_pager';
    _options.rowList = options.rowList !== undefined ? options.rowList : [5, 10, 20, 30];
    _options.height = options.height !== undefined ? options.height : 240;
    _options.sortname = options.sortname !== undefined ? options.sortname : '';
    _options.viewrecords = options.viewrecords !== undefined ? options.viewrecords : true;
    _options.gridview = options.gridview !== undefined ? options.gridview : true;
    _options.sortorder = options.sortorder !== undefined ? options.sortorder : 'asc';

//    _options.url = BaseX.xval(options.url, '');
//    _options.datatype = BaseX.xval(options.datatype, 'json');
//    _options.styleUI = BaseX.xval(options.styleUI, 'Bootstrap');
//    _options.mtype = BaseX.xval(options.mtype, 'GET');
//    _options.colModel = BaseX.xval(options.colModel, []);
//    _options.rowNum = BaseX.xval(options.rowNum, 10);
//    _options.pager = BaseX.xval(options.pager, id + '_pager');
//    _options.rowList = BaseX.xval(options.rowList, [5, 10, 20, 30]);
//    _options.height = BaseX.xval(options.height, 240);
//    _options.sortname = BaseX.xval(options.sortname, '');
//    _options.viewrecords = BaseX.xval(options.viewrecords, true);
//    _options.gridview = BaseX.xval(options.gridview, true);
//    _options.sortorder = BaseX.xval(options.sortorder, 'asc');

    if (_sw_options === true) {
        var grid = jQuery(id).jqGrid(_options);
    } else {
        var grid = jQuery(id);
    }
    grid._id = id;
    grid._options = _options;

//    console.debug(grid._options);
    grid.addButton = function (options) {

        var _button = BaseX.xval(options, {});

        _button.caption = BaseX.xval(options.caption, '');
        _button.buttonicon = BaseX.xval(options.buttonicon, '');
        _button.position = BaseX.xval(options.position, 'first');
        _button.title = BaseX.xval(options.title, '');
        _button.cursor = BaseX.xval(options.cursor, 'pointer');

        jQuery(id).jqGrid('navButtonAdd', this._options.pager, _button);

        return this.grid;
    };
    grid.getSelectedRow = function () {
        var rowKey = jQuery(this._id).jqGrid('getGridParam', "selrow");
        if (rowKey)
            return rowKey;
        else
            return null;
    };
    grid.getSelectedRowData = function () {

        var rowId = this.getSelectedRow();
        if (rowId === null) {
            return null;
        }
        var rowData = jQuery(this._id).getRowData(rowId);
        return rowData;
    };

    grid.refresh = function () {
//        console.log('Refresh');
        return jQuery(this._id).trigger('reloadGrid');
    };

    grid.getCheckedRows = function () {
        return jQuery(this._id).jqGrid('getGridParam', 'selarrrow');
    };


    grid.setData = function (data) {

        jQuery(this._id).jqGrid('setGridParam',
                {
                    datatype: 'local',
                    data: data
                })
                .trigger("reloadGrid");
    };

    grid.clear = function () {
        jQuery(this._id).jqGrid("clearGridData", true).trigger("reloadGrid");
    };
    grid.setSelectedRows = function (rows_id) {

        var i = 0;
        for (i = 0; i < rows_id.length; i++) {
            jQuery(this._id).jqGrid('setSelection', rows_id[i]);
        }
        grid.refresh();

    };

    grid.removeRow = function (rowId) {
        if (rowId === undefined) {

            rowId = parseInt(grid.getSelectedRow());

        }
        jQuery(this._id).jqGrid('delRowData', rowId);
        grid.refresh();
    };

//    grid.addRow = function (rowid, data, position, srcrowid) {
//
//        this.grid.jqGrid('addRowData', rowid, data, position,srcrowid);
//        this.grid.addRowData(1, [{lengua_desc: 1}]);
//
//    };


//    function jqGrid_get_row_selected(id) {
//        var selId = jQuery(id).jqGrid('getGridParam', 'selrow');
//        return selId;
//    }

//    function jqGrid_get_row_selected_data(id) {
//        var rowId = jQuery(id).jqGrid('getGridParam', 'selrow');
//        var rowData = jQuery(id).getRowData(rowId);
//        return rowData;
//    }
    return grid;

};

function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;

    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }

}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};


BaseX.get_data_sp = function (params) {

    if (params !== undefined || params !== '') {
        try {
            var _xml_data = JSON.parse(Base64.decode(params));
            if (Object.keys(_xml_data).length > 0) {
                return _xml_data;
            } else {
                return undefined;
            }
        } catch (e) {
            return undefined;
        }
    } else {
        return undefined;
    }
};

BaseX.disabled_form = function (data_hide, data_disabled, data_html_clear) {
    if (data_hide.length > 0) {
        $.each(data_hide, function (i, val) {
            jQuery('#' + val).hide();
        });
    }
    if (data_disabled.length > 0) {
        $.each(data_disabled, function (e, valu) {
            jQuery('#' + valu).prop('disabled', true);
        });
    }
    if (data_html_clear.length > 0) {
        $.each(data_html_clear, function (o, valu) {
            jQuery('#' + valu).html('');
        });
    }
};
BaseX.enabled_form = function (data) {
    if (data.length > 0) {
        $.each(data, function (index, value) {
            jQuery('#' + value).prop('disabled', true);
        });
    }
};


BaseX.dialogAceptar = function (options) {

    bootbox.dialog({
        message: options.message,
        closeButton: false,
        title: 'Mensaje del sistema',
        buttons: {
            success: {
                label: "Aceptar",
                className: "btn boton btn-aceptar-basex",
                callback: options.success.callback
            }
        }
    });
};

BaseX.dialogAceptarMsg = function (options) {

    bootbox.dialog({
        message: options.message,
        title: 'Mensaje del sistema',
        buttons: {
            success: {
                label: "Aceptar",
                className: "btn boton",
                callback: options.success.callback
            }
        },
        onEscape: options.onscape.callback
    });
};


//BaseX.render = function (tpl, obj) {
//    var template = $(tpl).html();
//    Mustache.parse(template); // optional, speeds up future uses
//    var rendered = Mustache.render(template, obj);
//    $(tpl).html(rendered);
//}


$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

BaseX.sanitizer = function (data) {
    var data_clean = [];

    if (data instanceof Array) {

        for (i = 0; i < data.length; i++) {
            data_clean.push(this.sanitizer(data[i]));
        }
    } else {
        for (var key in data) {
            if (data[key] instanceof Object) {

            } else {
                data[key] = strict_safify(data[key] + '');
            }
        }
        return data;
    }
    return data_clean;
};

BaseX.sanitizer_v = function (data) {

    if (Object.keys(data).length === 0) {

        return undefined;
    }

    var target = data.code + data.code;
    var uuid = data.uuid;
    var hash = target;
    var n = 3;
    var u = 15;
    while (n <= u)
    {
        if (hash === uuid) {
            break;
        }
        if (n === u && hash !== uuid) {
            hash = undefined;
            break;
        }
        hash = Kakashi.hash(hash);
        n++;
    }
    return hash;
};


BaseX.restoreHtml = function (str) {
    return String(str)
//        .replace(/&/g, "&amp;")
//        .replace(/</g, "&lt;")
//        .replace(/>/g, "&gt;")
//        .replace(/"/g, "&quot;")
//        .replace(/'/g, "&#039;")
            .replace(/&#x2F;/g, '/');
}


function enableTab(id, href, sw) {

    if (sw === true) {
        jQuery(id + ' a[href=' + href + ']').parent('li').removeClass('disabled');
    }
    if (sw === false) {
        jQuery(id + ' a[href=' + href + ']').parent().addClass('disabled');
    }
}
function showTab(id, href) {
    $(id + ' a[href="' + href + '"]').tab('show');
}

function radio_checked(name, value, checked) {
    var selector = 'input[name=' + name + '][value="' + value + '"]';
//    console.debug(selector);
    jQuery(selector).prop('checked', checked);
    if (checked === false) {
        jQuery(selector).parent('.btn').remove('active');
    } else {
        jQuery(selector).parent('.btn').addClass('active');
    }

}


// Templates


function Tpl2HtmlRender(source, data) {
    var _source = jQuery(source).html();

//        console.debug(template);
//        Mustache.parse(template);   // optional, speeds up future uses
    var template = Handlebars.compile(_source);
    var html = template(data);
    return html;

}


/*
 * Validar que radiobuttons no están siendo marcados
 * 
 */

function validar_rb_no_vacios(rbs, form) {
    var len = rbs.length;
    var form_obj = jQuery(form).serializeObject();
//    console.debug(form_obj);
    for (i = 0; i < len; i++) {

//        console.debug(form_obj[rbs[i]]);

        if (form_obj[rbs[i]] === undefined) {
            return false;
        }

    }
    return true;
}

/*
 * Validar fechas
 */

function validate_format_dd_mm_yyyy(value) {

    var pattern = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
    if (value === null || value === "" || !pattern.test(value)) {
        return false;
    } else {
        return true
    }
}

