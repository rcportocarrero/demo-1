function refresh_login()
{
    if (jQuery('#ref_login').val() !== undefined) {
        document.getElementById('ref_login').src        = root + "/usuario/captcha/logincaptcha?rnd=" + Math.random();
    }
}

function refresh_registro()
{
    if (jQuery('#ref_registro').val() !== undefined) {
        document.getElementById('ref_registro').src     = root + "/usuario/captcha/registrocaptcha?rnd=" + Math.random();
    }
}

function refresh_recuperar()
{
    if (jQuery('#ref_recuperar').val() !== undefined) {
        document.getElementById('ref_recuperar').src    = root + "/usuario/captcha/clavecaptcha?rnd=" + Math.random();
    }
}

function refresh_cambiar()
{
    if (jQuery('#ref_cambiar').val() !== undefined) {
        document.getElementById('ref_cambiar').src      = root + "/usuario/captcha/clavecaptcha?rnd=" + Math.random();
    }
}