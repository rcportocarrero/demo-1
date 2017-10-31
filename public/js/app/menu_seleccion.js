function menu_instrumento() {

    load_instrumentos();
    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#mnuInstrumento').addClass('clicked');

}

function load_instrumentos() {
    BaseX.load_html(root + '/seleccion/index/instrumentos', {
        data: {},
        success: function (xhr) {
            jQuery('#workArea').html(xhr);

            GridInstrumentos('#grid_instrumentos');
            GridInstrumentosData();

            evt_chg_dre_ugel_ins();
            //Btn limpiar formulario de inscripción
            jQuery('#btn_limpiar_ins').off('click');
            jQuery('#btn_limpiar_ins').click(function (e)
            {
                jQuery('#frm_instrumento')[0].reset();
                var resultado = instrumentos_listar({});
                BaseX.Grid('#grid_instrumentos').clear();
                BaseX.Grid('#grid_instrumentos').setData(resultado);
            });
            //Btn buscar formulario de inscripción
            jQuery('#btn_search_ins').off('click');
            jQuery('#btn_search_ins').click(function (e)
            {
                var obj = {
                    'le': jQuery('#lista_estrategia').val(),
                    'li': jQuery('#lista_intervencion').val(),
                    'la': jQuery('#lista_ambito').val(),
                    'lm': jQuery('#lista_muestra').val(),
                };

                var resultado = instrumentos_listar(obj);
                BaseX.Grid('#grid_instrumentos').clear();
                BaseX.Grid('#grid_instrumentos').setData(resultado);

            });

            jQuery('#btn_descarga_formato').off('click');
            jQuery('#btn_descarga_formato').click(function () {
                if (valida_sesion(0) === false) {
                    return;
                }
                var url = root + '/seleccion/index/pdfdw';
                window.open(url, '_blank');
            });

            jQuery('[data-toggle="tooltip"]').tooltip();
        }
    });
}
function load_detalle_instrumentos() {
    BaseX.load_html(root + '/seleccion/index/instrumentosdet', {
        data: {},
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
            jQuery('#srch-iiee').filter_input({regex: _strings.app.validate.diccionario_iiee});
            jQuery('#srch-ndoc').filter_input({regex: _strings.app.validate.diccionario_numeros});
            jQuery('#srch-name').filter_input({regex: _strings.app.validate.diccionario_nombres});
            GridInstrumentosDetail('#grid_instrumentos_detail');
            GridInstrumentosDetailData({});
            //Evento de cambio de dre
            evt_chg_dre_ugel();
            //Btn limpiar detalle de instrumento
            jQuery('#btn_limpiar_ins_det').off('click');
            jQuery('#btn_limpiar_ins_det').click(function (e)
            {
                jQuery('#frm_instrumento_det')[0].reset();
                GridInstrumentosDetailData({});
            });
            jQuery('#btn_search_ins_det').off('click');
            jQuery('#btn_search_ins_det').click(function (e)
            {
                var obj = {
                    d: jQuery('#lista_dre').val(),
                    u: jQuery('#lista_ugel').val(),
                    i: jQuery('#srch-iiee').val(),
                    n: jQuery('#srch-name').val(),
                    k: jQuery('#srch-ndoc').val(),
                };
                GridInstrumentosDetailData(obj);
            });
        }
    });
}

function GridInstrumentosData(obj) {
    BaseX.Grid('#grid_instrumentos').clear();
    var listas_data = instrumentos_listar({});
    BaseX.Grid('#grid_instrumentos').setData(listas_data);
}
function GridInstrumentosDetailData(obj) {
    BaseX.Grid('#grid_instrumentos_detail').clear();
    var listas_data = instrumentos_detail_listar(obj);
    BaseX.Grid('#grid_instrumentos_detail').setData(listas_data);
    jQuery('[data-toggle="tooltip"]').tooltip();
}

function instrumentos_listar(obj) {
    if (obj === undefined) {
        obj = {};
    }
    var data;
    BaseX.get({
        url: root + '/seleccion/index/get-instrumentos',
        data: obj,
        success: function (xhr, txt) {
            data = xhr;
        }
    });

    return data;
}
function instrumentos_detail_listar(obj) {
    if (obj === undefined) {
        obj = {};
    }
    var data;
    BaseX.get({
        url: root + '/seleccion/index/get-instrumentos-detail',
        data: obj,
        success: function (xhr, txt) {
            data = xhr.empleados;

        }
    });

    return data;
}
function preguntas_fec_listar() {
    var obj = {};
    BaseX.get({
        url: root + '/seleccion/index/get-preguntas-frecuentes',
        data: obj,
        success: function (xhr, txt) {
            data = xhr;
        }
    });
    return data;
}
function preguntas_listar(obj) {
    if (obj === undefined) {
        obj = {};
    }
    var data;
    BaseX.get({
        url: root + '/seleccion/index/get-preguntas',
        data: obj,
        success: function (xhr, txt) {
            data = xhr;
        }
    });

    return data;
}
function set_datos_instrumento(obj) {
    if (obj === undefined) {
        obj = {};
    }
    var data;
    BaseX.post({
        url: root + '/seleccion/index/set-instrumentos',
        data: obj,
        success: function (xhr, txt) {
        }
    });

    return data;
}

function cargar_dre(md) {
    jQuery("#lista_dre").html("");
    var cmb2 = [];
    cmb2.push('<option value="0">--Seleccione--</option>');
    jQuery.each(md, function (key, val) {
        cmb2.push('<option value="' + val["ID_DRE"] + '">' + val["NOMBRE"] + '</option>');
    });

    jQuery("#lista_dre").html(cmb2.join(''));

}
function evt_chg_dre_ugel_ins() {
    jQuery('#lista_dre_ins').off('change');
    jQuery('#lista_dre_ins').on('change', function () {
        if (jQuery('#lista_dre_ins').val() !== '') {
            //cargar combo ugeles
            var obj = {
                id: jQuery('#lista_dre_ins').val()
            };
            BaseX.get({
                url: root + '/seleccion/index/get-ugel-dre-ins',
                data: obj,
                success: function (xhr, txt) {
                    jQuery("#lista_ugel_ins").html("");
                    var cmb2 = [];
                    cmb2.push('<option value="0">--Seleccione--</option>');
                    jQuery.each(xhr, function (key, val) {
                        cmb2.push('<option value="' + val["ID_UGEL"] + '">' + val["NOMBRE"] + '</option>');
                    });

                    jQuery("#lista_ugel_ins").html(cmb2.join(''));
                }
            });
        } else {
            jQuery("#lista_ugel_ins").html("");
        }

    });
}

function evt_chg_dre_ugel() {

    jQuery('#lista_dre').off('change');
    jQuery('#lista_dre').on('change', function () {
        if (jQuery('#lista_dre').val() !== '') {
            //cargar combo ugeles
            var obj = {
                id: jQuery('#lista_dre').val()
            };
            BaseX.get({
                url: root + '/seleccion/index/get-ugel-dre',
                data: obj,
                success: function (xhr, txt) {
                    jQuery("#lista_ugel").html("");
                    var cmb2 = [];
                    cmb2.push('<option value="0">--Seleccione--</option>');
                    jQuery.each(xhr, function (key, val) {
                        cmb2.push('<option value="' + val["ID_UGEL"] + '">' + val["NOMBRE"] + '</option>');
                    });

                    jQuery("#lista_ugel").html(cmb2.join(''));
                }
            });
        } else {
            jQuery("#lista_ugel").html("");
        }

    });
}
function GridInstrumentos(id)
{
    return BaseX.Grid(id, {
        data: [],
        datatype: "local",
        colModel: [
            {name: 'a', label: 'Id', index: 'a', width: 10, align: "left", sortable: true, hidden: true, key: true},
            {name: 'b', label: 'Nombre de instrumento', index: 'b', width: 350, align: "left", sortable: true},
            {name: 't', label: 'Meta', index: 'm', width: 90, sortable: false},
            {name: 'i', label: 'Informantes', index: 'i', width: 250, sortable: true},
            {name: 'n', label: 'Estado', index: 'n', width: 90, align: "center", formatter: estadoINS, sortable: false},
            {name: 'a', label: 'Acciones', index: 'a', width: 200, formatter: btn_acciones, align: "center", sortable: false}
        ],
        rownumbers: false,
        width: 925,
        multiselect: false,
        shrinkToFit: false,
        hidegrid: false,
        pager: '#pager_grid_instrumentos',
        resizable: true
    });
}
;
function GridInstrumentosDetail(id)
{

    return BaseX.Grid(id, {
        data: [],
        datatype: "local",
        colModel: [
            {label: 'Id', name: 'a', index: 'a', width: 10, align: "left", sortable: true, hidden: true, key: true},
            {label: 'Id instrumento empleado', name: 'id_instrumento', index: 'id_instrumento', width: 10, align: "left", sortable: true, hidden: true},
            {label: 'Instituación educativa', name: 'institucion_educativa', index: 'institucion_educativa', width: 320, align: "left", sortable: false},
            {label: 'Informantes (Nombres y apellidos)', name: 'informante', index: 'informante', width: 400, sortable: false},
            {label: 'Total', name: 'total', index: 'total', width: 250, sortable: false, hidden: true},
            {label: 'Estado', name: 'estado', index: 'estado', width: 120, align: "center", formatter: estadoINSDetail, sortable: false},
            {label: 'Acciones', name: 'id_instrumento_empleado', index: 'id_instrumento_empleado', width: 120, formatter: initInstrumento, align: "center", sortable: false},
        ],
        rownumbers: false,
        multiselect: false,
        shrinkToFit: false,
        hidegrid: false,
        pager: '#pager_grid_instrumentos_detail',
        resizable: true
    });
}
;

function btn_acciones(cellvalue, options, rowObject) {
    var objeto = {
        id: rowObject['a'],
        t: rowObject['t']
    };
    var str = JSON.stringify(objeto);
    var btn_acciones = "";
    btn_acciones += "<button type='button' class='btn btn_primary_icons btn-md' data-toggle='tooltip' data-placement='top' title='Info. del instrumento' onclick='fn_info(" + str + ")'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span></button>";
    btn_acciones += "<button type='button' class='btn btn_primary_icons btn-md' data-toggle='tooltip' data-placement='top' title='Ver informantes' onclick='fn_lista(" + str + ")'><span class='glyphicon glyphicon-th-list' aria-hidden='true'></span></button>";
    btn_acciones += "<button type='button' class='btn btn_primary_icons btn-md' data-toggle='tooltip' data-placement='top' title='Resumen' onclick='fn_report(" + str + ")'><span class='glyphicon glyphicon-list-alt' aria-hidden='true'></span></button>";
    return btn_acciones;
}

function fn_info(obj) {
    show_tplinfo(obj);
}
function fn_lista(obj) {

    var obj = {
        k: obj.id
    };
    set_datos_instrumento(obj);
    load_detalle_instrumentos();
}
function fn_report(obj) {
    modal_resumen(obj);
}

function initInstrumento(cellvalue, options, rowObject) {
    var objeto = {
        id: rowObject['id_instrumento_empleado'],
        name: rowObject['informante'],
        nameie: rowObject['institucion_educativa'],
        codmod: rowObject['codmod'],
        ndoc: rowObject['ndoc'],
        d: rowObject['d'],
        u: rowObject['ug'],
    };
    var str = JSON.stringify(objeto);
    var btn_acciones = "";
    if (parseInt(rowObject['t']) === 1) {
        btn_acciones += "<button type='button' class='btn btn_primary_icons btn-md' data-toggle='tooltip' data-placement='bottom' title='Ver respuestas' onclick='fn_preview(" + str + ")'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span></button>";
    } else {
        btn_acciones += "<button type='button' class='btn btn_primary_icons btn-md' data-toggle='tooltip' data-placement='bottom' title='Aplicar instrumento' onclick='fn_init(" + str + ")'><span class='glyphicon glyphicon glyphicon-play' aria-hidden='true'></span></button>";
    }
    return btn_acciones;
}

function fn_init(obj) {
    modal_lista_preg(obj);
}
function fn_preview(obj) {
    modal_preview(obj);
}


function estadoINS(cellvalue, options, rowObject) {
    if (parseInt(rowObject['s']) === 0) {
        return "<span class='label label-success'>" + rowObject['t'] + "</span>";
    } else {
        if (parseInt(rowObject['t']) === parseInt(rowObject['s'])) {
            return "<span class='label label-danger'>" + cellvalue + "</span>";
        } else {
            return "<span class='label label-warning'>" + cellvalue + "</span>";
        }
    }
}
function estadoINSDetail(cellvalue, options, rowObject) {
    if (parseInt(rowObject['s']) === 1) {
        if (parseInt(rowObject['u']) === 2) {
            tooltip_ = "data-placement='bottom' data-toggle='tooltip' data-original-title='Finalizado'";
        }
        return "<span class='label label-success' " + tooltip_ + ">" + cellvalue + "</span>";
    } else {
        var tooltip_;
        if (parseInt(rowObject['ns']) === 0) {
            if (parseInt(rowObject['u']) === 3) {
                tooltip_ = "data-placement='bottom' data-toggle='tooltip' data-original-title='No iniciado'";
            }
            return "<span class='label label-danger' " + tooltip_ + ">" + cellvalue + "</span>";
        } else {
            if (parseInt(rowObject['u']) === 3) {
                tooltip_ = "data-placement='bottom' data-toggle='tooltip' data-original-title='No finalizado'";
            }
            return "<span class='label label-warning' " + tooltip_ + ">" + cellvalue + "</span>";
        }
    }
}
function updateEstado(f) {
    GridInstrumentosDetailData({});
}
function modal_lista_preg(obj) {
    var id_emp = obj.id;
    var name_emp = obj.name;
    var name_ie = obj.nameie;
    var cod_mod = obj.codmod;
    var ndoc = obj.ndoc;
    var des_dre = obj.d;
    var des_ugel = obj.u;
    jQuery('#content_out').html('');
    jQuery('#modal_preguntas_tmp').modal({
        keyboard: false,
        backdrop: 'static'
    });

    var modal_preguntas = jQuery('#modal_preguntas_tmp');
    modal_preguntas.on('shown.bs.modal', function (e) {
        jQuery(this).off('shown.bs.modal');
        var data = preguntas_listar({k: id_emp});
        var source = jQuery("#tpl_listado_formato_content").html();
        var template = Handlebars.compile(source);
        jQuery('#content_out').html(template(data));
        jQuery('#emp_t_hd').val(id_emp);
        jQuery('#emp_t_informante').html("<label class='col-xs-12 col-sm-6'>Informante:</label><label class='col-sm-6 lbl-lite padd_left'> " + name_emp + "</label>");
        jQuery('#emp_t_ndoc').html("<label class='col-xs-12 col-sm-6'>DNI:</label><label class='col-sm-6 lbl-lite'> " + ndoc + "</label>");
        jQuery('#emp_t_ie').html("<label class='col-xs-12 col-sm-3'>Institución educativa:</label><label class='col-sm-9 lbl-lite'> " + cod_mod + " - " + name_ie + "</label>");
        jQuery('#emp_t_dreugel').html("<label class='col-xs-12 col-sm-3'>DRE/UGEL:</label><label class='col-sm-9 lbl-lite'> " + des_dre + " / " + des_ugel + "</label>");
        jQuery('.mySlider').slider();
        jQuery('#btn_finalizar').hide();
        var pgtas = Object.keys(data.instrumento.p).length;
        var rptas = Object.keys(data.instrumentoEmpleado.j).length;
        var preg_rpta = [];
        var preg_rpta_tipo = [];
        if (pgtas > 0) {
            for (i = 0; i < pgtas; i++) {
                var pg_grupos_ = Object.keys(data.instrumento.p[i].f).length;
                for (o = 0; o < pg_grupos_; o++) {
                    preg_rpta[data.instrumento.p[i].f[o].a] = data.instrumento.p[i].f[o].g.a;
                    preg_rpta_tipo[data.instrumento.p[i].f[o].a] = data.instrumento.p[i].f[o].d;
                }
            }
        }

        if (rptas > 0) {
            for (i = 0; i < rptas; i++) {
                if (Object.keys(data.instrumentoEmpleado.j[i].f).length > 0) {
                    //listaRespuestaRango
                    jQuery("#slctrango_" + data.instrumentoEmpleado.j[i].b + "").slider().slider('setValue', data.instrumentoEmpleado.j[i].f[0].b);
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                var multiples_ = Object.keys(data.instrumentoEmpleado.j[i].g).length;
                if (multiples_ > 0) {
                    //j.a id pregunta respuesta
                    if (preg_rpta[data.instrumentoEmpleado.j[i].b] === 'Vg') {
                        //Radio preg_rpta_tipo
                        radio_checked('optrd_' + data.instrumentoEmpleado.j[i].b, data.instrumentoEmpleado.j[i].g[0].c, true);
                    } else if (preg_rpta[data.instrumentoEmpleado.j[i].b] === 'qR') {
                        //Check
                        for (c = 0; c < multiples_; c++) {
                            radio_checked('optchk_' + data.instrumentoEmpleado.j[i].b + '', data.instrumentoEmpleado.j[i].g[c].c, true);
                        }
                    } else {
                        //Select
                        jQuery("#slct_" + data.instrumentoEmpleado.j[i].b + "").val(data.instrumentoEmpleado.j[i].g[c].c);
                    }
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                if (Object.keys(data.instrumentoEmpleado.j[i].h).length > 0) {
                    //listaRespuestaFecha
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                if (Object.keys(data.instrumentoEmpleado.j[i].i).length > 0) {
                    //listaRespuestaTexto
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }

            }
        }

        jQuery('#ul_preg li').click(function (e)
        {
            var li_active_ = $(this).attr("id");
            if (li_active_ === "li_resumen") {
                jQuery('#btn_finalizar').prop('disabled', false);
                jQuery('#btn_finalizar').show();
                jQuery('#btn_preview_').hide();
                jQuery('#btn_next_').hide();
            } else {
                jQuery('#btn_finalizar').prop('disabled', true);
                jQuery('#btn_finalizar').hide();
                jQuery('#btn_preview_').show();
                jQuery('#btn_next_').show();
            }

            var $active = $('.nav-tabs2 li.active');
            var $str = $("#frm_paso_" + $active.attr("data-pre")).serializeObject();
            var j = [];
            $.each($str, function (i, item) {
                if (item !== '' && item !== '0') {
                    var idd = i.split("_");
                    var val = idd[1];
                    var valu = val.valueOf();
                    var obj = {};
                    if (preg_rpta_tipo[valu] === 'p4') {
                        var val_c = idd[2];
                        var valu_c = val_c.valueOf();
                        var rp_f = [];
                        rp_f.push({"b": item, "c": valu_c});
                        obj = {"b": valu, "f": rp_f};
                    } else if (preg_rpta_tipo[valu] === 'Vg' || preg_rpta_tipo[valu] === 'qR' || preg_rpta_tipo[valu] === 'am') {
                        var rp_g = [];
                        if (Array.isArray(item)) {
                            for (i = 0; i < item.length; i++) {
                                rp_g.push({"c": item[i]});
                            }
                        } else {
                            rp_g.push({"c": item});
                        }
                        obj = {"b": valu, "g": rp_g};
                    } else if (preg_rpta_tipo[valu] === 'Rw') {
                        obj = {"b": valu, "h": item};
                    } else if (preg_rpta_tipo[valu] === '75' || preg_rpta_tipo[valu] === 'mR') {
                        obj = {"b": valu, "i": item};
                    }
                    j.push(obj);
                }
            });

            jQuery('#emp_t_hd').val(id_emp);
            var f_sav = {
                "a": jQuery('#emp_t_hd').val(),
                "j": j
            };
            var $post_save = JSON.stringify(f_sav);
            save_frm($post_save);
        });

        jQuery('#btn_finalizar').click(function (e)
        {
            save_frm_last();
        });

        jQuery(".next-step").click(function (e) {

            var $active = $('.nav-tabs2 li.active');
            $active.next().removeClass('disabled');
            nextTab($active);

        });
        jQuery(".prev-step").click(function (e) {

            var $active = $('.nav-tabs2 li.active');
            prevTab($active);

        });

        jQuery('[data-toggle="tooltip"]').tooltip();

    });

}
function modal_preview(obj) {
    var id_emp = obj.id;
    var name_emp = obj.name;
    var name_ie = obj.nameie;
    var cod_mod = obj.codmod;
    var ndoc = obj.ndoc;
    var des_dre = obj.d;
    var des_ugel = obj.u;
    jQuery('#content_out').html('');
    jQuery('#modal_preguntas_tmp').modal({
        keyboard: false,
        backdrop: 'static'
    });

    var modal_preguntas = jQuery('#modal_preguntas_tmp');
    modal_preguntas.on('shown.bs.modal', function (e) {
        jQuery(this).off('shown.bs.modal');
        var data = preguntas_listar({k: id_emp});
        var source = jQuery("#tpl_listado_resumen_content").html();
        var template = Handlebars.compile(source);
        jQuery('#content_out').html(template(data));
        jQuery('#emp_t_hd').val(id_emp);
        jQuery('#emp_t_informante').html("<label class='col-xs-12 col-sm-6'>Informante:</label><label class='col-sm-6 lbl-lite padd_left'> " + name_emp + "</label>");
        jQuery('#emp_t_ndoc').html("<label class='col-xs-12 col-sm-6'>DNI:</label><label class='col-sm-6 lbl-lite'> " + ndoc + "</label>");
        jQuery('#emp_t_ie').html("<label class='col-xs-12 col-sm-3'>Institución educativa:</label><label class='col-sm-9 lbl-lite'> " + cod_mod + " - " + name_ie + "</label>");
        jQuery('#emp_t_dreugel').html("<label class='col-xs-12 col-sm-3'>DRE/UGEL:</label><label class='col-sm-9 lbl-lite'> " + des_dre + " / " + des_ugel + "</label>");
        jQuery('.mySlider').slider();
        //Deshabilitar todos los campos
        jQuery(".btn-group").addClass('disabled').prop('disabled', true);
        jQuery(".checkbox :input").addClass('disabled').prop('disabled',true);
        jQuery('.mySlider').slider('disable');
        //Fin deshabilitar 
        jQuery('#btn_finalizar').prop('disabled', false);
        jQuery('#btn_finalizar').show();
        jQuery('#btn_next_').hide();



        var pgtas = Object.keys(data.instrumento.p).length;
        var rptas = Object.keys(data.instrumentoEmpleado.j).length;
        var preg_rpta = [];
        var preg_rpta_tipo = [];
        if (pgtas > 0) {
            for (i = 0; i < pgtas; i++) {
                var pg_grupos_ = Object.keys(data.instrumento.p[i].f).length;
                for (o = 0; o < pg_grupos_; o++) {
                    preg_rpta[data.instrumento.p[i].f[o].a] = data.instrumento.p[i].f[o].g.a;
                    preg_rpta_tipo[data.instrumento.p[i].f[o].a] = data.instrumento.p[i].f[o].d;
                }
            }
        }

        if (rptas > 0) {
            for (i = 0; i < rptas; i++) {
                if (Object.keys(data.instrumentoEmpleado.j[i].f).length > 0) {
                    //listaRespuestaRango
                    jQuery("#slctrango_" + data.instrumentoEmpleado.j[i].b + "").slider().slider('setValue', data.instrumentoEmpleado.j[i].f[0].b);
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                var multiples_ = Object.keys(data.instrumentoEmpleado.j[i].g).length;
                if (multiples_ > 0) {
                    //j.a id pregunta respuesta
                    if (preg_rpta[data.instrumentoEmpleado.j[i].b] === 'Vg') {
                        //Radio preg_rpta_tipo
                        radio_checked('optrd_' + data.instrumentoEmpleado.j[i].b, data.instrumentoEmpleado.j[i].g[0].c, true);
                    } else if (preg_rpta[data.instrumentoEmpleado.j[i].b] === 'qR') {
                        //Check
                        for (c = 0; c < multiples_; c++) {
                            radio_checked('optchk_' + data.instrumentoEmpleado.j[i].b + '', data.instrumentoEmpleado.j[i].g[c].c, true);
                        }
                    } else {
                        //Select
                        jQuery("#slct_" + data.instrumentoEmpleado.j[i].b + "").val(data.instrumentoEmpleado.j[i].g[c].c);
                    }
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                if (Object.keys(data.instrumentoEmpleado.j[i].h).length > 0) {
                    //listaRespuestaFecha
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                if (Object.keys(data.instrumentoEmpleado.j[i].i).length > 0) {
                    //listaRespuestaTexto
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }

            }
        }

        jQuery('#ul_preg li').click(function (e)
        {
            var li_active_ = $(this).attr("id");
            if (li_active_ === "li_resumen") {
                jQuery('#btn_finalizar').prop('disabled', false);
                jQuery('#btn_finalizar').show();
                jQuery('#btn_next_').hide();
            } else {
                jQuery('#btn_finalizar').prop('disabled', true);
                jQuery('#btn_finalizar').hide();
                jQuery('#btn_preview_').show();
                jQuery('#btn_next_').show();
            }

            jQuery('#emp_t_hd').val(id_emp);

        });

        jQuery('#btn_finalizar').click(function (e)
        {
            $('#modal_preguntas_tmp').modal('hide');
        });

        jQuery(".next-step").click(function (e) {

            var $active = $('.nav-tabs2 li.active');
            $active.next().removeClass('disabled');
            nextTab($active);

        });
        jQuery(".prev-step").click(function (e) {

            var $active = $('.nav-tabs2 li.active');
            prevTab($active);

        });

        jQuery('[data-toggle="tooltip"]').tooltip();

    });

}
function modal_preview_(obj) {
    jQuery('#content_out').html('');
    jQuery('#modal_preguntas_tmp').modal({
        keyboard: false,
        backdrop: 'static'
    });

    var modal_preguntas = jQuery('#modal_preguntas_tmp');
    modal_preguntas.on('shown.bs.modal', function (e) {
        jQuery(this).off('shown.bs.modal');
        var id_emp = obj.id;
        var name_emp = obj.name;
        var name_ie = obj.nameie;
        var cod_mod = obj.codmod;
        var ndoc = obj.ndoc;
        var des_dre = obj.d;
        var des_ugel = obj.u;
        var data = preguntas_listar({k: id_emp});
        tpl_listado_formato_content
        var source = jQuery("#tpl_listado_resumen_content").html();
        var template = Handlebars.compile(source);
        jQuery('#content_out').html(template(data));
        jQuery('#emp_t_hd').val(id_emp);
        jQuery('#emp_t_informante').html("<label class='col-xs-12 col-sm-6'>Informante:</label><label class='col-sm-6 lbl-lite padd_left'> " + name_emp + "</label>");
        jQuery('#emp_t_ndoc').html("<label class='col-xs-12 col-sm-6'>DNI:</label><label class='col-sm-6 lbl-lite'> " + ndoc + "</label>");
        jQuery('#emp_t_ie').html("<label class='col-xs-12 col-sm-3'>Institución educativa:</label><label class='col-sm-9 lbl-lite'> " + cod_mod + " - " + name_ie + "</label>");
        jQuery('#emp_t_dreugel').html("<label class='col-xs-12 col-sm-3'>DRE/UGEL:</label><label class='col-sm-9 lbl-lite'> " + des_dre + " / " + des_ugel + "</label>");
        var rptas = Object.keys(data.instrumentoEmpleado.j).length;


        if (rptas > 0) {
            for (i = 0; i < rptas; i++) {
                if (Object.keys(data.instrumentoEmpleado.j[i].f).length > 0) {
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                if (Object.keys(data.instrumentoEmpleado.j[i].g).length > 0) {
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                if (Object.keys(data.instrumentoEmpleado.j[i].h).length > 0) {
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }
                if (Object.keys(data.instrumentoEmpleado.j[i].i).length > 0) {
                    jQuery("#res_pre_" + data.instrumentoEmpleado.j[i].b + "").addClass('btn_click');
                }

            }
        }

        jQuery('[data-toggle="tooltip"]').tooltip();

    });

}
function modal_resumen(obj) {

    jQuery('#modal_resumen_tmp').modal({
        keyboard: false,
        backdrop: 'static'
    });

    var modal_preguntas = jQuery('#modal_resumen_tmp');
    modal_preguntas.on('shown.bs.modal', function (e) {
        jQuery(this).off('shown.bs.modal');

        var dat;
        BaseX.get({
            url: root + '/seleccion/index/get-resumen',
            data: obj,
            success: function (xhr, txt) {
                dat = xhr;
            }
        });

        init_resumen('dv_container_pastel', dat);

    });

}
function save_frm(obj) {

    if (obj !== undefined) {
        var rpta = JSON.parse(obj);
        BaseX.post({
            url: root + '/seleccion/index/save_frm',
            data: {a: Base64.encode(obj)},
            success: function (xhr, txtSting) {
                var _id = parseInt(xhr.id);
                updateEstado(xhr.rest);
                $.each(rpta.j, function (index, value) {
                    jQuery("#res_pre_" + value.b + "").addClass('btn_click');
                });

                jQuery('#emp_t_res').val(xhr.rest);
            }
        });
    } else {
        alert("Por favor, debe seleccionar una respuesta.");
    }
}
function save_frm_last() {
    var id = jQuery('#emp_t_hd').val();
    var cant = parseInt(jQuery('#emp_t_res').val());
    if (cant !== 0) {

        bootbox.dialog({
            message: _strings.app.msg_sistema_pendientes,
            title: "Mensaje del sistema",
            closeButton: false,
            buttons: {
                success: {
                    label: "Aceptar",
                    className: "boton",
                    callback: function () {
                        $('#modal_preguntas_tmp').modal('hide');
                        GridInstrumentosDetailData();
                    }
                }
            }
        });
    } else {
        bootbox.confirm({
            message: "¿Está seguro de finalizar el instrumento? Una vez finalizado no podrá realizar cambios.",
            buttons: {
                cancel: {
                    label: 'Aceptar',
                    className: 'boton'
                },
                confirm: {
                    label: 'Cancelar',
                    className: 'boton'
                }
            },
            callback: function (result) {
                if (!result) {
                    BaseX.post({
                        url: root + '/seleccion/index/save_frm_fn',
                        data: {a: Base64.encode(id)},
                        success: function (xhr, txtSting) {
                            bootbox.dialog({
                                message: xhr.msg,
                                title: "Mensaje del sistema",
                                closeButton: false,
                                buttons: {
                                    success: {
                                        label: "Aceptar",
                                        className: "boton",
                                        callback: function () {
                                            if (parseInt(xhr.id) > 0) {
                                                $('#modal_preguntas_tmp').modal('hide');
                                                GridInstrumentosDetailData();
                                            }
                                        }
                                    }
                                }
                            });

                        }
                    });
                }
            }
        });
    }

}
function radio_checked(name, value, checked) {
    var selector = 'input[name=' + name + '][value="' + value + '"]';
    jQuery(selector).prop('checked', checked);
    if (checked === false) {
        jQuery(selector).parent('.btn').remove('active');
    } else {
        jQuery(selector).parent('.btn').addClass('active');
    }

}
function link_group(elem) {
    $("#li_paso_" + elem + "").find('a[data-toggle="tab"]').click();
}
function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
function refresh_login() {
    if (jQuery('#ref_login').val() !== undefined) {
        document.getElementById('ref_login').src = root + "/usuario/captcha/logincaptcha?rnd=" + Math.random();
    }
}

(function () {
    function checkCondition(v1, operator, v2) {
        switch (operator) {
            case '==':
                return (v1 == v2);
            case '===':
                return (v1 === v2);
            case '!==':
                return (v1 !== v2);
            case '<':
                return (v1 < v2);
            case '<=':
                return (v1 <= v2);
            case '>':
                return (v1 > v2);
            case '>=':
                return (v1 >= v2);
            case '&&':
                return (v1 && v2);
            case '||':
                return (v1 || v2);
            default:
                return false;
        }
    }
    Handlebars.registerHelper('for', function (from, to, incr, block) {
        var accum = '';
        for (var i = from; i < to; i += incr)
            accum += block.fn(i);
        return accum;
    });

    Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
        return checkCondition(v1, operator, v2)
                ? options.fn(this)
                : options.inverse(this);
    });
    Handlebars.registerHelper('ifNotNull', function (v1, options) {
        if (v1) {
            return options.fn(this);
        } else {
            return options.inverse(this);
        }
    });

    Handlebars.registerHelper('ifInfo', function (v1, options) {
        if (v1) {
            return options.fn(this);
        } else {
            return options.inverse(this);
        }
    });


    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function CapitalizeAll(cadena) {
        var parts = cadena.split(" ");
        var parts_fix = [];
        for (i = 0; i < parts.length; i++) {
            parts_fix.push(capitalizeFirstLetter(parts[i]));
        }
        return parts_fix.join(' ');
    }

    Handlebars.registerHelper('CapitalizeAll', function (cadena) {
        return CapitalizeAll(cadena);
    });

    Handlebars.registerHelper('toUpperCase', function (cadena) {
        return cadena.toUpperCase();
    });
}());