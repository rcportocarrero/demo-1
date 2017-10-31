function menu() {
    menu_config_instrumentos();
    var left_menu = jQuery('.left_menu li');
    left_menu.off('click').on('click', function (evt) {
        var _this = jQuery(this);        
        var _function_handle = _this.data('function-handle');
        eval(_function_handle + '()');      
    });
}
