<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return [
    'usuario' => [
        'registro' => [
            'enabled' => false, //Activar link de registro de usuarios
            'modal' => false, //Activar modal de ayuda
        ],
        'dashboard' => [
            'opc_msje_app' => true
        ],
        'general' => [
            'ultimas_claves' => 1,
            'nro_intentos_codval_cel' => 2,
            'diccionario_clave' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890',
            'diccionario_correo' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890.-_@',
            'templates' => [
                'template_recuperacion_id' => 11, //  ID del Template de Recuperar Contraseña por Correo (envia link)
                'template_cambio_clave_id' => 12, //  ID del Template de Confirmacion de Cambio de Contraseña
                'template_cambio_correo_id' => 14, //  ID del Template de Cambio de Correo
            ],
            'concurso' => array(
                'id_concurso' => 9, // Concurso
                'id_etapa_actividad' => 15, //  Etapa de Actividad 
            ),
        ],
        'path_files' => [
            'pdf'                   => getcwd() . '/public/pdf', // enlace simbólico    
            'date_format'           => 'YmdHis',
        ],
        'recuperacion' => [
            'opciones_recuperar' => [
                'correo' => true,
                'sms' => true
            ],
        ],        
        'menu_perfil' => [
            'datos_personales'      =>  [
                'cambio_correo'         =>  true,       //Permite visualizar el botón de Cambio de correo eletrónico
                'cambio_contrasenia'    =>  true,       //Permite visualizar el botón de Cambio de contraseña
                'cambio_celular'        =>  true,       //Permite visualizar el botón de Cambio de número de celular
                'cambio_num_fijo'       =>  true,       //Permite visualizar el botón de Cambio de número de teléfono fijo
            ],
            'info_adicional'        =>  [
                'cambio_direccion'      =>  true,       //Permite visualizar el botón opción de Cambio de dirección para Información Adicional
                'cambio_otro_celular'   =>  true,       //Permite visualizar el botón de Cambio de otro celular para Información Adicional
                'cambio_otro_correo'    =>  true,       //Permite visualizar el botón de Cambio de correo electrónico para Información Adicional
            ],
            'contacto_referencia'   =>  true,           //Permite visualizar el botón de Cambio de datos para el Contacto de referencia
            'flags_ws'              =>  [
                'correo_conf_cambio_clave'    => true,  //Permite enviar mediante el servicio web un correo de confirmación de cambio de contraseña
            ]
        ],
        'num_caracteres'    => [
            'captcha' => [
                    'enabled'       =>  true,
                    'diccionario'   =>  'ABX',             //Letras permitidas en el captcha 'ABCDEFGHIJKLMNPQRSTUVWXYZ',
                    'tamano_codigo' =>  3                //Número de caracteres permitidos para el captcha
            ],
            'documento_identidad'       =>  [
                    'min'                   =>  8,      //Número mínimo de caracteres permitidos para el documento de identidad
                    'max'                    =>  13,     //Número máximo de caracteres permitidos para el documento de identidad
            ],
            'contrasenias'          =>      [
                    'min'                   =>  9,      //Número mínimo de caracteres permitidos para una contraseña
                    'max'                   =>  10,     //Número máximo de caracteres permitidos para una contraseña
            ],
            'general'                       =>  [
                    'nombre_contacto'       =>   [
                            'min'   =>  2,              //Número mínimo de caracteres para el nombre y apellidos de contacto
                            'max'   =>  150,            //Número máximo de caracteres para el nombre y apellidos de contacto
                    ],
                    'correo'                =>  150,    //Número de caracteres para un correo electrónico
                    'celular'               =>  9,      //Número de caracteres para un número de celular
                    'telefono_fijo'         =>  [
                            'min'   =>  6,              //Número de caracteres mínimo para un teléfono fijo
                            'max'   =>  7,              //Número de caracteres máximo para un teléfono fijo
                    ],
                    'direccion_domicilio'   =>  [
                            'min'   =>  5,              //Número de caracteres mínimo para una dirección de domicilio
                            'max'   =>  250,              //Número de caracteres máximo para una dirección de domicilio
                    ],
                    'token_sms'             =>  6       //Número de caracteres para códigos de validación por SMS
            ],            
        ]
    ],

   
];
