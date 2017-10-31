function menu_inicio() {
    load_inicio();

    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#mnuInicio').addClass('clicked');

}

function menu_reportes() {
    load_reportes();

    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#menuReportes').addClass('clicked');
}


function load_inicio() {
    BaseX.load_html(root + '/seleccion/index/inicio', {
        data: {},
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
        }
    });
}
function load_reportes() {
    BaseX.load_html(root + '/seleccion/index/reportes', {
        data: {},
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
            var data = preguntas_fec_listar();
            var source = jQuery("#tpl_preguntas_frecuentes_content").html();
            var template = Handlebars.compile(source);
            jQuery('#content_out').html(template(data));
        }
    });
}