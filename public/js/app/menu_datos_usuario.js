function menu_datos_usuario() {
    load_menu_datos_usuario();
    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#mnuPerfilUsuario').addClass('clicked');
}

function limpia_carga(opc_campo){
    
    //Contraseña
    if(opc_campo === 1 || opc_campo === 0){
        var row_cambio_contrasena = jQuery('#row_cambio_contrasena');
        var div_row_cambio_contrasena = jQuery('#div_row_cambio_contrasena');
        jQuery('#perfil_pass_clave_old').val('');
        jQuery('#perfil_pass_nueva_clave').val('');
        jQuery('#perfil_pass_nueva_clave_confir').val('');
        row_cambio_contrasena.show();
        div_row_cambio_contrasena.hide();
    }
    
    //Correo
    if(opc_campo === 2 || opc_campo === 0){
        var row_cambio_correo = jQuery('#row_cambio_correo');
        var div_row_cambio_correo = jQuery('#div_row_cambio_correo');
        jQuery('#perfil_correo_nuevo').val('');
        jQuery('#perfil_correo_nuevo_confir').val('');
        row_cambio_correo.show();
        div_row_cambio_correo.hide();
    }
    
    //Celular
    if(opc_campo === 3 || opc_campo === 0){
        var row_cambio_celular = jQuery('#row_cambio_celular');
        var div_row_cambio_celular = jQuery('#div_row_cambio_celular');
        jQuery('#perfil_celular_nuevo').val('');
        row_cambio_celular.show();
        div_row_cambio_celular.hide();
    }
    
    //Celular Confirmación
    if(opc_campo === 4 || opc_campo === 0){
        var row_cambio_celular = jQuery('#row_cambio_celular');
        var div_row_cambio_celular = jQuery('#div_row_cambio_celular');
        jQuery('#perfil_celular_nuevo').val('');
        jQuery('#perfil_codigo_valcel').val('');
        row_cambio_celular.show();
        div_row_cambio_celular.hide();
    }
    
    //Telefono
    if(opc_campo === 5 || opc_campo === 0){
        var row_cambio_telefono = jQuery('#row_cambio_telefono');
        var div_row_cambio_telefono = jQuery('#div_row_cambio_telefono');
        jQuery('#perfil_lista_codigo_ciudad option').eq(0).prop('selected', true);
        jQuery('#perfil_telefono_nuevo').val('');
        row_cambio_telefono.show();
        div_row_cambio_telefono.hide();
    }
    
    //Direccion
    if(opc_campo === 6 || opc_campo === 0){
        var row_cambio_direccion = jQuery('#row_cambio_direccion');
        var div_row_cambio_direccion = jQuery('#div_row_cambio_direccion');
        var id_departamento = jQuery('#id_departamento').val();
        if(isNaN(parseInt(id_departamento))){
            id_departamento = 0;
        }
        if( parseInt(id_departamento) === 0 ){
            jQuery('#perfil_lista_departamento option').eq(0).prop('selected', true);
            jQuery('#perfil_lista_provincia option').eq(0).prop('selected', true);
            jQuery('#perfil_lista_distrito option').eq(0).prop('selected', true);
            jQuery('#perfil_direccion_nuevo').val('');
            jQuery('#perfil_referencia_nuevo').val('');
        }
        row_cambio_direccion.show();
        div_row_cambio_direccion.hide();
    }
    
    //Celular Adicional
    if(opc_campo === 7 || opc_campo === 0){
        var row_cambio_celular_alt = jQuery('#row_cambio_celular_alternativo');
        var div_row_cambio_celular_alt = jQuery('#div_row_cambio_celular_alternativo');
        jQuery('#perfil_celular_alternativo_nuevo').val('');
        row_cambio_celular_alt.show();
        div_row_cambio_celular_alt.hide();
    }
    
    //Correo Adicional
    if(opc_campo === 8 || opc_campo === 0){
        var row_cambio_correo_alt = jQuery('#row_cambio_correo_alternativo');
        var div_row_cambio_correo_alt = jQuery('#div_row_cambio_correo_alternativo');
        jQuery('#perfil_correo_alternativo_nuevo').val('');
        row_cambio_correo_alt.show();
        div_row_cambio_correo_alt.hide();
    }
    
    //Contacto Referencia
    if(opc_campo === 9 || opc_campo === 0){
        var row_cambio_contacto_ref = jQuery('#row_cambio_contacto_referencia');
        var div_row_cambio_contacto_ref = jQuery('#div_row_cambio_contacto_referencia');
        var id_contacto_nombre = jQuery('#id_contacto_nombre').val();
        if(isNaN(parseInt(id_contacto_nombre))){
            id_contacto_nombre = 0;
        }
        if( parseInt(id_contacto_nombre) === 0 ){
            jQuery('#perfil_nombre_referencia_nuevo').val('');
            jQuery('#perfil_celular_referencia_nuevo').val('');
            jQuery('#perfil_lista_codigo_ciudad_referencia option').eq(0).prop('selected', true);
            jQuery('#perfil_telefono_referencia_nuevo').val('');
        }
        row_cambio_contacto_ref.show();
        div_row_cambio_contacto_ref.hide();
    }
    
}

function load_menu_datos_usuario(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    if (valida_sesion(0) === false) {
        return;
    }

    BaseX.load_html(root + '/usuario/perfil/index', {
        data: ops,
        success: function (xhr) {

            jQuery('#workArea').html(xhr);

            //****************************************//
            //== Cambiar Contraseña -------- INICIO ==//
            //****************************************//

            jQuery('#perfil_pass_clave_old').filter_input({regex:_strings.app.validate.diccionario_clave});
            jQuery('#perfil_pass_clave_old').on('keypress', function (evt) {
                if (evt.altKey === true) {
                    evt.stopImmediatePropagation();
                    return false;
                }
            });
            jQuery('#perfil_pass_nueva_clave').filter_input({regex:_strings.app.validate.diccionario_clave});
            jQuery('#perfil_pass_nueva_clave').on('keypress', function (evt) {
                if (evt.altKey === true) {
                    evt.stopImmediatePropagation();
                    return false;
                }
            });
            jQuery('#perfil_pass_nueva_clave_confir').filter_input({regex:_strings.app.validate.diccionario_clave});
            jQuery('#perfil_pass_nueva_clave_confir').on('keypress', function (evt) {
                if (evt.altKey === true) {
                    evt.stopImmediatePropagation();
                    return false;
                }
            });
            var row_cambio_contrasena = jQuery('#row_cambio_contrasena');
            var perfil_btn_cambiar_contrasena = jQuery('#perfil_btn_cambiar_contrasena');
            var div_row_cambio_contrasena = jQuery('#div_row_cambio_contrasena');
            var perfil_row_contrasena_cancelar = jQuery('#perfil_row_pass_cancelar');
            var perfil_row_contrasena_guardar = jQuery('#perfil_row_pass_guardar');
            
            div_row_cambio_contrasena.hide();
            perfil_btn_cambiar_contrasena.off('click');
            perfil_btn_cambiar_contrasena.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_contrasena.hide();
                div_row_cambio_contrasena.show();
            });
            
            perfil_row_contrasena_guardar.off('click');
            perfil_row_contrasena_guardar.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton: false,
                    message: '¿Desea actualizar su contraseña?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            perfil_row_contrasena_guardar.prop('disabled', true);

                            var pass_old = jQuery('#perfil_pass_clave_old');
                            var pass_pri = jQuery('#perfil_pass_nueva_clave');
                            var pass_ver = jQuery('#perfil_pass_nueva_clave_confir');
                            
                            var obj = {
                                password_old: pass_old.val(),
                                password_pri: pass_pri.val(),
                                password_ver: pass_ver.val(),
                            };
                            
                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-clave',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_contrasena_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        document.location = _config.url_logout;
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_contrasena_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            perfil_row_contrasena_cancelar.off('click');
            perfil_row_contrasena_cancelar.on('click', function (evt) {
//                jQuery('#perfil_pass_clave_old').val('');
//                jQuery('#perfil_pass_nueva_clave').val('');
//                jQuery('#perfil_pass_nueva_clave_confir').val('');
//                row_cambio_contrasena.show();
//                div_row_cambio_contrasena.hide();
                limpia_carga(1);
            });
                        
            //****************************************//
            //== Cambiar Contraseña ----------- FIN ==//
            //****************************************//

            //****************************************//
            //== Cambiar Correo Usuario ---- INICIO ==//
            //****************************************//

            jQuery('#perfil_correo_nuevo').filter_input({regex:_strings.app.validate.diccionario_correo});
            jQuery('#perfil_correo_nuevo').on('keypress', function (evt) {
                var code = evt.keyCode || evt.which; 
                //alert(code);
                if (evt.altKey === true) {
                    if(code !== 64) { 
                        evt.stopImmediatePropagation();
                        return false;
                    }                    
                }
            });
            jQuery('#perfil_correo_nuevo_confir').filter_input({regex:_strings.app.validate.diccionario_correo});
            jQuery('#perfil_correo_nuevo_confir').on('keypress', function (evt) {
                var code = evt.keyCode || evt.which; 
                if (evt.altKey === true) {
                    if(code !== 64) { 
                        evt.stopImmediatePropagation();
                        return false;
                    }                    
                }
            });
            var row_cambio_correo = jQuery('#row_cambio_correo');
            var perfil_btn_cambiar_correo = jQuery('#perfil_btn_cambiar_correo');
            var div_row_cambio_correo = jQuery('#div_row_cambio_correo');
            var perfil_row_correo_cancelar = jQuery('#perfil_row_correo_cancelar');
            var perfil_row_correo_guardar = jQuery('#perfil_row_correo_guardar');

            div_row_cambio_correo.hide();
            perfil_btn_cambiar_correo.off('click');
            perfil_btn_cambiar_correo.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_correo.hide();
                div_row_cambio_correo.show();
            });

            perfil_row_correo_guardar.off('click');
            perfil_row_correo_guardar.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton: false,
                    message: '¿Desea actualizar su correo electr&oacute;nico?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            perfil_row_correo_guardar.prop('disabled', true);

                            var correo_old = jQuery('#perfil_correo_old');
                            var correo_pri = jQuery('#perfil_correo_nuevo');
                            var correo_ver = jQuery('#perfil_correo_nuevo_confir');

                            var obj = {
                                correo_old: correo_old.val(),
                                correo_pri: correo_pri.val(),
                                correo_ver: correo_ver.val()
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-correo',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_correo_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();                                                        
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_correo_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            perfil_row_correo_cancelar.off('click');
            perfil_row_correo_cancelar.on('click', function (evt) {
//                jQuery('#perfil_correo_nuevo').val('');
//                jQuery('#perfil_correo_nuevo_confir').val('');
//                row_cambio_correo.show();
//                div_row_cambio_correo.hide();
                limpia_carga(2);
            });
            
            //****************************************//
            
            var perfil_correo_refrescar = jQuery('#perfil_correo_refrescar');
            perfil_correo_refrescar.off('click');
            perfil_correo_refrescar.on('click', function (evt) {
                load_menu_datos_usuario();                                                        
            });
            
            //****************************************//
            //== Cambiar Correo Electrónico --- FIN ==//
            //****************************************//

            //****************************************//
            //== Cambiar Celular Usuario --- INICIO ==//
            //****************************************//

            jQuery('#perfil_celular_nuevo').filter_input({regex:_strings.app.validate.diccionario_numeros});
            var row_cambio_celular = jQuery('#row_cambio_celular');
            var perfil_btn_cambiar_celular = jQuery('#perfil_btn_cambiar_celular');            
            var div_row_cambio_celular = jQuery('#div_row_cambio_celular');
            var div_cambio_celular = jQuery('#div_cambio_celular');
            var div_cambio_celular_confirma = jQuery('#div_cambio_celular_confirma');
            var perfil_row_celular_cancelar = jQuery('#perfil_row_celular_cancelar');
            var perfil_row_celular_guardar = jQuery('#perfil_row_celular_guardar');
            var perfil_row_celular_ccancelar = jQuery('#perfil_row_celular_ccancelar');
                        
            div_row_cambio_celular.hide();
            perfil_btn_cambiar_celular.off('click');
            perfil_btn_cambiar_celular.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_celular.hide();
                div_row_cambio_celular.show();
                div_cambio_celular.show();
                div_cambio_celular_confirma.hide();
            });

            perfil_row_celular_guardar.off('click');
            perfil_row_celular_guardar.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton:false,
                    message: '¿Desea actualizar su n&uacute;mero de celular?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            perfil_row_celular_guardar.prop('disabled', true);

                            var celular_old = jQuery('#perfil_celular_old');
                            var celular_pri = jQuery('#perfil_celular_nuevo');

                            var obj = {
                                celular_old: celular_old.val(),
                                celular_pri: celular_pri.val()
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-celular',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_celular_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        div_cambio_celular.hide();
                                                        div_cambio_celular_confirma.show();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_celular_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            perfil_row_celular_cancelar.off('click');
            perfil_row_celular_cancelar.on('click', function (evt) {
//                jQuery('#perfil_celular_nuevo').val('');
//                row_cambio_celular.show();
//                div_row_cambio_celular.hide();
                limpia_carga(3);
            });
            
            //****************************************//
            
            jQuery('#perfil_codigo_valcel').filter_input({regex:_strings.app.validate.diccionario_numeros});
            var perfil_row_celular_confirmar = jQuery('#perfil_row_celular_confirmar');
            
            perfil_row_celular_confirmar.off('click');
            perfil_row_celular_confirmar.on('click', function (evt) {
                perfil_row_celular_confirmar.prop('disabled', true);

                var codigo_valcel = jQuery('#perfil_codigo_valcel');
                var obj = {
                    codigo_valcel: codigo_valcel.val()
                };
                BaseX.post({
                    url: root + '/usuario/perfil/validar-codigo-celular',
                    data: obj,
                    success: function (xhr, txtSting) {
                        var _id = parseInt(xhr.id);
                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    perfil_row_celular_confirmar.prop('disabled', false);
                                        if(_id > 0 || _id === parseInt(-200)){	
                                            load_menu_datos_usuario();                                            
                                        }
                                }
                            }
                        });
                        perfil_row_celular_confirmar.prop('disabled', false);
                    }
                });
            });
            
            perfil_row_celular_ccancelar.off('click');
            perfil_row_celular_ccancelar.on('click', function (evt) {
//                jQuery('#perfil_celular_nuevo').val('');
//                jQuery('#perfil_codigo_valcel').val('');
//                row_cambio_celular.show();
//                div_row_cambio_celular.hide();
                limpia_carga(4);
            });

            //****************************************//
            //== Cambiar Celular Usuario ------ FIN ==//
            //****************************************//

            //****************************************//
            //== Cambiar Telefono Usuario -- INICIO ==//
            //****************************************//

            jQuery('#perfil_telefono_nuevo').filter_input({regex:_strings.app.validate.diccionario_numeros});
            var row_cambio_telefono = jQuery('#row_cambio_telefono');
            var perfil_btn_cambiar_telefono = jQuery('#perfil_btn_cambiar_telefono');
            var perfil_btn_eliminar_telefono = jQuery('#perfil_btn_eliminar_telefono');
            var div_row_cambio_telefono = jQuery('#div_row_cambio_telefono');
            var perfil_row_telefono_cancelar = jQuery('#perfil_row_telefono_cancelar');
            var perfil_row_telefono_guardar = jQuery('#perfil_row_telefono_guardar');

            div_row_cambio_telefono.hide();
            perfil_btn_cambiar_telefono.off('click');
            perfil_btn_cambiar_telefono.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_telefono.hide();
                div_row_cambio_telefono.show();
            });
            
            perfil_btn_eliminar_telefono.off('click');
            perfil_btn_eliminar_telefono.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    message: '¿Desea eliminar su n&uacute;mero de tel&eacute;fono fijo?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {                        
                        if (result) {
                            perfil_btn_eliminar_telefono.prop('disabled', true);        
                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-eliminar-telefono',
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_btn_eliminar_telefono.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_btn_eliminar_telefono.prop('disabled', false);
                                }
                            });
                        }
                    }
                });    
            });

            perfil_row_telefono_guardar.off('click');
            perfil_row_telefono_guardar.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton:false,
                    message: '¿Desea actualizar su n&uacute;mero de tel&eacute;fono fijo?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {                        
                        if (result) {
                            perfil_row_telefono_guardar.prop('disabled', true);

                            var codigo_telefono = jQuery('#perfil_lista_codigo_ciudad');
                            var telefono = jQuery('#perfil_telefono_nuevo');         

                            var obj = {
                                codigo_telefono: codigo_telefono.val(),
                                telefono: telefono.val()
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-telefono',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_telefono_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_telefono_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });    
            });
          
            perfil_row_telefono_cancelar.off('click');
            perfil_row_telefono_cancelar.on('click', function (evt) {
//                jQuery('#perfil_lista_codigo_ciudad option').eq(0).prop('selected', true);
//                jQuery('#perfil_telefono_nuevo').val('');
//                row_cambio_telefono.show();
//                div_row_cambio_telefono.hide();
                limpia_carga(5);
            });

            //****************************************//
            //== Cambiar Telefono Usuario ----- FIN ==//
            //****************************************//
            
            //**************************************************//
            //== Cambiar Dirección Usuario ----------- INICIO ==//
            //**************************************************//

            //== PROVINCIA ==//    
            $("#perfil_lista_departamento").change(function () {

                $.getJSON(root + '/usuario/perfil/cargaprovincia?id_departamento=' + $(this).val(), {format: "json"}, function (md) {
                    $("#perfil_lista_provincia").html("");
                    var cmb2 = [];
                    cmb2.push('<option value="0" selected >--- Seleccione Provincia ---</option>');
                    $.each(md, function (key, val) {
                        cmb2.push('<option value="' + val["id"] + '">' + val["descripcion"] + '</option>');
                    });
                    $("#perfil_lista_provincia").html(cmb2.join(''));
                    //
                    var cmb3 = [];
                    cmb3.push('<option value="0" >--- Seleccione Distrito ---</option>');
                    $("#perfil_lista_distrito").html(cmb3.join(''));
                });
            });
            
            //== DISTRITO ==//
            var departamento = jQuery('#perfil_lista_departamento');
            $("#perfil_lista_provincia").change(function () {
                $.getJSON(root + '/usuario/perfil/cargadistrito?id_departamento=' + departamento.val() + '&id_provincia=' + $(this).val(), {format: "json"}, function (md) {
                    $("#perfil_lista_distrito").html("");
                    var cmb2 = [];
                    cmb2.push('<option value="0" selected >--- Seleccione Distrito ---</option>');
                    $.each(md, function (key, val) {
                        cmb2.push('<option value="' + val["id"] + '">' + val["descripcion"] + '</option>');
                    });
                    $("#perfil_lista_distrito").html(cmb2.join(''));
                });
            });

            //**************************************************//

            jQuery('#perfil_direccion_nuevo').filter_input({regex:_strings.app.validate.diccionario_direccion});
            jQuery('#perfil_direccion_nuevo').on('keypress', function (evt) {
                if (evt.altKey === true) {
                    evt.stopImmediatePropagation();
                    return false;
                }
            });
            jQuery('#perfil_referencia_nuevo').filter_input({regex:_strings.app.validate.diccionario_direccion});
            jQuery('#perfil_referencia_nuevo').on('keypress', function (evt) {
                if (evt.altKey === true) {
                    evt.stopImmediatePropagation();
                    return false;
                }
            });
            
             jQuery("#perfil_direccion_nuevo").on("change paste keyup", function () {
                if ($(this).val().charAt(0) === ' ') {
                    $(this).val("");
                }
            });
             jQuery("#perfil_referencia_nuevo").on("change paste keyup", function () {
                if ($(this).val().charAt(0) === ' ') {
                    $(this).val("");
                }
            });
            
            var row_cambio_direccion = jQuery('#row_cambio_direccion');
            var perfil_btn_cambiar_direccion = jQuery('#perfil_btn_cambiar_direccion');
            var perfil_btn_eliminar_direccion = jQuery('#perfil_btn_eliminar_direccion');
            var div_row_cambio_direccion = jQuery('#div_row_cambio_direccion');
            var perfil_row_direccion_cancelar = jQuery('#perfil_row_direccion_cancelar');
            var perfil_row_direccion_guardar = jQuery('#perfil_row_direccion_guardar');

            div_row_cambio_direccion.hide();
            perfil_btn_cambiar_direccion.off('click');
            perfil_btn_cambiar_direccion.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_direccion.hide();
                div_row_cambio_direccion.show();
                
                var id_departamento = jQuery('#id_departamento').val();
                var id_provincia = jQuery('#id_provincia').val();
                var id_distrito = jQuery('#id_distrito').val();
                
                jQuery('#perfil_lista_departamento option').eq(parseInt(id_departamento)).prop('selected', true);
                $.getJSON(root + '/usuario/perfil/cargaprovincia?id_departamento=' + id_departamento, {format: "json"}, function (md) {
                    $("#perfil_lista_provincia").html("");
                    var cmb2 = [];
                    cmb2.push('<option value="0" >--- Seleccione Provincia ---</option>');
                    $.each(md, function (key, val) {
                        cmb2.push('<option value="' + val["id"] + '">' + val["descripcion"] + '</option>');
                    });
                    $("#perfil_lista_provincia").html(cmb2.join(''));
                });
                jQuery('#perfil_lista_provincia option').eq(parseInt(id_provincia)).prop('selected', true);                
                $.getJSON(root + '/usuario/perfil/cargadistrito?id_departamento=' + id_departamento + '&id_provincia=' + id_provincia, {format: "json"}, function (md) {
                    $("#perfil_lista_distrito").html("");
                    var cmb2 = [];
                    cmb2.push('<option value="0" >--- Seleccione Distrito ---</option>');
                    $.each(md, function (key, val) {
                        cmb2.push('<option value="' + val["id"] + '">' + val["descripcion"] + '</option>');
                    });
                    $("#perfil_lista_distrito").html(cmb2.join(''));
                });
                jQuery('#perfil_lista_distrito option').eq(parseInt(id_distrito)).prop('selected', true);
            });
            
            perfil_btn_eliminar_direccion.off('click');
            perfil_btn_eliminar_direccion.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton: false,
                    message: '¿Desea eliminar su direcci&oacute;n?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {                        
                        if (result) {
                            perfil_btn_eliminar_direccion.prop('disabled', true);        
                            BaseX.post({
                                closeButton: false,
                                url: root + '/usuario/perfil/perfil-eliminar-direccion',
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_btn_eliminar_direccion.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_btn_eliminar_direccion.prop('disabled', false);
                                }
                            });
                        }
                    }
                });    
            });

            perfil_row_direccion_guardar.off('click');
            perfil_row_direccion_guardar.on('click', function (evt) {                
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton:false,
                    message: '¿Desea actualizar su direcci&oacute;n?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            perfil_row_direccion_guardar.prop('disabled', true);

                            var id_departamento = jQuery('#perfil_lista_departamento');
                            var id_provincia = jQuery('#perfil_lista_provincia');
                            var id_distrito = jQuery('#perfil_lista_distrito');    
                            var des_direccion = jQuery('#perfil_direccion_nuevo');
                            var des_referencia = jQuery('#perfil_referencia_nuevo');

                            var obj = {
                                id_departamento: id_departamento.val(),
                                id_provincia: id_provincia.val(),
                                id_distrito: id_distrito.val(),
                                des_direccion: des_direccion.val(),
                                des_referencia: des_referencia.val()                                
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-direccion',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_direccion_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_direccion_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            perfil_row_direccion_cancelar.off('click');
            perfil_row_direccion_cancelar.on('click', function (evt) {
//                var id_departamento = jQuery('#id_departamento').val();
//                if(isNaN(parseInt(id_departamento))){
//                    id_departamento = 0;
//                }
//                if( parseInt(id_departamento) === 0 ){
//                    jQuery('#perfil_lista_departamento option').eq(0).prop('selected', true);
//                    jQuery('#perfil_lista_provincia option').eq(0).prop('selected', true);
//                    jQuery('#perfil_lista_distrito option').eq(0).prop('selected', true);
//                    jQuery('#perfil_direccion_nuevo').val('');
//                    jQuery('#perfil_referencia_nuevo').val('');
//                }
//                row_cambio_direccion.show();
//                div_row_cambio_direccion.hide();
                limpia_carga(6);
            });

            //***********************************************//
            //== Cambiar Dirección Usuario ----------- FIN ==//
            //***********************************************//

            //**************************************************//
            //== Cambiar Celular Alternativo Usuario - INICIO ==//
            //**************************************************//

            jQuery('#perfil_celular_alternativo_nuevo').filter_input({regex:_strings.app.validate.diccionario_numeros});
            var row_cambio_celular_alt = jQuery('#row_cambio_celular_alternativo');
            var perfil_btn_cambiar_celular_alt = jQuery('#perfil_btn_cambiar_celular_alternativo');
            var perfil_btn_eliminar_celular_alt = jQuery('#perfil_btn_eliminar_celular_alternativo');
            var div_row_cambio_celular_alt = jQuery('#div_row_cambio_celular_alternativo');
            var perfil_row_celular_alt_cancelar = jQuery('#perfil_row_celular_alternativo_cancelar');
            var perfil_row_celular_alt_guardar = jQuery('#perfil_row_celular_alternativo_guardar');

            div_row_cambio_celular_alt.hide();
            perfil_btn_cambiar_celular_alt.off('click');
            perfil_btn_cambiar_celular_alt.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_celular_alt.hide();
                div_row_cambio_celular_alt.show();
            });
            
            perfil_btn_eliminar_celular_alt.off('click');
            perfil_btn_eliminar_celular_alt.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    message: '¿Desea eliminar su otro n&uacute;mero de celular?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {                        
                        if (result) {
                            perfil_btn_eliminar_celular_alt.prop('disabled', true);        
                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-eliminar-celular-alt',
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_btn_eliminar_celular_alt.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_btn_eliminar_celular_alt.prop('disabled', false);
                                }
                            });
                        }
                    }
                });    
            });

            perfil_row_celular_alt_guardar.off('click');
            perfil_row_celular_alt_guardar.on('click', function (evt) {                
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton:false,
                    message: '¿Desea actualizar su otro n&uacute;mero de celular?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            perfil_row_celular_alt_guardar.prop('disabled', true);

                            var celular_alt_old = jQuery('#perfil_celular_alternativo_old');
                            var celular_alt_pri = jQuery('#perfil_celular_alternativo_nuevo');

                            var obj = {
                                celular_alt_old: celular_alt_old.val(),
                                celular_alt_pri: celular_alt_pri.val()
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-celular-alt',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_celular_alt_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_celular_alt_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            perfil_row_celular_alt_cancelar.off('click');
            perfil_row_celular_alt_cancelar.on('click', function (evt) {
//                jQuery('#perfil_celular_alternativo_nuevo').val('');
//                row_cambio_celular_alt.show();
//                div_row_cambio_celular_alt.hide();
                limpia_carga(7);
            });

            //***********************************************//
            //== Cambiar Celular Alternativo Usuario - FIN ==//
            //***********************************************//
            
            //**************************************************//
            //== Cambiar Correo Alternativo Usuario -- INICIO ==//
            //**************************************************//
            
            jQuery('#perfil_correo_alternativo_nuevo').filter_input({regex:_strings.app.validate.diccionario_correo});
            jQuery('#perfil_correo_alternativo_nuevo').on('keypress', function (evt) {
                var code = evt.keyCode || evt.which; 
                if (evt.altKey === true) {
                    if(code !== 64) { 
                        evt.stopImmediatePropagation();
                        return false;
                    }                    
                }
            });
            var row_cambio_correo_alt = jQuery('#row_cambio_correo_alternativo');
            var perfil_btn_cambiar_correo_alt = jQuery('#perfil_btn_cambiar_correo_alternativo');
            var perfil_btn_eliminar_correo_alt = jQuery('#perfil_btn_eliminar_correo_alternativo');
            var div_row_cambio_correo_alt = jQuery('#div_row_cambio_correo_alternativo');
            var perfil_row_correo_alt_cancelar = jQuery('#perfil_row_correo_alternativo_cancelar');
            var perfil_row_correo_alt_guardar = jQuery('#perfil_row_correo_alternativo_guardar');

            div_row_cambio_correo_alt.hide();
            perfil_btn_cambiar_correo_alt.off('click');
            perfil_btn_cambiar_correo_alt.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_correo_alt.hide();
                div_row_cambio_correo_alt.show();
            });
            
            perfil_btn_eliminar_correo_alt.off('click');
            perfil_btn_eliminar_correo_alt.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    message: '¿Desea eliminar su otro correo electr&oacute;nico?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {                        
                        if (result) {
                            perfil_btn_eliminar_correo_alt.prop('disabled', true);        
                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-eliminar-correo-alt',
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_btn_eliminar_correo_alt.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_btn_eliminar_correo_alt.prop('disabled', false);
                                }
                            });
                        }
                    }
                });    
            });

            perfil_row_correo_alt_guardar.off('click');
            perfil_row_correo_alt_guardar.on('click', function (evt) {   
                
                var perfil_correo_alternativo_old = jQuery('#perfil_correo_alternativo_old');
                var perfil_correo_alternativo_nuevo = jQuery('#perfil_correo_alternativo_nuevo');

                if(perfil_correo_alternativo_old.val() === perfil_correo_alternativo_nuevo.val()){
                   jQuery_mensaje('El correo electrónico debe ser distinto al actual.');
                   return false;
                }
                
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton:false,
                    message: '¿Desea actualizar su otro correo electr&oacute;nico?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            perfil_row_correo_alt_guardar.prop('disabled', true);

                            var correo_alt_old = jQuery('#perfil_correo_alternativo_old');
                            var correo_alt_pri = jQuery('#perfil_correo_alternativo_nuevo');

                            var obj = {
                                correo_alt_old: correo_alt_old.val(),
                                correo_alt_pri: correo_alt_pri.val()
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-correo-alt',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_correo_alt_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_correo_alt_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            perfil_row_correo_alt_cancelar.off('click');
            perfil_row_correo_alt_cancelar.on('click', function (evt) {
//                jQuery('#perfil_correo_alternativo_nuevo').val('');
//                row_cambio_correo_alt.show();
//                div_row_cambio_correo_alt.hide();
                limpia_carga(8);
            });
            
            //**************************************************//
            //== Cambiar Correo Alternativo Usuario ----- FIN ==//
            //**************************************************//
            
            //**************************************************//
            //== Cambiar Contacto Referencia Usuario - INICIO ==//
            //**************************************************//
            
            jQuery('#perfil_nombre_referencia_nuevo').filter_input({regex:_strings.app.validate.diccionario_nombres});
            jQuery('#perfil_nombre_referencia_nuevo').on('keypress', function (evt) {
                if (evt.altKey === true) {
                    evt.stopImmediatePropagation();
                    return false;
                }
            });
            jQuery('#perfil_celular_referencia_nuevo').filter_input({regex:_strings.app.validate.diccionario_numeros});
            jQuery('#perfil_telefono_referencia_nuevo').filter_input({regex:_strings.app.validate.diccionario_numeros});
            var row_cambio_contacto_ref = jQuery('#row_cambio_contacto_referencia');
            var perfil_btn_cambiar_contacto_ref = jQuery('#perfil_btn_cambiar_contacto_referencia');
            var perfil_btn_eliminar_contacto_ref = jQuery('#perfil_btn_eliminar_contacto_referencia');
            var div_row_cambio_contacto_ref = jQuery('#div_row_cambio_contacto_referencia');
            var perfil_row_contacto_ref_cancelar = jQuery('#perfil_row_contacto_referencia_cancelar');
            var perfil_row_contacto_ref_guardar = jQuery('#perfil_row_contacto_referencia_guardar');

            div_row_cambio_contacto_ref.hide();
            perfil_btn_cambiar_contacto_ref.off('click');
            perfil_btn_cambiar_contacto_ref.on('click', function (evt) {
                evt.preventDefault();
                limpia_carga(0);
                row_cambio_contacto_ref.hide();
                div_row_cambio_contacto_ref.show();
                
                var cod_nro_telefono = jQuery('#cod_nro_telefono').val();
                var nro_telefono = jQuery('#nro_telefono').val();
                jQuery('#perfil_lista_codigo_ciudad_referencia').val(parseInt(cod_nro_telefono));
                jQuery('#perfil_telefono_referencia_nuevo').val(nro_telefono);
            });
            
            perfil_btn_eliminar_contacto_ref.off('click');
            perfil_btn_eliminar_contacto_ref.on('click', function (evt) {
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    message: '¿Desea eliminar los datos del contacto de referencia?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {                        
                        if (result) {
                            perfil_btn_eliminar_contacto_ref.prop('disabled', true);        
                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-eliminar-contacto-ref',
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_btn_eliminar_contacto_ref.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_btn_eliminar_contacto_ref.prop('disabled', false);
                                }
                            });
                        }
                    }
                });    
            });

            perfil_row_contacto_ref_guardar.off('click');
            perfil_row_contacto_ref_guardar.on('click', function (evt) {                   
                bootbox.confirm({
                    title: _strings.app.confirmacion.titulo,
                    closeButton:false,
                    message: '¿Desea actualizar los datos del contacto de referencia?',
                    buttons: {
                        'cancel': {
                            label: _strings.app.confirmacion.opc_cancel,
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: _strings.app.confirmacion.opc_confirm,
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            perfil_row_contacto_ref_guardar.prop('disabled', true);

                            var nombre_ref_pri = jQuery('#perfil_nombre_referencia_nuevo');
                            var celular_ref_pri = jQuery('#perfil_celular_referencia_nuevo');
                            var codigo_telefono_ref = jQuery('#perfil_lista_codigo_ciudad_referencia');
                            var telefono_ref = jQuery('#perfil_telefono_referencia_nuevo'); 
                            
                            var obj = {
                                nombre_ref_pri: nombre_ref_pri.val(),
                                celular_ref_pri: celular_ref_pri.val(),
                                codigo_telefono_ref: codigo_telefono_ref.val(),
                                telefono_ref: telefono_ref.val()                                
                            };
                            
                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-contacto-ref',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_contacto_ref_guardar.prop('disabled', false);
                                                    if(_id > 0){
                                                        load_menu_datos_usuario();
                                                    }
                                            }
                                        }
                                    });
                                    perfil_row_contacto_ref_guardar.prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            perfil_row_contacto_ref_cancelar.off('click');
            perfil_row_contacto_ref_cancelar.on('click', function (evt) {
//                var id_contacto_nombre = jQuery('#id_contacto_nombre').val();
//                if(isNaN(parseInt(id_contacto_nombre))){
//                    id_contacto_nombre = 0;
//                }
//                if( parseInt(id_contacto_nombre) === 0 ){
//                    jQuery('#perfil_nombre_referencia_nuevo').val('');
//                    jQuery('#perfil_celular_referencia_nuevo').val('');
//                    jQuery('#perfil_lista_codigo_ciudad_referencia option').eq(0).prop('selected', true);
//                    jQuery('#perfil_telefono_referencia_nuevo').val('');
//                }
//                row_cambio_contacto_ref.show();
//                div_row_cambio_contacto_ref.hide();
                limpia_carga(9);
            });

            //**************************************************//
            //== Cambiar Nombre Referencia Usuario ------ FIN ==//
            //**************************************************//

        }
    });
}

function jQuery_mensaje(mensaje) {
    bootbox.dialog({
        message: mensaje,
        title: "Mensaje del sistema",
        buttons: {
            success: {
                label: "Aceptar",
                className: "btn-aceptar",
            }
        }
    });
}
