function menu_config_instrumentos() {
    load_config_instrumentos();
    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#menuConfigInstrumentos').addClass('clicked');
}

function fn_add_new_group(obj) {
    $("#" + obj.a + "").append("<li class='ui-state-default' data-nom='" + obj.b + "' data-desc='" + obj.c + "' data-ind='0'>" + obj.b + "<button type='button' class='btn btn-login btn-sm'><span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span></button></li>");
}

function delete_group() {

}

function load_config_instrumentos(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    if (valida_sesion() === false) {
        return;
    }

    BaseX.load_html(root + '/seleccion/configuracion/config_instrumentos', {
        data: ops,
        success: function (xhr) {
            jQuery('#workArea').html(xhr);


            ListarGridInstrumentos('#listar_grid_instrumentos');
            GridInstrumentosDataConfig();


            //Btn limpiar formulario de inscripción
            jQuery('#btn_new_ins').off('click');
            jQuery('#btn_new_ins').click(function (e)
            {
                setDatos({'a': 0, 'b': 0});
                view_nuevo_instrumentos();
            });
            //Btn limpiar formulario de inscripción
            jQuery('#btn_limpiar_ins').off('click');
            jQuery('#btn_limpiar_ins').click(function (e)
            {
                jQuery('#frm_instrumento')[0].reset();
                var resultado = instrumentos_listar_all({});
                BaseX.Grid('#listar_grid_instrumentos').clear();
                BaseX.Grid('#listar_grid_instrumentos').setData(resultado);
            });
            //Btn buscar formulario de inscripción
            jQuery('#btn_search_ins').off('click');
            jQuery('#btn_search_ins').click(function (e)
            {
                var obj = {
                    'le': parseInt(jQuery('#lista_estrategia').val()),
                    'li': parseInt(jQuery('#lista_intervencion').val()),
                    'la': parseInt(jQuery('#lista_ambito').val()),
                    'lm': parseInt(jQuery('#lista_muestra').val())
                };

                var resultado = instrumentos_listar_all(obj);
                BaseX.Grid('#listar_grid_instrumentos').clear();
                BaseX.Grid('#listar_grid_instrumentos').setData(resultado);

            });
















            rp.b = [];
            rp.c = [];
            rp_groups.b = [];
            //Guardar nueva sección
//            $('#save_new_group').on('click', function () {
//                var txt_section = jQuery('#frm_add_section').val();
//                var txt_section_helper = jQuery('#frm_add_section_helper').val();
//                var o = rp_groups.b.length;
//                var obj = {a: (o + 1), b: txt_section, c: [], d: txt_section_helper};
//                rp_groups.b.push(obj);
//                rp.c.push(obj);
//                var template = Handlebars.compile(tpl_list_groups_content);
//                jQuery('#content_list_group').html(template(rp_groups));
//                refresh_tpl();
//                $('#frm_gen')[0].reset();
//            });
            //Guardar nueva pregunta a grupo
            $('#save_frm_group').on('click', function () {
                var a = jQuery('#question').val();
                var b = jQuery('#numppl').val();
                var c = jQuery('#list_group_').val();
                var i_group = (parseInt(c) - 1);

                if (a !== '' && b !== 0 && c !== 0) {
                    var o = {};
                    var n = (rp.b.length + 1);
                    var i_question = rp.c[i_group].c.length;
                    switch (b) {
                        case "a":
                            o = {group: i_group, id: i_question, order: n, question: a, type: a_.a.type, options: a_.a.option, values: []};
                            rp.c[i_group].c.push(o);
                            break;
                        case "b":
                            o = {group: i_group, id: i_question, order: n, question: a, type: a_.b.type, options: a_.b.option, values: [{a: 0, b: "default"}]};
                            rp.c[i_group].c.push(o);
                            break;
                        case "c":
                            o = {group: i_group, id: i_question, order: n, question: a, type: a_.c.type, options: a_.c.option, values: [{a: 0, b: "default"}]};
                            rp.c[i_group].c.push(o);
                            break;
                        case "d":
                            o = {group: i_group, id: i_question, order: n, question: a, type: a_.d.type, options: a_.d.option, values: [{a: 0, b: "default"}]};
                            rp.c[i_group].c.push(o);
                            break;
                        case "e":
                            o = {group: i_group, id: i_question, order: n, question: a, type: a_.e.type, options: a_.e.option, values: []};
                            rp.c[i_group].c.push(o);
                            break;
                        case "f":
                            o = {group: i_group, id: i_question, order: n, question: a, type: a_.f.type, options: a_.f.option, values: []};
                            rp.c[i_group].c.push(o);
                            break;
                    }
                }
                $('#frm_gen')[0].reset();
                $.each(rp.c, function (index, value) {
                    rp.c[index].c = order_array_principal_wg(rp.c[index].c, (index - 1), index);
                });
                refresh_tpl();
            });

            //Guardar nueva pregunta a grupo
            $('#save_all_frm_').on('click', function () {
                console.log(JSON.stringify(rp));
            });
        }
    });
}

function instrumentos_listar_all(obj) {
    if (obj === undefined) {
        obj = {};
    }
    var data;
    BaseX.get({
        url: root + '/seleccion/configuracion/get-instrumentos',
        data: obj,
        success: function (xhr, txt) {
            data = xhr;
        }
    });

    return data;
}

function view_nuevo_instrumentos(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    if (valida_sesion() === false) {
        return;
    }

    BaseX.load_html(root + '/seleccion/configuracion/nuevo_instrumento', {
        data: ops,
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
            jQuery('#fec_inicio').inputmask('99/99/9999');
            jQuery('#fec_fin').inputmask('99/99/9999');
            //Guardar nuevo instrumento
            jQuery('#btn_guardar_ins').off('click');
            jQuery('#btn_guardar_ins').on('click', function () {
                var obj = {
                    id_estrategia: parseInt(jQuery('#lista_estrategia').val()),
                    id_intervencion: parseInt(jQuery('#lista_intervencion').val()),
                    nombre: jQuery('#nombre_instrumento').val(),
                    descripcion: jQuery('#desc_instrumento').val(),
                    id_ambito: parseInt(jQuery('#lista_ambito').val()),
                    id_tipo_instrumento: parseInt(jQuery('#lista_tipoinstrumento').val()),
                    id_tipo_informante: parseInt(jQuery('#lista_tipoinformante').val()),
                    id_muestra: parseInt(jQuery('#lista_muestra').val()),
                    fec_inicio: jQuery('#fec_inicio').val(),
                    fec_fin: jQuery('#fec_fin').val()
                };

                BaseX.post({
                    url: root + '/seleccion/configuracion/instrumento-guardar',
                    data: obj,
                    success: function (xhr, txtSting) {
                        var _id = parseInt(xhr.id);
                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    jQuery('#id_instrumento_active').val(_id);
                                    setDatos({'a': _id, 'b': 0});
                                }
                            }
                        });
                    }
                });
            });

            //Btn buscar formulario de inscripción
//            jQuery('#add_new_group').off('click');
//            jQuery('#add_new_group').on('click', function () {
//                var obj = {
//                    a: 'sortable',
//                    b: jQuery('#frm_add_section').val(),
//                    c: jQuery('#frm_add_section_helper').val()
//                };
//                fn_add_new_group(obj);
//            });

            //Guardar grupo de preguntas ordenados
            jQuery("#btn_guardar_grupo").off("click");
            jQuery("#btn_guardar_grupo").on("click", function () {
                var a = [];
                var obj = {};
                jQuery("#sortable li").each(function (index) {
                    a[index] = telerik.encode(JSON.stringify({a: jQuery(this).data("ind"), b: jQuery(this).data("nom"), c: jQuery(this).data("desc")}));
                });
                obj = {
                  sec : a  
                };
                BaseX.post({
                    url: root + '/seleccion/configuracion/secciones-guardar',
                    data: obj,
                    success: function (xhr, txtSting) {
                        
//                        var _id = parseInt(xhr.id);
                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    setDataInstrumento();
                                }
                            }
                        });
                    }
                });
            });

        }
    }
    );
}

function setDatos(obj) {
    BaseX.post({
        url: root + '/seleccion/configuracion/set-instrumento-config',
        data: obj,
        success: function (xhr, txtSting) {
        }
    });
}

function fn_edit_instrumento(obj) {
    setDatos({'a': obj.id, 'b': 1});
    view_nuevo_instrumentos();
    setDataInstrumento();
}

function setDataInstrumento() {
    BaseX.get({
        url: root + '/seleccion/configuracion/get-instrumentos-detail',
        data: {},
        success: function (xhr, txt) {
            //Panel instrumento
            var data = xhr.a[0];
            var data_sec = xhr.b;
            jQuery('#id_instrumento_active').val(data.ID_INSTRUMENTO);
            jQuery('#lista_estrategia').val(data.ID_TIPO_ESTRATEGIA);
            jQuery('#lista_intervencion').val(data.ID_TIPO_INTERVENCION);
            jQuery('#nombre_instrumento').val(data.NOMBRE);
            if (!data.DESCRIPCION_INSTRUMENTO) {
                descripcion = '';
            } else {
                descripcion = data.DESCRIPCION_INSTRUMENTO;
            }

            jQuery('#desc_instrumento').val(descripcion);
            jQuery('#lista_ambito').val(data.ID_TIPO_AMBITO);
            jQuery('#lista_tipoinstrumento').val(data.ID_TIPO_INSTRUMENTO);
            jQuery('#lista_tipoinformante').val(data.ID_TIPO_INFORMANTE);
            jQuery('#lista_muestra').val(data.ID_TIPO_MUESTRA);
            jQuery('#fec_inicio').val(data.FECHA_INICIO);
            jQuery('#fec_fin').val(data.FECHA_FIN);

            //Panel de secciones
            var html_grupo = "";
            for (i = 0; i < data_sec.length; i++) {
                html_grupo += "<li id='" + data_sec[i].id_grupo_pregunta + "' class='ui-state-default' data-nom='" + data_sec[i].nombre + "' data-desc='" + data_sec[i].descripcion + "' data-ind='" + data_sec[i].id_grupo_pregunta + "'>" + data_sec[i].nombre + "<button type='button' class='btn btn-login btn-sm'><span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span></button> <button type='button' class='btn btn-login btn-edit'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></button></li>";
            }
            jQuery('#sortable').html(html_grupo);

            //Habilitar sortable
            jQuery("#sortable").sortable({placeholder: "ui-state-highlight"});
            jQuery("#sortable").disableSelection();

            metod_del_edi();
//            //Btn eliminar grupo de pregunta
//            jQuery("ol li .btn-sm").off("click");
//            jQuery("ol li .btn-sm").on("click", function () {
//                jQuery(this).parent().remove();
//            });
//            
//            //Btn buscar formulario de inscripción
//            jQuery('ol li .btn-edit').off('click');
//            jQuery('ol li .btn-edit').on('click', function () {
//                var obj = {
//                    a: 'sortable',
//                    b: jQuery(this).parent().data('nom'),
//                    c: jQuery(this).parent().data('desc'),
//                    d: jQuery(this).parent().attr('id'),
//                };
//                edit_section_(obj);
//            });
        }
    });
}

function metod_del_edi(){
     //Btn eliminar grupo de pregunta
            jQuery("ol li .btn-sm").off("click");
            jQuery("ol li .btn-sm").on("click", function () {
                jQuery(this).parent().remove();
            });
            
            //Btn buscar formulario de inscripción
            jQuery('ol li .btn-edit').off('click');
            jQuery('ol li .btn-edit').on('click', function () {
                var obj = {
                    a: 'sortable',
                    b: jQuery(this).parent().data('nom'),
                    c: jQuery(this).parent().data('desc'),
                    d: jQuery(this).parent().attr('id'),
                };
                edit_section_(obj);
            });
}
function edit_section_(o){

         jQuery('#modal_edit_tmp').modal({
            keyboard: false,
            backdrop: 'static'
        });
        //Modal de edición de preguntas
        var modal_preguntas = jQuery('#modal_edit_tmp');
        modal_preguntas.on('shown.bs.modal', function (e) {
            jQuery(this).off('shown.bs.modal');
            
            jQuery('#frm_add_section').val(o.b);
            jQuery('#frm_add_section_helper').val(o.c);
            jQuery('#frm_add_section_key_hdd').val(o.d);

            var edit_question_jq = jQuery('#save_edit_section');
            edit_question_jq.off('click');
            edit_question_jq.on('click', function () {
                
                var obj = {
                    a: 'sortable',
                    b: jQuery('#frm_add_section').val(),
                    c: jQuery('#frm_add_section_helper').val(),
                    d: jQuery('#frm_add_section_key_hdd').val()
                };
                var t = '<button type="button" class="btn btn-login btn-sm"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></button> <button type="button" class="btn btn-login btn-edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>';
                jQuery('#'+obj.d+'').html(obj.b + t);
                jQuery('#'+obj.d+'').data('nom',obj.b);
                jQuery('#'+obj.d+'').data('desc',obj.c);
                //fn_add_new_group(obj);
                
                modal_preguntas.modal('hide');
                metod_del_edi();
            });

        });
    }