function refresh_tpl(){
    var template = Handlebars.compile(tpl_listado_formato_content);
    jQuery('#content_out').html(template(rp)); 
}

function refresh_tpl_opt(g,i){
    //Renderizando valores editables
    var template = Handlebars.compile(tpl_edit_input);
    jQuery('#content_edit_input').html(template(rp.c[g].c[i])); 
}

function alert_question(i){
    if(i > 0){
        jQuery("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
            jQuery("#success-alert").slideUp(500);
        });
    }else{
        jQuery("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
            jQuery("#danger-alert").slideUp(500);
        });
    }
}

function delete_question_(g,i){
    //Eliminar elemento de arreglo de valores
    rp.c[g].c.splice(i, 1);
    //Ordenar nuevamente los índices
    $.each(rp.c, function (index, value) {
        rp.c[index].c = order_array_principal_wg(rp.c[index].c,(index-1),index);
    });
    //Refrescar handlebars content
    refresh_tpl();
}

function add_question_option_(){
    //Recorrer id de pregunta 
    var id_group = jQuery('#hdd_g_question').val();
    var id_question = jQuery('#hdd_i_question').val();
    var o = rp.c[id_group].c[id_question].values.length;
    var obj = {a:o,b:"default"};
    //Ordenar valores y guardar
    var temp = order_array(rp.c[id_group].c[id_question].values);
    rp.c[id_group].c[id_question].values = temp;
    //agregar nuevo valor
    rp.c[id_group].c[id_question].values.push(obj);
    refresh_tpl_opt(id_group,id_question);
}

function delete_question_val_(i){
    //Recorrer id de pregunta 
    var a = parseInt(jQuery('#hdd_g_question').val());
    var b = parseInt(jQuery('#hdd_i_question').val());
    var c = parseInt(i);
    //Eliminar elemento de arreglo de valores
    rp.c[a].c[b].values.splice(c, 1);
    //Ordenar nuevamente los índices
    var arr_temp = [];
    $.each(rp.c[a].c[b].values, function (index, value) {
        var obj = {a: index, b: value.b};
        arr_temp.push(obj); 
    });
    rp.c[a].c[b].values = arr_temp;
    //Refrescar handlebars content
    refresh_tpl_opt(a,b);
}

function order_array(arreglo){
    var arr_temp = [];
    //Recorrer arreglo y ordenarlo
    $.each(arreglo, function (index, value) {
        var obj = {a: index, b: $("#frm_edit_opt_"+index).val()};
        arr_temp.push(obj); 
    });
    //Retorna arreglo ordenado
    return arr_temp;
}

function order_array_principal_wg(arreglo,i,o){
    var tamanio = 0;
    if(i < 0){
        i = 0;
    }else{
        if(i>=1){
            for (e = 0; e <= i; e++) { 
                tamanio += rp.c[e].c.length;
            }
        }else{
            tamanio = rp.c[i].c.length;
        }
    }

    var arr_temp = [];
    //Recorrer arreglo y ordenarlo

    $.each(arreglo, function (index, value) {
        var obj = value;
        obj.order = (index+1+tamanio);
        obj.id = index;
        arr_temp.push(obj); 
    });
    //Retorna arreglo ordenado
    return arr_temp;
}

function order_array_principal(arreglo){
    var arr_temp = [];
    //Recorrer arreglo y ordenarlo
    $.each(arreglo, function (index, value) {
        var obj = value;
        obj.order = (index+1);
        arr_temp.push(obj); 
    });
    //Retorna arreglo ordenado
    return arr_temp;
}

function edit_question_(g,i){
        //g:group
        //i:indice
         jQuery('#modal_edit_tmp').modal({
            keyboard: false,
            backdrop: 'static'
        });
        //Modal de edición de preguntas
        var modal_preguntas = jQuery('#modal_edit_tmp');
        modal_preguntas.on('shown.bs.modal', function (e) {
            jQuery(this).off('shown.bs.modal');

            jQuery('#hdd_g_question').val(g);
            jQuery('#hdd_i_question').val(i);
            jQuery('#frm_edit_question').val(rp.c[g].c[i].question);

//            var source = jQuery("#tpl_edit_input").html();
            var template = Handlebars.compile(tpl_edit_input);
            jQuery('#content_edit_input').html(template(rp.c[g].c[i])); 
            refresh_tpl_opt(g,i);
            var edit_question_jq = jQuery('#save_question');
            edit_question_jq.off('click');
            edit_question_jq.on('click', function () {
                rp.c[g].c[i].question = jQuery('#frm_edit_question').val();

                //en caso del tipo slider setear sus valores 
                if(rp.c[g].c[i].type === "slider"){
                    rp.c[g].c[i].options.min =   jQuery('#frm_edit_a').val();  
                    rp.c[g].c[i].options.max =   jQuery('#frm_edit_b').val();  
                }

                if(rp.c[g].c[i].type === "text" || rp.c[g].c[i].type === "textarea"){
                    rp.c[g].c[i].options.maxlenght =   jQuery('#frm_edit_a').val();
                }
                
                var temp = order_array(rp.c[g].c[i].values);
                rp.c[g].c[i].values = temp;
                modal_preguntas.modal('hide');

                $.each(rp.c, function (index, value) {
                    rp.c[index].c = order_array_principal_wg(rp.c[index].c,(index-1),index);
                });
                alert_question(1);
                refresh_tpl();
                
            });

        });
    }

