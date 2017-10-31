jQuery(document).on('ready', function () {
//    jQuery('#usuario_sec_captcha_login').filter_input({regex: '[a-zA-Z]'});
//    jQuery('#usuario_sec_documento').filter_input({regex: '[0-9]'});
//    jQuery('#usuario_sec_documento').filter_input({regex: '[a-zA-Z]'});
//    jQuery('#usuario_sec_frm_login').validate({
//        rules: {
//            usuario_sec_documento: {
//                required: true, 
////                number: true, 
////                maxlength: max_dni,
////                minlength: min_dni
//            },
//            usuario_sec_password: {
//                required: true, 
////                maxlength: max_clave
//            },
//            usuario_sec_captcha_login: {
//                required: true, 
//                minlength: 3, 
//                number: false, 
//            }
//        },
//        messages: {
//            usuario_sec_documento: {
////                number: _strings.app.validate.only_numbers,
//                required: _strings.app.validate.required,
////                maxlength: "No puede ingresar mas de " + max_dni + " dígitos.",
////                minlength: "Debe ingresar " + min_dni + " números como mínimo.",
//            },
//            usuario_sec_password: {
//                required: _strings.app.validate.required,
////                maxlength: "No puede ingresar mas de " + max_clave + " caracteres."
//            },
//            usuario_sec_captcha_login: {
//                required: _strings.app.validate.required,
//                minlength: "Debe ingresar los 3 caracteres de la imagen.",
//                number: _strings.app.validate.only_letters
//            }
//        },
//        debug: true,
//        submitHandler: function (form) {
//        }
//    });
jQuery('[data-toggle="tooltip"]').tooltip();
    /*Evento onclick button login*/
    jQuery("#usuario_sec_login").click(function () {
        var form = jQuery("#usuario_sec_frm_login");
        if (form.valid()) {
            document.getElementById("usuario_sec_frm_login").submit();
        }
    });

    
});
