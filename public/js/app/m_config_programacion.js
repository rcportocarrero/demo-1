function menu_config_programacion() {
    load_config_programacion();
    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#menuConfigProgramacion').addClass('clicked');
}

function load_config_programacion(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    if (valida_sesion() === false) {
        return;
    }

    BaseX.load_html(root + '/seleccion/configuracion/config_programacion_instrumentos', {
        data: ops,
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
            jQuery("#datetimepicker1").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
            jQuery("#datetimepicker2").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        }
    });
}
