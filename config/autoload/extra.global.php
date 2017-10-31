<?php

return array(
    'config_extra' => array(
        'date_config' => array(
            'format' => 'Y-m-d'
        ),
        'captcha' => array(
            'enabled' => false,
            'diccionario' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'tamano_codigo' => 5
        ),
        'email' => array(
            'enabled' => true
        ),
        'param_sistema' => array(
            'etapa' => 1,
            'max_caracter_seleccion' => 2,
            'max_seleccion' => 20,
            'envio_de_correos' => 1,
            'bcc' => 0,
            'mostrar_encuesta' => 0,
            'ruta_encuesta' => '',
        ),
		
        'path_files' => [
            'pdf' => getcwd() .  '/public/pdf_seleccion_plazas',
            'pdf_registro' => getcwd() . '/public/pdf_registro_trayectoria',            
            'pdf_declaracion_jurada_trayectoria' => getcwd() .'/public/pdf_declaracion_jurada_trayectoria',
        ],
		
        'multirole' => [    // Se activa solo si se va usar varios roles (acl)
            'enabled' => 0
        ]
    ),
    'apigility' => array(
        'url' => 'http://localhost/ws/died_ws2/public', // Url de Apigility
        'acl' => 21, //  Código de ACL
        'app_id' => 2, //  Código de App
        'template_email_id' => 1, //  ID del Template de Email
    ),
);
