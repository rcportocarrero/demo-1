var _config = {
    url_valida_sesion: root + '/usuario/sesion/valida',
    url_logout: root + '/usuario/sec/logout'
};

jQuery(document).on('ready', function () {
    menu();
    jQuery("#btn_change_password").click(function () {
        modal_change_password();
    });
    function modal_change_password() {
        jQuery('#modal_chg_password').modal({
            keyboard: false,
            backdrop: 'static'
        });

        var modal_preguntas = jQuery('#modal_chg_password');
        modal_preguntas.on('shown.bs.modal', function (e) {
            jQuery(this).off('shown.bs.modal');
            document.getElementById("frm_change_password").reset();
            jQuery("#btn_change_password_save").off('click');
            jQuery("#btn_change_password_save").click(function (e) {

                BaseX.post({
                    url: root + '/usuario/sec/change_password',
                    data: {
                        'pass_current': jQuery('#pass_current').val(),
                        'pass_new': jQuery('#pass_new').val(),
                        'pass_confirmation': jQuery('#pass_confirmation').val(),
                    },
                    success: function (xhr, txt) {
                        bootbox.dialog({
                            message: xhr.msg,
                            title: "Mensaje del sistema",
                            closeButton: false,
                            buttons: {
                                success: {
                                    label: "Aceptar",
                                    className: "btn-default",
                                    callback: function () {

                                    }
                                }
                            }
                        });

                        jQuery('#modal_chg_password').modal('hide');
                    }
                });
            });
        });

    }
});

function valida_sesion(vfs) {
    var rp = true;
    jQuery.ajaxSetup({
        cache: false,
        async: false
    });
    jQuery.ajax({
        url: _config.url_valida_sesion,
        data: {
            neko: jQuery('#hdn_sesion_token').val(),
            vfs: vfs,
        },
        method: 'post',
        success: function (xhr, txt) {
            rp = xhr.activo;
            if (rp === false) {
                bootbox.dialog({
                    message: _strings.app.msg_sistema_termino_sesion,
                    title: "Mensaje del sistema",
                    closeButton: false,
                    buttons: {
                        success: {
                            label: "Aceptar",
                            className: "btn-aceptar",
                            callback: function () {
                                document.location = _config.url_logout;
                            }
                        }
                    }
                });
            }

            if (vfs === 1) {
                if (rp === true) {
                    if (xhr.ini === true) {
                        if (xhr.fin === true) {
                            load_procfin();
                            rp = false;
                            return rp;
                        }
                    } else {
                        load_procini();
                        rp = false;
                        return rp;
                    }
                }
            }

        }
    });

    return rp;
}

function load_procini(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    BaseX.load_html(root + '/inicio/index/procini', {
        data: ops,
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
        }
    });
}

function load_procfin(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    BaseX.load_html(root + '/inicio/index/procfin', {
        data: ops,
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
        }
    });
}