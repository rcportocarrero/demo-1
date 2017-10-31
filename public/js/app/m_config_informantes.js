function menu_config_informantes() {
    load_config_informantes();
    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#menuConfigInformantes').addClass('clicked');
}

function load_config_informantes(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    if (valida_sesion() === false) {
        return;
    }

    BaseX.load_html(root + '/seleccion/configuracion/config_informantes', {
        data: ops,
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
        }
    });
}
